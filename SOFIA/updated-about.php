<?php
// PRC-WebApp/about.php
// ── DB ──────────────────────────────────────────────────────────
$db_host = 'localhost';
$db_user = 'root';
$db_pass = '';
$db_name = 'prc_db';

$conn = new mysqli($db_host, $db_user, $db_pass, $db_name);
if ($conn->connect_error) {
    die('<p style="color:#ff6b6b;padding:40px;font-family:monospace;">DB error: ' . htmlspecialchars($conn->connect_error) . '</p>');
}

// ── FETCH ALL DATA ───────────────────────────────────────────────
// Meta (key-value)
$meta = [];
$r = $conn->query("SELECT meta_key, meta_value FROM prc_about_meta");
if ($r) while ($row = $r->fetch_assoc()) $meta[$row['meta_key']] = $row['meta_value'];

// Stats strip
$stats = [];
$r = $conn->query("SELECT * FROM prc_about_stats ORDER BY stat_sort ASC");
if ($r) while ($row = $r->fetch_assoc()) $stats[] = $row;

// Values
$values = [];
$r = $conn->query("SELECT * FROM prc_about_values ORDER BY val_sort ASC");
if ($r) while ($row = $r->fetch_assoc()) $values[] = $row;

// Background highlights
$highlights = [];
$r = $conn->query("SELECT * FROM prc_about_highlights ORDER BY hl_sort ASC");
if ($r) while ($row = $r->fetch_assoc()) $highlights[] = $row;

// Programs
$programs = [];
$r = $conn->query("SELECT * FROM prc_about_programs ORDER BY prog_sort ASC");
if ($r) while ($row = $r->fetch_assoc()) $programs[] = $row;

// Government partners
$partners_gov = [];
$r = $conn->query("SELECT * FROM prc_about_partners_gov ORDER BY pg_sort ASC");
if ($r) while ($row = $r->fetch_assoc()) $partners_gov[] = $row;

// Academic partners
$partners_acad = [];
$r = $conn->query("SELECT * FROM prc_about_partners_acad ORDER BY pa_sort ASC");
if ($r) while ($row = $r->fetch_assoc()) $partners_acad[] = $row;

// References
$refs = [];
$r = $conn->query("SELECT * FROM prc_about_refs ORDER BY ref_sort ASC");
if ($r) while ($row = $r->fetch_assoc()) $refs[] = $row;

$conn->close();

