<?php
session_start();
include_once("./check_session.php");

// session_unset();
$_SESSION['PAGE'] = "tenant_payments";
if (!isset($_SESSION['PAGEMODE'])) {
    $_SESSION['PAGEMODE'] = "LIST";
    $_SESSION['PAGENUM'] = 0;
}

?>
<!DOCTYPE html>
<html>

<head>
    <!-- 
        Title:       tenant_payments.php
        Application: RentalBuddy
        Purpose:     Handles tenant_payments
        Author:      G. Blandford,  Group 5, INFO-5139-01-21W
        Date:        April 4th, 2021 (April 4th, 2021)
    -->

    <title>RentalBuddy - Tenant Payments</title>

    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="RentalBuddy Tenant Payments">
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

    <script src="../js/script.js"></script>

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

        .container-crud {
            margin-left: 10px;
        }

        .btn-crud {
            min-width: 100px;
        }

        .btn-crud img {
            color: white;
        }
       
        #btn-edit {
            display: none;
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

        .form-check-input-payment {
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

        #fieldset-card {
            border: 1px solid lightgray;
            margin: 20px 2px;
            display: none;
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

<body onload="showHideCC();">
    <?php

    // navigation & search bars
    require_once("./navigationMenu.php");
    require_once("./search-bar.php");
    require_once("./crud-buttons.php");

    // data access layer
    require_once("../dal/codes_dal.php");
    require_once("../dal/tenants_dal.php");
    require_once("../dal/tenant_payments_dal.php");

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
    } else if (isset($_POST['btn-save']) && ($_POST['btn-save'] == "Pay")) { // Save clicked
        // Just let it through
    } else if (isset($_POST['btn-download']) && ($_POST['btn-download'] == "Download")) {
        downloadPayments();
    } else {
        $_SESSION['PAGENUM'] = 0;
        $_SESSION['PAGEMODE'] = "LIST";
    }

    // Get the Tenant id
    $_SESSION['tenant_id'] = $_SESSION['CURRENT_USER']['tenant_id'];

    // var_dump( $_SERVER['REQUEST_METHOD'] );
    // dump( $_SESSION );
    // var_dump( $_POST );
    // var_dump( $_GET );

    // $_ POSTing or $_GETting?
    if ($_SERVER['REQUEST_METHOD'] == 'GET' || $_SESSION['PAGEMODE'] == "LIST") {

        // Display Payments
        formDisplayTenantPayments();

    } else if ($_SESSION['PAGEMODE'] == "ADD" || $_SESSION['PAGEMODE'] == "EDIT" || $_SESSION['PAGEMODE'] == "VIEW") {

        switch ($_SESSION['PAGENUM']) {

            case 0: // Show Form
                $_SESSION['PAGENUM'] = 1; // Set to validate

                // ADD record
                if ($_SESSION['PAGEMODE'] == "ADD") {

                    // Empty record
                    $_SESSION['tenant_payment_id'] = 0;
                    $_SESSION['rowdata'] = array();

                    // Add DEFAULT values
                    // Description
                    // Payment DUE and PAY Amount from Lease
                    $defaults = getPaymentDefaults($_SESSION['tenant_id']);

                    $_SESSION['rowdata']['tenant_id'] = $_SESSION['tenant_id'];
                    $_SESSION['rowdata']['description'] = $defaults['description'];
                    $_SESSION['rowdata']['payment_due'] = $defaults['payment_due'];
                    $_SESSION['rowdata']['payment_amount'] = $defaults['payment_due'];

                    $_SESSION['rowdata']['card_holder'] = $defaults['card_holder'];

                    // Show Tenant Payment page
                    formTenantPayment();

                    // EDIT RECORD
                } else if (isset($_POST['selected']) && strlen($_POST['selected'][0] > 0)) {

                    // Get Selected Tenant Payment 
                    $_SESSION['tenant_payment_id'] = $_POST['selected'][0];
                    
                    // dump( $_SESSION );
                    // Get Tenant Payment data
                    getTenantPayment();

                    // Show Payment
                    formTenantPayment();

                    // LIST RECORDS
                } else {

                    formTenantPayment();
                }
                break;

            case 1: // Save
                if ((isset($_POST['btn-save'])) && ($_POST['btn-save'] == "Pay")) {

                    // Validate
                    $err_msgs = validateTenantPayment();

                    if (count($err_msgs) > 0) {

                        displayErrors($err_msgs);
                        formTenantPayment();
                    } else {

                        // Save to database                     
                        saveTenantPayment();
                        $_SESSION['PAGENUM'] = 0;

                        // Clear row
                        unset($_SESSION['rowdata']);

                        $_SESSION['PAGEMODE'] = "LIST";
                        formDisplayTenantPayments();
                    }
                } else if ((isset($_POST['btn-cancel'])) && ($_POST['btn-cancel'] == "Cancel" || $_POST['btn-cancel'] == "OK")) {

                    $_SESSION['PAGEMODE'] = "LIST";
                    $_SESSION['PAGENUM'] = 0;

                    // Clear current data
                    unset($_SESSION['tenant_property_id']);
                    unset($_SESSION['rowdata']);

                    formDisplayTenantPayments();
                }
                
                break;
            default:
        }
    } else if ($_SESSION['PAGEMODE'] == "LIST") {

        formDisplayTenantPayments();
    }

    // We can do anything here AFTER the form has loaded
    ?>

    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script src="../vendor/twbs/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
    <link href="../node_modules/bootstrap-icons/font//bootstrap-icons.css" rel="stylesheet">
