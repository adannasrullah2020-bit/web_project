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
    // Escape all inputs for security
    $region              = mysqli_real_escape_string($conn, $_POST['region']);
    $returnTrainNo       = mysqli_real_escape_string($conn, $_POST['returnTrainNo']);
    $trainName           = mysqli_real_escape_string($conn, $_POST['trainName']);
    $origin              = mysqli_real_escape_string($conn, $_POST['origin']);
    $destination         = mysqli_real_escape_string($conn, $_POST['destination']);
    $originCode          = mysqli_real_escape_string($conn, $_POST['originCode']);
    $destinationCode     = mysqli_real_escape_string($conn, $_POST['destinationCode']);
    $originTime          = mysqli_real_escape_string($conn, $_POST['originTime']);
    $destinationTime     = mysqli_real_escape_string($conn, $_POST['destinationTime']);
    $originPlatform      = mysqli_real_escape_string($conn, $_POST['originPlatform']);
    $destinationPlatform = mysqli_real_escape_string($conn, $_POST['destinationPlatform']);
    $duration            = mysqli_real_escape_string($conn, $_POST['duration']);
    $classes             = mysqli_real_escape_string($conn, $_POST['classes']);
    $seats1AC            = mysqli_real_escape_string($conn, $_POST['seats1AC']);
    $seats2AC            = mysqli_real_escape_string($conn, $_POST['seats2AC']);
    $seats3AC            = mysqli_real_escape_string($conn, $_POST['seats3AC']);
    $seatsSL             = mysqli_real_escape_string($conn, $_POST['seatsSL']);
    $seatsChairCar       = mysqli_real_escape_string($conn, $_POST['seatsChairCar']);
    $seatsChairCarAC     = mysqli_real_escape_string($conn, $_POST['seatsChairCarAC']);
    $price1AC            = mysqli_real_escape_string($conn, $_POST['price1AC']);
    $price2AC            = mysqli_real_escape_string($conn, $_POST['price2AC']);
    $price3AC            = mysqli_real_escape_string($conn, $_POST['price3AC']);
    $priceSL             = mysqli_real_escape_string($conn, $_POST['priceSL']);
    $priceChairCar       = mysqli_real_escape_string($conn, $_POST['priceChairCar']);
    $priceChairCarAC     = mysqli_real_escape_string($conn, $_POST['priceChairCarAC']);
    $runsOn              = mysqli_real_escape_string($conn, $_POST['runsOn']);
    $noOfBookings        = mysqli_real_escape_string($conn, $_POST['noOfBookings']);

    $insert_query = "INSERT INTO `trains` 
        (`region`, `returnTrainNo`, `trainName`, `origin`, `destination`, `originCode`, `destinationCode`, `originTime`, `destinationTime`, `originPlatform`, `destinationPlatform`, `duration`, `classes`, `seats1AC`, `seats2AC`, `seats3AC`, `seatsSL`, `seatsChairCar`, `seatsChairCarAC`, `price1AC`, `price2AC`, `price3AC`, `priceSL`, `priceChairCar`, `priceChairCarAC`, `runsOn`, `noOfBookings`) 
        VALUES 
        ('$region','$returnTrainNo','$trainName','$origin','$destination','$originCode','$destinationCode','$originTime','$destinationTime','$originPlatform','$destinationPlatform','$duration','$classes','$seats1AC','$seats2AC','$seats3AC','$seatsSL','$seatsChairCar','$seatsChairCarAC','$price1AC','$price2AC','$price3AC','$priceSL','$priceChairCar','$priceChairCarAC','$runsOn','$noOfBookings')";
    
    if(mysqli_query($conn, $insert_query)){
        $message = "Train added successfully!";
        $messageType = "success";
    } else {
        $message = "Error adding train: " . mysqli_error($conn);
        $messageType = "danger";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Admin Panel | Add Trains</title>
    
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
            font-size: 12px;
            color: #475569;
            margin-bottom: 4px;
        }

        .form-control {
            border-radius: 6px;
            padding: 6px 10px;
            border: 1px solid #cbd5e1;
            font-size: 13px;
        }

        .form-control:focus {
            box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.1);
            border-color: #2563eb;
        }
        
        .section-header {
            font-weight: 700;
            font-size: 14px;
            color: #2563eb;
            text-transform: uppercase;
            letter-spacing: 0.5px;
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
                    <li class="nav-item"><a class="nav-link" href="flights_add.php">Flights</a></li>
                    <li class="nav-item"><a class="nav-link active fw-bold text-white" href="trains_add.php">Trains</a></li>
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
            
            <!-- LEFT COLUMN: Add Train Form -->
            <div class="col-lg-4">
                <div class="dashboard-card h-100">
                    <h4 class="card-header-title">
                        <i class="bi bi-train-front-fill text-warning"></i> Add New Train
                    </h4>
                    
                    <form method="post" action="">
                        <div class="row g-2">
                            
                            <!-- SECTION: Basic Details -->
                            <div class="col-12 mt-3 mb-1 border-bottom pb-1">
                                <span class="section-header">Basic Details</span>
                            </div>
                            <div class="col-12">
                                <label class="form-label">Train Name</label>
                                <input type="text" name="trainName" class="form-control" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Region</label>
                                <input type="text" name="region" class="form-control" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Return Train No.</label>
                                <input type="number" name="returnTrainNo" class="form-control" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Runs On (Days)</label>
                                <input type="text" name="runsOn" class="form-control" placeholder="e.g. Mon, Wed, Fri" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Classes</label>
                                <input type="text" name="classes" class="form-control" placeholder="e.g. 1AC, 2AC, SL" required>
                            </div>

                            <!-- SECTION: Route & Timings -->
                            <div class="col-12 mt-3 mb-1 border-bottom pb-1">
                                <span class="section-header">Route & Timings</span>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Origin City</label>
                                <input type="text" name="origin" class="form-control" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Destination City</label>
                                <input type="text" name="destination" class="form-control" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Origin Code</label>
                                <input type="text" name="originCode" class="form-control" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Destination Code</label>
                                <input type="text" name="destinationCode" class="form-control" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Origin Time</label>
                                <input type="time" name="originTime" class="form-control" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Destination Time</label>
                                <input type="time" name="destinationTime" class="form-control" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Origin Platform</label>
                                <input type="text" name="originPlatform" class="form-control" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Dest. Platform</label>
                                <input type="text" name="destinationPlatform" class="form-control" required>
                            </div>
                            <div class="col-12">
                                <label class="form-label">Duration</label>
                                <input type="text" name="duration" class="form-control" placeholder="e.g. 12h 30m" required>
                            </div>

                            <!-- SECTION: Seats Allocation -->
                            <div class="col-12 mt-3 mb-1 border-bottom pb-1">
                                <span class="section-header">Seats Available</span>
                            </div>
                            <div class="col-md-4"><label class="form-label">1AC</label><input type="number" name="seats1AC" class="form-control" value="0" required></div>
                            <div class="col-md-4"><label class="form-label">2AC</label><input type="number" name="seats2AC" class="form-control" value="0" required></div>
                            <div class="col-md-4"><label class="form-label">3AC</label><input type="number" name="seats3AC" class="form-control" value="0" required></div>
                            <div class="col-md-4"><label class="form-label">Sleeper (SL)</label><input type="number" name="seatsSL" class="form-control" value="0" required></div>
                            <div class="col-md-4"><label class="form-label">Chair Car</label><input type="number" name="seatsChairCar" class="form-control" value="0" required></div>
                            <div class="col-md-4"><label class="form-label">CC AC</label><input type="number" name="seatsChairCarAC" class="form-control" value="0" required></div>

                            <!-- SECTION: Pricing -->
                            <div class="col-12 mt-3 mb-1 border-bottom pb-1">
                                <span class="section-header">Pricing Details ($)</span>
                            </div>
                            <div class="col-md-4"><label class="form-label">Price 1AC</label><input type="number" name="price1AC" class="form-control" value="0" required></div>
                            <div class="col-md-4"><label class="form-label">Price 2AC</label><input type="number" name="price2AC" class="form-control" value="0" required></div>
                            <div class="col-md-4"><label class="form-label">Price 3AC</label><input type="number" name="price3AC" class="form-control" value="0" required></div>
                            <div class="col-md-4"><label class="form-label">Price SL</label><input type="number" name="priceSL" class="form-control" value="0" required></div>
                            <div class="col-md-4"><label class="form-label">Price CC</label><input type="number" name="priceChairCar" class="form-control" value="0" required></div>
                            <div class="col-md-4"><label class="form-label">Price CC AC</label><input type="number" name="priceChairCarAC" class="form-control" value="0" required></div>

                            <div class="col-12 mt-3">
                                <label class="form-label">Initial Bookings</label>
                                <input type="number" name="noOfBookings" class="form-control" value="0" required>
                            </div>

                            <!-- Action Buttons -->
                            <div class="col-12 mt-4">
                                <button type="submit" name="insert" class="btn btn-warning text-dark w-100 fw-bold py-2">
                                    <i class="bi bi-save me-1"></i> Add Train
                                </button>
                                <div class="text-center mt-3">
                                    <a href="trains_update.php" class="text-decoration-none text-muted" style="font-size: 14px;">
                                        <i class="bi bi-pencil-square"></i> Go to Update/Delete/Search
                                    </a>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <!-- RIGHT COLUMN: Trains Data Table -->
            <div class="col-lg-8">
                <div class="dashboard-card h-100">
                    <h4 class="card-header-title">
                        <i class="bi bi-card-list text-primary"></i> Registered Trains Network
                    </h4>
                    
                    <div class="table-responsive">
                        <table class="table table-hover table-striped align-middle border">
                            <thead>
                                <tr>
                                    <th>Train No.</th>
                                    <th>Name & Region</th>
                                    <th>Route (Codes)</th>
                                    <th>Timing (Orig-Dest)</th>
                                    <th>Runs On</th>
                                    <th>Total Bookings</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $sql = "SELECT trainNo, trainName, region, originCode, destinationCode, originTime, destinationTime, runsOn, noOfBookings FROM trains ORDER BY trainNo DESC";
                                $result = mysqli_query($conn, $sql);

                                if (mysqli_num_rows($result) > 0) {
                                    while($row = mysqli_fetch_assoc($result)) {
                                        echo "<tr>";
                                        echo "<td><span class='badge bg-secondary'>#" . htmlspecialchars($row['trainNo']) . "</span></td>";
                                        echo "<td><span class='fw-bold text-dark'>" . htmlspecialchars($row['trainName']) . "</span><br><small class='text-muted'>" . htmlspecialchars($row['region']) . "</small></td>";
                                        echo "<td>" . htmlspecialchars($row['originCode']) . " <i class='bi bi-arrow-right mx-1'></i> " . htmlspecialchars($row['destinationCode']) . "</td>";
                                        echo "<td>" . htmlspecialchars($row['originTime']) . " - " . htmlspecialchars($row['destinationTime']) . "</td>";
                                        echo "<td>" . htmlspecialchars($row['runsOn']) . "</td>";
                                        echo "<td>" . htmlspecialchars($row['noOfBookings']) . "</td>";
                                        echo "</tr>";
                                    }
                                } else {
                                    echo "<tr><td colspan='6' class='text-center text-muted py-4'>No trains found in the database.</td></tr>";
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
