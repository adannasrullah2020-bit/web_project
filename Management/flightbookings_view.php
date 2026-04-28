<?php
session_start();

// Redirect if not logged in
if(!isset($_SESSION['username'])){
    header('Location: adminLogin.php');
    exit();
}

// Database Connection
$servername = "localhost";
$db_user = "root"; // Renamed to avoid conflict with booking username
$db_pass = "";
$dbname = "travel";

$conn = mysqli_connect($servername, $db_user, $db_pass, $dbname);
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Initialize Variables for Form Population
$bookingID = ""; $booking_username = ""; $date = ""; $cancelled = "";
$origin = ""; $destination = ""; $passengers = ""; $type = "";

$message = '';
$messageType = '';

// --- HANDLE SEARCH ---
if(isset($_POST['search'])){
    $searchID = mysqli_real_escape_string($conn, $_POST['bookingID']);
    if($searchID != ""){
        $search_query = "SELECT * FROM flightbookings WHERE bookingID = '$searchID'";
        $search_result = mysqli_query($conn, $search_query);
        
        if(mysqli_num_rows($search_result) > 0){
            $row = mysqli_fetch_assoc($search_result);
            $bookingID        = $row['bookingID'];
            $booking_username = $row['username'];
            $date             = $row['date'];
            $cancelled        = $row['cancelled'];
            $origin           = $row['origin'];
            $destination      = $row['destination'];
            $passengers       = $row['passengers'];
            $type             = $row['type'];
            
            $message = "Booking found. You can now update its status or details.";
            $messageType = "info";
        } else {
            $message = "No booking found with ID: " . htmlspecialchars($searchID);
            $messageType = "warning";
        }
    } else {
        $message = "Please enter a Booking ID to search.";
        $messageType = "danger";
    }
}

// --- HANDLE UPDATE ---
if(isset($_POST['update'])){
    $b_id = mysqli_real_escape_string($conn, $_POST['bookingID']);
    if($b_id != ""){
        $b_user   = mysqli_real_escape_string($conn, $_POST['username']);
        $b_date   = mysqli_real_escape_string($conn, $_POST['date']);
        $b_status = mysqli_real_escape_string($conn, $_POST['cancelled']);
        $b_origin = mysqli_real_escape_string($conn, $_POST['origin']);
        $b_dest   = mysqli_real_escape_string($conn, $_POST['destination']);
        $b_pass   = mysqli_real_escape_string($conn, $_POST['passengers']);
        $b_type   = mysqli_real_escape_string($conn, $_POST['type']);

        $update_query = "UPDATE `flightbookings` SET username='$b_user', date='$b_date', cancelled='$b_status', origin='$b_origin', destination='$b_dest', passengers='$b_pass', type='$b_type' WHERE bookingID='$b_id'";
        
        if(mysqli_query($conn, $update_query)){
            if(mysqli_affected_rows($conn) > 0){
                $message = "Booking updated successfully!";
                $messageType = "success";
                
                // Keep the updated values in the form
                $bookingID = $b_id; $booking_username = $b_user; $date = $b_date; 
                $cancelled = $b_status; $origin = $b_origin; $destination = $b_dest; 
                $passengers = $b_pass; $type = $b_type;
            } else {
                $message = "No changes were made to the booking.";
                $messageType = "info";
            }
        } else {
            $message = "Error updating data: " . mysqli_error($conn);
            $messageType = "danger";
        }
    } else {
        $message = "Booking ID is required to update a record.";
        $messageType = "danger";
    }
}