</body>

</html>

<?php

function validateTenantPayment()
{
    $rowdata = $_SESSION['rowdata'];

    $rowdata['tenant_payment_id'] = $_POST['tenant-payment-id'];

    $err_msgs = [];

    // payment type code
    if (!isset($_POST['payment-type-code'])) {
        $err_msgs[] = "A payment type is required";
    } else {
        $rowdata['payment_type_code'] = $_POST['payment-type-code'];
        if (strlen($rowdata['payment_type_code']) == 0) {
            $err_msgs[] = "A payment type is required";
        } else if (strlen($rowdata['payment_type_code']) > 20) {
            $err_msgs[] = "The payment type exceeds 20 characters";
        }
    }

    // description
    if (!isset($_POST['description'])) {
        $err_msgs[] = "A description is required";
    } else {
        $rowdata['description'] = $_POST['description'];
        if (strlen($rowdata['description']) == 0) {
            $err_msgs[] = "An description is required";
        } else if (strlen($rowdata['description']) > 50) {
            $err_msgs[] = "The description exceeds 50 characters";
        }
    }
    
    // if payment type is debit / credit card
    if ($rowdata['payment_type_code'] == 'debitcredit') {

        // card holder name
        if (!isset($_POST['card-holder'])) {
            $err_msgs[] = "The card holder name is required";
        } else {
            $rowdata['card_holder'] = $_POST['card-holder'];
            if (strlen($rowdata['card_holder']) == 0) {
                $err_msgs[] = "The card holder name is required";
            } else if (strlen($rowdata['card_holder']) > 50) {
                $err_msgs[] = "The card holder name exceeds 50 characters";
            }
        }

        // card number
        if (!isset($_POST['card-number'])) {
            $err_msgs[] = "The card number is required";
        } else {
            $rowdata['card_number'] = unformatCC($_POST['card-number']);
            if (strlen($rowdata['card_number']) == 0) {
                $err_msgs[] = "The card number is required";
            } else if (!preg_match('/^[0-9]{16}$/', trim($rowdata['card_number']))) {
                $err_msgs[] = "The card number is not valid";
            }
        }

        // card expiry - we'll do some special handling here
        if (!isset($_POST['card-expiry-month']) || !isset($_POST['card-expiry-year'])) {
            $err_msgs[] = "The card expiry date is required";
        } else {

     
            $startOfMonth = strtotime('01 ' . $_POST['card-expiry-month'] . ' ' . $_POST['card-expiry-year']);
            $expiryDate = date('Y-m-t', $startOfMonth);

// dump($_POST['card-expiry-month']);               
// dump($startOfMonth);
// dump($expiryDate);

            $endOfMonth = strtotime($expiryDate);
            $currentDate = strtotime(date("Y-m-d"));

            if ($endOfMonth < $currentDate) {
                $err_msgs[] = "This card has expired"; 
            } else {
                $expiryDate = New DateTime($expiryDate);
                $rowdata['card_expiry'] = $expiryDate->format("Y") . "-" . $expiryDate->format("m");
            }
        }

        // CVV
        if (!isset($_POST['card-cvv'])) {
            $err_msgs[] = "The card CVV number is required";
        } else {
            $rowdata['card_CVV'] = trim($_POST['card-cvv']);
            if (strlen($rowdata['card_CVV']) == 0) {
                $err_msgs[] = "The card CVV number is required";
            } else if (!preg_match('/^[0-9]{3}$/', trim($rowdata['card_CVV']))) {
                $err_msgs[] = "The card CVV is not valid";
            }
        }
    } else {
        // clear card fields
        $rowdata['card_holder'] = '';
        $rowdata['card_number'] = '';
        $rowdata['card_expiry'] = '';
        $rowdata['card_CVV'] = '';
    }

    // payment_due
    $rowdata['payment_due'] = $_POST['payment-due'];

    // discount
	if ( !isset($_POST['discount'] ) ) {
		$rowdata['discount'] = 0;
	} else {
		$rowdata['discount'] = $_POST['discount'] + 0;
	}

	if( !isset($_POST['payment-amount'] ) ) {
		$err_msgs[] = "A payment amount is required";
	} else {
		$rowdata['payment_amount'] = $_POST['payment-amount'];
		if (strlen($rowdata['payment_amount']) == 0){
			$err_msgs[] = "A payment amount is required";
		} else if ( $rowdata['payment_amount'] > $rowdata['payment_due'] ) {
			$err_msgs[] = "The payment amount must be <= ".$rowdata['payment_due'];
		} else if ($rowdata['payment_amount'] <= 0  ) {
			$err_msgs[] = "The payment amount must be greater than 0";
		}
	}

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

    $_SESSION['rowdata'] = $rowdata;
    return $err_msgs;
}

