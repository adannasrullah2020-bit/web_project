<?php
session_start();        
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>Home | Tourism Management</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Oswald:200,300,400|Raleway:100,300,400,500|Roboto:100,400,500,700" rel="stylesheet">
    <link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet">
    
    <style>
        body { font-family: 'Raleway', sans-serif; overflow-x: hidden; background-color: #007bff; margin: 0; }
        a { text-decoration: none !important; transition: 0.3s; }

        /* HEADER STYLING */
        .header {
            position: fixed;
            top: 0; 
            width: 60%;
            z-index: 1050;
            padding: 25px 0;
            background: linear-gradient(to bottom, rgba(0,0,0,0.9) 0%, rgba(0,0,0,0) 100%);
            transition: background 0.4s ease;
        }

        .nav-wrapper {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .logo-box a {
            font-family: 'Oswald', sans-serif;
            color: #ffffff !important;
            font-size: 28px;
            font-weight: 700;
            letter-spacing: 2px;
            text-transform: uppercase;
            padding-lef:10px;
        }

        /* Styling for the navigation within includes */
        .header nav ul {
            display: flex;
            list-style: none;
            gap: 25px;
            margin: 0;
            padding: 0;
            font-color: #ffffff !important;
            align-items: center;
        }

        .header nav ul li a {
            color: #ffffff !important;
            font-family: 'Oswald', sans-serif;
            text-transform: uppercase;
            font-weight: 400;
            letter-spacing: 1px;
            font-size: 15px;
            padding: 5px 0;
        }

        .header nav ul li a:hover { color: #ffff !important; }

        /* CAROUSEL STYLING */
        .carousel-item img {
            width: 100%;
            height: 100vh;
            object-fit: cover;
            filter: brightness(0.8); 
        }

        .carousel-control-prev, .carousel-control-next { opacity: 0.8; width: 5%; }
        .fa-chevron-left, .fa-chevron-right { font-size: 30px; color: #fff; }

        /* BOOKING SECTION */
        .booking-section { padding: 90px 0; background: #ffffff; }
        .booking-quote { font-family: 'Oswald', sans-serif; text-align: center; margin-bottom: 50px; font-size: 30px; }
        .booking-card { display: flex; flex-direction: column; align-items: center; padding: 40px 25px; text-align: center; border-radius: 15px; border: 1px solid #f0f0f0; background: #fff; height: 100%; transition: 0.3s; }
        .booking-card:hover { transform: translateY(-10px); box-shadow: 0 15px 40px rgba(0,123,255,0.12); border-color: #007bff; }
        .icon-wrapper { width: 75px; height: 75px; background: #eef6ff; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin-bottom: 20px; }
        .icon-wrapper i { font-size: 30px; color: #007bff; }
        .btn-book { margin-top: auto; padding: 10px 25px; border: 2px solid #007bff; border-radius: 4px; font-weight: 700; color: #007bff; text-transform: uppercase; font-size: 12px; }

        .popularDestinationsContainer { padding: 80px 0; background: #fcfcfc; }
        .picDim { width: 100%; height: 260px; object-fit: cover; border-radius: 12px; }
        .heading { text-align: center; font-weight: 600; margin-top: 15px; font-size: 18px; color: #333; }

        /* FOOTER */
        .footerMod { background-color: #002d4c; color: #ffffff; padding: 70px 0 30px 0; }
        .footerHeading { font-family: 'Oswald', sans-serif; font-size: 22px; text-transform: uppercase; margin-bottom: 25px; color: #00a2ff; }
        .copyrightContainer { border-top: 1px solid rgba(255,255,255,0.1); margin-top: 50px; padding-top: 25px; text-align: center; font-size: 14px; color: #999; }

        @media (max-width: 768px) {
            .header { background: rgba(0, 45, 76, 1) !important; position: relative; width: 100%; padding: 15px 0; }
            .nav-wrapper { flex-direction: column; gap: 15px; }
            .carousel-item img { height: 60vh; }
        }
    </style>
</head>

<body>

    <div class="home">
        
        <header class="header">
            <div class="container">
                <div class="nav-wrapper">
                    <div class="logo-box">
                        <a href="index.php">TOURISM</a>
                    </div>
                    
                    <nav>
                        <?php
                        // Logic applied from the second page
                        if(!isset($_SESSION["username"])) {
                            include("common/headerTransparentLoggedOut.php");
                        } else {
                            include("common/headerTransparentLoggedIn.php");
                        }
                        ?>
                    </nav>
                </div>
            </div>
        </header>

        <div class="banner p-0">
            <div id="myCarousel" class="carousel slide carousel-fade" data-bs-ride="carousel">
                <div class="carousel-inner">
                    <div class="carousel-item active">
                        <img src="images/carousel/image1.jpg" alt="Slide 1">
                    </div>
                    <div class="carousel-item">
                        <img src="images/carousel/image2.jpg" alt="Slide 2">
                    </div>
                    <div class="carousel-item">
                        <img src="images/carousel/image3.jpg" alt="Slide 3">
                    </div>
                </div>

                <button class="carousel-control-prev border-0 bg-transparent" type="button" data-bs-target="#myCarousel" data-bs-slide="prev">
                    <i class="fa fa-chevron-left"></i>
                </button>
                <button class="carousel-control-next border-0 bg-transparent" type="button" data-bs-target="#myCarousel" data-bs-slide="next">
                    <i class="fa fa-chevron-right"></i>
                </button>
            </div>
        </div> 

        <div class="booking-section">
            <div class="container">
                <h2 class="booking-quote">Explore Your Next Adventure</h2>
                <div class="row g-4">
                    <div class="col-sm-4">
                        <a href="hotels.php" class="booking-card">
                            <div class="icon-wrapper"><i class="fa fa-building-o"></i></div>
                            <h3>Luxury Hotels</h3>
                            <p>Find the best deals on 5-star hotels and cozy stays across the country.</p>
                            <span class="btn-book">Book Now</span>
                        </a>
                    </div>
                    <div class="col-sm-4">
                        <a href="flights.php" class="booking-card">
                            <div class="icon-wrapper"><i class="fa fa-plane"></i></div>
                            <h3>Flight Tickets</h3>
                            <p>Book domestic and international flights at the most competitive prices.</p>
                            <span class="btn-book">Search Flights</span>
                        </a>
                    </div>
                    <div class="col-sm-4">
                        <a href="trains.php" class="booking-card">
                            <div class="icon-wrapper"><i class="fa fa-train"></i></div>
                            <h3>Train Routes</h3>
                            <p>Fast, reliable, and scenic. Get your train tickets for any destination.</p>
                            <span class="btn-book">Reserve Seat</span>
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <div class="popularDestinationsContainer">
            <div class="container">
                <div class="row"><div class="col-12 mb-4 text-center fs-3 fw-bold">Top Trending Destinations</div></div>
                <div class="row g-4 text-center">
                    <div class="col-sm-4">
                        <img src="images/popularDestinations/imageAndaman.jpg" alt="Srilanka" class="picDim"/>
                        <div class="heading">Srilanka</div>
                    </div>
                    <div class="col-sm-4">
                        <img src="images/popularDestinations/imageJaisalmer.jpg" alt="Thal" class="picDim"/>
                        <div class="heading">Thal</div>
                    </div>
                    <div class="col-sm-4">
                        <img src="images/popularDestinations/imageKashmir.jpg" alt="Kashmir" class="picDim"/>
                        <div class="heading">Jammu and Kashmir</div>
                    </div>
                </div>
            </div>
        </div>

        <footer class="footerMod">
            <div class="container">
                <div class="row">
                    <div class="col-sm-4">
                        <div class="footerHeading">Contact Us</div>
                        <div class="footerText"><i class="fa fa-map-marker"></i> Main Boulevard, Pakistan</div>
                        <div class="footerText"><i class="fa fa-envelope"></i> queries@tourism.com</div>
                        <div class="footerText"><i class="fa fa-phone"></i> +92 300 1234567</div>
                    </div>
                    <div class="col-sm-4 d-none d-sm-block text-center">
                         <h2 style="font-family: 'Oswald'; letter-spacing: 2px;">TOURISM</h2>
                         <p style="font-size: 12px; color: #888;">Your Journey, Our Priority.</p>
                    </div>
                    <div class="col-sm-4">
                        <div class="footerHeading">Stay Connected</div>
                        <div class="socialLinks">
                            <div style="margin-bottom:10px;"><i class="fa fa-facebook-square"></i> facebook.com/tourism</div>
                            <div style="margin-bottom:10px;"><i class="fa fa-instagram"></i> @tourism_management</div>
                            <div style="margin-bottom:10px;"><i class="fa fa-twitter"></i> twitter.com/tourism</div>
                        </div> 
                    </div>
                </div>
                <div class="row">
                    <div class="col-12">
                        <div class="copyrightContainer">
                            Copyright &copy; 2026 <strong>ADAN NASRULLAH</strong> | All Rights Reserved
                        </div>
                    </div>
                </div>
            </div>
        </footer>

    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        var myCarousel = document.querySelector('#myCarousel');
        var carousel = new bootstrap.Carousel(myCarousel, {
            interval: 4000,
            wrap: true
        });

    
        window.addEventListener('scroll', function() {
            var header = document.querySelector('.header');
            if (window.scrollY > 80) {
                header.style.background = 'rgba(0, 45, 76, 0.98)';
                header.style.padding = '10px 0';
            } else {
                header.style.background = 'linear-gradient(to bottom, rgba(0,0,0,0.9) 0%, rgba(0,0,0,0) 100%)';
                header.style.padding = '25px 0';
            }
        });
    </script>

</body>
</html>