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
    // Escape inputs for security and grab them directly (Much easier than the old getData array!)
    $hotelName      = mysqli_real_escape_string($conn, $_POST['hotelName']);
    $city           = mysqli_real_escape_string($conn, $_POST['city']);
    $locality       = mysqli_real_escape_string($conn, $_POST['locality']);
    $stars          = mysqli_real_escape_string($conn, $_POST['stars']);
    $rating         = mysqli_real_escape_string($conn, $_POST['rating']);
    $hotelDesc      = mysqli_real_escape_string($conn, $_POST['hotelDesc']);
    $checkIn        = mysqli_real_escape_string($conn, $_POST['checkIn']);
    $checkOut       = mysqli_real_escape_string($conn, $_POST['checkOut']);
    $price          = mysqli_real_escape_string($conn, $_POST['price']);
    $roomsAvailable = mysqli_real_escape_string($conn, $_POST['roomsAvailable']);
    $wifi           = mysqli_real_escape_string($conn, $_POST['wifi']);
    $swimmingPool   = mysqli_real_escape_string($conn, $_POST['swimmingPool']);
    $parking        = mysqli_real_escape_string($conn, $_POST['parking']);
    $restaurant     = mysqli_real_escape_string($conn, $_POST['restaurant']);
    $laundry        = mysqli_real_escape_string($conn, $_POST['laundry']);
    $cafe           = mysqli_real_escape_string($conn, $_POST['cafe']);
    $mainImage      = mysqli_real_escape_string($conn, $_POST['mainImage']);

    $insert_query = "INSERT INTO `hotels` (`hotelName`, `city`, `locality`, `stars`, `rating`, `hotelDesc`, `checkIn`, `checkOut`, `price`, `roomsAvailable`, `wifi`, `swimmingPool`, `parking`, `restaurant`, `laundry`, `cafe`, `mainImage`) 
                     VALUES ('$hotelName','$city','$locality','$stars','$rating','$hotelDesc','$checkIn','$checkOut','$price','$roomsAvailable','$wifi','$swimmingPool','$parking','$restaurant','$laundry','$cafe','$mainImage')";
    
    if(mysqli_query($conn, $insert_query)){
        $message = "Hotel added successfully!";
        $messageType = "success";
    } else {
        $message = "Error adding hotel: " . mysqli_error($conn);
        $messageType = "danger";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Admin Panel | Add Hotels</title>
    
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
                    <li class="nav-item"><a class="nav-link active fw-bold text-white" href="hotels_add.php">Hotels</a></li>
                    <li class="nav-item"><a class="nav-link" href="flights_add.php">Flights</a></li>
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
            
            <!-- LEFT COLUMN: Add Hotel Form -->
            <div class="col-lg-4">
                <div class="dashboard-card h-100">
                    <h4 class="card-header-title">
                        <i class="bi bi-building-add text-success"></i> Add New Hotel
                    </h4>
                    
                    <form method="post" action="">
                        <div class="row g-2">
                            
                            <div class="col-12 mt-2">
                                <label class="form-label">Hotel Name</label>
                                <input type="text" name="hotelName" class="form-control" required>
                            </div>
                            
                            <div class="col-md-6 mt-2">
                                <label class="form-label">City</label>
                                <input type="text" name="city" class="form-control" required>
                            </div>
                            
                            <div class="col-md-6 mt-2">
                                <label class="form-label">Locality</label>
                                <input type="text" name="locality" class="form-control" required>
                            </div>

                            <div class="col-md-6 mt-2">
                                <label class="form-label">Stars (1-5)</label>
                                <input type="number" min="1" max="5" name="stars" class="form-control" required>
                            </div>

                            <div class="col-md-6 mt-2">
                                <label class="form-label">Rating</label>
                                <input type="text" name="rating" class="form-control" required>
                            </div>

                            <div class="col-12 mt-2">
                                <label class="form-label">Description</label>
                                <input type="text" name="hotelDesc" class="form-control" required>
                            </div>

                            <div class="col-md-6 mt-2">
                                <label class="form-label">Check In Time</label>
                                <input type="time" name="checkIn" class="form-control" required>
                            </div>

                            <div class="col-md-6 mt-2">
                                <label class="form-label">Check Out Time</label>
                                <input type="time" name="checkOut" class="form-control" required>
                            </div>

                            <div class="col-md-6 mt-2">
                                <label class="form-label">Price (per night)</label>
                                <input type="number" name="price" class="form-control" required>
                            </div>

                            <div class="col-md-6 mt-2">
                                <label class="form-label">Rooms Available</label>
                                <input type="number" name="roomsAvailable" class="form-control" required>
                            </div>

                            <div class="col-12 mt-3 mb-1 border-bottom pb-2">
                                <span class="fw-bold text-primary" style="font-size: 14px;">Amenities</span>
                            </div>

                            <div class="col-md-4 mt-2">
                                <label class="form-label">Wifi</label>
                                <select name="wifi" class="form-select"><option value="Yes">Yes</option><option value="No">No</option></select>
                            </div>
                            <div class="col-md-4 mt-2">
                                <label class="form-label">Pool</label>
                                <select name="swimmingPool" class="form-select"><option value="Yes">Yes</option><option value="No">No</option></select>
                            </div>
                            <div class="col-md-4 mt-2">
                                <label class="form-label">Parking</label>
                                <select name="parking" class="form-select"><option value="Yes">Yes</option><option value="No">No</option></select>
                            </div>
                            <div class="col-md-4 mt-2">
                                <label class="form-label">Restaurant</label>
                                <select name="restaurant" class="form-select"><option value="Yes">Yes</option><option value="No">No</option></select>
                            </div>
                            <div class="col-md-4 mt-2">
                                <label class="form-label">Laundry</label>
                                <select name="laundry" class="form-select"><option value="Yes">Yes</option><option value="No">No</option></select>
                            </div>
                            <div class="col-md-4 mt-2">
                                <label class="form-label">Cafe</label>
                                <select name="cafe" class="form-select"><option value="Yes">Yes</option><option value="No">No</option></select>
                            </div>

                            <div class="col-12 mt-3">
                                <label class="form-label">Main Image URL</label>
                                <input type="text" name="mainImage" class="form-control" placeholder="http://..." required>
                            </div>

                            <div class="col-12 mt-4">
                                <button type="submit" name="insert" class="btn btn-success w-100 fw-bold py-2">
                                    <i class="bi bi-save me-1"></i> Add Hotel
                                </button>
                                <div class="text-center mt-3">
                                    <a href="hotels_update.php" class="text-decoration-none text-muted" style="font-size: 14px;">
                                        <i class="bi bi-pencil-square"></i> Go to Update/Delete/Search
                                    </a>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <!-- RIGHT COLUMN: Hotels Data Table -->
            <div class="col-lg-8">
                <div class="dashboard-card h-100">
                    <h4 class="card-header-title">
                        <i class="bi bi-building text-primary"></i> Registered Hotels
                    </h4>
                    
                    <div class="table-responsive">
                        <table class="table table-hover table-striped align-middle border">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Hotel Name</th>
                                    <th>City/Locality</th>
                                    <th>Stars</th>
                                    <th>Price</th>
                                    <th>Rooms</th>
                                    <th>Amenities (W/P/P/R)</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $sql = "SELECT * FROM hotels ORDER BY hotelID DESC";
                                $result = mysqli_query($conn, $sql);

                                if (mysqli_num_rows($result) > 0) {
                                    while($row = mysqli_fetch_assoc($result)) {
                                        echo "<tr>";
                                        echo "<td><span class='badge bg-secondary'>#" . htmlspecialchars($row['hotelID']) . "</span></td>";
                                        echo "<td class='fw-bold text-primary'>" . htmlspecialchars($row['hotelName']) . "</td>";
                                        echo "<td>" . htmlspecialchars($row['city']) . " <br><small class='text-muted'>" . htmlspecialchars($row['locality']) . "</small></td>";
                                        echo "<td>" . htmlspecialchars($row['stars']) . " ⭐</td>";
                                        echo "<td class='fw-bold'>$" . htmlspecialchars($row['price']) . "</td>";
                                        echo "<td>" . htmlspecialchars($row['roomsAvailable']) . "</td>";
                                        // A short string to show amenities quickly
                                        echo "<td>" . 
                                             ($row['wifi'] == 'Yes' ? '📶 ' : '') . 
                                             ($row['swimmingPool'] == 'Yes' ? '🏊 ' : '') .
                                             ($row['parking'] == 'Yes' ? '🚗 ' : '') .
                                             ($row['restaurant'] == 'Yes' ? '🍽️' : '') .
                                             "</td>";
                                        echo "</tr>";
                                    }
                                } else {
                                    echo "<tr><td colspan='7' class='text-center text-muted py-4'>No hotels found in the database.</td></tr>";
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
