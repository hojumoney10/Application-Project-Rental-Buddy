<?php 
    session_start(); 
?>
<!-- 
    Title:       landlords.php
    Application: RentalBuddy
    Purpose:     Handles the CRUD functionality for landlord data
    Author:      G. Blandford, Group 5, INFO-5139-01-21W
    Date:        February 10th, 2021 (February 10th, 2021) 
-->
<!doctype html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <!-- <link rel="stylesheet" href="../node_modules/bootstrap/dist/css/bootstrap.min.css"> -->
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="css/style.css" type="text/css">

    <!-- Google Font -->
    <link href="https://fonts.googleapis.com/css2?family=Source+Sans+Pro&display=swap" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css2?family=Metrophobic&display=swap" rel="stylesheet">

    <!-- jQuery AJAX -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
    <script src="js/app.js" type="text/javascript"></script>

    <!-- Use internal styles for now -->
    <style>
        .bd-placeholder-img {
            font-size: 1.125rem;
            text-anchor: middle;
            -webkit-user-select: none;
            -moz-user-select: none;
            user-select: none;
        }

        @media (min-width: 768px) {
            .bd-placeholder-img-lg {
                font-size: 3.5rem;
            }
        }

        .backgroundColor {
            background: lightgray;
        }

        .fontColor {
            color: darkblue;
        }

        body {
            font-family: 'Metrophobic', sans-serif;
            font-size: 16px;
        }

    </style>

    <!-- Custom styles for this template -->
    <link href="../css/starter-template.css" rel="stylesheet">

    <?php
        // Load common
        define('__ROOT__', dirname(__FILE__));
        require_once(__ROOT__ . "/common.php");

        // Auto Mock Generator and symfony
        require_once '../vendor/autoload.php';
    ?>
</head>

<body p-3 mb-2 bg-dark text-center mt-5>
    <div class="container">

        <div class="row">
                <div class="col-"></div>
                <div class="col-md">

                    <?php
                        // Includes/Requires
                        require_once("./navigationMenu.php");
                        require_once("../dal/landlords_dal.php");
                    ?>

                    <div class="bg-light text-dark navbarWidth pt-4 pb-4">
                        <div id="outputLandlords">
                            <table id="table-activity" class="table table-light table-striped">
                                <thead class="thead-dark">
                                <tr>
                                   <th scope="row" colspan="6" style="text-align: left;">Landlords</th>
                                </tr>                                
                                <tr>    
                                    <th scope="col">No.</th>
                                    <th scope="col">Name</th>
                                    <th scope="col">Phone</th>
                                    <th scope="col">Email</th>
                                    <th scope="col">Status</th>
                                    <th scope="col"></th>
                                </tr>
                                </thead>
                                <tbody id="tbody-landlords">        
                                    <?php 
                                        // Get Landlords
                                        // We can add parameters to filter this if required
                                        getLandlords();                                        
                                    ?>
                                </tbody>
                            </table>                      
                        </div>
                    </div>
                </div>
                <div class="col-"></div>
            </div>

        <!-- Optional JavaScript -->
        <!-- <script src="./node_modules/bootstrap/dist/js/bootstrap.bundle.min.js"></script> -->
        <script src="https://code.jquery.com/jquery-3.4.1.slim.min.js" integrity="sha384-J6qa4849blE2+poT4WnyKhv5vZF5SrPo0iEjwBvKU7imGFAV0wwj1yYfoRSJoZ+n" crossorigin="anonymous"></script>
        <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js" integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" crossorigin="anonymous"></script>        
    </div>
</body>
</html>
