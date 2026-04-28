<?php
session_start();

// Redirect if not logged in
if(!isset($_SESSION['username'])){
    header('Location: adminLogin.php');
    exit();
}

// Database Connection
$servername = "localhost";
$db_user = "root"; // Database User
$db_pass = "";
$dbname = "travel";

$conn = mysqli_connect($servername, $db_user, $db_pass, $dbname);
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Initialize Variables for Form
$bookingID = ""; $hotelName = ""; $date = ""; $booking_username = ""; $cancelled = "";
$message = '';
$messageType = '';

// --- HANDLE SEARCH ---
if(isset($_POST['search'])){
    $searchID = mysqli_real_escape_string($conn, $_POST['bookingID']);
    if($searchID != ""){
        $search_query = "SELECT * FROM hotelbookings WHERE bookingID = '$searchID'";
        $search_result = mysqli_query($conn, $search_query);
        
        if(mysqli_num_rows($search_result) > 0){
            $row = mysqli_fetch_assoc($search_result);
            $bookingID        = $row['bookingID'];
            $hotelName        = $row['hotelName'];
            $date             = $row['date'];
            $booking_username = $row['username'];
            $cancelled        = $row['cancelled'];
            
            $message = "Booking found. You can now modify the details.";
            $messageType = "info";
        } else {
            $message = "No hotel booking found with ID: " . htmlspecialchars($searchID);
            $messageType = "warning";
        }
    }
}

// --- HANDLE UPDATE ---
if(isset($_POST['update'])){
    $b_id = mysqli_real_escape_string($conn, $_POST['bookingID']);
    if($b_id != ""){
        $u_hotel  = mysqli_real_escape_string($conn, $_POST['hotelName']);
        $u_date   = mysqli_real_escape_string($conn, $_POST['date']);
        $u_user   = mysqli_real_escape_string($conn, $_POST['username']);
        $u_status = mysqli_real_escape_string($conn, $_POST['cancelled']);

        $update_query = "UPDATE `hotelbookings` SET hotelName='$u_hotel', date='$u_date', username='$u_user', cancelled='$u_status' WHERE bookingID='$b_id'";
        
        if(mysqli_query($conn, $update_query)){
            $message = "Hotel booking updated successfully!";
            $messageType = "success";
            // Refresh variables
            $bookingID = $b_id; $hotelName = $u_hotel; $date = $u_date; $booking_username = $u_user; $cancelled = $u_status;
        } else {
            $message = "Error updating data: " . mysqli_error($conn);
            $messageType = "danger";
        }
    }
}

