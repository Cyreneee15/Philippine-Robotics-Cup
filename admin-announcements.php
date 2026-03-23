<?php
session_start();

// ── DB CONNECTION ──────────────────────────────────────────────
$db_host = 'localhost';
$db_user = 'root';
$db_pass = '';
$db_name = 'prc_db';

$conn = new mysqli($db_host, $db_user, $db_pass, $db_name);
if ($conn->connect_error) {
    die('<p style="color:#ff6b6b;padding:40px;font-family:monospace;">Database connection failed: ' . htmlspecialchars($conn->connect_error) . '</p>');
}

// ── UPLOAD CONFIG ──────────────────────────────────────────────
define('UPLOAD_DIR', __DIR__ . '/assets/announcements/');
define('UPLOAD_URL', 'assets/announcements/');
define('MAX_FILE_SIZE', 50 * 1024 * 1024); // 50 MB
define('ALLOWED_IMAGE_TYPES', ['image/jpeg','image/png','image/gif','image/webp']);
define('ALLOWED_VIDEO_TYPES', ['video/mp4','video/webm','video/ogg']);

if (!is_dir(UPLOAD_DIR)) {
    mkdir(UPLOAD_DIR, 0755, true);
}

// ── FLASH MESSAGE HELPER ──────────────────────────────────────
function set_flash($type, $msg) {
    $_SESSION['flash'] = ['type' => $type, 'msg' => $msg];
}
function get_flash() {
    if (!empty($_SESSION['flash'])) {
        $f = $_SESSION['flash'];
        unset($_SESSION['flash']);
        return $f;
    }
    return null;
}

// ── HANDLE POST ACTIONS ───────────────────────────────────────
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';

    // ── CREATE ANNOUNCEMENT ──
    if ($action === 'create') {
        $title   = trim($_POST['announcement_title'] ?? '');
        $caption = trim($_POST['announcement_caption'] ?? '');
        $pinned  = isset($_POST['announcement_is_pinned']) ? 1 : 0;

        if ($title === '') {
            set_flash('error', 'Title is required.');
        } else {
            $stmt = $conn->prepare(
                "INSERT INTO prc_announcements
                    (announcement_title, announcement_caption, announcement_is_pinned, announcement_date_posted)
                 VALUES (?, ?, ?, NOW())"
            );
            $stmt->bind_param('ssi', $title, $caption, $pinned);
            if ($stmt->execute()) {
                $new_id = $stmt->insert_id;
                // ── handle media uploads ──
                if (!empty($_FILES['announcement_media']['name'][0])) {
                    $files = $_FILES['announcement_media'];
                    $count = count($files['name']);
                    for ($i = 0; $i < $count; $i++) {
                        if ($files['error'][$i] !== UPLOAD_ERR_OK) continue;
                        $tmp   = $files['tmp_name'][$i];
                        $mime  = mime_content_type($tmp);
                        $is_img = in_array($mime, ALLOWED_IMAGE_TYPES);
                        $is_vid = in_array($mime, ALLOWED_VIDEO_TYPES);
                        if (!$is_img && !$is_vid) continue;
                        if ($files['size'][$i] > MAX_FILE_SIZE) continue;
                        $ext  = pathinfo($files['name'][$i], PATHINFO_EXTENSION);
                        $safe = 'prc_ann_' . $new_id . '_' . time() . '_' . $i . '.' . strtolower($ext);
                        $dest = UPLOAD_DIR . $safe;
                        if (move_uploaded_file($tmp, $dest)) {
                            $path = UPLOAD_URL . $safe;
                            $type = $is_vid ? 'video' : 'image';
                            $sort = $i;
                            $ms = $conn->prepare(
                                "INSERT INTO prc_announcement_media
                                    (announcement_id, media_file_path, media_type, media_sort_order)
                                 VALUES (?, ?, ?, ?)"
                            );
                            $ms->bind_param('issi', $new_id, $path, $type, $sort);
                            $ms->execute();
                            $ms->close();
                        }
                    }
                }
                set_flash('success', 'Announcement published successfully.');
            } else {
                set_flash('error', 'Failed to save announcement: ' . htmlspecialchars($conn->error));
            }
            $stmt->close();
        }
        header('Location: admin-announcements.php');
        exit;
    }

    // ── DELETE ANNOUNCEMENT ──
    if ($action === 'delete') {
        $del_id = (int)($_POST['announcement_id'] ?? 0);
        if ($del_id > 0) {
            // get media files first
            $mres = $conn->query("SELECT media_file_path FROM prc_announcement_media WHERE announcement_id = $del_id");
            if ($mres) {
                while ($mrow = $mres->fetch_assoc()) {
                    $fp = __DIR__ . '/' . $mrow['media_file_path'];
                    if (file_exists($fp)) @unlink($fp);
                }
            }
            $conn->query("DELETE FROM prc_announcement_media WHERE announcement_id = $del_id");
            $conn->query("DELETE FROM prc_announcements WHERE announcement_id = $del_id");
            set_flash('success', 'Announcement deleted.');
        }
        header('Location: admin-announcements.php');
        exit;
    }

    // ── TOGGLE PIN ──
    if ($action === 'toggle_pin') {
        $pin_id  = (int)($_POST['announcement_id'] ?? 0);
        $new_pin = (int)($_POST['new_pin_state'] ?? 0);
        if ($pin_id > 0) {
            $stmt = $conn->prepare("UPDATE prc_announcements SET announcement_is_pinned = ? WHERE announcement_id = ?");
            $stmt->bind_param('ii', $new_pin, $pin_id);
            $stmt->execute();
            $stmt->close();
            set_flash('success', $new_pin ? 'Announcement pinned.' : 'Announcement unpinned.');
        }
        header('Location: admin-announcements.php');
        exit;
    }

    // ── DELETE SINGLE MEDIA ──
    if ($action === 'delete_media') {
        $media_id = (int)($_POST['media_id'] ?? 0);
        if ($media_id > 0) {
            $mres = $conn->query("SELECT media_file_path FROM prc_announcement_media WHERE media_id = $media_id");
            if ($mrow = $mres->fetch_assoc()) {
                $fp = __DIR__ . '/' . $mrow['media_file_path'];
                if (file_exists($fp)) @unlink($fp);
            }
            $conn->query("DELETE FROM prc_announcement_media WHERE media_id = $media_id");
            set_flash('success', 'Media removed.');
        }
        header('Location: admin-announcements.php');
        exit;
    }
}

