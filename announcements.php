<?php
// ── DB CONNECTION ──────────────────────────────────────────────
$db_host = 'localhost';
$db_user = 'root';
$db_pass = '';
$db_name = 'prc_db';

$conn = new mysqli($db_host, $db_user, $db_pass, $db_name);
if ($conn->connect_error) {
    die('<p style="color:#ff6b6b;padding:40px;font-family:monospace;">Database connection failed: ' . htmlspecialchars($conn->connect_error) . '</p>');
}

// ── FETCH ANNOUNCEMENTS (newest first) ────────────────────────
$sql = "
    SELECT
        a.announcement_id,
        a.announcement_title,
        a.announcement_caption,
        a.announcement_date_posted,
        a.announcement_is_pinned,
        GROUP_CONCAT(
            m.media_id, '|', m.media_file_path, '|', m.media_type, '|', m.media_sort_order
            ORDER BY m.media_sort_order ASC
            SEPARATOR ';;'
        ) AS media_list
    FROM prc_announcements a
    LEFT JOIN prc_announcement_media m ON a.announcement_id = m.announcement_id
    GROUP BY a.announcement_id
    ORDER BY a.announcement_is_pinned DESC, a.announcement_date_posted DESC
";
$result = $conn->query($sql);
$announcements = [];
if ($result) {
    while ($row = $result->fetch_assoc()) {
        $row['media'] = [];
        if (!empty($row['media_list'])) {
            foreach (explode(';;', $row['media_list']) as $item) {
                $parts = explode('|', $item);
                if (count($parts) === 4) {
                    $row['media'][] = [
                        'media_id'        => $parts[0],
                        'media_file_path' => $parts[1],
                        'media_type'      => $parts[2],
                        'media_sort_order'=> $parts[3],
                    ];
                }
            }
        }
        $announcements[] = $row;
    }
}
$conn->close();

