<?php
session_start();

// Redirect if not logged in
if(!isset($_SESSION['username'])){
    header('Location: adminLogin.php');
    exit();
}

// Database Connection
require_once '../config.php';

$message = '';
$messageType = '';

// --- HANDLE DELETE ---
if(isset($_POST['delete'])){
    $uid = mysqli_real_escape_string($conn, $_POST['UserID']);
    if($uid != ""){
        $delete_query = "DELETE FROM `users` WHERE UserID = '$uid'";
        if(mysqli_query($conn, $delete_query)){
            if(mysqli_affected_rows($conn) > 0){
                $message = "User deleted successfully!";
                $messageType = "success";
            } else {
                $message = "User ID not found for deletion.";
                $messageType = "warning";
            }
        } else {
            $message = "Error deleting user: " . mysqli_error($conn);
            $messageType = "danger";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Admin Panel | Manage Users</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Courgette&family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body { background-color: #f8fafc; font-family: 'Inter', sans-serif; color: #334155; }
        .navbar-custom { background: linear-gradient(135deg, #1e3a8a, #2563eb); padding: 15px 0; box-shadow: 0 4px 12px rgba(0,0,0,0.1); }
        .navbar-brand { font-family: 'Courgette', cursive; font-size: 26px; color: #ffffff !important; }
        .nav-link { color: rgba(255, 255, 255, 0.85) !important; font-weight: 500; transition: color 0.3s; }
        .nav-link:hover { color: #ffffff !important; }
        .dashboard-card { background: #ffffff; border-radius: 12px; border: none; box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05); padding: 25px; margin-bottom: 24px; }
        .card-header-title { font-weight: 700; color: #1e293b; margin-bottom: 20px; padding-bottom: 15px; border-bottom: 1px solid #e2e8f0; display: flex; align-items: center; gap: 10px; }
        .form-label { font-weight: 600; font-size: 14px; color: #475569; margin-bottom: 6px; }
        .form-control { border-radius: 8px; padding: 10px 15px; border: 1px solid #cbd5e1; font-size: 14px; }
        .form-control:focus { box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.1); border-color: #2563eb; }
        .table th { background-color: #f1f5f9; color: #475569; font-weight: 600; font-size: 13px; text-transform: uppercase; letter-spacing: 0.5px; }
        .table td { font-size: 14px; vertical-align: middle; }
        .action-btn { padding: 5px 10px; font-size: 12px; }
    </style>
</head>
<body>

    <nav class="navbar navbar-expand-lg navbar-custom mb-4">
        <div class="container-fluid px-4">
            <a class="navbar-brand" href="Home.php"><i class="bi bi-globe-americas me-2"></i>JourneyHub Admin</a>
            <button class="navbar-toggler bg-light" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto align-items-center">
                    <li class="nav-item"><a class="nav-link" href="Home.php"><i class="bi bi-house-door me-1"></i> Dashboard</a></li>
                    <li class="nav-item"><a class="nav-link active fw-bold text-white" href="users_add.php">Users</a></li>
                    <li class="nav-item"><a class="nav-link" href="hotels_add.php">Hotels</a></li>
                    <li class="nav-item"><a class="nav-link" href="flights_add.php">Flights</a></li>
                    <li class="nav-item"><a class="nav-link" href="trains_add.php">Trains</a></li>
                    <li class="nav-item ms-3"><a href="adminLogout.php" class="btn btn-danger btn-sm rounded-pill px-3 fw-bold"><i class="bi bi-box-arrow-right me-1"></i> Logout</a></li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container-fluid px-4 pb-5">
        <?php if($message != ''): ?>
            <div class="alert alert-<?php echo $messageType; ?> alert-dismissible fade show shadow-sm" role="alert">
                <i class="bi bi-info-circle-fill me-2"></i> <?php echo $message; ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <div class="row g-4">
            <!-- FULL WIDTH: Users Management -->
            <div class="col-12">
                <div class="dashboard-card">
                    <h4 class="card-header-title"><i class="bi bi-people text-success"></i> Registered Users Management</h4>
                    <div class="table-responsive">
                        <table class="table table-hover table-striped align-middle border">
                            <thead>
                                <tr><th>ID</th><th>Full Name</th><th>Username</th><th>Phone</th><th>Location</th><th>Reg. Date</th><th class="text-center">Action</th></tr>
                            </thead>
                            <tbody>
                                <?php
                                $res = mysqli_query($conn, "SELECT UserID, FullName, Username, Phone, City, State, Date FROM users ORDER BY UserID DESC");
                                if(mysqli_num_rows($res) > 0) {
                                    while($row = mysqli_fetch_assoc($res)) {
                                        echo "<tr>";
                                        echo "<td><span class='badge bg-secondary'>#{$row['UserID']}</span></td>";
                                        echo "<td class='fw-bold'>" . htmlspecialchars($row['FullName']) . "</td>";
                                        echo "<td>" . htmlspecialchars($row['Username']) . "</td>";
                                        echo "<td>" . htmlspecialchars($row['Phone']) . "</td>";
                                        echo "<td>" . htmlspecialchars($row['City']) . ", " . htmlspecialchars($row['State']) . "</td>";
                                        echo "<td>" . htmlspecialchars($row['Date']) . "</td>";
                                        echo "<td class='text-center'>";
                                        echo "<form method='post' action='' style='display:inline;'>";
                                        echo "<input type='hidden' name='UserID' value='" . htmlspecialchars($row['UserID']) . "'>";
                                        echo "<button type='submit' name='delete' class='btn btn-danger action-btn' onclick=\"return confirm('Are you sure you want to delete this user?');\"><i class='bi bi-trash'></i> Delete</button>";
                                        echo "</form>";
                                        echo "</td>";
                                        echo "</tr>";
                                    }
                                } else {
                                    echo "<tr><td colspan='7' class='text-center text-muted py-4'>No users found in the database.</td></tr>";
                                }
                                ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
<?php mysqli_close($conn); ?>