function formDisplayTenantPayments() {

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
        getTenantPayments($_SESSION['tenant_id'] );

        // Search Bar
        // getSearch($fvalue);

        // Get Standard CRUD buttons
        getCRUDButtons();
        ?>
        <input value="Download" type="submit" name="btn-download" class="btn btn-primary" style="position:relative; left:330px; top:-39px; background-color:#3b3a3a; color:white; border-color:#3b3a3a;"></input>
    </form>
    </div>
<?php 
}

//When download is clicked and posted
function downloadPayments() {

    $content = "";
    $fieldNames = ['Payment ID', 'Payment Type', 'Description', 'Payment Date/Time', 'Payment Due', 'Discount Code', 'Discount', 'Payment Amount',
    'Card Holder', 'Status'];

    foreach($fieldNames as $names) {
        $content .= $names . '","';
    }
    $content .= '"\r\n"';

    $i = 0;
    foreach($_SESSION['paymentData'] as $payData) {
        array_splice($payData, 9, 3);
        foreach($payData as $field) {
            $content .= $field . '","';
            $i++;
        }
        $content .= '"\r\n"';
        $i = 0;
    }
    $content = strip_tags($content);
    //Uses Javascript to write and download payment history as csv | does not save to server only local
    echo "<script>
    var element = document.createElement('a');
    element.setAttribute('href', 'data:text/csv;charset=utf-8,' + encodeURIComponent('$content'));
    element.setAttribute('download', 'PaymentHistory.csv');

    element.style.display = 'none';
    document.body.appendChild(element);

    element.click();

    document.body.removeChild(element);

  </script>";
}