// --- HANDLE DELETE ---
if(isset($_POST['delete'])){
    $b_id = mysqli_real_escape_string($conn, $_POST['bookingID']);
    if($b_id != ""){
        $delete_query = "DELETE FROM `flightbookings` WHERE bookingID = '$b_id'";
        if(mysqli_query($conn, $delete_query)){
            if(mysqli_affected_rows($conn) > 0){
                $message = "Booking deleted successfully!";
                $messageType = "success";
                // Clear form
                $bookingID = ""; $booking_username = ""; $date = ""; $cancelled = "";
                $origin = ""; $destination = ""; $passengers = ""; $type = "";
            } else {
                $message = "Booking ID not found for deletion.";
                $messageType = "warning";
            }
        } else {
            $message = "Error deleting booking: " . mysqli_error($conn);
            $messageType = "danger";
        }
    } else {
        $message = "Booking ID is required to delete a record.";
        $messageType = "danger";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Admin Panel | Manage Flight Bookings</title>
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Courgette&family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

    <style>
        body {
            background-color: #f8fafc;
            font-family: 'Inter', sans-serif;
            color: #334155;
        }

        /* Top Navigation */
        .navbar-custom {
            background: linear-gradient(135deg, #1e3a8a, #2563eb);
            padding: 15px 0;
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
        }
        
        .navbar-brand {
            font-family: 'Courgette', cursive;
            font-size: 26px;
            color: #ffffff !important;
        }

        .nav-link {
            color: rgba(255, 255, 255, 0.85) !important;
            font-weight: 500;
            transition: color 0.3s;
        }

        .nav-link:hover {
            color: #ffffff !important;
        }

        /* Dashboard Cards */
        .dashboard-card {
            background: #ffffff;
            border-radius: 12px;
            border: none;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
            padding: 25px;
            margin-bottom: 24px;
        }

        .card-header-title {
            font-weight: 700;
            color: #1e293b;
            margin-bottom: 20px;
            padding-bottom: 15px;
            border-bottom: 1px solid #e2e8f0;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        /* Form Styling */
        .form-label {
            font-weight: 600;
            font-size: 13px;
            color: #475569;
            margin-bottom: 4px;
        }

        .form-control, .form-select {
            border-radius: 8px;
            padding: 8px 12px;
            border: 1px solid #cbd5e1;
            font-size: 14px;
        }
        
        .form-control:focus, .form-select:focus {
            box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.1);
            border-color: #2563eb;
        }

        .search-highlight {
            background-color: #f1f5f9;
            border: 2px solid #94a3b8;
        }

        /* Table Styling */
        .table th {
            background-color: #f1f5f9;
            color: #475569;
            font-weight: 600;
            font-size: 12px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            white-space: nowrap;
        }
        
        .table td {
            font-size: 14px;
            vertical-align: middle;
            white-space: nowrap;
        }
    </style>
</head>
<body>

    <!-- Top Navigation -->
    <nav class="navbar navbar-expand-lg navbar-custom mb-4">
        <div class="container-fluid px-4">
            <a class="navbar-brand" href="Home.php">
                <i class="bi bi-globe-americas me-2"></i>TMS Admin
            </a>
            <button class="navbar-toggler bg-light" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto align-items-center">
                    <li class="nav-item"><a class="nav-link" href="Home.php"><i class="bi bi-house-door me-1"></i> Dashboard</a></li>
                    <li class="nav-item"><a class="nav-link" href="users_add.php">Users</a></li>
                    <li class="nav-item"><a class="nav-link" href="hotels_add.php">Hotels</a></li>
                    <li class="nav-item"><a class="nav-link" href="flights_add.php">Flights</a></li>
                    <li class="nav-item"><a class="nav-link active fw-bold text-white" href="flightbookings_view.php">Flight Bookings</a></li>
                    <li class="nav-item"><a class="nav-link" href="trains_add.php">Trains</a></li>
                    <li class="nav-item ms-3">
                        <a href="adminLogout.php" class="btn btn-danger btn-sm rounded-pill px-3 fw-bold">
                            <i class="bi bi-box-arrow-right me-1"></i> Logout
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <div class="container-fluid px-4 pb-5">
        
        <!-- Alerts -->
        <?php if($message != ''): ?>
            <div class="alert alert-<?php echo $messageType; ?> alert-dismissible fade show shadow-sm" role="alert">
                <i class="bi bi-info-circle-fill me-2"></i> <?php echo $message; ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>

        <div class="row g-4">
            
            <!-- LEFT COLUMN: Search & Edit Bookings -->
            <div class="col-lg-4">
                <div class="dashboard-card h-100">
                    <h4 class="card-header-title">
                        <i class="bi bi-pencil-square text-warning"></i> Manage Flight Booking
                    </h4>
                    
                    <form method="post" action="">
                        <div class="row g-3">
                            
                            <!-- Search Bar -->
                            <div class="col-12 mb-2 border-bottom pb-3">
                                <label class="form-label text-primary fw-bold">Target Booking ID</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-white"><i class="bi bi-ticket-detailed text-muted"></i></span>
                                    <input type="number" name="bookingID" class="form-control search-highlight fw-bold" placeholder="Enter Booking ID" value="<?php echo htmlspecialchars($bookingID); ?>">
                                    <button type="submit" name="search" class="btn btn-secondary">Search</button>
                                </div>
                            </div>

                            <!-- Editable Fields -->
                            <div class="col-12">
                                <label class="form-label">Username</label>
                                <input type="text" name="username" class="form-control" value="<?php echo htmlspecialchars($booking_username); ?>">
                            </div>
                            
                            <div class="col-md-6">
                                <label class="form-label">Date</label>
                                <input type="date" name="date" class="form-control" value="<?php echo htmlspecialchars($date); ?>">
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Passengers</label>
                                <input type="number" name="passengers" class="form-control" value="<?php echo htmlspecialchars($passengers); ?>">
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Origin</label>
                                <input type="text" name="origin" class="form-control" value="<?php echo htmlspecialchars($origin); ?>">
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Destination</label>
                                <input type="text" name="destination" class="form-control" value="<?php echo htmlspecialchars($destination); ?>">
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Flight Type</label>
                                <input type="text" name="type" class="form-control" placeholder="e.g. One-way, Return" value="<?php echo htmlspecialchars($type); ?>">
                            </div>
                            
                            <div class="col-md-6">
                                <label class="form-label">Booking Status</label>
                                <select name="cancelled" class="form-select fw-bold">
                                    <option value="Active" <?php if(strtolower($cancelled) == 'active' || strtolower($cancelled) == 'no') echo 'selected'; ?>>Active</option>
                                    <option value="Pending" <?php if(strtolower($cancelled) == 'pending') echo 'selected'; ?>>Pending</option>
                                    <option value="Cancelled" <?php if(strtolower($cancelled) == 'cancelled' || strtolower($cancelled) == 'yes') echo 'selected'; ?>>Cancelled</option>
                                </select>
                            </div>

                            <!-- Action Buttons -->
                            <div class="col-12 mt-4">
                                <div class="d-flex gap-2">
                                    <button type="submit" name="update" class="btn btn-warning flex-grow-1 fw-bold text-dark">
                                        <i class="bi bi-arrow-clockwise me-1"></i> Update
                                    </button>
                                    <button type="submit" name="delete" class="btn btn-danger flex-grow-1 fw-bold" onclick="return confirm('Are you sure you want to permanently delete this flight booking?');">
                                        <i class="bi bi-trash me-1"></i> Delete
                                    </button>
                                </div>
                            </div>

                        </div>
                    </form>
                </div>
            </div>

            <!-- RIGHT COLUMN: All Bookings Data Table -->
            <div class="col-lg-8">
                <div class="dashboard-card h-100">
                    <h4 class="card-header-title">
                        <i class="bi bi-journal-text text-primary"></i> All Flight Bookings Database
                    </h4>
                    
                    <div class="table-responsive">
                        <table class="table table-hover table-striped align-middle border">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>User</th>
                                    <th>Route</th>
                                    <th>Date</th>
                                    <th>Pax</th>
                                    <th>Type</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $sql = "SELECT bookingID, username, date, cancelled, origin, destination, passengers, type FROM flightbookings ORDER BY bookingID DESC";
                                $result = mysqli_query($conn, $sql);

                                if (mysqli_num_rows($result) > 0) {
                                    while($row = mysqli_fetch_assoc($result)) {
                                        echo "<tr>";
                                        echo "<td><span class='badge bg-secondary'>#" . htmlspecialchars($row['bookingID']) . "</span></td>";
                                        echo "<td class='fw-bold'>" . htmlspecialchars($row['username']) . "</td>";
                                        echo "<td>" . htmlspecialchars($row['origin']) . " <i class='bi bi-arrow-right text-muted mx-1'></i> " . htmlspecialchars($row['destination']) . "</td>";
                                        echo "<td>" . htmlspecialchars($row['date']) . "</td>";
                                        echo "<td>" . htmlspecialchars($row['passengers']) . " <i class='bi bi-person-fill text-muted'></i></td>";
                                        echo "<td>" . htmlspecialchars($row['type']) . "</td>";
                                        
                                        // Display visual badge based on Status
                                        $status = strtolower($row['cancelled']);
                                        if($status == 'cancelled' || $status == 'yes' || $status == 'true') {
                                            echo "<td><span class='badge bg-danger'>Cancelled</span></td>";
                                        } elseif($status == 'pending') {
                                            echo "<td><span class='badge bg-warning text-dark'>Pending</span></td>";
                                        } else {
                                            echo "<td><span class='badge bg-success'>Active</span></td>";
                                        }
                                        
                                        echo "</tr>";
                                    }
                                } else {
                                    echo "<tr><td colspan='7' class='text-center text-muted py-4'>No flight bookings found in the database.</td></tr>";
                                }
                                ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

        </div>
    </div>

    <!-- Bootstrap 5 JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
<?php 
// Close connection at the very end
mysqli_close($conn); 
?>
