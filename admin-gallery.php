<?php
// PRC-WebApp/admin-gallery.php
session_start();

mysqli_report(MYSQLI_REPORT_OFF); // return false on error instead of throwing exceptions

$db_host = 'localhost'; $db_user = 'root'; $db_pass = ''; $db_name = 'prc_db';
$conn = new mysqli($db_host, $db_user, $db_pass, $db_name);
if ($conn->connect_error) die('<p style="color:#ff6b6b;padding:40px;font-family:monospace;">DB error: ' . htmlspecialchars($conn->connect_error) . '</p>');

// ── UPLOAD CONFIG ──────────────────────────────────────────────
define('GALLERY_UPLOAD_DIR', __DIR__ . '/assets/gallery/');
define('GALLERY_UPLOAD_URL', 'assets/gallery/');
define('MAX_FILE_SIZE', 50 * 1024 * 1024);
define('ALLOWED_IMG', ['image/jpeg','image/png','image/gif','image/webp']);
if (!is_dir(GALLERY_UPLOAD_DIR)) mkdir(GALLERY_UPLOAD_DIR, 0755, true);

function set_flash($t, $m){ $_SESSION['gflash'] = ['type'=>$t,'msg'=>$m]; }
function get_flash(){ if(!empty($_SESSION['gflash'])){ $f=$_SESSION['gflash']; unset($_SESSION['gflash']); return $f; } return null; }

// ── POST HANDLERS ──────────────────────────────────────────────
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';

    // ── ADD FOLDER ──
    if ($action === 'add_folder') {
        $name  = trim($_POST['folder_name']  ?? '');
        $label = trim($_POST['folder_label'] ?? '');
        $sort  = (int)($_POST['folder_sort'] ?? 0);
        if ($name === '') { set_flash('error','Folder name is required.'); }
        else {
            $s = $conn->prepare("INSERT INTO prc_gallery_folders (folder_name,folder_label,folder_sort) VALUES (?,?,?)");
            $s->bind_param('ssi',$name,$label,$sort);
            $s->execute() ? set_flash('success','Folder "'.$name.'" created.') : set_flash('error','Folder name may already exist.');
            $s->close();
        }
    }

    // ── EDIT FOLDER ──
    if ($action === 'edit_folder') {
        $fid   = (int)($_POST['folder_id']    ?? 0);
        $name  = trim($_POST['folder_name']   ?? '');
        $label = trim($_POST['folder_label']  ?? '');
        $sort  = (int)($_POST['folder_sort']  ?? 0);
        if ($fid && $name !== '') {
            $s = $conn->prepare("UPDATE prc_gallery_folders SET folder_name=?,folder_label=?,folder_sort=? WHERE folder_id=?");
            $s->bind_param('ssii',$name,$label,$sort,$fid);
            if ($s->execute()) set_flash('success','Folder updated.');
            else set_flash('error','That folder name already exists. Choose a different name.');
            $s->close();
        } else set_flash('error','Invalid folder data.');
    }

    // ── DELETE FOLDER ──
    if ($action === 'delete_folder') {
        $fid = (int)($_POST['folder_id'] ?? 0);
        if ($fid) {
            // delete physical files
            $pr = $conn->query("SELECT photo_file FROM prc_gallery_photos WHERE folder_id=$fid");
            if ($pr) while($row=$pr->fetch_assoc()){ $fp=__DIR__.'/'.$row['photo_file']; if(file_exists($fp)) @unlink($fp); }
            $conn->query("DELETE FROM prc_gallery_folders WHERE folder_id=$fid");
            set_flash('success','Folder and all its photos deleted.');
        }
    }

    // ── ADD PHOTOS ──
    if ($action === 'add_photos') {
        $fid  = (int)($_POST['folder_id'] ?? 0);
        $cat  = trim($_POST['photo_category'] ?? '');
        $sort = (int)($_POST['photo_sort']    ?? 0);
        if (!$fid) { set_flash('error','Select a folder first.'); }
        elseif (empty($_FILES['photo_files']['name'][0])) { set_flash('error','No files selected.'); }
        else {
            $files   = $_FILES['photo_files'];
            $count   = count($files['name']);
            $uploaded = 0;
            for ($i = 0; $i < $count; $i++) {
                if ($files['error'][$i] !== UPLOAD_ERR_OK) continue;
                if ($files['size'][$i] > MAX_FILE_SIZE) continue;
                $mime = mime_content_type($files['tmp_name'][$i]);
                if (!in_array($mime, ALLOWED_IMG)) continue;
                $ext  = pathinfo($files['name'][$i], PATHINFO_EXTENSION);
                $safe = 'prc_gal_' . $fid . '_' . time() . '_' . $i . '.' . strtolower($ext);
                $dest = GALLERY_UPLOAD_DIR . $safe;
                if (move_uploaded_file($files['tmp_name'][$i], $dest)) {
                    $path    = GALLERY_UPLOAD_URL . $safe;
                    $caption = trim($_POST['photo_captions'][$i] ?? '');
                    $s = $conn->prepare("INSERT INTO prc_gallery_photos (folder_id,photo_file,photo_caption,photo_category,photo_sort) VALUES (?,?,?,?,?)");
                    $s->bind_param('isssi',$fid,$path,$caption,$cat,$sort);
                    $s->execute(); $s->close();
                    $uploaded++;
                }
            }
            set_flash('success',$uploaded . ' photo(s) uploaded.');
        }
    }

    // ── EDIT PHOTO ──
    if ($action === 'edit_photo') {
        $pid  = (int)($_POST['photo_id']       ?? 0);
        $fid  = (int)($_POST['folder_id']      ?? 0);
        $cap  = trim($_POST['photo_caption']   ?? '');
        $cat  = trim($_POST['photo_category']  ?? '');
        $sort = (int)($_POST['photo_sort']     ?? 0);
        if ($pid) {
            $s = $conn->prepare("UPDATE prc_gallery_photos SET folder_id=?,photo_caption=?,photo_category=?,photo_sort=? WHERE photo_id=?");
            $s->bind_param('issii',$fid,$cap,$cat,$sort,$pid);
            $s->execute(); set_flash('success','Photo updated.'); $s->close();
        }
    }

    // ── DELETE PHOTO ──
    if ($action === 'delete_photo') {
        $pid = (int)($_POST['photo_id'] ?? 0);
        if ($pid) {
            $r = $conn->query("SELECT photo_file FROM prc_gallery_photos WHERE photo_id=$pid");
            if ($row = $r->fetch_assoc()){ $fp=__DIR__.'/'.$row['photo_file']; if(file_exists($fp)) @unlink($fp); }
            $conn->query("DELETE FROM prc_gallery_photos WHERE photo_id=$pid");
            set_flash('success','Photo deleted.');
        }
    }

    header('Location: admin-gallery.php'); exit;
}

