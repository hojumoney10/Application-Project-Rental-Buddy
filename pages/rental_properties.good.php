<?php
session_start();

// session_unset();
$_SESSION['PAGE'] = "rental_properties";
if (!isset($_SESSION['PAGEMODE'])) {
    $_SESSION['PAGEMODE'] = "LIST";
    $_SESSION['PAGENUM'] = 0;
}

?>
<!DOCTYPE html>
<html>

<head>
    <!-- 
        Title:       rental_properties.php
        Application: RentalBuddy
        Purpose:     Handles the crud functions of rental_properties
        Author:      G. Blandford,  Group 5, INFO-5139-01-21W
        Date:        March 7th, 2021 (February 15th, 2021)

        20210307     GPB    Check user logged in
        20210308     SKC    Added map API functionality
        20210311     GPB    Added bootstrap icons link

        20210404     SKC    Edited lat/lng field to readonly
        20210408     SKC    Edited file upload functionality for property photo

        20210415     GPB    Reverted to SPRINT 2 version and merged in SKH changes
                            due to commit to GIT of OLD version.

    -->

    <title>RentalBuddy - Rental Properties</title>

    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="RentalBuddy Rental Properties">
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

        .form-check-input-rental {
            margin: 10px 0 !important;
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

        input-group select  {
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
    require_once("./search-bar.php");
    require_once("./crud-buttons.php");

    // data access layer
    require_once("../dal/codes_dal.php");
    require_once("../dal/rental_properties_dal.php");

    // Check POST ACTIONS first
    if (isset($_POST['btn-add']) && ($_POST['btn-add'] == "Add")) { // Add clicked
        $_SESSION['PAGEMODE'] = "ADD";
        $_SESSION['PAGENUM'] = 0;
    } else if (isset($_POST['btn-edit']) && ($_POST['btn-edit'] == "Edit")) { // Edit clicked
        $_SESSION['PAGEMODE'] = "EDIT";
        $_SESSION['PAGENUM'] = 0;
    } else if (isset($_POST['btn-delete']) && ($_POST['btn-delete'] == "Delete")) { //
        $_SESSION['mode'] = "DELETE";
    } else if (isset($_POST['btn-view']) && ($_POST['btn-view'] == "View")) {
        $_SESSION['PAGEMODE'] = "VIEW";
        $_SESSION['PAGENUM'] = 0;
    } else if (isset($_POST['btn-cancel']) && ($_POST['btn-cancel'] == "Cancel")) {
        $_SESSION['PAGENUM'] = 0;
        $_SESSION['PAGEMODE'] = "LIST";
    } else if (isset($_POST['btn-save']) && ($_POST['btn-save'] == "Save")) { // Save clicked
        // Just let it through
    } else if ( isset($_POST['btn-search-landlords']) && ($_POST['btn-search-landlords'] == "...") 
                || isset($_POST['btn-select-landlord']) && ($_POST['btn-select-landlord'] == "Select") ) {
                // We have either called or closed the modal
    } else {
        $_SESSION['PAGENUM'] = 0;
        $_SESSION['PAGEMODE'] = "LIST";
    }

    // var_dump( $_SERVER['REQUEST_METHOD'] );
    // dump( $_SESSION );
    // var_dump( $_POST );
    // var_dump( $_GET );


    // $_ POSTing or $_GETting?
    if ($_SERVER['REQUEST_METHOD'] == 'GET' || $_SESSION['PAGEMODE'] == "LIST") {

        // Display Rental Properties
        formDisplayRentalProperties();
    } else if ($_SESSION['PAGEMODE'] == "ADD" || $_SESSION['PAGEMODE'] == "EDIT" || $_SESSION['PAGEMODE'] == "VIEW") {

        switch ($_SESSION['PAGENUM']) {

            case 0: // Show Form
                $_SESSION['PAGENUM'] = 1; // Set to validate

                // ADD record
                if ($_SESSION['PAGEMODE'] == "ADD") {

                    // Empty record
                    $_SESSION['rental_property_id'] = 0;
                    $_SESSION['rowdata'] = array();

                    // Show Rental Property page
                    formRentalProperty();

                    // EDIT RECORD
                } else if (isset($_POST['selected']) && strlen($_POST['selected'][0] > 0)) {

                    // Get Selected Rental Property 
                    $_SESSION['rental_property_id'] = $_POST['selected'][0];

                    // Get RentalProperty data
                    getRentalProperty();

                    // Show RentalProperty
                    formRentalProperty();

                    // LIST RECORDS
                } else {

                    formDisplayRentalProperties();
                }
                break;

            case 1: // Save
                if ((isset($_POST['btn-save'])) && ($_POST['btn-save'] == "Save")) {

                    // Validate
                    $err_msgs = validateRentalProperty();

                    if (count($err_msgs) > 0) {

                        displayErrors($err_msgs);
                        formRentalProperty();
                    } else {

                        // Save to database                     
                        saveRentalProperty();
                        $_SESSION['PAGENUM'] = 0;

                        // Clear row
                        unset($_SESSION['rowdata']);

                        $_SESSION['PAGEMODE'] = "LIST";
                        formDisplayRentalProperties();
                    }
                } else if ((isset($_POST['btn-cancel'])) && ($_POST['btn-cancel'] == "Cancel" || $_POST['btn-cancel'] == "OK")) {

                    $_SESSION['PAGEMODE'] = "LIST";
                    $_SESSION['PAGENUM'] = 0;

                    // Clear current data
                    unset($_SESSION['landord_id']);
                    unset($_SESSION['rowdata']);

                    formDisplayRentalProperties();
                }
                
                // Display a modal if we're searching landlords
                if (isset($_POST['btn-search-landlords']) && ($_POST['btn-search-landlords'] == "...")) {
                    
                    // We need to store the variables
                    // so let's call validate without error display
                    validateRentalProperty();
                                        
                    // Redisplay Property
                    formRentalProperty(1);

                } else if (isset($_POST['btn-select-landlord']) && ($_POST['btn-select-landlord'] == "Select")) {

                    if (isset($_POST['selected']) ) {
                        $_SESSION['rowdata']['landlord_id'] = $_POST['selected'][0];
                    }
                    formRentalProperty();
                }
                
                break;
            default:
        }
    } else if ($_SESSION['PAGEMODE'] == "LIST") {

        formDisplayRentalProperties();
    }

    // We can do anything here AFTER the form has loaded
    ?>

    <!-- Custom JS -->
    <!-- <script src="./js/script.js"></script> -->

    <!-- google map API -->
    <script
      src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDQJWK4iJkTx2qKbexRTHTUK8RFtgBrkdY&callback=initMap&libraries=&v=weekly"
      async
    ></script>

    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script src="../vendor/twbs/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
    <link href="../node_modules/bootstrap-icons/font//bootstrap-icons.css" rel="stylesheet">

    <!-- <script src="https://code.jquery.com/jquery-3.4.1.slim.min.js" integrity="sha384-J6qa4849blE2+poT4WnyKhv5vZF5SrPo0iEjwBvKU7imGFAV0wwj1yYfoRSJoZ+n" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script> -->
</body>

</html>

<?php

function validateRentalProperty()
{

    $rowdata = $_SESSION['rowdata'];

    $rowdata['rental_property_id'] = $_POST['rental-property-id'];

    
    $err_msgs = [];

    // landlord must have been selected
    if (!isset($rowdata['landlord_id'])) {
        $err_msgs[] = "A landlord must be selected";
    } 

    // listing reference
    if (!isset($_POST['listing-reference'])) {
        $err_msgs[] = "A listing reference is required";
    } else {
        $rowdata['listing_reference'] = $_POST['listing-reference'];
        if (strlen($rowdata['listing_reference']) == 0) {
            $err_msgs[] = "An listing reference is required";
        } else if (strlen($rowdata['listing_reference']) > 20) {
            $err_msgs[] = "The listing reference exceeds 20 characters";
        }
    }
    
    // address 1
    if (!isset($_POST['address-1'])) {
        $err_msgs[] = "An address is required";
    } else {
        $rowdata['address_1'] = $_POST['address-1'];
        if (strlen($rowdata['address_1']) == 0) {
            $err_msgs[] = "An address is required";
        } else if (strlen($rowdata['address_1']) > 50) {
            $err_msgs[] = "The address exceeds 50 characters";
        }
    }

    // address 2
    if (isset($_POST['address-2'])) {
        $rowdata['address_2'] = $_POST['address-2'];
        if (strlen($rowdata['address_2']) > 50) {
            $err_msgs[] = "The address exceeds 50 characters";
        }
    }

    // city
    if (!isset($_POST['city'])) {
        $err_msgs[] = "The city is required";
    } else {
        $rowdata['city'] = $_POST['city'];
        if (strlen($rowdata['city']) == 0) {
            $err_msgs[] = "The city is required";
        } else if (strlen($rowdata['city']) > 50) {
            $err_msgs[] = "The city exceeds 50 characters";
        }
    }

    // province code
    if (!isset($_POST['province'])) {
        $err_msgs[] = "A province is required";
    } else {
        $rowdata['province_code'] = $_POST['province'];
        if (strlen($rowdata['province_code']) == 0) {
            $err_msgs[] = "A province is required";
        } else if (strlen($rowdata['province_code']) > 2) {
            $err_msgs[] = "The province exceeds 2 characters";
        }
    }

    // postal code - use REGEX
    if (!isset($_POST['postal-code'])) {
        $err_msgs[] = "A postal code is required";
    } else {
        $rowdata['postal_code'] = $_POST['postal-code'];
        if (strlen($rowdata['postal_code']) == 0) {
            $err_msgs[] = "A postal code is required";
        } else if (!preg_match('/^([a-zA-Z]\d[a-zA-Z])\ {0,1}(\d[a-zA-Z]\d)$/', trim($rowdata['postal_code']))) {
            $err_msgs[] = "The postal code is not valid";
        }
    }

    // latitude
    if (isset($_POST['latitude'])) {
        $rowdata['latitude'] = $_POST['latitude'];
        if (strlen($rowdata['latitude']) > 20) {
            $err_msgs[] = "The latitude exceeds 20 characters";
        }
    }

    // longitude
    if (isset($_POST['longitude'])) {
        $rowdata['longitude'] = $_POST['longitude'];
        if (strlen($rowdata['longitude']) > 20 ) {
            $err_msgs[] = "The longitude exceeds 20 characters";
        }
    }

    // property type code
    if (!isset($_POST['property-type-code'])) {
        $err_msgs[] = "A property type is required";
    } else {
        $rowdata['property_type_code'] = $_POST['property-type-code'];
        if (strlen($rowdata['property_type_code']) == 0) {
            $err_msgs[] = "A property type is required";
        } else if (strlen($rowdata['property_type_code']) > 20) {
            $err_msgs[] = "The property type exceeds 20 characters";
        }
    }

    // number bedrooms
    if (!isset($_POST['number-bedrooms'])) {
        $err_msgs[] = "The number of bedrooms is required";
    } else {
        $rowdata['number_bedrooms'] = $_POST['number-bedrooms'];
        if ($rowdata['number_bedrooms'] < 1 || $rowdata['number_bedrooms'] > 9 ) {
            $err_msgs[] = "The number of bedrooms must be between 1 and 9";
        } 
    }    

    // parking space type code
    if (!isset($_POST['parking-type-code'])) {
        $err_msgs[] = "A parking type is required";
    } else {
        $rowdata['parking_space_type_code'] = $_POST['parking-type-code'];
        if (strlen($rowdata['parking_space_type_code']) == 0) {
            $err_msgs[] = "A parking type is required";
        } else if (strlen($rowdata['parking_space_type_code']) > 20) {
            $err_msgs[] = "The parking type exceeds 20 characters";
        }
    }

    // number parking spaces
    if (!isset($_POST['number-parking-spaces'])) {
        $err_msgs[] = "The number of parking spaces is required";
    } else {
        $rowdata['number_parking_spaces'] = $_POST['number-parking-spaces'];
        if ($rowdata['number_parking_spaces'] < 0 || $rowdata['number_parking_spaces'] > 99 ) {
            $err_msgs[] = "The number of parking spaces must be between 0 and 99";
        } 
    }    

    // rental duration type
    if (!isset($_POST['rental-duration-code'])) {
        $err_msgs[] = "A rental period is required";
    } else {
        $rowdata['rental_duration_code'] = $_POST['rental-duration-code'];
        if (strlen($rowdata['rental_duration_code']) == 0) {
            $err_msgs[] = "A rental period is required";
        } else if (strlen($rowdata['rental_duration_code']) > 20) {
            $err_msgs[] = "The rental period exceeds 20 characters";
        }
    }

    // smoking
    $rowdata['smoking_allowed'] = (int)isset($_POST['smoking-allowed']);

    // insurance
    $rowdata['insurance_required'] = (int)isset($_POST['insurance-required']);

    // status code
    if (!isset($_POST['status-code'])) {
        $err_msgs[] = "A status is required";
    } else {
        $rowdata['status_code'] = $_POST['status-code'];
        if (strlen($rowdata['status_code']) == 0) {
            $err_msgs[] = "A status is required";
        } else if (strlen($rowdata['status_code']) > 10) {
            $err_msgs[] = "The status exceeds 10 characters";
        }
    }

    // photo
    if(isset($_FILES)){
        $value = false;
        $array_file_extension = array('jpeg', 'jpg', 'png');
        foreach ($_FILES as $eachPhoto) {
            // When photo exist
            if ($eachPhoto['name'] != "") {
                if ($eachPhoto['type'] == "" || $eachPhoto['tmp_name'] == "" || $eachPhoto["size"] == 0 || $eachPhoto['error'] != 0) {
                    $value == false ? $err_msgs[] = ('Please check the file: '.$eachPhoto['name']):"";
                } else {
                    $ext = pathinfo(strtolower($eachPhoto['name']), PATHINFO_EXTENSION);
                    if (!in_array($ext, $array_file_extension)) {
                        $value == false ? $err_msgs[] = ('Please check the file extension: '.$eachPhoto['name']):"";
                    }
                }
            }
        }
    }

    $_SESSION['rowdata'] = $rowdata;
    return $err_msgs;
}

function formDisplayRentalProperties() {

    $fvalue = "";
    if (isset($_POST['btn-search']) && isset($_POST['text-search'])) {
        $_SESSION['text-search'] = trim($_POST['text-search']);
        $fvalue = $_SESSION['text-search'];
    } else if (isset($_POST['btn-search-clear'])) {
        $_SESSION['text-search'] = "";
        $fvalue = $_SESSION['text-search'];
    } else if (isset($_SESSION['text-search'])) {
        $fvalue = $_SESSION['text-search'];
    }
?>
    <form method="POST">
        <?php

        // Get Data
        getRentalProperties();

        // Search Bar
        getSearch($fvalue);

        // Get Standard CRUD buttons
        getCRUDButtons();
        ?>

    </form>
    </div>
<?php }

function formRentalProperty($showmodal = 0)
{
    // Get the data
    $row = $_SESSION['rowdata'];

?>
    <div class="container-fluid">

        <form class="form form-inline" method="POST" style="padding-right: 30px;" enctype="multipart/form-data">
            <fieldset class="bg-light">
                <legend class="text-light bg-dark">
                    <?php
                    switch ($_SESSION['PAGEMODE']) {
                        case "VIEW":
                            echo "View ";
                            break;
                        case "EDIT":
                            echo "Edit ";
                            break;
                        default:
                            break;
                    }
                    ?>Rental Property Details</legend>

                <!-- rental_property_id & landlord name-->
                <div class="input-group">
                    <label for="rental-property-id">Property No./Landlord</label>
                    <input type="text" size="10" maxlength="10" class="form-control" style="max-width: 100px" id="rental-property-id" name="rental-property-id" aria-describedby="rental-property-id-help" placeholder="" value="<?php echo $row['rental_property_id']; ?>" readonly>
                    <small id="rental-property-id-help" class="form-text text-muted"></small>

                    <input type="text" size="30" maxlength="50" 
                            class="form-control" 
                            id="landlord-legal-name" name="landlord-legal-name" 
                            value="<?php 
                                        echo getLandlordName($row['landlord_id']); ?>"
                            readonly
                            required
                            immediate="false">
                    <button type="submit" class="btn btn-danger" id="btn-search-landlords" name="btn-search-landlords" value="..." <?php echo ($_SESSION['PAGEMODE'] == 'VIEW') ? " disabled" : "" ?>>...</button>
                </div>

                <!-- listing reference -->
                <div class="input-group">
                    <label for="listing-reference">Ref.</label>
                    <input type="text" size="15" maxlength="15" style="max-width: 50%;" class="form-control" id="listing-reference" name="listing-reference" aria-describedby="listing-reference-help" placeholder="Enter listing reference number" required value="<?php echo $row['listing_reference']; ?>"<?php echo ($_SESSION['PAGEMODE'] == 'VIEW') ? " readonly" : "" ?>>
                    <small id="listing-reference-help" class="form-text text-muted"></small>
                </div>

                <!-- property type & # bedrooms -->

                <!-- address_1 -->
                <div class="input-group">
                    <label for="address-1">Address 1</label>
                    <input type="text" size="30" maxlength="50" class="form-control" id="address-1" name="address-1" aria-describedby="address-1-help" placeholder="Enter address" value="<?php echo $row['address_1']; ?>" required<?php echo ($_SESSION['PAGEMODE'] == 'VIEW') ? " readonly" : "" ?>>
                    <small id="address-1-help" class="form-text text-muted"></small>
                </div>

                <!-- address_2 -->
                <div class="input-group">
                    <label for="address-2">Address 2</label>
                    <input type="text" size="30" maxlength="50" class="form-control" id="address-2" name="address-2" aria-describedby="address-2-help" placeholder="Enter address, e.g. unit no." value="<?php echo $row['address_2']; ?>" <?php echo ($_SESSION['PAGEMODE'] == 'VIEW') ? " readonly" : "" ?>>
                    <small id="address-2-help" class="form-text text-muted"></small>
                </div>

                <!-- city -->
                <div class="input-group">
                    <label for="city">City</label>
                    <input type="text" size="30" maxlength="50" class="form-control" id="city" name="city" aria-describedby="city-help" placeholder="Enter city" value="<?php echo $row['city']; ?>" required<?php echo ($_SESSION['PAGEMODE'] == 'VIEW') ? " readonly" : "" ?>>
                    <small id="city-help" class="form-text text-muted"></small>
                </div>

                <!-- province & postal code -->
                <div class="input-group">
                    <label for="province">Province/Postal Code</label>
                    <select class="selectpicker form-control" style="max-width: 220px;" id="province" name="province" aria-describedby="province-help" placeholder="Enter province" required<?php echo ($_SESSION['PAGEMODE'] == 'VIEW') ? " readonly" : "" ?>>
                        <?php
                        getCodes('province', $row['province_code']);
                        ?>
                    </select>
                    <small id="province-help" class="form-text text-muted"></small>

                    <input type="text" style="max-width: 100px;" class="form-control" id="postal-code" name="postal-code" aria-describedby="postal-help" placeholder="Enter postal code" value="<?php echo $row['postal_code']; ?>" required<?php echo ($_SESSION['PAGEMODE'] == 'VIEW') ? " readonly" : "" ?>>
                    <small id="postal-code-help" class="form-text text-muted"></small>
                </div>

                <!-- lat & long -->
                <div class="input-group">
                    <label for="latitude">Lat./Long.</label>
                    <input type="text" size="15" maxlength="15" style="max-width: 50%;" class="form-control" id="latitude" name="latitude" aria-describedby="latitude-help" placeholder="Enter latitude" value="<?php echo $row['latitude']; ?>"<?php echo ($_SESSION['PAGEMODE'] == 'VIEW') ? " readonly" : "" ?>>
                    <small id="latitude-help" class="form-text text-muted"></small>

                    <input type="text" size="15" maxlength="15" style="max-width: 50%;" class="form-control" id="longitude" name="longitude" aria-describedby="longitude-help" placeholder="Enter longitude" value="<?php echo $row['longitude']; ?>"<?php echo ($_SESSION['PAGEMODE'] == 'VIEW') ? " readonly" : "" ?>>
                    <small id="longitude-help" class="form-text text-muted"></small>
                </div>

                <!-- property type & # bedrooms -->
                <div class="input-group">
                    <label for="property-type-code">Type/Bedrooms</label>
                    <select class="selectpicker form-control" style="max-width: 220px;" id="property-type-code" name="property-type-code" aria-describedby="property-type-code-help" placeholder="Enter property type" required<?php echo ($_SESSION['PAGEMODE'] == 'VIEW') ? " readonly" : "" ?>>
                        <?php
                        getCodes('property_type', $row['property_type_code']);
                        ?>
                    </select>
                    <small id="property-type-code-help" class="form-text text-muted"></small>

                    <input type="text" style="max-width: 100px;" class="form-control" id="number-bedrooms-code" name="number-bedrooms" aria-describedby="number-bedrooms-help" placeholder="# bedrooms" value="<?php echo $row['number_bedrooms']; ?>" required<?php echo ($_SESSION['PAGEMODE'] == 'VIEW') ? " readonly" : "" ?>>
                    <small id="number-bedrooms-help" class="form-text text-muted"></small>
                </div>

                <!-- prking type & spaces  -->
                <div class="input-group">
                    <label for="parking-type-code">Parking Spaces</label>
                    <select class="selectpicker form-control" style="max-width: 220px;" id="parking-type" name="parking-type-code" aria-describedby="parking-type-code-help" placeholder="Enter type of parking" required<?php echo ($_SESSION['PAGEMODE'] == 'VIEW') ? " readonly" : "" ?>>
                        <?php
                        getCodes('parking_space', $row['parking_space_type_code']);
                        ?>
                    </select>
                    <small id="parking-type-code-help" class="form-text text-muted"></small>

                    <input type="text" style="max-width: 100px;" class="form-control" id="number-parking-spaces" name="number-parking-spaces" aria-describedby="number-parking-spaces-help" placeholder="Enter number of spaces" value="<?php echo $row['number_parking_spaces']; ?>" required<?php echo ($_SESSION['PAGEMODE'] == 'VIEW') ? " readonly" : "" ?>>
                    <small id="number-parking-spaces-help" class="form-text text-muted"></small>
                </div>

                <!-- rental durtion  -->
                <div class="input-group">
                    <label for="rental-duration-code">Frequency</label>
                    <select class="selectpicker form-control" style="max-width: 220px" id="rental-duration-code" name="rental-duration-code" aria-describedby="rental-duration-code-help" placeholder="Enter rental duration" value="<?php echo $row['rental_duration_code']; ?>" <?php echo ($_SESSION['PAGEMODE'] == 'VIEW') ? " readonly" : "" ?>>
                        <?php
                        getCodes('rental_duration', $row['rental_duration_code']);
                        ?>
                    </select>
                    <small id="rental-duration-code-help" class="form-text text-muted"></small>
                </div>

                <!-- smoking  -->
                <div class="input-group">
                    <label for="smoking-allowed">Smoking?</label>
                    <input type="checkbox" class="form-check-input form-check-input-rental" id="smoking-allowed" name="smoking-allowed" <?php echo ($row['smoking_allowed']) ? "checked" : ""; ?> <?php echo ($_SESSION['PAGEMODE'] == 'VIEW') ? " readonly" : "" ?>>
                </div>

                <!-- insurance required  -->
                <div class="input-group">
                    <label for="insurance-required">Insurance?</label>
                    <input type="checkbox" class="form-check-input form-check-input-rental" id="insurance-required" name="insurance-required" <?php echo ($row['insurance_required']) ? "checked" : ""; ?> <?php echo ($_SESSION['PAGEMODE'] == 'VIEW') ? " readonly" : "" ?>>
                </div>

                <!--status  -->
                <div class="input-group">
                    <label for="status-code">Status</label>
                    <select class="selectpicker form-control" style="max-width: 100px" id="status-code" name="status-code" aria-describedby="status-code-help" placeholder="Enter status" required<?php echo ($_SESSION['PAGEMODE'] == 'VIEW') ? " readonly" : "" ?>>
                        <?php
                        getCodes('property_status', $row['status_code']);
                        ?>
                    </select>
                    <small id="status-code-help" class="form-text text-muted"></small>
                </div>

                <!-- picture -->
                <div class="input-group">
                    <label for="photo">Photo (PNG or JPEG)</label>
                    <div id="photoDiv">
                        <?php 
                            // echo ($row['photo']) ? "<label class='form-label' style='width:377px'>Uploaded file: <a href='/rental_property_photo/".$row['photo']."' target='_blank'>".$row['photo']."</a></label><br>":"" 
                            ?>
                        <?php
                            echo ( $row['photo'] ) ? '<img src="/rental_property_photo/' . $row['photo'] . '" style="width: 300px; padding-bottom: 10px;"><br>' : ''
                         ?>
                        <input class="form-control" type="file" id="photo" name="photo" <?php echo ($_SESSION['PAGEMODE'] == 'VIEW') ? " disabled" : ""?>>
                        <?php 
                            // echo ($_SESSION['PAGEMODE'] == 'ADD' || $_SESSION['PAGEMODE'] == 'EDIT') ? "<label  style='width:377px'>(Photo must be PNG or JPG format)</label>":""
                    ?>                        
                    </div>

                    <small id="photo-help" class="form-text text-muted"></small>
                </div>

                <!-- map -->
                <div class="input-group">
                    <label for="map">Property Location</label>
                    <script>
                        // Initialize and add the map
                        function initMap() {

                            // The location of property
                            var property = { lat: <?php echo $row['latitude']; ?>, lng: <?php echo $row['longitude']; ?> };

                            // The map, centered at property
                            const map = new google.maps.Map(document.getElementById("map"), {
                                zoom: 16,
                                center: property,
                            });

                            // The marker, positioned at property
                            var marker = new google.maps.Marker({
                                position: property,
                                map: map,
                            });

                        }
                    </script>
                    <div id="map"></div>
                </div>

                <table>
                    <tr>
                        <?php
                        if ($_SESSION['PAGEMODE'] == 'EDIT' || $_SESSION['PAGEMODE'] == 'ADD') { ?>
                            <td><input type="submit" class="btn btn-success btn-crud" name="btn-save" value="Save"></td>
                        <?php            }
                        ?>
                        <td><input type="submit" form="form-cancel" class="btn <?php echo ($_SESSION['PAGEMODE'] == 'EDIT' || $_SESSION['PAGEMODE'] == 'ADD') ? 'btn-secondary' : 'btn-primary'; ?> btn-crud" name="btn-cancel" value="<?php echo ($_SESSION['PAGEMODE'] == 'EDIT' || $_SESSION['PAGEMODE'] == 'ADD') ? 'Cancel' : 'OK'; ?>"></td>
                    </tr>
                </table>
            </fieldset>

<?php
        if ($showmodal) {
?>
            <!-- Modal show landlords  -->
            <div class="modal fade" id="show-landlords-modal" tabindex="-1" role="dialog" style="max-height: 80%">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="select-landlord-modal-label">Select Landlord</h5>
                            <button type="button" class="btn btn-secondary close" data-bs-dismiss="modal" aria-label="Cancel">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body" id="show-landlords-modal-body">
                            <?php
                                showLandlords();
                            ?>
                        </div>
                        <div class="modal-footer">
                            <input type="submit" class="btn btn-secondary" name="btn-select-landlord" value="Select"
                               >
                            </input>
                        </div>
                    </div>
                </div>
            </div>
            <script type="text/javascript">
                $(function(){
                    //instantiate your content as modal
                    $('#show-landlords-modal').modal({
                        //modal options here, like keyboard: false for e.g.
                    });

                    //show the modal when dom is ready
                    $('#show-landlords-modal').modal('show');
                });
            </script>  
<?php
        }
?>
        </form>
        <!-- empty form for cancel button -->
        <form id="form-cancel" hidden>
            <form>
    </div>
<?php
}
?>