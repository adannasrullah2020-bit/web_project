<?php
session_start();

// Redirect if not logged in
if(!isset($_SESSION['username'])){
    header('Location: adminLogin.php');
    exit();
}

// Database connection
require_once '../config.php';

// Initialize Variables
$flight_no = ""; $origin = ""; $destination = ""; $distance = ""; $fare = ""; 
$class = ""; $seats_available = ""; $departs = ""; $arrives = ""; $operator = ""; 
$origin_code = ""; $destination_code = ""; $refundable = ""; $noOfBookings = "";

// Alert Messages
$message = '';
$messageType = '';

// --- HANDLE SEARCH ---
if(isset($_POST['search'])){
    $searchID = mysqli_real_escape_string($conn, $_POST['flight_no']);
    if($searchID != ""){
        $search_query = "SELECT * FROM flights WHERE flight_no = '$searchID'";
        $search_result = mysqli_query($conn, $search_query);
        
        if(mysqli_num_rows($search_result) > 0){
            $row = mysqli_fetch_assoc($search_result);
            $flight_no        = $row['flight_no'];
            $origin           = $row['origin'];
            $destination      = $row['destination']; // Fixed typo from your original code
            $distance         = $row['distance'];
            $fare             = $row['fare'];
            $class            = $row['class'];
            $seats_available  = $row['seats_available'];
            $departs          = $row['departs'];
            $arrives          = $row['arrives'];
            $operator         = $row['operator'];
            $origin_code      = $row['origin_code'];
            $destination_code = $row['destination_code'];
            $refundable       = $row['refundable'];
            $noOfBookings     = $row['noOfBookings'];
            
            $message = "Flight found. You can now update or delete this record.";
            $messageType = "info";
        } else {
            $message = "No flight found with Number: " . htmlspecialchars($searchID);
            $messageType = "warning";
        }
    } else {
        $message = "Please enter a Flight Number to search.";
        $messageType = "danger";
    }
}

// --- HANDLE UPDATE ---
if(isset($_POST['update'])){
    $fid = mysqli_real_escape_string($conn, $_POST['flight_no']);
    if($fid != ""){
        $origin           = mysqli_real_escape_string($conn, $_POST['origin']);
        $destination      = mysqli_real_escape_string($conn, $_POST['destination']);
        $distance         = mysqli_real_escape_string($conn, $_POST['distance']);
        $fare             = mysqli_real_escape_string($conn, $_POST['fare']);
        $class            = mysqli_real_escape_string($conn, $_POST['class']);
        $seats_available  = mysqli_real_escape_string($conn, $_POST['seats_available']);
        $departs          = mysqli_real_escape_string($conn, $_POST['departs']);
        $arrives          = mysqli_real_escape_string($conn, $_POST['arrives']);
        $operator         = mysqli_real_escape_string($conn, $_POST['operator']);
        $origin_code      = mysqli_real_escape_string($conn, $_POST['origin_code']);
        $destination_code = mysqli_real_escape_string($conn, $_POST['destination_code']);
        $refundable       = mysqli_real_escape_string($conn, $_POST['refundable']);
        $noOfBookings     = mysqli_real_escape_string($conn, $_POST['noOfBookings']);

        $update_query = "UPDATE `flights` SET origin='$origin', destination='$destination', distance='$distance', fare='$fare', class='$class', seats_available='$seats_available', departs='$departs', arrives='$arrives', operator='$operator', origin_code='$origin_code', destination_code='$destination_code', refundable='$refundable', noOfBookings='$noOfBookings' WHERE flight_no = '$fid'";
        
        if(mysqli_query($conn, $update_query)){
            if(mysqli_affected_rows($conn) > 0){
                $message = "Flight data updated successfully!";
                $messageType = "success";
            } else {
                $message = "No changes were made to the flight data.";
                $messageType = "info";
            }
        } else {
            $message = "Error updating data: " . mysqli_error($conn);
            $messageType = "danger";
        }
    } else {
        $message = "Flight Number is required to update a record.";
        $messageType = "danger";
    }
}