// ── FETCH DATA ─────────────────────────────────────────────────
$folders = [];
$fr = $conn->query("SELECT * FROM prc_gallery_folders ORDER BY folder_sort ASC, folder_name DESC");
if ($fr) while($r=$fr->fetch_assoc()) $folders[] = $r;

$photos_by_folder = [];
$pr = $conn->query("SELECT * FROM prc_gallery_photos ORDER BY folder_id, photo_sort ASC, photo_id DESC");
if ($pr) while($r=$pr->fetch_assoc()) $photos_by_folder[$r['folder_id']][] = $r;

$total_photos = array_sum(array_map('count', $photos_by_folder));
$flash = get_flash();
$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <meta name="robots" content="noindex, nofollow" />
  <title>ADMIN — Gallery | Philippine Robotics Cup</title>
  <link rel="icon" type="image/png" href="assets/favicon.png" />
  <link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@400;600;700;800;900&family=Exo+2:wght@300;400;500;600;700&display=swap" rel="stylesheet"/>
  <link rel='stylesheet' href='https://cdn-uicons.flaticon.com/uicons-regular-rounded/css/uicons-regular-rounded.css'>
  <link rel='stylesheet' href='https://cdn-uicons.flaticon.com/uicons-solid-rounded/css/uicons-solid-rounded.css'>
  <link rel='stylesheet' href='https://cdn-uicons.flaticon.com/uicons-brands/css/uicons-brands.css'>
  <style>
    :root{
      --prc-violet:#8B7EFF; --prc-ice:#C4EEFF; --creo-amber:#FFA030;
      --creo-volt:#FFE930; --creo-sky:#44D9FF; --admin-red:#FF4D6A;
      --admin-green:#44FF88; --bg-void:#03020D; --bg-deep:#06051A;
      --bg-card:#0A0918; --border-neon:rgba(139,126,255,.22);
      --text-high:#F2EEFF; --text-mid:#C8C0F0; --text-soft:#9A90CC; --text-dim:#7068A8;
      --nav-height:72px; --font-hud:'Orbitron',monospace; --font-body:'Exo 2',sans-serif;
    }
    *,*::before,*::after{box-sizing:border-box;margin:0;padding:0}
    html{scroll-behavior:smooth}
    body{font-family:var(--font-body);background:var(--bg-void);color:var(--text-high);overflow-x:hidden;line-height:1.6;cursor:none}
    img{max-width:100%;display:block} a{text-decoration:none;color:inherit} ul{list-style:none}
    button{font-family:inherit;cursor:none;border:none;background:none}

    /* CURSOR */
    .cursor-dot{position:fixed;width:8px;height:8px;border-radius:50%;background:var(--admin-red);pointer-events:none;z-index:99999;transform:translate(-50%,-50%);box-shadow:0 0 18px rgba(255,77,106,.80);transition:transform .1s}
    .cursor-ring{position:fixed;width:36px;height:36px;border-radius:50%;border:1px solid rgba(255,77,106,.60);pointer-events:none;z-index:99998;transform:translate(-50%,-50%);transition:width .25s,height .25s}
    .cursor-ring.hovered{width:52px;height:52px;border-color:var(--creo-amber)}
    body::after{content:'';position:fixed;inset:0;z-index:9998;pointer-events:none;background:repeating-linear-gradient(to bottom,transparent,transparent 2px,rgba(0,0,0,.04) 2px,rgba(0,0,0,.04) 4px)}
    .hex-grid{position:fixed;inset:0;z-index:0;pointer-events:none;background-image:linear-gradient(rgba(255,77,106,.03) 1px,transparent 1px),linear-gradient(90deg,rgba(255,77,106,.03) 1px,transparent 1px);background-size:50px 50px}
    .hex-grid::before{content:'';position:absolute;inset:0;background:radial-gradient(ellipse 70% 60% at 50% 0%,rgba(255,77,106,.08) 0%,transparent 70%)}

    @keyframes neonPulse{0%,100%{opacity:1}50%{opacity:.7}}
    @keyframes slideDown{from{opacity:0;transform:translateY(-16px)}to{opacity:1;transform:translateY(0)}}
    @keyframes scanDown{from{transform:translateY(-100%)}to{transform:translateY(100vh)}}
    @keyframes fadeIn{from{opacity:0}to{opacity:1}}

    /* NAV */
    #main-nav{position:fixed;top:0;left:0;right:0;height:var(--nav-height);z-index:1000;background:rgba(6,2,10,.97);backdrop-filter:blur(20px);border-bottom:1px solid rgba(255,77,106,.30);box-shadow:0 0 30px rgba(255,77,106,.10)}
    .nav-inner{max-width:1340px;margin:0 auto;height:100%;padding:0 36px;display:flex;align-items:center;justify-content:space-between;gap:16px}
    .nav-logo{display:flex;align-items:center;gap:12px;flex-shrink:0}
    .nav-logo img{height:38px;width:auto}
    .nav-brand-wrap{display:flex;flex-direction:column;gap:2px}
    .nav-brand-top{display:flex;align-items:center;gap:8px}
    .nav-brand-name{font-family:var(--font-hud);font-weight:700;font-size:.72rem;letter-spacing:.06em;color:var(--prc-violet);text-shadow:0 0 12px rgba(139,126,255,.65)}
    .nav-admin-badge{font-family:var(--font-hud);font-size:.60rem;font-weight:900;letter-spacing:.18em;text-transform:uppercase;color:var(--admin-red);text-shadow:0 0 14px rgba(255,77,106,.90);border:1.5px solid rgba(255,77,106,.55);padding:2px 9px;background:rgba(255,77,106,.08);clip-path:polygon(4px 0%,100% 0%,calc(100% - 4px) 100%,0% 100%);animation:neonPulse 2.5s ease-in-out infinite}
    .nav-brand-sub{font-family:var(--font-hud);font-size:.54rem;font-weight:400;letter-spacing:.10em;text-transform:uppercase;color:var(--text-dim)}
    .nav-links{display:flex;align-items:center;gap:6px}
    .nav-links a{font-family:var(--font-hud);font-size:.65rem;font-weight:600;color:var(--text-mid);padding:8px 16px;letter-spacing:.08em;text-transform:uppercase;border:1px solid transparent;transition:all .2s;white-space:nowrap}
    .nav-links a.active,.nav-links a:hover{color:var(--admin-red);border-color:rgba(255,77,106,.35);background:rgba(255,77,106,.07)}
    .nav-public{font-family:var(--font-hud);font-size:.58rem;font-weight:700;letter-spacing:.10em;text-transform:uppercase;color:var(--prc-violet) !important;border:1px solid rgba(139,126,255,.35) !important;padding:7px 16px;clip-path:polygon(6px 0%,100% 0%,calc(100% - 6px) 100%,0% 100%);transition:all .25s;display:inline-flex;align-items:center;gap:8px}
    .nav-public:hover{background:rgba(139,126,255,.12) !important;color:#fff !important;border-color:var(--prc-violet) !important}
    .nav-hamburger{display:none;flex-direction:column;justify-content:center;align-items:center;gap:5px;width:44px;height:44px;padding:0;background:rgba(255,77,106,.06);border:1px solid rgba(255,77,106,.25);border-radius:4px;flex-shrink:0;z-index:1002;transition:all .2s;-webkit-tap-highlight-color:transparent}
    .nav-hamburger span{width:20px;height:1.5px;background:var(--admin-red);border-radius:2px;transition:transform .28s,opacity .28s;display:block;pointer-events:none}
    .nav-hamburger.open span:nth-child(1){transform:rotate(45deg) translate(5px,5px)}
    .nav-hamburger.open span:nth-child(2){opacity:0}
    .nav-hamburger.open span:nth-child(3){transform:rotate(-45deg) translate(5px,-5px)}
    .nav-mobile{display:none;position:fixed;top:var(--nav-height);left:0;right:0;background:rgba(6,2,10,.98);backdrop-filter:blur(20px);border-bottom:1px solid rgba(255,77,106,.25);padding:12px 18px 24px;z-index:1000;flex-direction:column;gap:2px}
    .nav-mobile.open{display:flex}
    .nav-mobile a{font-family:var(--font-hud);font-size:.70rem;font-weight:600;color:var(--text-mid);padding:13px 14px;letter-spacing:.08em;text-transform:uppercase;transition:all .2s;display:flex;align-items:center;gap:12px}
    .nav-mobile a i{font-size:1rem;color:var(--admin-red)}
    .nav-mobile a:hover{color:var(--admin-red);background:rgba(255,77,106,.07)}

    /* PAGE WRAPPER */
    .page-wrapper{position:relative;z-index:1;padding-top:var(--nav-height)}

    /* PAGE BANNER */
    .page-banner{position:relative;padding:60px 0 48px;border-bottom:1px solid rgba(255,77,106,.20);overflow:hidden}
    .page-banner::before{content:'';position:absolute;inset:0;background:radial-gradient(ellipse 60% 80% at 50% 0%,rgba(255,77,106,.09) 0%,transparent 70%)}
    .page-banner-scan{position:absolute;inset:0;overflow:hidden;pointer-events:none}
    .page-banner-scan::after{content:'';position:absolute;left:0;right:0;height:1px;background:linear-gradient(90deg,transparent,var(--admin-red),transparent);animation:scanDown 4s linear infinite;box-shadow:0 0 10px rgba(255,77,106,.70)}
    .page-banner-inner{max-width:1340px;margin:0 auto;padding:0 36px;position:relative;z-index:2;display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:20px}
    .page-banner-eyebrow{display:inline-flex;align-items:center;gap:10px;font-family:var(--font-hud);font-size:.58rem;font-weight:700;letter-spacing:.20em;text-transform:uppercase;color:var(--admin-red);margin-bottom:10px;text-shadow:0 0 10px rgba(255,77,106,.70)}
    .dot-live{width:7px;height:7px;background:var(--admin-red);border-radius:50%;box-shadow:0 0 8px rgba(255,77,106,.90);animation:neonPulse 1s ease-in-out infinite}
    .page-banner-title{font-family:var(--font-hud);font-size:clamp(1.6rem,4vw,2.8rem);font-weight:900;letter-spacing:-.01em;line-height:1.05;color:#fff}
    .accent-admin{color:var(--admin-red);text-shadow:0 0 22px rgba(255,77,106,.80)}
    .banner-stats{display:flex;gap:16px;flex-wrap:wrap}
    .banner-stat{background:rgba(255,77,106,.06);border:1px solid rgba(255,77,106,.20);padding:12px 22px;text-align:center;clip-path:polygon(6px 0%,100% 0%,calc(100% - 6px) 100%,0% 100%)}
    .banner-stat-num{font-family:var(--font-hud);font-size:1.6rem;font-weight:800;color:var(--admin-red);display:block;line-height:1;text-shadow:0 0 14px rgba(255,77,106,.70)}
    .banner-stat-lbl{font-family:var(--font-hud);font-size:.50rem;color:var(--text-soft);text-transform:uppercase;letter-spacing:.12em;display:block;margin-top:4px}

    /* FLASH */
    .flash-message{max-width:1280px;margin:20px auto 0;padding:0 36px;animation:slideDown .35s ease}
    .flash-inner{padding:14px 22px;display:flex;align-items:center;gap:12px;font-family:var(--font-hud);font-size:.65rem;font-weight:600;letter-spacing:.08em;border:1px solid}
    .flash-inner.success{color:var(--admin-green);border-color:rgba(68,255,136,.35);background:rgba(68,255,136,.06)}
    .flash-inner.error{color:var(--admin-red);border-color:rgba(255,77,106,.35);background:rgba(255,77,106,.06)}

    /* MAIN LAYOUT */
    .admin-layout{max-width:1280px;margin:0 auto;padding:36px 36px 100px;display:grid;grid-template-columns:320px 1fr;gap:28px;align-items:start}

    /* SIDEBAR / LEFT PANEL */
    .sidebar-panel{display:flex;flex-direction:column;gap:20px;position:sticky;top:calc(var(--nav-height) + 20px)}
    .panel-card{background:var(--bg-card);border:1px solid rgba(139,126,255,.18);position:relative}
    .panel-card::before{content:'';position:absolute;top:0;left:0;right:0;height:1px;background:linear-gradient(90deg,transparent,var(--prc-violet),transparent)}
    .panel-card.red-card{border-color:rgba(255,77,106,.22)}
    .panel-card.red-card::before{background:linear-gradient(90deg,transparent,var(--admin-red),transparent)}
    .panel-hdr{background:rgba(139,126,255,.05);padding:16px 22px;border-bottom:1px solid rgba(139,126,255,.12);display:flex;align-items:center;gap:10px}
    .panel-hdr.red{background:rgba(255,77,106,.05);border-color:rgba(255,77,106,.15)}
    .panel-hdr i{color:var(--prc-violet);font-size:.95rem}
    .panel-hdr.red i{color:var(--admin-red)}
    .panel-hdr-text h3{font-family:var(--font-hud);font-size:.72rem;font-weight:700;letter-spacing:.06em;color:var(--text-high)}
    .panel-hdr-text p{font-size:.76rem;color:var(--text-soft);margin-top:2px}
    .panel-body{padding:20px 22px}

    /* FORM FIELDS */
    .field{margin-bottom:16px}
    .field:last-of-type{margin-bottom:0}
    .field-label{font-family:var(--font-hud);font-size:.52rem;font-weight:700;letter-spacing:.14em;text-transform:uppercase;color:var(--text-soft);display:flex;align-items:center;gap:6px;margin-bottom:7px}
    .field-label .req{color:var(--admin-red)}
    .field-input,.field-select,.field-textarea{width:100%;padding:11px 14px;background:rgba(139,126,255,.04);border:1px solid rgba(139,126,255,.22);color:var(--text-high);font-family:var(--font-body);font-size:.90rem;outline:none;transition:border-color .22s,box-shadow .22s;appearance:none}
    .field-input::placeholder,.field-textarea::placeholder{color:var(--text-dim)}
    .field-input:focus,.field-select:focus,.field-textarea:focus{border-color:var(--prc-violet);box-shadow:0 0 0 2px rgba(139,126,255,.14)}
    .field-textarea{resize:vertical;min-height:70px}
    .field-hint{font-size:.76rem;color:var(--text-dim);margin-top:5px}

    /* Upload zone */
    .upload-zone{border:1.5px dashed rgba(139,126,255,.28);background:rgba(139,126,255,.03);padding:22px;text-align:center;cursor:pointer !important;transition:all .25s;position:relative;overflow:hidden}
    .upload-zone:hover,.upload-zone.dragover{border-color:var(--prc-violet);background:rgba(139,126,255,.08);box-shadow:0 0 16px rgba(139,126,255,.15)}
    .upload-zone input[type="file"]{position:absolute;inset:0;opacity:0;cursor:pointer !important;width:100%;height:100%}
    .upload-zone-icon{font-size:1.5rem;color:rgba(139,126,255,.40);display:block;margin-bottom:8px}
    .upload-zone-label{font-family:var(--font-hud);font-size:.60rem;font-weight:700;color:var(--text-soft);letter-spacing:.08em;display:block;margin-bottom:3px}
    .upload-zone-sub{font-size:.76rem;color:var(--text-dim)}
    .upload-preview{display:flex;flex-wrap:wrap;gap:6px;margin-top:10px}
    .upload-preview-thumb{width:60px;height:60px;overflow:hidden;border:1px solid rgba(139,126,255,.25)}
    .upload-preview-thumb img{width:100%;height:100%;object-fit:cover}

    /* BUTTONS */
    .btn-primary{display:inline-flex;align-items:center;justify-content:center;gap:8px;width:100%;padding:12px;font-family:var(--font-hud);font-size:.62rem;font-weight:700;letter-spacing:.12em;text-transform:uppercase;color:var(--prc-violet);border:1px solid var(--prc-violet) !important;box-shadow:0 0 14px rgba(139,126,255,.22);cursor:pointer !important;transition:all .25s;background:transparent;clip-path:polygon(8px 0%,100% 0%,calc(100% - 8px) 100%,0% 100%)}
    .btn-primary:hover{background:rgba(139,126,255,.12);box-shadow:0 0 28px rgba(139,126,255,.48);color:#fff;transform:translateY(-1px)}
    .btn-red{color:var(--admin-red);border-color:rgba(255,77,106,.40) !important;box-shadow:0 0 14px rgba(255,77,106,.18)}
    .btn-red:hover{background:rgba(255,77,106,.12);box-shadow:0 0 28px rgba(255,77,106,.45)}

    /* ── FOLDER LIST (right panel) ── */
    .right-panel{display:flex;flex-direction:column;gap:0}
    .right-panel-header{display:flex;align-items:center;justify-content:space-between;margin-bottom:20px;flex-wrap:wrap;gap:12px}
    .right-panel-title{font-family:var(--font-hud);font-size:.82rem;font-weight:700;letter-spacing:.06em;color:var(--text-high);display:flex;align-items:center;gap:10px}
    .count-pill{font-family:var(--font-hud);font-size:.50rem;font-weight:700;padding:3px 10px;border:1px solid rgba(255,77,106,.30);background:rgba(255,77,106,.07);color:var(--admin-red);letter-spacing:.10em}

    /* FOLDER BLOCK */
    .folder-block{border:1px solid rgba(139,126,255,.16);margin-bottom:24px;overflow:hidden}
    .folder-block-header{display:flex;align-items:center;justify-content:space-between;padding:14px 20px;background:rgba(139,126,255,.05);border-bottom:1px solid rgba(139,126,255,.12);gap:12px;flex-wrap:wrap}
    .folder-block-title{font-family:var(--font-hud);font-size:.80rem;font-weight:800;color:var(--text-high);letter-spacing:.04em;display:flex;align-items:center;gap:10px}
    .folder-block-title i{color:var(--prc-violet);font-size:.88rem}
    .folder-photo-count{font-family:var(--font-hud);font-size:.50rem;color:var(--text-dim);letter-spacing:.10em;text-transform:uppercase;padding:2px 8px;border:1px solid rgba(139,126,255,.20);background:rgba(139,126,255,.05)}
    .folder-actions{display:flex;align-items:center;gap:4px}

    /* ── ICON BUTTONS ── */
    .icon-btn{
      display:inline-flex;align-items:center;justify-content:center;
      width:34px;height:34px;
      border:1px solid;border-radius:2px;
      cursor:pointer !important;
      transition:all .20s;
      background:transparent;
      font-size:.85rem;
      position:relative;
    }
    /* tooltip */
    .icon-btn::after{
      content:attr(data-tip);
      position:absolute;bottom:calc(100% + 8px);left:50%;transform:translateX(-50%);
      white-space:nowrap;
      font-family:var(--font-hud);font-size:.48rem;font-weight:700;letter-spacing:.10em;text-transform:uppercase;
      padding:5px 10px;
      background:rgba(6,5,26,.96);border:1px solid rgba(139,126,255,.30);color:var(--text-mid);
      pointer-events:none;opacity:0;transition:opacity .2s;
      z-index:100;
    }
    .icon-btn::before{
      content:'';
      position:absolute;bottom:calc(100% + 2px);left:50%;transform:translateX(-50%);
      border:5px solid transparent;border-top-color:rgba(139,126,255,.30);
      pointer-events:none;opacity:0;transition:opacity .2s;
      z-index:100;
    }
    .icon-btn:hover::after,.icon-btn:hover::before{opacity:1}

    .icon-btn.edit{color:var(--creo-sky);border-color:rgba(68,217,255,.30);background:rgba(68,217,255,.04)}
    .icon-btn.edit:hover{background:rgba(68,217,255,.14);box-shadow:0 0 12px rgba(68,217,255,.28)}
    .icon-btn.edit::after{border-color:rgba(68,217,255,.40)}
    .icon-btn.edit::before{border-top-color:rgba(68,217,255,.40)}

    .icon-btn.add{color:var(--admin-green);border-color:rgba(68,255,136,.30);background:rgba(68,255,136,.04)}
    .icon-btn.add:hover{background:rgba(68,255,136,.14);box-shadow:0 0 12px rgba(68,255,136,.28)}
    .icon-btn.add::after{border-color:rgba(68,255,136,.40)}
    .icon-btn.add::before{border-top-color:rgba(68,255,136,.40)}

    .icon-btn.del{color:var(--admin-red);border-color:rgba(255,77,106,.30);background:rgba(255,77,106,.04)}
    .icon-btn.del:hover{background:rgba(255,77,106,.14);box-shadow:0 0 12px rgba(255,77,106,.28)}
    .icon-btn.del::after{border-color:rgba(255,77,106,.40)}
    .icon-btn.del::before{border-top-color:rgba(255,77,106,.40)}

    /* PHOTO GRID inside folder */
    .photo-grid{display:grid;grid-template-columns:repeat(auto-fill,minmax(130px,1fr));gap:8px;padding:16px 20px}
    .photo-item{position:relative;aspect-ratio:4/3;overflow:hidden;border:1px solid rgba(139,126,255,.12);background:rgba(0,0,8,.60)}
    .photo-item img{width:100%;height:100%;object-fit:cover;display:block;filter:brightness(.80) saturate(.70);transition:filter .3s}
    .photo-item:hover img{filter:brightness(.95) saturate(1)}
    .photo-item-overlay{position:absolute;inset:0;display:flex;flex-direction:column;justify-content:flex-end;padding:8px;background:linear-gradient(to top,rgba(3,2,13,.90) 0%,transparent 60%);opacity:0;transition:opacity .3s}
    .photo-item:hover .photo-item-overlay{opacity:1}
    .photo-item-cap{font-family:var(--font-hud);font-size:.44rem;color:var(--text-mid);letter-spacing:.06em;margin-bottom:6px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap}
    .photo-item-actions{display:flex;gap:4px}

    /* keep tooltip above grid items */
    .photo-item{overflow:visible}
    .photo-item > img{overflow:hidden;border-radius:0}

    .no-photos{padding:32px;text-align:center;font-family:var(--font-hud);font-size:.60rem;color:var(--text-dim);letter-spacing:.10em}

    /* MODALS */
    .modal-overlay{display:none;position:fixed;inset:0;z-index:9000;background:rgba(3,2,13,.88);backdrop-filter:blur(10px);align-items:center;justify-content:center;padding:20px}
    .modal-overlay.open{display:flex}
    .modal-box{background:var(--bg-card);border:1px solid var(--border-neon);max-width:500px;width:100%;position:relative;max-height:90vh;overflow-y:auto}
    .modal-box::before{content:'';position:absolute;top:0;left:0;right:0;height:1px;background:linear-gradient(90deg,transparent,var(--prc-violet),transparent)}
    .modal-hdr{padding:18px 22px 14px;border-bottom:1px solid rgba(139,126,255,.14);background:rgba(139,126,255,.06);display:flex;align-items:center;justify-content:space-between;gap:12px}
    .modal-hdr h3{font-family:var(--font-hud);font-size:.75rem;font-weight:700;letter-spacing:.06em;color:var(--prc-violet);text-shadow:0 0 10px rgba(139,126,255,.45)}
    .modal-close{width:30px;height:30px;background:rgba(139,126,255,.06);border:1px solid rgba(139,126,255,.20) !important;color:var(--text-soft);display:flex;align-items:center;justify-content:center;cursor:pointer !important;font-size:.75rem;transition:all .2s}
    .modal-close:hover{background:rgba(139,126,255,.14);color:var(--prc-violet)}
    .modal-body{padding:22px}
    .modal-footer{padding:14px 22px;border-top:1px solid rgba(139,126,255,.12);display:flex;gap:10px;justify-content:flex-end}

    /* DELETE CONFIRM */
    .confirm-box{max-width:420px}
    .confirm-icon{font-size:2rem;color:var(--admin-red);display:block;margin-bottom:14px}
    .confirm-title{font-family:var(--font-hud);font-size:.95rem;font-weight:800;color:#fff;margin-bottom:10px}
    .confirm-text{font-size:.90rem;color:var(--text-mid);margin-bottom:0;line-height:1.70}

    /* RESPONSIVE */
    @media(max-width:1000px){.admin-layout{grid-template-columns:1fr}.sidebar-panel{position:static}}
    @media(max-width:768px){
      body{cursor:auto} button{cursor:pointer}
      .cursor-dot,.cursor-ring{display:none}
      .nav-links{display:none} .nav-hamburger{display:flex}
      .admin-layout{padding:20px 16px 80px}
      .photo-grid{grid-template-columns:repeat(auto-fill,minmax(100px,1fr))}
    }
    @media(max-width:520px){
      :root{--nav-height:58px}
      .nav-inner{padding:0 14px}
      .flash-message{padding:0 16px}
      .page-banner-inner{padding:0 16px}
    }
    ::-webkit-scrollbar{width:4px}
    ::-webkit-scrollbar-track{background:var(--bg-void)}
    ::-webkit-scrollbar-thumb{background:var(--admin-red);border-radius:2px}
  </style>
</head>
<body>
<div class="cursor-dot" id="cursorDot"></div>
<div class="cursor-ring" id="cursorRing"></div>
<div class="hex-grid" aria-hidden="true"></div>

<div class="page-wrapper">

  <!-- NAV -->
  <nav id="main-nav" role="navigation" aria-label="Admin navigation">
    <div class="nav-inner">
      <a href="admin-gallery.php" class="nav-logo">
        <img src="assets/PRC White Logo.png" alt="PRC Logo" />
        <div class="nav-brand-wrap">
          <div class="nav-brand-top">
            <span class="nav-brand-name">Philippine Robotics Cup</span>
            <span class="nav-admin-badge">&#9632; ADMIN</span>
          </div>
          <span class="nav-brand-sub">By Creotec Philippines</span>
        </div>
      </a>
      <ul class="nav-links" role="list">
        <li><a href="admin-gallery.php" class="active"><i class="fi fi-rr-picture" style="margin-right:6px"></i>Gallery</a></li>
        <li><a href="admin-announcements.php"><i class="fi fi-rr-megaphone" style="margin-right:6px"></i>Announcements</a></li>
        <li><a href="gallery.php" class="nav-public" target="_blank"><i class="fi fi-rr-eye"></i> Public Gallery</a></li>
      </ul>
      <button class="nav-hamburger" id="hamburger" type="button" aria-label="Open menu" aria-expanded="false">
        <span></span><span></span><span></span>
      </button>
    </div>
  </nav>
  <nav class="nav-mobile" id="mobile-menu">
    <a href="admin-gallery.php"><i class="fi fi-rr-picture"></i>Gallery</a>
    <a href="admin-announcements.php"><i class="fi fi-rr-megaphone"></i>Announcements</a>
    <a href="gallery.php" target="_blank"><i class="fi fi-rr-eye"></i>Public Gallery</a>
  </nav>

  <!-- BANNER -->
  <div class="page-banner">
    <div class="page-banner-scan"></div>
    <div class="page-banner-inner">
      <div>
        <div class="page-banner-eyebrow"><span class="dot-live"></span> Admin Panel // PRC Gallery</div>
        <h1 class="page-banner-title"><span class="accent-admin">ADMIN</span> — Manage Gallery</h1>
      </div>
      <div class="banner-stats">
        <div class="banner-stat"><span class="banner-stat-num"><?= count($folders) ?></span><span class="banner-stat-lbl">Folders</span></div>
        <div class="banner-stat"><span class="banner-stat-num"><?= $total_photos ?></span><span class="banner-stat-lbl">Photos</span></div>
      </div>
    </div>
  </div>

  <!-- FLASH -->
  <?php if ($flash): ?>
  <div class="flash-message">
    <div class="flash-inner <?= $flash['type'] ?>">
      <i class="fi fi-<?= $flash['type']==='success' ? 'rr-check' : 'rr-cross' ?>"></i>
      <?= htmlspecialchars($flash['msg']) ?>
    </div>
  </div>
  <?php endif; ?>

  <!-- MAIN -->
  <div class="admin-layout">

    <!-- ── LEFT SIDEBAR ── -->
    <aside class="sidebar-panel">

      <!-- ADD FOLDER -->
      <div class="panel-card">
        <div class="panel-hdr">
          <i class="fi fi-rr-folder-add"></i>
          <div class="panel-hdr-text"><h3>New Folder</h3><p>Create a year / event folder</p></div>
        </div>
        <div class="panel-body">
          <form method="POST" action="admin-gallery.php" id="form-add-folder">
            <input type="hidden" name="action" value="add_folder" />
            <div class="field">
              <label class="field-label">Folder Name <span class="req">*</span></label>
              <input class="field-input" type="text" name="folder_name" placeholder="e.g. 2026" maxlength="100" required />
              <div class="field-hint">Used in IDs and filters — keep it short.</div>
            </div>
            <div class="field">
              <label class="field-label">Display Label</label>
              <input class="field-input" type="text" name="folder_label" placeholder="e.g. 2026 Competition" maxlength="150" />
            </div>
            <div class="field" style="margin-bottom:18px">
              <label class="field-label">Sort Order</label>
              <input class="field-input" type="number" name="folder_sort" value="0" min="0" />
              <div class="field-hint">Lower number = shown first.</div>
            </div>
            <button type="submit" class="btn-primary"><i class="fi fi-rr-plus"></i> Create Folder</button>
          </form>
        </div>
      </div>

      <!-- UPLOAD PHOTOS -->
      <div class="panel-card red-card">
        <div class="panel-hdr red">
          <i class="fi fi-rr-cloud-upload"></i>
          <div class="panel-hdr-text"><h3>Upload Photos</h3><p>Add images to a folder</p></div>
        </div>
        <div class="panel-body">
          <form method="POST" action="admin-gallery.php" enctype="multipart/form-data" id="form-upload">
            <input type="hidden" name="action" value="add_photos" />
            <div class="field">
              <label class="field-label">Target Folder <span class="req">*</span></label>
              <select class="field-select" name="folder_id" required>
                <option value="" disabled selected>— Select folder —</option>
                <?php foreach ($folders as $f): ?>
                <option value="<?= $f['folder_id'] ?>"><?= htmlspecialchars($f['folder_label'] ?: $f['folder_name']) ?></option>
                <?php endforeach; ?>
              </select>
            </div>
            <div class="field">
              <label class="field-label">Category</label>
              <input class="field-input" type="text" name="photo_category" placeholder="e.g. Drone Soccer, MakeX" maxlength="100" />
            </div>
            <div class="field">
              <label class="field-label">Sort Order</label>
              <input class="field-input" type="number" name="photo_sort" value="0" min="0" />
            </div>
            <div class="field">
              <label class="field-label">Images <span class="req">*</span></label>
              <div class="upload-zone" id="upload-zone">
                <input type="file" name="photo_files[]" id="photo_files" multiple accept="image/jpeg,image/png,image/gif,image/webp" aria-label="Upload photos" />
                <i class="fi fi-rr-picture upload-zone-icon"></i>
                <span class="upload-zone-label">Click or drag images here</span>
                <span class="upload-zone-sub">JPG, PNG, GIF, WEBP — max 50 MB each</span>
              </div>
              <div class="upload-preview" id="upload-preview"></div>
              <div class="field-hint" id="caption-fields-wrap"></div>
            </div>
            <button type="submit" class="btn-primary btn-red"><i class="fi fi-rr-upload"></i> Upload Photos</button>
          </form>
        </div>
      </div>

    </aside>

    <!-- ── RIGHT: FOLDER + PHOTO LIST ── -->
    <section class="right-panel" aria-label="Gallery folders">
      <div class="right-panel-header">
        <div class="right-panel-title">
          All Folders &amp; Photos
          <span class="count-pill"><?= count($folders) ?> folders · <?= $total_photos ?> photos</span>
        </div>
      </div>

      <?php if (empty($folders)): ?>
      <div style="text-align:center;padding:60px 20px;border:1px dashed rgba(139,126,255,.18);background:rgba(139,126,255,.02)">
        <i class="fi fi-rr-folder" style="font-size:2rem;color:rgba(255,77,106,.30);display:block;margin-bottom:14px"></i>
        <p style="font-family:var(--font-hud);font-size:.62rem;color:var(--text-dim)">No folders yet. Create your first one using the panel on the left.</p>
      </div>
      <?php else: ?>

      <?php foreach ($folders as $f):
        $fid    = $f['folder_id'];
        $photos = $photos_by_folder[$fid] ?? [];
        $label  = $f['folder_label'] ?: $f['folder_name'];
      ?>
      <div class="folder-block" id="folder-<?= $fid ?>">
        <div class="folder-block-header">
          <div class="folder-block-title">
            <i class="fi fi-rr-folder"></i>
            <?= htmlspecialchars($label) ?>
            <span class="folder-photo-count"><?= count($photos) ?> photos</span>
          </div>
          <div class="folder-actions">
            <button
              class="icon-btn add"
              data-tip="Upload photos to this folder"
              onclick="setUploadFolder(<?= $fid ?>, '<?= htmlspecialchars($label, ENT_QUOTES) ?>')"
              type="button"
              aria-label="Upload to this folder">
              <i class="fi fi-rr-cloud-upload"></i>
            </button>
            <button
              class="icon-btn edit"
              data-tip="Edit folder"
              onclick="openEditFolder(<?= $fid ?>,
                '<?= htmlspecialchars($f['folder_name'], ENT_QUOTES) ?>',
                '<?= htmlspecialchars($f['folder_label'] ?? '', ENT_QUOTES) ?>',
                <?= (int)$f['folder_sort'] ?>)"
              type="button"
              aria-label="Edit folder">
              <i class="fi fi-rr-edit"></i>
            </button>
            <button
              class="icon-btn del"
              data-tip="Delete folder &amp; all photos"
              onclick="openConfirm('folder', <?= $fid ?>, '<?= htmlspecialchars($label, ENT_QUOTES) ?>', '<?= count($photos) ?> photo(s) inside')"
              type="button"
              aria-label="Delete folder">
              <i class="fi fi-rr-trash"></i>
            </button>
          </div>
        </div>

        <?php if (empty($photos)): ?>
        <div class="no-photos">No photos in this folder yet. Use the upload panel or click <i class="fi fi-rr-cloud-upload"></i> above.</div>
        <?php else: ?>
        <div class="photo-grid">
          <?php foreach ($photos as $p): ?>
          <div class="photo-item">
            <img src="<?= htmlspecialchars($p['photo_file']) ?>" alt="<?= htmlspecialchars($p['photo_caption']??'') ?>" loading="lazy" />
            <div class="photo-item-overlay">
              <div class="photo-item-cap"><?= htmlspecialchars($p['photo_caption'] ?: '—') ?></div>
              <div class="photo-item-actions">
                <button
                  class="icon-btn edit"
                  data-tip="Edit caption &amp; category"
                  style="width:28px;height:28px;font-size:.75rem"
                  onclick="openEditPhoto(
                    <?= $p['photo_id'] ?>,
                    <?= $fid ?>,
                    '<?= htmlspecialchars($p['photo_caption'] ?? '', ENT_QUOTES) ?>',
                    '<?= htmlspecialchars($p['photo_category'] ?? '', ENT_QUOTES) ?>',
                    <?= (int)$p['photo_sort'] ?>)"
                  type="button"
                  aria-label="Edit photo">
                  <i class="fi fi-rr-edit"></i>
                </button>
                <button
                  class="icon-btn del"
                  data-tip="Delete photo"
                  style="width:28px;height:28px;font-size:.75rem"
                  onclick="openConfirm('photo', <?= $p['photo_id'] ?>, '<?= htmlspecialchars($p['photo_caption'] ?: 'this photo', ENT_QUOTES) ?>', '')"
                  type="button"
                  aria-label="Delete photo">
                  <i class="fi fi-rr-trash"></i>
                </button>
              </div>
            </div>
          </div>
          <?php endforeach; ?>
        </div>
        <?php endif; ?>
      </div>
      <?php endforeach; ?>
      <?php endif; ?>

    </section>

  </div><!-- /admin-layout -->

</div><!-- /page-wrapper -->

<!-- ══════════ EDIT FOLDER MODAL ══════════ -->
<div class="modal-overlay" id="modal-edit-folder">
  <div class="modal-box">
    <div class="modal-hdr">
      <h3>Edit Folder</h3>
      <button class="modal-close" onclick="closeModal('modal-edit-folder')"><i class="fi fi-rr-cross"></i></button>
    </div>
    <form method="POST" action="admin-gallery.php">
      <input type="hidden" name="action" value="edit_folder" />
      <input type="hidden" name="folder_id" id="ef-id" />
      <div class="modal-body">
        <div class="field">
          <label class="field-label">Folder Name <span class="req">*</span></label>
          <input class="field-input" type="text" name="folder_name" id="ef-name" maxlength="100" required />
        </div>
        <div class="field">
          <label class="field-label">Display Label</label>
          <input class="field-input" type="text" name="folder_label" id="ef-label" maxlength="150" />
        </div>
        <div class="field">
          <label class="field-label">Sort Order</label>
          <input class="field-input" type="number" name="folder_sort" id="ef-sort" min="0" />
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn-primary" style="width:auto;padding:10px 24px;clip-path:none" onclick="closeModal('modal-edit-folder')">Cancel</button>
        <button type="submit" class="btn-primary" style="width:auto;padding:10px 24px;clip-path:none"><i class="fi fi-rr-check"></i> Save Changes</button>
      </div>
    </form>
  </div>
</div>

<!-- ══════════ EDIT PHOTO MODAL ══════════ -->
<div class="modal-overlay" id="modal-edit-photo">
  <div class="modal-box">
    <div class="modal-hdr">
      <h3>Edit Photo</h3>
      <button class="modal-close" onclick="closeModal('modal-edit-photo')"><i class="fi fi-rr-cross"></i></button>
    </div>
    <form method="POST" action="admin-gallery.php">
      <input type="hidden" name="action" value="edit_photo" />
      <input type="hidden" name="photo_id" id="ep-id" />
      <div class="modal-body">
        <div class="field">
          <label class="field-label">Move to Folder</label>
          <select class="field-select" name="folder_id" id="ep-folder">
            <?php foreach ($folders as $f): ?>
            <option value="<?= $f['folder_id'] ?>"><?= htmlspecialchars($f['folder_label'] ?: $f['folder_name']) ?></option>
            <?php endforeach; ?>
          </select>
        </div>
        <div class="field">
          <label class="field-label">Caption</label>
          <textarea class="field-textarea" name="photo_caption" id="ep-caption" placeholder="e.g. Competition Floor — National Finals 2025" rows="3"></textarea>
        </div>
        <div class="field">
          <label class="field-label">Category</label>
          <input class="field-input" type="text" name="photo_category" id="ep-category" placeholder="e.g. Drone Soccer" maxlength="100" />
        </div>
        <div class="field">
          <label class="field-label">Sort Order</label>
          <input class="field-input" type="number" name="photo_sort" id="ep-sort" min="0" />
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn-primary" style="width:auto;padding:10px 24px;clip-path:none" onclick="closeModal('modal-edit-photo')">Cancel</button>
        <button type="submit" class="btn-primary" style="width:auto;padding:10px 24px;clip-path:none"><i class="fi fi-rr-check"></i> Save Changes</button>
      </div>
    </form>
  </div>
</div>

<!-- ══════════ DELETE CONFIRM MODAL ══════════ -->
<div class="modal-overlay" id="modal-confirm">
  <div class="modal-box confirm-box" style="max-width:420px;text-align:center">
    <div class="modal-hdr" style="justify-content:center;border-color:rgba(255,77,106,.25);background:rgba(255,77,106,.05)">
      <h3 style="color:var(--admin-red)">Confirm Delete</h3>
    </div>
    <div class="modal-body">
      <i class="fi fi-rr-trash confirm-icon"></i>
      <div class="confirm-title" id="confirm-title">Delete?</div>
      <div class="confirm-text" id="confirm-text">This action cannot be undone.</div>
    </div>
    <div class="modal-footer" style="justify-content:center">
      <button type="button" class="btn-primary" style="width:auto;padding:10px 24px;clip-path:none" onclick="closeModal('modal-confirm')">Cancel</button>
      <form method="POST" action="admin-gallery.php" id="confirm-form" style="display:inline">
        <input type="hidden" name="action" id="confirm-action" />
        <input type="hidden" name="folder_id" id="confirm-folder-id" />
        <input type="hidden" name="photo_id"  id="confirm-photo-id" />
        <button type="submit" class="btn-primary btn-red" style="width:auto;padding:10px 24px;clip-path:none"><i class="fi fi-rr-trash"></i> Yes, Delete</button>
      </form>
    </div>
  </div>
</div>

<script>
// ── CURSOR ──
(function(){
  var dot=document.getElementById('cursorDot'), ring=document.getElementById('cursorRing');
  if(!dot||!ring) return;
  var mx=0,my=0,rx=0,ry=0;
  document.addEventListener('mousemove',function(e){ mx=e.clientX; my=e.clientY; dot.style.left=mx+'px'; dot.style.top=my+'px'; });
  (function l(){ rx+=(mx-rx)*.12; ry+=(my-ry)*.12; ring.style.left=rx+'px'; ring.style.top=ry+'px'; requestAnimationFrame(l); })();
  document.querySelectorAll('a,button,.photo-item,.folder-block').forEach(function(el){
    el.addEventListener('mouseenter',function(){ ring.classList.add('hovered'); });
    el.addEventListener('mouseleave',function(){ ring.classList.remove('hovered'); });
  });
})();

// ── HAMBURGER ──
(function(){
  var btn=document.getElementById('hamburger'), menu=document.getElementById('mobile-menu');
  if(!btn||!menu) return;
  btn.addEventListener('click',function(e){ e.stopPropagation(); var o=menu.classList.toggle('open'); btn.classList.toggle('open',o); btn.setAttribute('aria-expanded',o); document.body.style.overflow=o?'hidden':''; });
  document.addEventListener('click',function(e){ if(menu.classList.contains('open')&&!btn.contains(e.target)&&!menu.contains(e.target)){ menu.classList.remove('open'); btn.classList.remove('open'); document.body.style.overflow=''; } });
})();

// ── MODAL HELPERS ──
function openModal(id){ document.getElementById(id).classList.add('open'); document.body.style.overflow='hidden'; }
function closeModal(id){ document.getElementById(id).classList.remove('open'); document.body.style.overflow=''; }
document.querySelectorAll('.modal-overlay').forEach(function(el){
  el.addEventListener('click',function(e){ if(e.target===el) closeModal(el.id); });
});
document.addEventListener('keydown',function(e){ if(e.key==='Escape') document.querySelectorAll('.modal-overlay.open').forEach(function(m){ closeModal(m.id); }); });

// ── EDIT FOLDER ──
function openEditFolder(id, name, label, sort){
  document.getElementById('ef-id').value    = id;
  document.getElementById('ef-name').value  = name;
  document.getElementById('ef-label').value = label;
  document.getElementById('ef-sort').value  = sort;
  openModal('modal-edit-folder');
}

// ── EDIT PHOTO ──
function openEditPhoto(id, folderId, caption, category, sort){
  document.getElementById('ep-id').value       = id;
  document.getElementById('ep-folder').value   = folderId;
  document.getElementById('ep-caption').value  = caption;
  document.getElementById('ep-category').value = category;
  document.getElementById('ep-sort').value     = sort;
  openModal('modal-edit-photo');
}

// ── DELETE CONFIRM ──
function openConfirm(type, id, name, extra){
  var title = type === 'folder'
    ? 'Delete folder "' + name + '"?'
    : 'Delete "' + name + '"?';
  var text = type === 'folder'
    ? 'This will permanently delete the folder and all ' + extra + ' along with their files. This cannot be undone.'
    : 'This will permanently delete the photo and its file. This cannot be undone.';
  document.getElementById('confirm-title').textContent = title;
  document.getElementById('confirm-text').textContent  = text;
  document.getElementById('confirm-action').value      = type === 'folder' ? 'delete_folder' : 'delete_photo';
  document.getElementById('confirm-folder-id').value   = type === 'folder' ? id : '';
  document.getElementById('confirm-photo-id').value    = type === 'photo'  ? id : '';
  openModal('modal-confirm');
}

// ── SET UPLOAD FOLDER (quick-set sidebar select) ──
function setUploadFolder(fid, label){
  var sel = document.querySelector('#form-upload select[name="folder_id"]');
  if(sel){ sel.value = fid; }
  // scroll to the upload panel
  document.getElementById('form-upload').closest('.panel-card').scrollIntoView({behavior:'smooth',block:'start'});
}

// ── UPLOAD PREVIEW ──
(function(){
  var input   = document.getElementById('photo_files');
  var preview = document.getElementById('upload-preview');
  var zone    = document.getElementById('upload-zone');
  if(!input) return;
  input.addEventListener('change',function(){
    preview.innerHTML = '';
    Array.from(this.files).forEach(function(file){
      var wrap = document.createElement('div');
      wrap.className = 'upload-preview-thumb';
      var img = document.createElement('img');
      img.src = URL.createObjectURL(file);
      img.alt = file.name;
      wrap.appendChild(img);
      preview.appendChild(wrap);
    });
  });
  zone.addEventListener('dragover',function(e){ e.preventDefault(); zone.classList.add('dragover'); });
  zone.addEventListener('dragleave',function(){ zone.classList.remove('dragover'); });
  zone.addEventListener('drop',function(e){ e.preventDefault(); zone.classList.remove('dragover'); });
})();
</script>
</body>
</html>