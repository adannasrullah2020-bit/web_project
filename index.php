<?php
session_start();        
?>

<!DOCTYPE html>
<html>
    <head>
    
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    
    <title>Home | tourism_management</title>
    
    <link href="css/bootstrap.min.css" rel="stylesheet"/>
    <link href="css/hover-min.css" rel="stylesheet"/>
    <link href="css/main.css" rel="stylesheet"/>
    <link href="https://fonts.googleapis.com/css?family=Oswald:200,300,400|Raleway:100,300,400,500|Roboto:100,400,500,700" rel="stylesheet">
    <link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet">
    
    <script src="js/jquery-3.2.1.min.js" type="text/javascript"></script>
    <script src="js/bootstrap.min.js" type="text/javascript"></script>
    <script src="js/main.js" type="text/javascript"></script>

    <style>
        /* Accurate Styling for Booking Section per Screenshot */
        .booking-section {
            background-color: #ffffff;
            padding: 100px 0 60px 0;
            font-family: 'Raleway', sans-serif;
        }

        .booking-quote {
            font-family: 'Oswald', sans-serif;
            color: #333;
            text-align: center;
            margin-bottom: 60px;
            text-transform: uppercase;
            font-weight: 400;
            letter-spacing: 1.5px;
            font-size: 26px;
        }

        .booking-card {
            display: block;
            background: #ffffff;
            padding:50px 25px;
            text-align: center;
            border-radius: 15px;
            border: 1px solid #e0e0e0;
            transition: all 0.3s ease-in-out;
            text-decoration: none !important;
            color: #333 !important;
            height: 100%;
            margin-bottom: 30px;
        }

    
        .booking-card:hover {
            border-color: #007bff;
            box-shadow: 0 10px 30px rgba(0, 123, 255, 0.1);
            transform: translateY(-5px);
        }

        .icon-wrapper {
            width: 70px;
            height: 80px;
            background: #f0f7ff;
            border-radius: 50%;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 25px;
            transition: 0.3s;
        }

        .icon-wrapper i {
            font-size: 32px;
            color: #007bff;
        }

        /* Hover interactions */
        .booking-card:hover .icon-wrapper {
            background: #007bff;
        }

        .booking-card:hover .icon-wrapper i {
            color: #ffffff;
        }

        .booking-card h3 {
            font-family: 'Roboto', sans-serif;
            font-size: 1.5rem;
            margin-bottom: 15px;
            font-weight: 500;
        }

        .booking-card p {
            font-size: 0.95rem;
            color: #666;
            margin-bottom: 30px;
            line-height: 1.6;
            padding: 0 10px;
        }

       
        .btn-book {
            display: inline-block;
            padding: 10px 0;
            border: 1.5px solid #007bff;
            border-radius: 5px;
            color: #007bff;
            font-weight: 700;
            font-size: 0.85rem;
            text-transform: uppercase;
            width: 80%;
            transition: 0.3s;
        }

        .booking-card:hover .btn-book {
            background: #007bff;
            color: #ffffff;
        }
    </style>
    
    </head>
    
    <body>
    
        <div class="col-xs-12 home">
        
            <div class="col-sm-12">
                <div class="header">
                    <?php
                    if(!isset($_SESSION["username"])) {
                        include("common/headerTransparentLoggedOut.php");
                    }
                    else {
                        include("common/headerTransparentLoggedIn.php");
                    }
                    ?>
                </div> 
            </div> 
            <div class="col-xs-12 banner">
                <div id="myCarousel" class="carousel slide" data-ride="carousel" data-interval="3000">
                    <ol class="carousel-indicators">
                        <li data-target="#myCarousel" data-slide-to="0" class="active"></li>
                        <li data-target="#myCarousel" data-slide-to="1"></li>
                        <li data-target="#myCarousel" data-slide-to="2"></li>
                    </ol>
                
                    <div class="carousel-inner">
                        <div class="item active">
                          <img src="images/carousel/image1.jpg" alt="Image1">
                        </div>
                        <div class="item">
                          <img src="images/carousel/image2.jpg" alt="Image2">
                        </div>
                        <div class="item">
                          <img src="images/carousel/image3.jpg" alt="Image3">
                        </div>
                    </div>
                    
                    <a href="#myCarousel" class="left carousel-control" data-slide="prev">
                        <span class="glyphicon glyphicon-chevron-left"></span>
                    </a>
                    <a href="#myCarousel" class="right carousel-control" data-slide="next">
                        <span class="glyphicon glyphicon-chevron-right"></span>
                    </a>
                </div>
            </div> <div class="col-xs-12 booking-section">
                <div class="container">
                    <div class="row">
                        <div class="col-xs-12">
                            <h2 class="booking-quote">What would you like to book today?</h2>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-sm-4">
                            <a href="hotels.php" class="booking-card">
                                <div class="icon-wrapper">
                                    <i class="fa fa-building-o"></i>
                                </div>
                                <h3>Luxury Hotels</h3>
                                <p>Find the best deals on 5-star hotels and cozy stays across the country.</p>
                                <span class="btn-book">Book Now</span>
                            </a>
                        </div>

                        <div class="col-sm-4">
                            <a href="flights.php" class="booking-card">
                                <div class="icon-wrapper">
                                    <i class="fa fa-plane"></i>
                                </div>
                                <h3>Flight Tickets</h3>
                                <p>Book domestic and international flights at the most competitive prices.</p>
                                <span class="btn-book">Search Flights</span>
                            </a>
                        </div>

                        <div class="col-sm-4">
                            <a href="trains.php" class="booking-card">
                                <div class="icon-wrapper">
                                    <i class="fa fa-train"></i>
                                </div>
                                <h3>Train Routes</h3>
                                <p>Fast, reliable, and scenic. Get your train tickets for any destination.</p>
                                <span class="btn-book">Reserve Seat</span>
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xs-12 popularDestinationsContainer">
                <div class="col-xs-12 destinationHolder">
                    <div class="col-xs-12 destinationQuote">
                        Popular Destinations
                    </div>
                    
                    <div class="col-xs-12 containerGrids hvr-buzz-out">
                        <div class="col-xs-12 pics text-center">
                            <img src="images/popularDestinations/imageAndaman.jpg" alt="popularDestination1" class="picDim text-center"/>
                        </div>
                        <div class="col-xs-12 heading">Srilanka</div>
                    </div>
                    
                    <div class="col-xs-12 containerGrids hvr-buzz-out">
                        <div class="col-xs-12 pics text-center">
                            <img src="images/popularDestinations/imageJaisalmer.jpg" alt="popularDestination1" class="picDim text-center"/>
                        </div>
                        <div class="col-xs-12 heading">Thal</div>
                    </div>
                    
                    <div class="col-xs-12 containerGrids hvr-buzz-out">
                        <div class="col-xs-12 pics text-center">
                            <img src="images/popularDestinations/imageKashmir.jpg" alt="popularDestination1" class="picDim text-center"/>
                        </div>
                        <div class="col-xs-12 heading">Jammu and Kashmir</div>
                    </div>
                </div>
            </div>
        <!-- </div> <div class="footerMod col-sm-12">
            <div class="col-sm-4">
                <div class="footerHeading">Contact Us</div>
                <div class="footerText">Pakistan</div>
                <div class="footerText">E-mail: queries@tourism_management.com</div>
            </div>
            
            <div class="col-sm-4"></div>
            
            <div class="col-sm-4">
                <div class="footerHeading">Social Links</div>
                <div class="socialLinks">
                    <div class="fb">facebook.com/tourism_management</div>
                    <div class="gp">plus.google.com/tourism_management</div>
                    <div class="tw">twitter.com/tourism_management</div>
                    <div class="in">linkedin.com/tourism_management</div>
                </div> 
            </div>
                
            <div class="col-sm-12">
                <div class="copyrightContainer">
                    <div class="copyright">
                        Copyright &copy; 2026 ADAN NASRULLAH
                    </div>
                </div>
            </div>
        </div>  -->
        <?php include("common/footer.php"); ?>
        </body>
</html>