// --- HANDLE DELETE ---
if(isset($_POST['delete'])){
    $fid = mysqli_real_escape_string($conn, $_POST['flight_no']);
    if($fid != ""){
        $delete_query = "DELETE FROM `flights` WHERE flight_no = '$fid'";
        if(mysqli_query($conn, $delete_query)){
            if(mysqli_affected_rows($conn) > 0){
                $message = "Flight deleted successfully!";
                $messageType = "success";
                // Clear form fields after delete
                $flight_no = ""; $origin = ""; $destination = ""; $distance = ""; $fare = ""; 
                $class = ""; $seats_available = ""; $departs = ""; $arrives = ""; $operator = ""; 
                $origin_code = ""; $destination_code = ""; $refundable = ""; $noOfBookings = "";
            } else {
                $message = "Flight Number not found for deletion.";
                $messageType = "warning";
            }
        } else {
            $message = "Error deleting flight: " . mysqli_error($conn);
            $messageType = "danger";
        }
    } else {
        $message = "Flight Number is required to delete a record.";
        $messageType = "danger";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Admin Panel | Update & Delete Flights</title>
    
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
            font-size: 13px;
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
                <i class="bi bi-globe-americas me-2"></i>JourneyHub Admin
            </a>
            <button class="navbar-toggler bg-light" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto align-items-center">
                    <li class="nav-item"><a class="nav-link" href="Home.php"><i class="bi bi-house-door me-1"></i> Dashboard</a></li>
                    <li class="nav-item"><a class="nav-link" href="users_add.php">Users</a></li>
                    <li class="nav-item"><a class="nav-link" href="hotels_add.php">Hotels</a></li>
                    <li class="nav-item"><a class="nav-link active fw-bold text-white" href="flights_add.php">Flights</a></li>
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
            
            <!-- LEFT COLUMN: Search & Update Form -->
            <div class="col-lg-4">
                <div class="dashboard-card h-100">
                    <div class="d-flex justify-content-between align-items-center border-bottom pb-3 mb-4">
                        <h4 class="card-header-title border-0 mb-0 pb-0">
                            <i class="bi bi-pencil-square text-warning"></i> Edit / Delete Flight
                        </h4>
                        <a href="flights_add.php" class="btn btn-outline-danger btn-sm">
                            <i class="bi bi-plus-lg"></i> Add New
                        </a>
                    </div>
                    
                    <form method="post" action="">
                        <div class="row g-2">
                            
                            <!-- Search Bar -->
                            <div class="col-12 mb-3 pb-3 border-bottom">
                                <label class="form-label text-primary fw-bold">Target Flight No. (For Search/Edit/Delete)</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-white"><i class="bi bi-search text-muted"></i></span>
                                    <input type="number" name="flight_no" class="form-control search-highlight fw-bold" placeholder="Enter Flight No" value="<?php echo htmlspecialchars($flight_no); ?>">
                                    <button type="submit" name="search" class="btn btn-secondary">Search</button>
                                </div>
                            </div>

                            <!-- Flight Fields -->
                            <div class="col-12 mt-2">
                                <label class="form-label">Operator</label>
                                <input type="text" name="operator" class="form-control" value="<?php echo htmlspecialchars($operator); ?>">
                            </div>
                            
                            <div class="col-md-6 mt-2">
                                <label class="form-label">Origin City</label>
                                <input type="text" name="origin" class="form-control" value="<?php echo htmlspecialchars($origin); ?>">
                            </div>

                            <div class="col-md-6 mt-2">
                                <label class="form-label">Origin Code</label>
                                <input type="text" name="origin_code" class="form-control" value="<?php echo htmlspecialchars($origin_code); ?>">
                            </div>

                            <div class="col-md-6 mt-2">
                                <label class="form-label">Destination City</label>
                                <input type="text" name="destination" class="form-control" value="<?php echo htmlspecialchars($destination); ?>">
                            </div>

                            <div class="col-md-6 mt-2">
                                <label class="form-label">Destination Code</label>
                                <input type="text" name="destination_code" class="form-control" value="<?php echo htmlspecialchars($destination_code); ?>">
                            </div>

                            <div class="col-md-6 mt-2">
                                <label class="form-label">Distance</label>
                                <input type="number" name="distance" class="form-control" value="<?php echo htmlspecialchars($distance); ?>">
                            </div>

                            <div class="col-md-6 mt-2">
                                <label class="form-label">Fare ($)</label>
                                <input type="number" name="fare" class="form-control" value="<?php echo htmlspecialchars($fare); ?>">
                            </div>

                            <div class="col-md-6 mt-2">
                                <label class="form-label">Class</label>
                                <select name="class" class="form-select">
                                    <option value="Economy" <?php if($class == 'Economy') echo 'selected'; ?>>Economy</option>
                                    <option value="Business" <?php if($class == 'Business') echo 'selected'; ?>>Business</option>
                                    <option value="First Class" <?php if($class == 'First Class') echo 'selected'; ?>>First Class</option>
                                </select>
                            </div>

                            <div class="col-md-6 mt-2">
                                <label class="form-label">Refundable</label>
                                <select name="refundable" class="form-select">
                                    <option value="Refundable" <?php if($refundable == 'Refundable') echo 'selected'; ?>>Refundable</option>
                                    <option value="Non-Refundable" <?php if($refundable == 'Non-Refundable') echo 'selected'; ?>>Non-Refundable</option>
                                </select>
                            </div>

                            <div class="col-md-6 mt-2">
                                <label class="form-label">Seats Available</label>
                                <input type="number" name="seats_available" class="form-control" value="<?php echo htmlspecialchars($seats_available); ?>">
                            </div>

                            <div class="col-md-6 mt-2">
                                <label class="form-label">Bookings</label>
                                <input type="number" name="noOfBookings" class="form-control" value="<?php echo htmlspecialchars($noOfBookings); ?>">
                            </div>

                            <div class="col-md-6 mt-2">
                                <label class="form-label">Departs</label>
                                <input type="time" name="departs" class="form-control" value="<?php echo htmlspecialchars($departs); ?>">
                            </div>

                            <div class="col-md-6 mt-2">
                                <label class="form-label">Arrives</label>
                                <input type="time" name="arrives" class="form-control" value="<?php echo htmlspecialchars($arrives); ?>">
                            </div>

                            <!-- Action Buttons -->
                            <div class="col-12 mt-4">
                                <div class="d-flex gap-2">
                                    <button type="submit" name="update" class="btn btn-warning flex-grow-1 fw-bold text-dark">
                                        <i class="bi bi-arrow-clockwise me-1"></i> Update
                                    </button>
                                    <button type="submit" name="delete" class="btn btn-danger flex-grow-1 fw-bold" onclick="return confirm('Are you sure you want to completely delete this Flight?');">
                                        <i class="bi bi-trash me-1"></i> Delete
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <!-- RIGHT COLUMN: Flights Data Table -->
            <div class="col-lg-8">
                <div class="dashboard-card h-100">
                    <h4 class="card-header-title">
                        <i class="bi bi-card-list text-primary"></i> Scheduled Flights Database
                    </h4>
                    
                    <div class="table-responsive">
                        <table class="table table-hover table-striped align-middle border">
                            <thead>
                                <tr>
                                    <th>Flight No.</th>
                                    <th>Operator</th>
                                    <th>Route (Codes)</th>
                                    <th>Departs</th>
                                    <th>Arrives</th>
                                    <th>Fare</th>
                                    <th>Class</th>
                                    <th>Seats</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $sql = "SELECT flight_no, operator, origin_code, destination_code, departs, arrives, fare, class, seats_available FROM flights ORDER BY flight_no DESC";
                                $result = mysqli_query($conn, $sql);

                                if (mysqli_num_rows($result) > 0) {
                                    while($row = mysqli_fetch_assoc($result)) {
                                        echo "<tr>";
                                        echo "<td><span class='badge bg-secondary'>#" . htmlspecialchars($row['flight_no']) . "</span></td>";
                                        echo "<td class='fw-bold text-danger'>" . htmlspecialchars($row['operator']) . "</td>";
                                        echo "<td>" . htmlspecialchars($row['origin_code']) . " <i class='bi bi-arrow-right mx-1'></i> " . htmlspecialchars($row['destination_code']) . "</td>";
                                        echo "<td>" . htmlspecialchars($row['departs']) . "</td>";
                                        echo "<td>" . htmlspecialchars($row['arrives']) . "</td>";
                                        echo "<td class='fw-bold'>$" . htmlspecialchars($row['fare']) . "</td>";
                                        echo "<td>" . htmlspecialchars($row['class']) . "</td>";
                                        echo "<td>" . htmlspecialchars($row['seats_available']) . "</td>";
                                        echo "</tr>";
                                    }
                                } else {
                                    echo "<tr><td colspan='8' class='text-center text-muted py-4'>No scheduled flights found in the database.</td></tr>";
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
