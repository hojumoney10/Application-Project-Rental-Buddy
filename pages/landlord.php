<?php 
    session_start(); 
?>
<!-- 
    Title:       landlord.php
    Application: RentalBuddy
    Purpose:     Handles the create/update of landlord data
    Author:      G. Blandford, Group 5, INFO-5139-01-21W
    Date:        February 13th, 2021 (February 13th, 2021) 
-->
<!doctype html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="../vendor/twbs/bootstrap/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="css/style.css" type="text/css">

    <!-- Google Font -->
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css2?family=Source+Sans+Pro&display=swap" rel="stylesheet">
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

                        <form class="form form-inline" method="POST" action="./index.html">
                            <fieldset class="bg-light">
                                <legend class="text-light bg-dark">Customer Details</legend>

                                <!--Customer no.-->
                                <div class="form-group">
                                    <label for="customer-id">Customer No.</label>
                                    <input type="text" size="30" maxlength="50" class="form-control" id="customer-id" name="customer-id" aria-describedby="customer-id-help" placeholder="Enter customer number or '' for ALL">
                                    <small id="customer-id-help" class="form-text text-muted"></small>
                                </div>

                                <!--First Name -->
                                <div class="form-group">
                                    <label for="first-name">First Name</label>
                                    <input type="text" size="30" maxlength="50" class="form-control" id="first-name" name="first-name" aria-describedby="first-name-help" placeholder="Enter first name">
                                    <small id="first-name-help" class="form-text text-muted"></small>
                                </div>

                                <!--Last Name -->
                                <div class="form-group">
                                    <label for="last-name">Last Name</label>
                                    <input type="text" size="30" maxlength="50" class="form-control" id="last-name" name="last-name" aria-describedby="last-name-help" placeholder="Enter last name">
                                    <small id="last-name-help" class="form-text text-muted"></small>
                                </div>

                                <!--Address -->
                                <div class="form-group">
                                    <label for="address">Address</label>
                                    <input type="text" size="30" maxlength="50" class="form-control" id="address" name="address" aria-describedby="address-help" placeholder="Enter address">
                                    <small id="address-help" class="form-text text-muted"></small>
                                </div>

                                <!--City -->
                                <div class="form-group">
                                    <label for="city">City</label>
                                    <input type="text" size="30" maxlength="50" class="form-control" id="city" name="city" aria-describedby="city-help" placeholder="Enter city">
                                    <small id="city-help" class="form-text text-muted"></small>
                                </div>

                                <!--Province -->
                                <div class="form-group">
                                    <label for="province">Province</label>
                                    <select class="selectpicker form-control" id="province" name="province" aria-describedby="province-help" placeholder="Enter province">
                                        <option>Alberta</option>
                                        <option>British Columbia</option>
                                        <option>Manitoba</option>
                                        <option>New Brunswick</option>
                                        <option>Newfoundland and Labrador</option>
                                        <option>Northwest Territories</option>
                                        <option>Nova Scotia</option>
                                        <option>Nunavut</option>
                                        <option selected>Ontario</option>
                                        <option>Prince Edward Island</option>
                                        <option>Quebec</option>
                                        <option>Saskatchewan</option>
                                        <option>Yukon</option>
                                    </select>
                                    <small id="province-help" class="form-text text-muted"></small>
                                </div>

                                <!--Postal Code -->
                                <div class="form-group">
                                    <label for="postal-code">Postal Code</label>
                                    <input type="text" size="30" maxlength="50" class="form-control" id="postal-code" name="postal-code" aria-describedby="postal-code-help" placeholder="Enter Postal Code">
                                    <small id="postal-code-help" class="form-text text-muted"></small>
                                </div>

                                <!-- <div class="form-group">
                                    <button type="button" id="btn-create" onclick="createCustomer();" class="btn btn-info fa fa-file" data-toggle="tooltip" data-placement="right" title="Add"></button>
                                    <button type="button" id="btn-edit" onclick="editCustomer();" class="btn btn-info fa fa-edit" data-toggle="tooltip" data-placement="right" title="Edit"></button>
                                    <button type="button" onclick="cancelEdit();" id="btn-undo" class="btn btn-info fa fa-undo" data-toggle="tooltip" data-placement="right" title="Undo"></button>
                                    <button type="button" onclick="saveCustomer();" id="btn-save" class="btn btn-info fa fa-save" data-toggle="tooltip" data-placement="right" title="Save"></button>
                                    <div data-toggle="tooltip" data-placement="right" title="Delete">
                                        <button type="button" id="btn-delete" class="btn btn-danger fa fa-trash" data-toggle="modal" data-target="#confirm-delete-modal"></button>
                                    </div>
                                </div> -->
                            </fieldset>
                        </form>

                    </div>
                </div>
                <div class="col-"></div>
            </div>

        <!-- Optional JavaScript -->
        <script src="../vendor/twbs/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
        <script src="https://code.jquery.com/jquery-3.4.1.slim.min.js" integrity="sha384-J6qa4849blE2+poT4WnyKhv5vZF5SrPo0iEjwBvKU7imGFAV0wwj1yYfoRSJoZ+n" crossorigin="anonymous"></script>
        <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
    </div>
</body>
</html>
