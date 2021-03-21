<?php
session_start();
include_once("./check_session.php");
?>
<!-- 
        Title:       leases.php
        Application: RentalBuddy
        Purpose:     Handles the crud functions of landlords
        Author:      G. Blandford, S. Jeong  Group 5, INFO-5139-01-21W
        Date:        March 19th, 2021 (February 10th, 2021)

        20210220    GPB     Updated all links, and now menus are user-driven
        20210221    GPB     Added readonly listing ref & tenant name
        20210307    GPB     Check user logged in           
        20210311    GPB     Added bootstrap icons link
        20210319     TK     Added Upload Rental Document feature

    -->
<?php
session_start();

// session_unset();
$_SESSION['PAGE'] = "leases";
if (!isset($_SESSION['PAGEMODE'])){
    $_SESSION['PAGEMODE'] = "LIST";
    $_SESSION['PAGENUM'] = 0;
}

?>
<!DOCTYPE html>
<html>

<head>

    <title>RentalBuddy - Leases</title>

    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="RentalBuddy Leases">
    <meta name="author" content="Graham Blandford, Sirlin Jeong">

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

    select {
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
    require_once("../dal/leases_dal.php");

    // Check POST ACTIONS first
    if ( isset( $_POST['btn-add'] ) && ( $_POST['btn-add'] == "Add") ) { // Add clicked
        $_SESSION['PAGEMODE'] = "ADD";
        $_SESSION['PAGENUM'] = 0;

    } else if ( isset( $_POST['btn-edit'] ) && ($_POST['btn-edit'] == "Edit") ) { // Edit clicked
        $_SESSION['PAGEMODE'] = "EDIT";
        $_SESSION['PAGENUM'] = 0;

    } else if ( isset($_POST['btn-delete'] ) && ( $_POST['btn-delete'] == "Delete") ) { //
        $_SESSION['mode'] = "DELETE";

    } else if ( isset($_POST['btn-view'] ) && ($_POST['btn-view'] == "View") ) {
        $_SESSION['PAGEMODE'] = "VIEW";
        $_SESSION['PAGENUM'] = 0;

    } else if ( isset($_POST['btn-cancel'] ) && ( $_POST['btn-cancel'] == "Cancel") ) {
        $_SESSION['PAGENUM'] = 0;
        $_SESSION['PAGEMODE'] = "LIST";
    } else if ( isset($_POST['btn-save'] ) && ( $_POST['btn-save'] == "Save") ) {
    } else {
        $_SESSION['PAGENUM'] = 0;
        $_SESSION['PAGEMODE'] = "LIST";
    }

    // var_dump( $_SERVER['REQUEST_METHOD'] );
    // var_dump( $_SESSION );
    // var_dump( $_POST );
    // var_dump( $_GET );

    // $_ POSTing or $_GETting?
    IF ($_SERVER['REQUEST_METHOD'] == 'GET' || $_SESSION['PAGEMODE'] == "LIST" ) {

        // Display Leases
        formDisplayLeases();

    } else if ( $_SESSION['PAGEMODE'] == "ADD" || $_SESSION['PAGEMODE'] == "EDIT" || $_SESSION['PAGEMODE'] == "VIEW" ) {

        switch ($_SESSION['PAGENUM']) {

            case 0: // Show Form
                $_SESSION['PAGENUM'] = 1; // Set to validate

                // ADD record
                if ($_SESSION['PAGEMODE'] == "ADD") {

                    // Empty record
                    $_SESSION['lease_id'] = 0;
                    $_SESSION['rowdata'] = array();

                    // Show lease page
                    formLease();

                // EDIT RECORD
                } else if (isset($_POST['selected']) && strlen($_POST['selected'][0] > 0 ) ) {

                    // Get Selected Lease 
                    $_SESSION['lease_id'] = $_POST['selected'][0];

                    // Get Lease data
                    getLease();

                    // Show Lease
                    formLease();

                // LIST RECORDS
                } else {

                    formDisplayLeases(); 
                }
                break;

            case 1: // Save
                if ( ( isset($_POST['btn-save'] ) ) && ( $_POST['btn-save'] == "Save" ) ) {
                    // Validate
                    $err_msgs = validateLease();

                    if (count($err_msgs) > 0) {

                        displayErrors($err_msgs);
                        formLease();
                    } else {

                        // Save to database                     
                        saveLease();
                        $_SESSION['PAGENUM'] = 0;

                        // Clear row
                        unset($_SESSION['rowdata']);

                        $_SESSION['PAGEMODE'] = "LIST";
                        formDisplayLeases();
                    }

                } else if  ( ( isset($_POST['btn-cancel'] ) ) && ( $_POST['btn-cancel'] == "Cancel" || $_POST['btn-cancel'] == "OK" ) ) {

                    $_SESSION['PAGEMODE'] = "LIST";
                    $_SESSION['PAGENUM'] = 0;

                    // Clear current data
                    unset($_SESSION['lease_id']);
                    unset($_SESSION['rowdata']);

                    formDisplayLeases();
                }
                break;
            default:
        }
    } else if ($_SESSION['PAGEMODE'] == "LIST") {

        formDisplayLeases();
    }

    // We can do anything here AFTER the form has loaded
    ?>

    <!-- Custom JS -->
    <!-- <script src="./js/script.js"></script> -->

    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script src="../vendor/twbs/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
    <link href="../node_modules/bootstrap-icons/font//bootstrap-icons.css" rel="stylesheet">
    <!-- <script src="https://code.jquery.com/jquery-3.4.1.slim.min.js" integrity="sha384-J6qa4849blE2+poT4WnyKhv5vZF5SrPo0iEjwBvKU7imGFAV0wwj1yYfoRSJoZ+n" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script> -->
</body>

</html>

<?php

//lease function
function validateLease() {   

    $rowdata = $_SESSION['rowdata'];
    $rowdata['lease_id'] = $_POST['lease-id'];

    if( isset($_POST['rental-property-id'] ) ) {
        $rowdata['rental_property_id'] = $_POST['rental-property-id'];
    } 

    if( isset($_POST['tenant-id'] ) ) {
		$rowdata['tenant_id'] = $_POST['tenant-id'];
	} 

    $err_msgs = [];

    // start date
	if( !isset($_POST['start-date'] ) ) {
		$err_msgs[] = "A date of start is required";
	} else {
		$rowdata['start_date'] = $_POST['start-date'];
		if (strlen($rowdata['start_date']) == 0){
			$err_msgs[] = "A date of start is required";
		} 
	}

    // End Date
	if( isset($_POST['end-date'] ) ) {
		$rowdata['end_date'] = $_POST['end-date'];
	} 

    // payment day
    if( !isset($_POST['payment-day'] ) ) {
        $err_msgs[] = "A payment day is required";
    } else {
		$rowdata['payment_day'] = $_POST['payment-day'];
		if ($rowdata['payment_day'] < 0){
			$err_msgs[] = "A payment day is not valid";
		}else if ($rowdata['payment_day'] > 32){
            $err_msgs[] = "A payment day is not valid";
        }
	}
    // file check.
    if (file_exists($_FILES['document-file']['tmp_name']) || is_uploaded_file($_FILES['document-file']['tmp_name'])){
        $value = false;
        $array_file_extension = array('application/vnd.openxmlformats-officedocument.wordprocessingml.document', 'application/pdf', 'text/plain', 'image/jpeg');
        foreach($array_file_extension as $extension){
            if($_FILES['document-file']['type'] == $extension){
                $value = true;
            }
        }
        unset($extension);
        $value == false ? $err_msgs[] = 'Please check the file extension.':"";
            

    }

    // payment_frequency_code
	if( !isset($_POST['payment-frequency-code'] ) ) {
		$err_msgs[] = "A payment-frequency-code is required";
	} else {
		$rowdata['payment_frequency_code'] = $_POST['payment-frequency-code'];
		if (strlen($rowdata['payment_frequency_code']) == 0){
			$err_msgs[] = "A payment-frequency-code is required";
		} else if (strlen($rowdata['payment_frequency_code']) > 30 ){
			$err_msgs[] = "The payment-frequency-code exceeds 30 characters";
		}
	}

    // base_rent_amount
	if( !isset($_POST['base-rent-amount'] ) ) {
		$err_msgs[] = "A base-rent-amount is required";
	} else {
		$rowdata['base_rent_amount'] = $_POST['base-rent-amount'];
		if (strlen($rowdata['base_rent_amount']) == 0){
			$err_msgs[] = "A base-rent-amount is required";
		} else if (strlen( $rowdata['base_rent_amount']) > 11  ){
			$err_msgs[] = "The base-rent-amount is not valid";
		} else if ($rowdata['base_rent_amount'] < 0  ){
			$err_msgs[] = "The base-rent-amount is not valid";
		}
	}

    // parking_amount
	if( isset($_POST['parking-amount'] ) ) {
		$rowdata['parking_amount'] = $_POST['parking-amount'];
	} else {
		if (strlen( $rowdata['parking_amount']) > 11  ){
			$err_msgs[] = "The parking-amount is not valid";
		}else if ($rowdata['base_rent_amount'] < 0  ){
			$err_msgs[] = "The base-rent-amount is not valid";
		}
	}

    // other_amount
	if( isset($_POST['other-amount'] ) ) {
		$rowdata['other_amount'] = $_POST['other-amount'];
	} else {
		if (strlen( $rowdata['other_amount']) > 11  ){
			$err_msgs[] = "The other-amount is not valid";
		}else if ($rowdata['base_rent_amount'] < 0  ){
			$err_msgs[] = "The base-rent-amount is not valid";
		}
	}

    // payable_to
	if( isset($_POST['payable-to'] ) ) {
		$rowdata['payable_to'] = $_POST['payable-to'];
	} else {
		if (strlen( $rowdata['payable_to']) > 50 ){
			$err_msgs[] = "The payable-to exceeds 50 characters";
		}
	}

    // deposit_amount
	if( !isset($_POST['deposit-amount'] ) ) {
		$err_msgs[] = "A deposit-amount is required";
	} else {
		$rowdata['deposit_amount'] = $_POST['deposit-amount'];
		if (strlen($rowdata['deposit_amount']) == 0){
			$err_msgs[] = "A deposit-amount is required";
		} else if (strlen( $rowdata['deposit_amount']) > 11  ){
			$err_msgs[] = "The deposit-amount is not valid";
		}else if ($rowdata['base_rent_amount'] < 0  ){
			$err_msgs[] = "The base-rent-amount is not valid";
		}
	}

    // key_deposit
	if( isset($_POST['key-deposit'] ) ) {
		$rowdata['key_deposit'] = $_POST['key-deposit'];
	} else {
        if (strlen( $rowdata['key_deposit']) > 11  ){
			$err_msgs[] = "The key-deposit is not valid";
		}
        elseif ($rowdata['key_deposit'] < 0) {
            $err_msgs[] = "The key-deposit is not valid";
        }else if ($rowdata['base_rent_amount'] < 0  ){
			$err_msgs[] = "The base-rent-amount is not valid";
		}
	}

    // payment_type_code
	if( !isset($_POST['payment-type-code'] ) ) {
		$err_msgs[] = "A payment-type-code is required";
	} else {
		$rowdata['payment_type_code'] = $_POST['payment-type-code'];
		if (strlen($rowdata['payment_type_code']) == 0){
			$err_msgs[] = "A payment-type-code is required";
		} else if (strlen($rowdata['payment_type_code']) > 30 ){
			$err_msgs[] = "The payment-type-code exceeds 30 characters";
		}
	}

    // include_electricity
    $rowdata['include_electricity'] = (int)isset($_POST['include-electricity']);

    // include_heat
    $rowdata['include_heat'] = (int)isset($_POST['include-heat']);


    // include_water
    $rowdata['include_water'] = (int)isset($_POST['include-water']);

    // insurancy_policy_number
	if( isset($_POST['insurancy-policy-number'] ) ) {
		$rowdata['insurancy_policy_number'] = $_POST['insurancy-policy-number'];
	} else {
		if (strlen( $rowdata['insurancy_policy_number']) > 50 ){
			$err_msgs[] = "The insurancy-policy-number exceeds 50 characters";
		}
	}

    // status code
	if( !isset($_POST['status-code'] ) ) {
		$err_msgs[] = "A status is required";
	} else {
		$rowdata['status_code'] = $_POST['status-code'];
		if (strlen($rowdata['status_code']) == 0){
			$err_msgs[] = "A status is required";
		} else if (strlen($rowdata['status_code']) > 10 ){
			$err_msgs[] = "The status exceeds 10 characters";
		}
	}
        
    $_SESSION['rowdata'] = $rowdata;
	return $err_msgs;
}

function formDisplayLeases()
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
        getLeases();

        // Search Bar
        //getSearch($fvalue);

        // Get Standard CRUD buttons
        getCRUDButtons();
        ?>

</form>
</div>
<?php }

