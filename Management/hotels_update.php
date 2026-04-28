<?php
session_start();

// Redirect if not logged in
if(!isset($_SESSION['username'])){
    header('Location: adminLogin.php');
    exit();
}

// Database Connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "travel";

$conn = mysqli_connect($servername, $username, $password, $dbname);
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Initialize Variables
$hotelID = ""; $hotelName = ""; $city = ""; $locality = ""; $stars = ""; $rating = "";
$hotelDesc = ""; $checkIn = ""; $checkOut = ""; $price = ""; $roomsAvailable = "";
$wifi = ""; $swimmingPool = ""; $parking = ""; $restaurant = ""; $laundry = ""; $cafe = ""; $mainImage = "";

// Alert Messages
$message = '';
$messageType = '';

// --- HANDLE SEARCH ---
if(isset($_POST['search'])){
    $searchID = mysqli_real_escape_string($conn, $_POST['hotelID']);
    if($searchID != ""){
        $search_query = "SELECT * FROM hotels WHERE hotelID = '$searchID'";
        $search_result = mysqli_query($conn, $search_query);
        
        if(mysqli_num_rows($search_result) > 0){
            $row = mysqli_fetch_assoc($search_result);
            $hotelID        = $row['hotelID'];
            $hotelName      = $row['hotelName'];
            $city           = $row['city'];
            $locality       = $row['locality'];
            $stars          = $row['stars'];
            $rating         = $row['rating'];
            $hotelDesc      = $row['hotelDesc'];
            $checkIn        = $row['checkIn'];
            $checkOut       = $row['checkOut'];
            $price          = $row['price'];
            $roomsAvailable = $row['roomsAvailable'];
            $wifi           = $row['wifi'];
            $swimmingPool   = $row['swimmingPool'];
            $parking        = $row['parking'];
            $restaurant     = $row['restaurant'];
            $laundry        = $row['laundry'];
            $cafe           = $row['cafe'];
            $mainImage      = $row['mainImage'];
            
            $message = "Hotel found. You can now update or delete this record.";
            $messageType = "info";
        } else {
            $message = "No hotel found with ID: " . htmlspecialchars($searchID);
            $messageType = "warning";
        }
    } else {
        $message = "Please enter a Hotel ID to search.";
        $messageType = "danger";
    }
}

// --- HANDLE UPDATE ---
if(isset($_POST['update'])){
    $hid = mysqli_real_escape_string($conn, $_POST['hotelID']);
    if($hid != ""){
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

        $update_query = "UPDATE `hotels` SET hotelName='$hotelName', city='$city', locality='$locality', stars='$stars', rating='$rating', hotelDesc='$hotelDesc', checkIn='$checkIn', checkOut='$checkOut', price='$price', roomsAvailable='$roomsAvailable', wifi='$wifi', swimmingPool='$swimmingPool', parking='$parking', restaurant='$restaurant', laundry='$laundry', cafe='$cafe', mainImage='$mainImage' WHERE hotelID = '$hid'";
        
        if(mysqli_query($conn, $update_query)){
            if(mysqli_affected_rows($conn) > 0){
                $message = "Hotel data updated successfully!";
                $messageType = "success";
            } else {
                $message = "No changes were made to the hotel data.";
                $messageType = "info";
            }
        } else {
            $message = "Error updating data: " . mysqli_error($conn);
            $messageType = "danger";
        }
    } else {
        $message = "Hotel ID is required to update a record.";
        $messageType = "danger";
    }
}

