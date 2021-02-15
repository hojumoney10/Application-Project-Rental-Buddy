<?php
session_start();

$_SESSION['PAGE'] = "landlords";

?>
<!DOCTYPE html>
<html>

<head>
    <!-- 
        Title:       landlords.php
        Application: RentalBuddy
        Purpose:     Handles the crud functions of landlords
        Author:      G. Blandford, Group 5, INFO-5139-01-21W
        Date:        February 14th, 2021 (February 10th, 2021) 
    -->

    <title>RentalBuddy - Landlords</title>

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

        nav {
            margin-top: 20px;
        }

        .btn-crud img {
            color: white;
        }


        .form-inline {
            /* margin-top: 10px; */
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

        select {
            min-width: 285px;
            /* margin-right: 10px !important; */
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
    require_once("../dal/landlords_dal.php");

    // Check POSTS 
    if ( isset( $_POST['btn-add'] ) && ( $_POST['btn-add'] == "Add") ) { // Add clicked
        $_SESSION['PAGEMODE'] = "ADD";
        $_SESSION['PAGENUM'] = 0;
echo 'Add clicked';

    } else if ( isset( $_POST['btn-edit'] ) && ($_POST['btn-edit'] == "Edit") ) { // Edit clicked
        $_SESSION['PAGEMODE'] = "EDIT";
        $_SESSION['PAGENUM'] = 0;
// echo 'Edit clicked';

    } else if ( isset($_POST['btn-delete'] ) && ( $_POST['btn-delete'] == "Delete") ) { //
        $_SESSION['mode'] = "DELETE";
// echo 'Delete clicked';

    } else if ( isset($_POST['btn-view'] ) && ($_POST['btn-view'] == "View") ) {
        $_SESSION['PAGEMODE'] = "VIEW";
        $_SESSION['PAGENUM'] = 0;
// echo 'View clicked';
    } else if ( isset($_POST['btn-cancel'] ) && ( $_POST['btn-cancel'] == "Cancel") ) {
        // if ( $_SESSION['PAGEMODE'] == "ADD"  ) {
            $_SESSION['PAGENUM'] = 0;
//echo 'Cancel clicked';
//         } else {
// echo 'Cancel clicked';
//         }
        $_SESSION['PAGEMODE'] = "LIST";
    }

    if (($_SESSION['PAGEMODE'] == "ADD") && ($_SERVER['REQUEST_METHOD'] == "GET")) {
        switch ($_SESSION['PAGENUM']) {
            case 0:
            case 1:
                formLandlord();
                break;
            default:
        }
    } else if ($_SESSION['PAGEMODE'] == "VIEW" || $_SESSION['PAGEMODE'] == "EDIT") {

        switch ($_SESSION['PAGENUM']) {

            case 0: // Show Form
                $_SESSION['PAGENUM'] = 1; // Set to validate

                if (isset($_POST['selected']) && strlen($_POST['selected'][0] > 0 ) ) {

                    //$landlord_id = 
                    $_SESSION['landlord_id'] = $_POST['selected'][0]; //$landlord_id;

                    // Get landlord data
                    getLandlord();

                    // Show landlord
                    formLandlord();
                } else {

                    formDisplayLandlords(); 
                }
                break;

            case 1: // Save
                if ( ( isset($_POST['btn-save'] ) ) && ( $_POST['btn-save'] == "Save" ) ) {

                    // Validate
                    $err_msgs = validateLandlord();

                    if (count($err_msgs) > 0) {
                        displayErrors($err_msgs);
                        formLandlord();
                    } else {
                        
                        // Save $_POST variables
                        savePostVariables();

                        // Save to database                     
                        saveLandlord();
                        $_SESSION['PAGENUM'] = 0;

                        // Clear row
                        unset($_SESSION['rowdata']);

                        $_SESSION['PAGEMODE'] = "LIST";
                        formDisplayLandlords();
                    }

                } else if  ( ( isset($_POST['btn-cancel'] ) ) && ( $_POST['btn-cancel'] == "Cancel" || $_POST['btn-cancel'] == "OK" ) ) {

                    $_SESSION['PAGEMODE'] = "LIST";
                    $_SESSION['PAGENUM'] = 0;

                    // Clear current data
                    unset($_SESSION['landord_id']);
                    unset($_SESSION['rowdata']);

                    formDisplayLandlords();
                }
                break;
            default:
        }
    } else if ($_SESSION['PAGEMODE'] == "ADD" ) {

        switch ($_SESSION['PAGENUM']) {

            case 0: // Show Form
                $_SESSION['PAGENUM'] = 1; // Set to validate

                // Empty record
                $_SESSION['landlord_id'] = 0;
                $_SESSION['rowdata'] = array();
echo 'adding...';
                // Show landlord page
                formLandlord();

                break;

            case 1: // Save
                if ( ( isset($_POST['btn-save'] ) ) && ( $_POST['btn-save'] == "Save" ) ) {

 echo 'save clicked...';

                    // Validate
                    $err_msgs = validateLandlord();

                    if (count($err_msgs) > 0) {
                        displayErrors($err_msgs);
                        formLandlord();

                    } else {
echo 'validated ok...';
                        
                        // Save $_POST variables
                        savePostVariables();

                        // Save to database                     
                        saveLandlord();
                        $_SESSION['PAGENUM'] = 0;

                        // Clear row
                        unset($_SESSION['rowdata']);

                        $_SESSION['PAGEMODE'] = "LIST";
                        formDisplayLandlords();
                    }

                } else if ( ( isset($_POST['btn-cancel'] ) ) && ( $_POST['btn-cancel'] == "Cancel" || $_POST['btn-cancel'] == "OK" ) ) {

                    $_SESSION['PAGEMODE'] = "LIST";
                    $_SESSION['PAGENUM'] = 0;

                    // Clear current data
                    unset($_SESSION['landord_id']);
                    unset($_SESSION['rowdata']);

                    formDisplayLandlords();
                }
                break;
            default:
        }
    } else if ($_SESSION['PAGEMODE'] == "LIST") {

        formDisplayLandlords();
    }

    // We can do anything here AFTER the form has loaded
    ?>

    <!-- Custom JS -->
    <!-- <script src="./js/script.js"></script> -->

    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script src="../vendor/twbs/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.4.1.slim.min.js" integrity="sha384-J6qa4849blE2+poT4WnyKhv5vZF5SrPo0iEjwBvKU7imGFAV0wwj1yYfoRSJoZ+n" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
</body>

</html>

<?php

function validateLandlord() {

    $err_msgs = [];

    // Do data validation here
    return $err_msgs;
}

function displayErrors($err_msgs) {

    // Display errors, bootstrap modal?
}

// Save $_POST variables to row
function savePostVariables() {

// print_r($_POST);
    $rowdata = $_SESSION['rowdata'];

    $rowdata['landlord_id'] = $_POST['landlord-id'];
    $rowdata['legal_name'] = $_POST['legal-name'];
    $rowdata['salutation_code'] = $_POST['salutation'];
    $rowdata['first_name'] = $_POST['first-name'];
    $rowdata['last_name'] = $_POST['last-name'];
    $rowdata['address_1'] = $_POST['address-1'];
    $rowdata['address_2'] = $_POST['address-2'];
    $rowdata['city'] = $_POST['city'];
    $rowdata['province_code'] = $_POST['province'];
    $rowdata['postal_code'] = $_POST['postal-code'];
    $rowdata['phone'] = unformatPhone($_POST['phone']);
    $rowdata['fax'] = unformatPhone($_POST['fax']);
    $rowdata['email'] = $_POST['email'];
    $rowdata['sms'] = unformatPhone($_POST['sms']);
    $rowdata['status_code'] = $_POST['status-code'];

    $_SESSION['rowdata'] = $rowdata;
}

function formDisplayLandlords()
{
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
        getLandlords();

        // Search Bar
        getSearch($fvalue);

        // Get Standard CRUD buttons
        getCRUDButtons();
        ?>

    </form>
    </div>
<?php }

function formLandlord()
{
    // Get the data
    $row = $_SESSION['rowdata'];

?>
    <div class="container-fluid">

        <!-- <form class="form form-inline" method="POST" style="max-height: 450px; padding-right: 30px;overflow-y:scroll"> -->
        <form class="form form-inline" method="POST" style="padding-right: 30px;">
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
                    ?>Landlord Details</legend>

                <!-- Landlord no.-->
                <div class="input-group">
                    <label for="landlord-id">Landlord No.</label>
                    <input type="text" size="10" maxlength="10" class="form-control" style="max-width: 100px" id="landlord-id" name="landlord-id" aria-describedby="landlord-id-help" placeholder="" value="<?php echo $row['landlord_id']; ?>" readonly>
                    <small id="landlord-help" class="form-text text-muted"></small>
                </div>

                <!-- Legal name -->
                <div class="input-group">
                    <label for="legal-name">Legal Name</label>
                    <input type="text" size="30" maxlength="50" class="form-control" id="legal-name" name="legal-name" aria-describedby="legal-name-help" placeholder="Enter legal name" value="<?php echo $row['legal_name']; ?>">
                    <small id="legal-name-help" class="form-text text-muted"></small>
                </div>

                <!--Salutation -->
                <div class="input-group">
                    <label for="salutation">Salutation</label>
                    <select class="selectpicker form-control" style="max-width: 100px" id="salutation" name="salutation" aria-describedby="salutation-help" placeholder="Enter salutation" value="<?php echo $row['salutation_code']; ?>">
                        <?php
                        getCodes('salutation', $row['salutation_code']);
                        ?>
                    </select>
                    <small id="salutation-help" class="form-text text-muted"></small>
                </div>

                <!-- first name -->
                <div class="input-group">
                    <label for="first-name">First Name</label>
                    <input type="text" size="30" maxlength="50" class="form-control" id="first-name" name="first-name" aria-describedby="first-name-help" placeholder="Enter first name" value="<?php echo $row['first_name']; ?>">
                    <small id="first-name-help" class="form-text text-muted"></small>
                </div>

                <!-- last name -->
                <div class="input-group">
                    <label for="last-name">Last Name</label>
                    <input type="text" size="30" maxlength="50" class="form-control" id="last-name" name="last-name" aria-describedby="last-name-help" placeholder="Enter last name" value="<?php echo $row['last_name']; ?>">
                    <small id="last-name-help" class="form-text text-muted"></small>
                </div>

                <!-- address_1 -->
                <div class="input-group">
                    <label for="address-1">Address 1</label>
                    <input type="text" size="30" maxlength="50" class="form-control" id="address-1" name="address-1" aria-describedby="address-1-help" placeholder="Enter address" value="<?php echo $row['address_1']; ?>">
                    <small id="address-1-help" class="form-text text-muted"></small>
                </div>

                <!-- address_2 -->
                <div class="input-group">
                    <label for="address-2">Address 2</label>
                    <input type="text" size="30" maxlength="50" class="form-control" id="address-2" name="address-2" aria-describedby="address-2-help" placeholder="Enter address, e.g. unit no." value="<?php echo $row['address_2']; ?>">
                    <small id="address-2-help" class="form-text text-muted"></small>
                </div>

                <!-- city -->
                <div class="input-group">
                    <label for="city">City</label>
                    <input type="text" size="30" maxlength="50" class="form-control" id="city" name="city" aria-describedby="city-help" placeholder="Enter city" value="<?php echo $row['city']; ?>">
                    <small id="city-help" class="form-text text-muted"></small>
                </div>

                <!-- province --><!-- postal code -->
                <div class="input-group">
                    <label for="province">Province/Postal Code</label>
                    <select class="selectpicker form-control" style="max-width: 220px;" id="province" name="province" aria-describedby="province-help" placeholder="Enter province">
                        <?php
                        getCodes('province', $row['province_code']);
                        ?>
                    </select>
                    <small id="province-help" class="form-text text-muted"></small>

                    <input type="text" style="max-width: 100px;" class="form-control" id="postal-code" name="postal-code" aria-describedby="postal-help" placeholder="Enter postal code" value="<?php echo $row['postal_code']; ?>">
                    <small id="postal-code-help" class="form-text text-muted"></small>
                </div>

                <!-- phone -->
                <div class="input-group">
                    <label for="phone">Phone/Fax/SMS</label>
                    <input type="tel" size="15" maxlength="15" style="max-width: 33%;" class="form-control" id="phone" name="phone" aria-describedby="phone-help" placeholder="Enter main phone number" value="<?php echo formatPhone($row['phone']); ?>">
                    <small id="phone-code-help" class="form-text text-muted"></small>

                    <input type="tel" size="15" maxlength="15" style="max-width: 33%;" class="form-control" id="fax" name="fax" aria-describedby="fax-help" placeholder="Enter fax number" value="<?php echo formatPhone($row['fax']); ?>">
                    <small id="fax-code-help" class="form-text text-muted"></small>
                <!-- </div> -->

                <!-- sms -->
                <!-- <div class="input-group"> -->
                    <!-- <label for="sms">SMS</label> -->
                    <input type="tel" size="15" maxlength="15" style="max-width: 33%;" class="form-control" id="sms" name="sms" aria-describedby="sms-help" placeholder="Enter number for SMS" value="<?php echo formatPhone($row['sms']); ?>">
                    <small id="sms-help" class="form-text text-muted"></small>
                </div>

                <!-- email -->
                <div class="input-group">
                    <label for="email">Email</label>
                    <input type="email" size="30" maxlength="50" class="form-control" id="email" name="email" aria-describedby="email-help" placeholder="Enter email address" value="<?php echo $row['email']; ?>">
                    <small id="email-help" class="form-text text-muted"></small>
                </div>

                <!--Salutation -->
                <div class="input-group">
                    <label for="status-code">Status</label>
                    <select class="selectpicker form-control" style="max-width: 100px" id="status-code" name="status-code" aria-describedby="status-code-help" placeholder="Enter status">
                        <?php
                        getCodes('landlord_status', $row['status_code']);
                        ?>
                    </select>
                    <small id="status-code-help" class="form-text text-muted"></small>
                </div>

                <table>
                    <tr>
<?php 
                if ($_SESSION['PAGEMODE'] == 'EDIT' || $_SESSION['PAGEMODE'] == 'ADD') { ?>
                        <td><input type="submit" class="btn btn-success btn-crud" name="btn-save" value="Save"></td>
<?php            }
?>
                        <td><input type="submit" class="btn btn-secondary btn-crud" name="btn-cancel" value="<?php echo ($_SESSION['PAGEMODE'] == 'EDIT' || $_SESSION['PAGEMODE'] == 'ADD' ) ? 'Cancel' : 'OK'; ?>"></td>
                    </tr>
                </table>
            </fieldset>
        </form>
    </div>
<?php
}
?>