// ── HELPER: format date nicely ────────────────────────────────
function fmt_date($dt) {
    $ts = strtotime($dt);
    $diff = time() - $ts;
    if ($diff < 60)          return 'Just now';
    if ($diff < 3600)        return floor($diff/60) . ' minutes ago';
    if ($diff < 86400)       return floor($diff/3600) . ' hours ago';
    if ($diff < 172800)      return 'Yesterday at ' . date('g:i A', $ts);
    if ($diff < 604800)      return date('l \a\t g:i A', $ts);
    return date('F j, Y \a\t g:i A', $ts);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <meta name="theme-color" content="#8B7EFF" />
  <meta name="description" content="Philippine Robotics Cup — Official Announcements" />
  <title>Announcements — Philippine Robotics Cup 2026</title>

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
      --bg-void:      #03020D;
      --bg-deep:      #06051A;
      --bg-card:      #0A0918;
      --border-neon:  rgba(139,126,255,0.22);
      --glow-primary: 0 0 18px rgba(139,126,255,0.60), 0 0 55px rgba(139,126,255,0.20);
      --glow-orange:  0 0 18px rgba(255,160,48,0.55),  0 0 55px rgba(255,160,48,0.18);
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
      background: var(--prc-violet); pointer-events: none; z-index: 99999;
      transform: translate(-50%,-50%);
      box-shadow: var(--glow-primary);
      transition: transform 0.1s, background 0.2s;
    }
    .cursor-ring {
      position: fixed; width: 36px; height: 36px; border-radius: 50%;
      border: 1px solid rgba(139,126,255,0.65); pointer-events: none; z-index: 99998;
      transform: translate(-50%,-50%);
      transition: width 0.25s, height 0.25s, border-color 0.25s, transform 0.08s;
    }
    .cursor-ring.hovered { width: 56px; height: 56px; border-color: var(--creo-amber); border-width: 1.5px; }

    /* ── SCANLINES ── */
    body::after {
      content: ''; position: fixed; inset: 0; z-index: 9998; pointer-events: none;
      background: repeating-linear-gradient(to bottom, transparent, transparent 2px, rgba(0,0,0,0.04) 2px, rgba(0,0,0,0.04) 4px);
    }

    /* ── HEX GRID ── */
    .hex-grid {
      position: fixed; inset: 0; z-index: 0; pointer-events: none;
      background-image: linear-gradient(rgba(139,126,255,0.04) 1px, transparent 1px), linear-gradient(90deg, rgba(139,126,255,0.04) 1px, transparent 1px);
      background-size: 50px 50px;
    }
    .hex-grid::before {
      content: ''; position: absolute; inset: 0;
      background: radial-gradient(ellipse 80% 60% at 50% 0%, rgba(119,51,255,0.14) 0%, transparent 70%),
                  radial-gradient(ellipse 60% 50% at 100% 100%, rgba(204,85,255,0.07) 0%, transparent 60%);
    }

    /* ── ANIMATIONS ── */
    @keyframes neonPulse { 0%,100%{opacity:1} 50%{opacity:0.7} }
    @keyframes fadeInUp  { from{opacity:0;transform:translateY(28px)} to{opacity:1;transform:translateY(0)} }
    @keyframes scanDown  { from{transform:translateY(-100%)} to{transform:translateY(100vh)} }
    @keyframes flicker   { 0%,100%{opacity:1} 92%{opacity:1} 93%{opacity:0.4} 94%{opacity:1} 96%{opacity:0.7} 97%{opacity:1} }
    @keyframes slideIn   { from{opacity:0;transform:translateY(24px)} to{opacity:1;transform:translateY(0)} }

    /* ── NAV ── */
    #main-nav {
      position: fixed; top: 0; left: 0; right: 0;
      height: var(--nav-height); z-index: 1000;
      background: rgba(3,2,13,0.94);
      backdrop-filter: blur(20px);
      border-bottom: 1px solid var(--border-neon);
      box-shadow: 0 0 30px rgba(139,126,255,0.10);
    }
    .nav-inner {
      max-width: 1340px; margin: 0 auto; height: 100%; padding: 0 36px;
      display: flex; align-items: center; justify-content: space-between; gap: 16px;
    }
    .nav-logo { display: flex; align-items: center; gap: 12px; flex-shrink: 0; }
    .nav-logo img { height: 38px; width: auto; transition: filter 0.3s; }
    .nav-logo:hover img { filter: drop-shadow(0 0 14px rgba(139,126,255,0.75)); }
    .nav-brand { font-family: var(--font-hud); font-weight: 700; font-size: 0.72rem; letter-spacing: 0.06em; line-height: 1.3; color: var(--prc-violet); text-shadow: 0 0 12px rgba(139,126,255,0.65); }
    .nav-brand span { color: var(--text-soft); display: block; font-size: 0.58rem; font-weight: 400; letter-spacing: 0.10em; text-transform: uppercase; margin-top: 1px; }
    .nav-links { display: flex; align-items: center; gap: 2px; }
    .nav-links a {
      font-family: var(--font-hud); font-size: 0.65rem; font-weight: 600;
      color: var(--text-mid); padding: 8px 14px;
      letter-spacing: 0.08em; text-transform: uppercase;
      border-radius: 4px; transition: all 0.2s; white-space: nowrap;
    }
    .nav-links a:hover, .nav-links a.active { color: var(--prc-violet); text-shadow: 0 0 12px rgba(139,126,255,0.85); }
    .nav-cta {
      background: transparent !important; border: 1px solid var(--prc-violet) !important;
      color: var(--prc-violet) !important; padding: 8px 20px !important;
      border-radius: 3px !important; margin-left: 8px;
      box-shadow: 0 0 15px rgba(139,126,255,0.28), inset 0 0 15px rgba(139,126,255,0.06) !important;
      transition: all 0.25s !important;
      clip-path: polygon(6px 0%, 100% 0%, calc(100% - 6px) 100%, 0% 100%);
    }
    .nav-cta:hover { background: rgba(139,126,255,0.12) !important; color: #fff !important; box-shadow: 0 0 30px rgba(139,126,255,0.52), inset 0 0 20px rgba(139,126,255,0.10) !important; }
    .nav-hamburger {
      display: none; flex-direction: column; justify-content: center; align-items: center;
      gap: 5px; width: 44px; height: 44px; padding: 0;
      background: rgba(139,126,255,0.06); border: 1px solid var(--border-neon);
      border-radius: 4px; flex-shrink: 0; z-index: 1002;
      transition: all 0.2s; -webkit-tap-highlight-color: transparent;
    }
    .nav-hamburger span { width: 20px; height: 1.5px; background: var(--prc-violet); border-radius: 2px; transition: transform 0.28s, opacity 0.28s; display: block; pointer-events: none; }
    .nav-hamburger.open span:nth-child(1) { transform: rotate(45deg) translate(5px,5px); }
    .nav-hamburger.open span:nth-child(2) { opacity: 0; }
    .nav-hamburger.open span:nth-child(3) { transform: rotate(-45deg) translate(5px,-5px); }
    .nav-mobile {
      display: none; position: fixed; top: var(--nav-height); left: 0; right: 0;
      background: rgba(3,2,13,0.98); backdrop-filter: blur(20px);
      border-bottom: 1px solid var(--border-neon); padding: 12px 18px 24px;
      z-index: 1000; flex-direction: column; gap: 2px;
    }
    .nav-mobile.open { display: flex; }
    .nav-mobile a {
      font-family: var(--font-hud); font-size: 0.70rem; font-weight: 600;
      color: var(--text-mid); padding: 13px 14px; border-radius: 3px;
      letter-spacing: 0.08em; text-transform: uppercase; transition: all 0.2s;
      display: flex; align-items: center; gap: 12px;
    }
    .nav-mobile a i { font-size: 1rem; color: var(--prc-violet); }
    .nav-mobile a:hover { color: var(--prc-violet); background: rgba(139,126,255,0.07); }
    .nav-mobile .nav-cta { border: 1px solid var(--prc-violet) !important; color: var(--prc-violet) !important; margin-top: 10px; justify-content: center; clip-path: none !important; }

    /* ── PAGE WRAPPER ── */
    .page-wrapper { position: relative; z-index: 1; padding-top: var(--nav-height); }

    /* ── PAGE HERO BANNER ── */
    .page-banner {
      position: relative; padding: 72px 0 60px; overflow: hidden;
      border-bottom: 1px solid var(--border-neon);
    }
    .page-banner::before {
      content: ''; position: absolute; inset: 0;
      background: radial-gradient(ellipse 70% 80% at 50% 50%, rgba(119,51,255,0.12) 0%, transparent 70%);
    }
    .page-banner-scan {
      position: absolute; inset: 0; overflow: hidden; pointer-events: none;
    }
    .page-banner-scan::after {
      content: ''; position: absolute; left: 0; right: 0; height: 1px;
      background: linear-gradient(90deg, transparent, var(--prc-violet), var(--prc-ice), transparent);
      animation: scanDown 5s linear infinite;
      box-shadow: 0 0 12px rgba(139,126,255,0.55);
    }
    .page-banner-inner {
      max-width: 1340px; margin: 0 auto; padding: 0 36px;
      position: relative; z-index: 2; text-align: center;
    }
    .page-banner-eyebrow {
      display: inline-flex; align-items: center; gap: 10px;
      font-family: var(--font-hud); font-size: 0.60rem; font-weight: 700;
      letter-spacing: 0.22em; text-transform: uppercase; color: var(--prc-ice);
      margin-bottom: 18px;
    }
    .page-banner-eyebrow .blink { width: 6px; height: 6px; background: var(--prc-ice); border-radius: 50%; box-shadow: 0 0 8px rgba(196,238,255,0.80); animation: neonPulse 1.2s ease-in-out infinite; }
    .page-banner-title {
      font-family: var(--font-hud); font-size: clamp(2rem, 5vw, 3.6rem);
      font-weight: 900; letter-spacing: -0.01em; line-height: 1;
      color: #fff; text-shadow: 0 0 60px rgba(139,126,255,0.25);
      margin-bottom: 14px;
    }
    .page-banner-title .accent { color: var(--prc-violet); text-shadow: 0 0 22px rgba(139,126,255,0.70); }
    .page-banner-desc {
      font-size: 1.05rem; color: var(--text-mid); max-width: 540px;
      margin: 0 auto; line-height: 1.78;
    }

    /* ── MAIN LAYOUT ── */
    .announcements-layout {
      max-width: 860px;
      margin: 0 auto;
      padding: 60px 24px 100px;
    }

    /* ── FILTER BAR ── */
    .filter-bar {
      display: flex; align-items: center; gap: 10px;
      margin-bottom: 40px; flex-wrap: wrap;
    }
    .filter-label {
      font-family: var(--font-hud); font-size: 0.54rem; font-weight: 700;
      letter-spacing: 0.18em; text-transform: uppercase; color: var(--text-soft);
      flex-shrink: 0;
    }
    .filter-btn {
      font-family: var(--font-hud); font-size: 0.58rem; font-weight: 600;
      letter-spacing: 0.10em; text-transform: uppercase;
      padding: 7px 16px; border: 1px solid rgba(139,126,255,0.22);
      background: rgba(139,126,255,0.04); color: var(--text-soft);
      transition: all 0.2s; cursor: pointer !important;
      clip-path: polygon(5px 0%, 100% 0%, calc(100% - 5px) 100%, 0% 100%);
    }
    .filter-btn:hover, .filter-btn.active {
      border-color: var(--prc-violet); color: var(--prc-violet);
      background: rgba(139,126,255,0.10); box-shadow: 0 0 12px rgba(139,126,255,0.28);
    }

    /* ── EMPTY STATE ── */
    .no-announcements {
      text-align: center; padding: 80px 20px;
      border: 1px dashed rgba(139,126,255,0.20);
      background: rgba(139,126,255,0.02);
    }
    .no-announcements i { font-size: 2.5rem; color: rgba(139,126,255,0.30); display: block; margin-bottom: 18px; }
    .no-announcements h3 { font-family: var(--font-hud); font-size: 1rem; font-weight: 700; color: var(--text-soft); margin-bottom: 10px; }
    .no-announcements p { color: var(--text-dim); font-size: 0.92rem; }

    /* ── ANNOUNCEMENT CARD ── */
    .post-card {
      background: var(--bg-card);
      border: 1px solid rgba(139,126,255,0.16);
      margin-bottom: 28px;
      position: relative;
      overflow: hidden;
      transition: border-color 0.3s, box-shadow 0.3s;
      animation: slideIn 0.55s ease both;
    }
    .post-card:hover {
      border-color: rgba(139,126,255,0.35);
      box-shadow: 0 0 36px rgba(139,126,255,0.10);
    }
    /* top accent line */
    .post-card::before {
      content: ''; position: absolute; top: 0; left: 0; right: 0; height: 1px;
      background: linear-gradient(90deg, transparent, var(--prc-violet), transparent);
      opacity: 0; transition: opacity 0.3s;
    }
    .post-card:hover::before { opacity: 1; }

    /* PINNED variant */
    .post-card.is-pinned {
      border-color: rgba(255,233,48,0.28);
      box-shadow: 0 0 30px rgba(255,233,48,0.07), inset 0 0 40px rgba(255,233,48,0.02);
    }
    .post-card.is-pinned::before {
      background: linear-gradient(90deg, transparent, var(--creo-volt), transparent);
      opacity: 1;
    }
    .post-card.is-pinned:hover { border-color: rgba(255,233,48,0.50); }

    /* ── CARD HEADER ── */
    .post-header {
      display: flex; align-items: flex-start;
      justify-content: space-between;
      padding: 22px 26px 16px;
      gap: 16px;
    }
    .post-identity { display: flex; align-items: center; gap: 14px; }
    .post-avatar {
      width: 48px; height: 48px; flex-shrink: 0;
      border: 1.5px solid rgba(139,126,255,0.40);
      overflow: hidden;
      background: rgba(139,126,255,0.08);
      display: flex; align-items: center; justify-content: center;
    }
    .post-avatar img { width: 100%; height: 100%; object-fit: contain; padding: 6px; }
    .post-meta {}
    .post-org-name {
      font-family: var(--font-hud); font-size: 0.75rem; font-weight: 700;
      color: var(--prc-violet); letter-spacing: 0.04em;
      text-shadow: 0 0 10px rgba(139,126,255,0.45);
      line-height: 1.2; margin-bottom: 3px;
    }
    .post-date {
      font-family: var(--font-hud); font-size: 0.56rem; font-weight: 500;
      color: var(--text-dim); letter-spacing: 0.08em;
      display: flex; align-items: center; gap: 6px;
    }
    .post-date i { font-size: 0.62rem; color: var(--text-dim); }

    /* pinned badge */
    .post-pinned-badge {
      display: inline-flex; align-items: center; gap: 6px;
      font-family: var(--font-hud); font-size: 0.50rem; font-weight: 700;
      letter-spacing: 0.14em; text-transform: uppercase;
      color: var(--creo-volt); border: 1px solid rgba(255,233,48,0.35);
      background: rgba(255,233,48,0.05); padding: 4px 10px; flex-shrink: 0;
      clip-path: polygon(4px 0%, 100% 0%, calc(100% - 4px) 100%, 0% 100%);
      text-shadow: 0 0 8px rgba(255,233,48,0.60);
    }
    .post-pinned-badge i { font-size: 0.60rem; }

    /* ── CARD TITLE ── */
    .post-title {
      font-family: var(--font-hud);
      font-size: clamp(1.05rem, 2.2vw, 1.35rem);
      font-weight: 800;
      color: #fff;
      letter-spacing: -0.01em;
      line-height: 1.20;
      padding: 0 26px 14px;
      /* Accessibility: large enough for poor eyesight */
    }
    .post-card.is-pinned .post-title { color: var(--creo-volt); text-shadow: 0 0 18px rgba(255,233,48,0.35); }

    /* ── DIVIDER ── */
    .post-divider {
      height: 1px; margin: 0 26px;
      background: linear-gradient(90deg, transparent, rgba(139,126,255,0.22), transparent);
    }

    /* ── CAPTION ── */
    .post-caption {
      padding: 18px 26px 20px;
      font-size: 1.02rem; /* slightly large for readability */
      color: var(--text-mid);
      line-height: 1.84;
      white-space: pre-wrap;
      word-break: break-word;
    }
    .post-caption a { color: var(--prc-violet); border-bottom: 1px solid rgba(139,126,255,0.30); transition: color 0.2s; }
    .post-caption a:hover { color: var(--prc-ice); }

    /* ── MEDIA GRID ── */
    .post-media { padding: 0 0 4px; }

    /* single image */
    .media-grid-1 .media-item { width: 100%; }
    .media-grid-1 .media-item img,
    .media-grid-1 .media-item video { width: 100%; max-height: 560px; object-fit: cover; display: block; }

    /* two images side by side */
    .media-grid-2 { display: grid; grid-template-columns: 1fr 1fr; gap: 3px; }
    .media-grid-2 .media-item { aspect-ratio: 1/1; overflow: hidden; }
    .media-grid-2 .media-item img,
    .media-grid-2 .media-item video { width: 100%; height: 100%; object-fit: cover; display: block; }

    /* three: big left, two stacked right */
    .media-grid-3 { display: grid; grid-template-columns: 2fr 1fr; gap: 3px; }
    .media-grid-3 .media-item:first-child { grid-row: span 2; }
    .media-grid-3 .media-item { overflow: hidden; }
    .media-grid-3 .media-item img,
    .media-grid-3 .media-item video { width: 100%; height: 100%; object-fit: cover; display: block; min-height: 180px; }

    /* four: 2x2 */
    .media-grid-4 { display: grid; grid-template-columns: 1fr 1fr; gap: 3px; }
    .media-grid-4 .media-item { aspect-ratio: 16/10; overflow: hidden; }
    .media-grid-4 .media-item img,
    .media-grid-4 .media-item video { width: 100%; height: 100%; object-fit: cover; display: block; }

    /* five+: 2-col, last shown with +N overlay */
    .media-grid-5 { display: grid; grid-template-columns: 1fr 1fr; gap: 3px; }
    .media-grid-5 .media-item { aspect-ratio: 16/10; overflow: hidden; position: relative; }
    .media-grid-5 .media-item img,
    .media-grid-5 .media-item video { width: 100%; height: 100%; object-fit: cover; display: block; }
    .media-grid-5 .media-item:nth-child(n+5) { display: none; }

    /* +more overlay on 4th item if 5+ */
    .media-more-overlay {
      position: absolute; inset: 0;
      background: rgba(3,2,13,0.75);
      display: flex; align-items: center; justify-content: center;
      font-family: var(--font-hud); font-size: 1.6rem; font-weight: 800;
      color: #fff; letter-spacing: -0.02em;
      text-shadow: 0 0 20px rgba(139,126,255,0.80);
      cursor: pointer !important;
    }

    /* hover brightening on all media items */
    .media-item { cursor: pointer !important; position: relative; overflow: hidden; }
    .media-item img, .media-item video { transition: filter 0.35s, transform 0.35s; filter: brightness(0.85) saturate(0.75); }
    .media-item:hover img, .media-item:hover video { filter: brightness(1.0) saturate(1.0); transform: scale(1.025); }

    /* video play icon */
    .media-item.is-video::after {
      content: ''; position: absolute; inset: 0;
      background: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 64 64'%3E%3Ccircle cx='32' cy='32' r='30' fill='rgba(0,0,0,0.55)'/%3E%3Cpolygon points='26,20 26,44 48,32' fill='rgba(196,238,255,0.90)'/%3E%3C/svg%3E") center/56px no-repeat;
      pointer-events: none;
    }

    /* ── CARD FOOTER ── */
    .post-footer {
      padding: 14px 26px 18px;
      display: flex; align-items: center; justify-content: space-between;
      border-top: 1px solid rgba(139,126,255,0.10);
      flex-wrap: wrap; gap: 10px;
    }
    .post-full-date {
      font-family: var(--font-hud); font-size: 0.54rem;
      color: var(--text-dim); letter-spacing: 0.08em;
      display: flex; align-items: center; gap: 6px;
    }
    .post-full-date i { color: rgba(139,126,255,0.50); }
    .post-media-count {
      font-family: var(--font-hud); font-size: 0.54rem;
      color: var(--text-dim); letter-spacing: 0.08em;
      display: flex; align-items: center; gap: 6px;
    }

    /* ── LIGHTBOX ── */
    .lightbox {
      display: none; position: fixed; inset: 0; z-index: 99990;
      background: rgba(3,2,13,0.96);
      align-items: center; justify-content: center;
      padding: 20px;
    }
    .lightbox.open { display: flex; }
    .lightbox-inner {
      position: relative; max-width: 1100px; width: 100%;
      display: flex; align-items: center; justify-content: center;
    }
    .lightbox-media { max-width: 100%; max-height: 85vh; object-fit: contain; display: block; box-shadow: 0 0 80px rgba(139,126,255,0.25); }
    .lightbox-close {
      position: absolute; top: -48px; right: 0;
      font-family: var(--font-hud); font-size: 0.60rem; font-weight: 700;
      letter-spacing: 0.14em; text-transform: uppercase;
      color: var(--text-soft); border: 1px solid rgba(139,126,255,0.28);
      background: rgba(3,2,13,0.80); padding: 6px 14px;
      cursor: pointer !important; transition: all 0.2s; display: flex; align-items: center; gap: 8px;
    }
    .lightbox-close:hover { color: var(--prc-violet); border-color: var(--prc-violet); }
    .lightbox-nav {
      position: absolute; top: 50%; transform: translateY(-50%);
      width: 44px; height: 44px;
      background: rgba(3,2,13,0.80); border: 1px solid rgba(139,126,255,0.35);
      color: var(--prc-ice); font-size: 1rem;
      display: flex; align-items: center; justify-content: center;
      cursor: pointer !important; transition: all 0.2s;
    }
    .lightbox-nav:hover { background: rgba(139,126,255,0.20); border-color: var(--prc-violet); }
    .lightbox-nav.prev { left: -60px; }
    .lightbox-nav.next { right: -60px; }

    /* ── SCROLL REVEAL ── */
    .reveal { opacity:0; transform:translateY(24px); transition: opacity 0.55s ease, transform 0.55s ease; }
    .reveal.visible { opacity:1; transform:translateY(0); }

    /* ── FOOTER ── */
    footer {
      background: rgba(0,0,6,0.95); border-top: 1px solid var(--border-neon); padding: 48px 0 28px;
      text-align: center;
    }
    .footer-simple-inner { max-width: 1340px; margin: 0 auto; padding: 0 36px; }
    .footer-simple-logo { display: flex; align-items: center; justify-content: center; gap: 12px; margin-bottom: 16px; }
    .footer-simple-logo img { height: 32px; width: auto; }
    .footer-simple-copy { font-family: var(--font-hud); font-size: 0.58rem; color: var(--text-soft); letter-spacing: 0.06em; }

    /* ── RESPONSIVE ── */
    @media (max-width: 768px) {
      body { cursor: auto; } button { cursor: pointer; }
      .cursor-dot, .cursor-ring { display: none; }
      .nav-links { display: none; } .nav-hamburger { display: flex; }
      .announcements-layout { padding: 40px 16px 80px; }
      .post-header { padding: 18px 18px 12px; }
      .post-title, .post-caption { padding-left: 18px; padding-right: 18px; }
      .post-divider { margin: 0 18px; }
      .post-footer { padding: 12px 18px 14px; }
      .lightbox-nav.prev { left: -8px; }
      .lightbox-nav.next { right: -8px; }
      .media-grid-3 { grid-template-columns: 1fr; }
      .media-grid-3 .media-item:first-child { grid-row: auto; }
    }
    @media (max-width: 520px) {
      :root { --nav-height: 58px; }
      .nav-inner { padding: 0 14px; }
      .nav-brand span { display: none; }
      .page-banner-inner { padding: 0 16px; }
      .post-caption { font-size: 0.98rem; }
    }

    ::-webkit-scrollbar { width: 4px; }
    ::-webkit-scrollbar-track { background: var(--bg-void); }
    ::-webkit-scrollbar-thumb { background: var(--prc-violet); box-shadow: 0 0 8px rgba(139,126,255,0.70); border-radius: 2px; }
  </style>
</head>
<body>

<div class="cursor-dot" id="cursorDot"></div>
<div class="cursor-ring" id="cursorRing"></div>
<div class="hex-grid" aria-hidden="true"></div>

<div class="page-wrapper">

  <!-- NAV -->
  <nav id="main-nav" role="navigation" aria-label="Main navigation">
    <div class="nav-inner">
      <a href="index.html" class="nav-logo" aria-label="PRC Home">
        <img src="assets/PRC White Logo.png" alt="Philippine Robotics Cup Logo" />
        <div class="nav-brand">Philippine Robotics Cup<span>By Creotec Philippines</span></div>
      </a>
      <ul class="nav-links" role="list">
        <li><a href="index.html">Home</a></li>
        <li><a href="categories.html">Categories</a></li>
        <li><a href="rankings.html">Rankings</a></li>
        <li><a href="announcements.php" class="active">Announcements</a></li>
        <li><a href="#">Shop</a></li>
        <li><a href="contact.html">Contact Us</a></li>
        <li><a href="#" class="nav-cta">Register Now</a></li>
      </ul>
      <button class="nav-hamburger" id="hamburger" type="button" aria-label="Open menu" aria-expanded="false">
        <span></span><span></span><span></span>
      </button>
    </div>
  </nav>

  <nav class="nav-mobile" id="mobile-menu" aria-label="Mobile navigation">
    <a href="index.html"><i class="fi fi-rr-home"></i>Home</a>
    <a href="categories.html"><i class="fi fi-rr-trophy"></i>Categories</a>
    <a href="rankings.html"><i class="fi fi-rr-list-check"></i>Rankings</a>
    <a href="announcements.php"><i class="fi fi-rr-megaphone"></i>Announcements</a>
    <a href="#"><i class="fi fi-rr-shopping-cart"></i>Shop</a>
    <a href="contact.html"><i class="fi fi-rr-envelope"></i>Contact Us</a>
    <a href="#" class="nav-cta"><i class="fi fi-rr-pen-field"></i>Register Now</a>
  </nav>

  <!-- PAGE BANNER -->
  <div class="page-banner">
    <div class="page-banner-scan"></div>
    <div class="page-banner-inner">
      <div class="page-banner-eyebrow"><span class="blink"></span> Official Channel // PRC 2026</div>
      <h1 class="page-banner-title">Official <span class="accent">Announcements</span></h1>
      <p class="page-banner-desc">Stay up to date with the latest news, updates, and important information from the Philippine Robotics Cup.</p>
    </div>
  </div>

  <!-- ANNOUNCEMENTS FEED -->
  <main class="announcements-layout" id="announcements-feed">

    <!-- FILTER BAR -->
    <div class="filter-bar reveal">
      <span class="filter-label">Filter:</span>
      <button class="filter-btn active" data-filter="all">All Posts</button>
      <button class="filter-btn" data-filter="pinned">📌 Pinned</button>
    </div>

    <?php if (empty($announcements)): ?>
    <div class="no-announcements reveal">
      <i class="fi fi-rr-megaphone"></i>
      <h3>No Announcements Yet</h3>
      <p>Check back soon for updates from the Philippine Robotics Cup.</p>
    </div>
    <?php else: ?>

    <?php foreach ($announcements as $i => $post):
      $delay = min($i * 80, 400);
      $media = $post['media'];
      $media_count = count($media);
      $grid_class = 'media-grid-' . min($media_count, 5);
      $pinned = (bool)$post['announcement_is_pinned'];
    ?>

    <article
      class="post-card<?= $pinned ? ' is-pinned' : '' ?> reveal"
      style="animation-delay:<?= $delay ?>ms"
      data-pinned="<?= $pinned ? '1' : '0' ?>"
      aria-label="Announcement: <?= htmlspecialchars($post['announcement_title']) ?>"
    >

      <!-- HEADER -->
      <div class="post-header">
        <div class="post-identity">
          <div class="post-avatar">
            <img src="assets/PRC White Logo.png" alt="PRC Logo" />
          </div>
          <div class="post-meta">
            <div class="post-org-name">Philippine Robotics Cup</div>
            <div class="post-date">
              <i class="fi fi-rr-clock"></i>
              <?= htmlspecialchars(fmt_date($post['announcement_date_posted'])) ?>
            </div>
          </div>
        </div>
        <?php if ($pinned): ?>
        <div class="post-pinned-badge" title="Pinned announcement">
          <i class="fi fi-rr-thumbtack"></i> Pinned
        </div>
        <?php endif; ?>
      </div>

      <!-- TITLE -->
      <h2 class="post-title"><?= htmlspecialchars($post['announcement_title']) ?></h2>

      <div class="post-divider"></div>

      <!-- CAPTION -->
      <?php if (!empty($post['announcement_caption'])): ?>
      <div class="post-caption"><?= nl2br(htmlspecialchars($post['announcement_caption'])) ?></div>
      <?php endif; ?>

      <!-- MEDIA -->
      <?php if ($media_count > 0): ?>
      <div class="post-media">
        <div class="<?= $grid_class ?>" id="media-grid-<?= $post['announcement_id'] ?>">
          <?php foreach ($media as $mi => $m):
            $is_video = ($m['media_type'] === 'video');
            $show_overlay = ($media_count > 4 && $mi === 3);
            $remaining = $media_count - 4;
          ?>
          <div
            class="media-item<?= $is_video ? ' is-video' : '' ?>"
            data-post-id="<?= $post['announcement_id'] ?>"
            data-media-index="<?= $mi ?>"
            onclick="openLightbox(<?= $post['announcement_id'] ?>, <?= $mi ?>)"
            role="button"
            tabindex="0"
            aria-label="View <?= $is_video ? 'video' : 'image' ?> <?= $mi+1 ?>"
          >
            <?php if ($is_video): ?>
              <video src="<?= htmlspecialchars($m['media_file_path']) ?>" preload="metadata" muted playsinline></video>
            <?php else: ?>
              <img src="<?= htmlspecialchars($m['media_file_path']) ?>" alt="Announcement media <?= $mi+1 ?>" loading="lazy" />
            <?php endif; ?>
            <?php if ($show_overlay): ?>
            <div class="media-more-overlay">+<?= $remaining ?></div>
            <?php endif; ?>
          </div>
          <?php endforeach; ?>
        </div>
      </div>
      <?php endif; ?>

      <!-- FOOTER -->
      <div class="post-footer">
        <div class="post-full-date">
          <i class="fi fi-rr-calendar"></i>
          <?= date('F j, Y \a\t g:i A', strtotime($post['announcement_date_posted'])) ?>
        </div>
        <?php if ($media_count > 0): ?>
        <div class="post-media-count">
          <i class="fi fi-rr-picture"></i>
          <?= $media_count ?> <?= $media_count === 1 ? 'attachment' : 'attachments' ?>
        </div>
        <?php endif; ?>
      </div>

    </article>

    <?php endforeach; ?>
    <?php endif; ?>

  </main><!-- /announcements-feed -->

  <!-- LIGHTBOX -->
  <div class="lightbox" id="lightbox" role="dialog" aria-modal="true" aria-label="Media viewer">
    <div class="lightbox-inner">
      <button class="lightbox-close" id="lb-close" aria-label="Close viewer">
        <i class="fi fi-rr-cross-small"></i> Close
      </button>
      <button class="lightbox-nav prev" id="lb-prev" aria-label="Previous"><i class="fi fi-rr-angle-left"></i></button>
      <div id="lb-content"></div>
      <button class="lightbox-nav next" id="lb-next" aria-label="Next"><i class="fi fi-rr-angle-right"></i></button>
    </div>
  </div>

  <!-- FOOTER -->
  <footer role="contentinfo">
    <div class="footer-simple-inner">
      <div class="footer-simple-logo">
        <img src="assets/PRC White Logo.png" alt="Philippine Robotics Cup" />
      </div>
      <p class="footer-simple-copy">&copy; 2026 Philippine Robotics Cup // Creotec Philippines Inc. All rights reserved.</p>
    </div>
  </footer>

</div><!-- /page-wrapper -->

<script>
// ── CURSOR ──
(function(){
  var dot = document.getElementById('cursorDot');
  var ring = document.getElementById('cursorRing');
  if (!dot || !ring) return;
  var mx=0,my=0,rx=0,ry=0;
  document.addEventListener('mousemove',function(e){ mx=e.clientX; my=e.clientY; dot.style.left=mx+'px'; dot.style.top=my+'px'; });
  (function loop(){ rx+=(mx-rx)*0.12; ry+=(my-ry)*0.12; ring.style.left=rx+'px'; ring.style.top=ry+'px'; requestAnimationFrame(loop); })();
  document.querySelectorAll('a,button,.post-card,.media-item').forEach(function(el){
    el.addEventListener('mouseenter',function(){ ring.classList.add('hovered'); dot.style.background='var(--creo-amber)'; });
    el.addEventListener('mouseleave',function(){ ring.classList.remove('hovered'); dot.style.background='var(--prc-violet)'; });
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
  menu.querySelectorAll('a').forEach(function(a){ a.addEventListener('click',function(){ menu.classList.remove('open'); btn.classList.remove('open'); document.body.style.overflow=''; }); });
  document.addEventListener('click',function(e){ if(menu.classList.contains('open')&&!btn.contains(e.target)&&!menu.contains(e.target)){ menu.classList.remove('open'); btn.classList.remove('open'); document.body.style.overflow=''; } });
})();

// ── SCROLL REVEAL ──
(function(){
  var els = document.querySelectorAll('.reveal');
  var obs = new IntersectionObserver(function(entries){
    entries.forEach(function(e){ if(e.isIntersecting){ e.target.classList.add('visible'); obs.unobserve(e.target); } });
  },{threshold:0.06,rootMargin:'0px 0px -20px 0px'});
  els.forEach(function(el){ obs.observe(el); });
})();

// ── FILTER ──
(function(){
  var btns = document.querySelectorAll('.filter-btn');
  btns.forEach(function(btn){
    btn.addEventListener('click',function(){
      btns.forEach(function(b){ b.classList.remove('active'); });
      btn.classList.add('active');
      var f = btn.getAttribute('data-filter');
      document.querySelectorAll('.post-card').forEach(function(card){
        if(f==='all') card.style.display='';
        else if(f==='pinned') card.style.display = card.getAttribute('data-pinned')==='1' ? '' : 'none';
      });
    });
  });
})();

// ── LIGHTBOX ──
var lbData = {};
<?php foreach ($announcements as $post):
  if (empty($post['media'])) continue;
  $items = [];
  foreach ($post['media'] as $m) {
    $items[] = ['src' => $m['media_file_path'], 'type' => $m['media_type']];
  }
  echo 'lbData[' . $post['announcement_id'] . '] = ' . json_encode($items) . ";\n";
endforeach; ?>

var lbPostId = null, lbIndex = 0;

function openLightbox(postId, idx) {
  lbPostId = postId; lbIndex = idx;
  renderLightbox();
  document.getElementById('lightbox').classList.add('open');
  document.body.style.overflow = 'hidden';
}

function renderLightbox() {
  var items = lbData[lbPostId];
  if (!items || !items.length) return;
  lbIndex = ((lbIndex % items.length) + items.length) % items.length;
  var item = items[lbIndex];
  var el;
  if (item.type === 'video') {
    el = '<video src="' + item.src + '" controls autoplay class="lightbox-media" style="max-height:85vh;"></video>';
  } else {
    el = '<img src="' + item.src + '" class="lightbox-media" alt="Announcement media" />';
  }
  document.getElementById('lb-content').innerHTML = el;
  var total = items.length;
  document.getElementById('lb-prev').style.display = total > 1 ? 'flex' : 'none';
  document.getElementById('lb-next').style.display = total > 1 ? 'flex' : 'none';
}

document.getElementById('lb-close').addEventListener('click', function() {
  document.getElementById('lightbox').classList.remove('open');
  document.getElementById('lb-content').innerHTML = '';
  document.body.style.overflow = '';
});
document.getElementById('lb-prev').addEventListener('click', function() { lbIndex--; renderLightbox(); });
document.getElementById('lb-next').addEventListener('click', function() { lbIndex++; renderLightbox(); });
document.getElementById('lightbox').addEventListener('click', function(e) {
  if (e.target === this) { this.classList.remove('open'); document.getElementById('lb-content').innerHTML = ''; document.body.style.overflow = ''; }
});
document.addEventListener('keydown', function(e) {
  var lb = document.getElementById('lightbox');
  if (!lb.classList.contains('open')) return;
  if (e.key === 'Escape') lb.click();
  if (e.key === 'ArrowLeft') { lbIndex--; renderLightbox(); }
  if (e.key === 'ArrowRight') { lbIndex++; renderLightbox(); }
});
</script>
</body>
</html>