function formTenantPayment($showmodal = 0) {
    // Get the data
    $row = $_SESSION['rowdata'];

// dump($$row);

?>
    <div class="container-fluid">

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
                    ?>Tenant Payment</legend>

                <!-- tenant_id & name-->
                <div class="input-group">
                    <label for="tenant-id">Tenant</label>
                    <input type="text" size="10" maxlength="10" class="form-control" style="max-width: 100px" id="tenant-id" name="tenant-id" aria-describedby="tenant-id-help" placeholder="" value="<?php echo $row['tenant_id']; ?>" readonly>
                    <small id="tenant-id-help" class="form-text text-muted"></small>

                    <input type="text" size="30" maxlength="50" 
                            class="form-control" 
                            id="tenant-name" name="tenant-name" 
                            value="<?php 
                                        echo getTenantName($row['tenant_id']); ?>"
                            readonly
                            required
                            immediate="false">
                </div>

                <!-- description -->
                <div class="input-group">
                    <label for="description">Description</label>
                    <input type="text" size="30" maxlength="30" style="max-width: 50%;" class="form-control" id="description" name="description" aria-describedby="description-help" placeholder="Enter description" required value="<?php echo $row['description']; ?>"<?php echo ($_SESSION['PAGEMODE'] == 'VIEW') ? " readonly" : "" ?>>
                    <small id="description-help" class="form-text text-muted"></small>
                </div>

                <!-- payment type -->
                <div class="input-group">
                    <label for="payment-type-code">Payment Type</label>
                    <select class="selectpicker form-control" style="max-width: 220px;" id="payment-type-code" name="payment-type-code" aria-describedby="payment-type-code-help" placeholder="Enter payment type" required<?php echo ($_SESSION['PAGEMODE'] == 'VIEW') ? " disabled" : "" ?> onclick="showHideCC()">
                        <?php
                        getCodes('payment_type', $row['payment_type_code']);
                        ?>
                    </select>
                    <small id="payment-type-code-help" class="form-text text-muted"></small>
                </div>

                <!-- Contact prefs -->
                <label for="fieldset-card">Card Details</label>

                <fieldset id="fieldset-card" class="bg-light">

                    <!-- cardholder name -->
                    <div class="input-group">
                        <label for="card-holder">Name</label>
                        <input type="text" size="30" maxlength="50" class="form-control" id="card-holder" name="card-holder" aria-describedby="card-holder-help" placeholder="Enter card holder name" value="<?php echo $row['card_holder']; ?>" <?php echo ($_SESSION['PAGEMODE'] == 'VIEW') ? " readonly" : "" ?>>
                        <small id="card-holder-help" class="form-text text-muted"></small>
                    </div>

                    <!-- card number -->
                    <div class="input-group">
                        <label for="card-number">Number</label>
                        <input type="text" size="30" maxlength="50" class="form-control" id="card-number" name="card-number" aria-describedby="card-number-help" placeholder="Enter card number" value="<?php echo formatCC($row['card_number']); ?>" <?php echo ($_SESSION['PAGEMODE'] == 'VIEW') ? " readonly" : "" ?> onblur="formatCC();">
                        <small id="card-number-help" class="form-text text-muted"></small>
                    </div>

                    <?php getCreditCardExpiryFields($row); ?>
                </fieldset>                    

                <!-- payment due -->
                <div class="input-group">
                    <label for="payment-due">Payment Due</label>
                    <input type="text" size="15" maxlength="15" style="max-width: 20%;" class="form-control" id="payment-due" name="payment-due" aria-describedby="payment-due-help" placeholder="Enter payment due" value="<?php echo $row['payment_due']; ?>" required readonly>
                    <small id="payment-due-help" class="form-text text-muted"></small>
                </div>

                <!-- discount code & value -->
                <div class="input-group">
                    <label for="discount-coupon-code">Discount</label>
                    <select class="selectpicker form-control" style="max-width: 220px;" id=discount-coupon-code" name="discount-coupon-code" aria-describedby="discount-coupon-code-help" <?php echo ($_SESSION['PAGEMODE'] == 'VIEW') ? " readonly" : "" ?>>
                        <option disabled selected></option>
                        <?php
                        getCodes('discount_code', $row['discount_coupon_code']);
                        ?>
                    </select>
                    <small id="discount-coupon-code-help" class="form-text text-muted"></small>

                    <input type="text" style="max-width: 100px;" class="form-control" id="discount" name="discount" aria-describedby="discount-help" placeholder="Discount" value="<?php echo $row['discount']; ?>" readonly>
                    <small id="discount-help" class="form-text text-muted"></small>
                </div>

                <!-- payment amount -->
                <div class="input-group">
                    <label for="payment-amount">Payment Amount</label>
                    <input type="text" size="15" maxlength="15" style="max-width: 20%;" class="form-control" id="payment-amount" name="payment-amount" aria-describedby="payment-amount-help" placeholder="Enter payment amount" value="<?php echo $row['payment_amount']; ?>" required <?php echo ($_SESSION['PAGEMODE'] == 'VIEW') ? " readonly" : "" ?>>
                    <small id="payment-amount-help" class="form-text text-muted"></small>
                </div>

                <!--status  -->
                <div class="input-group">
                    <label for="status-code">Status</label>
                    <select class="selectpicker form-control" style="max-width: 100px" id="status-code" name="status-code" aria-describedby="status-code-help" placeholder="Enter status" required<?php echo ($_SESSION['PAGEMODE'] == 'VIEW') ? " readonly" : "" ?>>
                        <?php
                        getCodes('payment_status', $row['status_code']);
                        ?>
                    </select>
                    <small id="status-code-help" class="form-text text-muted"></small>
                </div>

                <table>
                    <tr>
                        <?php
                        if ($_SESSION['PAGEMODE'] == 'EDIT' || $_SESSION['PAGEMODE'] == 'ADD') { ?>
                            <td><input type="submit" class="btn btn-success btn-crud" name="btn-save" value="Pay"></td>
                        <?php            }
                        ?>
                        <td><input type="submit" form="form-cancel" class="btn <?php echo ($_SESSION['PAGEMODE'] == 'EDIT' || $_SESSION['PAGEMODE'] == 'ADD') ? 'btn-secondary' : 'btn-primary'; ?> btn-crud" name="btn-cancel" value="<?php echo ($_SESSION['PAGEMODE'] == 'EDIT' || $_SESSION['PAGEMODE'] == 'ADD') ? 'Cancel' : 'OK'; ?>"></td>
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

// Get a drop down for credit card
function getCreditCardExpiryFields($row) {

    $months = [];
    $years = [];

    $dMonth = New DateTime();
    $dYear = New DateTime();

    for ($i = 0; $i < 12; $i++) {
        array_push($months, $dMonth->format('F'));
        $dMonth->add(new \DateInterval('P1M'));

        array_push($years, $dYear->format('Y'));
        $dYear->add(new \DateInterval('P1Y'));
    }
?>
    <!-- expiry & cvv -->
    <div class="input-group">
        <label for="card-expiry">Expiry/CVV</label>

        <select class="selectpicker form-control" style="max-width: 800px;" id="card-expiry-month" name="card-expiry-month" aria-describedby="card-expiry-month-help" <?php echo ($_SESSION['PAGEMODE'] == 'VIEW') ? " readonly" : "" ?>>
            <option disabled selected>Month</option>
            <?php
            foreach($months as $m) {
                echo '<option>' . $m . '</option>';
            }
            ?>
        </select>

        <select class="selectpicker form-control" style="max-width: 800px;" id="card-expiry-year" name="card-expiry-year" aria-describedby="card-expiry-year-help" <?php echo ($_SESSION['PAGEMODE'] == 'VIEW') ? " readonly" : "" ?>>
            <option disabled selected>Year</option>
            <?php
            foreach($years as $y) {
                echo '<option>' . $y . '</option>';
            }
            ?>
        </select>

        <small id="card-expiry-year-help" class="form-text text-muted"></small>

        <input type="text" size="15" maxlength="15" style="max-width: 50%;" class="form-control" id="card-cvv" name="card-cvv" aria-describedby="card-cvv-help" placeholder="Enter CVV" value="<?php echo $row['card_CVV']; ?>"<?php echo ($_SESSION['PAGEMODE'] == 'VIEW') ? " readonly" : "" ?>>
        <small id="longitude-help" class="form-text text-muted"></small>
    </div>
<?php
}
