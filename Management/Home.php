<?php 
session_start();
if(!isset($_SESSION['username'])){
    header('Location: adminLogin.php');
    exit();
}

// --- DATABASE CONNECTION ---
require_once '../config.php';

$update_success = '';
$update_error   = '';

if(isset($_POST['update_profile'])){
    $new_username = $_POST['new_username'];
    $new_password = $_POST['new_password'];
    $confirm_pwd  = $_POST['confirm_password'];
    $full_name    = $_POST['full_name'];
    $email        = $_POST['email'];
    $current_user = $_SESSION['username'];

    if($new_password !== '' && $new_password !== $confirm_pwd){
        $update_error = "Passwords do not match.";
    } else {
        if($new_password !== ''){
            $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
            $sql  = "UPDATE admin SET username=?, password=?, full_name=?, email=? WHERE username=?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("sssss", $new_username, $hashed_password, $full_name, $email, $current_user);
        } else {
            $sql  = "UPDATE admin SET username=?, full_name=?, email=? WHERE username=?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("ssss", $new_username, $full_name, $email, $current_user);
        }
        if($stmt->execute()){
            $_SESSION['username'] = $new_username;
            $update_success = "Profile updated successfully.";
        } else {
            $update_error = "Update failed: " . $conn->error;
        }
        $stmt->close();
    }
}

// --- DATA FETCHING FOR STATS & CHART ---
$sql_users = mysqli_query($conn, "SELECT COUNT(*) as total FROM users");
$total_users = mysqli_fetch_assoc($sql_users)['total'];

$sql_hotels = mysqli_query($conn, "SELECT COUNT(*) as total FROM hotels");
$total_hotels = mysqli_fetch_assoc($sql_hotels)['total'];

$sql_flights = mysqli_query($conn, "SELECT COUNT(*) as total FROM flights");
$total_flights = mysqli_fetch_assoc($sql_flights)['total'];

$sql_trains = mysqli_query($conn, "SELECT COUNT(*) as total FROM trains");
$total_trains = mysqli_fetch_assoc($sql_trains)['total'];

// Fetching Bookings for the Chart
$sql_h_book = mysqli_query($conn, "SELECT COUNT(*) as total FROM hotelbookings");
$h_bookings = mysqli_fetch_assoc($sql_h_book)['total'];

$sql_f_book = mysqli_query($conn, "SELECT COUNT(*) as total FROM flightbookings");
$f_bookings = mysqli_fetch_assoc($sql_f_book)['total'];

$sql_t_book = mysqli_query($conn, "SELECT COUNT(*) as total FROM trainbookings");
$t_bookings = mysqli_fetch_assoc($sql_t_book)['total'];

$admin_data = [];
$res = mysqli_query($conn, "SELECT * FROM admin WHERE username='" . mysqli_real_escape_string($conn, $_SESSION['username']) . "'");

if($res && mysqli_num_rows($res) > 0){ $admin_data = mysqli_fetch_assoc($res); }