// --- HANDLE DELETE ---
if(isset($_POST['delete'])){
    $hid = mysqli_real_escape_string($conn, $_POST['hotelID']);
    if($hid != ""){
        $delete_query = "DELETE FROM `hotels` WHERE hotelID = '$hid'";
        if(mysqli_query($conn, $delete_query)){
            if(mysqli_affected_rows($conn) > 0){
                $message = "Hotel deleted successfully!";
                $messageType = "success";
                // Clear form fields
                $hotelID = ""; $hotelName = ""; $city = ""; $locality = ""; $stars = ""; $rating = "";
                $hotelDesc = ""; $checkIn = ""; $checkOut = ""; $price = ""; $roomsAvailable = "";
                $wifi = ""; $swimmingPool = ""; $parking = ""; $restaurant = ""; $laundry = ""; $cafe = ""; $mainImage = "";
            } else {
                $message = "Hotel ID not found for deletion.";
                $messageType = "warning";
            }
        } else {
            $message = "Error deleting hotel: " . mysqli_error($conn);
            $messageType = "danger";
        }
    } else {
        $message = "Hotel ID is required to delete a record.";
        $messageType = "danger";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Admin Panel | Update & Delete Hotels</title>
    
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
                            <i class="bi bi-pencil-square text-warning"></i> Edit / Delete Hotel
                        </h4>
                        <a href="hotels_add.php" class="btn btn-outline-success btn-sm">
                            <i class="bi bi-plus-lg"></i> Add New
                        </a>
                    </div>
                    
                    <form method="post" action="">
                        <div class="row g-2">
                            
                            <!-- Search Bar -->
                            <div class="col-12 mb-3 pb-3 border-bottom">
                                <label class="form-label text-primary fw-bold">Target Hotel ID (For Search/Edit/Delete)</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-white"><i class="bi bi-search text-muted"></i></span>
                                    <input type="number" name="hotelID" class="form-control search-highlight fw-bold" placeholder="Enter Hotel ID" value="<?php echo htmlspecialchars($hotelID); ?>">
                                    <button type="submit" name="search" class="btn btn-secondary">Search</button>
                                </div>
                            </div>

                            <!-- Hotel Fields -->
                            <div class="col-12 mt-2">
                                <label class="form-label">Hotel Name</label>
                                <input type="text" name="hotelName" class="form-control" value="<?php echo htmlspecialchars($hotelName); ?>">
                            </div>
                            
                            <div class="col-md-6 mt-2">
                                <label class="form-label">City</label>
                                <input type="text" name="city" class="form-control" value="<?php echo htmlspecialchars($city); ?>">
                            </div>
                            
                            <div class="col-md-6 mt-2">
                                <label class="form-label">Locality</label>
                                <input type="text" name="locality" class="form-control" value="<?php echo htmlspecialchars($locality); ?>">
                            </div>

                            <div class="col-md-6 mt-2">
                                <label class="form-label">Stars</label>
                                <input type="number" min="1" max="5" name="stars" class="form-control" value="<?php echo htmlspecialchars($stars); ?>">
                            </div>

                            <div class="col-md-6 mt-2">
                                <label class="form-label">Rating</label>
                                <input type="text" name="rating" class="form-control" value="<?php echo htmlspecialchars($rating); ?>">
                            </div>

                            <div class="col-12 mt-2">
                                <label class="form-label">Description</label>
                                <input type="text" name="hotelDesc" class="form-control" value="<?php echo htmlspecialchars($hotelDesc); ?>">
                            </div>

                            <div class="col-md-6 mt-2">
                                <label class="form-label">Check In</label>
                                <input type="time" name="checkIn" class="form-control" value="<?php echo htmlspecialchars($checkIn); ?>">
                            </div>

                            <div class="col-md-6 mt-2">
                                <label class="form-label">Check Out</label>
                                <input type="time" name="checkOut" class="form-control" value="<?php echo htmlspecialchars($checkOut); ?>">
                            </div>

                            <div class="col-md-6 mt-2">
                                <label class="form-label">Price</label>
                                <input type="number" name="price" class="form-control" value="<?php echo htmlspecialchars($price); ?>">
                            </div>

                            <div class="col-md-6 mt-2">
                                <label class="form-label">Rooms Available</label>
                                <input type="number" name="roomsAvailable" class="form-control" value="<?php echo htmlspecialchars($roomsAvailable); ?>">
                            </div>

                            <div class="col-12 mt-3 mb-1 border-bottom pb-2">
                                <span class="fw-bold text-primary" style="font-size: 14px;">Amenities</span>
                            </div>

                            <div class="col-md-4 mt-2">
                                <label class="form-label">Wifi</label>
                                <select name="wifi" class="form-select">
                                    <option value="Yes" <?php if($wifi == 'Yes') echo 'selected'; ?>>Yes</option>
                                    <option value="No" <?php if($wifi == 'No') echo 'selected'; ?>>No</option>
                                </select>
                            </div>
                            <div class="col-md-4 mt-2">
                                <label class="form-label">Pool</label>
                                <select name="swimmingPool" class="form-select">
                                    <option value="Yes" <?php if($swimmingPool == 'Yes') echo 'selected'; ?>>Yes</option>
                                    <option value="No" <?php if($swimmingPool == 'No') echo 'selected'; ?>>No</option>
                                </select>
                            </div>
                            <div class="col-md-4 mt-2">
                                <label class="form-label">Parking</label>
                                <select name="parking" class="form-select">
                                    <option value="Yes" <?php if($parking == 'Yes') echo 'selected'; ?>>Yes</option>
                                    <option value="No" <?php if($parking == 'No') echo 'selected'; ?>>No</option>
                                </select>
                            </div>
                            <div class="col-md-4 mt-2">
                                <label class="form-label">Restaurant</label>
                                <select name="restaurant" class="form-select">
                                    <option value="Yes" <?php if($restaurant == 'Yes') echo 'selected'; ?>>Yes</option>
                                    <option value="No" <?php if($restaurant == 'No') echo 'selected'; ?>>No</option>
                                </select>
                            </div>
                            <div class="col-md-4 mt-2">
                                <label class="form-label">Laundry</label>
                                <select name="laundry" class="form-select">
                                    <option value="Yes" <?php if($laundry == 'Yes') echo 'selected'; ?>>Yes</option>
                                    <option value="No" <?php if($laundry == 'No') echo 'selected'; ?>>No</option>
                                </select>
                            </div>
                            <div class="col-md-4 mt-2">
                                <label class="form-label">Cafe</label>
                                <select name="cafe" class="form-select">
                                    <option value="Yes" <?php if($cafe == 'Yes') echo 'selected'; ?>>Yes</option>
                                    <option value="No" <?php if($cafe == 'No') echo 'selected'; ?>>No</option>
                                </select>
                            </div>

                            <div class="col-12 mt-3">
                                <label class="form-label">Image URL</label>
                                <input type="text" name="mainImage" class="form-control" value="<?php echo htmlspecialchars($mainImage); ?>">
                            </div>

                            <!-- Action Buttons -->
                            <div class="col-12 mt-4">
                                <div class="d-flex gap-2">
                                    <button type="submit" name="update" class="btn btn-warning flex-grow-1 fw-bold text-dark">
                                        <i class="bi bi-arrow-clockwise me-1"></i> Update
                                    </button>
                                    <button type="submit" name="delete" class="btn btn-danger flex-grow-1 fw-bold" onclick="return confirm('Are you sure you want to completely delete this Hotel?');">
                                        <i class="bi bi-trash me-1"></i> Delete
                                    </button>
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
                        <i class="bi bi-building text-primary"></i> Existing Hotels Database
                    </h4>
                    
                    <div class="table-responsive">
                        <table class="table table-hover table-striped align-middle border">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Hotel Name</th>
                                    <th>City</th>
                                    <th>Locality</th>
                                    <th>Stars</th>
                                    <th>Price</th>
                                    <th>Rooms</th>
                                    <th>Wifi</th>
                                    <th>Pool</th>
                                    <th>Parking</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $sql = "SELECT hotelID, hotelName, city, locality, stars, price, roomsAvailable, wifi, swimmingPool, parking FROM hotels ORDER BY hotelID DESC";
                                $result = mysqli_query($conn, $sql);

                                if (mysqli_num_rows($result) > 0) {
                                    while($row = mysqli_fetch_assoc($result)) {
                                        echo "<tr>";
                                        echo "<td><span class='badge bg-secondary'>#" . htmlspecialchars($row['hotelID']) . "</span></td>";
                                        echo "<td class='fw-bold'>" . htmlspecialchars($row['hotelName']) . "</td>";
                                        echo "<td>" . htmlspecialchars($row['city']) . "</td>";
                                        echo "<td>" . htmlspecialchars($row['locality']) . "</td>";
                                        echo "<td>" . htmlspecialchars($row['stars']) . "</td>";
                                        echo "<td>$" . htmlspecialchars($row['price']) . "</td>";
                                        echo "<td>" . htmlspecialchars($row['roomsAvailable']) . "</td>";
                                        echo "<td>" . htmlspecialchars($row['wifi']) . "</td>";
                                        echo "<td>" . htmlspecialchars($row['swimmingPool']) . "</td>";
                                        echo "<td>" . htmlspecialchars($row['parking']) . "</td>";
                                        echo "</tr>";
                                    }
                                } else {
                                    echo "<tr><td colspan='10' class='text-center text-muted py-4'>No hotels found in the database.</td></tr>";
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
