<?php
session_start();
unset($_SESSION["username"]);
unset($_SESSION["password"]);
session_destroy();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Logged Out — Travel Admin</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=DM+Serif+Display:ital@0;1&family=DM+Sans:opsz,wght@9..40,300;9..40,400;9..40,500;9..40,600&display=swap" rel="stylesheet">
    <style>
        *, *::before, *::after {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        :root {
            --ink: #0e1117;
            --ink-muted: #4a5568;
            --ink-faint: #8a96a8;
            --surface: #ffffff;
            --surface-alt: #f5f6f8;
            --border: #e4e7ec;
            --accent: #1a56db;
            --accent-hover: #1447c0;
            --shadow-lg: 0 20px 60px rgba(0,0,0,0.12), 0 8px 24px rgba(0,0,0,0.06);
            --radius: 10px;
        }

        html, body { height: 100%; }

        body {
            font-family: 'DM Sans', sans-serif;
            background-color: #f0f2f5;
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
            padding: 1.5rem;
        }

        /* Subtle grid background — same as login */
        body::before {
            content: '';
            position: fixed;
            inset: 0;
            background-image:
                linear-gradient(rgba(26,86,219,0.04) 1px, transparent 1px),
                linear-gradient(90deg, rgba(26,86,219,0.04) 1px, transparent 1px);
            background-size: 36px 36px;
            pointer-events: none;
        }

        .page-layout {
            display: flex;
            width: 100%;
            max-width: 900px;
            min-height: 520px;
            border-radius: 16px;
            overflow: hidden;
            box-shadow: var(--shadow-lg);
            animation: fadeUp 0.5s ease both;
        }

        @keyframes fadeUp {
            from { opacity: 0; transform: translateY(16px); }
            to   { opacity: 1; transform: translateY(0); }
        }

        /* ── Left Panel — matches login left panel exactly ── */
        .panel-left {
            flex: 1;
            background: linear-gradient(160deg, #0f1f5c 0%, #1a3799 55%, #1e56e0 100%);
            padding: 52px 44px;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            position: relative;
            overflow: hidden;
        }

        .panel-left::before,
        .panel-left::after {
            content: '';
            position: absolute;
            border-radius: 50%;
            background: rgba(255,255,255,0.05);
        }
        .panel-left::before { width: 280px; height: 280px; top: -80px;  right: -80px; }
        .panel-left::after  { width: 180px; height: 180px; bottom: -50px; left: -50px; }

        .brand { position: relative; z-index: 1; }

        .brand-mark {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 48px;
        }

        .brand-icon {
            width: 36px; height: 36px;
            background: rgba(255,255,255,0.15);
            border: 1px solid rgba(255,255,255,0.2);
            border-radius: 8px;
            display: flex; align-items: center; justify-content: center;
        }

        .brand-icon svg { width: 18px; height: 18px; fill: white; }

        .brand-name {
            font-family: 'DM Sans', sans-serif;
            font-weight: 600;
            font-size: 15px;
            color: rgba(255,255,255,0.9);
            letter-spacing: 0.4px;
        }

        .panel-headline {
            font-family: 'DM Serif Display', serif;
            font-size: 34px;
            line-height: 1.25;
            color: white;
            margin-bottom: 14px;
        }

        .panel-headline em {
            font-style: italic;
            color: rgba(255,255,255,0.65);
        }

        .panel-sub {
            font-size: 14px;
            font-weight: 400;
            line-height: 1.6;
            color: rgba(255,255,255,0.55);
            max-width: 260px;
        }

        .panel-features {
            position: relative;
            z-index: 1;
            display: flex;
            flex-direction: column;
            gap: 12px;
        }

        .feature-item { display: flex; align-items: center; gap: 10px; }
        .feature-dot  { width: 6px; height: 6px; border-radius: 50%; background: rgba(255,255,255,0.4); flex-shrink: 0; }
        .feature-text { font-size: 13px; color: rgba(255,255,255,0.5); font-weight: 400; }

        /* ── Right Panel ── */
        .panel-right {
            width: 380px;
            background: var(--surface);
            padding: 52px 44px;
            display: flex;
            flex-direction: column;
            justify-content: center;
            flex-shrink: 0;
        }

        /* Goodbye icon */
        .logout-icon-wrap {
            width: 64px; height: 64px;
            background: #eff4ff;
            border-radius: 16px;
            display: flex; align-items: center; justify-content: center;
            margin-bottom: 24px;
        }

        .logout-icon-wrap svg { width: 30px; height: 30px; fill: var(--accent); }

        .form-eyebrow {
            font-size: 11px;
            font-weight: 600;
            letter-spacing: 1.2px;
            text-transform: uppercase;
            color: var(--accent);
            margin-bottom: 8px;
        }

        .form-title {
            font-family: 'DM Serif Display', serif;
            font-size: 26px;
            color: var(--ink);
            margin-bottom: 8px;
        }

        .form-subtitle {
            font-size: 13.5px;
            color: var(--ink-faint);
            font-weight: 400;
            margin-bottom: 36px;
            line-height: 1.6;
        }

        /* Action buttons */
        .btn-block {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 9px;
            width: 100%;
            padding: 12px 16px;
            border-radius: var(--radius);
            font-family: 'DM Sans', sans-serif;
            font-size: 14px;
            font-weight: 600;
            text-decoration: none;
            cursor: pointer;
            border: none;
            transition: all 0.2s ease;
            letter-spacing: 0.2px;
        }

        .btn-block svg { width: 16px; height: 16px; fill: currentColor; flex-shrink: 0; }

        .btn-primary {
            background: var(--accent);
            color: white;
            box-shadow: 0 2px 8px rgba(26,86,219,0.25);
            margin-bottom: 12px;
        }

        .btn-primary:hover {
            background: var(--accent-hover);
            transform: translateY(-1px);
            box-shadow: 0 6px 16px rgba(26,86,219,0.3);
        }

        .btn-secondary {
            background: var(--surface-alt);
            color: var(--ink-muted);
            border: 1.5px solid var(--border);
        }

        .btn-secondary:hover {
            background: #eaecf0;
            color: var(--ink);
        }

        /* Divider */
        .divider {
            display: flex;
            align-items: center;
            gap: 10px;
            margin: 4px 0 16px;
            color: var(--ink-faint);
            font-size: 12px;
        }

        .divider::before, .divider::after {
            content: '';
            flex: 1;
            height: 1px;
            background: var(--border);
        }

        /* Footer note */
        .panel-footer-note {
            margin-top: 28px;
            padding-top: 20px;
            border-top: 1px solid var(--border);
            text-align: center;
            font-size: 12px;
            color: var(--ink-faint);
            line-height: 1.5;
        }

        /* Responsive */
        @media (max-width: 680px) {
            .panel-left { display: none; }
            .panel-right { width: 100%; padding: 44px 32px; }
            .page-layout { max-width: 420px; min-height: unset; }
        }
    </style>
</head>
<body>

<div class="page-layout">

    <!-- Left Branding Panel (identical to login) -->
    <div class="panel-left">
        <div class="brand">
            <div class="brand-mark">
                <div class="brand-icon">
                    <svg viewBox="0 0 24 24"><path d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7zm0 9.5c-1.38 0-2.5-1.12-2.5-2.5S10.62 6.5 12 6.5s2.5 1.12 2.5 2.5S13.38 11.5 12 11.5z"/></svg>
                </div>
                <span class="brand-name">Travel Admin</span>
            </div>

            <h1 class="panel-headline">
                Manage your<br>travel platform<br><em>with ease.</em>
            </h1>
            <p class="panel-sub">
                A secure, centralized dashboard for administrators to oversee operations.
            </p>
        </div>

        <div class="panel-features">
            <div class="feature-item">
                <span class="feature-dot"></span>
                <span class="feature-text">Booking &amp; reservation management</span>
            </div>
            <div class="feature-item">
                <span class="feature-dot"></span>
                <span class="feature-text">Real-time analytics &amp; reporting</span>
            </div>
            <div class="feature-item">
                <span class="feature-dot"></span>
                <span class="feature-text">Customer &amp; agent oversight</span>
            </div>
        </div>
    </div>

    <!-- Right Content Panel -->
    <div class="panel-right">

        <div class="logout-icon-wrap">
            <svg viewBox="0 0 24 24"><path d="M17 7l-1.41 1.41L18.17 11H8v2h10.17l-2.58 2.58L17 17l5-5zM4 5h8V3H4c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h8v-2H4V5z"/></svg>
        </div>

        <p class="form-eyebrow">Session Ended</p>
        <h2 class="form-title">You've signed out.</h2>
        <p class="form-subtitle">
            Your session has been securely closed.<br>
            Choose where you'd like to go next.
        </p>

        <a href="adminLogin.php" class="btn-block btn-primary">
            <svg viewBox="0 0 24 24"><path d="M11 7L9.6 8.4l2.6 2.6H2v2h10.2l-2.6 2.6L11 17l5-5-5-5zm9 12h-8v2h8c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2h-8v2h8v14z"/></svg>
            Back to Admin Login
        </a>

        <div class="divider">or</div>

        <a href="\travel\index.php" class="btn-block btn-secondary">
            <svg viewBox="0 0 24 24"><path d="M10 20v-6h4v6h5v-8h3L12 3 2 12h3v8z"/></svg>
            Go to User Panel
        </a>

        <div class="panel-footer-note">
            Your activity was monitored and logged.<br>All session data has been cleared.
        </div>

    </div>

</div>

</body>
</html>