// ── FETCH ALL ANNOUNCEMENTS FOR LISTING ──────────────────────
$list_sql = "
    SELECT
        a.announcement_id,
        a.announcement_title,
        a.announcement_caption,
        a.announcement_date_posted,
        a.announcement_is_pinned,
        COUNT(m.media_id) AS media_count
    FROM prc_announcements a
    LEFT JOIN prc_announcement_media m ON a.announcement_id = m.announcement_id
    GROUP BY a.announcement_id
    ORDER BY a.announcement_is_pinned DESC, a.announcement_date_posted DESC
";
$list_result = $conn->query($list_sql);
$all_posts = [];
if ($list_result) {
    while ($r = $list_result->fetch_assoc()) $all_posts[] = $r;
}
$conn->close();

$flash = get_flash();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <meta name="robots" content="noindex, nofollow" />
  <title>ADMIN — Announcements | Philippine Robotics Cup</title>

  <link rel="icon" type="image/png" href="assets/favicon.png" />
  <link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@400;600;700;800;900&family=Exo+2:wght@300;400;500;600;700&display=swap" rel="stylesheet"/>
  <link rel='stylesheet' href='https://cdn-uicons.flaticon.com/uicons-regular-rounded/css/uicons-regular-rounded.css'>
  <link rel='stylesheet' href='https://cdn-uicons.flaticon.com/uicons-solid-rounded/css/uicons-solid-rounded.css'>
  <link rel='stylesheet' href='https://cdn-uicons.flaticon.com/uicons-brands/css/uicons-brands.css'>

  <style>
    /* ── ROOT / SHARED THEME ── */
    :root {
      --prc-violet:   #8B7EFF;
      --prc-ice:      #C4EEFF;
      --creo-purple:  #7733FF;
      --creo-amber:   #FFA030;
      --creo-volt:    #FFE930;
      --creo-sky:     #44D9FF;
      --admin-red:    #FF4D6A;
      --admin-green:  #44FF88;
      --bg-void:      #03020D;
      --bg-deep:      #06051A;
      --bg-card:      #0A0918;
      --border-neon:  rgba(139,126,255,0.22);
      --glow-primary: 0 0 18px rgba(139,126,255,0.60), 0 0 55px rgba(139,126,255,0.20);
      --text-high:    #F2EEFF;
      --text-mid:     #C8C0F0;
      --text-soft:    #9A90CC;
      --text-dim:     #7068A8;
      --nav-height:   72px;
      --font-hud:     'Orbitron', monospace;
      --font-body:    'Exo 2', sans-serif;
    }

    *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
    html { scroll-behavior: smooth; }
    body {
      font-family: var(--font-body);
      background: var(--bg-void);
      color: var(--text-high);
      overflow-x: hidden;
      line-height: 1.6;
      cursor: none;
    }
    img { max-width: 100%; display: block; }
    a { text-decoration: none; color: inherit; }
    ul { list-style: none; }
    button { font-family: inherit; cursor: none; border: none; background: none; }

    /* ── CURSOR ── */
    .cursor-dot {
      position: fixed; width: 8px; height: 8px; border-radius: 50%;
      background: var(--admin-red); pointer-events: none; z-index: 99999;
      transform: translate(-50%,-50%);
      box-shadow: 0 0 18px rgba(255,77,106,0.80);
      transition: transform 0.1s, background 0.2s;
    }
    .cursor-ring {
      position: fixed; width: 36px; height: 36px; border-radius: 50%;
      border: 1px solid rgba(255,77,106,0.60); pointer-events: none; z-index: 99998;
      transform: translate(-50%,-50%);
      transition: width 0.25s, height 0.25s, border-color 0.25s, transform 0.08s;
    }
    .cursor-ring.hovered { width: 52px; height: 52px; border-color: var(--creo-amber); }

    /* ── SCANLINES ── */
    body::after {
      content: ''; position: fixed; inset: 0; z-index: 9998; pointer-events: none;
      background: repeating-linear-gradient(to bottom, transparent, transparent 2px, rgba(0,0,0,0.04) 2px, rgba(0,0,0,0.04) 4px);
    }

    /* ── HEX GRID ── */
    .hex-grid {
      position: fixed; inset: 0; z-index: 0; pointer-events: none;
      background-image: linear-gradient(rgba(255,77,106,0.03) 1px, transparent 1px), linear-gradient(90deg, rgba(255,77,106,0.03) 1px, transparent 1px);
      background-size: 50px 50px;
    }
    .hex-grid::before {
      content: ''; position: absolute; inset: 0;
      background: radial-gradient(ellipse 70% 60% at 50% 0%, rgba(255,77,106,0.08) 0%, transparent 70%),
                  radial-gradient(ellipse 50% 50% at 100% 100%, rgba(139,126,255,0.06) 0%, transparent 60%);
    }

    /* ── ANIMATIONS ── */
    @keyframes neonPulse { 0%,100%{opacity:1} 50%{opacity:0.7} }
    @keyframes slideDown { from{opacity:0;transform:translateY(-16px)} to{opacity:1;transform:translateY(0)} }
    @keyframes slideIn   { from{opacity:0;transform:translateY(20px)} to{opacity:1;transform:translateY(0)} }
    @keyframes scanDown  { from{transform:translateY(-100%)} to{transform:translateY(100vh)} }

    /* ── NAV — ADMIN VARIANT ── */
    #main-nav {
      position: fixed; top: 0; left: 0; right: 0;
      height: var(--nav-height); z-index: 1000;
      background: rgba(6,2,10,0.97);
      backdrop-filter: blur(20px);
      border-bottom: 1px solid rgba(255,77,106,0.30);
      box-shadow: 0 0 30px rgba(255,77,106,0.10);
    }
    .nav-inner {
      max-width: 1340px; margin: 0 auto; height: 100%; padding: 0 36px;
      display: flex; align-items: center; justify-content: space-between; gap: 16px;
    }
    .nav-logo { display: flex; align-items: center; gap: 12px; flex-shrink: 0; }
    .nav-logo img { height: 38px; width: auto; transition: filter 0.3s; }
    .nav-logo:hover img { filter: drop-shadow(0 0 14px rgba(255,77,106,0.60)); }

    /* Brand block with ADMIN badge */
    .nav-brand-wrap { display: flex; flex-direction: column; gap: 2px; }
    .nav-brand-top { display: flex; align-items: center; gap: 8px; }
    .nav-brand-name {
      font-family: var(--font-hud); font-weight: 700; font-size: 0.72rem;
      letter-spacing: 0.06em; color: var(--prc-violet);
      text-shadow: 0 0 12px rgba(139,126,255,0.65);
    }
    .nav-admin-badge {
      font-family: var(--font-hud); font-size: 0.60rem; font-weight: 900;
      letter-spacing: 0.18em; text-transform: uppercase;
      color: var(--admin-red);
      text-shadow: 0 0 14px rgba(255,77,106,0.90), 0 0 40px rgba(255,77,106,0.40);
      border: 1.5px solid rgba(255,77,106,0.55);
      padding: 2px 9px;
      background: rgba(255,77,106,0.08);
      clip-path: polygon(4px 0%, 100% 0%, calc(100% - 4px) 100%, 0% 100%);
      animation: neonPulse 2.5s ease-in-out infinite;
    }
    .nav-brand-sub {
      font-family: var(--font-hud); font-size: 0.54rem; font-weight: 400;
      letter-spacing: 0.10em; text-transform: uppercase; color: var(--text-dim);
    }

    .nav-links { display: flex; align-items: center; gap: 2px; }
    .nav-links a {
      font-family: var(--font-hud); font-size: 0.65rem; font-weight: 600;
      color: var(--text-mid); padding: 8px 20px;
      letter-spacing: 0.08em; text-transform: uppercase;
      border: 1px solid transparent; transition: all 0.2s; white-space: nowrap;
    }
    .nav-links a.active, .nav-links a:hover {
      color: var(--admin-red);
      border-color: rgba(255,77,106,0.35);
      background: rgba(255,77,106,0.07);
      text-shadow: 0 0 12px rgba(255,77,106,0.70);
    }
    .nav-view-public {
      font-family: var(--font-hud); font-size: 0.58rem; font-weight: 700;
      letter-spacing: 0.10em; text-transform: uppercase;
      color: var(--prc-violet) !important;
      border: 1px solid rgba(139,126,255,0.35) !important;
      padding: 7px 16px; margin-left: 8px;
      clip-path: polygon(6px 0%, 100% 0%, calc(100% - 6px) 100%, 0% 100%);
      transition: all 0.25s; display: inline-flex; align-items: center; gap: 8px;
    }
    .nav-view-public:hover {
      background: rgba(139,126,255,0.12) !important;
      color: #fff !important;
      border-color: var(--prc-violet) !important;
    }
    .nav-hamburger {
      display: none; flex-direction: column; justify-content: center; align-items: center;
      gap: 5px; width: 44px; height: 44px; padding: 0;
      background: rgba(255,77,106,0.06); border: 1px solid rgba(255,77,106,0.25);
      border-radius: 4px; flex-shrink: 0; z-index: 1002;
      transition: all 0.2s; -webkit-tap-highlight-color: transparent;
    }
    .nav-hamburger span { width: 20px; height: 1.5px; background: var(--admin-red); border-radius: 2px; transition: transform 0.28s, opacity 0.28s; display: block; pointer-events: none; }
    .nav-hamburger.open span:nth-child(1) { transform: rotate(45deg) translate(5px,5px); }
    .nav-hamburger.open span:nth-child(2) { opacity: 0; }
    .nav-hamburger.open span:nth-child(3) { transform: rotate(-45deg) translate(5px,-5px); }
    .nav-mobile {
      display: none; position: fixed; top: var(--nav-height); left: 0; right: 0;
      background: rgba(6,2,10,0.98); backdrop-filter: blur(20px);
      border-bottom: 1px solid rgba(255,77,106,0.25); padding: 12px 18px 24px;
      z-index: 1000; flex-direction: column; gap: 2px;
    }
    .nav-mobile.open { display: flex; }
    .nav-mobile a {
      font-family: var(--font-hud); font-size: 0.70rem; font-weight: 600;
      color: var(--text-mid); padding: 13px 14px; border-radius: 3px;
      letter-spacing: 0.08em; text-transform: uppercase; transition: all 0.2s;
      display: flex; align-items: center; gap: 12px;
    }
    .nav-mobile a i { font-size: 1rem; color: var(--admin-red); }
    .nav-mobile a:hover { color: var(--admin-red); background: rgba(255,77,106,0.07); }

    /* ── PAGE WRAPPER ── */
    .page-wrapper { position: relative; z-index: 1; padding-top: var(--nav-height); }

    /* ── PAGE BANNER ── */
    .page-banner {
      position: relative; padding: 60px 0 48px;
      border-bottom: 1px solid rgba(255,77,106,0.20); overflow: hidden;
    }
    .page-banner::before {
      content: ''; position: absolute; inset: 0;
      background: radial-gradient(ellipse 60% 80% at 50% 0%, rgba(255,77,106,0.09) 0%, transparent 70%);
    }
    .page-banner-scan { position: absolute; inset: 0; overflow: hidden; pointer-events: none; }
    .page-banner-scan::after {
      content: ''; position: absolute; left: 0; right: 0; height: 1px;
      background: linear-gradient(90deg, transparent, var(--admin-red), transparent);
      animation: scanDown 4s linear infinite;
      box-shadow: 0 0 10px rgba(255,77,106,0.70);
    }
    .page-banner-inner {
      max-width: 1340px; margin: 0 auto; padding: 0 36px;
      position: relative; z-index: 2;
      display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 20px;
    }
    .page-banner-left {}
    .page-banner-eyebrow {
      display: inline-flex; align-items: center; gap: 10px;
      font-family: var(--font-hud); font-size: 0.58rem; font-weight: 700;
      letter-spacing: 0.20em; text-transform: uppercase;
      color: var(--admin-red); margin-bottom: 10px;
      text-shadow: 0 0 10px rgba(255,77,106,0.70);
    }
    .page-banner-eyebrow .dot-live {
      width: 7px; height: 7px; background: var(--admin-red); border-radius: 50%;
      box-shadow: 0 0 8px rgba(255,77,106,0.90);
      animation: neonPulse 1s ease-in-out infinite;
    }
    .page-banner-title {
      font-family: var(--font-hud); font-size: clamp(1.6rem, 4vw, 2.8rem);
      font-weight: 900; letter-spacing: -0.01em; line-height: 1.05; color: #fff;
    }
    .page-banner-title .accent-admin {
      color: var(--admin-red);
      text-shadow: 0 0 22px rgba(255,77,106,0.80), 0 0 60px rgba(255,77,106,0.30);
    }
    .page-banner-stats {
      display: flex; gap: 20px; flex-wrap: wrap;
    }
    .banner-stat {
      background: rgba(255,77,106,0.06); border: 1px solid rgba(255,77,106,0.20);
      padding: 12px 22px; text-align: center;
      clip-path: polygon(6px 0%, 100% 0%, calc(100% - 6px) 100%, 0% 100%);
    }
    .banner-stat-num {
      font-family: var(--font-hud); font-size: 1.6rem; font-weight: 800;
      color: var(--admin-red); display: block; line-height: 1;
      text-shadow: 0 0 14px rgba(255,77,106,0.70);
    }
    .banner-stat-lbl {
      font-family: var(--font-hud); font-size: 0.50rem; color: var(--text-soft);
      text-transform: uppercase; letter-spacing: 0.12em; display: block; margin-top: 4px;
    }

    /* ── FLASH MESSAGE ── */
    .flash-message {
      max-width: 1100px; margin: 20px auto 0; padding: 0 36px;
      animation: slideDown 0.35s ease;
    }
    .flash-inner {
      padding: 14px 22px; display: flex; align-items: center; gap: 12px;
      font-family: var(--font-hud); font-size: 0.65rem; font-weight: 600;
      letter-spacing: 0.08em; border: 1px solid; position: relative;
    }
    .flash-inner i { font-size: 1rem; flex-shrink: 0; }
    .flash-inner.success { color: var(--admin-green); border-color: rgba(68,255,136,0.35); background: rgba(68,255,136,0.06); }
    .flash-inner.error   { color: var(--admin-red);   border-color: rgba(255,77,106,0.35);  background: rgba(255,77,106,0.06); }

    /* ── MAIN LAYOUT ── */
    .admin-layout {
      max-width: 1100px; margin: 0 auto; padding: 40px 36px 100px;
      display: grid; grid-template-columns: 420px 1fr; gap: 36px; align-items: start;
    }

    /* ── FORM PANEL ── */
    .form-panel {
      background: var(--bg-card); border: 1px solid rgba(255,77,106,0.22);
      position: sticky; top: calc(var(--nav-height) + 24px);
    }
    .form-panel::before {
      content: ''; position: absolute; top: 0; left: 0; right: 0; height: 1px;
      background: linear-gradient(90deg, transparent, var(--admin-red), transparent);
    }
    .form-panel-header {
      background: rgba(255,77,106,0.06); padding: 20px 26px;
      border-bottom: 1px solid rgba(255,77,106,0.15);
      display: flex; align-items: center; gap: 12px;
    }
    .form-panel-header i { color: var(--admin-red); font-size: 1rem; }
    .form-panel-header-text h2 {
      font-family: var(--font-hud); font-size: 0.78rem; font-weight: 700;
      letter-spacing: 0.06em; color: var(--text-high);
    }
    .form-panel-header-text p { font-size: 0.78rem; color: var(--text-soft); margin-top: 2px; }
    .form-panel-body { padding: 26px; }

    /* ── FORM FIELDS ── */
    .field-group { margin-bottom: 22px; }
    .field-label {
      display: flex; align-items: center; gap: 8px;
      font-family: var(--font-hud); font-size: 0.58rem; font-weight: 700;
      letter-spacing: 0.14em; text-transform: uppercase; color: var(--text-soft);
      margin-bottom: 8px;
    }
    .field-label .required { color: var(--admin-red); font-size: 0.70rem; }
    .field-hint { font-size: 0.78rem; color: var(--text-dim); margin-top: 6px; }

    .field-input, .field-textarea, .field-select {
      width: 100%; padding: 13px 16px;
      background: rgba(139,126,255,0.04);
      border: 1px solid rgba(139,126,255,0.22);
      color: var(--text-high);
      font-family: var(--font-body); font-size: 0.96rem; line-height: 1.5;
      transition: border-color 0.25s, box-shadow 0.25s;
      outline: none; appearance: none;
    }
    .field-input:focus, .field-textarea:focus {
      border-color: var(--prc-violet);
      box-shadow: 0 0 0 2px rgba(139,126,255,0.14), 0 0 18px rgba(139,126,255,0.18);
    }
    .field-textarea { resize: vertical; min-height: 130px; }
    .field-input::placeholder, .field-textarea::placeholder { color: var(--text-dim); }

    /* Media upload zone */
    .upload-zone {
      border: 1.5px dashed rgba(139,126,255,0.28);
      background: rgba(139,126,255,0.03);
      padding: 28px 20px; text-align: center;
      cursor: pointer !important; transition: all 0.25s;
      position: relative; overflow: hidden;
    }
    .upload-zone:hover, .upload-zone.dragover {
      border-color: var(--prc-violet);
      background: rgba(139,126,255,0.08);
      box-shadow: 0 0 20px rgba(139,126,255,0.15);
    }
    .upload-zone input[type="file"] {
      position: absolute; inset: 0; opacity: 0;
      cursor: pointer !important; width: 100%; height: 100%;
    }
    .upload-zone-icon { font-size: 1.8rem; color: rgba(139,126,255,0.40); display: block; margin-bottom: 10px; }
    .upload-zone-label {
      font-family: var(--font-hud); font-size: 0.65rem; font-weight: 700;
      color: var(--text-soft); letter-spacing: 0.08em; display: block; margin-bottom: 4px;
    }
    .upload-zone-sub { font-size: 0.78rem; color: var(--text-dim); }
    .upload-preview {
      display: flex; flex-wrap: wrap; gap: 8px; margin-top: 14px;
    }
    .upload-preview-thumb {
      width: 70px; height: 70px; overflow: hidden;
      border: 1px solid rgba(139,126,255,0.25);
      position: relative;
    }
    .upload-preview-thumb img, .upload-preview-thumb video {
      width: 100%; height: 100%; object-fit: cover; display: block;
    }
    .upload-preview-thumb .remove-preview {
      position: absolute; top: 2px; right: 2px;
      width: 18px; height: 18px; border-radius: 50%;
      background: rgba(255,77,106,0.80); color: #fff;
      font-size: 0.55rem; display: flex; align-items: center; justify-content: center;
      cursor: pointer !important; font-weight: 700; border: none;
    }

    /* Pin toggle */
    .pin-row {
      display: flex; align-items: center; gap: 14px; padding: 14px 16px;
      background: rgba(255,233,48,0.04); border: 1px solid rgba(255,233,48,0.18);
      cursor: pointer !important;
    }
    .pin-row input[type="checkbox"] { width: 18px; height: 18px; cursor: pointer !important; accent-color: var(--creo-volt); }
    .pin-row-label {
      font-family: var(--font-hud); font-size: 0.62rem; font-weight: 700;
      letter-spacing: 0.08em; color: var(--creo-volt);
      text-shadow: 0 0 8px rgba(255,233,48,0.50); flex: 1;
    }
    .pin-row-sub { font-size: 0.78rem; color: var(--text-soft); }

    /* Submit button */
    .btn-submit {
      width: 100%; padding: 15px 28px;
      background: transparent; color: var(--admin-red);
      font-family: var(--font-hud); font-size: 0.68rem; font-weight: 700;
      letter-spacing: 0.14em; text-transform: uppercase;
      border: 1px solid var(--admin-red) !important;
      box-shadow: 0 0 18px rgba(255,77,106,0.28), inset 0 0 18px rgba(255,77,106,0.06);
      transition: all 0.25s; cursor: pointer !important; display: flex;
      align-items: center; justify-content: center; gap: 10px;
      clip-path: polygon(10px 0%, 100% 0%, calc(100% - 10px) 100%, 0% 100%);
    }
    .btn-submit:hover {
      background: rgba(255,77,106,0.12);
      box-shadow: 0 0 38px rgba(255,77,106,0.55), inset 0 0 28px rgba(255,77,106,0.12);
      color: #fff; transform: translateY(-2px);
    }

    /* ── POSTS LIST ── */
    .posts-panel {}
    .posts-panel-header {
      display: flex; align-items: center; justify-content: space-between;
      margin-bottom: 20px; flex-wrap: wrap; gap: 12px;
    }
    .posts-panel-title {
      font-family: var(--font-hud); font-size: 0.80rem; font-weight: 700;
      letter-spacing: 0.06em; color: var(--text-high); display: flex; align-items: center; gap: 10px;
    }
    .posts-count-badge {
      font-family: var(--font-hud); font-size: 0.52rem; font-weight: 700;
      padding: 3px 10px; border: 1px solid rgba(255,77,106,0.30);
      background: rgba(255,77,106,0.07); color: var(--admin-red);
      letter-spacing: 0.10em;
    }

    /* individual post row */
    .post-row {
      background: var(--bg-card); border: 1px solid rgba(139,126,255,0.14);
      margin-bottom: 14px; transition: border-color 0.25s;
      animation: slideIn 0.45s ease both;
    }
    .post-row:hover { border-color: rgba(139,126,255,0.32); }
    .post-row.pinned { border-color: rgba(255,233,48,0.25); }
    .post-row.pinned:hover { border-color: rgba(255,233,48,0.45); }

    /* top stripe */
    .post-row::before {
      content: ''; display: block; height: 1px;
      background: linear-gradient(90deg, transparent, rgba(139,126,255,0.22), transparent);
    }
    .post-row.pinned::before {
      background: linear-gradient(90deg, transparent, var(--creo-volt), transparent);
    }

    .post-row-inner { padding: 18px 22px; }
    .post-row-top {
      display: flex; align-items: flex-start; gap: 14px; margin-bottom: 10px;
    }
    .post-row-meta { flex: 1; min-width: 0; }
    .post-row-title {
      font-family: var(--font-hud); font-size: 0.82rem; font-weight: 700;
      color: var(--text-high); letter-spacing: 0.02em; margin-bottom: 5px;
      white-space: nowrap; overflow: hidden; text-overflow: ellipsis;
    }
    .post-row.pinned .post-row-title { color: var(--creo-volt); }
    .post-row-info {
      display: flex; align-items: center; gap: 14px; flex-wrap: wrap;
    }
    .post-row-date {
      font-family: var(--font-hud); font-size: 0.52rem; color: var(--text-dim);
      letter-spacing: 0.08em; display: flex; align-items: center; gap: 5px;
    }
    .post-row-date i { font-size: 0.60rem; }
    .post-row-media-count {
      font-family: var(--font-hud); font-size: 0.52rem; color: var(--prc-violet);
      letter-spacing: 0.08em; display: flex; align-items: center; gap: 5px;
    }
    .pin-indicator {
      font-family: var(--font-hud); font-size: 0.50rem; font-weight: 700;
      letter-spacing: 0.12em; color: var(--creo-volt); display: flex; align-items: center; gap: 5px;
    }

    .post-row-caption {
      font-size: 0.86rem; color: var(--text-soft);
      overflow: hidden; display: -webkit-box;
      -webkit-line-clamp: 2; -webkit-box-orient: vertical;
      line-clamp: 2; margin-bottom: 14px;
    }

    .post-row-actions {
      display: flex; align-items: center; gap: 8px; flex-wrap: wrap;
      padding-top: 12px; border-top: 1px solid rgba(139,126,255,0.08);
    }
    .action-btn {
      display: inline-flex; align-items: center; gap: 7px;
      font-family: var(--font-hud); font-size: 0.54rem; font-weight: 700;
      letter-spacing: 0.10em; text-transform: uppercase;
      padding: 7px 14px; border: 1px solid; transition: all 0.2s;
      cursor: pointer !important;
      clip-path: polygon(4px 0%, 100% 0%, calc(100% - 4px) 100%, 0% 100%);
    }
    .action-btn.pin {
      color: var(--creo-volt); border-color: rgba(255,233,48,0.30);
      background: rgba(255,233,48,0.04);
    }
    .action-btn.pin:hover { background: rgba(255,233,48,0.12); box-shadow: 0 0 12px rgba(255,233,48,0.25); }
    .action-btn.unpin {
      color: var(--text-soft); border-color: rgba(139,126,255,0.22);
      background: rgba(139,126,255,0.04);
    }
    .action-btn.unpin:hover { color: var(--text-mid); }
    .action-btn.delete {
      color: var(--admin-red); border-color: rgba(255,77,106,0.30);
      background: rgba(255,77,106,0.04); margin-left: auto;
    }
    .action-btn.delete:hover { background: rgba(255,77,106,0.14); box-shadow: 0 0 14px rgba(255,77,106,0.28); }

    /* media thumbnails in post row */
    .post-row-thumbs {
      display: flex; gap: 6px; flex-wrap: wrap; margin-bottom: 10px;
    }
    .row-thumb {
      width: 56px; height: 42px; overflow: hidden; border: 1px solid rgba(139,126,255,0.20);
      position: relative; flex-shrink: 0;
    }
    .row-thumb img, .row-thumb video {
      width: 100%; height: 100%; object-fit: cover; display: block;
      filter: brightness(0.65) saturate(0.60);
    }
    .row-thumb-del {
      position: absolute; inset: 0; display: flex; align-items: center; justify-content: center;
      background: rgba(255,77,106,0.0); transition: background 0.2s;
      cursor: pointer !important;
    }
    .row-thumb-del:hover { background: rgba(255,77,106,0.65); }
    .row-thumb-del i { color: transparent; font-size: 0.80rem; transition: color 0.2s; }
    .row-thumb-del:hover i { color: #fff; }

    /* empty list */
    .no-posts {
      text-align: center; padding: 60px 20px;
      border: 1px dashed rgba(139,126,255,0.18);
      background: rgba(139,126,255,0.02);
    }
    .no-posts i { font-size: 2rem; color: rgba(255,77,106,0.30); display: block; margin-bottom: 14px; }
    .no-posts p { font-family: var(--font-hud); font-size: 0.68rem; color: var(--text-dim); }

    /* ── CONFIRM MODAL ── */
    .confirm-overlay {
      display: none; position: fixed; inset: 0; z-index: 9990;
      background: rgba(3,2,13,0.88); align-items: center; justify-content: center;
    }
    .confirm-overlay.open { display: flex; }
    .confirm-box {
      background: var(--bg-card); border: 1px solid rgba(255,77,106,0.40);
      max-width: 440px; width: 90%; padding: 40px 36px; position: relative;
      box-shadow: 0 0 60px rgba(255,77,106,0.18);
      text-align: center;
    }
    .confirm-box::before { content: ''; position: absolute; top: 0; left: 0; right: 0; height: 1px; background: linear-gradient(90deg, transparent, var(--admin-red), transparent); }
    .confirm-icon { font-size: 2rem; color: var(--admin-red); margin-bottom: 16px; display: block; }
    .confirm-title { font-family: var(--font-hud); font-size: 1rem; font-weight: 800; color: #fff; margin-bottom: 10px; }
    .confirm-text { font-size: 0.92rem; color: var(--text-mid); margin-bottom: 30px; line-height: 1.70; }
    .confirm-actions { display: flex; gap: 12px; justify-content: center; }
    .btn-cancel {
      padding: 11px 28px; font-family: var(--font-hud); font-size: 0.62rem;
      font-weight: 700; letter-spacing: 0.12em; text-transform: uppercase;
      color: var(--text-soft); border: 1px solid rgba(139,126,255,0.22) !important;
      background: rgba(139,126,255,0.05); cursor: pointer !important; transition: all 0.2s;
    }
    .btn-cancel:hover { color: var(--text-high); border-color: rgba(139,126,255,0.45) !important; }
    .btn-confirm-del {
      padding: 11px 28px; font-family: var(--font-hud); font-size: 0.62rem;
      font-weight: 700; letter-spacing: 0.12em; text-transform: uppercase;
      color: var(--admin-red); border: 1px solid rgba(255,77,106,0.40) !important;
      background: rgba(255,77,106,0.08); cursor: pointer !important; transition: all 0.2s;
    }
    .btn-confirm-del:hover { background: rgba(255,77,106,0.20); box-shadow: 0 0 20px rgba(255,77,106,0.28); }

    /* ── RESPONSIVE ── */
    @media (max-width: 1000px) {
      .admin-layout { grid-template-columns: 1fr; }
      .form-panel { position: static; }
    }
    @media (max-width: 768px) {
      body { cursor: auto; } button { cursor: pointer; }
      .cursor-dot, .cursor-ring { display: none; }
      .nav-links { display: none; } .nav-hamburger { display: flex; }
      .admin-layout { padding: 24px 16px 80px; }
      .page-banner-inner { flex-direction: column; align-items: flex-start; }
    }
    @media (max-width: 520px) {
      :root { --nav-height: 58px; }
      .nav-inner { padding: 0 14px; }
      .flash-message { padding: 0 16px; }
    }

    ::-webkit-scrollbar { width: 4px; }
    ::-webkit-scrollbar-track { background: var(--bg-void); }
    ::-webkit-scrollbar-thumb { background: var(--admin-red); border-radius: 2px; }
  </style>
</head>
<body>

<div class="cursor-dot" id="cursorDot"></div>
<div class="cursor-ring" id="cursorRing"></div>
<div class="hex-grid" aria-hidden="true"></div>

<div class="page-wrapper">

  <!-- ADMIN NAV -->
  <nav id="main-nav" role="navigation" aria-label="Admin navigation">
    <div class="nav-inner">
      <a href="admin-announcements.php" class="nav-logo" aria-label="Admin Home">
        <img src="assets/PRC White Logo.png" alt="Philippine Robotics Cup Logo" />
        <div class="nav-brand-wrap">
          <div class="nav-brand-top">
            <span class="nav-brand-name">Philippine Robotics Cup</span>
            <span class="nav-admin-badge">&#9632; ADMIN</span>
          </div>
          <span class="nav-brand-sub">By Creotec Philippines</span>
        </div>
      </a>
      <ul class="nav-links" role="list">
        <li><a href="admin-announcements.php" class="active"><i class="fi fi-rr-megaphone" style="margin-right:6px;"></i> Announcements</a></li>
        <li><a href="announcements.php" class="nav-view-public" target="_blank"><i class="fi fi-rr-eye"></i> View Public Page</a></li>
      </ul>
      <button class="nav-hamburger" id="hamburger" type="button" aria-label="Open menu" aria-expanded="false">
        <span></span><span></span><span></span>
      </button>
    </div>
  </nav>

  <nav class="nav-mobile" id="mobile-menu" aria-label="Admin mobile navigation">
    <a href="admin-announcements.php"><i class="fi fi-rr-megaphone"></i>Announcements</a>
    <a href="announcements.php" target="_blank"><i class="fi fi-rr-eye"></i>View Public Page</a>
  </nav>

  <!-- PAGE BANNER -->
  <div class="page-banner">
    <div class="page-banner-scan"></div>
    <div class="page-banner-inner">
      <div class="page-banner-left">
        <div class="page-banner-eyebrow"><span class="dot-live"></span> Admin Panel // PRC 2026</div>
        <h1 class="page-banner-title"><span class="accent-admin">ADMIN</span> — Manage Announcements</h1>
      </div>
      <div class="page-banner-stats">
        <div class="banner-stat">
          <span class="banner-stat-num"><?= count($all_posts) ?></span>
          <span class="banner-stat-lbl">Total Posts</span>
        </div>
        <div class="banner-stat">
          <span class="banner-stat-num"><?= count(array_filter($all_posts, fn($p) => $p['announcement_is_pinned'])) ?></span>
          <span class="banner-stat-lbl">Pinned</span>
        </div>
      </div>
    </div>
  </div>

  <!-- FLASH MESSAGE -->
  <?php if ($flash): ?>
  <div class="flash-message">
    <div class="flash-inner <?= $flash['type'] ?>">
      <i class="fi fi-<?= $flash['type']==='success' ? 'rr-check' : 'rr-cross' ?>"></i>
      <?= htmlspecialchars($flash['msg']) ?>
    </div>
  </div>
  <?php endif; ?>

  <!-- ADMIN LAYOUT -->
  <div class="admin-layout">

    <!-- ── LEFT: CREATE FORM ── -->
    <aside class="form-panel">
      <div class="form-panel-header">
        <i class="fi fi-rr-plus-small"></i>
        <div class="form-panel-header-text">
          <h2>New Announcement</h2>
          <p>Publish a post to the public feed</p>
        </div>
      </div>
      <div class="form-panel-body">
        <form method="POST" action="admin-announcements.php" enctype="multipart/form-data" id="create-form" novalidate>
          <input type="hidden" name="action" value="create" />

          <!-- Title -->
          <div class="field-group">
            <label class="field-label" for="announcement_title">
              Title <span class="required">*</span>
            </label>
            <input
              type="text"
              class="field-input"
              id="announcement_title"
              name="announcement_title"
              placeholder="e.g. Registration Now Open for PRC 2026"
              maxlength="255"
              required
            />
          </div>

          <!-- Caption -->
          <div class="field-group">
            <label class="field-label" for="announcement_caption">Caption / Body</label>
            <textarea
              class="field-textarea"
              id="announcement_caption"
              name="announcement_caption"
              placeholder="Write the full announcement here. You can use multiple paragraphs."
              rows="6"
            ></textarea>
            <div class="field-hint">Plain text. Line breaks will be preserved.</div>
          </div>

          <!-- Media Upload -->
          <div class="field-group">
            <label class="field-label"><i class="fi fi-rr-picture"></i> Media Attachments</label>
            <div class="upload-zone" id="upload-zone">
              <input
                type="file"
                name="announcement_media[]"
                id="announcement_media"
                multiple
                accept="image/jpeg,image/png,image/gif,image/webp,video/mp4,video/webm,video/ogg"
                aria-label="Upload images or videos"
              />
              <i class="fi fi-rr-cloud-upload upload-zone-icon"></i>
              <span class="upload-zone-label">Click or drag files here</span>
              <span class="upload-zone-sub">Images (JPG, PNG, GIF, WEBP) or Videos (MP4, WEBM) — max 50 MB each</span>
            </div>
            <div class="upload-preview" id="upload-preview"></div>
          </div>

          <!-- Pin toggle -->
          <div class="field-group">
            <label class="pin-row" for="announcement_is_pinned">
              <input type="checkbox" id="announcement_is_pinned" name="announcement_is_pinned" value="1" />
              <div>
                <div class="pin-row-label"><i class="fi fi-rr-thumbtack"></i> Pin this announcement</div>
                <div class="pin-row-sub">Pinned posts always appear at the top of the feed.</div>
              </div>
            </label>
          </div>

          <button type="submit" class="btn-submit">
            <i class="fi fi-rr-paper-plane"></i> Publish Announcement
          </button>
        </form>
      </div>
    </aside>

    <!-- ── RIGHT: POSTS LIST ── -->
    <section class="posts-panel" aria-label="All announcements">
      <div class="posts-panel-header">
        <div class="posts-panel-title">
          All Announcements
          <span class="posts-count-badge"><?= count($all_posts) ?> total</span>
        </div>
      </div>

      <?php if (empty($all_posts)): ?>
      <div class="no-posts">
        <i class="fi fi-rr-megaphone"></i>
        <p>No announcements yet. Create your first one using the form.</p>
      </div>
      <?php else: ?>

      <?php foreach ($all_posts as $i => $p):
        $pinned = (bool)$p['announcement_is_pinned'];

        // fetch media for this post to show thumbnails
        $conn2 = new mysqli($db_host, $db_user, $db_pass, $db_name);
        $mres2 = $conn2->query("SELECT media_id, media_file_path, media_type FROM prc_announcement_media WHERE announcement_id = {$p['announcement_id']} ORDER BY media_sort_order ASC LIMIT 5");
        $thumb_media = [];
        if ($mres2) while ($mr = $mres2->fetch_assoc()) $thumb_media[] = $mr;
        $conn2->close();
      ?>

      <div class="post-row <?= $pinned ? 'pinned' : '' ?>" style="animation-delay:<?= $i*50 ?>ms">
        <div class="post-row-inner">

          <div class="post-row-top">
            <div class="post-row-meta">
              <div class="post-row-title" title="<?= htmlspecialchars($p['announcement_title']) ?>">
                <?= htmlspecialchars($p['announcement_title']) ?>
              </div>
              <div class="post-row-info">
                <span class="post-row-date">
                  <i class="fi fi-rr-calendar"></i>
                  <?= date('M j, Y g:i A', strtotime($p['announcement_date_posted'])) ?>
                </span>
                <?php if ($p['media_count'] > 0): ?>
                <span class="post-row-media-count">
                  <i class="fi fi-rr-picture"></i>
                  <?= $p['media_count'] ?> file<?= $p['media_count'] > 1 ? 's' : '' ?>
                </span>
                <?php endif; ?>
                <?php if ($pinned): ?>
                <span class="pin-indicator"><i class="fi fi-rr-thumbtack"></i> Pinned</span>
                <?php endif; ?>
              </div>
            </div>
          </div>

          <?php if (!empty($p['announcement_caption'])): ?>
          <div class="post-row-caption"><?= htmlspecialchars($p['announcement_caption']) ?></div>
          <?php endif; ?>

          <!-- Thumbnails -->
          <?php if (!empty($thumb_media)): ?>
          <div class="post-row-thumbs">
            <?php foreach ($thumb_media as $tm): ?>
            <div class="row-thumb" title="Remove this file">
              <?php if ($tm['media_type'] === 'video'): ?>
                <video src="<?= htmlspecialchars($tm['media_file_path']) ?>" preload="none" muted></video>
              <?php else: ?>
                <img src="<?= htmlspecialchars($tm['media_file_path']) ?>" alt="Attachment" loading="lazy" />
              <?php endif; ?>
              <form method="POST" action="admin-announcements.php" class="row-thumb-del" onsubmit="return true">
                <input type="hidden" name="action" value="delete_media" />
                <input type="hidden" name="media_id" value="<?= $tm['media_id'] ?>" />
                <button type="submit" title="Remove this file" style="background:none;border:none;width:100%;height:100%;cursor:pointer !important;display:flex;align-items:center;justify-content:center;">
                  <i class="fi fi-rr-trash"></i>
                </button>
              </form>
            </div>
            <?php endforeach; ?>
          </div>
          <?php endif; ?>

          <!-- Actions -->
          <div class="post-row-actions">
            <!-- Pin / Unpin -->
            <form method="POST" action="admin-announcements.php" style="display:inline;">
              <input type="hidden" name="action" value="toggle_pin" />
              <input type="hidden" name="announcement_id" value="<?= $p['announcement_id'] ?>" />
              <input type="hidden" name="new_pin_state" value="<?= $pinned ? '0' : '1' ?>" />
              <button type="submit" class="action-btn <?= $pinned ? 'unpin' : 'pin' ?>">
                <i class="fi fi-rr-thumbtack"></i>
                <?= $pinned ? 'Unpin' : 'Pin' ?>
              </button>
            </form>

            <!-- Delete -->
            <button
              type="button"
              class="action-btn delete"
              onclick="confirmDelete(<?= $p['announcement_id'] ?>, <?= htmlspecialchars(json_encode($p['announcement_title'])) ?>)"
            >
              <i class="fi fi-rr-trash"></i> Delete
            </button>
          </div>

        </div>
      </div>

      <?php endforeach; ?>
      <?php endif; ?>

    </section>

  </div><!-- /admin-layout -->

  <!-- DELETE CONFIRM MODAL -->
  <div class="confirm-overlay" id="confirm-overlay" role="dialog" aria-modal="true" aria-labelledby="confirm-title">
    <div class="confirm-box">
      <i class="fi fi-rr-trash confirm-icon"></i>
      <h2 class="confirm-title" id="confirm-title">Delete Announcement?</h2>
      <p class="confirm-text" id="confirm-text">This will permanently delete this announcement and all its media files. This action cannot be undone.</p>
      <div class="confirm-actions">
        <button class="btn-cancel" onclick="closeConfirm()">Cancel</button>
        <form method="POST" action="admin-announcements.php" id="confirm-delete-form">
          <input type="hidden" name="action" value="delete" />
          <input type="hidden" name="announcement_id" id="confirm-delete-id" value="" />
          <button type="submit" class="btn-confirm-del"><i class="fi fi-rr-trash"></i> Yes, Delete</button>
        </form>
      </div>
    </div>
  </div>

</div><!-- /page-wrapper -->

<script>
// ── CURSOR ──
(function(){
  var dot = document.getElementById('cursorDot');
  var ring = document.getElementById('cursorRing');
  if (!dot||!ring) return;
  var mx=0,my=0,rx=0,ry=0;
  document.addEventListener('mousemove',function(e){ mx=e.clientX; my=e.clientY; dot.style.left=mx+'px'; dot.style.top=my+'px'; });
  (function loop(){ rx+=(mx-rx)*0.12; ry+=(my-ry)*0.12; ring.style.left=rx+'px'; ring.style.top=ry+'px'; requestAnimationFrame(loop); })();
  document.querySelectorAll('a,button,.post-row,.action-btn,.row-thumb').forEach(function(el){
    el.addEventListener('mouseenter',function(){ ring.classList.add('hovered'); });
    el.addEventListener('mouseleave',function(){ ring.classList.remove('hovered'); });
  });
})();

// ── HAMBURGER ──
(function(){
  var btn = document.getElementById('hamburger');
  var menu = document.getElementById('mobile-menu');
  if (!btn||!menu) return;
  btn.addEventListener('click',function(e){
    e.stopPropagation();
    var open = menu.classList.toggle('open');
    btn.classList.toggle('open',open);
    btn.setAttribute('aria-expanded',open);
    document.body.style.overflow = open ? 'hidden' : '';
  });
  document.addEventListener('click',function(e){ if(menu.classList.contains('open')&&!btn.contains(e.target)&&!menu.contains(e.target)){ menu.classList.remove('open'); btn.classList.remove('open'); document.body.style.overflow=''; } });
})();

// ── DELETE CONFIRM ──
function confirmDelete(id, title) {
  document.getElementById('confirm-delete-id').value = id;
  document.getElementById('confirm-text').textContent = 'You are about to permanently delete "' + title + '" and all its media files. This cannot be undone.';
  document.getElementById('confirm-overlay').classList.add('open');
  document.body.style.overflow = 'hidden';
}
function closeConfirm() {
  document.getElementById('confirm-overlay').classList.remove('open');
  document.body.style.overflow = '';
}
document.getElementById('confirm-overlay').addEventListener('click', function(e) {
  if (e.target === this) closeConfirm();
});
document.addEventListener('keydown', function(e) {
  if (e.key === 'Escape') closeConfirm();
});

// ── UPLOAD PREVIEW ──
(function(){
  var input = document.getElementById('announcement_media');
  var preview = document.getElementById('upload-preview');
  var zone = document.getElementById('upload-zone');
  if (!input||!preview) return;

  input.addEventListener('change', function() {
    preview.innerHTML = '';
    var files = Array.from(this.files);
    files.forEach(function(file, i) {
      var thumb = document.createElement('div');
      thumb.className = 'upload-preview-thumb';
      if (file.type.startsWith('video')) {
        var v = document.createElement('video');
        v.src = URL.createObjectURL(file);
        v.muted = true; v.preload = 'metadata';
        thumb.appendChild(v);
      } else {
        var img = document.createElement('img');
        img.src = URL.createObjectURL(file);
        img.alt = file.name;
        thumb.appendChild(img);
      }
      preview.appendChild(thumb);
    });
  });

  // drag over styling
  zone.addEventListener('dragover', function(e) { e.preventDefault(); zone.classList.add('dragover'); });
  zone.addEventListener('dragleave', function() { zone.classList.remove('dragover'); });
  zone.addEventListener('drop', function(e) { e.preventDefault(); zone.classList.remove('dragover'); });
})();

// ── FORM VALIDATION ──
document.getElementById('create-form').addEventListener('submit', function(e) {
  var title = document.getElementById('announcement_title').value.trim();
  if (!title) {
    e.preventDefault();
    document.getElementById('announcement_title').focus();
    document.getElementById('announcement_title').style.borderColor = 'var(--admin-red)';
    document.getElementById('announcement_title').style.boxShadow = '0 0 0 2px rgba(255,77,106,0.20)';
  }
});
document.getElementById('announcement_title').addEventListener('input', function() {
  this.style.borderColor = ''; this.style.boxShadow = '';
});
</script>
</body>
</html>