// --- HANDLE DELETE ---
if(isset($_POST['delete'])){
    $b_id = mysqli_real_escape_string($conn, $_POST['bookingID']);
    if($b_id != ""){
        $delete_query = "DELETE FROM `hotelbookings` WHERE bookingID = '$b_id'";
        if(mysqli_query($conn, $delete_query)){
            $message = "Booking deleted successfully!";
            $messageType = "success";
            $bookingID = ""; $hotelName = ""; $date = ""; $booking_username = ""; $cancelled = "";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Admin Panel | Hotel Bookings</title>
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Courgette&family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

    <style>
        body { background-color: #f8fafc; font-family: 'Inter', sans-serif; color: #334155; }
        .navbar-custom { background: linear-gradient(135deg, #1e3a8a, #2563eb); padding: 15px 0; box-shadow: 0 4px 12px rgba(0,0,0,0.1); }
        .navbar-brand { font-family: 'Courgette', cursive; font-size: 26px; color: #ffffff !important; }
        .nav-link { color: rgba(255, 255, 255, 0.85) !important; font-weight: 500; transition: color 0.3s; }
        .nav-link:hover { color: #ffffff !important; }
        .dashboard-card { background: #ffffff; border-radius: 12px; border: none; box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05); padding: 25px; margin-bottom: 24px; }
        .card-header-title { font-weight: 700; color: #1e293b; margin-bottom: 20px; padding-bottom: 15px; border-bottom: 1px solid #e2e8f0; display: flex; align-items: center; gap: 10px; }
        .form-label { font-weight: 600; font-size: 13px; color: #475569; margin-bottom: 4px; }
        .form-control, .form-select { border-radius: 8px; padding: 10px 12px; border: 1px solid #cbd5e1; font-size: 14px; }
        .search-highlight { background-color: #f1f5f9; border: 2px solid #94a3b8; }
        .table th { background-color: #f1f5f9; color: #475569; font-weight: 600; font-size: 12px; text-transform: uppercase; white-space: nowrap; }
        .table td { font-size: 14px; vertical-align: middle; white-space: nowrap; }
    </style>
</head>
<body>

    <!-- Top Navigation -->
    <nav class="navbar navbar-expand-lg navbar-custom mb-4">
        <div class="container-fluid px-4">
            <a class="navbar-brand" href="Home.php"><i class="bi bi-globe-americas me-2"></i>JourneyHub Admin</a>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto align-items-center">
                    <li class="nav-item"><a class="nav-link" href="Home.php">Dashboard</a></li>
                    <li class="nav-item"><a class="nav-link active fw-bold text-white" href="hotelbookings_view.php">Hotel Bookings</a></li>
                    <li class="nav-item ms-3">
                        <a href="adminLogout.php" class="btn btn-danger btn-sm rounded-pill px-4 fw-bold">Logout</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <div class="container-fluid px-4 pb-5">
        
        <?php if($message != ''): ?>
            <div class="alert alert-<?php echo $messageType; ?> alert-dismissible fade show shadow-sm" role="alert">
                <i class="bi bi-info-circle-fill me-2"></i> <?php echo $message; ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <div class="row g-4">
            
            <!-- MANAGEMENT FORM (LEFT) -->
            <div class="col-lg-4">
                <div class="dashboard-card h-100">
                    <h4 class="card-header-title"><i class="bi bi-pencil-square text-warning"></i> Manage Booking</h4>
                    <form method="post" action="">
                        <div class="row g-3">
                            <div class="col-12 border-bottom pb-3 mb-2">
                                <label class="form-label text-primary fw-bold">Search Booking ID</label>
                                <div class="input-group">
                                    <input type="number" name="bookingID" class="form-control search-highlight fw-bold" placeholder="e.g. 101" value="<?php echo $bookingID; ?>">
                                    <button type="submit" name="search" class="btn btn-secondary">Search</button>
                                </div>
                            </div>

                            <div class="col-12">
                                <label class="form-label">Guest Username</label>
                                <input type="text" name="username" class="form-control" value="<?php echo htmlspecialchars($booking_username); ?>">
                            </div>
                            
                            <div class="col-12">
                                <label class="form-label">Hotel Name</label>
                                <input type="text" name="hotelName" class="form-control" value="<?php echo htmlspecialchars($hotelName); ?>">
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Check-In Date</label>
                                <input type="date" name="date" class="form-control" value="<?php echo $date; ?>">
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Booking Status</label>
                                <select name="cancelled" class="form-select fw-bold">
                                    <option value="Active" <?php if(strtolower($cancelled) == 'active' || $cancelled == 'No') echo 'selected'; ?>>Active</option>
                                    <option value="Pending" <?php if(strtolower($cancelled) == 'pending') echo 'selected'; ?>>Pending</option>
                                    <option value="Cancelled" <?php if(strtolower($cancelled) == 'cancelled' || $cancelled == 'Yes') echo 'selected'; ?>>Cancelled</option>
                                </select>
                            </div>

                            <div class="col-12 mt-4 d-flex gap-2">
                                <button type="submit" name="update" class="btn btn-warning flex-grow-1 fw-bold text-dark">Update Record</button>
                                <button type="submit" name="delete" class="btn btn-danger flex-grow-1 fw-bold" onclick="return confirm('Confirm permanent deletion?')">Delete</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <!-- DATA TABLE (RIGHT) -->
            <div class="col-lg-8">
                <div class="dashboard-card h-100">
                    <h4 class="card-header-title"><i class="bi bi-journal-text text-primary"></i> Hotel Booking History</h4>
                    <div class="table-responsive">
                        <table class="table table-hover table-striped align-middle border">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Guest</th>
                                    <th>Hotel</th>
                                    <th>Check-In Date</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $sql = "SELECT * FROM hotelbookings ORDER BY bookingID DESC";
                                $result = mysqli_query($conn, $sql);
                                if (mysqli_num_rows($result) > 0) {
                                    while($row = mysqli_fetch_assoc($result)) {
                                        echo "<tr>";
                                        echo "<td><span class='badge bg-secondary'>#{$row['bookingID']}</span></td>";
                                        echo "<td class='fw-bold'>{$row['username']}</td>";
                                        echo "<td class='text-primary'>{$row['hotelName']}</td>";
                                        echo "<td>{$row['date']}</td>";
                                        
                                        $st = strtolower($row['cancelled']);
                                        if($st == 'cancelled' || $st == 'yes') echo "<td><span class='badge bg-danger text-white'>Cancelled</span></td>";
                                        elseif($st == 'pending') echo "<td><span class='badge bg-warning text-dark'>Pending</span></td>";
                                        else echo "<td><span class='badge bg-success text-white'>Active</span></td>";
                                        
                                        echo "</tr>";
                                    }
                                } else {
                                    echo "<tr><td colspan='5' class='text-center text-muted py-4'>No hotel bookings found.</td></tr>";
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
