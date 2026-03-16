<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <meta name="theme-color" content="#8B7EFF" />
  <meta name="description" content="Philippine Robotics Cup — The premier national robotics competition for Filipino students." />
  <title>Philippine Robotics Cup 2026</title>
  <link rel="icon" type="image/png" href="assets/favicon.png" />
  <link rel="shortcut icon" href="assets/favicon.png" />
  <link rel="apple-touch-icon" href="assets/favicon.png" />
  <link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@400;600;700;800;900&family=Exo+2:wght@300;400;500;600;700&display=swap" rel="stylesheet"/>
  <link rel='stylesheet' href='https://cdn-uicons.flaticon.com/uicons-regular-rounded/css/uicons-regular-rounded.css'>
  <link rel='stylesheet' href='https://cdn-uicons.flaticon.com/uicons-solid-rounded/css/uicons-solid-rounded.css'>
  <link rel='stylesheet' href='https://cdn-uicons.flaticon.com/uicons-brands/css/uicons-brands.css'>
  <style>
    /* ===== SPA PAGE SWITCHER ===== */
    .page { display: none; }
    .page.active { display: block; }
    #main-nav.solid-nav {
      background: rgba(3,2,13,0.94) !important;
      backdrop-filter: blur(20px) !important;
      border-bottom: 1px solid var(--border-neon) !important;
      box-shadow: 0 0 30px rgba(139,126,255,0.10) !important;
    }


    :root {
      --prc-violet:   #8B7EFF;
      --prc-ice:      #C4EEFF;
      --creo-purple:  #7733FF;
      --creo-amber:   #FFA030;
      --creo-volt:    #FFE930;
      --creo-sky:     #44D9FF;
      --neon-cyan:    var(--prc-ice);
      --neon-magenta: #CC55FF;
      --neon-volt:    var(--creo-volt);
      --neon-orange:  var(--creo-amber);
      --neon-primary: var(--prc-violet);
      --neon-sky:     var(--creo-sky);
      --bg-void:      #03020D;
      --bg-deep:      #06051A;
      --border-neon:  rgba(139,126,255,0.22);
      --border-hot:   rgba(139,126,255,0.55);
      --glow-cyan:    0 0 18px rgba(196,238,255,0.55), 0 0 55px rgba(196,238,255,0.18);
      --glow-magenta: 0 0 18px rgba(204,85,255,0.55),  0 0 55px rgba(204,85,255,0.18);
      --glow-volt:    0 0 18px rgba(255,233,48,0.60),  0 0 55px rgba(255,233,48,0.20);
      --glow-orange:  0 0 18px rgba(255,160,48,0.55),  0 0 55px rgba(255,160,48,0.18);
      --glow-primary: 0 0 18px rgba(139,126,255,0.60), 0 0 55px rgba(139,126,255,0.20);
      --glow-sky:     0 0 18px rgba(68,217,255,0.55),  0 0 55px rgba(68,217,255,0.18);
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

    .cursor-dot { position: fixed; width: 8px; height: 8px; border-radius: 50%; background: var(--neon-primary); pointer-events: none; z-index: 99999; transform: translate(-50%,-50%); box-shadow: var(--glow-primary); transition: transform 0.1s, background 0.2s; }
    .cursor-ring { position: fixed; width: 36px; height: 36px; border-radius: 50%; border: 1px solid rgba(139,126,255,0.65); pointer-events: none; z-index: 99998; transform: translate(-50%,-50%); transition: width 0.25s, height 0.25s, border-color 0.25s, transform 0.08s; }
    .cursor-ring.hovered { width: 56px; height: 56px; border-color: var(--creo-amber); border-width: 1.5px; }

    body::after { content: ''; position: fixed; inset: 0; z-index: 9998; pointer-events: none; background: repeating-linear-gradient(to bottom, transparent, transparent 2px, rgba(0,0,0,0.04) 2px, rgba(0,0,0,0.04) 4px); }

    .hex-grid { position: fixed; inset: 0; z-index: 0; pointer-events: none; background-image: linear-gradient(rgba(139,126,255,0.04) 1px, transparent 1px), linear-gradient(90deg, rgba(139,126,255,0.04) 1px, transparent 1px); background-size: 50px 50px; }
    .hex-grid::before { content: ''; position: absolute; inset: 0; background: radial-gradient(ellipse 80% 60% at 50% 0%, rgba(119,51,255,0.14) 0%, transparent 70%), radial-gradient(ellipse 60% 50% at 100% 100%, rgba(204,85,255,0.07) 0%, transparent 60%), radial-gradient(ellipse 50% 50% at 0% 80%, rgba(139,126,255,0.09) 0%, transparent 60%); }

    @keyframes glitch1 { 0%,94%,100% { clip-path: inset(0 0 100% 0); transform: translate(0); } 95% { clip-path: inset(20% 0 60% 0); transform: translate(-4px, 1px); } 97% { clip-path: inset(60% 0 10% 0); transform: translate(4px, -1px); } 99% { clip-path: inset(40% 0 40% 0); transform: translate(-2px, 2px); } }
    @keyframes glitch2 { 0%,96%,100% { clip-path: inset(0 0 100% 0); transform: translate(0); } 97% { clip-path: inset(10% 0 80% 0); transform: translate(5px, -2px); } 99% { clip-path: inset(70% 0 5% 0); transform: translate(-5px, 1px); } }
    @keyframes flicker { 0%,100% { opacity:1; } 92% { opacity:1; } 93% { opacity:0.4; } 94% { opacity:1; } 96% { opacity:0.7; } 97% { opacity:1; } }
    @keyframes scanDown { from { transform: translateY(-100%); } to { transform: translateY(100vh); } }
    @keyframes neonPulse { 0%,100% { opacity:1; } 50% { opacity:0.7; } }
    @keyframes fadeInUp { from { opacity:0; transform:translateY(28px); } to { opacity:1; transform:translateY(0); } }
    @keyframes fadeIn { from { opacity:0; } to { opacity:1; } }
    @keyframes videoGlowPulse { 0%,100% { box-shadow: 0 0 30px rgba(139,126,255,0.30), 0 0 80px rgba(119,51,255,0.12); } 50% { box-shadow: 0 0 55px rgba(139,126,255,0.55), 0 0 120px rgba(119,51,255,0.22); } }
    @keyframes cornerBlink { 0%,100% { opacity:1; } 50% { opacity:0.35; } }
    @keyframes bounce { 0%,100% { transform: translateX(-50%) translateY(0); } 50% { transform: translateX(-50%) translateY(10px); } }

    .page-wrapper { position: relative; z-index: 1; }

    /* NAV */
    #main-nav { position: fixed; top: 0; left: 0; right: 0; height: var(--nav-height); z-index: 1000; background: rgba(3,2,13,0.94); backdrop-filter: blur(20px); border-bottom: 1px solid var(--border-neon); box-shadow: 0 0 30px rgba(139,126,255,0.10); }
    #main-nav.scrolled { background: rgba(3,2,13,0.94); backdrop-filter: blur(20px); border-bottom: 1px solid var(--border-neon); box-shadow: 0 0 30px rgba(139,126,255,0.10); }
    .nav-inner { max-width: 1340px; margin: 0 auto; height: 100%; padding: 0 36px; display: flex; align-items: center; justify-content: space-between; gap: 16px; }
    .nav-logo { display: flex; align-items: center; gap: 12px; flex-shrink: 0; }
    .nav-logo img { height: 38px; width: auto; transition: filter 0.3s; }
    .nav-logo:hover img { filter: drop-shadow(0 0 14px rgba(139,126,255,0.75)); }
    .nav-brand { font-family: var(--font-hud); font-weight: 700; font-size: 0.72rem; letter-spacing: 0.06em; line-height: 1.3; color: var(--prc-violet); text-shadow: 0 0 12px rgba(139,126,255,0.65); }
    .nav-brand span { color: var(--text-soft); display: block; font-size: 0.58rem; font-weight: 400; letter-spacing: 0.10em; text-transform: uppercase; margin-top: 1px; }
    .nav-links { display: flex; align-items: center; gap: 2px; }
    .nav-links a { font-family: var(--font-hud); font-size: 0.65rem; font-weight: 600; color: var(--text-mid); padding: 8px 14px; letter-spacing: 0.08em; text-transform: uppercase; border-radius: 4px; transition: all 0.2s; position: relative; white-space: nowrap; }
    .nav-links a:hover { color: var(--prc-violet); text-shadow: 0 0 12px rgba(139,126,255,0.85); }
    .nav-cta { background: transparent !important; border: 1px solid var(--prc-violet) !important; color: var(--prc-violet) !important; padding: 8px 20px !important; border-radius: 3px !important; box-shadow: 0 0 15px rgba(139,126,255,0.28), inset 0 0 15px rgba(139,126,255,0.06) !important; transition: all 0.25s !important; margin-left: 8px; clip-path: polygon(6px 0%, 100% 0%, calc(100% - 6px) 100%, 0% 100%); }
    .nav-cta:hover { background: rgba(139,126,255,0.12) !important; box-shadow: 0 0 30px rgba(139,126,255,0.52), inset 0 0 20px rgba(139,126,255,0.10) !important; color: #fff !important; }
    .nav-hamburger { display: none; flex-direction: column; justify-content: center; align-items: center; gap: 5px; width: 44px; height: 44px; padding: 0; cursor: none; background: rgba(139,126,255,0.06); border: 1px solid var(--border-neon); border-radius: 4px; flex-shrink: 0; z-index: 1002; -webkit-tap-highlight-color: transparent; touch-action: manipulation; transition: all 0.2s; }
    .nav-hamburger:hover { background: rgba(139,126,255,0.14); box-shadow: 0 0 14px rgba(139,126,255,0.28); }
    .nav-hamburger span { width: 20px; height: 1.5px; background: var(--prc-violet); border-radius: 2px; transition: transform 0.28s, opacity 0.28s; display: block; pointer-events: none; }
    .nav-hamburger.open span:nth-child(1) { transform: rotate(45deg) translate(5px,5px); }
    .nav-hamburger.open span:nth-child(2) { opacity: 0; }
    .nav-hamburger.open span:nth-child(3) { transform: rotate(-45deg) translate(5px,-5px); }
    .nav-mobile { display: none; position: fixed; top: var(--nav-height); left: 0; right: 0; background: rgba(3,2,13,0.98); backdrop-filter: blur(20px); border-bottom: 1px solid var(--border-neon); padding: 12px 18px 24px; z-index: 1000; flex-direction: column; gap: 2px; box-shadow: 0 20px 60px rgba(139,126,255,0.09); }
    .nav-mobile.open { display: flex; }
    .nav-mobile a { font-family: var(--font-hud); font-size: 0.70rem; font-weight: 600; color: var(--text-mid); padding: 13px 14px; border-radius: 3px; letter-spacing: 0.08em; text-transform: uppercase; transition: all 0.2s; display: flex; align-items: center; gap: 12px; }
    .nav-mobile a i { font-size: 1rem; color: var(--prc-violet); }
    .nav-mobile a:hover { color: var(--prc-violet); background: rgba(139,126,255,0.07); text-shadow: 0 0 10px rgba(139,126,255,0.55); }
    .nav-mobile .nav-cta { border: 1px solid var(--prc-violet) !important; color: var(--prc-violet) !important; margin-top: 10px; justify-content: center; border-radius: 3px !important; clip-path: none !important; }

    /* HERO */
    #hero { position: relative; width: 100%; min-height: 100vh; min-height: 100dvh; display: flex; align-items: center; justify-content: center; overflow: hidden; padding-top: var(--nav-height); }
    .hero-bg { position: absolute; inset: 0; background-image: url('assets/hero-background2.jpg'); background-size: cover; background-position: center; filter: brightness(0.20) saturate(0.35); }
    .hero-overlay { position: absolute; inset: 0; background: radial-gradient(ellipse 65% 70% at 25% 50%, rgba(119,51,255,0.18) 0%, transparent 70%), radial-gradient(ellipse 50% 50% at 80% 50%, rgba(68,217,255,0.07) 0%, transparent 60%), radial-gradient(ellipse 40% 40% at 10% 85%, rgba(139,126,255,0.14) 0%, transparent 60%), linear-gradient(to bottom, rgba(3,2,13,0.28) 0%, transparent 35%, rgba(3,2,13,0.62) 100%); }
    .hero-scan { position: absolute; inset: 0; pointer-events: none; overflow: hidden; }
    .hero-scan::after { content: ''; position: absolute; left: 0; right: 0; height: 2px; background: linear-gradient(90deg, transparent, var(--prc-violet), var(--prc-ice), transparent); animation: scanDown 6s linear infinite; box-shadow: 0 0 18px rgba(139,126,255,0.55); }
    .hero-content { position: relative; z-index: 3; width: 100%; max-width: 1340px; padding: 36px 36px 64px; display: grid; grid-template-columns: 5fr 7fr; gap: 56px; align-items: center; }
    .hero-left { display: flex; flex-direction: column; align-items: flex-start; }
    .hero-sys-label { display: inline-flex; align-items: center; gap: 10px; font-family: var(--font-hud); font-size: 0.60rem; font-weight: 600; letter-spacing: 0.18em; text-transform: uppercase; color: var(--prc-ice); margin-bottom: 22px; animation: fadeIn 1s ease 0.1s both; }
    .sys-blink { width: 6px; height: 6px; background: var(--prc-ice); border-radius: 50%; box-shadow: var(--glow-cyan); animation: neonPulse 1.2s ease-in-out infinite; }
    .hero-title { font-family: var(--font-hud); text-align: left; line-height: 1; margin-bottom: 20px; position: relative; animation: fadeInUp 1s ease 0.2s both; }
    .ht-philippine { display: block; font-size: clamp(0.72rem, 1.6vw, 1.05rem); font-weight: 700; letter-spacing: 0.30em; text-transform: uppercase; color: var(--creo-volt); text-shadow: 0 0 18px rgba(255,233,48,0.85), 0 0 55px rgba(255,233,48,0.28); animation: flicker 8s ease infinite; margin-bottom: 4px; }
    .ht-robotics { display: block; font-size: clamp(2.6rem, 6.2vw, 5.2rem); font-weight: 900; letter-spacing: -0.01em; text-transform: uppercase; line-height: 0.88; color: #fff; text-shadow: 0 0 40px rgba(139,126,255,0.45), 0 0 80px rgba(139,126,255,0.15), 0 4px 0 rgba(0,0,0,0.90); position: relative; }
    .ht-robotics::before, .ht-robotics::after { content: attr(data-text); position: absolute; inset: 0; color: #fff; font-size: inherit; font-weight: inherit; letter-spacing: inherit; }
    .ht-robotics::before { color: var(--prc-ice); animation: glitch1 7s ease-in-out infinite; mix-blend-mode: screen; }
    .ht-robotics::after { color: var(--creo-purple); animation: glitch2 7s ease-in-out infinite 0.5s; mix-blend-mode: screen; }
    .ht-cup { display: block; font-size: clamp(2.6rem, 6.2vw, 5.2rem); font-weight: 900; letter-spacing: 0.14em; text-transform: uppercase; line-height: 0.88; background: linear-gradient(90deg, var(--prc-violet) 0%, var(--creo-sky) 100%); -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text; filter: drop-shadow(0 0 18px rgba(139,126,255,0.55)); }
    .ht-divider { display: flex; align-items: center; gap: 12px; margin: 10px 0 8px; }
    .ht-divider-line { flex: 1; max-width: 80px; height: 1px; background: linear-gradient(90deg, transparent, var(--prc-ice)); }
    .ht-divider-line.right { background: linear-gradient(90deg, var(--prc-ice), transparent); }
    .ht-divider-diamond { width: 8px; height: 8px; background: var(--prc-ice); transform: rotate(45deg); box-shadow: var(--glow-cyan); }
    .ht-year { display: block; font-size: clamp(1.5rem, 4vw, 3.2rem); font-weight: 900; letter-spacing: 0.20em; color: var(--creo-volt); text-shadow: 0 0 28px rgba(255,233,48,0.80), 0 0 80px rgba(255,233,48,0.25); line-height: 1; animation: flicker 12s ease-in-out infinite 2s; }
    .hero-tagline { font-family: var(--font-body); font-size: clamp(0.84rem, 1.4vw, 0.96rem); color: var(--text-mid); max-width: 460px; margin: 0 0 26px; line-height: 1.78; animation: fadeInUp 1s ease 0.35s both; }
    .hero-actions { display: flex; gap: 12px; flex-wrap: wrap; margin-bottom: 34px; animation: fadeInUp 1s ease 0.48s both; }
    .hero-countdown { animation: fadeInUp 1s ease 0.60s both; width: 100%; }
    .countdown-label { font-family: var(--font-hud); font-size: 0.54rem; letter-spacing: 0.18em; color: var(--text-soft); margin-bottom: 10px; text-transform: uppercase; display: flex; align-items: center; gap: 10px; }
    .countdown-label::after { content: ''; flex: 1; height: 1px; background: linear-gradient(90deg, rgba(139,126,255,0.48), transparent); }
    .countdown-grid { display: flex; gap: 8px; flex-wrap: wrap; }
    .countdown-item { background: rgba(139,126,255,0.05); border: 1px solid rgba(139,126,255,0.22); padding: 11px 15px; min-width: 68px; text-align: center; position: relative; overflow: hidden; clip-path: polygon(6px 0%, 100% 0%, calc(100% - 6px) 100%, 0% 100%); transition: border-color 0.3s, background 0.3s; }
    .countdown-item:hover { border-color: var(--prc-violet); background: rgba(139,126,255,0.10); box-shadow: 0 0 20px rgba(139,126,255,0.22); }
    .countdown-item::before { content: ''; position: absolute; top: 0; left: 0; right: 0; height: 1px; background: linear-gradient(90deg, transparent, var(--prc-violet), transparent); }
    .countdown-num { font-family: var(--font-hud); font-size: 1.75rem; font-weight: 700; line-height: 1; color: var(--prc-ice); display: block; text-shadow: 0 0 20px rgba(139,126,255,0.7); }
    .countdown-unit { font-family: var(--font-hud); font-size: 0.46rem; text-transform: uppercase; letter-spacing: 0.14em; color: var(--text-soft); margin-top: 4px; display: block; }
    .hero-event-date { display: inline-flex; align-items: center; gap: 9px; margin-top: 13px; font-family: var(--font-hud); font-size: 0.57rem; letter-spacing: 0.10em; text-transform: uppercase; color: var(--text-mid); border: 1px solid rgba(255,160,48,0.28); padding: 7px 15px; clip-path: polygon(6px 0%, 100% 0%, calc(100% - 6px) 100%, 0% 100%); background: rgba(255,160,48,0.06); }
    .hero-event-date i { color: var(--creo-amber); }
    .hero-right { position: relative; animation: fadeInUp 1s ease 0.30s both; }
    .hero-video-frame { position: relative; padding: 2px; background: linear-gradient(135deg, rgba(139,126,255,0.85), rgba(68,217,255,0.55), rgba(119,51,255,0.75)); animation: videoGlowPulse 3s ease-in-out infinite; clip-path: polygon(14px 0%, 100% 0%, calc(100% - 14px) 100%, 0% 100%); }
    .hero-video-frame::before, .hero-video-frame::after { content: ''; position: absolute; width: 22px; height: 22px; border-color: var(--prc-ice); border-style: solid; z-index: 5; pointer-events: none; animation: cornerBlink 2.5s ease-in-out infinite; }
    .hero-video-frame::before { top: -1px; left: -1px; border-width: 2px 0 0 2px; }
    .hero-video-frame::after { bottom: -1px; right: -1px; border-width: 0 2px 2px 0; }
    .hero-video-inner-wrap { position: relative; overflow: hidden; background: #000; clip-path: polygon(13px 0%, 100% 0%, calc(100% - 13px) 100%, 0% 100%); }
    .hero-video-inner-wrap::before, .hero-video-inner-wrap::after { content: ''; position: absolute; width: 20px; height: 20px; border-color: var(--creo-amber); border-style: solid; z-index: 5; pointer-events: none; animation: cornerBlink 2.5s ease-in-out infinite 1.25s; }
    .hero-video-inner-wrap::before { top: 9px; right: 9px; border-width: 2px 2px 0 0; }
    .hero-video-inner-wrap::after { bottom: 9px; left: 9px; border-width: 0 0 2px 2px; }
    .hero-video-el { width: 100%; display: block; aspect-ratio: 16/9; object-fit: cover; filter: brightness(0.92) saturate(1.10) contrast(1.04); transition: filter 0.4s; }
    .hero-video-frame:hover .hero-video-el { filter: brightness(1.0) saturate(1.2) contrast(1.06); }
    .hero-video-hud { position: absolute; inset: 0; z-index: 4; pointer-events: none; background: linear-gradient(to bottom, rgba(3,2,13,0.38) 0%, transparent 22%, transparent 72%, rgba(3,2,13,0.58) 100%), linear-gradient(to right, rgba(3,2,13,0.18) 0%, transparent 14%, transparent 86%, rgba(3,2,13,0.18) 100%); }
    .video-hud-label { position: absolute; top: 13px; left: 17px; font-family: var(--font-hud); font-size: 0.50rem; font-weight: 700; letter-spacing: 0.16em; text-transform: uppercase; color: var(--prc-ice); opacity: 0.88; display: flex; align-items: center; gap: 7px; }
    .video-hud-label .rec-dot { width: 6px; height: 6px; background: #FF4444; border-radius: 50%; box-shadow: 0 0 8px rgba(255,68,68,0.80); animation: neonPulse 1s ease-in-out infinite; }
    .video-hud-bottom { position: absolute; bottom: 0; left: 0; right: 0; padding: 10px 17px; background: linear-gradient(to top, rgba(3,2,13,0.88), transparent); display: flex; align-items: center; justify-content: space-between; }
    .video-hud-title { font-family: var(--font-hud); font-size: 0.56rem; font-weight: 700; color: var(--text-high); letter-spacing: 0.08em; text-shadow: 0 0 10px rgba(139,126,255,0.55); }
    .video-hud-year { font-family: var(--font-hud); font-size: 0.52rem; color: var(--creo-volt); letter-spacing: 0.12em; text-shadow: 0 0 8px rgba(255,233,48,0.60); }
    .video-replay-badge { position: absolute; inset: 0; z-index: 6; display: none; }
    .video-replay-badge.show { opacity: 1; pointer-events: auto; }
    .replay-btn { display: flex; flex-direction: column; align-items: center; gap: 8px; font-family: var(--font-hud); font-size: 0.62rem; color: var(--prc-ice); letter-spacing: 0.14em; cursor: pointer; border: 1px solid rgba(139,126,255,0.55); padding: 14px 30px; background: rgba(139,126,255,0.08); clip-path: polygon(8px 0%, 100% 0%, calc(100% - 8px) 100%, 0% 100%); transition: all 0.25s; }
    .replay-btn:hover { background: rgba(139,126,255,0.20); box-shadow: 0 0 22px rgba(139,126,255,0.38); }
    .replay-btn i { font-size: 1.8rem; color: var(--prc-violet); }
    .hero-scroll { position: absolute; bottom: 20px; left: 50%; transform: translateX(-50%); display: flex; flex-direction: column; align-items: center; gap: 6px; animation: bounce 2.4s ease-in-out infinite; font-family: var(--font-hud); color: var(--text-dim); font-size: 0.52rem; letter-spacing: 0.16em; text-transform: uppercase; z-index: 4; }

    /* BUTTONS */
    .btn-neon-primary { display: inline-flex; align-items: center; gap: 10px; background: transparent; color: var(--prc-violet); padding: 12px 28px; font-family: var(--font-hud); font-size: 0.66rem; font-weight: 700; letter-spacing: 0.12em; text-transform: uppercase; border: 1px solid var(--prc-violet); clip-path: polygon(10px 0%, 100% 0%, calc(100% - 10px) 100%, 0% 100%); box-shadow: 0 0 18px rgba(139,126,255,0.32), inset 0 0 18px rgba(139,126,255,0.07); transition: all 0.25s; position: relative; overflow: hidden; }
    .btn-neon-primary::before { content: ''; position: absolute; inset: 0; background: linear-gradient(90deg, transparent, rgba(139,126,255,0.18), transparent); transform: translateX(-100%); transition: transform 0.5s; }
    .btn-neon-primary:hover { background: rgba(139,126,255,0.12); box-shadow: 0 0 38px rgba(139,126,255,0.60), inset 0 0 28px rgba(139,126,255,0.12); color: #fff; transform: translateY(-2px); }
    .btn-neon-primary:hover::before { transform: translateX(100%); }
    .btn-neon-secondary { display: inline-flex; align-items: center; gap: 10px; background: transparent; color: var(--creo-sky); padding: 12px 28px; font-family: var(--font-hud); font-size: 0.66rem; font-weight: 700; letter-spacing: 0.12em; text-transform: uppercase; border: 1px solid var(--creo-sky); clip-path: polygon(10px 0%, 100% 0%, calc(100% - 10px) 100%, 0% 100%); box-shadow: 0 0 18px rgba(68,217,255,0.28), inset 0 0 18px rgba(68,217,255,0.06); transition: all 0.25s; position: relative; overflow: hidden; }
    .btn-neon-secondary:hover { background: rgba(68,217,255,0.10); box-shadow: 0 0 38px rgba(68,217,255,0.55), inset 0 0 28px rgba(68,217,255,0.10); color: #fff; transform: translateY(-2px); }

    /* STATS STRIP */
    .stats-strip { border-top: 1px solid var(--border-neon); border-bottom: 1px solid var(--border-neon); padding: 48px 0; position: relative; overflow: hidden; background: rgba(139,126,255,0.02); }
    .stats-inner { max-width: 1340px; margin: 0 auto; padding: 0 36px; display: grid; grid-template-columns: repeat(4,1fr); }
    .stat-item { text-align: center; padding: 14px 20px; border-right: 1px solid var(--border-neon); }
    .stat-item:last-child { border-right: none; }
    .stat-num { font-family: var(--font-hud); font-size: 2.4rem; font-weight: 800; color: var(--prc-violet); display: block; line-height: 1; text-shadow: 0 0 20px rgba(139,126,255,0.70); }
    .stat-label { font-family: var(--font-hud); font-size: 0.58rem; color: var(--text-soft); margin-top: 7px; text-transform: uppercase; letter-spacing: 0.10em; }
    .stat-icon { color: rgba(139,126,255,0.60); font-size: 1.1rem; margin-bottom: 8px; }

    /* SECTION BASE */
    section { padding: var(--section-pad); }
    .section-inner { max-width: 1340px; margin: 0 auto; padding: 0 36px; }
    .section-eyebrow { display: inline-flex; align-items: center; gap: 8px; font-family: var(--font-hud); color: var(--prc-ice); font-size: 0.60rem; font-weight: 700; letter-spacing: 0.20em; text-transform: uppercase; margin-bottom: 16px; }
    .section-eyebrow::before { content: '//'; color: rgba(139,126,255,0.40); font-size: 0.70rem; }
    .section-title { font-family: var(--font-hud); font-size: clamp(1.8rem, 3.8vw, 2.8rem); font-weight: 800; letter-spacing: -0.01em; line-height: 1.08; margin-bottom: 14px; color: #fff; text-shadow: 0 0 40px rgba(139,126,255,0.14); }
    .section-title .accent { color: var(--prc-violet); text-shadow: 0 0 18px rgba(139,126,255,0.65); }
    .section-desc { font-size: 1rem; color: var(--text-mid); max-width: 500px; line-height: 1.78; }

    /* ABOUT */
    #about .section-inner { display: grid; grid-template-columns: 1fr 1fr; gap: 80px; align-items: center; }
    .about-feature-list { display: flex; flex-direction: column; gap: 12px; margin-top: 36px; }
    .about-feature { display: flex; align-items: flex-start; gap: 16px; padding: 16px 20px; background: rgba(139,126,255,0.04); border: 1px solid rgba(139,126,255,0.12); border-left: 2px solid var(--prc-violet); transition: all 0.3s; }
    .about-feature:hover { background: rgba(139,126,255,0.09); border-color: rgba(139,126,255,0.30); border-left-color: var(--creo-volt); box-shadow: 0 0 22px rgba(139,126,255,0.12); transform: translateX(4px); }
    .about-feature-icon { width: 40px; height: 40px; flex-shrink: 0; background: rgba(139,126,255,0.10); border: 1px solid rgba(139,126,255,0.24); display: flex; align-items: center; justify-content: center; color: var(--prc-violet); font-size: 1rem; }
    .about-feature-text h4 { font-family: var(--font-hud); font-size: 0.72rem; font-weight: 700; letter-spacing: 0.06em; color: var(--text-high); margin-bottom: 4px; }
    .about-feature-text p { font-size: 0.875rem; color: var(--text-mid); line-height: 1.60; }
    .about-card { background: rgba(139,126,255,0.04); border: 1px solid var(--border-neon); position: relative; overflow: hidden; box-shadow: 0 0 40px rgba(139,126,255,0.08), inset 0 0 40px rgba(139,126,255,0.02); }
    .about-card::before { content: ''; position: absolute; top: 0; left: 0; right: 0; height: 1px; background: linear-gradient(90deg, transparent, var(--prc-violet), transparent); }
    .about-card-header { background: rgba(139,126,255,0.07); padding: 26px 30px; display: flex; align-items: center; gap: 16px; border-bottom: 1px solid var(--border-neon); }
    .about-card-header img { height: 44px; width: auto; }
    .about-card-header-text h3 { font-family: var(--font-hud); font-size: 0.80rem; font-weight: 700; letter-spacing: 0.06em; color: var(--prc-violet); text-shadow: 0 0 10px rgba(139,126,255,0.55); }
    .about-card-header-text p { font-size: 0.80rem; color: var(--text-soft); margin-top: 3px; }
    .about-card-body { padding: 26px 30px; }
    .about-card-body p { font-size: 0.888rem; color: var(--text-mid); line-height: 1.82; margin-bottom: 22px; }
    .partner-strip { padding-top: 18px; border-top: 1px solid rgba(139,126,255,0.14); }
    .partner-strip-label { font-family: var(--font-hud); font-size: 0.55rem; text-transform: uppercase; letter-spacing: 0.14em; color: var(--text-soft); margin-bottom: 12px; }
    .partner-logos { display: flex; gap: 10px; align-items: center; flex-wrap: wrap; }
    .partner-badge { background: rgba(139,126,255,0.08); border: 1px solid rgba(139,126,255,0.24); padding: 4px 12px; font-family: var(--font-hud); font-size: 0.60rem; font-weight: 700; color: var(--prc-violet); letter-spacing: 0.08em; }
    .partner-logos img { height: 20px; width: auto; }
    .about-visual { position: relative; }
    .floating-badge { position: absolute; bottom: -16px; right: -16px; background: transparent; border: 1px solid var(--creo-volt); color: var(--creo-volt); padding: 16px 24px; font-family: var(--font-hud); font-weight: 700; font-size: 0.72rem; text-shadow: 0 0 10px rgba(255,233,48,0.6); box-shadow: 0 0 30px rgba(255,233,48,0.25), inset 0 0 20px rgba(255,233,48,0.06); clip-path: polygon(8px 0%, 100% 0%, calc(100% - 8px) 100%, 0% 100%); letter-spacing: 0.06em; }
    .floating-badge span { display: block; font-size: 1.2rem; font-weight: 900; margin-bottom: 2px; }

    /* CATEGORIES */
    #categories { background: rgba(0,0,8,0.5); }
    .categories-header { display: flex; align-items: flex-end; justify-content: space-between; margin-bottom: 52px; flex-wrap: wrap; gap: 24px; }
    .categories-grid { display: grid; grid-template-columns: repeat(3,1fr); gap: 20px; }
    .cat-card { background: rgba(0,0,8,0.6); border: 1px solid rgba(255,255,255,0.07); overflow: hidden; position: relative; display: flex; flex-direction: column; transition: all 0.35s cubic-bezier(0.23,1,0.32,1); cursor: none; }
    .cat-card::before { content: ''; position: absolute; top: 0; left: 0; right: 0; height: 2px; opacity: 0; transition: opacity 0.35s; }
    .cat-card.prc::before   { background: linear-gradient(90deg, transparent, var(--prc-violet), transparent); }
    .cat-card.makex::before { background: linear-gradient(90deg, transparent, var(--creo-sky), transparent); }
    .cat-card.drone::before { background: linear-gradient(90deg, transparent, var(--creo-amber), transparent); }
    .cat-card::after { content: ''; position: absolute; inset: 0; opacity: 0; transition: opacity 0.35s; pointer-events: none; }
    .cat-card.prc::after   { box-shadow: inset 0 0 40px rgba(139,126,255,0.06); }
    .cat-card.makex::after { box-shadow: inset 0 0 40px rgba(204,85,255,0.06); }
    .cat-card.drone::after { box-shadow: inset 0 0 40px rgba(255,160,48,0.06); }
    .cat-card:hover::before, .cat-card:hover::after { opacity: 1; }
    .cat-card.prc:hover   { border-color: rgba(139,126,255,0.35); box-shadow: 0 0 40px rgba(139,126,255,0.12); transform: translateY(-6px); }
    .cat-card.makex:hover { border-color: rgba(204,85,255,0.35); box-shadow: 0 0 40px rgba(204,85,255,0.12); transform: translateY(-6px); }
    .cat-card.drone:hover { border-color: rgba(255,160,48,0.35); box-shadow: 0 0 40px rgba(255,160,48,0.12); transform: translateY(-6px); }
    .cat-img-wrap { position: relative; width: 100%; height: 188px; overflow: hidden; }
    .cat-img-wrap img { width: 100%; height: 100%; object-fit: cover; transition: transform 0.50s; filter: brightness(0.5) saturate(0.6); }
    .cat-card:hover .cat-img-wrap img { transform: scale(1.07); filter: brightness(0.6) saturate(0.8); }
    .cat-img-overlay { position: absolute; inset: 0; background: linear-gradient(to bottom, transparent 30%, var(--bg-void) 100%); }
    .cat-img-badge { position: absolute; bottom: 12px; left: 12px; font-family: var(--font-hud); font-size: 0.58rem; font-weight: 700; letter-spacing: 0.10em; text-transform: uppercase; padding: 4px 12px; background: rgba(3,3,8,0.80); border: 1px solid; clip-path: polygon(4px 0%, 100% 0%, calc(100% - 4px) 100%, 0% 100%); }
    .cat-card.prc   .cat-img-badge { color: var(--prc-violet);  border-color: rgba(139,126,255,0.40); }
    .cat-card.makex .cat-img-badge { color: var(--creo-sky);    border-color: rgba(68,217,255,0.40); }
    .cat-card.drone .cat-img-badge { color: var(--creo-amber);  border-color: rgba(255,160,48,0.40); }
    .cat-body { padding: 22px 26px 26px; display: flex; flex-direction: column; flex: 1; }
    .cat-logo-row { display: flex; align-items: center; gap: 10px; margin-bottom: 12px; }
    .cat-logo { height: 30px; width: auto; }
    .cat-title { font-family: var(--font-hud); font-size: 1rem; font-weight: 700; letter-spacing: 0.04em; margin-bottom: 9px; color: var(--text-high); }
    .cat-card.prc   .cat-title { text-shadow: 0 0 14px rgba(139,126,255,0.45); }
    .cat-card.makex .cat-title { text-shadow: 0 0 14px rgba(68,217,255,0.45); }
    .cat-card.drone .cat-title { text-shadow: 0 0 14px rgba(255,160,48,0.45); }
    .cat-desc { font-size: 0.862rem; color: var(--text-mid); line-height: 1.68; margin-bottom: 16px; flex: 1; }
    .cat-subcats { display: flex; flex-wrap: wrap; gap: 5px; margin-bottom: 18px; }
    .cat-tag { border: 1px solid; padding: 3px 10px; font-family: var(--font-hud); font-size: 0.56rem; font-weight: 600; letter-spacing: 0.06em; text-transform: uppercase; }
    .cat-card.prc   .cat-tag { color: var(--prc-violet);  border-color: rgba(139,126,255,0.20); background: rgba(139,126,255,0.04); }
    .cat-card.makex .cat-tag { color: var(--creo-sky);    border-color: rgba(68,217,255,0.20);  background: rgba(68,217,255,0.04); }
    .cat-card.drone .cat-tag { color: var(--creo-amber);  border-color: rgba(255,160,48,0.20);  background: rgba(255,160,48,0.04); }
    .cat-link { display: inline-flex; align-items: center; gap: 7px; margin-top: auto; font-family: var(--font-hud); font-size: 0.62rem; font-weight: 700; letter-spacing: 0.10em; text-transform: uppercase; padding: 9px 18px; border: 1px solid; align-self: flex-start; clip-path: polygon(6px 0%, 100% 0%, calc(100% - 6px) 100%, 0% 100%); transition: all 0.25s; }
    .cat-card.prc   .cat-link { color: var(--prc-violet);  border-color: rgba(139,126,255,0.34); }
    .cat-card.makex .cat-link { color: var(--creo-sky);    border-color: rgba(68,217,255,0.34); }
    .cat-card.drone .cat-link { color: var(--creo-amber);  border-color: rgba(255,160,48,0.34); }
    .cat-card.prc:hover   .cat-link { background: rgba(139,126,255,0.10); box-shadow: 0 0 14px rgba(139,126,255,0.24); }
    .cat-card.makex:hover .cat-link { background: rgba(68,217,255,0.10);  box-shadow: 0 0 14px rgba(68,217,255,0.24); }
    .cat-card.drone:hover .cat-link { background: rgba(255,160,48,0.10);  box-shadow: 0 0 14px rgba(255,160,48,0.24); }

    /* VIDEO */
    #video { padding: var(--section-pad); }
    .video-inner { max-width: 1340px; margin: 0 auto; padding: 0 36px; }
    .video-header { text-align: center; margin-bottom: 50px; }
    .video-header .section-eyebrow { display: inline-flex; }
    .video-header .section-desc { margin: 0 auto; }
    .video-outer-wrap { position: relative; max-width: 980px; margin: 0 auto; padding: 1px; background: linear-gradient(135deg, var(--prc-ice), var(--creo-purple), var(--creo-amber)); box-shadow: 0 0 60px rgba(139,126,255,0.20), 0 0 120px rgba(204,85,255,0.10); }
    .video-outer-wrap::before { content: ''; position: absolute; inset: -2px; background: linear-gradient(135deg, var(--prc-violet), var(--creo-sky), var(--creo-amber)); filter: blur(18px); opacity: 0.35; z-index: -1; }
    .video-inner-wrap { overflow: hidden; background: #000; }
    .video-ratio { position: relative; padding-bottom: 56.25%; }
    .video-ratio iframe { position: absolute; inset: 0; width: 100%; height: 100%; border: none; }

    /* HIGHLIGHTS */
    #highlights { background: rgba(0,0,8,0.5); }
    .highlights-header { display: flex; align-items: flex-end; justify-content: space-between; margin-bottom: 40px; flex-wrap: wrap; gap: 20px; }
    .highlights-grid { display: grid; grid-template-columns: 2fr 1fr 1fr; grid-template-rows: auto auto; gap: 12px; }
    .highlight-item { position: relative; background: rgba(0,0,8,0.8); border: 1px solid rgba(139,126,255,0.12); overflow: hidden; cursor: none; transition: border-color 0.35s, box-shadow 0.35s, transform 0.35s; }
    .highlight-item:nth-child(1) { grid-row: 1/3; }
    .highlight-img { width: 100%; height: 100%; object-fit: cover; display: block; transition: transform 0.45s; filter: brightness(0.55) saturate(0.5); }
    .highlight-item:nth-child(1) .highlight-img { min-height: 410px; }
    .highlight-item:nth-child(n+2) .highlight-img { height: 196px; }
    .highlight-item:hover { border-color: rgba(139,126,255,0.48); box-shadow: 0 0 28px rgba(139,126,255,0.18); transform: scale(1.015); }
    .highlight-item:hover .highlight-img { transform: scale(1.06); filter: brightness(0.70) saturate(0.8); }
    .highlight-overlay { position: absolute; inset: 0; background: linear-gradient(to top, rgba(3,3,8,0.95) 0%, rgba(3,3,8,0.30) 50%, transparent 100%); opacity: 0; transition: opacity 0.35s; display: flex; align-items: flex-end; padding: 18px; }
    .highlight-item:hover .highlight-overlay { opacity: 1; }
    .highlight-caption { font-family: var(--font-hud); font-size: 0.65rem; font-weight: 700; letter-spacing: 0.08em; text-transform: uppercase; color: var(--prc-violet); text-shadow: 0 0 10px rgba(139,126,255,0.70); transform: translateY(6px); transition: transform 0.3s; }
    .highlight-item:hover .highlight-caption { transform: translateY(0); }
    .highlight-num { position: absolute; top: 10px; right: 10px; z-index: 2; font-family: var(--font-hud); font-size: 0.58rem; font-weight: 700; color: var(--text-soft); letter-spacing: 0.05em; }
    .highlight-item::before, .highlight-item::after { content: ''; position: absolute; width: 16px; height: 16px; z-index: 3; border-color: var(--prc-ice); border-style: solid; opacity: 0; transition: opacity 0.3s; }
    .highlight-item::before { top: 8px; left: 8px; border-width: 1.5px 0 0 1.5px; }
    .highlight-item::after { bottom: 8px; right: 8px; border-width: 0 1.5px 1.5px 0; }
    .highlight-item:hover::before, .highlight-item:hover::after { opacity: 1; }

    /* ORGANIZERS */
    #organizers { padding: 90px 0; }
    .organizers-inner { max-width: 1340px; margin: 0 auto; padding: 0 36px; }
    .organizers-card { background: rgba(139,126,255,0.025); border: 1px solid var(--border-neon); padding: 60px 52px; text-align: center; position: relative; overflow: hidden; box-shadow: 0 0 60px rgba(139,126,255,0.07), inset 0 0 60px rgba(139,126,255,0.02); }
    .organizers-card::before { content: ''; position: absolute; top: 0; left: 0; right: 0; height: 1px; background: linear-gradient(90deg, transparent, var(--prc-violet), transparent); }
    .organizers-card::after { content: ''; position: absolute; bottom: 0; left: 0; right: 0; height: 1px; background: linear-gradient(90deg, transparent, rgba(139,126,255,0.42), transparent); }
    .organizers-heading { font-family: var(--font-hud); font-size: 1rem; font-weight: 700; color: var(--prc-violet); letter-spacing: 0.08em; text-shadow: 0 0 18px rgba(139,126,255,0.60); margin-bottom: 8px; }
    .organizers-sub { font-size: 0.900rem; color: var(--text-mid); margin-bottom: 46px; }
    .organizers-row { display: flex; align-items: stretch; justify-content: center; flex-wrap: wrap; }
    .org-item { display: flex; flex-direction: column; align-items: center; gap: 14px; padding: 22px 44px; border-right: 1px solid var(--border-neon); transition: all 0.25s; cursor: none; }
    .org-item:last-child { border-right: none; }
    .org-item:hover { background: rgba(139,126,255,0.06); box-shadow: 0 0 20px rgba(139,126,255,0.10); }
    .org-logo-img { height: 60px; width: auto; object-fit: contain; transition: transform 0.25s, filter 0.25s; }
    .org-item:hover .org-logo-img { transform: scale(1.06); filter: drop-shadow(0 0 8px rgba(139,126,255,0.55)); }
    .org-name { font-family: var(--font-hud); font-size: 0.65rem; font-weight: 600; color: var(--text-mid); letter-spacing: 0.05em; text-align: center; line-height: 1.4; }
    .org-role { font-size: 0.62rem; color: var(--prc-violet); font-weight: 500; font-family: var(--font-hud); opacity: 0.85; }

    /* CTA */
    #cta { padding: var(--section-pad); }
    .cta-card { max-width: 960px; margin: 0 auto; background: rgba(139,126,255,0.04); border: 1px solid var(--prc-violet); padding: 88px 64px; text-align: center; position: relative; overflow: hidden; box-shadow: 0 0 80px rgba(139,126,255,0.18), inset 0 0 80px rgba(139,126,255,0.03); }
    .cta-card::before { content: ''; position: absolute; top: 0; left: 0; right: 0; height: 2px; background: linear-gradient(90deg, transparent, var(--prc-ice), transparent); box-shadow: 0 0 20px rgba(139,126,255,0.8); }
    .cta-card::after { content: ''; position: absolute; bottom: 0; left: 0; right: 0; height: 2px; background: linear-gradient(90deg, transparent, var(--creo-purple), transparent); box-shadow: 0 0 20px rgba(204,85,255,0.8); }
    .cta-corner { position: absolute; width: 24px; height: 24px; border-color: var(--prc-violet); border-style: solid; }
    .cta-corner.tl { top: 12px; left: 12px; border-width: 1.5px 0 0 1.5px; }
    .cta-corner.tr { top: 12px; right: 12px; border-width: 1.5px 1.5px 0 0; }
    .cta-corner.bl { bottom: 12px; left: 12px; border-width: 0 0 1.5px 1.5px; }
    .cta-corner.br { bottom: 12px; right: 12px; border-width: 0 1.5px 1.5px 0; }
    .cta-bg-grid { position: absolute; inset: 0; pointer-events: none; background-image: linear-gradient(rgba(139,126,255,0.05) 1px, transparent 1px), linear-gradient(90deg, rgba(139,126,255,0.05) 1px, transparent 1px); background-size: 40px 40px; mask-image: radial-gradient(ellipse 70% 70% at 50% 50%, black 0%, transparent 100%); }
    .cta-tag { display: inline-flex; align-items: center; gap: 8px; border: 1px solid rgba(255,233,48,0.38); padding: 6px 18px; font-family: var(--font-hud); font-size: 0.60rem; font-weight: 700; letter-spacing: 0.14em; text-transform: uppercase; color: var(--creo-volt); margin-bottom: 22px; position: relative; z-index: 1; clip-path: polygon(6px 0%, 100% 0%, calc(100% - 6px) 100%, 0% 100%); text-shadow: 0 0 10px rgba(255,233,48,0.60); background: rgba(255,233,48,0.05); }
    .cta-title { font-family: var(--font-hud); font-size: clamp(1.8rem, 3.8vw, 2.8rem); font-weight: 800; letter-spacing: -0.01em; line-height: 1.08; margin-bottom: 16px; position: relative; z-index: 1; color: #fff; }
    .cta-title .accent { color: var(--prc-violet); text-shadow: 0 0 18px rgba(139,126,255,0.70); }
    .cta-desc { font-size: 1rem; color: var(--text-mid); max-width: 460px; margin: 0 auto 38px; line-height: 1.78; position: relative; z-index: 1; }
    .cta-actions { display: flex; gap: 14px; justify-content: center; flex-wrap: wrap; position: relative; z-index: 1; }

    /* FOOTER */
    footer { background: rgba(0,0,6,0.95); border-top: 1px solid var(--border-neon); padding: 70px 0 32px; }
    .footer-inner { max-width: 1340px; margin: 0 auto; padding: 0 36px; }
    .footer-top { display: grid; grid-template-columns: 2fr 1fr 1fr 1fr; gap: 52px; margin-bottom: 52px; }
    .footer-brand img { height: 36px; width: auto; margin-bottom: 16px; }
    .footer-brand p { font-size: 0.875rem; color: var(--text-mid); line-height: 1.80; margin-bottom: 22px; }
    .footer-contact-list { display: flex; flex-direction: column; gap: 11px; margin-bottom: 24px; }
    .footer-contact-item { display: flex; align-items: center; gap: 11px; font-size: 0.875rem; color: var(--text-mid); }
    .footer-contact-item i { color: var(--prc-violet); font-size: 0.95rem; flex-shrink: 0; }
    .footer-contact-item a { color: var(--text-mid); transition: color 0.2s; }
    .footer-contact-item a:hover { color: var(--prc-violet); text-shadow: 0 0 8px rgba(139,126,255,0.60); }
    .social-links { display: flex; gap: 8px; }
    .social-link { width: 40px; height: 40px; background: rgba(139,126,255,0.04); border: 1px solid rgba(139,126,255,0.18); display: flex; align-items: center; justify-content: center; font-size: 1rem; color: rgba(139,126,255,0.50); transition: all 0.25s; }
    .social-link:hover { background: rgba(139,126,255,0.12); color: var(--prc-violet); box-shadow: 0 0 14px rgba(139,126,255,0.35); border-color: var(--prc-violet); }
    .footer-col h4 { font-family: var(--font-hud); font-size: 0.65rem; font-weight: 700; letter-spacing: 0.10em; text-transform: uppercase; margin-bottom: 20px; color: var(--prc-ice); text-shadow: 0 0 10px rgba(196,238,255,0.45); }
    .footer-col ul { display: flex; flex-direction: column; gap: 10px; }
    .footer-col ul li a { font-size: 0.875rem; color: var(--text-mid); transition: all 0.2s; display: flex; align-items: center; gap: 8px; }
    .footer-col ul li a i { font-size: 0.65rem; color: rgba(139,126,255,0.38); transition: color 0.2s; }
    .footer-col ul li a:hover { color: var(--prc-violet); padding-left: 4px; text-shadow: 0 0 8px rgba(139,126,255,0.50); }
    .footer-col ul li a:hover i { color: var(--prc-violet); }
    .footer-bottom { padding-top: 24px; border-top: 1px solid rgba(139,126,255,0.12); display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 14px; }
    .footer-bottom p { font-family: var(--font-hud); font-size: 0.58rem; color: var(--text-soft); letter-spacing: 0.06em; }
    .footer-bottom-links { display: flex; gap: 22px; }
    .footer-bottom-links a { font-family: var(--font-hud); font-size: 0.58rem; color: var(--text-soft); transition: color 0.2s; letter-spacing: 0.06em; }
    .footer-bottom-links a:hover { color: var(--prc-violet); }

    /* REVEAL */
    .reveal { opacity:0; transform:translateY(30px); transition: opacity 0.65s ease, transform 0.65s ease; }
    .reveal.visible { opacity:1; transform:translateY(0); }
    .reveal-left { opacity:0; transform:translateX(-24px); transition: opacity 0.65s ease, transform 0.65s ease; }
    .reveal-left.visible { opacity:1; transform:translateX(0); }
    .reveal-delay-1 { transition-delay:0.10s; }
    .reveal-delay-2 { transition-delay:0.20s; }
    .reveal-delay-3 { transition-delay:0.30s; }

    /* RESPONSIVE */
    @media (max-width: 1100px) { .hero-content { grid-template-columns: 1fr; gap: 40px; padding-bottom: 80px; } .hero-left { align-items: center; } .hero-title { text-align: center; } .hero-tagline { text-align: center; } .hero-actions { justify-content: center; } .countdown-grid { justify-content: center; } .hero-right { max-width: 620px; width: 100%; margin: 0 auto; } }
    @media (max-width: 1024px) { .stats-inner { grid-template-columns: repeat(2,1fr); } .stat-item:nth-child(2) { border-right:none; } .stat-item:nth-child(1),.stat-item:nth-child(2) { border-bottom:1px solid var(--border-neon); padding-bottom:24px; } .stat-item:nth-child(3),.stat-item:nth-child(4) { padding-top:24px; } #about .section-inner { grid-template-columns:1fr; gap:44px; } .about-visual { order:-1; } .floating-badge { display:none; } .categories-grid { grid-template-columns:1fr 1fr; } .categories-grid .cat-card:last-child { grid-column:1/-1; } .highlights-grid { grid-template-columns:1fr 1fr; } .highlight-item:nth-child(1) { grid-row:auto; grid-column:1/-1; } .highlight-item:nth-child(1) .highlight-img { min-height:270px; } .footer-top { grid-template-columns:1fr 1fr; gap:36px; } .organizers-card { padding:40px 28px; } .org-item { padding:18px 28px; } .cta-card { padding:64px 36px; } }
    @media (max-width: 768px) { :root { --section-pad:70px 0; --nav-height:62px; } body { cursor: auto; } button { cursor: pointer; } .cursor-dot, .cursor-ring { display: none; } .nav-links { display:none; } .nav-hamburger { display:flex; } .categories-grid { grid-template-columns:1fr; } .highlights-grid { grid-template-columns:1fr; } .highlight-item:nth-child(1) { grid-column:auto; } .highlight-item:nth-child(n+2) .highlight-img { height:200px; } .cta-card { padding:48px 24px; } .footer-top { grid-template-columns:1fr; gap:32px; } .footer-bottom { flex-direction:column; text-align:center; } .organizers-row { flex-direction:column; } .org-item { border-right:none; border-bottom:1px solid var(--border-neon); width:100%; } .org-item:last-child { border-bottom:none; } }
    @media (max-width: 520px) { :root { --nav-height:58px; } .nav-inner { padding:0 14px; } .section-inner,.video-inner,.organizers-inner,.footer-inner,.stats-inner { padding:0 16px; } .hero-content { padding:16px 16px 68px; } .nav-brand span { display:none; } .countdown-item { min-width:60px; padding:10px 10px; } .countdown-num { font-size:1.45rem; } .hero-actions { flex-direction:column; align-items:stretch; } .hero-actions .btn-neon-primary, .hero-actions .btn-neon-secondary { justify-content:center; clip-path:none; } .btn-neon-primary,.btn-neon-secondary { clip-path:none !important; } }

    ::-webkit-scrollbar { width: 4px; }
    ::-webkit-scrollbar-track { background: var(--bg-void); }
    ::-webkit-scrollbar-thumb { background: var(--prc-violet); box-shadow: 0 0 8px rgba(139,126,255,0.70); border-radius: 2px; }
  

    /* ===== ROBOVENTURE PAGE STYLES ===== */
@keyframes borderGlow { 0%,100% { box-shadow: 0 0 20px rgba(139,126,255,0.18); } 50% { box-shadow: 0 0 40px rgba(139,126,255,0.42); } }
    @keyframes cornerBlink { 0%,100% { opacity:1; } 50% { opacity:0.30; } }

    .page-wrapper { position: relative; z-index: 1; }

    /* ===== NAV ===== */
    #main-nav { position: fixed; top: 0; left: 0; right: 0; height: var(--nav-height); z-index: 1000; background: rgba(3,2,13,0.94); backdrop-filter: blur(20px); border-bottom: 1px solid var(--border-neon); box-shadow: 0 0 30px rgba(139,126,255,0.10); transition: background 0.4s, border-color 0.4s; }
    .nav-inner { max-width: 1340px; margin: 0 auto; height: 100%; padding: 0 36px; display: flex; align-items: center; justify-content: space-between; gap: 16px; }
    .nav-logo { display: flex; align-items: center; gap: 12px; flex-shrink: 0; }
    .nav-logo img { height: 38px; width: auto; transition: filter 0.3s; }
    .nav-logo:hover img { filter: drop-shadow(0 0 14px rgba(139,126,255,0.75)); }
    .nav-brand { font-family: var(--font-hud); font-weight: 700; font-size: 0.72rem; letter-spacing: 0.06em; line-height: 1.3; color: var(--prc-violet); text-shadow: 0 0 12px rgba(139,126,255,0.65); }
    .nav-brand span { color: var(--text-soft); display: block; font-size: 0.58rem; font-weight: 400; letter-spacing: 0.10em; text-transform: uppercase; margin-top: 1px; }
    .nav-links { display: flex; align-items: center; gap: 2px; }
    .nav-links a { font-family: var(--font-hud); font-size: 0.65rem; font-weight: 600; color: var(--text-mid); padding: 8px 14px; letter-spacing: 0.08em; text-transform: uppercase; border-radius: 4px; transition: all 0.2s; white-space: nowrap; }
    .nav-links a:hover, .nav-links a.active { color: var(--prc-violet); text-shadow: 0 0 12px rgba(139,126,255,0.85); }
    .nav-cta { background: transparent !important; border: 1px solid var(--prc-violet) !important; color: var(--prc-violet) !important; padding: 8px 20px !important; border-radius: 3px !important; box-shadow: 0 0 15px rgba(139,126,255,0.28), inset 0 0 15px rgba(139,126,255,0.06) !important; transition: all 0.25s !important; margin-left: 8px; clip-path: polygon(6px 0%, 100% 0%, calc(100% - 6px) 100%, 0% 100%); }
    .nav-cta:hover { background: rgba(139,126,255,0.12) !important; box-shadow: 0 0 30px rgba(139,126,255,0.52), inset 0 0 20px rgba(139,126,255,0.10) !important; color: #fff !important; }
    .nav-hamburger { display: none; flex-direction: column; justify-content: center; align-items: center; gap: 5px; width: 44px; height: 44px; padding: 0; background: rgba(139,126,255,0.06); border: 1px solid var(--border-neon); border-radius: 4px; flex-shrink: 0; z-index: 1002; -webkit-tap-highlight-color: transparent; touch-action: manipulation; transition: all 0.2s; }
    .nav-hamburger:hover { background: rgba(139,126,255,0.14); box-shadow: 0 0 14px rgba(139,126,255,0.28); }
    .nav-hamburger span { width: 20px; height: 1.5px; background: var(--prc-violet); border-radius: 2px; transition: transform 0.28s, opacity 0.28s; display: block; pointer-events: none; }
    .nav-hamburger.open span:nth-child(1) { transform: rotate(45deg) translate(5px,5px); }
    .nav-hamburger.open span:nth-child(2) { opacity: 0; }
    .nav-hamburger.open span:nth-child(3) { transform: rotate(-45deg) translate(5px,-5px); }
    .nav-mobile { display: none; position: fixed; top: var(--nav-height); left: 0; right: 0; background: rgba(3,2,13,0.98); backdrop-filter: blur(20px); border-bottom: 1px solid var(--border-neon); padding: 12px 18px 24px; z-index: 1000; flex-direction: column; gap: 2px; box-shadow: 0 20px 60px rgba(139,126,255,0.09); }
    .nav-mobile.open { display: flex; }
    .nav-mobile a { font-family: var(--font-hud); font-size: 0.70rem; font-weight: 600; color: var(--text-mid); padding: 13px 14px; border-radius: 3px; letter-spacing: 0.08em; text-transform: uppercase; transition: all 0.2s; display: flex; align-items: center; gap: 12px; }
    .nav-mobile a i { font-size: 1rem; color: var(--prc-violet); }
    .nav-mobile a:hover { color: var(--prc-violet); background: rgba(139,126,255,0.07); text-shadow: 0 0 10px rgba(139,126,255,0.55); }
    .nav-mobile .nav-cta { border: 1px solid var(--prc-violet) !important; color: var(--prc-violet) !important; margin-top: 10px; justify-content: center; border-radius: 3px !important; clip-path: none !important; }

    /* ===== BREADCRUMB ===== */
    .breadcrumb-bar { margin-top: var(--nav-height); padding: 14px 0; border-bottom: 1px solid var(--border-neon); background: rgba(139,126,255,0.02); }
    .breadcrumb-inner { max-width: 1340px; margin: 0 auto; padding: 0 36px; display: flex; align-items: center; gap: 10px; font-family: var(--font-hud); font-size: 0.58rem; letter-spacing: 0.10em; text-transform: uppercase; }
    .breadcrumb-inner a { color: var(--text-dim); transition: color 0.2s; }
    .breadcrumb-inner a:hover { color: var(--prc-violet); }
    .breadcrumb-sep { color: var(--text-dim); font-size: 0.52rem; }
    .breadcrumb-current { color: var(--prc-violet); }

    /* ===== PAGE HERO ===== */
    .page-hero { position: relative; overflow: hidden; padding: 80px 0 70px; background: radial-gradient(ellipse 70% 80% at 30% 50%, rgba(119,51,255,0.15) 0%, transparent 65%), radial-gradient(ellipse 50% 50% at 80% 30%, rgba(139,126,255,0.08) 0%, transparent 55%); }
    .page-hero-scan { position: absolute; inset: 0; pointer-events: none; overflow: hidden; }
    .page-hero-scan::after { content: ''; position: absolute; left: 0; right: 0; height: 2px; background: linear-gradient(90deg, transparent, var(--prc-violet), var(--prc-ice), transparent); animation: scanDown 6s linear infinite; box-shadow: 0 0 18px rgba(139,126,255,0.55); }
    .page-hero-inner { max-width: 1340px; margin: 0 auto; padding: 0 36px; display: flex; align-items: center; justify-content: space-between; gap: 56px; flex-wrap: wrap; }
    .page-hero-left { max-width: 700px; }

    .hero-sys-label { display: inline-flex; align-items: center; gap: 10px; font-family: var(--font-hud); font-size: 0.60rem; font-weight: 600; letter-spacing: 0.18em; text-transform: uppercase; color: var(--prc-ice); margin-bottom: 22px; animation: fadeIn 1s ease 0.1s both; }
    .sys-blink { width: 6px; height: 6px; background: var(--prc-ice); border-radius: 50%; box-shadow: var(--glow-cyan); animation: neonPulse 1.2s ease-in-out infinite; }

    .page-hero-title { font-family: var(--font-hud); margin-bottom: 20px; position: relative; animation: fadeInUp 0.9s ease 0.15s both; }
    .pht-track { display: block; font-size: clamp(0.65rem,1.2vw,0.88rem); font-weight: 700; letter-spacing: 0.28em; text-transform: uppercase; color: var(--creo-volt); text-shadow: 0 0 18px rgba(255,233,48,0.85), 0 0 55px rgba(255,233,48,0.28); animation: flicker 9s ease infinite; margin-bottom: 4px; }
    .pht-name { display: block; font-size: clamp(2.8rem,7vw,5.4rem); font-weight: 900; letter-spacing: -0.01em; line-height: 0.88; text-transform: uppercase; color: #fff; text-shadow: 0 0 40px rgba(139,126,255,0.45), 0 0 80px rgba(139,126,255,0.15), 0 4px 0 rgba(0,0,0,0.90); position: relative; }
    .pht-name::before, .pht-name::after { content: attr(data-text); position: absolute; inset: 0; font-size: inherit; font-weight: inherit; letter-spacing: inherit; }
    .pht-name::before { color: var(--prc-ice);    animation: glitch1 7s ease-in-out infinite; mix-blend-mode: screen; }
    .pht-name::after  { color: var(--creo-purple); animation: glitch2 7s ease-in-out infinite 0.5s; mix-blend-mode: screen; }
    .pht-sub { display: block; font-size: clamp(1.1rem,2.5vw,1.9rem); font-weight: 900; letter-spacing: 0.20em; text-transform: uppercase; background: linear-gradient(90deg, var(--prc-violet) 0%, var(--creo-sky) 100%); -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text; filter: drop-shadow(0 0 14px rgba(139,126,255,0.55)); }

    .page-hero-desc { font-size: clamp(0.88rem,1.4vw,1rem); color: var(--text-mid); line-height: 1.80; margin: 20px 0 28px; max-width: 580px; animation: fadeInUp 0.9s ease 0.25s both; }
    .hero-meta-row { display: flex; gap: 10px; flex-wrap: wrap; animation: fadeInUp 0.9s ease 0.35s both; }
    .hero-badge { display: inline-flex; align-items: center; gap: 8px; font-family: var(--font-hud); font-size: 0.58rem; font-weight: 700; letter-spacing: 0.10em; text-transform: uppercase; padding: 7px 16px; border: 1px solid; clip-path: polygon(6px 0%, 100% 0%, calc(100% - 6px) 100%, 0% 100%); }
    .hero-badge.violet { color: var(--prc-violet); border-color: rgba(139,126,255,0.40); background: rgba(139,126,255,0.06); }
    .hero-badge.volt   { color: var(--creo-volt);  border-color: rgba(255,233,48,0.35);  background: rgba(255,233,48,0.05);  text-shadow: 0 0 10px rgba(255,233,48,0.50); }
    .hero-badge.sky    { color: var(--creo-sky);   border-color: rgba(68,217,255,0.35);   background: rgba(68,217,255,0.05); }

    /* HUD panel */
    .page-hero-right { animation: fadeIn 0.9s ease 0.4s both; flex-shrink: 0; }
    .hud-panel { background: rgba(139,126,255,0.04); border: 1px solid var(--border-neon); padding: 26px 30px; position: relative; overflow: hidden; min-width: 250px; animation: borderGlow 3s ease-in-out infinite; }
    .hud-panel::before { content: ''; position: absolute; top: 0; left: 0; right: 0; height: 1px; background: linear-gradient(90deg, transparent, var(--prc-violet), transparent); }
    .hud-panel-label { font-family: var(--font-hud); font-size: 0.52rem; letter-spacing: 0.16em; text-transform: uppercase; color: var(--text-soft); margin-bottom: 18px; display: flex; align-items: center; gap: 8px; }
    .hud-panel-label::before { content: '//'; color: rgba(139,126,255,0.40); }
    .hud-stat { display: flex; align-items: baseline; gap: 10px; margin-bottom: 14px; }
    .hud-stat-num { font-family: var(--font-hud); font-size: 1.80rem; font-weight: 800; color: var(--prc-violet); line-height: 1; text-shadow: 0 0 18px rgba(139,126,255,0.65); }
    .hud-stat-label { font-family: var(--font-hud); font-size: 0.58rem; color: var(--text-soft); letter-spacing: 0.08em; text-transform: uppercase; }
    .hud-divider { height: 1px; background: var(--border-neon); margin: 18px 0; }
    .hud-list { display: flex; flex-direction: column; gap: 8px; }
    .hud-list-item { display: flex; align-items: center; gap: 10px; font-family: var(--font-hud); font-size: 0.56rem; color: var(--text-mid); letter-spacing: 0.06em; }
    .hud-list-dot { width: 5px; height: 5px; border-radius: 50%; background: var(--prc-violet); box-shadow: var(--glow-primary); flex-shrink: 0; }

    /* ===== BUTTONS — exact match to main site ===== */
    .btn-neon-primary { display: inline-flex; align-items: center; gap: 10px; background: transparent; color: var(--prc-violet); padding: 12px 28px; font-family: var(--font-hud); font-size: 0.66rem; font-weight: 700; letter-spacing: 0.12em; text-transform: uppercase; border: 1px solid var(--prc-violet); clip-path: polygon(10px 0%, 100% 0%, calc(100% - 10px) 100%, 0% 100%); box-shadow: 0 0 18px rgba(139,126,255,0.32), inset 0 0 18px rgba(139,126,255,0.07); transition: all 0.25s; position: relative; overflow: hidden; }
    .btn-neon-primary::before { content: ''; position: absolute; inset: 0; background: linear-gradient(90deg, transparent, rgba(139,126,255,0.18), transparent); transform: translateX(-100%); transition: transform 0.5s; }
    .btn-neon-primary:hover { background: rgba(139,126,255,0.12); box-shadow: 0 0 38px rgba(139,126,255,0.60), inset 0 0 28px rgba(139,126,255,0.12); color: #fff; transform: translateY(-2px); }
    .btn-neon-primary:hover::before { transform: translateX(100%); }
    .btn-neon-secondary { display: inline-flex; align-items: center; gap: 10px; background: transparent; color: var(--creo-sky); padding: 12px 28px; font-family: var(--font-hud); font-size: 0.66rem; font-weight: 700; letter-spacing: 0.12em; text-transform: uppercase; border: 1px solid var(--creo-sky); clip-path: polygon(10px 0%, 100% 0%, calc(100% - 10px) 100%, 0% 100%); box-shadow: 0 0 18px rgba(68,217,255,0.28), inset 0 0 18px rgba(68,217,255,0.06); transition: all 0.25s; }
    .btn-neon-secondary:hover { background: rgba(68,217,255,0.10); box-shadow: 0 0 38px rgba(68,217,255,0.55), inset 0 0 28px rgba(68,217,255,0.10); color: #fff; transform: translateY(-2px); }

    /* ===== MAIN CONTENT ===== */
    .main-content { max-width: 1340px; margin: 0 auto; padding: 80px 36px 100px; }

    .section-divider { display: flex; align-items: center; gap: 16px; margin-bottom: 52px; }
    .section-divider-line { flex: 1; height: 1px; background: linear-gradient(90deg, var(--border-neon), transparent); }
    .section-divider-label { font-family: var(--font-hud); font-size: 0.58rem; font-weight: 700; letter-spacing: 0.18em; text-transform: uppercase; color: var(--prc-ice); display: flex; align-items: center; gap: 8px; white-space: nowrap; }
    .section-divider-label::before { content: '//'; color: rgba(139,126,255,0.40); }
    .section-divider-line.right { background: linear-gradient(90deg, transparent, var(--border-neon)); }

    /* ===== CARDS — mirrors .cat-card.prc from main site ===== */
    .subcats-grid { display: grid; grid-template-columns: repeat(2,1fr); gap: 20px; }

    .subcat-card { background: rgba(0,0,8,0.60); border: 1px solid rgba(255,255,255,0.07); overflow: hidden; position: relative; display: flex; flex-direction: column; transition: all 0.35s cubic-bezier(0.23,1,0.32,1); cursor: none; }
    /* Top violet line on hover — same as .cat-card.prc::before */
    .subcat-card::before { content: ''; position: absolute; top: 0; left: 0; right: 0; height: 2px; background: linear-gradient(90deg, transparent, var(--prc-violet), transparent); opacity: 0; transition: opacity 0.35s; }
    /* Inset violet glow on hover — same as .cat-card.prc::after */
    .subcat-card::after { content: ''; position: absolute; inset: 0; box-shadow: inset 0 0 40px rgba(139,126,255,0.06); opacity: 0; transition: opacity 0.35s; pointer-events: none; }
    .subcat-card:hover { border-color: rgba(139,126,255,0.35); box-shadow: 0 0 40px rgba(139,126,255,0.12); transform: translateY(-6px); }
    .subcat-card:hover::before, .subcat-card:hover::after { opacity: 1; }

    /* Corner ornaments */
    .subcat-card .corner-tl, .subcat-card .corner-br { position: absolute; width: 16px; height: 16px; border-color: rgba(139,126,255,0.30); border-style: solid; z-index: 3; pointer-events: none; opacity: 0; transition: opacity 0.35s; }
    .subcat-card .corner-tl { top: 8px; left: 8px; border-width: 1.5px 0 0 1.5px; }
    .subcat-card .corner-br { bottom: 8px; right: 8px; border-width: 0 1.5px 1.5px 0; }
    .subcat-card:hover .corner-tl, .subcat-card:hover .corner-br { opacity: 1; border-color: var(--prc-ice); animation: cornerBlink 2s ease-in-out infinite; }

    /* Header */
    .subcat-header { padding: 22px 26px 18px; display: flex; align-items: flex-start; gap: 18px; border-bottom: 1px solid rgba(139,126,255,0.10); }

    /* Icon — matches .about-feature-icon from main site */
    .subcat-icon-wrap { width: 52px; height: 52px; flex-shrink: 0; display: flex; align-items: center; justify-content: center; font-size: 1.3rem; background: rgba(139,126,255,0.10); border: 1px solid rgba(139,126,255,0.24); color: var(--prc-violet); transition: all 0.35s; position: relative; }
    .subcat-icon-wrap::after { content: ''; position: absolute; inset: 0; background: linear-gradient(135deg, rgba(255,255,255,0.06) 0%, transparent 100%); }
    .subcat-card:hover .subcat-icon-wrap { transform: scale(1.08); box-shadow: 0 0 20px rgba(139,126,255,0.40); background: rgba(139,126,255,0.16); border-color: rgba(139,126,255,0.50); }

    .subcat-header-text { flex: 1; }
    .subcat-num { font-family: var(--font-hud); font-size: 0.50rem; font-weight: 700; letter-spacing: 0.16em; text-transform: uppercase; color: var(--text-dim); margin-bottom: 5px; display: block; }
    /* Title — same glow as .cat-title.prc */
    .subcat-name { font-family: var(--font-hud); font-size: clamp(0.88rem,1.5vw,1.05rem); font-weight: 700; letter-spacing: 0.04em; color: var(--text-high); margin-bottom: 8px; line-height: 1.2; text-shadow: 0 0 14px rgba(139,126,255,0.45); }
    /* Tags — exact .cat-tag.prc style */
    .subcat-tags { display: flex; gap: 5px; flex-wrap: wrap; }
    .subcat-tag { border: 1px solid rgba(139,126,255,0.20); padding: 3px 10px; font-family: var(--font-hud); font-size: 0.56rem; font-weight: 600; letter-spacing: 0.06em; text-transform: uppercase; color: var(--prc-violet); background: rgba(139,126,255,0.04); }

    /* Body */
    .subcat-body { padding: 20px 26px 26px; flex: 1; display: flex; flex-direction: column; }
    /* Description — matches .cat-desc from main site */
    .subcat-desc { font-size: 0.862rem; color: var(--text-mid); line-height: 1.68; margin-bottom: 18px; flex: 1; }

    /* Skills */
    .subcat-skills-label { font-family: var(--font-hud); font-size: 0.52rem; font-weight: 700; letter-spacing: 0.14em; text-transform: uppercase; color: var(--text-soft); margin-bottom: 10px; display: flex; align-items: center; gap: 8px; }
    .subcat-skills-label::before { content: '›'; font-size: 0.90rem; color: var(--prc-violet); }
    .subcat-skills { display: flex; flex-direction: column; gap: 7px; margin-bottom: 20px; }
    .subcat-skill { display: flex; align-items: flex-start; gap: 10px; font-size: 0.856rem; color: var(--text-mid); line-height: 1.50; }
    .skill-dot { width: 6px; height: 6px; border-radius: 50%; flex-shrink: 0; margin-top: 7px; background: var(--prc-violet); box-shadow: 0 0 6px rgba(139,126,255,0.60); }

    /* Difficulty bar */
    .subcat-level { display: flex; align-items: center; gap: 12px; margin-bottom: 20px; }
    .level-label { font-family: var(--font-hud); font-size: 0.50rem; letter-spacing: 0.12em; text-transform: uppercase; color: var(--text-dim); white-space: nowrap; }
    .level-track { flex: 1; height: 3px; background: rgba(139,126,255,0.12); border-radius: 10px; }
    .level-fill { height: 3px; border-radius: 10px; background: linear-gradient(90deg, rgba(139,126,255,0.50), var(--prc-violet)); box-shadow: 0 0 8px rgba(139,126,255,0.55); transition: width 1.2s cubic-bezier(0.23,1,0.32,1); }
    .level-pct { font-family: var(--font-hud); font-size: 0.50rem; letter-spacing: 0.08em; color: var(--text-dim); white-space: nowrap; }

    /* CTA link — exact .cat-link.prc style */
    .subcat-cta { display: inline-flex; align-items: center; gap: 7px; margin-top: auto; font-family: var(--font-hud); font-size: 0.62rem; font-weight: 700; letter-spacing: 0.10em; text-transform: uppercase; padding: 9px 18px; border: 1px solid rgba(139,126,255,0.34); color: var(--prc-violet); align-self: flex-start; clip-path: polygon(6px 0%, 100% 0%, calc(100% - 6px) 100%, 0% 100%); transition: all 0.25s; }
    .subcat-card:hover .subcat-cta { background: rgba(139,126,255,0.10); box-shadow: 0 0 14px rgba(139,126,255,0.24); }

    /* ===== CTA — exact #cta from main site ===== */
    .page-cta-wrap { margin-top: 80px; }
    .page-cta { max-width: 960px; margin: 0 auto; background: rgba(139,126,255,0.04); border: 1px solid var(--prc-violet); padding: 88px 64px; text-align: center; position: relative; overflow: hidden; box-shadow: 0 0 80px rgba(139,126,255,0.18), inset 0 0 80px rgba(139,126,255,0.03); }
    .page-cta::before { content: ''; position: absolute; top: 0; left: 0; right: 0; height: 2px; background: linear-gradient(90deg, transparent, var(--prc-ice), transparent); box-shadow: 0 0 20px rgba(139,126,255,0.8); }
    .page-cta::after  { content: ''; position: absolute; bottom: 0; left: 0; right: 0; height: 2px; background: linear-gradient(90deg, transparent, var(--creo-purple), transparent); box-shadow: 0 0 20px rgba(204,85,255,0.8); }
    .cta-corner { position: absolute; width: 24px; height: 24px; border-color: var(--prc-violet); border-style: solid; }
    .cta-corner.tl { top: 12px; left: 12px; border-width: 1.5px 0 0 1.5px; }
    .cta-corner.tr { top: 12px; right: 12px; border-width: 1.5px 1.5px 0 0; }
    .cta-corner.bl { bottom: 12px; left: 12px; border-width: 0 0 1.5px 1.5px; }
    .cta-corner.br { bottom: 12px; right: 12px; border-width: 0 1.5px 1.5px 0; }
    .cta-bg-grid { position: absolute; inset: 0; pointer-events: none; background-image: linear-gradient(rgba(139,126,255,0.05) 1px, transparent 1px), linear-gradient(90deg, rgba(139,126,255,0.05) 1px, transparent 1px); background-size: 40px 40px; mask-image: radial-gradient(ellipse 70% 70% at 50% 50%, black 0%, transparent 100%); }
    .cta-tag { display: inline-flex; align-items: center; gap: 8px; border: 1px solid rgba(255,233,48,0.38); padding: 6px 18px; font-family: var(--font-hud); font-size: 0.60rem; font-weight: 700; letter-spacing: 0.14em; text-transform: uppercase; color: var(--creo-volt); margin-bottom: 22px; position: relative; z-index: 1; clip-path: polygon(6px 0%, 100% 0%, calc(100% - 6px) 100%, 0% 100%); text-shadow: 0 0 10px rgba(255,233,48,0.60); background: rgba(255,233,48,0.05); }
    .cta-title { font-family: var(--font-hud); font-size: clamp(1.8rem,3.8vw,2.8rem); font-weight: 800; letter-spacing: -0.01em; line-height: 1.08; margin-bottom: 16px; position: relative; z-index: 1; color: #fff; }
    .cta-title .accent { color: var(--prc-violet); text-shadow: 0 0 18px rgba(139,126,255,0.70); }
    .cta-desc { font-size: 1rem; color: var(--text-mid); max-width: 460px; margin: 0 auto 38px; line-height: 1.78; position: relative; z-index: 1; }
    .cta-actions { display: flex; gap: 14px; justify-content: center; flex-wrap: wrap; position: relative; z-index: 1; }

    /* ===== FOOTER — exact copy from main site ===== */
    footer { background: rgba(0,0,6,0.95); border-top: 1px solid var(--border-neon); padding: 70px 0 32px; }
    .footer-inner { max-width: 1340px; margin: 0 auto; padding: 0 36px; }
    .footer-top { display: grid; grid-template-columns: 2fr 1fr 1fr 1fr; gap: 52px; margin-bottom: 52px; }
    .footer-brand img { height: 36px; width: auto; margin-bottom: 16px; }
    .footer-brand p { font-size: 0.875rem; color: var(--text-mid); line-height: 1.80; margin-bottom: 22px; }
    .footer-contact-list { display: flex; flex-direction: column; gap: 11px; margin-bottom: 24px; }
    .footer-contact-item { display: flex; align-items: center; gap: 11px; font-size: 0.875rem; color: var(--text-mid); }
    .footer-contact-item i { color: var(--prc-violet); font-size: 0.95rem; flex-shrink: 0; }
    .footer-contact-item a { color: var(--text-mid); transition: color 0.2s; }
    .footer-contact-item a:hover { color: var(--prc-violet); text-shadow: 0 0 8px rgba(139,126,255,0.60); }
    .social-links { display: flex; gap: 8px; }
    .social-link { width: 40px; height: 40px; background: rgba(139,126,255,0.04); border: 1px solid rgba(139,126,255,0.18); display: flex; align-items: center; justify-content: center; font-size: 1rem; color: rgba(139,126,255,0.50); transition: all 0.25s; }
    .social-link:hover { background: rgba(139,126,255,0.12); color: var(--prc-violet); box-shadow: 0 0 14px rgba(139,126,255,0.35); border-color: var(--prc-violet); }
    .footer-col h4 { font-family: var(--font-hud); font-size: 0.65rem; font-weight: 700; letter-spacing: 0.10em; text-transform: uppercase; margin-bottom: 20px; color: var(--prc-ice); text-shadow: 0 0 10px rgba(196,238,255,0.45); }
    .footer-col ul { display: flex; flex-direction: column; gap: 10px; }
    .footer-col ul li a { font-size: 0.875rem; color: var(--text-mid); transition: all 0.2s; display: flex; align-items: center; gap: 8px; }
    .footer-col ul li a i { font-size: 0.65rem; color: rgba(139,126,255,0.38); transition: color 0.2s; }
    .footer-col ul li a:hover { color: var(--prc-violet); padding-left: 4px; text-shadow: 0 0 8px rgba(139,126,255,0.50); }
    .footer-col ul li a:hover i { color: var(--prc-violet); }
    .footer-bottom { padding-top: 24px; border-top: 1px solid rgba(139,126,255,0.12); display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 14px; }
    .footer-bottom p { font-family: var(--font-hud); font-size: 0.58rem; color: var(--text-soft); letter-spacing: 0.06em; }
    .footer-bottom-links { display: flex; gap: 22px; }
    .footer-bottom-links a { font-family: var(--font-hud); font-size: 0.58rem; color: var(--text-soft); transition: color 0.2s; letter-spacing: 0.06em; }
    .footer-bottom-links a:hover { color: var(--prc-violet); }

    /* ===== REVEAL ===== */
    .reveal { opacity:0; transform:translateY(30px); transition: opacity 0.65s ease, transform 0.65s ease; }
    .reveal.visible { opacity:1; transform:translateY(0); }
    .reveal-delay-1 { transition-delay:0.10s; }
    .reveal-delay-2 { transition-delay:0.20s; }
    .reveal-delay-3 { transition-delay:0.30s; }
    .reveal-delay-4 { transition-delay:0.40s; }

    /* ===== RESPONSIVE ===== */
    @media (max-width: 1100px) { .page-hero-right { display: none; } }
    @media (max-width: 1024px) { .subcats-grid { grid-template-columns: 1fr; } .footer-top { grid-template-columns: 1fr 1fr; gap: 36px; } .page-cta { padding: 64px 36px; } }
    @media (max-width: 768px) {
      :root { --nav-height: 62px; }
      body { cursor: auto; } button { cursor: pointer; }
      .cursor-dot, .cursor-ring { display: none; }
      .nav-links { display: none; } .nav-hamburger { display: flex; }
      .main-content { padding: 60px 16px 80px; }
      .breadcrumb-inner, .page-hero-inner, .footer-inner { padding: 0 16px; }
      .page-hero { padding: 60px 0 50px; }
      .page-cta { padding: 48px 24px; }
      .footer-top { grid-template-columns: 1fr; gap: 32px; }
      .footer-bottom { flex-direction: column; text-align: center; }
    }
    @media (max-width: 520px) {
      :root { --nav-height: 58px; }
      .nav-inner { padding: 0 14px; }
      .hero-meta-row { flex-direction: column; align-items: flex-start; }
      .cta-actions { flex-direction: column; align-items: stretch; }
      .cta-actions .btn-neon-primary, .cta-actions .btn-neon-secondary { justify-content: center; clip-path: none; }
    }

    ::-webkit-scrollbar { width: 4px; }
    ::-webkit-scrollbar-track { background: var(--bg-void); }
    ::-webkit-scrollbar-thumb { background: var(--prc-violet); box-shadow: 0 0 8px rgba(139,126,255,0.70); border-radius: 2px; }
  
    /* ===== MAKEX PAGE STYLES ===== */
    .makex-card::before { background: linear-gradient(90deg, transparent, var(--creo-sky), transparent); }
    .makex-card::after  { box-shadow: inset 0 0 40px rgba(68,217,255,0.06); }
    .makex-card:hover   { border-color: rgba(68,217,255,0.35); box-shadow: 0 0 40px rgba(68,217,255,0.14); transform: translateY(-6px); }
    .makex-card:hover .corner-tl, .makex-card:hover .corner-br { border-color: var(--creo-sky); }
    .makex-icon { background: rgba(68,217,255,0.10); border-color: rgba(68,217,255,0.28); color: var(--creo-sky); }
    .makex-card:hover .subcat-icon-wrap { box-shadow: 0 0 20px rgba(68,217,255,0.40); background: rgba(68,217,255,0.16); border-color: rgba(68,217,255,0.50); }
    .makex-name  { text-shadow: 0 0 14px rgba(68,217,255,0.45); }
    .subcat-tag.makex-tag  { color: var(--creo-sky);   border-color: rgba(68,217,255,0.25);  background: rgba(68,217,255,0.05); }
    .subcat-tag.makex-tag-m{ color: var(--neon-magenta); border-color: rgba(204,85,255,0.25); background: rgba(204,85,255,0.05); }
    .makex-skills-label::before   { color: var(--creo-sky); }
    .makex-skills-label-m::before { color: var(--neon-magenta); }
    .makex-dot   { background: var(--creo-sky);   box-shadow: 0 0 6px rgba(68,217,255,0.60); }
    .makex-dot-m { background: var(--neon-magenta); box-shadow: 0 0 6px rgba(204,85,255,0.60); }
    .makex-fill  { background: linear-gradient(90deg, rgba(68,217,255,0.50), var(--creo-sky));   box-shadow: 0 0 8px rgba(68,217,255,0.55); }
    .makex-fill-m{ background: linear-gradient(90deg, rgba(204,85,255,0.50), var(--neon-magenta)); box-shadow: 0 0 8px rgba(204,85,255,0.55); }
    .makex-cta  { color: var(--creo-sky);    border-color: rgba(68,217,255,0.34); }
    .makex-cta-m{ color: var(--neon-magenta); border-color: rgba(204,85,255,0.34); }
    .makex-card:hover .makex-cta  { background: rgba(68,217,255,0.10);  box-shadow: 0 0 14px rgba(68,217,255,0.24); }
    .makex-card:hover .makex-cta-m{ background: rgba(204,85,255,0.10); box-shadow: 0 0 14px rgba(204,85,255,0.24); }

  
    /* ===== DRONE SOCCER PAGE STYLES ===== */
    .drone-card::before { background: linear-gradient(90deg, transparent, var(--creo-amber), transparent); }
    .drone-card::after  { box-shadow: inset 0 0 40px rgba(255,160,48,0.06); }
    .drone-card:hover   { border-color: rgba(255,160,48,0.35); box-shadow: 0 0 40px rgba(255,160,48,0.14); transform: translateY(-6px); }
    .drone-card:hover .corner-tl, .drone-card:hover .corner-br { border-color: var(--creo-amber); }
    .drone-icon  { background: rgba(255,160,48,0.10); border-color: rgba(255,160,48,0.28); color: var(--creo-amber); }
    .drone-card:hover .subcat-icon-wrap { box-shadow: 0 0 20px rgba(255,160,48,0.40); background: rgba(255,160,48,0.16); border-color: rgba(255,160,48,0.50); }
    .drone-name  { text-shadow: 0 0 14px rgba(255,160,48,0.50); }
    .subcat-tag.drone-tag { color: var(--creo-amber); border-color: rgba(255,160,48,0.25); background: rgba(255,160,48,0.05); }
    .drone-skills-label::before { color: var(--creo-amber); }
    .drone-dot  { background: var(--creo-amber); box-shadow: 0 0 6px rgba(255,160,48,0.65); }
    .drone-fill { background: linear-gradient(90deg, rgba(255,160,48,0.50), var(--creo-amber)); box-shadow: 0 0 8px rgba(255,160,48,0.60); }
    .drone-cta  { color: var(--creo-amber); border-color: rgba(255,160,48,0.34); }
    .drone-card:hover .drone-cta { background: rgba(255,160,48,0.10); box-shadow: 0 0 14px rgba(255,160,48,0.28); }

  
    /* ===== DRONE SOCCER PAGE STYLES ===== */
    .drone-card::before { background: linear-gradient(90deg, transparent, var(--creo-amber), transparent); }
    .drone-card::after  { box-shadow: inset 0 0 40px rgba(255,160,48,0.06); }
    .drone-card:hover   { border-color: rgba(255,160,48,0.35); box-shadow: 0 0 40px rgba(255,160,48,0.14); transform: translateY(-6px); }
    .drone-card:hover .corner-tl, .drone-card:hover .corner-br { border-color: var(--creo-amber); }
    .drone-icon  { background: rgba(255,160,48,0.10); border-color: rgba(255,160,48,0.28); color: var(--creo-amber); }
    .drone-card:hover .subcat-icon-wrap { box-shadow: 0 0 20px rgba(255,160,48,0.40); background: rgba(255,160,48,0.16); border-color: rgba(255,160,48,0.50); }
    .drone-name  { text-shadow: 0 0 14px rgba(255,160,48,0.45); }
    .subcat-tag.drone-tag { color: var(--creo-amber); border-color: rgba(255,160,48,0.25); background: rgba(255,160,48,0.05); }
    .drone-skills-label::before { color: var(--creo-amber); }
    .drone-dot  { background: var(--creo-amber); box-shadow: 0 0 6px rgba(255,160,48,0.60); }
    .drone-fill { background: linear-gradient(90deg, rgba(255,160,48,0.50), var(--creo-amber)); box-shadow: 0 0 8px rgba(255,160,48,0.55); }
    .drone-cta  { color: var(--creo-amber); border-color: rgba(255,160,48,0.34); }
    .drone-card:hover .drone-cta { background: rgba(255,160,48,0.10); box-shadow: 0 0 14px rgba(255,160,48,0.24); }

  
    /* ===== SUB-PAGE LOGO ===== */
    .page-hero-logo { display: flex; align-items: center; margin-bottom: 18px; animation: fadeIn 0.9s ease 0.05s both; }
    .page-hero-logo img { height: 44px; width: auto; filter: drop-shadow(0 0 12px rgba(139,126,255,0.50)); transition: filter 0.3s; }
    .page-hero-logo img:hover { filter: drop-shadow(0 0 20px rgba(139,126,255,0.80)); }

  
    /* ===== RANKINGS PAGE ===== */
    .rankings-filter-bar { display: flex; gap: 8px; flex-wrap: wrap; margin-bottom: 36px; }
    .filter-btn {
      font-family: var(--font-hud); font-size: 0.60rem; font-weight: 700;
      letter-spacing: 0.10em; text-transform: uppercase;
      padding: 8px 18px; border: 1px solid rgba(139,126,255,0.28);
      color: var(--text-soft); background: transparent;
      clip-path: polygon(5px 0%, 100% 0%, calc(100% - 5px) 100%, 0% 100%);
      transition: all 0.2s; cursor: pointer;
    }
    .filter-btn:hover, .filter-btn.active {
      border-color: var(--prc-violet); color: var(--prc-violet);
      background: rgba(139,126,255,0.10);
      box-shadow: 0 0 14px rgba(139,126,255,0.25);
    }
    .filter-btn.active-rv   { border-color: var(--prc-violet); color: var(--prc-violet); background: rgba(139,126,255,0.10); }
    .filter-btn.active-mx   { border-color: var(--creo-sky);   color: var(--creo-sky);   background: rgba(68,217,255,0.10);  box-shadow: 0 0 14px rgba(68,217,255,0.22); }
    .filter-btn.active-dr   { border-color: var(--creo-amber); color: var(--creo-amber); background: rgba(255,160,48,0.10);  box-shadow: 0 0 14px rgba(255,160,48,0.22); }

    .rankings-table-wrap { position: relative; overflow: hidden; border: 1px solid var(--border-neon); }
    .rankings-table-wrap::before { content: ''; position: absolute; top: 0; left: 0; right: 0; height: 1px; background: linear-gradient(90deg, transparent, var(--prc-violet), transparent); }

    .rankings-header-row {
      display: grid; grid-template-columns: 56px 1fr 200px 100px 100px 80px;
      padding: 12px 20px; background: rgba(139,126,255,0.08);
      border-bottom: 1px solid var(--border-neon);
      font-family: var(--font-hud); font-size: 0.52rem; font-weight: 700;
      letter-spacing: 0.14em; text-transform: uppercase; color: var(--text-soft);
    }
    .rankings-row {
      display: grid; grid-template-columns: 56px 1fr 200px 100px 100px 80px;
      padding: 14px 20px; border-bottom: 1px solid rgba(139,126,255,0.08);
      align-items: center; transition: background 0.2s;
      position: relative;
    }
    .rankings-row:last-child { border-bottom: none; }
    .rankings-row:hover { background: rgba(139,126,255,0.06); }

    /* Top 3 highlights */
    .rankings-row.rank-1 { background: rgba(255,233,48,0.04); }
    .rankings-row.rank-2 { background: rgba(196,238,255,0.03); }
    .rankings-row.rank-3 { background: rgba(255,160,48,0.03); }
    .rankings-row.rank-1:hover { background: rgba(255,233,48,0.08); }
    .rankings-row.rank-2:hover { background: rgba(196,238,255,0.06); }
    .rankings-row.rank-3:hover { background: rgba(255,160,48,0.06); }

    .rank-num {
      font-family: var(--font-hud); font-size: 1.1rem; font-weight: 900;
      line-height: 1; text-align: center;
    }
    .rank-1 .rank-num { color: var(--creo-volt);  text-shadow: 0 0 14px rgba(255,233,48,0.70); }
    .rank-2 .rank-num { color: var(--prc-ice);    text-shadow: 0 0 14px rgba(196,238,255,0.70); }
    .rank-3 .rank-num { color: var(--creo-amber); text-shadow: 0 0 14px rgba(255,160,48,0.70); }
    .rank-num.other   { color: var(--text-dim); font-size: 0.88rem; }

    .rank-medal { font-size: 1.1rem; display: block; text-align: center; line-height: 1; }

    .rank-school { display: flex; flex-direction: column; gap: 3px; }
    .rank-school-name {
      font-family: var(--font-hud); font-size: 0.72rem; font-weight: 700;
      color: var(--text-high); letter-spacing: 0.04em;
    }
    .rank-team-name {
      font-family: var(--font-hud); font-size: 0.56rem; color: var(--text-soft);
      letter-spacing: 0.08em; text-transform: uppercase;
    }

    .rank-category-badge {
      display: inline-flex; align-items: center;
      font-family: var(--font-hud); font-size: 0.54rem; font-weight: 700;
      letter-spacing: 0.08em; text-transform: uppercase;
      padding: 4px 10px; border: 1px solid;
      clip-path: polygon(4px 0%, 100% 0%, calc(100% - 4px) 100%, 0% 100%);
    }
    .badge-rv    { color: var(--prc-violet); border-color: rgba(139,126,255,0.30); background: rgba(139,126,255,0.06); }
    .badge-mx    { color: var(--creo-sky);   border-color: rgba(68,217,255,0.30);  background: rgba(68,217,255,0.06); }
    .badge-drone { color: var(--creo-amber); border-color: rgba(255,160,48,0.30);  background: rgba(255,160,48,0.06); }

    .rank-score {
      font-family: var(--font-hud); font-size: 0.88rem; font-weight: 800;
      color: var(--text-high); text-align: center;
    }
    .rank-school-loc {
      font-family: var(--font-hud); font-size: 0.54rem; color: var(--text-dim);
      letter-spacing: 0.06em; text-align: center;
    }
    .rank-status {
      font-family: var(--font-hud); font-size: 0.52rem; font-weight: 700;
      letter-spacing: 0.08em; text-transform: uppercase;
      text-align: center;
    }
    .rank-status.qualified { color: var(--creo-volt); text-shadow: 0 0 8px rgba(255,233,48,0.55); }
    .rank-status.finalist  { color: var(--prc-violet); }
    .rank-status.competing { color: var(--text-soft); }

    .rank-cat-group {
      display: none;
    }
    .rank-cat-group.visible { display: block; }

    .rankings-section-label {
      padding: 10px 20px 8px;
      font-family: var(--font-hud); font-size: 0.55rem; font-weight: 700;
      letter-spacing: 0.18em; text-transform: uppercase;
      background: rgba(0,0,0,0.30); border-bottom: 1px solid rgba(139,126,255,0.12);
      display: flex; align-items: center; gap: 10px;
    }
    .rankings-section-label .label-dot {
      width: 6px; height: 6px; border-radius: 50%; flex-shrink: 0;
    }

    @media (max-width: 768px) {
      .rankings-header-row { grid-template-columns: 44px 1fr 90px; }
      .rankings-row        { grid-template-columns: 44px 1fr 90px; }
      .col-cat, .col-loc, .col-status { display: none; }
      .rank-school-name { font-size: 0.65rem; }
    }

  </style>
</head>

<body>

  <!-- Custom Cursor -->
  <div class="cursor-dot" id="cursorDot"></div>
  <div class="cursor-ring" id="cursorRing"></div>

  <!-- Hex Grid Background -->
  <div class="hex-grid" aria-hidden="true"></div>

    <nav id="main-nav" role="navigation" aria-label="Main navigation">
      <div class="nav-inner">
        <a onclick="goHome()" class="nav-logo" style="cursor:pointer" aria-label="PRC Home">
          <img src="assets/PRC White Logo.png" alt="Philippine Robotics Cup Logo" />
          <div class="nav-brand">Philippine Robotics Cup<span>By Creotec Philippines</span></div>
        </a>
        <ul class="nav-links" role="list">
          <li><a onclick="navTo('hero')" style="cursor:pointer">Home</a></li>
          <li><a onclick="navTo('about')" style="cursor:pointer">About</a></li>
          <li><a onclick="navTo('categories')" style="cursor:pointer">Categories</a></li>
          <li><a onclick="goRankings()" style="cursor:pointer">Rankings</a></li>
          <li><a onclick="navTo('highlights')" style="cursor:pointer">Gallery</a></li>
          <li><a onclick="navTo('cta')" style="cursor:pointer">Shop</a></li>
          <li><a onclick="navTo('cta')" class="nav-cta" style="cursor:pointer">Register Now</a></li>
        </ul>
        <button class="nav-hamburger" id="hamburger" type="button" aria-label="Open menu" aria-expanded="false" aria-controls="mobile-menu">
          <span></span><span></span><span></span>
        </button>
      </div>
    </nav>

    <nav class="nav-mobile" id="mobile-menu" aria-label="Mobile navigation" aria-hidden="true">
      <a onclick="navTo('hero');closeMobile()" style="cursor:pointer"><i class="fi fi-rr-home"></i>Home</a>
      <a onclick="navTo('about');closeMobile()" style="cursor:pointer"><i class="fi fi-rr-info"></i>About</a>
      <a onclick="navTo('categories');closeMobile()" style="cursor:pointer"><i class="fi fi-rr-trophy"></i>Categories</a>
      <a onclick="goRankings();closeMobile()" style="cursor:pointer"><i class="fi fi-rr-list-check"></i>Rankings</a>
      <a onclick="navTo('highlights');closeMobile()" style="cursor:pointer"><i class="fi fi-rr-picture"></i>Gallery</a>
      <a onclick="navTo('cta');closeMobile()" style="cursor:pointer"><i class="fi fi-rr-shopping-cart"></i>Shop</a>
      <a onclick="navTo('cta');closeMobile()" class="nav-cta" style="cursor:pointer"><i class="fi fi-rr-pen-field"></i>Register Now</a>
    </nav>

  <div class="page-wrapper">

    <!-- ===== PAGE: HOME ===== -->
    <div id="page-home" class="page active">
<div class="cursor-dot" id="cursorDot"></div>
  <div class="cursor-ring" id="cursorRing"></div>
  <div class="hex-grid" aria-hidden="true"></div>

  <div class="page-wrapper">


    <!-- HERO -->
    <section id="hero" aria-label="Hero">
      <div class="hero-bg"></div>
      <div class="hero-overlay"></div>
      <div class="hero-scan"></div>
      <div class="hero-content">
        <div class="hero-left">
          <h1 class="hero-title">
            <span class="ht-philippine">Philippine</span>
            <span class="ht-robotics" data-text="Robotics">Robotics</span>
            <span class="ht-cup">Cup</span>
            <div class="ht-divider" aria-hidden="true">
              <div class="ht-divider-line"></div>
              <div class="ht-divider-diamond"></div>
              <div class="ht-divider-line right"></div>
            </div>
            <span class="ht-year">2026</span>
          </h1>
          <p class="hero-tagline">Where young innovators build, code, and compete — shaping the next generation of Filipino STEM leaders on the world stage.</p>
          <div class="hero-actions">
            <a href="#" class="btn-neon-primary"><i class="fi fi-rr-pen-field"></i> Register Your Team</a>
            <a onclick="scrollToSection('video')" class="btn-neon-secondary" style="cursor:pointer"><i class="fi fi-rr-play-alt"></i> Watch Highlights</a>
          </div>
          <div class="hero-countdown" aria-label="Countdown to PRC 2026">
            <div class="countdown-label">Competition begins in</div>
            <div class="countdown-grid">
              <div class="countdown-item"><span class="countdown-num" id="cd-days">000</span><span class="countdown-unit">Days</span></div>
              <div class="countdown-item"><span class="countdown-num" id="cd-hours">00</span><span class="countdown-unit">Hours</span></div>
              <div class="countdown-item"><span class="countdown-num" id="cd-minutes">00</span><span class="countdown-unit">Minutes</span></div>
              <div class="countdown-item"><span class="countdown-num" id="cd-seconds">00</span><span class="countdown-unit">Seconds</span></div>
            </div>
            <p class="hero-event-date"><i class="fi fi-rr-calendar"></i> October 2026 &nbsp;&#47;&#47;&nbsp; Vista Mall Las Pi&ntilde;as, Metro Manila</p>
          </div>
        </div>
        <div class="hero-right">
          <div class="hero-video-frame">
            <div class="hero-video-inner-wrap">
              <video id="heroVideo" class="hero-video-el" src="assets/hero-video.mp4" autoplay muted loop playsinline preload="auto" aria-label="Philippine Robotics Cup 2025 competition highlights video"></video>
              <div class="hero-video-hud" aria-hidden="true">
                <div class="video-hud-label"><span class="rec-dot"></span>PRC 2025 &nbsp;//&nbsp; COMPETITION HIGHLIGHTS</div>
                <div class="video-hud-bottom">
                  <span class="video-hud-title">Philippine Robotics Cup</span>
                  <span class="video-hud-year">&#9654; 2025 SEASON</span>
                </div>
              </div>
              <div class="video-replay-badge" id="replayBadge" aria-live="polite">
                <div class="replay-btn" id="replayBtn" role="button" tabindex="0" aria-label="Replay highlights video">
                  <i class="fi fi-rr-rotate-right"></i>REPLAY
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="hero-scroll" aria-hidden="true"><i class="fi fi-rr-angle-double-down"></i> Scroll Down</div>
    </section>

    <!-- STATS -->
    <div class="stats-strip">
      <div class="stats-inner">
        <div class="stat-item reveal"><div class="stat-icon"><i class="fi fi-rr-layers"></i></div><span class="stat-num" data-target="3">0</span><span class="stat-label">Competition Tracks</span></div>
        <div class="stat-item reveal reveal-delay-1"><div class="stat-icon"><i class="fi fi-rr-trophy"></i></div><span class="stat-num" data-target="11">0</span><span class="stat-label">Event Categories</span></div>
        <div class="stat-item reveal reveal-delay-2"><div class="stat-icon"><i class="fi fi-rr-calendar-check"></i></div><span class="stat-num">2025</span><span class="stat-label">Active Since</span></div>
        <div class="stat-item reveal reveal-delay-3"><div class="stat-icon"><i class="fi fi-rr-globe"></i></div><span class="stat-num">1st</span><span class="stat-label">Int&rsquo;l Qualifier (MakeX)</span></div>
      </div>
    </div>

    <!-- ABOUT -->
    <section id="about" aria-labelledby="about-title">
      <div class="section-inner">
        <div>
          <div class="section-eyebrow reveal-left">About the Event</div>
          <h2 class="section-title reveal" id="about-title">Empowering<br/><span class="accent">Filipino Innovators</span></h2>
          <p class="section-desc reveal">The Philippine Robotics Cup (PRC) is a premier national, outcome-based robotics competition organized by Creotec Philippines Inc., supported by DepEd, DOST, and DICT.</p>
          <div class="about-feature-list">
            <div class="about-feature reveal"><div class="about-feature-icon"><i class="fi fi-rr-brain"></i></div><div class="about-feature-text"><h4>STEM-Centered Learning</h4><p>Develops hands-on skills in AI, IoT, coding, and robotics engineering for real-world application.</p></div></div>
            <div class="about-feature reveal reveal-delay-1"><div class="about-feature-icon"><i class="fi fi-rr-globe"></i></div><div class="about-feature-text"><h4>International Gateway</h4><p>MakeX category winners earn the chance to represent the Philippines at the MakeX World Championships in China.</p></div></div>
            <div class="about-feature reveal reveal-delay-2"><div class="about-feature-icon"><i class="fi fi-rr-users"></i></div><div class="about-feature-text"><h4>Open to All Schools</h4><p>Welcoming students from both public and private schools nationwide — no school too big or too small.</p></div></div>
          </div>
        </div>
        <div class="about-visual reveal">
          <div class="about-card">
            <div class="about-card-header">
              <img src="assets/PRC White Logo.png" alt="PRC Logo" />
              <div class="about-card-header-text"><h3>Philippine Robotics Cup</h3><p>Organized by Creotec Philippines Inc.</p></div>
            </div>
            <div class="about-card-body">
              <p>A qualifying venue for international events, the PRC features diverse categories spanning robotics, automation, drone sports, and creative engineering — designed to challenge and inspire the next generation of Filipino tech leaders.</p>
              <div class="partner-strip">
                <div class="partner-strip-label">Supported by</div>
                <div class="partner-logos">
                  <span class="partner-badge">DepEd</span><span class="partner-badge">DOST</span><span class="partner-badge">DICT</span>
                  <img src="assets/CreoLogo.png" alt="Creotec Philippines" style="height:20px;" />
                </div>
              </div>
            </div>
          </div>
          <div class="floating-badge"><span>PRC 2026</span>OCT // LAS PI&Ntilde;AS</div>
        </div>
      </div>
    </section>

    <!-- CATEGORIES -->
    <section id="categories" aria-labelledby="categories-title">
      <div class="section-inner">
        <div class="categories-header">
          <div>
            <div class="section-eyebrow reveal-left">Competition Tracks</div>
            <h2 class="section-title reveal" id="categories-title">Find Your<br/><span class="accent">Competition</span></h2>
            <p class="section-desc reveal">Three major tracks, eleven categories — each designed to challenge students at every level.</p>
          </div>
          <a href="#" class="btn-neon-secondary reveal" style="white-space:nowrap;flex-shrink:0;align-self:flex-end;"><i class="fi fi-rr-apps"></i> All Categories</a>
        </div>
        <div class="categories-grid">
          <div class="cat-card prc reveal">
            <div class="cat-img-wrap">
              <img src="assets/highlights/highlight-roboventure.jpg" alt="RoboVenture competition" loading="lazy" />
              <div class="cat-img-overlay"></div>
              <span class="cat-img-badge">8 Sub-categories</span>
            </div>
            <div class="cat-body">
              <div class="cat-logo-row"><img src="assets/Roboventure Logo.png" alt="RoboVenture" class="cat-logo" /></div>
              <h3 class="cat-title">RoboVenture</h3>
              <p class="cat-desc">The flagship multi-category track featuring eight unique events — from robot navigation to creative innovation challenges.</p>
              <div class="cat-subcats">
                <span class="cat-tag">Aspiring Makers</span><span class="cat-tag">Robot Soccer</span>
                <span class="cat-tag">Emerging Innovators</span><span class="cat-tag">Navigation - Auto</span>
                <span class="cat-tag">Line Tracing</span><span class="cat-tag">Sumobot</span>
              </div>
              <a onclick="goRV()" class="cat-link" style="cursor:pointer">Explore <i class="fi fi-rr-arrow-right"></i></a>
            </div>
          </div>
          <div class="cat-card makex reveal reveal-delay-1">
            <div class="cat-img-wrap">
              <img src="assets/highlights/highlight-makex.jpg" alt="MakeX competition" loading="lazy" />
              <div class="cat-img-overlay"></div>
              <span class="cat-img-badge">2 Sub-categories</span>
            </div>
            <div class="cat-body">
              <div class="cat-logo-row"><img src="assets/Makex logo.png" alt="MakeX" class="cat-logo" /></div>
              <h3 class="cat-title">MakeX</h3>
              <p class="cat-desc">An internationally recognized competition. Winners represent the Philippines at the MakeX World Championships in China.</p>
              <div class="cat-subcats"><span class="cat-tag">MakeX Starter</span><span class="cat-tag">MakeX Explorer</span></div>
              <a onclick="goMakeX()" class="cat-link" style="cursor:pointer">Explore <i class="fi fi-rr-arrow-right"></i></a>
            </div>
          </div>
          <div class="cat-card drone reveal reveal-delay-2">
            <div class="cat-img-wrap">
              <img src="assets/highlights/highlight-drone.jpg" alt="Drone Soccer" loading="lazy" />
              <div class="cat-img-overlay"></div>
              <span class="cat-img-badge">1 Category</span>
            </div>
            <div class="cat-body">
              <div class="cat-logo-row"><img src="assets/Drone Soccer Logo.png" alt="Drone Soccer" class="cat-logo" /></div>
              <h3 class="cat-title">Drone Soccer</h3>
              <p class="cat-desc">A thrilling aerial sport combining drone piloting skills with team strategy in a high-energy futuristic competition.</p>
              <div class="cat-subcats"><span class="cat-tag">Drone Soccer</span></div>
              <a onclick="goDrone()" class="cat-link" style="cursor:pointer">Explore <i class="fi fi-rr-arrow-right"></i></a>
            </div>
          </div>
        </div>
      </div>
    </section>

    <!-- VIDEO -->
    <section id="video" aria-labelledby="video-title">
      <div class="video-inner">
        <div class="video-header reveal">
          <div class="section-eyebrow" style="display:inline-flex;">Watch the Action</div>
          <h2 class="section-title" id="video-title" style="margin-top:14px;">See It In <span class="accent">Action</span></h2>
          <p class="section-desc">Experience the excitement, energy, and innovation of the Philippine Robotics Cup.</p>
        </div>
        <div class="video-outer-wrap reveal">
          <div class="video-inner-wrap">
            <div class="video-ratio">
              <iframe src="https://www.youtube.com/embed/fis54XtM3NM?rel=0&modestbranding=1&color=white" title="Philippine Robotics Cup Highlights" allow="accelerometer; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" allowfullscreen loading="lazy"></iframe>
            </div>
          </div>
        </div>
      </div>
    </section>

    <!-- HIGHLIGHTS -->
    <section id="highlights" aria-labelledby="highlights-title">
      <div class="section-inner">
        <div class="highlights-header">
          <div>
            <div class="section-eyebrow reveal-left">Photo Gallery</div>
            <h2 class="section-title reveal" id="highlights-title">Competition<br/><span class="accent">Highlights</span></h2>
          </div>
          <a href="#" class="btn-neon-primary reveal"><i class="fi fi-rr-images"></i> Full Gallery</a>
        </div>
        <div class="highlights-grid">
          <div class="highlight-item reveal"><span class="highlight-num">01</span><img src="assets/highlights/highlight-1.jpg" alt="National Finals floor" class="highlight-img" loading="lazy" /><div class="highlight-overlay"><span class="highlight-caption">Competition Floor — National Finals</span></div></div>
          <div class="highlight-item reveal reveal-delay-1"><span class="highlight-num">02</span><img src="assets/highlights/highlight-2.jpg" alt="Robot assembly" class="highlight-img" loading="lazy" /><div class="highlight-overlay"><span class="highlight-caption">Robot Assembly Challenge</span></div></div>
          <div class="highlight-item reveal reveal-delay-2"><span class="highlight-num">03</span><img src="assets/highlights/highlight-3.jpg" alt="Drone soccer" class="highlight-img" loading="lazy" /><div class="highlight-overlay"><span class="highlight-caption">Drone Soccer — Aerial Battle</span></div></div>
          <div class="highlight-item reveal reveal-delay-1"><span class="highlight-num">04</span><img src="assets/highlights/highlight-4.jpg" alt="Awarding ceremony" class="highlight-img" loading="lazy" /><div class="highlight-overlay"><span class="highlight-caption">Awarding Ceremony — National Champions</span></div></div>
          <div class="highlight-item reveal reveal-delay-2"><span class="highlight-num">05</span><img src="assets/highlights/highlight-5.jpg" alt="MakeX build phase" class="highlight-img" loading="lazy" /><div class="highlight-overlay"><span class="highlight-caption">MakeX Explorer — Build Phase</span></div></div>
        </div>
      </div>
    </section>

    <!-- ORGANIZERS -->
    <section id="organizers" aria-label="Organizers and supporters">
      <div class="organizers-inner">
        <div class="organizers-card reveal">
          <div class="organizers-heading">// Organized &amp; Supported By</div>
          <p class="organizers-sub">The Philippine Robotics Cup is proudly backed by these organizations and government agencies.</p>
          <div class="organizers-row">
            <div class="org-item"><img src="assets/CreoLogo.png" alt="Creotec Philippines" class="org-logo-img" /><div class="org-name">Creotec Philippines Inc.<br/><span class="org-role">Primary Organizer</span></div></div>
            <div class="org-item"><img src="assets/DepED-Logo.png" alt="Department of Education" class="org-logo-img" /><div class="org-name">Dept. of Education<br/><span class="org-role">DepEd</span></div></div>
            <div class="org-item"><img src="assets/DOST-Logo.png" alt="Department of Science and Technology" class="org-logo-img" /><div class="org-name">Dept. of Science &amp; Technology<br/><span class="org-role">DOST</span></div></div>
            <div class="org-item"><img src="assets/DICT-Logo.png" alt="Dept. of ICT" class="org-logo-img" /><div class="org-name">Dept. of ICT<br/><span class="org-role">DICT</span></div></div>
          </div>
        </div>
      </div>
    </section>

    <!-- CTA -->
    <section id="cta" aria-label="Call to action">
      <div class="section-inner">
        <div class="cta-card reveal">
          <div class="cta-bg-grid"></div>
          <div class="cta-corner tl"></div><div class="cta-corner tr"></div><div class="cta-corner bl"></div><div class="cta-corner br"></div>
          <div class="cta-tag"><i class="fi fi-rr-calendar"></i> Registration Open // PRC 2026</div>
          <h2 class="cta-title">Ready to Compete<br/>on the <span class="accent">National Stage?</span></h2>
          <p class="cta-desc">Join thousands of Filipino students in the country's most exciting robotics competition. Register your team today.</p>
          <div class="cta-actions">
            <a onclick="scrollToSection('cta')" class="btn-neon-primary" style="cursor:pointer"><i class="fi fi-rr-pen-field"></i> Register Your Team</a>
            <a onclick="goHome('shop')" class="btn-neon-secondary" style="cursor:pointer"><i class="fi fi-rr-shopping-cart"></i> Order Materials</a>
          </div>
        </div>
      </div>
    </section>

    <!-- FOOTER -->
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
            <div class="social-links">
              <a href="https://www.facebook.com/profile.php?id=61579706372017" target="_blank" rel="noopener" class="social-link" aria-label="Facebook"><i class="fi fi-brands-facebook"></i></a>
            </div>
          </div>
          <nav class="footer-col" aria-label="Competition"><h4>Competition</h4><ul><li><a onclick="scrollToSection('categories')" style="cursor:pointer"><i class="fi fi-rr-angle-right"></i>Categories</a></li><li><a href="#"><i class="fi fi-rr-angle-right"></i>Rules &amp; Guidelines</a></li><li><a href="#"><i class="fi fi-rr-angle-right"></i>Schedule</a></li><li><a href="#"><i class="fi fi-rr-angle-right"></i>Past Events</a></li></ul></nav>
          <nav class="footer-col" aria-label="Participate"><h4>Participate</h4><ul><li><a href="#"><i class="fi fi-rr-angle-right"></i>Register Now</a></li><li><a href="#"><i class="fi fi-rr-angle-right"></i>Order Materials</a></li><li><a href="#"><i class="fi fi-rr-angle-right"></i>FAQ</a></li><li><a href="#"><i class="fi fi-rr-angle-right"></i>Contact Us</a></li></ul></nav>
          <nav class="footer-col" aria-label="Resources"><h4>Resources</h4><ul><li><a href="#"><i class="fi fi-rr-angle-right"></i>News &amp; Updates</a></li><li><a onclick="scrollToSection('highlights')" style="cursor:pointer"><i class="fi fi-rr-angle-right"></i>Gallery</a></li><li><a onclick="scrollToSection('video')" style="cursor:pointer"><i class="fi fi-rr-angle-right"></i>Videos</a></li><li><a href="#"><i class="fi fi-rr-angle-right"></i>Creotec Philippines</a></li></ul></nav>
        </div>
        <div class="footer-bottom">
          <p>&copy; 2026 Philippine Robotics Cup // Creotec Philippines Inc. All rights reserved.</p>
          <div class="footer-bottom-links"><a href="#">Privacy Policy</a><a href="#">Terms of Use</a></div>
        </div>
      </div>
    </footer>

  </div>

  
    </div><!-- /page-home -->

    <!-- ===== PAGE: ROBOVENTURE ===== -->
    <div id="page-roboventure" class="page">
<div class="breadcrumb-bar">
      <div class="breadcrumb-inner">
        <a onclick="goHome()" style="cursor:pointer">Home</a>
        <span class="breadcrumb-sep">›</span>
        <a onclick="goHome('categories')" style="cursor:pointer">Categories</a>
        <span class="breadcrumb-sep">›</span>
        <span class="breadcrumb-current">RoboVenture</span>
      </div>
    </div>

    <!-- ===== PAGE HERO ===== -->
    <section class="page-hero" aria-label="RoboVenture page hero">
      <div class="page-hero-scan" aria-hidden="true"></div>
      <div class="page-hero-inner">

        <div class="page-hero-left">
          <div class="page-hero-logo"><img src="assets/Roboventure Logo.png" alt="RoboVenture" /></div>
          <h1 class="page-hero-title">
            <span class="pht-name" data-text="Robo">Robo</span>
            <span class="pht-sub">Venture</span>
          </h1>
          <p class="page-hero-desc">RoboVenture is the heart of the Philippine Robotics Cup — an eight-category showcase where students demonstrate engineering creativity, programming precision, and competitive grit across a wide range of robotics challenges.</p>
          <div class="hero-meta-row">
            <span class="hero-badge violet"><i class="fi fi-rr-layers"></i> 8 Sub-categories</span>
            <span class="hero-badge volt"><i class="fi fi-rr-school"></i> All School Levels</span>
            <span class="hero-badge sky"><i class="fi fi-rr-trophy"></i> National Finals</span>
          </div>
        </div>

        <div class="page-hero-right">
          <div class="hud-panel">
            <div class="hud-panel-label">Track Overview</div>
            <div class="hud-stat"><span class="hud-stat-num">8</span><span class="hud-stat-label">Sub-categories</span></div>
            <div class="hud-stat"><span class="hud-stat-num">K–12</span><span class="hud-stat-label">Eligible Levels</span></div>
            <div class="hud-divider"></div>
            <div class="hud-list">
              <div class="hud-list-item"><span class="hud-list-dot"></span>Aspiring Makers</div>
              <div class="hud-list-item"><span class="hud-list-dot"></span>Robot Soccer</div>
              <div class="hud-list-item"><span class="hud-list-dot"></span>Emerging Innovators</div>
              <div class="hud-list-item"><span class="hud-list-dot"></span>Navigation – Autonomous</div>
              <div class="hud-list-item"><span class="hud-list-dot"></span>Navigation – Manual</div>
              <div class="hud-list-item"><span class="hud-list-dot"></span>Line Tracing</div>
              <div class="hud-list-item"><span class="hud-list-dot"></span>Sumobot</div>
              <div class="hud-list-item"><span class="hud-list-dot"></span>Innovation Builders</div>
            </div>
          </div>
        </div>

      </div>
    </section>

    <!-- ===== MAIN CONTENT ===== -->
    <main class="main-content">
      <div class="section-divider">
        <div class="section-divider-line"></div>
        <div class="section-divider-label">All 8 Sub-categories</div>
        <div class="section-divider-line right"></div>
      </div>

      <div class="subcats-grid">

        <!-- 01 Aspiring Makers -->
        <div class="subcat-card reveal">
          <div class="corner-tl"></div><div class="corner-br"></div>
          <div class="subcat-header">
            <div class="subcat-icon-wrap"><i class="fi fi-rr-settings"></i></div>
            <div class="subcat-header-text">
              <span class="subcat-num">// 01</span>
              <h2 class="subcat-name">Aspiring Makers</h2>
              <div class="subcat-tags"><span class="subcat-tag">Beginner</span><span class="subcat-tag">Build + Program</span></div>
            </div>
          </div>
          <div class="subcat-body">
            <p class="subcat-desc">Designed for young elementary-level students taking their very first steps into robotics, Aspiring Makers challenges participants to assemble and program a robot to complete simple but meaningful missions. This category is intentionally accessible — nurturing curiosity, problem-solving, and a love for technology from the ground up.</p>
            <div class="subcat-skills-label">Skills Developed</div>
            <div class="subcat-skills">
              <div class="subcat-skill"><span class="skill-dot"></span>Basic robot assembly from structured kits</div>
              <div class="subcat-skill"><span class="skill-dot"></span>Introduction to block-based or visual programming</div>
              <div class="subcat-skill"><span class="skill-dot"></span>Understanding sensors and actuators</div>
              <div class="subcat-skill"><span class="skill-dot"></span>Teamwork and following technical instructions</div>
            </div>
            <div class="subcat-level">
              <span class="level-label">Difficulty</span>
              <div class="level-track"><div class="level-fill" style="width:0%" data-width="20%"></div></div>
              <span class="level-pct">Beginner</span>
            </div>
            <a href="#" class="subcat-cta">Learn More <i class="fi fi-rr-arrow-right"></i></a>
          </div>
        </div>

        <!-- 02 Robot Soccer -->
        <div class="subcat-card reveal reveal-delay-1">
          <div class="corner-tl"></div><div class="corner-br"></div>
          <div class="subcat-header">
            <div class="subcat-icon-wrap"><i class="fi fi-rr-ball"></i></div>
            <div class="subcat-header-text">
              <span class="subcat-num">// 02</span>
              <h2 class="subcat-name">Robot Soccer</h2>
              <div class="subcat-tags"><span class="subcat-tag">Team-Based</span><span class="subcat-tag">Strategy + Speed</span></div>
            </div>
          </div>
          <div class="subcat-body">
            <p class="subcat-desc">Two teams of autonomous or remote-controlled robots face off on a miniature soccer field — the objective is simple, the execution is anything but. Robot Soccer demands fast mechanical design, responsive programming, and real-time tactical thinking as teams compete to outscore each other within a fixed match time.</p>
            <div class="subcat-skills-label">Skills Developed</div>
            <div class="subcat-skills">
              <div class="subcat-skill"><span class="skill-dot"></span>Mechanical drive system design for speed and agility</div>
              <div class="subcat-skill"><span class="skill-dot"></span>Real-time robot control and motor tuning</div>
              <div class="subcat-skill"><span class="skill-dot"></span>Game strategy and in-match decision making</div>
              <div class="subcat-skill"><span class="skill-dot"></span>Ball detection using IR or vision sensors</div>
            </div>
            <div class="subcat-level">
              <span class="level-label">Difficulty</span>
              <div class="level-track"><div class="level-fill" style="width:0%" data-width="55%"></div></div>
              <span class="level-pct">Intermediate</span>
            </div>
            <a href="#" class="subcat-cta">Learn More <i class="fi fi-rr-arrow-right"></i></a>
          </div>
        </div>

        <!-- 03 Emerging Innovators -->
        <div class="subcat-card reveal">
          <div class="corner-tl"></div><div class="corner-br"></div>
          <div class="subcat-header">
            <div class="subcat-icon-wrap"><i class="fi fi-rr-bulb"></i></div>
            <div class="subcat-header-text">
              <span class="subcat-num">// 03</span>
              <h2 class="subcat-name">Emerging Innovators</h2>
              <div class="subcat-tags"><span class="subcat-tag">Open Design</span><span class="subcat-tag">Innovation</span></div>
            </div>
          </div>
          <div class="subcat-body">
            <p class="subcat-desc">A creative engineering challenge where junior high school students design, build, and present a robot that addresses a specific real-world problem or theme. Teams are judged not just on robot performance, but on the originality of their concept, quality of engineering, and how clearly they can communicate their solution to a panel of judges.</p>
            <div class="subcat-skills-label">Skills Developed</div>
            <div class="subcat-skills">
              <div class="subcat-skill"><span class="skill-dot"></span>Open-ended engineering design and prototyping</div>
              <div class="subcat-skill"><span class="skill-dot"></span>Identifying and solving real-world problems with technology</div>
              <div class="subcat-skill"><span class="skill-dot"></span>Technical presentation and documentation skills</div>
              <div class="subcat-skill"><span class="skill-dot"></span>Iteration and testing under time constraints</div>
            </div>
            <div class="subcat-level">
              <span class="level-label">Difficulty</span>
              <div class="level-track"><div class="level-fill" style="width:0%" data-width="50%"></div></div>
              <span class="level-pct">Intermediate</span>
            </div>
            <a href="#" class="subcat-cta">Learn More <i class="fi fi-rr-arrow-right"></i></a>
          </div>
        </div>

        <!-- 04 Navigation – Autonomous -->
        <div class="subcat-card reveal reveal-delay-1">
          <div class="corner-tl"></div><div class="corner-br"></div>
          <div class="subcat-header">
            <div class="subcat-icon-wrap"><i class="fi fi-rr-route"></i></div>
            <div class="subcat-header-text">
              <span class="subcat-num">// 04</span>
              <h2 class="subcat-name">Navigation – Autonomous</h2>
              <div class="subcat-tags"><span class="subcat-tag">Autonomous</span><span class="subcat-tag">Sensors</span></div>
            </div>
          </div>
          <div class="subcat-body">
            <p class="subcat-desc">Participants program a robot to navigate a complex obstacle course entirely on its own — no human control allowed after the start signal. The robot must use onboard sensors to detect walls, avoid obstacles, and reach the finish zone accurately. Precision coding and smart sensor integration are the keys to victory.</p>
            <div class="subcat-skills-label">Skills Developed</div>
            <div class="subcat-skills">
              <div class="subcat-skill"><span class="skill-dot"></span>Autonomous programming logic and state machines</div>
              <div class="subcat-skill"><span class="skill-dot"></span>Ultrasonic, infrared, and proximity sensor integration</div>
              <div class="subcat-skill"><span class="skill-dot"></span>PID control for motor precision and straight-line travel</div>
              <div class="subcat-skill"><span class="skill-dot"></span>Debugging and field calibration under competition conditions</div>
            </div>
            <div class="subcat-level">
              <span class="level-label">Difficulty</span>
              <div class="level-track"><div class="level-fill" style="width:0%" data-width="75%"></div></div>
              <span class="level-pct">Advanced</span>
            </div>
            <a href="#" class="subcat-cta">Learn More <i class="fi fi-rr-arrow-right"></i></a>
          </div>
        </div>

        <!-- 05 Navigation – Manual -->
        <div class="subcat-card reveal">
          <div class="corner-tl"></div><div class="corner-br"></div>
          <div class="subcat-header">
            <div class="subcat-icon-wrap"><i class="fi fi-rr-gamepad"></i></div>
            <div class="subcat-header-text">
              <span class="subcat-num">// 05</span>
              <h2 class="subcat-name">Navigation – Manual</h2>
              <div class="subcat-tags"><span class="subcat-tag">Remote Control</span><span class="subcat-tag">Piloting Skill</span></div>
            </div>
          </div>
          <div class="subcat-body">
            <p class="subcat-desc">In this category, the driver takes full command. Competitors remotely pilot their robot through a timed obstacle course, showcasing hand-eye coordination, mechanical understanding, and precision control. A great entry point for students who excel at hands-on robot operation — where chassis design and driver skill combine to determine the winner.</p>
            <div class="subcat-skills-label">Skills Developed</div>
            <div class="subcat-skills">
              <div class="subcat-skill"><span class="skill-dot"></span>Remote control system wiring and configuration</div>
              <div class="subcat-skill"><span class="skill-dot"></span>Fine motor control and spatial awareness under pressure</div>
              <div class="subcat-skill"><span class="skill-dot"></span>Chassis design optimized for precise maneuverability</div>
              <div class="subcat-skill"><span class="skill-dot"></span>Time management and efficient path planning</div>
            </div>
            <div class="subcat-level">
              <span class="level-label">Difficulty</span>
              <div class="level-track"><div class="level-fill" style="width:0%" data-width="38%"></div></div>
              <span class="level-pct">Beginner–Mid</span>
            </div>
            <a href="#" class="subcat-cta">Learn More <i class="fi fi-rr-arrow-right"></i></a>
          </div>
        </div>

        <!-- 06 Line Tracing -->
        <div class="subcat-card reveal reveal-delay-1">
          <div class="corner-tl"></div><div class="corner-br"></div>
          <div class="subcat-header">
            <div class="subcat-icon-wrap"><i class="fi fi-rr-following"></i></div>
            <div class="subcat-header-text">
              <span class="subcat-num">// 06</span>
              <h2 class="subcat-name">Line Tracing</h2>
              <div class="subcat-tags"><span class="subcat-tag">Autonomous</span><span class="subcat-tag">Speed + Accuracy</span></div>
            </div>
          </div>
          <div class="subcat-body">
            <p class="subcat-desc">A classic and beloved robotics challenge — participants build a robot that autonomously follows a black line on a white track surface, navigating through curves, intersections, and speed variations as fast as possible. Deceptively simple in concept, Line Tracing rewards deep understanding of sensor feedback loops and motor control tuning.</p>
            <div class="subcat-skills-label">Skills Developed</div>
            <div class="subcat-skills">
              <div class="subcat-skill"><span class="skill-dot"></span>Reflective (IR) sensor arrays and threshold calibration</div>
              <div class="subcat-skill"><span class="skill-dot"></span>PID and proportional control for smooth line following</div>
              <div class="subcat-skill"><span class="skill-dot"></span>Speed vs. accuracy trade-off optimization</div>
              <div class="subcat-skill"><span class="skill-dot"></span>Robot chassis design for stable high-speed travel</div>
            </div>
            <div class="subcat-level">
              <span class="level-label">Difficulty</span>
              <div class="level-track"><div class="level-fill" style="width:0%" data-width="62%"></div></div>
              <span class="level-pct">Intermediate</span>
            </div>
            <a href="#" class="subcat-cta">Learn More <i class="fi fi-rr-arrow-right"></i></a>
          </div>
        </div>

        <!-- 07 Sumobot -->
        <div class="subcat-card reveal">
          <div class="corner-tl"></div><div class="corner-br"></div>
          <div class="subcat-header">
            <div class="subcat-icon-wrap"><i class="fi fi-rr-swords"></i></div>
            <div class="subcat-header-text">
              <span class="subcat-num">// 07</span>
              <h2 class="subcat-name">Sumobot</h2>
              <div class="subcat-tags"><span class="subcat-tag">Combat</span><span class="subcat-tag">Autonomous / RC</span></div>
            </div>
          </div>
          <div class="subcat-body">
            <p class="subcat-desc">The crowd favourite. Two robots enter a circular dohyo arena; only one can remain. Sumobot competitors must build the most powerful, lowest-profile, and strategically designed robot that can outpush its opponent out of bounds. Both autonomous and remote-controlled configurations are supported, making this category electrifying to watch and deeply technical to master.</p>
            <div class="subcat-skills-label">Skills Developed</div>
            <div class="subcat-skills">
              <div class="subcat-skill"><span class="skill-dot"></span>Structural rigidity and low centre-of-gravity chassis design</div>
              <div class="subcat-skill"><span class="skill-dot"></span>High-torque motor selection and drive train engineering</div>
              <div class="subcat-skill"><span class="skill-dot"></span>Opponent detection using IR and ultrasonic sensors</div>
              <div class="subcat-skill"><span class="skill-dot"></span>Combat strategy — aggression, edge sensing, and positioning</div>
            </div>
            <div class="subcat-level">
              <span class="level-label">Difficulty</span>
              <div class="level-track"><div class="level-fill" style="width:0%" data-width="70%"></div></div>
              <span class="level-pct">Advanced</span>
            </div>
            <a href="#" class="subcat-cta">Learn More <i class="fi fi-rr-arrow-right"></i></a>
          </div>
        </div>

        <!-- 08 Innovation Builders -->
        <div class="subcat-card reveal reveal-delay-1">
          <div class="corner-tl"></div><div class="corner-br"></div>
          <div class="subcat-header">
            <div class="subcat-icon-wrap"><i class="fi fi-rr-rocket"></i></div>
            <div class="subcat-header-text">
              <span class="subcat-num">// 08</span>
              <h2 class="subcat-name">Innovation Builders</h2>
              <div class="subcat-tags"><span class="subcat-tag">Open Theme</span><span class="subcat-tag">Design + Present</span></div>
            </div>
          </div>
          <div class="subcat-body">
            <p class="subcat-desc">The most forward-thinking category at RoboVenture — teams are given a real-world challenge theme and must engineer a working robotic solution that addresses it meaningfully. Equal weight is placed on functionality, innovation, build quality, and the team's ability to present and defend their creation before a panel of judges. This is where future engineers and tech leaders are discovered.</p>
            <div class="subcat-skills-label">Skills Developed</div>
            <div class="subcat-skills">
              <div class="subcat-skill"><span class="skill-dot"></span>Human-centred design thinking and real-world problem framing</div>
              <div class="subcat-skill"><span class="skill-dot"></span>Multi-disciplinary build — mechanics, electronics, and code</div>
              <div class="subcat-skill"><span class="skill-dot"></span>Public speaking, project documentation, and pitching skills</div>
              <div class="subcat-skill"><span class="skill-dot"></span>Rapid prototyping and iteration under competition time limits</div>
            </div>
            <div class="subcat-level">
              <span class="level-label">Difficulty</span>
              <div class="level-track"><div class="level-fill" style="width:0%" data-width="85%"></div></div>
              <span class="level-pct">Expert</span>
            </div>
            <a href="#" class="subcat-cta">Learn More <i class="fi fi-rr-arrow-right"></i></a>
          </div>
        </div>

      </div><!-- /subcats-grid -->

      <!-- CTA -->
      <div class="page-cta-wrap reveal">
        <div class="page-cta">
          <div class="cta-bg-grid"></div>
          <div class="cta-corner tl"></div>
          <div class="cta-corner tr"></div>
          <div class="cta-corner bl"></div>
          <div class="cta-corner br"></div>
          <div class="cta-tag"><i class="fi fi-rr-calendar"></i> Registration Open // PRC 2026</div>
          <h2 class="cta-title">Found Your<br/><span class="accent">RoboVenture Category?</span></h2>
          <p class="cta-desc">Register your team now and compete at the Philippine Robotics Cup 2026 — October, Vista Mall Las Pi&ntilde;as, Metro Manila.</p>
          <div class="cta-actions">
            <a href="#" class="btn-neon-primary"><i class="fi fi-rr-pen-field"></i> Register Your Team</a>
            <a onclick="goHome('categories')" class="btn-neon-secondary" style="cursor:pointer"><i class="fi fi-rr-arrow-left"></i> All Categories</a>
          </div>
        </div>
      </div>

    </main>

    <!-- ===== FOOTER ===== -->
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
            <div class="social-links">
              <a href="https://www.facebook.com/profile.php?id=61579706372017" target="_blank" rel="noopener" class="social-link" aria-label="Facebook"><i class="fi fi-brands-facebook"></i></a>
            </div>
          </div>
          <nav class="footer-col" aria-label="Competition"><h4>Competition</h4><ul><li><a onclick="goHome('categories')" style="cursor:pointer"><i class="fi fi-rr-angle-right"></i>Categories</a></li><li><a href="#"><i class="fi fi-rr-angle-right"></i>Rules &amp; Guidelines</a></li><li><a href="#"><i class="fi fi-rr-angle-right"></i>Schedule</a></li><li><a href="#"><i class="fi fi-rr-angle-right"></i>Past Events</a></li></ul></nav>
          <nav class="footer-col" aria-label="Participate"><h4>Participate</h4><ul><li><a href="#"><i class="fi fi-rr-angle-right"></i>Register Now</a></li><li><a href="#"><i class="fi fi-rr-angle-right"></i>Order Materials</a></li><li><a href="#"><i class="fi fi-rr-angle-right"></i>FAQ</a></li><li><a href="#"><i class="fi fi-rr-angle-right"></i>Contact Us</a></li></ul></nav>
          <nav class="footer-col" aria-label="Resources"><h4>Resources</h4><ul><li><a href="#"><i class="fi fi-rr-angle-right"></i>News &amp; Updates</a></li><li><a onclick="goHome('highlights')" style="cursor:pointer"><i class="fi fi-rr-angle-right"></i>Gallery</a></li><li><a onclick="goHome('video')" style="cursor:pointer"><i class="fi fi-rr-angle-right"></i>Videos</a></li><li><a href="#"><i class="fi fi-rr-angle-right"></i>Creotec Philippines</a></li></ul></nav>
        </div>
        <div class="footer-bottom">
          <p>&copy; 2026 Philippine Robotics Cup // Creotec Philippines Inc. All rights reserved.</p>
          <div class="footer-bottom-links"><a href="#">Privacy Policy</a><a href="#">Terms of Use</a></div>
        </div>
      </div>
    </footer>
    </div><!-- /page-roboventure -->
    <!-- ===== PAGE: MAKEX ===== -->
    <div id="page-makex" class="page">

      <!-- BREADCRUMB -->
      <div class="breadcrumb-bar">
        <div class="breadcrumb-inner">
          <a onclick="goHome()" style="cursor:pointer">Home</a>
          <span class="breadcrumb-sep">›</span>
          <a onclick="goHome('categories')" style="cursor:pointer">Categories</a>
          <span class="breadcrumb-sep">›</span>
          <span class="breadcrumb-current">MakeX</span>
        </div>
      </div>

      <!-- PAGE HERO -->
      <section class="page-hero" aria-label="MakeX page hero">
        <div class="page-hero-scan" aria-hidden="true"></div>
        <div class="page-hero-inner">
          <div class="page-hero-left">
            <div class="page-hero-logo"><img src="assets/Makex logo.png" alt="MakeX" style="filter:drop-shadow(0 0 12px rgba(68,217,255,0.55));" /></div>
            <h1 class="page-hero-title">
              <span class="pht-name" data-text="Make" style="background:linear-gradient(90deg,var(--creo-sky),var(--neon-magenta));-webkit-background-clip:text;-webkit-text-fill-color:transparent;background-clip:text;text-shadow:none;filter:drop-shadow(0 0 14px rgba(68,217,255,0.55));">Make</span>
              <span class="pht-sub" style="background:linear-gradient(90deg,var(--creo-sky),var(--neon-magenta));-webkit-background-clip:text;-webkit-text-fill-color:transparent;background-clip:text;filter:drop-shadow(0 0 14px rgba(68,217,255,0.55));">X</span>
            </h1>
            <p class="page-hero-desc">MakeX is an internationally recognized robotics competition where winners earn the right to represent the Philippines at the MakeX World Championships in China — one of the most prestigious robotics stages in Asia.</p>
            <div class="hero-meta-row">
              <span class="hero-badge" style="color:var(--creo-sky);border-color:rgba(68,217,255,0.40);background:rgba(68,217,255,0.06)"><i class="fi fi-rr-layers"></i> 2 Sub-categories</span>
              <span class="hero-badge volt"><i class="fi fi-rr-globe"></i> World Championships Qualifier</span>
              <span class="hero-badge" style="color:var(--neon-magenta);border-color:rgba(204,85,255,0.35);background:rgba(204,85,255,0.05)"><i class="fi fi-rr-trophy"></i> National Finals</span>
            </div>
          </div>
          <div class="page-hero-right">
            <div class="hud-panel">
              <div class="hud-panel-label">Track Overview</div>
              <div class="hud-stat"><span class="hud-stat-num" style="color:var(--creo-sky);text-shadow:0 0 18px rgba(68,217,255,0.65)">2</span><span class="hud-stat-label">Sub-categories</span></div>
              <div class="hud-stat"><span class="hud-stat-num" style="color:var(--creo-sky);text-shadow:0 0 18px rgba(68,217,255,0.65)">China</span><span class="hud-stat-label">World Finals Venue</span></div>
              <div class="hud-divider"></div>
              <div class="hud-list">
                <div class="hud-list-item"><span class="hud-list-dot" style="background:var(--creo-sky);box-shadow:0 0 6px rgba(68,217,255,0.60)"></span>MakeX Starter</div>
                <div class="hud-list-item"><span class="hud-list-dot" style="background:var(--neon-magenta);box-shadow:0 0 6px rgba(204,85,255,0.60)"></span>MakeX Explorer</div>
              </div>
            </div>
          </div>
        </div>
      </section>

      <!-- MAIN CONTENT -->
      <main class="main-content">
        <div class="section-divider">
          <div class="section-divider-line"></div>
          <div class="section-divider-label">All 2 Sub-categories</div>
          <div class="section-divider-line right"></div>
        </div>

        <div class="subcats-grid">

          <!-- MakeX Starter -->
          <div class="subcat-card makex-card reveal">
            <div class="corner-tl"></div><div class="corner-br"></div>
            <div class="subcat-header">
              <div class="subcat-icon-wrap makex-icon"><i class="fi fi-rr-star"></i></div>
              <div class="subcat-header-text">
                <span class="subcat-num">// 01</span>
                <h2 class="subcat-name makex-name">MakeX Starter</h2>
                <div class="subcat-tags">
                  <span class="subcat-tag makex-tag">Entry Level</span>
                  <span class="subcat-tag makex-tag">Mission-Based</span>
                  <span class="subcat-tag makex-tag">Build + Code</span>
                </div>
              </div>
            </div>
            <div class="subcat-body">
              <p class="subcat-desc">MakeX Starter is the entry point into the MakeX international ecosystem, designed for younger or less-experienced students. Teams build and program a robot to complete a series of structured missions on a themed game field. Emphasis is placed on foundational coding, mechanical design, and teamwork — the perfect launchpad for future world competitors.</p>
              <div class="subcat-skills-label makex-skills-label">Skills Developed</div>
              <div class="subcat-skills">
                <div class="subcat-skill"><span class="skill-dot makex-dot"></span>Structured robot assembly and kit-based engineering</div>
                <div class="subcat-skill"><span class="skill-dot makex-dot"></span>Block-based and introductory text programming</div>
                <div class="subcat-skill"><span class="skill-dot makex-dot"></span>Mission planning and sequential task execution</div>
                <div class="subcat-skill"><span class="skill-dot makex-dot"></span>Teamwork, communication, and basic troubleshooting</div>
              </div>
              <div class="subcat-level">
                <span class="level-label">Difficulty</span>
                <div class="level-track"><div class="level-fill makex-fill" style="width:0%" data-width="30%"></div></div>
                <span class="level-pct">Beginner</span>
              </div>
              <button class="subcat-cta makex-cta">Learn More <i class="fi fi-rr-arrow-right"></i></button>
            </div>
          </div>

          <!-- MakeX Explorer -->
          <div class="subcat-card makex-card reveal reveal-delay-1">
            <div class="corner-tl"></div><div class="corner-br"></div>
            <div class="subcat-header">
              <div class="subcat-icon-wrap makex-icon" style="background:rgba(204,85,255,0.10);border-color:rgba(204,85,255,0.28);color:var(--neon-magenta)"><i class="fi fi-rr-rocket"></i></div>
              <div class="subcat-header-text">
                <span class="subcat-num">// 02</span>
                <h2 class="subcat-name makex-name">MakeX Explorer</h2>
                <div class="subcat-tags">
                  <span class="subcat-tag makex-tag-m">Advanced</span>
                  <span class="subcat-tag makex-tag-m">Open Strategy</span>
                  <span class="subcat-tag makex-tag-m">World Qualifier</span>
                </div>
              </div>
            </div>
            <div class="subcat-body">
              <p class="subcat-desc">MakeX Explorer is the flagship international-level category — the direct qualifier for the MakeX World Championships in China. Teams design and program a robot to tackle a complex, open-strategy game field with multiple objectives and scoring paths. Advanced sensor use, optimized autonomous sequences, and sharp in-match strategy are what separate the champions from the rest.</p>
              <div class="subcat-skills-label makex-skills-label-m">Skills Developed</div>
              <div class="subcat-skills">
                <div class="subcat-skill"><span class="skill-dot makex-dot-m"></span>Advanced autonomous programming and path optimization</div>
                <div class="subcat-skill"><span class="skill-dot makex-dot-m"></span>Multi-sensor fusion and real-time decision logic</div>
                <div class="subcat-skill"><span class="skill-dot makex-dot-m"></span>Game strategy analysis and dynamic scoring decisions</div>
                <div class="subcat-skill"><span class="skill-dot makex-dot-m"></span>International competition preparation and pressure management</div>
              </div>
              <div class="subcat-level">
                <span class="level-label">Difficulty</span>
                <div class="level-track"><div class="level-fill makex-fill-m" style="width:0%" data-width="88%"></div></div>
                <span class="level-pct">Expert</span>
              </div>
              <button class="subcat-cta makex-cta-m">Learn More <i class="fi fi-rr-arrow-right"></i></button>
            </div>
          </div>

        </div><!-- /subcats-grid -->

        <!-- CTA -->
        <div class="page-cta-wrap reveal">
          <div class="page-cta">
            <div class="cta-bg-grid"></div>
            <div class="cta-corner tl"></div><div class="cta-corner tr"></div>
            <div class="cta-corner bl"></div><div class="cta-corner br"></div>
            <div class="cta-tag"><i class="fi fi-rr-calendar"></i> Registration Open // PRC 2026</div>
            <h2 class="cta-title">Ready to Compete<br/>on the <span class="accent" style="color:var(--creo-sky);text-shadow:0 0 18px rgba(68,217,255,0.70)">World Stage?</span></h2>
            <p class="cta-desc">Register your team for MakeX at PRC 2026 and earn your chance to represent the Philippines at the MakeX World Championships in China.</p>
            <div class="cta-actions">
              <button class="btn-neon-primary"><i class="fi fi-rr-pen-field"></i> Register Your Team</button>
              <button class="btn-neon-secondary" onclick="goHome('categories')"><i class="fi fi-rr-arrow-left"></i> All Categories</button>
            </div>
          </div>
        </div>

      </main>

      <!-- FOOTER -->
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
              <div class="social-links">
                <a href="https://www.facebook.com/profile.php?id=61579706372017" target="_blank" rel="noopener" class="social-link" aria-label="Facebook"><i class="fi fi-brands-facebook"></i></a>
              </div>
            </div>
            <nav class="footer-col" aria-label="Competition"><h4>Competition</h4><ul><li><a onclick="goHome('categories')" style="cursor:pointer"><i class="fi fi-rr-angle-right"></i>Categories</a></li><li><a href="#"><i class="fi fi-rr-angle-right"></i>Rules &amp; Guidelines</a></li><li><a href="#"><i class="fi fi-rr-angle-right"></i>Schedule</a></li><li><a href="#"><i class="fi fi-rr-angle-right"></i>Past Events</a></li></ul></nav>
            <nav class="footer-col" aria-label="Participate"><h4>Participate</h4><ul><li><a href="#"><i class="fi fi-rr-angle-right"></i>Register Now</a></li><li><a href="#"><i class="fi fi-rr-angle-right"></i>Order Materials</a></li><li><a href="#"><i class="fi fi-rr-angle-right"></i>FAQ</a></li><li><a href="#"><i class="fi fi-rr-angle-right"></i>Contact Us</a></li></ul></nav>
            <nav class="footer-col" aria-label="Resources"><h4>Resources</h4><ul><li><a href="#"><i class="fi fi-rr-angle-right"></i>News &amp; Updates</a></li><li><a onclick="goHome('highlights')" style="cursor:pointer"><i class="fi fi-rr-angle-right"></i>Gallery</a></li><li><a onclick="goHome('video')" style="cursor:pointer"><i class="fi fi-rr-angle-right"></i>Videos</a></li><li><a href="#"><i class="fi fi-rr-angle-right"></i>Creotec Philippines</a></li></ul></nav>
          </div>
          <div class="footer-bottom">
            <p>&copy; 2026 Philippine Robotics Cup // Creotec Philippines Inc. All rights reserved.</p>
            <div class="footer-bottom-links"><a href="#">Privacy Policy</a><a href="#">Terms of Use</a></div>
          </div>
        </div>
      </footer>

    </div><!-- /page-makex -->
    <!-- ===== PAGE: DRONE SOCCER ===== -->
    <div id="page-drone" class="page">

      <!-- BREADCRUMB -->
      <div class="breadcrumb-bar">
        <div class="breadcrumb-inner">
          <a onclick="goHome()" style="cursor:pointer">Home</a>
          <span class="breadcrumb-sep">›</span>
          <a onclick="goHome('categories')" style="cursor:pointer">Categories</a>
          <span class="breadcrumb-sep">›</span>
          <span class="breadcrumb-current">Drone Soccer</span>
        </div>
      </div>

      <!-- PAGE HERO -->
      <section class="page-hero" aria-label="Drone Soccer page hero">
        <div class="page-hero-scan" aria-hidden="true"></div>
        <div class="page-hero-inner">
          <div class="page-hero-left">
            <div class="page-hero-logo"><img src="assets/Drone Soccer Logo.png" alt="Drone Soccer" style="filter:drop-shadow(0 0 12px rgba(255,160,48,0.55));" /></div>
            <h1 class="page-hero-title">
              <span class="pht-name" data-text="Drone" style="text-shadow:0 0 40px rgba(255,160,48,0.45),0 4px 0 rgba(0,0,0,0.90);">Drone</span>
              <span class="pht-sub" style="background:linear-gradient(90deg,var(--creo-amber),var(--creo-volt));-webkit-background-clip:text;-webkit-text-fill-color:transparent;background-clip:text;filter:drop-shadow(0 0 14px rgba(255,160,48,0.55));">Soccer</span>
            </h1>
            <p class="page-hero-desc">Drone Soccer is a high-octane aerial sport where teams of pilots fly encaged drones to score goals on a three-dimensional field. It's the perfect fusion of engineering, piloting precision, and competitive team strategy — unlike anything else at PRC.</p>
            <div class="hero-meta-row">
              <span class="hero-badge" style="color:var(--creo-amber);border-color:rgba(255,160,48,0.40);background:rgba(255,160,48,0.06)"><i class="fi fi-rr-drone"></i> 1 Category</span>
              <span class="hero-badge volt"><i class="fi fi-rr-users"></i> Team-Based</span>
              <span class="hero-badge" style="color:var(--creo-amber);border-color:rgba(255,160,48,0.35);background:rgba(255,160,48,0.05)"><i class="fi fi-rr-trophy"></i> National Finals</span>
            </div>
          </div>
          <div class="page-hero-right">
            <div class="hud-panel">
              <div class="hud-panel-label">Track Overview</div>
              <div class="hud-stat"><span class="hud-stat-num" style="color:var(--creo-amber);text-shadow:0 0 18px rgba(255,160,48,0.65)">1</span><span class="hud-stat-label">Category</span></div>
              <div class="hud-divider"></div>
              <div class="hud-list">
                <div class="hud-list-item"><span class="hud-list-dot" style="background:var(--creo-amber);box-shadow:0 0 6px rgba(255,160,48,0.60)"></span>Drone Soccer</div>
                <div class="hud-list-item"><span class="hud-list-dot" style="background:var(--creo-volt);box-shadow:0 0 6px rgba(255,233,48,0.60)"></span>FPV-Style Aerial Combat</div>
                <div class="hud-list-item"><span class="hud-list-dot" style="background:var(--creo-amber);box-shadow:0 0 6px rgba(255,160,48,0.60)"></span>Encaged Safety Drones</div>
              </div>
            </div>
          </div>
        </div>
      </section>

      <!-- MAIN CONTENT -->
      <main class="main-content">
        <div class="section-divider">
          <div class="section-divider-line"></div>
          <div class="section-divider-label">Category Overview</div>
          <div class="section-divider-line right"></div>
        </div>

        <div class="subcats-grid" style="grid-template-columns:1fr;">

          <!-- Drone Soccer -->
          <div class="subcat-card drone-card reveal">
            <div class="corner-tl"></div><div class="corner-br"></div>
            <div class="subcat-header">
              <div class="subcat-icon-wrap drone-icon"><i class="fi fi-rr-drone"></i></div>
              <div class="subcat-header-text">
                <span class="subcat-num">// 01</span>
                <h2 class="subcat-name drone-name">Drone Soccer</h2>
                <div class="subcat-tags">
                  <span class="subcat-tag drone-tag">Team Sport</span>
                  <span class="subcat-tag drone-tag">FPV Piloting</span>
                  <span class="subcat-tag drone-tag">Encaged Drones</span>
                </div>
              </div>
            </div>
            <div class="subcat-body">
              <p class="subcat-desc">Drone Soccer is a thrilling aerial team sport where two teams of three pilots compete to fly their encaged drones through a goal ring suspended in a 3D arena. Each drone is housed inside a protective spherical cage, making collisions part of the game strategy. Matches are fast, physical, and technically demanding — requiring sharp reflexes, tight formation flying, and coordinated team tactics to dominate the air and outscore the opposition.</p>
              <div class="subcat-skills-label drone-skills-label">Skills Developed</div>
              <div class="subcat-skills">
                <div class="subcat-skill"><span class="skill-dot drone-dot"></span>FPV and line-of-sight drone piloting and throttle control</div>
                <div class="subcat-skill"><span class="skill-dot drone-dot"></span>Spatial awareness and 3D aerial navigation under pressure</div>
                <div class="subcat-skill"><span class="skill-dot drone-dot"></span>Team coordination, formations, and real-time communication</div>
                <div class="subcat-skill"><span class="skill-dot drone-dot"></span>Drone maintenance, basic electronics, and pre-match setup</div>
                <div class="subcat-skill"><span class="skill-dot drone-dot"></span>Competitive strategy — offense, defense, and aerial blocking</div>
              </div>
              <div class="subcat-level">
                <span class="level-label">Difficulty</span>
                <div class="level-track"><div class="level-fill drone-fill" style="width:0%" data-width="78%"></div></div>
                <span class="level-pct">Advanced</span>
              </div>
              <button class="subcat-cta drone-cta">Learn More <i class="fi fi-rr-arrow-right"></i></button>
            </div>
          </div>

        </div><!-- /subcats-grid -->

        <!-- WHAT MAKES IT UNIQUE -->
        <div class="section-divider" style="margin-top:60px;">
          <div class="section-divider-line"></div>
          <div class="section-divider-label">What Makes It Unique</div>
          <div class="section-divider-line right"></div>
        </div>

        <div style="display:grid;grid-template-columns:repeat(3,1fr);gap:16px;margin-bottom:80px;" class="drone-features-grid">
          <div class="about-feature reveal" style="border-left-color:var(--creo-amber);">
            <div class="about-feature-icon" style="background:rgba(255,160,48,0.10);border-color:rgba(255,160,48,0.24);color:var(--creo-amber)"><i class="fi fi-rr-shield-check"></i></div>
            <div class="about-feature-text"><h4>Encaged Safety Design</h4><p>All drones fly inside a spherical protective cage — enabling physical contact and aerial battles while keeping pilots and spectators safe.</p></div>
          </div>
          <div class="about-feature reveal reveal-delay-1" style="border-left-color:var(--creo-amber);">
            <div class="about-feature-icon" style="background:rgba(255,160,48,0.10);border-color:rgba(255,160,48,0.24);color:var(--creo-amber)"><i class="fi fi-rr-users"></i></div>
            <div class="about-feature-text"><h4>True Team Sport</h4><p>Three pilots per team must fly in concert — covering offense, defense, and blocking roles simultaneously throughout each timed match.</p></div>
          </div>
          <div class="about-feature reveal reveal-delay-2" style="border-left-color:var(--creo-amber);">
            <div class="about-feature-icon" style="background:rgba(255,160,48,0.10);border-color:rgba(255,160,48,0.24);color:var(--creo-amber)"><i class="fi fi-rr-globe"></i></div>
            <div class="about-feature-text"><h4>Growing Global Sport</h4><p>Drone Soccer is recognized internationally with world-level competitions — students who excel here join one of the fastest-growing tech sports on the planet.</p></div>
          </div>
        </div>

        <!-- CTA -->
        <div class="page-cta-wrap reveal">
          <div class="page-cta">
            <div class="cta-bg-grid"></div>
            <div class="cta-corner tl"></div><div class="cta-corner tr"></div>
            <div class="cta-corner bl"></div><div class="cta-corner br"></div>
            <div class="cta-tag"><i class="fi fi-rr-calendar"></i> Registration Open // PRC 2026</div>
            <h2 class="cta-title">Ready to Take<br/><span class="accent" style="color:var(--creo-amber);text-shadow:0 0 18px rgba(255,160,48,0.70)">Flight?</span></h2>
            <p class="cta-desc">Register your Drone Soccer team for PRC 2026 and compete in the most electrifying aerial sport at the national stage.</p>
            <div class="cta-actions">
              <button class="btn-neon-primary"><i class="fi fi-rr-pen-field"></i> Register Your Team</button>
              <button class="btn-neon-secondary" onclick="goHome('categories')"><i class="fi fi-rr-arrow-left"></i> All Categories</button>
            </div>
          </div>
        </div>

      </main>

      <!-- FOOTER -->
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
              <div class="social-links">
                <a href="https://www.facebook.com/profile.php?id=61579706372017" target="_blank" rel="noopener" class="social-link" aria-label="Facebook"><i class="fi fi-brands-facebook"></i></a>
              </div>
            </div>
            <nav class="footer-col" aria-label="Competition"><h4>Competition</h4><ul><li><a onclick="goHome('categories')" style="cursor:pointer"><i class="fi fi-rr-angle-right"></i>Categories</a></li><li><a href="#"><i class="fi fi-rr-angle-right"></i>Rules &amp; Guidelines</a></li><li><a href="#"><i class="fi fi-rr-angle-right"></i>Schedule</a></li><li><a href="#"><i class="fi fi-rr-angle-right"></i>Past Events</a></li></ul></nav>
            <nav class="footer-col" aria-label="Participate"><h4>Participate</h4><ul><li><a href="#"><i class="fi fi-rr-angle-right"></i>Register Now</a></li><li><a href="#"><i class="fi fi-rr-angle-right"></i>Order Materials</a></li><li><a href="#"><i class="fi fi-rr-angle-right"></i>FAQ</a></li><li><a href="#"><i class="fi fi-rr-angle-right"></i>Contact Us</a></li></ul></nav>
            <nav class="footer-col" aria-label="Resources"><h4>Resources</h4><ul><li><a href="#"><i class="fi fi-rr-angle-right"></i>News &amp; Updates</a></li><li><a onclick="goHome('highlights')" style="cursor:pointer"><i class="fi fi-rr-angle-right"></i>Gallery</a></li><li><a onclick="goHome('video')" style="cursor:pointer"><i class="fi fi-rr-angle-right"></i>Videos</a></li><li><a href="#"><i class="fi fi-rr-angle-right"></i>Creotec Philippines</a></li></ul></nav>
          </div>
          <div class="footer-bottom">
            <p>&copy; 2026 Philippine Robotics Cup // Creotec Philippines Inc. All rights reserved.</p>
            <div class="footer-bottom-links"><a href="#">Privacy Policy</a><a href="#">Terms of Use</a></div>
          </div>
        </div>
      </footer>

    </div><!-- /page-drone -->
    <!-- ===== PAGE: RANKINGS ===== -->
    <div id="page-rankings" class="page">

      <!-- BREADCRUMB -->
      <div class="breadcrumb-bar">
        <div class="breadcrumb-inner">
          <a onclick="goHome()" style="cursor:pointer">Home</a>
          <span class="breadcrumb-sep">›</span>
          <span class="breadcrumb-current">Rankings</span>
        </div>
      </div>

      <!-- PAGE HERO -->
      <section class="page-hero" aria-label="Rankings hero" style="background:radial-gradient(ellipse 70% 80% at 30% 50%, rgba(119,51,255,0.12) 0%, transparent 65%);">
        <div class="page-hero-scan" aria-hidden="true"></div>
        <div class="page-hero-inner">
          <div class="page-hero-left">
            <h1 class="page-hero-title">
              <span class="pht-name" data-text="Rankings" style="font-size:clamp(2.2rem,5.5vw,4.2rem);">Rankings</span>
              <span class="pht-sub">PRC 2025 Results</span>
            </h1>
            <p class="page-hero-desc">Official standings from the Philippine Robotics Cup 2025 — National Finals. Rankings are sorted by category with top schools highlighted.</p>
            <div class="hero-meta-row">
              <span class="hero-badge violet"><i class="fi fi-rr-trophy"></i> PRC 2025 — National Finals</span>
              <span class="hero-badge volt"><i class="fi fi-rr-calendar"></i> October 2025</span>
            </div>
          </div>
        </div>
      </section>

      <!-- MAIN CONTENT -->
      <main class="main-content">

        <!-- FILTER BAR -->
        <div class="rankings-filter-bar">
          <button class="filter-btn active-rv" id="filter-all"   onclick="filterRankings('all')">All Categories</button>
          <button class="filter-btn"           id="filter-ei"    onclick="filterRankings('ei')">Emerging Innovators</button>
          <button class="filter-btn"           id="filter-as"    onclick="filterRankings('as')">Aspiring Makers</button>
          <button class="filter-btn"           id="filter-rs"    onclick="filterRankings('rs')">Robot Soccer</button>
          <button class="filter-btn"           id="filter-lt"    onclick="filterRankings('lt')">Line Tracing</button>
          <button class="filter-btn"           id="filter-sb"    onclick="filterRankings('sb')">Sumobot</button>
          <button class="filter-btn"           id="filter-nav"   onclick="filterRankings('nav')">Navigation</button>
          <button class="filter-btn"           id="filter-mx"    onclick="filterRankings('mx')">MakeX</button>
          <button class="filter-btn"           id="filter-drone" onclick="filterRankings('drone')">Drone Soccer</button>
        </div>

        <!-- ── EMERGING INNOVATORS ── -->
        <div class="rank-cat-group visible" id="rg-ei">
          <div class="rankings-table-wrap reveal" style="border-color:rgba(139,126,255,0.28);margin-bottom:32px;">
            <div class="rankings-section-label" style="color:var(--prc-violet);">
              <span class="label-dot" style="background:var(--prc-violet);box-shadow:0 0 6px rgba(139,126,255,0.70);"></span>
              RoboVenture — Emerging Innovators
            </div>
            <div class="rankings-header-row">
              <div>Rank</div><div>School / Team</div>
              <div class="col-cat">Category</div>
              <div class="col-loc" style="text-align:center">Score</div>
              <div class="col-loc" style="text-align:center">Region</div>
              <div class="col-status" style="text-align:center">Status</div>
            </div>
            <div class="rankings-row rank-1">
              <div class="rank-num">1<span class="rank-medal">🥇</span></div>
              <div class="rank-school"><span class="rank-school-name">AUP – Academy</span><span class="rank-team-name">AUPA RoboLights</span></div>
              <div class="col-cat"><span class="rank-category-badge badge-rv">Emrg. Innovators</span></div>
              <div class="rank-score col-loc">—</div>
              <div class="rank-school-loc col-loc">NCR</div>
              <div class="rank-status qualified col-status">Champion</div>
            </div>
            <div class="rankings-row rank-2">
              <div class="rank-num">2<span class="rank-medal">🥈</span></div>
              <div class="rank-school"><span class="rank-school-name">Bethel Academy</span><span class="rank-team-name">The Mechatronics</span></div>
              <div class="col-cat"><span class="rank-category-badge badge-rv">Emrg. Innovators</span></div>
              <div class="rank-score col-loc">—</div>
              <div class="rank-school-loc col-loc">—</div>
              <div class="rank-status finalist col-status">2nd Place</div>
            </div>
            <div class="rankings-row rank-3">
              <div class="rank-num">3<span class="rank-medal">🥉</span></div>
              <div class="rank-school"><span class="rank-school-name">Capas National High School</span><span class="rank-team-name">CNHS Tarlac</span></div>
              <div class="col-cat"><span class="rank-category-badge badge-rv">Emrg. Innovators</span></div>
              <div class="rank-score col-loc">—</div>
              <div class="rank-school-loc col-loc">Region III</div>
              <div class="rank-status finalist col-status">3rd Place</div>
            </div>
            <div class="rankings-row">
              <div class="rank-num"><span class="rank-num other">4</span></div>
              <div class="rank-school"><span class="rank-school-name">Halapitan National High School</span><span class="rank-team-name">San Fernando Team 1 | 2 | 3</span></div>
              <div class="col-cat"><span class="rank-category-badge badge-rv">Emrg. Innovators</span></div>
              <div class="rank-score col-loc">—</div>
              <div class="rank-school-loc col-loc">Region III</div>
              <div class="rank-status competing col-status">Finalist</div>
            </div>
            <div class="rankings-row">
              <div class="rank-num"><span class="rank-num other">5</span></div>
              <div class="rank-school"><span class="rank-school-name">Libona National High School</span><span class="rank-team-name">Libona High</span></div>
              <div class="col-cat"><span class="rank-category-badge badge-rv">Emrg. Innovators</span></div>
              <div class="rank-score col-loc">—</div>
              <div class="rank-school-loc col-loc">Region X</div>
              <div class="rank-status competing col-status">Finalist</div>
            </div>
            <div class="rankings-row">
              <div class="rank-num"><span class="rank-num other">6</span></div>
              <div class="rank-school"><span class="rank-school-name">Malacampa National High School</span><span class="rank-team-name">Codesters | Steminators</span></div>
              <div class="col-cat"><span class="rank-category-badge badge-rv">Emrg. Innovators</span></div>
              <div class="rank-score col-loc">—</div>
              <div class="rank-school-loc col-loc">Region III</div>
              <div class="rank-status competing col-status">Finalist</div>
            </div>
            <div class="rankings-row">
              <div class="rank-num"><span class="rank-num other">7</span></div>
              <div class="rank-school"><span class="rank-school-name">San Jose Community High School</span><span class="rank-team-name">SJCHS Robo Titans</span></div>
              <div class="col-cat"><span class="rank-category-badge badge-rv">Emrg. Innovators</span></div>
              <div class="rank-score col-loc">—</div>
              <div class="rank-school-loc col-loc">—</div>
              <div class="rank-status competing col-status">Finalist</div>
            </div>
            <div class="rankings-row">
              <div class="rank-num"><span class="rank-num other">8</span></div>
              <div class="rank-school"><span class="rank-school-name">Tuguegarao City Science High School</span><span class="rank-team-name">TugSayVenture</span></div>
              <div class="col-cat"><span class="rank-category-badge badge-rv">Emrg. Innovators</span></div>
              <div class="rank-score col-loc">—</div>
              <div class="rank-school-loc col-loc">Region II</div>
              <div class="rank-status competing col-status">Finalist</div>
            </div>
          </div>
        </div>

        <!-- ── OTHER CATEGORIES PLACEHOLDER ── -->
        <div class="rank-cat-group visible" id="rg-other">
          <div style="border:1px solid var(--border-neon);padding:52px 36px;text-align:center;background:rgba(139,126,255,0.02);margin-bottom:32px;" class="reveal">
            <div style="font-family:var(--font-hud);font-size:0.55rem;letter-spacing:0.18em;text-transform:uppercase;color:var(--text-dim);margin-bottom:16px;">// More Categories</div>
            <div style="font-family:var(--font-hud);font-size:1.1rem;font-weight:700;color:var(--text-soft);margin-bottom:10px;">Rankings Coming Soon</div>
            <p style="font-size:0.875rem;color:var(--text-dim);max-width:420px;margin:0 auto;">Full results for Aspiring Makers, Robot Soccer, Line Tracing, Sumobot, Navigation, MakeX, and Drone Soccer will be posted after the official tabulation.</p>
          </div>
        </div>

        <!-- CTA -->
        <div class="page-cta-wrap reveal" style="margin-top:40px;">
          <div class="page-cta">
            <div class="cta-bg-grid"></div>
            <div class="cta-corner tl"></div><div class="cta-corner tr"></div>
            <div class="cta-corner bl"></div><div class="cta-corner br"></div>
            <div class="cta-tag"><i class="fi fi-rr-calendar"></i> Registration Open // PRC 2026</div>
            <h2 class="cta-title">Compete at<br/><span class="accent">PRC 2026</span></h2>
            <p class="cta-desc">Think your team has what it takes to top the rankings next year? Register now for PRC 2026.</p>
            <div class="cta-actions">
              <button class="btn-neon-primary"><i class="fi fi-rr-pen-field"></i> Register Your Team</button>
              <button class="btn-neon-secondary" onclick="goHome('categories')"><i class="fi fi-rr-layers"></i> View Categories</button>
            </div>
          </div>
        </div>

      </main>

      <!-- FOOTER -->
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
              <div class="social-links">
                <a href="https://www.facebook.com/profile.php?id=61579706372017" target="_blank" rel="noopener" class="social-link" aria-label="Facebook"><i class="fi fi-brands-facebook"></i></a>
              </div>
            </div>
            <nav class="footer-col" aria-label="Competition"><h4>Competition</h4><ul><li><a onclick="goHome('categories')" style="cursor:pointer"><i class="fi fi-rr-angle-right"></i>Categories</a></li><li><a href="#"><i class="fi fi-rr-angle-right"></i>Rules &amp; Guidelines</a></li><li><a href="#"><i class="fi fi-rr-angle-right"></i>Schedule</a></li><li><a href="#"><i class="fi fi-rr-angle-right"></i>Past Events</a></li></ul></nav>
            <nav class="footer-col" aria-label="Participate"><h4>Participate</h4><ul><li><a href="#"><i class="fi fi-rr-angle-right"></i>Register Now</a></li><li><a href="#"><i class="fi fi-rr-angle-right"></i>Order Materials</a></li><li><a href="#"><i class="fi fi-rr-angle-right"></i>FAQ</a></li><li><a href="#"><i class="fi fi-rr-angle-right"></i>Contact Us</a></li></ul></nav>
            <nav class="footer-col" aria-label="Resources"><h4>Resources</h4><ul><li><a href="#"><i class="fi fi-rr-angle-right"></i>News &amp; Updates</a></li><li><a onclick="goHome('highlights')" style="cursor:pointer"><i class="fi fi-rr-angle-right"></i>Gallery</a></li><li><a onclick="goHome('video')" style="cursor:pointer"><i class="fi fi-rr-angle-right"></i>Videos</a></li><li><a href="#"><i class="fi fi-rr-angle-right"></i>Creotec Philippines</a></li></ul></nav>
          </div>
          <div class="footer-bottom">
            <p>&copy; 2026 Philippine Robotics Cup // Creotec Philippines Inc. All rights reserved.</p>
            <div class="footer-bottom-links"><a href="#">Privacy Policy</a><a href="#">Terms of Use</a></div>
          </div>
        </div>
      </footer>

    </div><!-- /page-rankings -->
    <!-- ===== PAGE: DRONE SOCCER ===== -->
   

  </div><!-- /page-wrapper -->

  <script>

    // ================================================================
    // SPA ROUTER
    // ================================================================
    var currentPage = 'home';

    // ── Core: show a page by id ──
    function showPage(id) {
      document.querySelectorAll('.page').forEach(function(p) {
        p.classList.remove('active');
        p.style.display = 'none';
        p.style.opacity = '';
        p.style.transform = '';
        p.style.transition = '';
      });
      var pg = document.getElementById('page-' + id);
      if (!pg) return;
      pg.style.display = 'block';
      pg.classList.add('active');
      window.scrollTo({ top: 0, behavior: 'instant' });
      currentPage = id;
      if (id === 'home') { setTimeout(startHeroVideo, 60); }
      // Fade in
      pg.style.opacity = '0';
      pg.style.transform = 'translateY(10px)';
      pg.style.transition = 'opacity 0.28s ease, transform 0.28s ease';
      requestAnimationFrame(function() {
        requestAnimationFrame(function() {
          pg.style.opacity = '1';
          pg.style.transform = 'translateY(0)';
        });
      });
      setTimeout(initReveal, 80);
      if (id === 'roboventure' || id === 'makex' || id === 'drone') setTimeout(initLevelBars, 150);
    }

    // ── navTo: go to home page + scroll to section ──
    function navTo(sectionId) {
      if (currentPage !== 'home') {
        showPage('home');
        setTimeout(function() { jumpTo(sectionId); }, 500);
      } else {
        jumpTo(sectionId);
      }
    }

    // ── jumpTo: scroll to a section by ID ──
    function jumpTo(sectionId) {
      var el = document.getElementById(sectionId);
      if (!el) return;
      var navH = parseInt(getComputedStyle(document.documentElement).getPropertyValue('--nav-height')) || 72;
      var top = el.getBoundingClientRect().top + window.pageYOffset - navH - 8;
      window.scrollTo({ top: top, behavior: 'smooth' });
    }

    // ── goHome: used by sub-page back buttons ──
    function goHome(anchor) {
      var map = { 'shop':'cta', 'register':'cta', 'gallery':'highlights' };
      var target = anchor ? (map[anchor] || anchor) : 'hero';
      navTo(target);
    }

    function goRV()       { showPage('roboventure'); }
    function goMakeX()    { showPage('makex'); }
    function goDrone()    { showPage('drone'); }
    function goRankings() { showPage('rankings'); }

    function closeMobile() {
      var m = document.getElementById('mobile-menu');
      var b = document.getElementById('hamburger');
      if (!m) return;
      m.classList.remove('open'); b.classList.remove('open');
      b.setAttribute('aria-expanded','false');
      m.setAttribute('aria-hidden','true');
      document.body.style.overflow = '';
    }





    // ================================================================
    // SCROLL REVEAL (scoped to active page)
    // ================================================================
    var revObserver;
    function initReveal() {
      if (revObserver) revObserver.disconnect();
      revObserver = new IntersectionObserver(function(entries) {
        entries.forEach(function(e) {
          if (e.isIntersecting) { e.target.classList.add('visible'); revObserver.unobserve(e.target); }
        });
      }, { threshold: 0.08, rootMargin: '0px 0px -28px 0px' });
      var activePage = document.querySelector('.page.active');
      if (!activePage) return;
      activePage.querySelectorAll('.reveal, .reveal-left').forEach(function(el) {
        el.classList.remove('visible');
        revObserver.observe(el);
      });
    }

    // ================================================================
    // LEVEL BARS for RoboVenture
    // ================================================================
    function initLevelBars() {
      var pg = document.querySelector('.page.active');
      if (!pg) return;
      var lo = new IntersectionObserver(function(entries) {
        entries.forEach(function(e) {
          if (e.isIntersecting) {
            var w = e.target.getAttribute('data-width');
            setTimeout(function() { e.target.style.width = w; }, 200);
            lo.unobserve(e.target);
          }
        });
      }, { threshold: 0.4 });
      pg.querySelectorAll('.level-fill, .makex-fill, .makex-fill-m, .drone-fill').forEach(function(el) {
        el.style.width = '0%';
        lo.observe(el);
      });
    }


    // ================================================================
    // HOME PAGE SCRIPTS
    // ================================================================
// CURSOR
    var dot = document.getElementById('cursorDot');
    var ring = document.getElementById('cursorRing');
    var mx = 0, my = 0, rx = 0, ry = 0;
    document.addEventListener('mousemove', function(e) { mx = e.clientX; my = e.clientY; dot.style.left = mx + 'px'; dot.style.top = my + 'px'; });
    (function animRing() { rx += (mx - rx) * 0.12; ry += (my - ry) * 0.12; ring.style.left = rx + 'px'; ring.style.top = ry + 'px'; requestAnimationFrame(animRing); })();
    document.querySelectorAll('a, button, .cat-card, .highlight-item, .org-item, .about-feature, .hero-video-frame, .replay-btn').forEach(function(el) {
      el.addEventListener('mouseenter', function() { ring.classList.add('hovered'); dot.style.background = 'var(--creo-amber)'; dot.style.boxShadow = 'var(--glow-orange)'; });
      el.addEventListener('mouseleave', function() { ring.classList.remove('hovered'); dot.style.background = 'var(--prc-violet)'; dot.style.boxShadow = 'var(--glow-primary)'; });
    });

    // NAV SCROLL
    var nav = document.getElementById('main-nav');
    function syncNav() { /* nav always solid */ }
    // nav is always solid — no scroll listener needed

    // HAMBURGER
    var btn = document.getElementById('hamburger');
    var menu = document.getElementById('mobile-menu');
    function toggleMenu(open) { menu.classList.toggle('open', open); btn.classList.toggle('open', open); btn.setAttribute('aria-expanded', open ? 'true' : 'false'); menu.setAttribute('aria-hidden', open ? 'false' : 'true'); document.body.style.overflow = open ? 'hidden' : ''; }
    btn.addEventListener('click', function(e) { e.stopPropagation(); toggleMenu(!menu.classList.contains('open')); });
    menu.querySelectorAll('a').forEach(function(a) { a.addEventListener('click', function() { toggleMenu(false); }); });
    document.addEventListener('click', function(e) { if (menu.classList.contains('open') && !btn.contains(e.target) && !menu.contains(e.target)) toggleMenu(false); });

    // HERO VIDEO
    // ── HERO VIDEO — loop forever ──
    function startHeroVideo() {
      var vid = document.getElementById('heroVideo');
      if (!vid) return;
      vid.muted = true;
      vid.loop  = true;
      // Try to play — if blocked, retry on first user interaction
      var p = vid.play();
      if (p !== undefined) {
        p.catch(function() {
          var resume = function() {
            vid.play().catch(function(){});
            document.removeEventListener('click',     resume);
            document.removeEventListener('touchstart', resume);
            document.removeEventListener('keydown',   resume);
          };
          document.addEventListener('click',     resume, { once: true });
          document.addEventListener('touchstart', resume, { once: true });
          document.addEventListener('keydown',   resume, { once: true });
        });
      }
    }
    startHeroVideo();

    // COUNTDOWN
    var TARGET = new Date('2026-10-01T08:00:00+08:00');
    function tick() {
      var diff = TARGET - Date.now(); if (diff < 0) diff = 0;
      var d = Math.floor(diff/86400000), h = Math.floor((diff%86400000)/3600000);
      var m = Math.floor((diff%3600000)/60000), s = Math.floor((diff%60000)/1000);
      document.getElementById('cd-days').textContent    = String(d).padStart(3,'0');
      document.getElementById('cd-hours').textContent   = String(h).padStart(2,'0');
      document.getElementById('cd-minutes').textContent = String(m).padStart(2,'0');
      document.getElementById('cd-seconds').textContent = String(s).padStart(2,'0');
    }
    tick(); setInterval(tick, 1000);

    // SCROLL REVEAL
    var revEls = document.querySelectorAll('.reveal, .reveal-left');
    var ro = new IntersectionObserver(function(entries) { entries.forEach(function(e) { if (e.isIntersecting) { e.target.classList.add('visible'); ro.unobserve(e.target); } }); }, { threshold: 0.08, rootMargin: '0px 0px -28px 0px' });
    revEls.forEach(function(el) { ro.observe(el); });

    // STAT COUNTER
    var counters = document.querySelectorAll('.stat-num[data-target]');
    var co = new IntersectionObserver(function(entries) {
      entries.forEach(function(e) {
        if (!e.isIntersecting || e.target._done) return;
        e.target._done = true;
        var target = parseInt(e.target.getAttribute('data-target'));
        var t0 = Date.now(), dur = 1400;
        (function loop() { var p = Math.min((Date.now()-t0)/dur, 1); e.target.textContent = Math.floor((1-Math.pow(1-p,3))*target) + '+'; if (p < 1) requestAnimationFrame(loop); else e.target.textContent = target + '+'; })();
      });
    }, { threshold: 0.5 });
    counters.forEach(function(el) { co.observe(el); });


    // ── RANKINGS FILTER ──
    function filterRankings(cat) {
      // Reset all buttons
      document.querySelectorAll('.filter-btn').forEach(function(b) {
        b.classList.remove('active-rv','active-mx','active-dr');
      });
      var btn = document.getElementById('filter-' + cat);
      if (btn) {
        if (cat === 'mx') btn.classList.add('active-mx');
        else if (cat === 'drone') btn.classList.add('active-dr');
        else btn.classList.add('active-rv');
      }

      var groups = {
        'all':   ['rg-ei','rg-other'],
        'ei':    ['rg-ei'],
        'as':    ['rg-other'],
        'rs':    ['rg-other'],
        'lt':    ['rg-other'],
        'sb':    ['rg-other'],
        'nav':   ['rg-other'],
        'mx':    ['rg-other'],
        'drone': ['rg-other'],
      };

      // Hide all
      document.querySelectorAll('.rank-cat-group').forEach(function(g) {
        g.classList.remove('visible');
      });
      // Show selected
      var show = groups[cat] || ['rg-ei','rg-other'];
      show.forEach(function(id) {
        var el = document.getElementById(id);
        if (el) el.classList.add('visible');
      });
    }

    // Initial reveal for home page
    initReveal();
  </script>
</body>
</html>