function formLease()
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
                    ?>Lease Details</legend>
            <!--check form-->

            <!-- Lease no.-->
            <div class="input-group">
                <label for="lease-id">Lease No.</label>
                <input type="text" size="10" maxlength="10" class="form-control" style="max-width: 100px" id="lease-id"
                    name="lease-id" aria-describedby="lease-id-help" placeholder=""
                    value="<?php echo $row['lease_id']; ?>" readonly>
                <small id="lease-help" class="form-text text-muted"></small>
            </div>

            <!--rental_property_id-->
            <div class="input-group">
                <label for="rental-property-id">Rental Property No.</label>
                <input type="text" size="10" maxlength="10" class="form-control" style="max-width: 80px"
                    id="rental-property-id" name="rental-property-id" aria-describedby="rental-property-id-help"
                    placeholder="" value="<?php echo $row['rental_property_id']; ?>"
                    required<?php echo ($_SESSION['PAGEMODE'] == 'VIEW') ? " readonly" : ""?>>
                <small id="rental-property-id-help" class="form-text text-muted"></small>

                <input type="text" class="form-control" id="listing-reference" name="listing-reference"
                    value="<?php echo $row['listing_reference']; ?>" readonly>
                <small id="listing-reference-help" class="form-text text-muted"></small>
            </div>

            <!--tenant_id-->
            <div class="input-group">
                <label for="tenant-id">Tenant No.</label>
                <input type="text" size="10" maxlength="10" class="form-control" style="max-width: 80px" id="tenant-id"
                    name="tenant-id" aria-describedby="tenant-id-help" placeholder=""
                    value="<?php echo $row['tenant_id']; ?>"
                    required<?php echo ($_SESSION['PAGEMODE'] == 'VIEW') ? " readonly" : ""?>>
                <small id="tenant-id-help" class="form-text text-muted"></small>

                <input type="text" class="form-control" id="tenant-name" name="tenant-name"
                    value="<?php echo $row['tenant_name']; ?>" readonly>
                <small id="tenant-name-help" class="form-text text-muted"></small>
            </div>

            <!--start_date-->
            <div class="input-group">
                <label for="start-date">Start Date</label>
                <input type="date" style="max-width: 200px;" class="form-control" id="start-date" name="start-date"
                    aria-describedby="start-date-help" value="<?php echo $row['start_date']; ?>"
                    required<?php echo ($_SESSION['PAGEMODE'] == 'VIEW') ? " readonly" : ""?>>
                <small id="start-date-help" class="form-text text-muted"></small>
            </div>

            <!--end_date-->
            <div class="input-group">
                <label for="end-date">End Date</label>
                <input type="date" style="max-width: 200px;" class="form-control" id="end-date" name="end-date"
                    aria-describedby="end-date-help" value="<?php echo $row['end_date']; ?>"
                    <?php echo ($_SESSION['PAGEMODE'] == 'VIEW') ? " readonly" : ""?>>
                <small id="end-date-help" class="form-text text-muted"></small>
            </div>

            <!--Payment day/Frequency-->
            <div class="input-group">
                <label for="province">Frequency/Day</label>
                <select class="selectpicker form-control" style="max-width: 33%;" id="payment-frequency-code"
                    name="payment-frequency-code" aria-describedby="payment-frequency-code-help"
                    placeholder="Enter frequency"
                    required<?php echo ($_SESSION['PAGEMODE'] == 'VIEW') ? " readonly" : ""?>>
                    <?php
                        getCodes('payment_frequency', $row['payment_frequency_code']);
                        ?>
                </select>
                <small id="payment-frequency-code-help" class="form-text text-muted"></small>

                <input type="text" style="max-width: 33%;" class="form-control" id="payment-day" name="payment-day"
                    aria-describedby="payment-day-help" placeholder="Enter payment day"
                    value="<?php echo $row['payment_day']; ?>"
                    required<?php echo ($_SESSION['PAGEMODE'] == 'VIEW') ? " readonly" : ""?>>
                <small id="payment-day-help" class="form-text text-muted"></small>
            </div>

            <!--base_rent_amount-->
            <div class="input-group">
                <label for="base-rent-amount">Base Rent</label>
                <input type="number" maxlength="10" class="form-control" id="base-rent-amount" name="base-rent-amount"
                    aria-describedby="base-rent-amount-help" placeholder="0.00"
                    value="<?php echo $row['base_rent_amount']; ?>"
                    required<?php echo ($_SESSION['PAGEMODE'] == 'VIEW') ? " readonly" : ""?>>
                <small id="base-rent-amount-help" class="form-text text-muted"></small>
            </div>

            <!--parking_amount-->
            <div class="input-group">
                <label for="parking-amount">Parking</label>
                <input type="number" maxlength="10" class="form-control" id="parking-amount" name="parking-amount"
                    aria-describedby="parking-amount-help" placeholder="0.00"
                    value="<?php echo $row['parking_amount']; ?>"
                    <?php echo ($_SESSION['PAGEMODE'] == 'VIEW') ? " readonly" : ""?>>
                <small id="parking-amount-help" class="form-text text-muted"></small>
            </div>

            <!--other_amount-->
            <div class="input-group">
                <label for="other-amount">Other</label>
                <input type="number" maxlength="10" class="form-control" id="other-amount" name="other-amount"
                    aria-describedby="other-amount-help" placeholder="0.00" value="<?php echo $row['other_amount']; ?>"
                    <?php echo ($_SESSION['PAGEMODE'] == 'VIEW') ? " readonly" : ""?>>
                <small id="other-amount-help" class="form-text text-muted"></small>
            </div>

            <!--payable_to-->
            <div class="input-group">
                <label for="payable-to">Payable To</label>
                <input type="text" size="30" maxlength="50" class="form-control" id="payable-to" name="payable-to"
                    aria-describedby="payable-to-help" placeholder="" value="<?php echo $row['payable_to']; ?>"
                    required<?php echo ($_SESSION['PAGEMODE'] == 'VIEW') ? " readonly" : ""?>>
                <small id="payable-to-help" class="form-text text-muted"></small>
            </div>

            <!--deposit_amount-->
            <div class="input-group">
                <label for="deposit-amount">Deposit</label>
                <input type="number" size="30" maxlength="10" class="form-control" id="deposit-amount"
                    name="deposit-amount" aria-describedby="deposit-amount-help" placeholder="0.00"
                    value="<?php echo $row['deposit_amount']; ?>"
                    required<?php echo ($_SESSION['PAGEMODE'] == 'VIEW') ? " readonly" : ""?>>
                <small id="deposit-amount-help" class="form-text text-muted"></small>
            </div>

            <!--key_deposit-->
            <div class="input-group">
                <label for="key-deposit">Key Deposit</label>
                <input type="number" size="30" maxlength="10" class="form-control" id="key-deposit" name="key-deposit"
                    aria-describedby="key-deposit-help" placeholder="0.00" value="<?php echo $row['key_deposit']; ?>"
                    <?php echo ($_SESSION['PAGEMODE'] == 'VIEW') ? " readonly" : ""?>>
                <small id="key-deposit-help" class="form-text text-muted"></small>
            </div>

            <!--payment_type_code-->
            <div class="input-group">
                <label for="payment-type-code">Payment Type</label>
                <select class="selectpicker form-control" style="max-width: 200px" id="payment-type-code"
                    name="payment-type-code" aria-describedby="payment-type-code-help"
                    placeholder="Enter payment Type code" value="<?php echo $row['payment_frequency_code']; ?>"
                    <?php echo ($_SESSION['PAGEMODE'] == 'VIEW') ? " readonly" : ""?>>
                    <?php
                        getCodes('payment_type', $row['payment_type_code']);
                        ?>
                </select>
                <small id="payment-type-code-help" class="form-text text-muted"></small>
            </div>

            <!--include_electricity-->
            <div class="input-group">
                <label for="include-electricity">Electricity</label>
                <input type="checkbox" class="form-check-input form-check-input-rental" id="include-electricity"
                    name="include-electricity" <?php echo ($row['include_electricity']) ? "checked" : ""; ?>
                    <?php echo ($_SESSION['PAGEMODE'] == 'VIEW') ? " readonly" : "" ?>>
            </div>

            <!--include_heat-->
            <div class="input-group">
                <label for="include-heat">Heat</label>
                <input type="checkbox" class="form-check-input form-check-input-rental" id="include-heat"
                    name="include-heat" <?php echo ($row['include_heat']) ? "checked" : ""; ?>
                    <?php echo ($_SESSION['PAGEMODE'] == 'VIEW') ? " readonly" : "" ?>>
            </div>

            <!--include_water-->
            <div class="input-group">
                <label for="include-water">Water</label>
                <input type="checkbox" class="form-check-input form-check-input-rental" id="include-water"
                    name="include-water" <?php echo ($row['include_water']) ? "checked" : ""; ?>
                    <?php echo ($_SESSION['PAGEMODE'] == 'VIEW') ? " readonly" : "" ?>>
            </div>

            <!--insurancy_policy_number-->
            <div class="input-group">
                <label for="insurancy-policy-number">Insurance Policy No.</label>
                <input type="text" size="30" maxlength="50" class="form-control" id="insurancy-policy-number"
                    name="insurancy-policy-number" aria-describedby="insurancy-policy-number-help"
                    placeholder="Enter Insurancy Policy Number" value="<?php echo $row['insurancy_policy_number']; ?>"
                    <?php echo ($_SESSION['PAGEMODE'] == 'VIEW') ? " readonly" : ""?>>
                <small id="insurancy-policy-number-help" class="form-text text-muted"></small>
            </div>

            <!--status  -->
            <div class="input-group">
                <label for="status-code">Status</label>
                <select class="selectpicker form-control" style="max-width: 100px" id="status-code" name="status-code"
                    aria-describedby="status-code-help" placeholder="Enter status"
                    required<?php echo ($_SESSION['PAGEMODE'] == 'VIEW') ? " readonly" : ""?>>
                    <?php
                        getCodes('lease_status', $row['status_code']);
                        ?>
                </select>
                <small id="status-code-help" class="form-text text-muted"></small>
            </div>
            
            <!-- 2021 Mar 19. Add Rental Document T.K -->
            <div class="input-group">
                <label for="documentfile">Document file</label>
                <?php echo ($_SESSION['PAGEMODE'] != 'ADD') ? "<div class='mb-3'><label class='form-label' style='width:377px'>Uploaded file: <a href='/lease_document_file/".$row['file']."'>".$row['file']."</a></label>":"" ?>
                <?php echo ($_SESSION['PAGEMODE'] == 'ADD') ? "<div class='mb-3'><label class='form-label' style='width:377px'>pdf, docx, txt extension is allowed.</label>":"" ?>                
                <input class="form-control" type="file" id="documentfile" name="document-file" <?php echo ($_SESSION['PAGEMODE'] == 'VIEW') ? " disabled" : ""?>>
                </div>
                
            </div>


            <table>
                <tr>
                    <?php 
                if ($_SESSION['PAGEMODE'] == 'EDIT' || $_SESSION['PAGEMODE'] == 'ADD') { ?>
                    <td><input type="submit" class="btn btn-success btn-crud" name="btn-save" value="Save"></td>
                    <?php            }
?>
                    <td>
                        <input type="submit" form="form-cancel"
                            class="btn <?php echo ($_SESSION['PAGEMODE'] == 'EDIT' || $_SESSION['PAGEMODE'] == 'ADD' ) ? 'btn-secondary' : 'btn-primary'; ?> btn-crud"
                            name="btn-cancel"
                            value="<?php echo ($_SESSION['PAGEMODE'] == 'EDIT' || $_SESSION['PAGEMODE'] == 'ADD' ) ? 'Cancel' : 'OK'; ?>">
                    </td>
                </tr>
            </table>
        </fieldset>
    </form>
    <!-- empty form for cancel button -->
    <form id="form-cancel" hidden>
        <form>
</div>
<?php
}
?>