$current_page = isset($_GET['page']) ? $_GET['page'] : 'dashboard';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>Admin Dashboard — Travel</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=DM+Serif+Display:ital@0;1&family=DM+Sans:opsz,wght@9..40,300;9..40,400;9..40,500;9..40,600&display=swap" rel="stylesheet">
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        :root {
            --sidebar-w:  260px;
            --topbar-h:   64px;
            --bg:          #f0f2f5;
            --surface:     #ffffff;
            --surface-alt: #f5f6f8;
            --border:      #e4e7ec;
            --border-soft: #edf0f4;
            --ink:         #0e1117;
            --ink-muted:   #4a5568;
            --ink-faint:   #8a96a8;
            --accent:      #1a56db;
            --accent-dark: #0f1f5c;
            --accent-mid:  #1a3799;
            --accent-light:#eff4ff;
            --accent-hover:#1447c0;
            --green:  #059669;  --green-bg:  rgba(5,150,105,0.09);
            --amber:  #d97706;  --amber-bg:  rgba(217,119,6,0.09);
            --red:    #dc2626;  --red-bg:    rgba(220,38,38,0.09);
            --violet: #7c3aed;  --violet-bg: rgba(124,58,237,0.09);
            --shadow-sm: 0 1px 3px rgba(0,0,0,0.06), 0 1px 2px rgba(0,0,0,0.04);
            --shadow-md: 0 4px 16px rgba(0,0,0,0.07), 0 2px 6px rgba(0,0,0,0.04);
            --radius: 10px;  --radius-sm: 7px;
        }

        html, body { height: 100%; }

        body {
            font-family: 'DM Sans', sans-serif;
            background: var(--bg);
            color: var(--ink);
            font-size: 14px;
            line-height: 1.5;
            overflow-x: hidden;
        }

        body::before {
            content: '';
            position: fixed; inset: 0;
            background-image:
                linear-gradient(rgba(26,86,219,0.03) 1px, transparent 1px),
                linear-gradient(90deg, rgba(26,86,219,0.03) 1px, transparent 1px);
            background-size: 40px 40px;
            pointer-events: none; z-index: 0;
        }

        /* SIDEBAR */
        .sidebar {
            position: fixed; top: 0; left: 0;
            width: var(--sidebar-w); height: 100vh;
            background: linear-gradient(160deg, #0f1f5c 0%, #1a3799 60%, #1e56e0 100%);
            display: flex; flex-direction: column;
            z-index: 200; transition: transform 0.3s ease; overflow: hidden;
        }

        .sidebar::before, .sidebar::after {
            content: ''; position: absolute; border-radius: 50%;
            background: rgba(255,255,255,0.05); pointer-events: none;
        }
        .sidebar::before { width: 260px; height: 260px; top: -80px; right: -80px; }
        .sidebar::after  { width: 160px; height: 160px; bottom: -50px; left: -50px; }

        .sidebar-logo {
            height: var(--topbar-h);
            display: flex; align-items: center; gap: 10px;
            padding: 0 22px;
            border-bottom: 1px solid rgba(255,255,255,0.1);
            flex-shrink: 0; position: relative; z-index: 1;
        }

        .logo-mark {
            width: 32px; height: 32px;
            background: rgba(255,255,255,0.15);
            border: 1px solid rgba(255,255,255,0.2);
            border-radius: 8px;
            display: flex; align-items: center; justify-content: center; flex-shrink: 0;
        }
        .logo-mark svg { width: 17px; height: 17px; fill: white; }

        .logo-label {
            font-family: 'DM Serif Display', serif;
            font-size: 17px; color: white; letter-spacing: 0.2px;
        }

        .sidebar-body {
            flex: 1; overflow-y: auto;
            padding: 20px 12px 8px;
            position: relative; z-index: 1;
            scrollbar-width: none;
        }
        .sidebar-body::-webkit-scrollbar { display: none; }

        .sidebar-section-label {
            font-size: 10px; font-weight: 600;
            letter-spacing: 1.3px; text-transform: uppercase;
            color: rgba(255,255,255,0.4);
            padding: 0 8px; margin: 16px 0 6px;
        }
        .sidebar-section-label:first-child { margin-top: 4px; }

        .nav-item {
            display: flex; align-items: center; gap: 10px;
            padding: 9px 10px;
            border-radius: var(--radius-sm);
            color: rgba(255,255,255,0.65);
            text-decoration: none;
            font-size: 13.5px; font-weight: 400;
            transition: all 0.18s ease;
            position: relative; margin-bottom: 2px; cursor: pointer;
        }
        .nav-item svg { width: 16px; height: 16px; fill: currentColor; flex-shrink: 0; opacity: 0.75; }
        .nav-item:hover { background: rgba(255,255,255,0.12); color: white; }
        .nav-item:hover svg { opacity: 1; }
        .nav-item.active { background: rgba(255,255,255,0.18); color: white; font-weight: 500; }
        .nav-item.active svg { opacity: 1; }
        .nav-item.active::before {
            content: ''; position: absolute;
            left: 0; top: 6px; bottom: 6px;
            width: 3px; border-radius: 0 3px 3px 0; background: white;
        }
        .nav-item.danger { color: rgba(252,165,165,0.85); }
        .nav-item.danger:hover { background: rgba(220,38,38,0.18); color: #fca5a5; }

        .nav-badge {
            margin-left: auto; font-size: 10px; font-weight: 600;
            padding: 2px 7px; border-radius: 20px;
            background: rgba(255,255,255,0.15); color: rgba(255,255,255,0.9);
        }

        .sidebar-footer {
            padding: 14px 12px;
            border-top: 1px solid rgba(255,255,255,0.1);
            position: relative; z-index: 1;
        }

        .sidebar-profile {
            display: flex; align-items: center; gap: 10px;
            padding: 10px; border-radius: var(--radius-sm);
            cursor: pointer; transition: background 0.18s; text-decoration: none;
        }
        .sidebar-profile:hover { background: rgba(255,255,255,0.12); }

        .avatar {
            width: 34px; height: 34px; border-radius: 50%;
            background: rgba(255,255,255,0.2);
            border: 1.5px solid rgba(255,255,255,0.3);
            display: flex; align-items: center; justify-content: center;
            font-size: 13px; font-weight: 700; color: white; flex-shrink: 0;
        }
        .avatar-name { font-size: 13px; font-weight: 600; color: white; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
        .avatar-role { font-size: 11px; color: rgba(255,255,255,0.45); }

        /* TOPBAR */
        .topbar {
            position: fixed; top: 0; left: var(--sidebar-w); right: 0;
            height: var(--topbar-h);
            background: var(--surface); border-bottom: 1px solid var(--border);
            display: flex; align-items: center; justify-content: space-between;
            padding: 0 28px; z-index: 100;
            box-shadow: var(--shadow-sm);
        }
        .topbar-left { display: flex; align-items: center; gap: 12px; }

        .hamburger {
            display: none; background: none; border: none;
            color: var(--ink-muted); cursor: pointer; padding: 6px; border-radius: 6px;
        }
        .hamburger:hover { background: var(--surface-alt); color: var(--ink); }
        .hamburger svg { width: 20px; height: 20px; fill: currentColor; display: block; }

        .page-title { font-family: 'DM Serif Display', serif; font-size: 20px; color: var(--ink); }

        .topbar-right { display: flex; align-items: center; gap: 10px; }

        .topbar-btn {
            display: flex; align-items: center; gap: 7px;
            padding: 7px 14px; border-radius: var(--radius-sm);
            font-family: 'DM Sans', sans-serif;
            font-size: 13px; font-weight: 500;
            cursor: pointer; text-decoration: none; transition: all 0.18s; border: none;
        }
        .topbar-btn svg { width: 14px; height: 14px; fill: currentColor; flex-shrink: 0; }

        .btn-ghost { background: var(--surface-alt); color: var(--ink-muted); border: 1px solid var(--border); }
        .btn-ghost:hover { background: var(--border); color: var(--ink); }

        .btn-danger-outline { background: var(--red-bg); color: var(--red); border: 1px solid rgba(220,38,38,0.2); }
        .btn-danger-outline:hover { background: rgba(220,38,38,0.14); }

        /* MAIN */
        .main {
            margin-left: var(--sidebar-w); margin-top: var(--topbar-h);
            padding: 32px 28px;
            min-height: calc(100vh - var(--topbar-h));
            position: relative; z-index: 1;
        }

        .page-section { display: none; }
        .page-section.active { display: block; animation: fadeIn 0.3s ease; }
        @keyframes fadeIn { from { opacity:0; transform:translateY(8px); } to { opacity:1; transform:translateY(0); } }

        /* STAT CARDS */
        .stats-grid { display: grid; grid-template-columns: repeat(4,1fr); gap: 16px; margin-bottom: 28px; }

        .stat-card {
            background: var(--surface); border: 1px solid var(--border);
            border-radius: var(--radius); padding: 22px 20px;
            box-shadow: var(--shadow-sm); position: relative; overflow: hidden;
            transition: box-shadow 0.2s, transform 0.2s, border-color 0.2s;
        }
        .stat-card:hover { box-shadow: var(--shadow-md); transform: translateY(-2px); border-color: #d0d8e8; }

        .stat-card::before {
            content: ''; position: absolute;
            top: 0; left: 0; right: 0; height: 3px;
            border-radius: var(--radius) var(--radius) 0 0;
        }
        .stat-card.blue::before  { background: var(--accent); }
        .stat-card.green::before { background: var(--green); }
        .stat-card.amber::before { background: var(--amber); }
        .stat-card.violet::before{ background: var(--violet); }

        .stat-icon { width: 40px; height: 40px; border-radius: 9px; display: flex; align-items: center; justify-content: center; margin-bottom: 14px; }
        .stat-icon svg { width: 19px; height: 19px; fill: currentColor; }
        .stat-icon.blue   { background: rgba(26,86,219,0.1);  color: var(--accent); }
        .stat-icon.green  { background: var(--green-bg);  color: var(--green); }
        .stat-icon.amber  { background: var(--amber-bg);  color: var(--amber); }
        .stat-icon.violet { background: var(--violet-bg); color: var(--violet); }

        .stat-value { font-family: 'DM Serif Display', serif; font-size: 30px; color: var(--ink); line-height: 1; margin-bottom: 4px; }
        .stat-label { font-size: 12px; color: var(--ink-faint); font-weight: 500; letter-spacing: 0.2px; }

        /* SECTION HEADER */
        .section-header { margin-bottom: 24px; }
        .section-header h2 { font-family: 'DM Serif Display', serif; font-size: 22px; color: var(--ink); margin-bottom: 4px; }
        .section-header p { font-size: 13px; color: var(--ink-faint); }

        /* MGMT CARDS */
        .mgmt-grid { display: grid; grid-template-columns: repeat(2,1fr); gap: 16px; }

        .mgmt-card {
            background: var(--surface); border: 1px solid var(--border);
            border-radius: var(--radius); overflow: hidden;
            box-shadow: var(--shadow-sm); transition: box-shadow 0.2s, border-color 0.2s;
        }
        .mgmt-card:hover { box-shadow: var(--shadow-md); border-color: #d0d8e8; }

        .mgmt-card-head { padding: 18px 20px 14px; display: flex; align-items: center; gap: 12px; border-bottom: 1px solid var(--border-soft); }

        .mgmt-icon { width: 36px; height: 36px; border-radius: 8px; display: flex; align-items: center; justify-content: center; flex-shrink: 0; }
        .mgmt-icon svg { width: 17px; height: 17px; fill: currentColor; }
        .mgmt-icon.blue   { background: rgba(26,86,219,0.1);  color: var(--accent); }
        .mgmt-icon.green  { background: var(--green-bg);  color: var(--green); }
        .mgmt-icon.red    { background: var(--red-bg);    color: var(--red); }
        .mgmt-icon.amber  { background: var(--amber-bg);  color: var(--amber); }

        .mgmt-title { font-size: 14px; font-weight: 600; color: var(--ink); }
        .mgmt-desc  { font-size: 12px; color: var(--ink-faint); margin-top: 1px; }

        .mgmt-links { padding: 10px 12px; display: flex; flex-direction: column; gap: 2px; }

        .mgmt-link {
            display: flex; align-items: center; gap: 9px; padding: 9px 10px;
            border-radius: var(--radius-sm); color: var(--ink-muted);
            text-decoration: none; font-size: 13px; font-weight: 400; transition: all 0.18s;
        }
        .mgmt-link svg { width: 14px; height: 14px; fill: currentColor; flex-shrink: 0; opacity: 0.55; }
        .mgmt-link:hover { background: var(--accent-light); color: var(--accent); }
        .mgmt-link:hover svg { opacity: 1; }

        /* PROFILE / FORM */
        .form-card { background: var(--surface); border: 1px solid var(--border); border-radius: var(--radius); padding: 28px; max-width: 600px; box-shadow: var(--shadow-sm); }

        .profile-avatar-area { display: flex; align-items: center; gap: 18px; margin-bottom: 28px; padding-bottom: 24px; border-bottom: 1px solid var(--border); }

        .avatar-lg {
            width: 64px; height: 64px; border-radius: 50%;
            background: linear-gradient(135deg, #0f1f5c, #1a56db);
            display: flex; align-items: center; justify-content: center;
            font-size: 22px; font-weight: 700; color: white; flex-shrink: 0;
        }
        .avatar-meta h3 { font-size: 16px; font-weight: 600; color: var(--ink); margin-bottom: 2px; }
        .avatar-meta p  { font-size: 12px; color: var(--ink-faint); }

        .form-row { display: grid; grid-template-columns: 1fr 1fr; gap: 16px; }
        .form-group { margin-bottom: 18px; }

        .form-label { display: block; font-size: 12px; font-weight: 600; color: var(--ink-muted); letter-spacing: 0.3px; margin-bottom: 7px; text-transform: uppercase; }

        .form-input {
            width: 100%; height: 42px; padding: 0 14px;
            background: var(--surface-alt); border: 1.5px solid var(--border);
            border-radius: var(--radius-sm); color: var(--ink);
            font-family: 'DM Sans', sans-serif; font-size: 13.5px;
            outline: none; transition: border-color 0.2s, background 0.2s, box-shadow 0.2s; -webkit-appearance: none;
        }
        .form-input::placeholder { color: var(--ink-faint); }
        .form-input:focus { border-color: var(--accent); background: var(--surface); box-shadow: 0 0 0 3px rgba(26,86,219,0.1); }

        .btn-primary {
            display: inline-flex; align-items: center; gap: 7px;
            padding: 10px 22px; background: var(--accent); color: white; border: none;
            border-radius: var(--radius-sm);
            font-family: 'DM Sans', sans-serif; font-size: 13.5px; font-weight: 600;
            cursor: pointer; transition: background 0.2s, transform 0.15s, box-shadow 0.2s;
            box-shadow: 0 2px 8px rgba(26,86,219,0.25);
        }
        .btn-primary:hover { background: var(--accent-hover); transform: translateY(-1px); box-shadow: 0 6px 16px rgba(26,86,219,0.3); }
        .btn-primary svg { width: 14px; height: 14px; fill: currentColor; }

        .alert-box { display: flex; align-items: center; gap: 10px; padding: 11px 14px; border-radius: var(--radius-sm); font-size: 13px; font-weight: 500; margin-bottom: 20px; }
        .alert-box svg { width: 15px; height: 15px; fill: currentColor; flex-shrink: 0; }
        .alert-success { background: var(--green-bg); border: 1px solid rgba(5,150,105,0.25); color: var(--green); }
        .alert-error   { background: var(--red-bg);   border: 1px solid rgba(220,38,38,0.25);  color: var(--red); }

        .divider-label {
            font-size: 11px; font-weight: 600; color: var(--ink-faint);
            text-transform: uppercase; letter-spacing: 0.8px;
            margin: 22px 0 16px; display: flex; align-items: center; gap: 10px;
        }
        .divider-label::before, .divider-label::after { content: ''; flex: 1; height: 1px; background: var(--border); }

        /* OVERLAY */
        .sidebar-overlay { display: none; position: fixed; inset: 0; background: rgba(14,17,23,0.4); z-index: 199; backdrop-filter: blur(2px); pointer-events: none; }

        /* RESPONSIVE */
        @media (max-width: 1100px) { .stats-grid { grid-template-columns: repeat(2,1fr); } }
        @media (max-width: 768px) {
            .sidebar { transform: translateX(-100%); }
            .sidebar.open { transform: translateX(0); }
            .sidebar.open ~ .sidebar-overlay { display: block; pointer-events: auto; }
            .topbar { left: 0; }
            .main { margin-left: 0; padding: 20px 16px; }
            .hamburger { display: flex; }
            .mgmt-grid { grid-template-columns: 1fr; }
            .form-row { grid-template-columns: 1fr; }
        }
        @media (max-width: 520px) { .stats-grid { grid-template-columns: 1fr; } }
    </style>
</head>
<body>

<?php
$username  = htmlspecialchars($_SESSION['username']);
$full_name = !empty($admin_data['full_name']) ? htmlspecialchars($admin_data['full_name']) : $username;
$email     = isset($admin_data['email']) ? htmlspecialchars($admin_data['email']) : '';
$initials  = strtoupper(substr($full_name, 0, 1));
?>

<div class="sidebar-overlay" id="overlay" onclick="closeSidebar()"></div>

<aside class="sidebar" id="sidebar">
    <div class="sidebar-logo">
        <div class="logo-mark">
            <svg viewBox="0 0 24 24"><path d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7zm0 9.5c-1.38 0-2.5-1.12-2.5-2.5S10.62 6.5 12 6.5s2.5 1.12 2.5 2.5S13.38 11.5 12 11.5z"/></svg>
        </div>
        <span class="logo-label">JourneyHub Admin</span>
    </div>

    <div class="sidebar-body">
        <div class="sidebar-section-label">Main</div>
        <a href="?page=dashboard" class="nav-item <?= $current_page==='dashboard'?'active':'' ?>" onclick="setPage('dashboard'); return false;">
            <svg viewBox="0 0 24 24"><path d="M3 13h8V3H3v10zm0 8h8v-6H3v6zm10 0h8V11h-8v10zm0-18v6h8V3h-8z"/></svg>
            Dashboard
        </a>
        <a href="?page=profile" class="nav-item <?= $current_page==='profile'?'active':'' ?>" onclick="setPage('profile'); return false;">
            <svg viewBox="0 0 24 24"><path d="M12 12c2.7 0 4.8-2.1 4.8-4.8S14.7 2.4 12 2.4 7.2 4.5 7.2 7.2 9.3 12 12 12zm0 2.4c-3.2 0-9.6 1.6-9.6 4.8v2.4h19.2v-2.4c0-3.2-6.4-4.8-9.6-4.8z"/></svg>
            My Profile
        </a>

        <div class="sidebar-section-label">Management</div>
        <a href="users_add.php" class="nav-item">
            <svg viewBox="0 0 24 24"><path d="M16 11c1.66 0 2.99-1.34 2.99-3S17.66 5 16 5c-1.66 0-3 1.34-3 3s1.34 3 3 3zm-8 0c1.66 0 2.99-1.34 2.99-3S9.66 5 8 5C6.34 5 5 6.34 5 8s1.34 3 3 3zm0 2c-2.33 0-7 1.17-7 3.5V19h14v-2.5c0-2.33-4.67-3.5-7-3.5zm8 0c-.29 0-.62.02-.97.05 1.16.84 1.97 1.97 1.97 3.45V19h6v-2.5c0-2.33-4.67-3.5-7-3.5z"/></svg>
            Users
        </a>
        <a href="hotels_add.php" class="nav-item">
            <svg viewBox="0 0 24 24"><path d="M7 13c1.66 0 3-1.34 3-3S8.66 7 7 7s-3 1.34-3 3 1.34 3 3 3zm12-6h-8v7H3V5H1v15h2v-3h18v3h2v-9c0-2.21-1.79-4-4-4z"/></svg>
            Hotels
        </a>
        <a href="flights_add.php" class="nav-item">
            <svg viewBox="0 0 24 24"><path d="M21 16v-2l-8-5V3.5c0-.83-.67-1.5-1.5-1.5S10 2.67 10 3.5V9l-8 5v2l8-2.5V19l-2 1.5V22l3.5-1 3.5 1v-1.5L13 19v-5.5l8 2.5z"/></svg>
            Flights
        </a>
        <a href="trains_add.php" class="nav-item">
            <svg viewBox="0 0 24 24"><path d="M12 2c-4 0-8 .5-8 4v9.5C4 17.43 5.57 19 7.5 19L6 20.5v.5h2.23l2-2H14l2 2H18v-.5L16.5 19c1.93 0 3.5-1.57 3.5-3.5V6c0-3.5-3.58-4-8-4zm0 2c3.51 0 5.5.48 5.93 1.5H6.07C6.5 4.48 8.49 4 12 4zm-5 9H8v-2h-1V9h2v4zm3.5 3c-.83 0-1.5-.67-1.5-1.5S9.67 13 10.5 13s1.5.67 1.5 1.5S11.33 16 10.5 16zm3 0c-.83 0-1.5-.67-1.5-1.5S12.67 13 13.5 13s1.5.67 1.5 1.5S14.33 16 13.5 16zM16 13h2V9h-3v2h１v2z"/></svg>
            Trains
        </a>

        <div class="sidebar-section-label">System</div>
        
        <a href="adminLogout.php" class="nav-item danger">
            <svg viewBox="0 0 24 24"><path d="M17 7l-1.41 1.41L18.17 11H8v2h10.17l-2.58 2.58L17 17l5-5zM4 5h8V3H4c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h8v-2H4V5z"/></svg>
            Logout
        </a>
    </div>

    <div class="sidebar-footer">
        <div class="sidebar-profile" onclick="setPage('profile')">
            <div class="avatar"><?= $initials ?></div>
            <div style="overflow:hidden;">
                <div class="avatar-name"><?= $full_name ?></div>
                <div class="avatar-role">Administrator</div>
            </div>
        </div>
    </div>
</aside>

<header class="topbar">
    <div class="topbar-left">
        <button class="hamburger" onclick="toggleSidebar()">
            <svg viewBox="0 0 24 24"><path d="M3 18h18v-2H3v2zm0-5h18v-2H3v2zm0-7v2h18V6H3z"/></svg>
        </button>
        <span class="page-title" id="page-title">Dashboard</span>
    </div>
    <div class="topbar-right">
        <a href="adminLogout.php" class="topbar-btn btn-danger-outline">
            <svg viewBox="0 0 24 24"><path d="M17 7l-1.41 1.41L18.17 11H8v2h10.17l-2.58 2.58L17 17l5-5zM4 5h8V3H4c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h8v-2H4V5z"/></svg>
            Logout
        </a>
    </div>
</header>
<main class="main">

    <section class="page-section <?= $current_page==='dashboard'?'active':'' ?>" id="sec-dashboard">
        <div class="section-header">
            <h2>Dashboard Overview</h2>
            <p>Welcome back, <?= $full_name ?>. Here's a snapshot of your platform.</p>
        </div>

        <div class="stats-grid">
            <div class="stat-card blue">
                <div class="stat-icon blue"><svg viewBox="0 0 24 24"><path d="M16 11c1.66 0 2.99-1.34 2.99-3S17.66 5 16 5c-1.66 0-3 1.34-3 3s1.34 3 3 3zm-8 0c1.66 0 2.99-1.34 2.99-3S9.66 5 8 5C6.34 5 5 6.34 5 8s1.34 3 3 3zm0 2c-2.33 0-7 1.17-7 3.5V19h14v-2.5c0-2.33-4.67-3.5-7-3.5z"/></svg></div>
                <div class="stat-value"><?= $total_users ?></div><div class="stat-label">Total Users</div>
            </div>
            <div class="stat-card green">
                <div class="stat-icon green"><svg viewBox="0 0 24 24"><path d="M7 13c1.66 0 3-1.34 3-3S8.66 7 7 7s-3 1.34-3 3 1.34 3 3 3zm12-6h-8v7H3V5H1v15h2v-3h18v3h2v-9c0-2.21-1.79-4-4-4z"/></svg></div>
                <div class="stat-value"><?= $total_hotels ?></div><div class="stat-label">Total Hotels</div>
            </div>
            <div class="stat-card amber">
                <div class="stat-icon amber"><svg viewBox="0 0 24 24"><path d="M21 16v-2l-8-5V3.5c0-.83-.67-1.5-1.5-1.5S10 2.67 10 3.5V9l-8 5v2l8-2.5V19l-2 1.5V22l3.5-1 3.5 1v-1.5L13 19v-5.5l8 2.5z"/></svg></div>
                <div class="stat-value"><?= $total_flights ?></div><div class="stat-label">Total Flights</div>
            </div>
            <div class="stat-card violet">
                <div class="stat-icon violet"><svg viewBox="0 0 24 24"><path d="M12 2c-4 0-8 .5-8 4v9.5C4 17.43 5.57 19 7.5 19L6 20.5v.5h2.23l2-2H14l2 2H18v-.5L16.5 19c1.93 0 3.5-1.57 3.5-3.5V6c0-3.5-3.58-4-8-4z"/></svg></div>
                <div class="stat-value"><?= $total_trains ?></div><div class="stat-label">Total Trains</div>
            </div>
        </div>

        <div class="mgmt-card" style="margin-bottom: 28px; padding: 24px;">
            <div class="mgmt-title" style="font-size: 16px; margin-bottom: 20px;">Bookings Distribution (Hotels vs Flights vs Trains)</div>
            <div style="height: 300px; position: relative;">
                <canvas id="bookingsChart"></canvas>
            </div>
        </div>

        <div class="mgmt-grid">
            <div class="mgmt-card">
                <div class="mgmt-card-head">
                    <div class="mgmt-icon blue"><svg viewBox="0 0 24 24"><path d="M16 11c1.66 0 2.99-1.34 2.99-3S17.66 5 16 5c-1.66 0-3 1.34-3 3s1.34 3 3 3zm-8 0c1.66 0 2.99-1.34 2.99-3S9.66 5 8 5C6.34 5 5 6.34 5 8s1.34 3 3 3zm0 2c-2.33 0-7 1.17-7 3.5V19h14v-2.5c0-2.33-4.67-3.5-7-3.5z"/></svg></div>
                    <div><div class="mgmt-title">User Management</div><div class="mgmt-desc">Manage platform users &amp; roles</div></div>
                </div>
                <div class="mgmt-links">
                    <a href="users_add.php" class="mgmt-link"><svg viewBox="0 0 24 24"><path d="M15 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm-9-2V7H4v3H1v2h3v3h2v-3h3v-2H6zm9 4c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z"/></svg>Manage Users</a>
                </div>
            </div>

            <div class="mgmt-card">
                <div class="mgmt-card-head">
                    <div class="mgmt-icon green"><svg viewBox="0 0 24 24"><path d="M7 13c1.66 0 3-1.34 3-3S8.66 7 7 7s-3 1.34-3 3 1.34 3 3 3zm12-6h-8v7H3V5H1v15h2v-3h18v3h2v-9c0-2.21-1.79-4-4-4z"/></svg></div>
                    <div><div class="mgmt-title">Hotels</div><div class="mgmt-desc">Add listings &amp; review bookings</div></div>
                </div>
                <div class="mgmt-links">
                    <a href="hotels_add.php" class="mgmt-link"><svg viewBox="0 0 24 24"><path d="M19 13h-6v6h-2v-6H5v-2h6V5h2v6h6v2z"/></svg>Add Hotel</a>
                    <a href="hotelbookings_view.php" class="mgmt-link"><svg viewBox="0 0 24 24"><path d="M19 3H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2zm-5 14H7v-2h7v2zm3-4H7v-2h10v2zm0-4H7V7h10v2z"/></svg>View Bookings</a>
                </div>
            </div>

            <div class="mgmt-card">
                <div class="mgmt-card-head">
                    <div class="mgmt-icon red"><svg viewBox="0 0 24 24"><path d="M21 16v-2l-8-5V3.5c0-.83-.67-1.5-1.5-1.5S10 2.67 10 3.5V9l-8 5v2l8-2.5V19l-2 1.5V22l3.5-1 3.5 1v-1.5L13 19v-5.5l8 2.5z"/></svg></div>
                    <div><div class="mgmt-title">Flights</div><div class="mgmt-desc">Manage flights &amp; reservations</div></div>
                </div>
                <div class="mgmt-links">
                    <a href="flights_add.php" class="mgmt-link"><svg viewBox="0 0 24 24"><path d="M19 13h-6v6h-2v-6H5v-2h6V5h2v6h6v2z"/></svg>Add Flight</a>
                    <a href="flightbookings_view.php" class="mgmt-link"><svg viewBox="0 0 24 24"><path d="M19 3H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2zm-5 14H7v-2h7v2zm3-4H7v-2h10v2zm0-4H7V7h10v2z"/></svg>View Bookings</a>
                </div>
            </div>

            <div class="mgmt-card">
                <div class="mgmt-card-head">
                    <div class="mgmt-icon amber"><svg viewBox="0 0 24 24"><path d="M12 2c-4 0-8 .5-8 4v9.5C4 17.43 5.57 19 7.5 19L6 20.5v.5h2.23l2-2H14l2 2H18v-.5L16.5 19c1.93 0 3.5-1.57 3.5-3.5V6c0-3.5-3.58-4-8-4z"/></svg></div>
                    <div><div class="mgmt-title">Trains</div><div class="mgmt-desc">Manage trains &amp; reservations</div></div>
                </div>
                <div class="mgmt-links">
                    <a href="trains_add.php" class="mgmt-link"><svg viewBox="0 0 24 24"><path d="M19 13h-6v6h-2v-6H5v-2h6V5h2v6h6v2z"/></svg>Add Train</a>
                    <a href="trainbookings_view.php" class="mgmt-link"><svg viewBox="0 0 24 24"><path d="M19 3H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2zm-5 14H7v-2h7v2zm3-4H7v-2h10v2zm0-4H7V7h10v2z"/></svg>View Bookings</a>
                </div>
            </div>
        </div>
    </section>

    <section class="page-section <?= $current_page==='profile'?'active':'' ?>" id="sec-profile">
        <div class="section-header">
            <h2>Admin Profile</h2>
            <p>View and update your account information.</p>
        </div>

        <?php if($update_success): ?>
        <div class="alert-box alert-success" style="max-width:600px;margin-bottom:16px;">
            <svg viewBox="0 0 24 24"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 15l-5-5 1.41-1.41L10 14.17l7.59-7.59L19 8l-9 9z"/></svg>
            <?= htmlspecialchars($update_success) ?>
        </div>
        <?php endif; ?>
        <?php if($update_error): ?>
        <div class="alert-box alert-error" style="max-width:600px;margin-bottom:16px;">
            <svg viewBox="0 0 24 24"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm1 15h-2v-2h2v2zm0-4h-2V7h2v6z"/></svg>
            <?= htmlspecialchars($update_error) ?>
        </div>
        <?php endif; ?>

        <div class="form-card">
            <div class="profile-avatar-area">
                <div class="avatar-lg"><?= $initials ?></div>
                <div class="avatar-meta">
                    <h3><?= $full_name ?></h3>
                    <p>@<?= $username ?> &nbsp;·&nbsp; Administrator</p>
                </div>
            </div>
            <form method="post" action="?page=profile">
                <div class="divider-label">Account Details</div>
                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Full Name</label>
                        <input type="text" name="full_name" class="form-input" placeholder="Your full name" value="<?= isset($admin_data['full_name']) ? htmlspecialchars($admin_data['full_name']) : '' ?>">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Email Address</label>
                        <input type="email" name="email" class="form-input" placeholder="admin@example.com" value="<?= $email ?>">
                    </div>
                </div>
                <div class="divider-label">Change Username</div>
                <div class="form-group">
                    <label class="form-label">Username</label>
                    <input type="text" name="new_username" class="form-input" value="<?= $username ?>">
                </div>
                <div class="divider-label">Security</div>
                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">New Password</label>
                        <input type="password" name="new_password" class="form-input" placeholder="••••••••">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Confirm Password</label>
                        <input type="password" name="confirm_password" class="form-input" placeholder="••••••••">
                    </div>
                </div>
                <button type="submit" name="update_profile" class="btn-primary">Update Profile</button>
            </form>
        </div>
    </section>
</main>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    function toggleSidebar() {
        const sidebar = document.getElementById('sidebar');
        const overlay = document.getElementById('overlay');
        sidebar.classList.toggle('open');
        overlay.style.display = sidebar.classList.contains('open') ? 'block' : 'none';
        overlay.style.pointerEvents = sidebar.classList.contains('open') ? 'auto' : 'none';
    }
    function closeSidebar() {
        const sidebar = document.getElementById('sidebar');
        const overlay = document.getElementById('overlay');
        sidebar.classList.remove('open');
        overlay.style.display = 'none';
        overlay.style.pointerEvents = 'none';
    }
    function setPage(page)   { window.location.href = '?page=' + page; }

    // Chart.js implementation
    const ctx = document.getElementById('bookingsChart').getContext('2d');
    const bookingsChart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: ['Hotels', 'Flights', 'Trains'],
            datasets: [{
                label: 'Total Bookings',
                data: [<?= $h_bookings ?>, <?= $f_bookings ?>, <?= $t_bookings ?>],
                backgroundColor: ['#059669', '#dc2626', '#d97706'],
                borderRadius: 6,
                borderWidth: 0,
                barThickness: 40
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: { precision: 0 },
                    grid: { color: '#edf0f4' }
                },
                x: {
                    grid: { display: false }
                }
            },
            plugins: {
                legend: { display: false }
            }
        }
    });
</script>

</body>
</html>