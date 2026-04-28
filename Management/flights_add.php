<?php
session_start();

// Redirect if not logged in
if(!isset($_SESSION['username'])){
    header('Location: adminLogin.php');
    exit();
}

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "travel";

$message = '';
$messageType = '';

// Create a single database connection for the whole page
$conn = mysqli_connect($servername, $username, $password, $dbname);
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Handle Form Submission (Insert)
if(isset($_POST['insert'])){
    // Escape inputs for security and grab them directly
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

    $insert_query = "INSERT INTO `flights` (`origin`, `destination`, `distance`, `fare`, `class`, `seats_available`, `departs`, `arrives`, `operator`, `origin_code`, `destination_code`, `refundable`, `noOfBookings`) 
                     VALUES ('$origin','$destination','$distance','$fare','$class','$seats_available','$departs','$arrives','$operator','$origin_code','$destination_code','$refundable','$noOfBookings')";
    
    if(mysqli_query($conn, $insert_query)){
        $message = "Flight added successfully!";
        $messageType = "success";
    } else {
        $message = "Error adding flight: " . mysqli_error($conn);
        $messageType = "danger";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Admin Panel | Add Flights</title>
    
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
        
        <?php if($message != ''): ?>
            <div class="alert alert-<?php echo $messageType; ?> alert-dismissible fade show shadow-sm" role="alert">
                <i class="bi bi-info-circle-fill me-2"></i> <?php echo $message; ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>

        <div class="row g-4">
            
            <!-- LEFT COLUMN: Add Flight Form -->
            <div class="col-lg-4">
                <div class="dashboard-card h-100">
                    <h4 class="card-header-title">
                        <i class="bi bi-airplane-fill text-danger"></i> Add New Flight
                    </h4>
                    
                    <form method="post" action="">
                        <div class="row g-2">
                            
                            <div class="col-12 mt-2">
                                <label class="form-label">Airline Operator</label>
                                <input type="text" name="operator" class="form-control" placeholder="e.g. Emirates, PIA" required>
                            </div>

                            <div class="col-md-6 mt-2">
                                <label class="form-label">Origin City</label>
                                <input type="text" name="origin" class="form-control" required>
                            </div>
                            
                            <div class="col-md-6 mt-2">
                                <label class="form-label">Origin Code</label>
                                <input type="text" name="origin_code" class="form-control" placeholder="e.g. LHE" required>
                            </div>

                            <div class="col-md-6 mt-2">
                                <label class="form-label">Destination City</label>
                                <input type="text" name="destination" class="form-control" required>
                            </div>

                            <div class="col-md-6 mt-2">
                                <label class="form-label">Destination Code</label>
                                <input type="text" name="destination_code" class="form-control" placeholder="e.g. DXB" required>
                            </div>

                            <div class="col-md-6 mt-2">
                                <label class="form-label">Departure Time</label>
                                <input type="time" name="departs" class="form-control" required>
                            </div>

                            <div class="col-md-6 mt-2">
                                <label class="form-label">Arrival Time</label>
                                <input type="time" name="arrives" class="form-control" required>
                            </div>

                            <div class="col-md-6 mt-2">
                                <label class="form-label">Distance (km)</label>
                                <input type="number" name="distance" class="form-control" required>
                            </div>

                            <div class="col-md-6 mt-2">
                                <label class="form-label">Fare ($)</label>
                                <input type="number" name="fare" class="form-control" required>
                            </div>

                            <div class="col-md-6 mt-2">
                                <label class="form-label">Class</label>
                                <select name="class" class="form-select" required>
                                    <option value="Economy">Economy</option>
                                    <option value="Business">Business</option>
                                    <option value="First Class">First Class</option>
                                </select>
                            </div>

                            <div class="col-md-6 mt-2">
                                <label class="form-label">Refundable Policy</label>
                                <select name="refundable" class="form-select" required>
                                    <option value="Refundable">Refundable</option>
                                    <option value="Non-Refundable">Non-Refundable</option>
                                </select>
                            </div>

                            <div class="col-md-6 mt-2">
                                <label class="form-label">Seats Available</label>
                                <input type="number" name="seats_available" class="form-control" required>
                            </div>

                            <div class="col-md-6 mt-2">
                                <label class="form-label">Initial Bookings</label>
                                <input type="number" name="noOfBookings" class="form-control" value="0" required>
                            </div>

                            <div class="col-12 mt-4">
                                <button type="submit" name="insert" class="btn btn-danger w-100 fw-bold py-2">
                                    <i class="bi bi-save me-1"></i> Add Flight
                                </button>
                                <div class="text-center mt-3">
                                    <a href="flights_update.php" class="text-decoration-none text-muted" style="font-size: 14px;">
                                        <i class="bi bi-pencil-square"></i> Go to Update/Delete/Search
                                    </a>
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
                        <i class="bi bi-card-list text-primary"></i> Scheduled Flights
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
                                $sql = "SELECT * FROM flights ORDER BY flight_no DESC";
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
                                    echo "<tr><td colspan='8' class='text-center text-muted py-4'>No scheduled flights found.</td></tr>";
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
