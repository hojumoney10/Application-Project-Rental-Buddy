<?php
session_start();
// include_once("./check_session.php");


?>
<!DOCTYPE html>
<html>

<head>
    <!-- 
        Title:       contact.php
        Application: RentalBuddy
        Purpose:     contact page
        Author:      S. Jeong,  Group 5, INFO-5139-01-21W
        Date:        April 7th, 2021   

        20210417    GPB Added Clickable links for Phone/Email and styling 
    -->

    <title>RentalBuddy - Contact</title>

    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="RentalBuddy Landlords">
    <meta name="author" content="Graham Blandford">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="../vendor/twbs/bootstrap/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

    <!-- Google Font -->
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css2?family=Source+Sans+Pro&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Metrophobic&display=swap" rel="stylesheet">

    <!-- jQuery AJAX -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>

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

        /* nav {
            margin-top: 20px;
        } */

        .container-crud {
            margin-left: 10px;
        }

        .btn-crud {
            min-width: 100px;
        }

        .btn-crud img {
            color: white;
        }

        .form-inline {
            display: inline-block;
        }

        .input-group {
            margin-bottom: 10px !important;
        }

        .form-group {
            margin-bottom: 10px !important;
        }

        .form-check-inline {
            margin: 5px 15px;
        }

        label {
            width: 180px;
            justify-content: left !important;
        }

        .label-checkbox {
            width: 180px !important;
        }

        input[type=text] {
            width: 280px;

        }

        input-group select {
            min-width: 285px;
        }

        fieldset {
            padding: 10px;
            border-radius: 10px;
        }

        legend {
            padding-top: 5px;
            border-radius: 5px;
            border: 5px;
            margin-bottom: 20px;
            text-align: center;
            height: 50px;
            vertical-align: middle !important;
        }

        #map {
            height: 400px;
            width: 100%;
            margin-top: 10px;
        }

        .col-sm {
            text-align: center;
            background-color: #F0F0F0;
        }

        .imagecard {
            width: 100px;
            margin-left: auto;
            margin-right: auto;
            margin-top: 20%;
        }

        .contactDetail {
            margin-top: 15%;
            margin-bottom: 15%;
        }

        #contactCard {
            width: 90%;
            margin: auto;
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

<body>
    <?php

    // navigation & search bars
    require_once("./navigationMenu.php");


    ?>

    <!-- Custom JS -->
    <!-- <script src="./js/script.js"></script> -->

    <!-- google map API -->
    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDQJWK4iJkTx2qKbexRTHTUK8RFtgBrkdY&callback=initMap&libraries=&v=weekly" async></script>

    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script src="../vendor/twbs/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
    <link href="../node_modules/bootstrap-icons/font//bootstrap-icons.css" rel="stylesheet">
    <!-- <script src="https://code.jquery.com/jquery-3.4.1.slim.min.js" integrity="sha384-J6qa4849blE2+poT4WnyKhv5vZF5SrPo0iEjwBvKU7imGFAV0wwj1yYfoRSJoZ+n" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script> -->

    <div class="container-fluid">
        <legend class="text-light bg-dark" style="margin-top: 10px">Contact</legend>
        <div class="row" id="contactCard">
            <div class="col-sm">
                <img src="../images/headset.png" class="imagecard" alt="call"><br>
                <div class="contactDetail">
                    <h4>By Phone</h4><br>

                    Business Hours: <a href="tel:(519)-456-7890">(519)-456-7890</a><br>
                    Emergencies: <a href="tel:(226)-456-7890">(226)-456-7890</a>
                </div>
            </div>
            <div class="col-sm">
                <img src="../images/email.png" class="imagecard" alt="email"><br>
                <div class="contactDetail">
                    <h4>By E-mail</h4><br>
                    <a href="mailto:admin@rentalbuddy.ca">admin@rentalbuddy.ca</a>
                </div>
            </div>
            <div class="col-sm">
                <img src="../images/map.png" class="imagecard" alt="address"><br>
                <div class="contactDetail">
                    <h4>Location</h4><br>
                    <address>
                        Suite #1211<br>
                        121 Main Street<br>
                        London, Ontario<br>
                        Canada<br>
                        N3Y 3E8<br>
                    </address>
                </div>
            </div>
            <div class="col-sm">
                <img src="../images/wall-clock.png" class="imagecard" alt="worktime"><br>
                <div class="contactDetail" >
                    <h4>Hours of Operation</h4><br>
                    <div style="padding-left: 50px;">
                    <table>
                        <tr >
                            <td>Monday: </td>
                            <td>10:00 - 20:00</td>
                        </tr>
                        <tr>
                            <td>Tuesday: </td>
                            <td>Closed</td>
                        </tr>
                        <tr>
                            <td>Wednesday: </td>
                            <td>10:00 - 20:00</td>
                        </tr>
                        <tr>
                            <td>Thursday: </td>
                            <td>10:00 - 20:00</td>
                        </tr>
                        <tr>
                            <td>Friday: </td>
                            <td>10:00 - 17:00</td>
                        </tr>
                        <tr>
                            <td>Saturday: </td>
                            <td>10:00 - 13:00</td>
                        </tr>
                        <tr>
                            <td>Sunday: </td>
                            <td>Closed</td>
                        </tr>
                    </table>
                    </div>
                  </div>
            </div>
        </div>
    </div>

</body>

</html>