// ── HELPERS ──────────────────────────────────────────────────────
// Safe HTML output (allows stored HTML like <strong>, <span>)
function mh($meta, $key, $default = '') {
    return $meta[$key] ?? $default;
}
// Escaped plain text output
function me($meta, $key, $default = '') {
    return htmlspecialchars($meta[$key] ?? $default);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <meta name="theme-color" content="#8B7EFF" />
  <meta name="description" content="About Creotec Philippines — the organizer of the Philippine Robotics Cup." />
  <title>About Us - Philippine Robotics Cup</title>

  <link rel="icon" type="image/png" href="assets/favicon.png" />
  <link rel="shortcut icon" href="assets/favicon.png" />
  <link rel="apple-touch-icon" href="assets/favicon.png" />

  <link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@400;600;700;800;900&family=Exo+2:wght@300;400;500;600;700&display=swap" rel="stylesheet"/>
  <link rel='stylesheet' href='https://cdn-uicons.flaticon.com/uicons-regular-rounded/css/uicons-regular-rounded.css'>
  <link rel='stylesheet' href='https://cdn-uicons.flaticon.com/uicons-solid-rounded/css/uicons-solid-rounded.css'>
  <link rel='stylesheet' href='https://cdn-uicons.flaticon.com/uicons-brands/css/uicons-brands.css'>

  <style>
    :root {
      --prc-violet:   #8B7EFF;
      --prc-ice:      #C4EEFF;
      --creo-purple:  #7733FF;
      --creo-amber:   #FFA030;
      --creo-volt:    #FFE930;
      --neon-primary: var(--prc-violet);
      --bg-void:      #03020D;
      --bg-deep:      #06051A;
      --border-neon:  rgba(139,126,255,0.22);
      --border-hot:   rgba(139,126,255,0.55);
      --glow-primary: 0 0 18px rgba(139,126,255,0.60), 0 0 55px rgba(139,126,255,0.20);
      --glow-orange:  0 0 18px rgba(255,160,48,0.55),  0 0 55px rgba(255,160,48,0.18);
      --glow-volt:    0 0 18px rgba(255,233,48,0.60),  0 0 55px rgba(255,233,48,0.20);
      --text-high:    #F2EEFF;
      --text-mid:     #C8C0F0;
      --text-soft:    #9A90CC;
      --text-dim:     #7068A8;
      --nav-height:   72px;
      --font-hud:     'Orbitron', monospace;
      --font-body:    'Exo 2', sans-serif;
      --section-pad:  110px 0;
    }

    *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
    html { scroll-behavior: smooth; }
    body { font-family: var(--font-body); background: var(--bg-void); color: var(--text-high); overflow-x: hidden; line-height: 1.6; cursor: none; }
    img { max-width: 100%; display: block; }
    a { text-decoration: none; color: inherit; }
    ul { list-style: none; }
    button { font-family: inherit; cursor: none; border: none; background: none; }

    /* ===== CURSOR ===== */
    .cursor-dot { position:fixed;width:8px;height:8px;border-radius:50%;background:var(--neon-primary);pointer-events:none;z-index:99999;transform:translate(-50%,-50%);box-shadow:var(--glow-primary);transition:transform .1s,background .2s; }
    .cursor-ring { position:fixed;width:36px;height:36px;border-radius:50%;border:1px solid rgba(139,126,255,0.65);pointer-events:none;z-index:99998;transform:translate(-50%,-50%);transition:width .25s,height .25s,border-color .25s; }
    .cursor-ring.hovered { width:56px;height:56px;border-color:var(--creo-amber);border-width:1.5px; }

    /* ===== SCANLINES ===== */
    body::after { content:'';position:fixed;inset:0;z-index:9998;pointer-events:none;background:repeating-linear-gradient(to bottom,transparent,transparent 2px,rgba(0,0,0,0.04) 2px,rgba(0,0,0,0.04) 4px); }

    /* ===== HEX GRID ===== */
    .hex-grid { position:fixed;inset:0;z-index:0;pointer-events:none;background-image:linear-gradient(rgba(139,126,255,0.04) 1px,transparent 1px),linear-gradient(90deg,rgba(139,126,255,0.04) 1px,transparent 1px);background-size:50px 50px; }
    .hex-grid::before { content:'';position:absolute;inset:0;background:radial-gradient(ellipse 80% 60% at 50% 0%,rgba(119,51,255,0.14) 0%,transparent 70%),radial-gradient(ellipse 60% 50% at 100% 100%,rgba(204,85,255,0.07) 0%,transparent 60%); }

    /* ===== ANIMATIONS ===== */
    @keyframes neonPulse { 0%,100%{opacity:1}50%{opacity:0.6} }
    @keyframes fadeInUp { from{opacity:0;transform:translateY(24px)}to{opacity:1;transform:translateY(0)} }
    @keyframes fadeIn { from{opacity:0}to{opacity:1} }
    @keyframes scanDown { from{transform:translateY(-100%)}to{transform:translateY(100vh)} }
    @keyframes borderGlow { 0%,100%{box-shadow:0 0 20px rgba(139,126,255,0.18)}50%{box-shadow:0 0 45px rgba(139,126,255,0.38)} }

    .page-wrapper { position:relative;z-index:1; }

    /* ===== NAV ===== */
    #main-nav { position:fixed;top:0;left:0;right:0;height:var(--nav-height);z-index:1000;background:rgba(3,2,13,0.94);backdrop-filter:blur(20px);border-bottom:1px solid var(--border-neon);box-shadow:0 0 30px rgba(139,126,255,0.10); }
    .nav-inner { max-width:1340px;margin:0 auto;height:100%;padding:0 36px;display:flex;align-items:center;justify-content:space-between;gap:16px; }
    .nav-logo { display:flex;align-items:center;gap:12px;flex-shrink:0; }
    .nav-logo img { height:38px;width:auto;transition:filter .3s; }
    .nav-logo:hover img { filter:drop-shadow(0 0 14px rgba(139,126,255,0.75)); }
    .nav-brand { font-family:var(--font-hud);font-weight:700;font-size:0.72rem;letter-spacing:0.06em;line-height:1.3;color:var(--prc-violet);text-shadow:0 0 12px rgba(139,126,255,0.65); }
    .nav-brand span { color:var(--text-soft);display:block;font-size:0.58rem;font-weight:400;letter-spacing:0.10em;text-transform:uppercase;margin-top:1px; }
    .nav-links { display:flex;align-items:center;gap:2px; }
    .nav-links a { font-family:var(--font-hud);font-size:0.65rem;font-weight:600;color:var(--text-mid);padding:8px 14px;letter-spacing:0.08em;text-transform:uppercase;border-radius:4px;transition:all .2s;white-space:nowrap;position:relative; }
    .nav-links a:hover { color:var(--prc-violet);text-shadow:0 0 12px rgba(139,126,255,0.85); }
    .nav-links a.active { color:var(--prc-violet);text-shadow:0 0 12px rgba(139,126,255,0.85); }
    .nav-links a.active::after { content:'';position:absolute;bottom:4px;left:14px;right:14px;height:1px;background:var(--prc-violet);box-shadow:0 0 6px rgba(139,126,255,0.80); }
    .nav-cta { background:transparent!important;border:1px solid var(--prc-violet)!important;color:var(--prc-violet)!important;padding:8px 20px!important;border-radius:3px!important;box-shadow:0 0 15px rgba(139,126,255,0.28),inset 0 0 15px rgba(139,126,255,0.06)!important;transition:all .25s!important;margin-left:8px;clip-path:polygon(6px 0%,100% 0%,calc(100% - 6px) 100%,0% 100%); }
    .nav-cta:hover { background:rgba(139,126,255,0.12)!important;box-shadow:0 0 30px rgba(139,126,255,0.52),inset 0 0 20px rgba(139,126,255,0.10)!important;color:#fff!important; }
    .nav-hamburger { display:none;flex-direction:column;justify-content:center;align-items:center;gap:5px;width:44px;height:44px;padding:0;cursor:none;background:rgba(139,126,255,0.06);border:1px solid var(--border-neon);border-radius:4px;flex-shrink:0;z-index:1002;-webkit-tap-highlight-color:transparent;touch-action:manipulation;transition:all .2s; }
    .nav-hamburger:hover { background:rgba(139,126,255,0.14);box-shadow:0 0 14px rgba(139,126,255,0.28); }
    .nav-hamburger span { width:20px;height:1.5px;background:var(--prc-violet);border-radius:2px;transition:transform .28s,opacity .28s;display:block;pointer-events:none; }
    .nav-hamburger.open span:nth-child(1) { transform:rotate(45deg) translate(5px,5px); }
    .nav-hamburger.open span:nth-child(2) { opacity:0; }
    .nav-hamburger.open span:nth-child(3) { transform:rotate(-45deg) translate(5px,-5px); }
    .nav-mobile { display:none;position:fixed;top:var(--nav-height);left:0;right:0;background:rgba(3,2,13,0.98);backdrop-filter:blur(20px);border-bottom:1px solid var(--border-neon);padding:12px 18px 24px;z-index:1000;flex-direction:column;gap:2px;box-shadow:0 20px 60px rgba(139,126,255,0.09); }
    .nav-mobile.open { display:flex; }
    .nav-mobile a { font-family:var(--font-hud);font-size:0.70rem;font-weight:600;color:var(--text-mid);padding:13px 14px;border-radius:3px;letter-spacing:0.08em;text-transform:uppercase;transition:all .2s;display:flex;align-items:center;gap:12px; }
    .nav-mobile a i { font-size:1rem;color:var(--prc-violet); }
    .nav-mobile a:hover,.nav-mobile a.active { color:var(--prc-violet);background:rgba(139,126,255,0.07);text-shadow:0 0 10px rgba(139,126,255,0.55); }
    .nav-mobile .nav-cta { border:1px solid var(--prc-violet)!important;color:var(--prc-violet)!important;margin-top:10px;justify-content:center;border-radius:3px!important;clip-path:none!important; }

    /* ===== PAGE HERO ===== */
    .page-hero { position:relative;padding:calc(var(--nav-height) + 72px) 0 72px;overflow:hidden;text-align:center; }
    .page-hero::before { content:'';position:absolute;inset:0;background:radial-gradient(ellipse 70% 70% at 50% 0%,rgba(139,126,255,0.10) 0%,transparent 70%),linear-gradient(to bottom,rgba(3,2,13,0) 60%,var(--bg-void) 100%); }
    .page-hero-scan { position:absolute;inset:0;pointer-events:none;overflow:hidden; }
    .page-hero-scan::after { content:'';position:absolute;left:0;right:0;height:1px;background:linear-gradient(90deg,transparent,var(--prc-violet),var(--prc-ice),transparent);animation:scanDown 8s linear infinite;box-shadow:0 0 14px rgba(139,126,255,0.55); }
    .page-hero-inner { position:relative;z-index:2;max-width:700px;margin:0 auto;padding:0 36px; }
    .page-hero-eyebrow { display:inline-flex;align-items:center;gap:10px;font-family:var(--font-hud);font-size:0.60rem;font-weight:600;letter-spacing:0.20em;text-transform:uppercase;color:var(--prc-ice);margin-bottom:18px;animation:fadeIn .8s ease both; }
    .page-hero-eyebrow::before { content:'//';color:rgba(139,126,255,0.40);font-size:0.70rem; }
    .page-hero-blink { width:6px;height:6px;background:var(--prc-violet);border-radius:50%;box-shadow:var(--glow-primary);animation:neonPulse 1.2s ease-in-out infinite; }
    .page-hero-title { font-family:var(--font-hud);font-size:clamp(2.2rem,6vw,4rem);font-weight:900;letter-spacing:-0.01em;line-height:1.0;color:#fff;margin-bottom:18px;text-shadow:0 0 40px rgba(139,126,255,0.20);animation:fadeInUp .8s ease .1s both; }
    .page-hero-title .accent { color:var(--prc-violet);text-shadow:0 0 22px rgba(139,126,255,0.65); }
    .page-hero-desc { font-size:1rem;color:var(--text-mid);line-height:1.78;max-width:520px;margin:0 auto;animation:fadeInUp .8s ease .2s both; }
    .page-hero-divider { display:flex;align-items:center;justify-content:center;gap:14px;margin-top:36px;animation:fadeIn .8s ease .3s both; }
    .page-hero-divider-line { width:80px;height:1px;background:linear-gradient(90deg,transparent,rgba(139,126,255,0.40)); }
    .page-hero-divider-line.right { background:linear-gradient(90deg,rgba(139,126,255,0.40),transparent); }
    .page-hero-divider-diamond { width:8px;height:8px;background:var(--prc-violet);transform:rotate(45deg);box-shadow:var(--glow-primary); }

    /* ===== SHARED ===== */
    section { padding:var(--section-pad); }
    .section-inner { max-width:1340px;margin:0 auto;padding:0 36px; }
    .section-eyebrow { display:inline-flex;align-items:center;gap:8px;font-family:var(--font-hud);color:var(--prc-ice);font-size:0.60rem;font-weight:700;letter-spacing:0.20em;text-transform:uppercase;margin-bottom:16px; }
    .section-eyebrow::before { content:'//';color:rgba(139,126,255,0.40);font-size:0.70rem; }
    .section-title { font-family:var(--font-hud);font-size:clamp(1.8rem,3.8vw,2.8rem);font-weight:800;letter-spacing:-0.01em;line-height:1.08;margin-bottom:14px;color:#fff;text-shadow:0 0 40px rgba(139,126,255,0.14); }
    .section-title .accent { color:var(--prc-violet);text-shadow:0 0 18px rgba(139,126,255,0.65); }
    .section-desc { font-size:1rem;color:var(--text-mid);max-width:560px;line-height:1.78; }
    .btn-neon-primary { display:inline-flex;align-items:center;gap:10px;background:transparent;color:var(--prc-violet);padding:12px 28px;font-family:var(--font-hud);font-size:0.66rem;font-weight:700;letter-spacing:0.12em;text-transform:uppercase;border:1px solid var(--prc-violet);clip-path:polygon(10px 0%,100% 0%,calc(100% - 10px) 100%,0% 100%);box-shadow:0 0 18px rgba(139,126,255,0.32),inset 0 0 18px rgba(139,126,255,0.07);transition:all .25s; }
    .btn-neon-primary:hover { background:rgba(139,126,255,0.12);box-shadow:0 0 38px rgba(139,126,255,0.60),inset 0 0 28px rgba(139,126,255,0.12);color:#fff;transform:translateY(-2px); }
    .btn-neon-amber { display:inline-flex;align-items:center;gap:10px;background:transparent;color:var(--creo-amber);padding:12px 28px;font-family:var(--font-hud);font-size:0.66rem;font-weight:700;letter-spacing:0.12em;text-transform:uppercase;border:1px solid var(--creo-amber);clip-path:polygon(10px 0%,100% 0%,calc(100% - 10px) 100%,0% 100%);box-shadow:0 0 18px rgba(255,160,48,0.28),inset 0 0 18px rgba(255,160,48,0.06);transition:all .25s; }
    .btn-neon-amber:hover { background:rgba(255,160,48,0.10);box-shadow:0 0 38px rgba(255,160,48,0.55),inset 0 0 28px rgba(255,160,48,0.10);color:#fff;transform:translateY(-2px); }
    .reveal { opacity:0;transform:translateY(30px);transition:opacity .65s ease,transform .65s ease; }
    .reveal.visible { opacity:1;transform:translateY(0); }
    .reveal-left { opacity:0;transform:translateX(-24px);transition:opacity .65s ease,transform .65s ease; }
    .reveal-left.visible { opacity:1;transform:translateX(0); }
    .reveal-delay-1 { transition-delay:.10s; }
    .reveal-delay-2 { transition-delay:.20s; }
    .reveal-delay-3 { transition-delay:.30s; }

    /* ===== WHO IS CREOTEC ===== */
    #who { padding:110px 0 80px; }
    .who-grid { display:grid;grid-template-columns:1fr 1fr;gap:72px;align-items:center; }
    .who-text .section-eyebrow { margin-bottom:14px; }
    .who-text p { font-size:1rem;color:var(--text-mid);line-height:1.82;margin-bottom:16px; }
    .who-actions { display:flex;gap:12px;flex-wrap:wrap;margin-top:32px; }
    .who-visual { position:relative; }
    .who-img-frame { position:relative;padding:2px;background:linear-gradient(135deg,rgba(139,126,255,0.85),rgba(255,160,48,0.55),rgba(119,51,255,0.75));animation:borderGlow 3s ease-in-out infinite;clip-path:polygon(14px 0%,100% 0%,calc(100% - 14px) 100%,0% 100%); }
    .who-img-frame img { width:100%;display:block;aspect-ratio:4/3;object-fit:cover;filter:brightness(0.88) saturate(1.05);clip-path:polygon(13px 0%,100% 0%,calc(100% - 13px) 100%,0% 100%); }
    .who-badge { position:absolute;bottom:-20px;right:-20px;background:rgba(3,2,13,0.92);border:1px solid var(--creo-volt);color:var(--creo-volt);padding:16px 22px;font-family:var(--font-hud);font-size:0.68rem;font-weight:700;letter-spacing:0.06em;text-shadow:0 0 10px rgba(255,233,48,0.60);box-shadow:0 0 28px rgba(255,233,48,0.22),inset 0 0 18px rgba(255,233,48,0.05);clip-path:polygon(8px 0%,100% 0%,calc(100% - 8px) 100%,0% 100%); }
    .who-badge span { display:block;font-size:1.15rem;font-weight:900;margin-bottom:2px; }

    /* ===== STATS STRIP ===== */
    .stats-strip { border-top:1px solid var(--border-neon);border-bottom:1px solid var(--border-neon);padding:44px 0;background:rgba(139,126,255,0.02); }
    .stats-inner { max-width:1340px;margin:0 auto;padding:0 36px;display:grid;grid-template-columns:repeat(<?= max(1,count($stats)) ?>,1fr); }
    .stat-item { text-align:center;padding:14px 20px;border-right:1px solid var(--border-neon); }
    .stat-item:last-child { border-right:none; }
    .stat-num { font-family:var(--font-hud);font-size:2.2rem;font-weight:800;color:var(--prc-violet);display:block;line-height:1;text-shadow:0 0 20px rgba(139,126,255,0.70); }
    .stat-label { font-family:var(--font-hud);font-size:0.55rem;color:var(--text-soft);margin-top:7px;text-transform:uppercase;letter-spacing:0.10em; }
    .stat-icon { color:rgba(139,126,255,0.60);font-size:1.1rem;margin-bottom:8px; }

    /* ===== VALUES ===== */
    #values { background:rgba(0,0,8,0.50); }
    .values-header { text-align:center;max-width:600px;margin:0 auto 56px; }
    .values-header .section-eyebrow { justify-content:center; }
    .values-grid { display:grid;grid-template-columns:repeat(<?= max(1,min(4,count($values))) ?>,1fr);gap:18px; }
    .value-card { background:rgba(139,126,255,0.04);border:1px solid rgba(139,126,255,0.14);padding:32px 24px;text-align:center;position:relative;overflow:hidden;transition:all .35s; }
    .value-card::before { content:'';position:absolute;top:0;left:0;right:0;height:2px;background:linear-gradient(90deg,transparent,var(--prc-violet),transparent);opacity:0;transition:opacity .3s; }
    .value-card:hover { border-color:rgba(139,126,255,0.38);background:rgba(139,126,255,0.09);transform:translateY(-6px);box-shadow:0 0 30px rgba(139,126,255,0.14); }
    .value-card:hover::before { opacity:1; }
    .value-num { font-family:var(--font-hud);font-size:0.48rem;font-weight:700;letter-spacing:0.20em;text-transform:uppercase;color:var(--text-dim);margin-bottom:16px; }
    .value-icon { font-size:2rem;margin-bottom:16px;display:block; }
    .value-icon-0 { color:#44FFAA; }
    .value-icon-1 { color:var(--creo-amber); }
    .value-icon-2 { color:var(--prc-violet); }
    .value-icon-3 { color:var(--creo-volt); }
    .value-title { font-family:var(--font-hud);font-size:0.78rem;font-weight:700;letter-spacing:0.04em;color:var(--text-high);margin-bottom:10px;line-height:1.3; }
    .value-desc { font-size:0.84rem;color:var(--text-mid);line-height:1.68; }

    /* ===== MISSION/VISION ===== */
    #mission { padding:var(--section-pad); }
    .mv-grid { display:grid;grid-template-columns:1fr 1fr;gap:24px; }
    .mv-card { padding:48px 44px;border:1px solid var(--border-neon);position:relative;overflow:hidden;background:rgba(139,126,255,0.03);box-shadow:0 0 40px rgba(139,126,255,0.06),inset 0 0 40px rgba(139,126,255,0.02);transition:border-color .3s,box-shadow .3s; }
    .mv-card::before { content:'';position:absolute;top:0;left:0;right:0;height:2px;background:linear-gradient(90deg,transparent,var(--prc-violet),transparent); }
    .mv-card:hover { border-color:rgba(139,126,255,0.40);box-shadow:0 0 55px rgba(139,126,255,0.14); }
    .mv-card.amber-accent::before { background:linear-gradient(90deg,transparent,var(--creo-amber),transparent); }
    .mv-card.amber-accent:hover { border-color:rgba(255,160,48,0.35);box-shadow:0 0 55px rgba(255,160,48,0.12); }
    .mv-tag { display:inline-flex;align-items:center;gap:7px;font-family:var(--font-hud);font-size:0.54rem;font-weight:700;letter-spacing:0.18em;text-transform:uppercase;color:var(--prc-violet);margin-bottom:20px; }
    .mv-card.amber-accent .mv-tag { color:var(--creo-amber); }
    .mv-tag-dot { width:6px;height:6px;border-radius:50%;background:var(--prc-violet);animation:neonPulse 1.5s ease-in-out infinite;flex-shrink:0; }
    .mv-card.amber-accent .mv-tag-dot { background:var(--creo-amber); }
    .mv-title { font-family:var(--font-hud);font-size:1.35rem;font-weight:800;color:#fff;margin-bottom:18px;letter-spacing:0.02em; }
    .mv-body { font-size:0.955rem;color:var(--text-mid);line-height:1.82; }

    /* ===== PROGRAMS ===== */
    #programs { background:rgba(0,0,8,0.50); }
    .programs-header { display:flex;align-items:flex-end;justify-content:space-between;margin-bottom:52px;flex-wrap:wrap;gap:24px; }
    .programs-grid { display:grid;grid-template-columns:repeat(3,1fr);gap:20px; }
    .prog-card { background:rgba(0,0,8,0.70);border:1px solid rgba(139,126,255,0.12);overflow:hidden;display:flex;flex-direction:column;transition:all .35s cubic-bezier(0.23,1,0.32,1); }
    .prog-card::before { content:'';display:block;position:relative;top:0;left:0;right:0;height:2px;background:linear-gradient(90deg,transparent,var(--prc-violet),transparent);opacity:0;transition:opacity .3s; }
    .prog-card:hover { border-color:rgba(139,126,255,0.38);box-shadow:0 0 36px rgba(139,126,255,0.14);transform:translateY(-6px); }
    .prog-card:hover::before { opacity:1; }
    .prog-img { width:100%;height:200px;object-fit:cover;filter:brightness(0.5) saturate(0.6);transition:filter .4s,transform .4s;display:block; }
    .prog-card:hover .prog-img { filter:brightness(0.65) saturate(0.80);transform:scale(1.04); }
    .prog-img-wrap { overflow:hidden;position:relative; }
    .prog-img-overlay { position:absolute;inset:0;background:linear-gradient(to bottom,transparent 40%,var(--bg-void) 100%); }
    .prog-body { padding:22px 24px 28px;flex:1;display:flex;flex-direction:column; }
    .prog-num { font-family:var(--font-hud);font-size:0.50rem;font-weight:700;letter-spacing:0.20em;text-transform:uppercase;color:var(--prc-violet);opacity:0.70;margin-bottom:8px; }
    .prog-title { font-family:var(--font-hud);font-size:0.88rem;font-weight:700;color:var(--text-high);margin-bottom:12px;line-height:1.3;letter-spacing:0.02em; }
    .prog-desc { font-size:0.848rem;color:var(--text-mid);line-height:1.70;flex:1; }
    .prog-tags { display:flex;flex-wrap:wrap;gap:5px;margin-top:16px; }
    .prog-tag { font-family:var(--font-hud);font-size:0.50rem;font-weight:600;letter-spacing:0.08em;text-transform:uppercase;padding:3px 10px;border:1px solid rgba(139,126,255,0.22);background:rgba(139,126,255,0.05);color:var(--text-soft); }
    .prog-card.tesda { border-color:rgba(255,233,48,0.12); }
    .prog-card.tesda::before { background:linear-gradient(90deg,transparent,var(--creo-volt),transparent); }
    .prog-card.tesda:hover { border-color:rgba(255,233,48,0.35);box-shadow:0 0 36px rgba(255,233,48,0.12); }
    .prog-card.tesda .prog-num { color:var(--creo-volt); }
    .prog-card.tesda .prog-tag { border-color:rgba(255,233,48,0.22);background:rgba(255,233,48,0.05);color:rgba(255,233,48,0.75); }
    .prog-card.competition { border-color:rgba(255,160,48,0.12); }
    .prog-card.competition::before { background:linear-gradient(90deg,transparent,var(--creo-amber),transparent); }
    .prog-card.competition:hover { border-color:rgba(255,160,48,0.35);box-shadow:0 0 36px rgba(255,160,48,0.12); }
    .prog-card.competition .prog-num { color:var(--creo-amber); }
    .prog-card.competition .prog-tag { border-color:rgba(255,160,48,0.22);background:rgba(255,160,48,0.05);color:rgba(255,160,48,0.80); }

    /* ===== BACKGROUND SECTION ===== */
    #background { padding:var(--section-pad); }
    .bg-grid { display:grid;grid-template-columns:1fr 1fr;gap:72px;align-items:center; }
    .bg-text p { font-size:0.968rem;color:var(--text-mid);line-height:1.84;margin-bottom:18px; }
    .bg-highlights { display:flex;flex-direction:column;gap:12px;margin-top:28px; }
    .bg-highlight { display:flex;align-items:flex-start;gap:14px;padding:14px 18px;background:rgba(139,126,255,0.04);border:1px solid rgba(139,126,255,0.12);border-left:2px solid var(--prc-violet);transition:all .3s; }
    .bg-highlight:hover { background:rgba(139,126,255,0.09);border-color:rgba(139,126,255,0.28);border-left-color:var(--creo-volt);transform:translateX(4px); }
    .bg-highlight-icon { width:36px;height:36px;flex-shrink:0;background:rgba(139,126,255,0.10);border:1px solid rgba(139,126,255,0.22);display:flex;align-items:center;justify-content:center;color:var(--prc-violet);font-size:0.9rem; }
    .bg-highlight-text strong { display:block;font-family:var(--font-hud);font-size:0.68rem;font-weight:700;color:var(--text-high);margin-bottom:3px;letter-spacing:0.03em; }
    .bg-highlight-text span { font-size:0.84rem;color:var(--text-mid); }
    .bg-visual { position:relative; }
    .bg-img-stack { position:relative; }
    .bg-img-main { width:100%;aspect-ratio:4/3;object-fit:cover;border:1px solid var(--border-neon);filter:brightness(0.80) saturate(0.90);display:block; }
    .bg-img-secondary { position:absolute;bottom:-24px;right:-24px;width:55%;aspect-ratio:3/2;object-fit:cover;border:2px solid rgba(255,160,48,0.40);filter:brightness(0.85) saturate(0.90);display:block;box-shadow:0 0 30px rgba(255,160,48,0.18); }
    .bg-emsg-badge { position:absolute;top:16px;left:16px;background:rgba(3,2,13,0.88);border:1px solid rgba(139,126,255,0.40);padding:10px 16px;font-family:var(--font-hud);font-size:0.55rem;font-weight:700;letter-spacing:0.10em;text-transform:uppercase;color:var(--prc-ice);clip-path:polygon(5px 0%,100% 0%,calc(100% - 5px) 100%,0% 100%); }

    /* ===== PARTNERS ===== */
    #partners { background:rgba(0,0,8,0.50);padding:80px 0; }
    .partners-card { background:rgba(139,126,255,0.025);border:1px solid var(--border-neon);padding:60px 52px;text-align:center;position:relative;overflow:hidden;box-shadow:0 0 60px rgba(139,126,255,0.07),inset 0 0 60px rgba(139,126,255,0.02); }
    .partners-card::before { content:'';position:absolute;top:0;left:0;right:0;height:1px;background:linear-gradient(90deg,transparent,var(--prc-violet),transparent); }
    .partners-card::after { content:'';position:absolute;bottom:0;left:0;right:0;height:1px;background:linear-gradient(90deg,transparent,rgba(139,126,255,0.42),transparent); }
    .partners-heading { font-family:var(--font-hud);font-size:1rem;font-weight:700;color:var(--prc-violet);letter-spacing:0.08em;text-shadow:0 0 18px rgba(139,126,255,0.60);margin-bottom:8px; }
    .partners-sub { font-size:0.90rem;color:var(--text-mid);margin-bottom:44px; }
    .partners-section-label { font-family:var(--font-hud);font-size:0.54rem;font-weight:700;letter-spacing:0.22em;text-transform:uppercase;color:var(--text-dim);margin-bottom:24px;display:flex;align-items:center;gap:10px;justify-content:center; }
    .partners-section-label::before,.partners-section-label::after { content:'';width:40px;height:1px;background:rgba(139,126,255,0.30); }
    .partners-logos { display:flex;align-items:center;justify-content:center;flex-wrap:wrap;gap:0;margin-bottom:40px; }
    .partner-item { display:flex;flex-direction:column;align-items:center;gap:10px;padding:18px 32px;border-right:1px solid var(--border-neon);transition:all .25s; }
    .partner-item:last-child { border-right:none; }
    .partner-item:hover { background:rgba(139,126,255,0.06);box-shadow:0 0 16px rgba(139,126,255,0.10); }
    .partner-logo { height:50px;width:auto;object-fit:contain;transition:transform .25s,filter .25s;filter:saturate(0.4) brightness(0.80); }
    .partner-item:hover .partner-logo { transform:scale(1.08);filter:saturate(1.0) brightness(1.0) drop-shadow(0 0 6px rgba(139,126,255,0.50)); }
    .partner-name { font-family:var(--font-hud);font-size:0.60rem;font-weight:600;color:var(--text-dim);letter-spacing:0.04em;text-align:center;line-height:1.3;transition:color .25s; }
    .partner-item:hover .partner-name { color:var(--text-mid); }
    .partner-abbr { width:50px;height:50px;border-radius:50%;display:flex;align-items:center;justify-content:center;font-family:var(--font-hud);font-size:0.46rem;font-weight:700;text-align:center;line-height:1.2;padding:4px; }
    .partner-abbr-violet { background:rgba(139,126,255,0.08);border:1px solid rgba(139,126,255,0.25);color:var(--prc-violet); }
    .partner-abbr-volt { background:rgba(255,233,48,0.08);border:1px solid rgba(255,233,48,0.25);color:var(--creo-volt); }
    .academic-badges { display:flex;flex-wrap:wrap;gap:8px;justify-content:center; }
    .academic-badge { font-family:var(--font-hud);font-size:0.54rem;font-weight:600;letter-spacing:0.06em;padding:5px 14px;border:1px solid rgba(139,126,255,0.18);background:rgba(139,126,255,0.04);color:var(--text-soft);transition:all .2s; }
    .academic-badge:hover { border-color:rgba(139,126,255,0.38);color:var(--prc-violet);background:rgba(139,126,255,0.08); }

    /* ===== CONTACT ===== */
    #contact-info { padding:var(--section-pad); }
    .contact-grid { display:grid;grid-template-columns:1fr 1fr;gap:0;border:1px solid var(--border-neon);position:relative;overflow:hidden;background:rgba(139,126,255,0.03); }
    .contact-grid::before { content:'';position:absolute;top:0;left:0;right:0;height:1px;background:linear-gradient(90deg,transparent,var(--creo-amber),var(--prc-violet),transparent); }
    .contact-left { padding:52px;border-right:1px solid var(--border-neon); }
    .contact-right { padding:52px;display:flex;flex-direction:column;justify-content:center;background:rgba(139,126,255,0.015); }
    .contact-items { display:flex;flex-direction:column;gap:20px;margin-top:28px; }
    .contact-item { display:flex;align-items:center;gap:16px; }
    .contact-icon { width:42px;height:42px;flex-shrink:0;background:rgba(255,160,48,0.08);border:1px solid rgba(255,160,48,0.24);display:flex;align-items:center;justify-content:center;color:var(--creo-amber);font-size:1rem; }
    .contact-item-label { font-family:var(--font-hud);font-size:0.52rem;letter-spacing:0.14em;text-transform:uppercase;color:var(--text-soft);margin-bottom:3px; }
    .contact-item-val { font-size:0.90rem;color:var(--text-mid);transition:color .2s; }
    a.contact-item-val:hover { color:var(--creo-amber); }
    .contact-cta-box { display:flex;flex-direction:column;gap:16px; }
    .contact-cta-title { font-family:var(--font-hud);font-size:1.2rem;font-weight:800;color:#fff;letter-spacing:0.02em; }
    .contact-cta-desc { font-size:0.90rem;color:var(--text-mid);line-height:1.76; }
    .contact-online-dot { width:7px;height:7px;border-radius:50%;background:#44FF88;flex-shrink:0;box-shadow:0 0 8px rgba(68,255,136,0.70);animation:neonPulse 1.8s ease-in-out infinite; }
    .contact-online-note { display:flex;align-items:center;gap:8px;font-family:var(--font-hud);font-size:0.54rem;letter-spacing:0.10em;text-transform:uppercase;color:var(--text-soft);margin-top:6px; }

    /* ===== REFERENCES ===== */
    #references { background:rgba(0,0,8,0.50);padding:90px 0; }
    .ref-header { text-align:center;max-width:560px;margin:0 auto 52px; }
    .ref-header .section-eyebrow { justify-content:center; }
    .ref-grid { display:grid;grid-template-columns:repeat(2,1fr);gap:18px; }
    .ref-card { position:relative;overflow:hidden;background:rgba(0,0,8,0.70);border:1px solid rgba(139,126,255,0.14);display:flex;flex-direction:column;transition:all .35s cubic-bezier(0.23,1,0.32,1);text-decoration:none;color:inherit; }
    .ref-card::before { content:'';position:absolute;top:0;left:0;right:0;height:2px;opacity:0;transition:opacity .3s; }
    .ref-card.fb::before { background:linear-gradient(90deg,transparent,#1877F2,transparent); }
    .ref-card.award::before { background:linear-gradient(90deg,transparent,var(--creo-volt),transparent); }
    .ref-card:hover { transform:translateY(-6px); }
    .ref-card.fb:hover { border-color:rgba(24,119,242,0.38);box-shadow:0 0 36px rgba(24,119,242,0.14); }
    .ref-card.award:hover { border-color:rgba(255,233,48,0.38);box-shadow:0 0 36px rgba(255,233,48,0.14); }
    .ref-card:hover::before { opacity:1; }
    .ref-card-top { display:flex;align-items:center;gap:14px;padding:20px 22px 16px;border-bottom:1px solid rgba(139,126,255,0.10); }
    .ref-source-icon { width:38px;height:38px;flex-shrink:0;display:flex;align-items:center;justify-content:center;font-size:1.1rem; }
    .ref-card.fb .ref-source-icon { background:rgba(24,119,242,0.12);border:1px solid rgba(24,119,242,0.30);color:#1877F2; }
    .ref-card.award .ref-source-icon { background:rgba(255,233,48,0.10);border:1px solid rgba(255,233,48,0.28);color:var(--creo-volt); }
    .ref-source-meta { display:flex;flex-direction:column;gap:2px; }
    .ref-source-type { font-family:var(--font-hud);font-size:0.46rem;font-weight:700;letter-spacing:0.18em;text-transform:uppercase; }
    .ref-card.fb .ref-source-type { color:#1877F2; }
    .ref-card.award .ref-source-type { color:var(--creo-volt); }
    .ref-source-platform { font-family:var(--font-hud);font-size:0.52rem;color:var(--text-dim);letter-spacing:0.06em; }
    .ref-card-body { padding:18px 22px 20px;flex:1;display:flex;flex-direction:column;gap:10px; }
    .ref-title { font-family:var(--font-hud);font-size:0.80rem;font-weight:700;color:var(--text-high);line-height:1.35;letter-spacing:0.02em; }
    .ref-desc { font-size:0.838rem;color:var(--text-mid);line-height:1.68; }
    .ref-card-footer { padding:12px 22px;border-top:1px solid rgba(139,126,255,0.08);display:flex;align-items:center;justify-content:space-between; }
    .ref-tag { font-family:var(--font-hud);font-size:0.48rem;font-weight:600;letter-spacing:0.10em;text-transform:uppercase;padding:3px 10px;border:1px solid; }
    .ref-card.fb .ref-tag { color:#1877F2;border-color:rgba(24,119,242,0.28);background:rgba(24,119,242,0.06); }
    .ref-card.award .ref-tag { color:var(--creo-volt);border-color:rgba(255,233,48,0.28);background:rgba(255,233,48,0.06); }
    .ref-arrow { display:flex;align-items:center;gap:6px;font-family:var(--font-hud);font-size:0.52rem;font-weight:700;letter-spacing:0.10em;text-transform:uppercase;transition:gap .2s,color .2s; }
    .ref-card.fb .ref-arrow { color:rgba(24,119,242,0.60); }
    .ref-card.award .ref-arrow { color:rgba(255,233,48,0.60); }
    .ref-card:hover .ref-arrow { gap:10px; }
    .ref-card.fb:hover .ref-arrow { color:#1877F2; }
    .ref-card.award:hover .ref-arrow { color:var(--creo-volt); }
    @media (max-width:768px) { .ref-grid { grid-template-columns:1fr; } }

    /* ===== PAGE SIDEBAR ===== */
    @keyframes psbFadeIn { from{opacity:0;transform:translateY(-50%) translateX(-10px)}to{opacity:1;transform:translateY(-50%) translateX(0)} }
    #page-sidebar { position:fixed;left:20px;top:50%;transform:translateY(-50%);z-index:500;display:flex;flex-direction:column;background:rgba(3,2,13,0.72);backdrop-filter:blur(16px);-webkit-backdrop-filter:blur(16px);border:1px solid rgba(139,126,255,0.18);border-left:2px solid rgba(139,126,255,0.45);padding:10px 14px 10px 0;gap:0;box-shadow:0 0 40px rgba(0,0,0,0.55),0 0 22px rgba(139,126,255,0.07);animation:psbFadeIn .5s ease .4s both; }
    #page-sidebar::before { content:'';position:absolute;top:0;left:0;right:0;height:1px;background:linear-gradient(90deg,var(--prc-violet),transparent);opacity:0.55; }
    #page-sidebar::after { content:'';position:absolute;bottom:0;left:0;right:0;height:1px;background:linear-gradient(90deg,rgba(139,126,255,0.35),transparent); }
    .psb-item { display:flex;align-items:center;gap:10px;cursor:pointer;padding:7px 10px 7px 14px;position:relative;transition:background .2s; }
    .psb-item:hover { background:rgba(139,126,255,0.07); }
    .psb-item.active { background:rgba(139,126,255,0.10); }
    .psb-item.active::before { content:'';position:absolute;left:-2px;top:20%;bottom:20%;width:2px;background:var(--prc-violet);box-shadow:0 0 8px rgba(139,126,255,0.90);border-radius:0 2px 2px 0; }
    .psb-dot { width:6px;height:6px;border-radius:50%;border:1.5px solid rgba(139,126,255,0.30);background:transparent;transition:all .22s;flex-shrink:0; }
    .psb-item:hover .psb-dot { border-color:var(--prc-violet);background:rgba(139,126,255,0.35);box-shadow:0 0 8px rgba(139,126,255,0.55); }
    .psb-item.active .psb-dot { border-color:var(--prc-violet);background:var(--prc-violet);box-shadow:0 0 10px rgba(139,126,255,0.90);animation:neonPulse 1.6s ease-in-out infinite; }
    .psb-label { font-family:var(--font-hud);font-size:0.46rem;font-weight:700;letter-spacing:0.13em;text-transform:uppercase;color:var(--text-dim);white-space:nowrap;transition:color .2s;line-height:1; }
    .psb-item:hover .psb-label { color:var(--text-soft); }
    .psb-item.active .psb-label { color:var(--prc-violet);text-shadow:0 0 10px rgba(139,126,255,0.60); }
    .psb-line { height:1px;background:rgba(139,126,255,0.08);margin:0 10px 0 14px; }
    @media (max-width:1100px) { #page-sidebar { display:none; } }

    /* ===== FOOTER ===== */
    footer { background:rgba(0,0,6,0.95);border-top:1px solid var(--border-neon);padding:70px 0 32px; }
    .footer-inner { max-width:1340px;margin:0 auto;padding:0 36px; }
    .footer-top { display:grid;grid-template-columns:2fr 1fr 1fr 1fr;gap:52px;margin-bottom:52px; }
    .footer-brand img { height:36px;width:auto;margin-bottom:16px; }
    .footer-brand p { font-size:0.875rem;color:var(--text-mid);line-height:1.80;margin-bottom:22px; }
    .footer-contact-list { display:flex;flex-direction:column;gap:11px;margin-bottom:24px; }
    .footer-contact-item { display:flex;align-items:center;gap:11px;font-size:0.875rem;color:var(--text-mid); }
    .footer-contact-item i { color:var(--prc-violet);font-size:0.95rem;flex-shrink:0; }
    .footer-contact-item a { color:var(--text-mid);transition:color .2s; }
    .footer-contact-item a:hover { color:var(--prc-violet); }
    .social-links { display:flex;gap:8px; }
    .social-link { width:40px;height:40px;background:rgba(139,126,255,0.04);border:1px solid rgba(139,126,255,0.18);display:flex;align-items:center;justify-content:center;font-size:1rem;color:rgba(139,126,255,0.50);transition:all .25s; }
    .social-link:hover { background:rgba(139,126,255,0.12);color:var(--prc-violet);box-shadow:0 0 14px rgba(139,126,255,0.35);border-color:var(--prc-violet); }
    .footer-col h4 { font-family:var(--font-hud);font-size:0.65rem;font-weight:700;letter-spacing:0.10em;text-transform:uppercase;margin-bottom:20px;color:var(--prc-ice); }
    .footer-col ul { display:flex;flex-direction:column;gap:10px; }
    .footer-col ul li a { font-size:0.875rem;color:var(--text-mid);transition:all .2s;display:flex;align-items:center;gap:8px; }
    .footer-col ul li a i { font-size:0.65rem;color:rgba(139,126,255,0.38);transition:color .2s; }
    .footer-col ul li a:hover { color:var(--prc-violet);padding-left:4px; }
    .footer-col ul li a:hover i { color:var(--prc-violet); }
    .footer-bottom { padding-top:24px;border-top:1px solid rgba(139,126,255,0.12);display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:14px; }
    .footer-bottom p { font-family:var(--font-hud);font-size:0.58rem;color:var(--text-soft);letter-spacing:0.06em; }
    .footer-bottom-links { display:flex;gap:22px; }
    .footer-bottom-links a { font-family:var(--font-hud);font-size:0.58rem;color:var(--text-soft);transition:color .2s;letter-spacing:0.06em; }
    .footer-bottom-links a:hover { color:var(--prc-violet); }

    /* ===== RESPONSIVE ===== */
    @media (max-width:1024px) {
      .who-grid,.bg-grid,.mv-grid { grid-template-columns:1fr; }
      .who-badge { display:none; }
      .bg-img-secondary { display:none; }
      .values-grid { grid-template-columns:1fr 1fr; }
      .programs-grid { grid-template-columns:1fr 1fr; }
      .footer-top { grid-template-columns:1fr 1fr;gap:36px; }
      .partners-card { padding:40px 28px; }
      .partner-item { padding:14px 22px; }
      .stats-inner { grid-template-columns:repeat(2,1fr); }
      .stat-item:nth-child(2) { border-right:none; }
      .stat-item:nth-child(1),.stat-item:nth-child(2) { border-bottom:1px solid var(--border-neon);padding-bottom:24px; }
      .stat-item:nth-child(3),.stat-item:nth-child(4) { padding-top:24px; }
    }
    @media (max-width:768px) {
      :root { --section-pad:70px 0;--nav-height:62px; }
      body { cursor:auto; } button { cursor:pointer; }
      .cursor-dot,.cursor-ring { display:none; }
      .nav-links { display:none; } .nav-hamburger { display:flex; }
      .values-grid { grid-template-columns:1fr; }
      .programs-grid { grid-template-columns:1fr; }
      .contact-grid { grid-template-columns:1fr; }
      .contact-left { border-right:none;border-bottom:1px solid var(--border-neon);padding:36px 28px; }
      .contact-right { padding:36px 28px; }
      .footer-top { grid-template-columns:1fr;gap:32px; }
      .footer-bottom { flex-direction:column;text-align:center; }
      .partners-logos { flex-direction:column; }
      .partner-item { border-right:none;border-bottom:1px solid var(--border-neon);width:100%; }
      .partner-item:last-child { border-bottom:none; }
    }
    @media (max-width:520px) {
      :root { --nav-height:58px; }
      .nav-inner { padding:0 14px; }
      .section-inner,.footer-inner,.stats-inner { padding:0 16px; }
      .page-hero-inner { padding:0 16px; }
      .nav-brand span { display:none; }
      .mv-card { padding:32px 24px; }
      .contact-left,.contact-right { padding:28px 20px; }
    }
    ::-webkit-scrollbar { width:4px; }
    ::-webkit-scrollbar-track { background:var(--bg-void); }
    ::-webkit-scrollbar-thumb { background:var(--prc-violet);box-shadow:0 0 8px rgba(139,126,255,0.70);border-radius:2px; }
  </style>
</head>
<body>

  <div class="cursor-dot" id="cursorDot"></div>
  <div class="cursor-ring" id="cursorRing"></div>
  <div class="hex-grid" aria-hidden="true"></div>

  <div class="page-wrapper">

    <!-- PAGE NAV SIDEBAR -->
    <nav id="page-sidebar" aria-label="Page sections">
      <div class="psb-item" data-target="who"><span class="psb-dot"></span><span class="psb-label">Who Is Creotec</span></div>
      <div class="psb-line"></div>
      <div class="psb-item" data-target="mission"><span class="psb-dot"></span><span class="psb-label">Mission &amp; Vision</span></div>
      <div class="psb-line"></div>
      <div class="psb-item" data-target="values"><span class="psb-dot"></span><span class="psb-label">Our Values</span></div>
      <div class="psb-line"></div>
      <div class="psb-item" data-target="background"><span class="psb-dot"></span><span class="psb-label">Background</span></div>
      <div class="psb-line"></div>
      <div class="psb-item" data-target="programs"><span class="psb-dot"></span><span class="psb-label">Programs</span></div>
      <div class="psb-line"></div>
      <div class="psb-item" data-target="partners"><span class="psb-dot"></span><span class="psb-label">Partners</span></div>
      <div class="psb-line"></div>
      <div class="psb-item" data-target="contact-info"><span class="psb-dot"></span><span class="psb-label">Contact</span></div>
      <div class="psb-line"></div>
      <div class="psb-item" data-target="references"><span class="psb-dot"></span><span class="psb-label">References</span></div>
    </nav>

    <?php $activePage = 'about'; include 'nav.php'; ?>

    <!-- ══════════════════════════════════════
         PAGE HERO
    ══════════════════════════════════════ -->
    <header class="page-hero">
      <div class="page-hero-scan"></div>
      <div class="page-hero-inner">
        <div class="page-hero-eyebrow">
          <div class="page-hero-blink"></div>
          <?= me($meta, 'hero_eyebrow', 'About the Organizer') ?>
        </div>
        <h1 class="page-hero-title"><?= mh($meta, 'hero_title', 'About <span class="accent">Creotec</span>') ?></h1>
        <p class="page-hero-desc"><?= me($meta, 'hero_desc') ?></p>
        <div class="page-hero-divider" aria-hidden="true">
          <div class="page-hero-divider-line"></div>
          <div class="page-hero-divider-diamond"></div>
          <div class="page-hero-divider-line right"></div>
        </div>
      </div>
    </header>

    <!-- ══════════════════════════════════════
         WHO IS CREOTEC
    ══════════════════════════════════════ -->
    <section id="who">
      <div class="section-inner">
        <div class="who-grid">
          <div class="who-text reveal-left">
            <div class="section-eyebrow">Who Is Creotec?</div>
            <h2 class="section-title">Creotec<br/><span class="accent">Philippines Inc.</span></h2>
            <?php if (!empty($meta['who_p1'])): ?>
            <p><?= mh($meta, 'who_p1') ?></p>
            <?php endif; ?>
            <?php if (!empty($meta['who_p2'])): ?>
            <p><?= mh($meta, 'who_p2') ?></p>
            <?php endif; ?>
            <?php if (!empty($meta['who_p3'])): ?>
            <p><?= mh($meta, 'who_p3') ?></p>
            <?php endif; ?>
            <div class="who-actions">
              <a href="#programs" class="btn-neon-primary"><i class="fi fi-rr-apps"></i> Our Programs</a>
              <a href="#contact-info" class="btn-neon-amber"><i class="fi fi-rr-envelope"></i> Contact Us</a>
            </div>
          </div>
          <div class="who-visual reveal">
            <div class="who-img-frame">
              <img src="<?= me($meta, 'who_img', 'assets/about-creotec.png') ?>"
                   alt="Creotec Philippines building and facility" />
            </div>
            <div class="who-badge">
              <span><?= me($meta, 'who_badge_year', '2015') ?></span>
              <?= me($meta, 'who_badge_sub', 'Est. Biñan, Laguna') ?>
            </div>
          </div>
        </div>
      </div>
    </section>

    <!-- ══════════════════════════════════════
         STATS STRIP
    ══════════════════════════════════════ -->
    <?php if (!empty($stats)): ?>
    <div class="stats-strip">
      <div class="stats-inner">
        <?php foreach ($stats as $i => $s): ?>
        <div class="stat-item reveal<?= $i > 0 ? ' reveal-delay-'.$i : '' ?>">
          <div class="stat-icon"><i class="fi <?= htmlspecialchars($s['stat_icon']) ?>"></i></div>
          <span class="stat-num"><?= htmlspecialchars($s['stat_num']) ?></span>
          <span class="stat-label"><?= htmlspecialchars($s['stat_label']) ?></span>
        </div>
        <?php endforeach; ?>
      </div>
    </div>
    <?php endif; ?>

    <!-- ══════════════════════════════════════
         MISSION & VISION
    ══════════════════════════════════════ -->
    <section id="mission">
      <div class="section-inner">
        <div style="text-align:center;max-width:600px;margin:0 auto 52px;">
          <div class="section-eyebrow" style="justify-content:center;">Our Purpose</div>
          <h2 class="section-title reveal">Mission &amp; <span class="accent">Vision</span></h2>
        </div>
        <div class="mv-grid">
          <div class="mv-card reveal">
            <div class="mv-tag"><span class="mv-tag-dot"></span> Our Vision</div>
            <h3 class="mv-title">Where We're Headed</h3>
            <p class="mv-body"><?= me($meta, 'vision_text') ?></p>
          </div>
          <div class="mv-card amber-accent reveal reveal-delay-1">
            <div class="mv-tag"><span class="mv-tag-dot"></span> Our Mission</div>
            <h3 class="mv-title">What Drives Us</h3>
            <p class="mv-body"><?= me($meta, 'mission_text') ?></p>
          </div>
        </div>
      </div>
    </section>

    <!-- ══════════════════════════════════════
         OUR VALUES
    ══════════════════════════════════════ -->
    <?php if (!empty($values)): ?>
    <section id="values">
      <div class="section-inner">
        <div class="values-header reveal">
          <div class="section-eyebrow" style="justify-content:center;">Our Values</div>
          <h2 class="section-title">What We <span class="accent">Stand For</span></h2>
          <p class="section-desc" style="margin:0 auto;">Core principles that guide every program, partnership, and innovation we pursue.</p>
        </div>
        <div class="values-grid">
          <?php foreach ($values as $i => $v): ?>
          <div class="value-card reveal<?= $i > 0 ? ' reveal-delay-'.min($i,3) : '' ?>">
            <div class="value-num"><?= htmlspecialchars($v['val_num']) ?></div>
            <span class="value-icon value-icon-<?= $i % 4 ?>">
              <i class="fi <?= htmlspecialchars($v['val_icon']) ?>"></i>
            </span>
            <div class="value-title"><?= htmlspecialchars($v['val_title']) ?></div>
            <p class="value-desc"><?= htmlspecialchars($v['val_desc']) ?></p>
          </div>
          <?php endforeach; ?>
        </div>
      </div>
    </section>
    <?php endif; ?>

    <!-- ══════════════════════════════════════
         BACKGROUND / EMSG
    ══════════════════════════════════════ -->
    <section id="background">
      <div class="section-inner">
        <div class="bg-grid">
          <div class="bg-text reveal-left">
            <div class="section-eyebrow">Our Background</div>
            <h2 class="section-title">Industry Roots,<br/><span class="accent">Educational Heart</span></h2>
            <?php if (!empty($meta['bg_p1'])): ?>
            <p><?= me($meta, 'bg_p1') ?></p>
            <?php endif; ?>
            <?php if (!empty($meta['bg_p2'])): ?>
            <p><?= me($meta, 'bg_p2') ?></p>
            <?php endif; ?>
            <?php if (!empty($highlights)): ?>
            <div class="bg-highlights">
              <?php foreach ($highlights as $h): ?>
              <div class="bg-highlight">
                <div class="bg-highlight-icon"><i class="fi <?= htmlspecialchars($h['hl_icon']) ?>"></i></div>
                <div class="bg-highlight-text">
                  <strong><?= htmlspecialchars($h['hl_title']) ?></strong>
                  <span><?= htmlspecialchars($h['hl_desc']) ?></span>
                </div>
              </div>
              <?php endforeach; ?>
            </div>
            <?php endif; ?>
          </div>
          <div class="bg-visual reveal">
            <div class="bg-img-stack">
              <div class="bg-emsg-badge">// EMS Group Member</div>
              <img class="bg-img-main"
                   src="<?= me($meta, 'bg_img_main', 'assets/about-history.jpg') ?>"
                   alt="Industry training facility" />
              <?php if (!empty($meta['bg_img_secondary'])): ?>
              <img class="bg-img-secondary"
                   src="<?= me($meta, 'bg_img_secondary') ?>"
                   alt="Electronics manufacturing" />
              <?php endif; ?>
            </div>
          </div>
        </div>
      </div>
    </section>

    <!-- ══════════════════════════════════════
         PROGRAMS
    ══════════════════════════════════════ -->
    <?php if (!empty($programs)): ?>
    <section id="programs">
      <div class="section-inner">
        <div class="programs-header">
          <div>
            <div class="section-eyebrow reveal-left">What We Offer</div>
            <h2 class="section-title reveal">Training Programs &amp;<br/><span class="accent">Learning Services</span></h2>
            <p class="section-desc reveal">Comprehensive programs spanning K–12, tertiary, technical, and professional education.</p>
          </div>
        </div>
        <div class="programs-grid">
          <?php foreach ($programs as $i => $p):
            $delay_class = '';
            $mod = $i % 3;
            if ($mod === 1) $delay_class = ' reveal-delay-1';
            if ($mod === 2) $delay_class = ' reveal-delay-2';
            $type_class = in_array($p['prog_type'], ['tesda','competition']) ? ' ' . $p['prog_type'] : '';
            $tags = array_filter(array_map('trim', explode(',', $p['prog_tags'])));
          ?>
          <div class="prog-card<?= $type_class ?> reveal<?= $delay_class ?>">
            <?php if (!empty($p['prog_img'])): ?>
            <div class="prog-img-wrap">
              <img class="prog-img"
                   src="<?= htmlspecialchars($p['prog_img']) ?>"
                   alt="<?= htmlspecialchars($p['prog_title']) ?>"
                   loading="lazy" />
              <div class="prog-img-overlay"></div>
            </div>
            <?php endif; ?>
            <div class="prog-body">
              <div class="prog-num"><?= htmlspecialchars($p['prog_num']) ?></div>
              <div class="prog-title"><?= htmlspecialchars($p['prog_title']) ?></div>
              <p class="prog-desc"><?= htmlspecialchars($p['prog_desc']) ?></p>
              <?php if (!empty($tags)): ?>
              <div class="prog-tags">
                <?php foreach ($tags as $tag): ?>
                <span class="prog-tag"><?= htmlspecialchars($tag) ?></span>
                <?php endforeach; ?>
              </div>
              <?php endif; ?>
            </div>
          </div>
          <?php endforeach; ?>
        </div>
      </div>
    </section>
    <?php endif; ?>

    <!-- ══════════════════════════════════════
         PARTNERS
    ══════════════════════════════════════ -->
    <section id="partners">
      <div class="section-inner">
        <div class="partners-card reveal">
          <div class="partners-heading"><?= me($meta, 'partners_heading', '// Key Partnerships') ?></div>
          <p class="partners-sub"><?= me($meta, 'partners_sub') ?></p>

          <?php if (!empty($partners_gov)): ?>
          <div class="partners-section-label">Government Institutions</div>
          <div class="partners-logos">
            <?php foreach ($partners_gov as $pg): ?>
            <div class="partner-item">
              <?php if (!empty($pg['pg_logo'])): ?>
                <img class="partner-logo"
                     src="<?= htmlspecialchars($pg['pg_logo']) ?>"
                     alt="<?= htmlspecialchars($pg['pg_short']) ?>"
                     onerror="this.style.display='none';this.nextElementSibling.style.display='flex';" />
                <div class="partner-abbr partner-abbr-violet" style="display:none;"><?= htmlspecialchars($pg['pg_short']) ?></div>
              <?php else: ?>
                <div class="partner-abbr <?= (strtoupper($pg['pg_short']) === 'TESDA') ? 'partner-abbr-volt' : 'partner-abbr-violet' ?>">
                  <?= htmlspecialchars($pg['pg_short']) ?>
                </div>
              <?php endif; ?>
              <div class="partner-name">
                <?= htmlspecialchars($pg['pg_name']) ?><br/>
                <span style="color:var(--prc-violet);"><?= htmlspecialchars($pg['pg_short']) ?></span>
              </div>
            </div>
            <?php endforeach; ?>
          </div>
          <?php endif; ?>

          <?php if (!empty($partners_acad)): ?>
          <div class="partners-section-label" style="margin-top:16px;">Academic Institutions</div>
          <div class="academic-badges">
            <?php foreach ($partners_acad as $pa): ?>
            <span class="academic-badge"><?= htmlspecialchars($pa['pa_name']) ?></span>
            <?php endforeach; ?>
            <span class="academic-badge">+ Many More</span>
          </div>
          <?php endif; ?>
        </div>
      </div>
    </section>

    <!-- ══════════════════════════════════════
         CONTACT
    ══════════════════════════════════════ -->
    <section id="contact-info">
      <div class="section-inner">
        <div style="text-align:center;max-width:560px;margin:0 auto 52px;">
          <div class="section-eyebrow" style="justify-content:center;">Get In Touch</div>
          <h2 class="section-title reveal">Reach <span class="accent">Creotec</span></h2>
          <p class="section-desc" style="margin:0 auto;">Questions about programs, partnerships, or the Philippine Robotics Cup? Our team is ready to help.</p>
        </div>
        <div class="contact-grid reveal">
          <div class="contact-left">
            <div class="section-eyebrow">Contact Details</div>
            <h3 style="font-family:var(--font-hud);font-size:1.3rem;font-weight:800;color:#fff;margin-bottom:4px;">Creotec Philippines Inc.</h3>
            <p style="font-size:0.88rem;color:var(--text-soft);"><?= me($meta, 'contact_address') ?></p>
            <div class="contact-items">

              <?php if (!empty($meta['contact_phone1']) || !empty($meta['contact_phone2'])): ?>
              <div class="contact-item">
                <div class="contact-icon"><i class="fi fi-rr-phone-call"></i></div>
                <div>
                  <div class="contact-item-label">Phone / Mobile</div>
                  <?php if (!empty($meta['contact_phone1'])): ?>
                  <a href="tel:<?= preg_replace('/\s+/', '', me($meta, 'contact_phone1')) ?>" class="contact-item-val">
                    <?= me($meta, 'contact_phone1') ?>
                  </a>
                  <?php endif; ?>
                  <?php if (!empty($meta['contact_phone2'])): ?>
                  <a href="tel:<?= preg_replace('/\s+/', '', me($meta, 'contact_phone2')) ?>" class="contact-item-val" style="display:block;">
                    <?= me($meta, 'contact_phone2') ?>
                  </a>
                  <?php endif; ?>
                </div>
              </div>
              <?php endif; ?>

              <?php if (!empty($meta['contact_email'])): ?>
              <div class="contact-item">
                <div class="contact-icon"><i class="fi fi-rr-envelope"></i></div>
                <div>
                  <div class="contact-item-label">Email</div>
                  <a href="mailto:<?= me($meta, 'contact_email') ?>" class="contact-item-val">
                    <?= me($meta, 'contact_email') ?>
                  </a>
                </div>
              </div>
              <?php endif; ?>

              <?php if (!empty($meta['contact_website'])): ?>
              <div class="contact-item">
                <div class="contact-icon"><i class="fi fi-rr-globe"></i></div>
                <div>
                  <div class="contact-item-label">Website</div>
                  <?php
                    $site = $meta['contact_website'];
                    $site_href = (strpos($site, 'http') === 0) ? $site : 'https://' . $site;
                  ?>
                  <a href="<?= htmlspecialchars($site_href) ?>" target="_blank" rel="noopener" class="contact-item-val">
                    <?= me($meta, 'contact_website') ?>
                  </a>
                </div>
              </div>
              <?php endif; ?>

              <?php if (!empty($meta['contact_fb_url'])): ?>
              <div class="contact-item">
                <div class="contact-icon"><i class="fi fi-brands-facebook"></i></div>
                <div>
                  <div class="contact-item-label">Facebook</div>
                  <a href="<?= me($meta, 'contact_fb_url') ?>" target="_blank" rel="noopener" class="contact-item-val">
                    <?= me($meta, 'contact_fb_label', 'Creotec Philippines Inc.') ?>
                  </a>
                </div>
              </div>
              <?php endif; ?>

            </div>
          </div>
          <div class="contact-right">
            <div class="contact-cta-box">
              <div style="width:54px;height:54px;background:rgba(255,160,48,0.08);border:1px solid rgba(255,160,48,0.30);display:flex;align-items:center;justify-content:center;color:var(--creo-amber);font-size:1.4rem;">
                <i class="fi fi-rr-paper-plane"></i>
              </div>
              <div class="contact-cta-title">Partner With Us</div>
              <p class="contact-cta-desc">
                Whether you're a school looking to bring robotics into the classroom, a government agency seeking
                industry training solutions, or an organization interested in the Philippine Robotics Cup — we'd
                love to connect and explore how we can work together.
              </p>
              <a href="contact.php" class="btn-neon-amber" style="align-self:flex-start;">
                <i class="fi fi-rr-paper-plane"></i> Send a Message
              </a>
              <div class="contact-online-note">
                <span class="contact-online-dot"></span>
                Typical response time: within 24 hours
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>

    <!-- ══════════════════════════════════════
         REFERENCES
    ══════════════════════════════════════ -->
    <?php if (!empty($refs)): ?>
    <section id="references">
      <div class="section-inner">
        <div class="ref-header reveal">
          <div class="section-eyebrow" style="justify-content:center;">References</div>
          <h2 class="section-title">Related <span class="accent">Links</span></h2>
          <p class="section-desc" style="margin:0 auto;">Posts, videos, and updates from Creotec Philippines related to our programs and achievements.</p>
        </div>
        <div class="ref-grid">
          <?php foreach ($refs as $i => $ref):
            $type   = htmlspecialchars($ref['ref_type']);
            $delay  = ($i % 2 === 1) ? ' reveal-delay-1' : '';
            $icon   = ($ref['ref_type'] === 'award') ? '<i class="fi fi-sr-trophy"></i>' : '<i class="fi fi-brands-facebook"></i>';
          ?>
          <a class="ref-card <?= $type ?> reveal<?= $delay ?>"
             href="<?= htmlspecialchars($ref['ref_url']) ?>"
             target="_blank" rel="noopener">
            <div class="ref-card-top">
              <div class="ref-source-icon"><?= $icon ?></div>
              <div class="ref-source-meta">
                <span class="ref-source-type"><?= htmlspecialchars($ref['ref_source']) ?></span>
                <span class="ref-source-platform"><?= htmlspecialchars($ref['ref_platform']) ?></span>
              </div>
            </div>
            <div class="ref-card-body">
              <div class="ref-title"><?= htmlspecialchars($ref['ref_title']) ?></div>
              <p class="ref-desc"><?= htmlspecialchars($ref['ref_desc']) ?></p>
            </div>
            <div class="ref-card-footer">
              <span class="ref-tag"><?= htmlspecialchars($ref['ref_tag']) ?></span>
              <span class="ref-arrow">View Post <i class="fi fi-rr-arrow-right"></i></span>
            </div>
          </a>
          <?php endforeach; ?>
        </div>
      </div>
    </section>
    <?php endif; ?>

    <!-- ══════════════════════════════════════
         FOOTER
    ══════════════════════════════════════ -->
    <footer role="contentinfo">
      <div class="footer-inner">
        <div class="footer-top">
          <div class="footer-brand">
            <img src="assets/PRC White Logo.png" alt="Philippine Robotics Cup" />
            <p>The Philippine Robotics Cup is a premier national robotics competition promoting STEM education and preparing Filipino students for the future of technology.</p>
            <div class="footer-contact-list">
              <div class="footer-contact-item"><i class="fi fi-brands-facebook"></i><a href="https://www.facebook.com/profile.php?id=61579706372017" target="_blank" rel="noopener">Philippine Robotics Cup</a></div>
              <div class="footer-contact-item"><i class="fi fi-rr-phone-call"></i><a href="tel:+639177713961">+63 917 771 3961</a></div>
              <div class="footer-contact-item"><i class="fi fi-rr-envelope"></i><a href="mailto:philippineroboticscup@gmail.com">philippineroboticscup@gmail.com</a></div>
            </div>
            <div class="social-links"><a href="https://www.facebook.com/profile.php?id=61579706372017" target="_blank" rel="noopener" class="social-link" aria-label="Facebook"><i class="fi fi-brands-facebook"></i></a></div>
          </div>
          <nav class="footer-col" aria-label="Competition"><h4>Competition</h4><ul><li><a href="categories.php"><i class="fi fi-rr-angle-right"></i>Categories</a></li><li><a href="#"><i class="fi fi-rr-angle-right"></i>Rules &amp; Guidelines</a></li><li><a href="#"><i class="fi fi-rr-angle-right"></i>Schedule</a></li><li><a href="#"><i class="fi fi-rr-angle-right"></i>Past Events</a></li></ul></nav>
          <nav class="footer-col" aria-label="Participate"><h4>Participate</h4><ul><li><a href="#"><i class="fi fi-rr-angle-right"></i>Register Now</a></li><li><a href="#"><i class="fi fi-rr-angle-right"></i>Order Materials</a></li><li><a href="contact.php"><i class="fi fi-rr-angle-right"></i>Contact Us</a></li><li><a href="contact.php"><i class="fi fi-rr-angle-right"></i>FAQ</a></li></ul></nav>
          <nav class="footer-col" aria-label="Resources"><h4>Resources</h4><ul><li><a href="#"><i class="fi fi-rr-angle-right"></i>News &amp; Updates</a></li><li><a href="#"><i class="fi fi-rr-angle-right"></i>Gallery</a></li><li><a href="index.php#video"><i class="fi fi-rr-angle-right"></i>Videos</a></li><li><a href="about.php"><i class="fi fi-rr-angle-right"></i>About Creotec</a></li></ul></nav>
        </div>
        <div class="footer-bottom">
          <p>&copy; 2026 Philippine Robotics Cup // Creotec Philippines Inc. All rights reserved.</p>
          <div class="footer-bottom-links"><a href="#">Privacy Policy</a><a href="#">Terms of Use</a></div>
        </div>
      </div>
    </footer>

  </div><!-- /page-wrapper -->

  <script src="nav-loader.js" defer></script>
  <script>
    // ── CURSOR ──
    var dot  = document.getElementById('cursorDot');
    var ring = document.getElementById('cursorRing');
    var mx = 0, my = 0, rx = 0, ry = 0;
    document.addEventListener('mousemove', function(e) {
      mx = e.clientX; my = e.clientY;
      dot.style.left = mx + 'px'; dot.style.top = my + 'px';
    });
    (function animRing() {
      rx += (mx - rx) * 0.12; ry += (my - ry) * 0.12;
      ring.style.left = rx + 'px'; ring.style.top = ry + 'px';
      requestAnimationFrame(animRing);
    })();
    document.querySelectorAll('a, button, .value-card, .prog-card, .mv-card, .partner-item, .bg-highlight').forEach(function(el) {
      el.addEventListener('mouseenter', function() { ring.classList.add('hovered'); dot.style.background = 'var(--creo-amber)'; dot.style.boxShadow = 'var(--glow-orange)'; });
      el.addEventListener('mouseleave', function() { ring.classList.remove('hovered'); dot.style.background = 'var(--prc-violet)'; dot.style.boxShadow = 'var(--glow-primary)'; });
    });

    // ── SCROLL REVEAL ──
    var revEls = document.querySelectorAll('.reveal, .reveal-left');
    var ro = new IntersectionObserver(function(entries) {
      entries.forEach(function(e) {
        if (e.isIntersecting) { e.target.classList.add('visible'); ro.unobserve(e.target); }
      });
    }, { threshold: 0.08, rootMargin: '0px 0px -28px 0px' });
    revEls.forEach(function(el) { ro.observe(el); });

    // ── PAGE SIDEBAR ──
    (function() {
      var sections = ['who','mission','values','background','programs','partners','contact-info','references'];
      var items = document.querySelectorAll('.psb-item');
      items.forEach(function(item) {
        item.addEventListener('click', function() {
          var target = document.getElementById(item.dataset.target);
          if (target) {
            var offset = parseInt(getComputedStyle(document.documentElement).getPropertyValue('--nav-height')) || 72;
            window.scrollTo({ top: target.getBoundingClientRect().top + window.scrollY - offset - 20, behavior: 'smooth' });
          }
        });
      });
      var sectionEls = sections.map(function(id) { return document.getElementById(id); });
      var sidebarObs = new IntersectionObserver(function(entries) {
        entries.forEach(function(entry) {
          if (entry.isIntersecting) {
            var id = entry.target.id;
            items.forEach(function(item) {
              item.classList.toggle('active', item.dataset.target === id);
            });
          }
        });
      }, { rootMargin: '-30% 0px -60% 0px', threshold: 0 });
      sectionEls.forEach(function(el) { if (el) sidebarObs.observe(el); });
    })();
  </script>

</body>
</html>
