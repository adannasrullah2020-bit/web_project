<?php
session_start();
$error_message = '';

// Database connection
require_once '../config.php';

if(isset($_POST['but_submit'])){
    // We don't need mysqli_real_escape_string if we use Prepared Statements
    $username = $_POST['username'];
    $password = $_POST['password'];

    if ($username != "" && $password != ""){
        
        // 1. Prepare the query to find the user
        $stmt = $conn->prepare("SELECT password FROM admin WHERE username = ?");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if($result->num_rows > 0){
            $row = $result->fetch_assoc();
            
            // 2. USE PASSWORD_VERIFY INSTEAD OF == 
            // This function handles the "math" to check if the plain text matches the hash
            if(password_verify($password, $row['password'])){
                $_SESSION['username'] = $username;
                header('Location: Home.php');
                exit();
            } else {
                $error_message = "Invalid password. Please try again.";
            }
        } else {
            $error_message = "User not found. Check your username.";
        }
        $stmt->close();
    } else {
        $error_message = "Please enter both username and password.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Portal — Travel</title>
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
            --accent-light: #eff4ff;
            --danger: #c0392b;
            --danger-bg: #fff5f5;
            --shadow-sm: 0 1px 3px rgba(0,0,0,0.06), 0 1px 2px rgba(0,0,0,0.04);
            --shadow-md: 0 4px 16px rgba(0,0,0,0.08), 0 2px 6px rgba(0,0,0,0.05);
            --shadow-lg: 0 20px 60px rgba(0,0,0,0.12), 0 8px 24px rgba(0,0,0,0.06);
            --radius: 10px;
        }

        html, body {
            height: 100%;
        }

        body {
            font-family: 'DM Sans', sans-serif;
            background-color: #f0f2f5;
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
            padding: 1.5rem;
        }

        /* Subtle grid background */
        body::before {
            content: '';
            position: fixed;
            inset: 0;
            background-image:
                linear-gradient(rgba(26,86,219,0.03) 1px, transparent 1px),
                linear-gradient(90deg, rgba(26,86,219,0.03) 1px, transparent 1px);
            background-size: 40px 40px;
            pointer-events: none;
        }

        .page-layout {
            display: flex;
            width: 100%;
            max-width: 900px;
            min-height: 540px;
            border-radius: 16px;
            overflow: hidden;
            box-shadow: var(--shadow-lg);
            animation: fadeUp 0.5s ease both;
        }

        @keyframes fadeUp {
            from { opacity: 0; transform: translateY(16px); }
            to   { opacity: 1; transform: translateY(0); }
        }

        /* ── Left Panel ── */
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

        /* Decorative circles */
        .panel-left::before,
        .panel-left::after {
            content: '';
            position: absolute;
            border-radius: 50%;
            background: rgba(255,255,255,0.05);
        }
        .panel-left::before {
            width: 280px; height: 280px;
            top: -80px; right: -80px;
        }
        .panel-left::after {
            width: 180px; height: 180px;
            bottom: -50px; left: -50px;
        }

        .brand {
            position: relative;
            z-index: 1;
        }

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
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .brand-icon svg {
            width: 18px; height: 18px;
            fill: white;
        }

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

        .feature-item {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .feature-dot {
            width: 6px; height: 6px;
            border-radius: 50%;
            background: rgba(255,255,255,0.4);
            flex-shrink: 0;
        }

        .feature-text {
            font-size: 13px;
            color: rgba(255,255,255,0.5);
            font-weight: 400;
        }

        /* ── Right Panel (Form) ── */
        .panel-right {
            width: 380px;
            background: var(--surface);
            padding: 52px 44px;
            display: flex;
            flex-direction: column;
            justify-content: center;
            flex-shrink: 0;
        }

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
            margin-bottom: 6px;
        }

        .form-subtitle {
            font-size: 13.5px;
            color: var(--ink-faint);
            font-weight: 400;
            margin-bottom: 36px;
            line-height: 1.5;
        }

        /* Error Alert */
        .alert-error {
            display: flex;
            align-items: flex-start;
            gap: 10px;
            background: var(--danger-bg);
            border: 1px solid rgba(192,57,43,0.18);
            border-radius: var(--radius);
            padding: 12px 14px;
            margin-bottom: 24px;
            animation: shake 0.35s ease;
        }

        @keyframes shake {
            0%, 100% { transform: translateX(0); }
            25%       { transform: translateX(-4px); }
            75%       { transform: translateX(4px); }
        }

        .alert-error svg {
            width: 16px; height: 16px;
            fill: var(--danger);
            flex-shrink: 0;
            margin-top: 1px;
        }

        .alert-error span {
            font-size: 13px;
            color: var(--danger);
            font-weight: 500;
            line-height: 1.45;
        }

        /* Field */
        .field {
            margin-bottom: 20px;
        }

        .field-label {
            display: block;
            font-size: 12.5px;
            font-weight: 600;
            color: var(--ink);
            letter-spacing: 0.2px;
            margin-bottom: 7px;
        }

        .field-wrap {
            position: relative;
        }

        .field-icon {
            position: absolute;
            left: 13px;
            top: 50%;
            transform: translateY(-50%);
            pointer-events: none;
            transition: color 0.2s;
            color: var(--ink-faint);
            display: flex;
            align-items: center;
        }

        .field-icon svg {
            width: 15px; height: 15px;
            fill: currentColor;
        }

        .field-input {
            width: 100%;
            height: 44px;
            padding: 0 14px 0 40px;
            border: 1.5px solid var(--border);
            border-radius: var(--radius);
            font-family: 'DM Sans', sans-serif;
            font-size: 14px;
            font-weight: 400;
            color: var(--ink);
            background: var(--surface-alt);
            outline: none;
            transition: border-color 0.2s, background 0.2s, box-shadow 0.2s;
            -webkit-appearance: none;
        }

        .field-input::placeholder {
            color: var(--ink-faint);
            font-weight: 400;
        }

        .field-input:focus {
            border-color: var(--accent);
            background: var(--surface);
            box-shadow: 0 0 0 3px rgba(26,86,219,0.1);
        }

        .field-input:focus + .field-icon {
            color: var(--accent);
        }

        /* Hmm — icon is before input in DOM, so use sibling trick differently */
        .field-wrap:focus-within .field-icon {
            color: var(--accent);
        }

        /* Submit */
        .btn-submit {
            width: 100%;
            height: 46px;
            border: none;
            border-radius: var(--radius);
            background: var(--accent);
            color: white;
            font-family: 'DM Sans', sans-serif;
            font-size: 14.5px;
            font-weight: 600;
            letter-spacing: 0.3px;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 7px;
            margin-top: 28px;
            transition: background 0.2s, transform 0.15s, box-shadow 0.2s;
            box-shadow: 0 2px 8px rgba(26,86,219,0.25);
        }

        .btn-submit:hover {
            background: var(--accent-hover);
            transform: translateY(-1px);
            box-shadow: 0 6px 16px rgba(26,86,219,0.3);
        }

        .btn-submit:active {
            transform: translateY(0);
            box-shadow: 0 2px 8px rgba(26,86,219,0.2);
        }

        .btn-submit svg {
            width: 16px; height: 16px;
            fill: white;
        }

        /* Footer */
        .form-footer {
            margin-top: 28px;
            padding-top: 20px;
            border-top: 1px solid var(--border);
            text-align: center;
        }

        .form-footer p {
            font-size: 12px;
            color: var(--ink-faint);
            line-height: 1.5;
        }

        /* Responsive */
        @media (max-width: 680px) {
            .panel-left { display: none; }
            .panel-right {
                width: 100%;
                padding: 44px 32px;
            }
            .page-layout {
                max-width: 420px;
                min-height: unset;
            }
        }
    </style>
</head>
<body>

<div class="page-layout">

    <!-- Left Branding Panel -->
    <div class="panel-left">
        <div class="brand">
            <div class="brand-mark">
                <div class="brand-icon">
                    <svg viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-1 14H9V8h2v8zm4 0h-2V8h2v8z"/>
                    </svg>
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
                <span class="feature-text">Booking & reservation management</span>
            </div>
            <div class="feature-item">
                <span class="feature-dot"></span>
                <span class="feature-text">Real-time analytics & reporting</span>
            </div>
            <div class="feature-item">
                <span class="feature-dot"></span>
                <span class="feature-text">Customer & agent oversight</span>
            </div>
        </div>
    </div>

    <!-- Right Form Panel -->
    <div class="panel-right">

        <p class="form-eyebrow">Administrator Access</p>
        <h2 class="form-title">Welcome back</h2>
        <p class="form-subtitle">Sign in to your admin account to continue.</p>

        <?php if($error_message != ''): ?>
        <div class="alert-error" role="alert">
            <svg viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm1 15h-2v-2h2v2zm0-4h-2V7h2v6z"/>
            </svg>
            <span><?php echo htmlspecialchars($error_message); ?></span>
        </div>
        <?php endif; ?>

        <form method="post" action="">

            <div class="field">
                <label class="field-label" for="username">Username</label>
                <div class="field-wrap">
                    <span class="field-icon">
                        <svg viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path d="M12 12c2.7 0 4.8-2.1 4.8-4.8S14.7 2.4 12 2.4 7.2 4.5 7.2 7.2 9.3 12 12 12zm0 2.4c-3.2 0-9.6 1.6-9.6 4.8v2.4h19.2v-2.4c0-3.2-6.4-4.8-9.6-4.8z"/>
                        </svg>
                    </span>
                    <input
                        type="text"
                        id="username"
                        class="field-input"
                        name="username"
                        placeholder="Enter your username"
                        autocomplete="username"
                        required
                    >
                </div>
            </div>

            <div class="field">
                <label class="field-label" for="password">Password</label>
                <div class="field-wrap">
                    <span class="field-icon">
                        <svg viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path d="M18 8h-1V6c0-2.76-2.24-5-5-5S7 3.24 7 6v2H6c-1.1 0-2 .9-2 2v10c0 1.1.9 2 2 2h12c1.1 0 2-.9 2-2V10c0-1.1-.9-2-2-2zm-6 9c-1.1 0-2-.9-2-2s.9-2 2-2 2 .9 2 2-.9 2-2 2zm3.1-9H8.9V6c0-1.71 1.39-3.1 3.1-3.1 1.71 0 3.1 1.39 3.1 3.1v2z"/>
                        </svg>
                    </span>
                    <input
                        type="password"
                        id="password"
                        class="field-input"
                        name="password"
                        placeholder="Enter your password"
                        autocomplete="current-password"
                        required
                    >
                </div>
            </div>

            <button type="submit" name="but_submit" class="btn-submit">
                Sign In
                <svg viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path d="M12 4l-1.41 1.41L16.17 11H4v2h12.17l-5.58 5.59L12 20l8-8-8-8z"/>
                </svg>
            </button>

        </form>

        <div class="form-footer">
            <p>Protected area &mdash; authorized personnel only.<br>Access is monitored and logged.</p>
        </div>

    </div>
</div>

</body>
</html>