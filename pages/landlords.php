<?php
session_start();

$_SESSION['PAGE'] = "landlords";


?>
<!DOCTYPE html>
<html>

<head>
    <!--
            Name:    index.php
            By:      Graham Blandford
            Date:    2020-10-29
            Purpose: INFO-3106 Music Library - Landlords
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
            margin-top: 30px;
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
            width: 120px;
            justify-content: left !important;
        }

        .label-checkbox {
            width: 80px !important;
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
    if (isset($_POST['btn-add']) && ($_POST['ct_b_add'] == "Add")) {
        $_SESSION['PAGEMODE'] = "ADD";
        $_SESSION['PAGENUM'] = 0;
    } else if (isset($_POST['ct_b_edit']) && ($_POST['ct_b_edit'] == "Edit")) {
        $_SESSION['PAGEMODE'] = "EDIT";
    } else if (isset($_POST['ct_b_delete']) && ($_POST['ct_b_delete'] == "Delete")) {
        $_SESSION['mode'] = "DELETE";
    } else if (isset($_POST['btn-view']) && ($_POST['btn-view'] == "View")) {
        $_SESSION['PAGEMODE'] = "VIEW";
        $_SESSION['PAGENUM'] = 0;
    } else if (isset($_POST['btn-cancel']) && ($_POST['btn-cancel'] == "Cancel")) {
        if ($_SESSION['PAGEMODE'] == "ADD") {
            $_SESSION['PAGENUM'] = 0;
        }
        $_SESSION['PAGEMODE'] = "LIST";
    } else {
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
    } else if ($_SESSION['PAGEMODE'] == "VIEW") {
        switch ($_SESSION['PAGENUM']) {
            case 0: // Show Form
                $_SESSION['PAGENUM'] = 1;

                if (isset($_POST['selected']) && $_POST['selected'][0] > 0) {
                    $landlord_id = $_POST['selected'][0];
                    getLandlord($landlord_id);
                    formLandlord();
                }
                break;
            case 1: // Validate 
                //$err_msgs = validateLandlord();
                if (count($err_msgs) > 0) {
                    //displayErrors($err_msgs);
                    formLandlord();
                } else {
                    // postLandlordToSession();
                    $_SESSION['PAGENUM'] = 2;
                    //formContactName();
                }
                break;
            case 2: // Save
                if ((isset($_POST['btn-save']))
                    && ($_POST['btn-save'] == "SAVE")
                ) {
                    //saveLandlord();
                    $_SESSION['PAGENUM'] = 0;
                    //clearAddLandlordFromSession();
                    $_SESSION['PAGEMODE'] = "LIST";
                    formDisplayLandlords();
                } else if ((isset($_POST['btn-cancel']))
                    && ($_POST['btn-cancel'] == "CANCEL")
                ) {
                    $_SESSION['PAGENUM'] = 0;
                    formLandlord();
                }
                break;
            default:
        }
        // } else if ( $_SESSION['PAGEMODE'] == "ADD") { 
        //     switch ($_SESSION['PAGENUM']) {
        //         case 0:
        //             echo "<h1> VIEW LANDLORD </h1>\n";
        //             $_SESSION['PAGENUM'] = 1;
        //             formLandlord();
        //             break;
        //         case 1:
        //             echo "<h1> Add New LANDLORD </h1>\n";
        //             //$err_msgs = validateLandlord();
        //             if (count($err_msgs) > 0) {
        //                 //displayErrors($err_msgs);
        //                 formLandlord();
        //             } else {
        //                 // postLandlordToSession();
        //                 $_SESSION['PAGENUM'] = 2;
        //                 //formContactName();
        //             }
        //             break;
        // 		case 2:
        // 			if ((isset($_POST['ct_b_next']))
        // 					&& ($_POST['ct_b_next'] == "Save")){
        // 				//saveLandlord();
        // 				$_SESSION['PAGENUM'] = 0;
        // 				//clearAddLandlordFromSession();
        // 				$_SESSION['PAGEMODE'] = "DISPLAY";
        // 				formDisplayLandlords();
        // 			// } else if ((isset($_POST['ct_b_back']))
        // 			// 			&& ($_POST['ct_b_back'] == "Back")){
        // 			// 	echo "<h1> Add New Contact </h1>\n";
        // 			// 	$_SESSION['add_part'] = 7;
        // 			// 	formContactNote();
        // 			}
        // 			break;
        // 		default:
        // 	}
        // } else if ( $_SESSION['mode'] == "EDIT") { 
        // } else if ( $_SESSION['mode'] == "DELETE") { 
    } else if ($_SESSION['PAGEMODE'] == "LIST") {
        formDisplayLandlords();
    }

    // formDisplayLandlords(); 
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
function formDisplayLandlords()
{
    $fvalue = "";
    if (isset($_POST['btn-search']) && isset($_POST['searchtext'])) {
        $_SESSION['searchtext'] = trim($_POST['searchtext']);
        $fvalue = $_SESSION['searchtext'];
    } else if (isset($_POST['btn-search-clear'])) {
        $_SESSION['searchtext'] = "";
        $fvalue = $_SESSION['searchtext'];
    } else if (isset($_SESSION['searchtext'])) {
        $fvalue = $_SESSION['searchtext'];
    }
?>
    <form method="POST">
        <?php
        // Search Bar
        getSearch($fvalue);

        // Get Data
        getLandlords();

        // Get Standard CRUD buttons
        getCRUDButtons();
        ?>
    </form>
    </div>
<?php }

function formLandlord()
{
    $row = $_SESSION['landlord_data'];

?>
    <div class="container-fluid" >

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
                    <input type="text" size="10" maxlength="10" class="form-control" id="landlord-id" name="landlord-id" aria-describedby="landlord-id-help" placeholder="" value="<?php echo $row['landlord_id']; ?> " readonly>
                    <small id="landlord-help" class="form-text text-muted"></small>
                </div>

                <!-- Legal name -->
                <div class="input-group">
                    <label for="legal-name">Legal Name</label>
                    <input type="text" size="30" maxlength="50" class="form-control" id="legal-name" name="legal-name" aria-describedby="legal-name-help" placeholder="Enter legal name" value="<?php echo $row['legal_name']; ?> ">
                    <small id="legal-name-help" class="form-text text-muted"></small>
                </div>

                <!--Salutation -->
                <div class="input-group">
                    <label for="salutation">Salutation</label>
                    <select class="selectpicker form-control" style="max-width: 100px" id="salutation" name="salutation" aria-describedby="salutation-help" placeholder="Enter salutation" value="<?php echo $row['salutation_code']; ?> ">
                        <?php
                        getCodes('salutation', $row['salutation_code']);
                        ?>
                    </select>
                    <small id="salutation-help" class="form-text text-muted"></small>
                </div>

                <!-- first name -->
                <div class="input-group">
                    <label for="first-name">First Name</label>
                    <input type="text" size="30" maxlength="50" class="form-control" id="first-name" name="first-name" aria-describedby="first-name-help" placeholder="Enter first name" value="<?php echo $row['first_name']; ?> ">
                    <small id="first-name-help" class="form-text text-muted"></small>
                </div>

                <!-- last name -->
                <div class="input-group">
                    <label for="last-name">Last Name</label>
                    <input type="text" size="30" maxlength="50" class="form-control" id="last-name" name="last-name" aria-describedby="last-name-help" placeholder="Enter last name" value="<?php echo $row['last_name']; ?> ">
                    <small id="last-name-help" class="form-text text-muted"></small>
                </div>

                <!-- address_1 -->
                <div class="input-group">
                    <label for="address-1">Address 1</label>
                    <input type="text" size="30" maxlength="50" class="form-control" id="address-1" name="address-1" aria-describedby="address-1-help" placeholder="Enter address" value="<?php echo $row['address_1']; ?> ">
                    <small id="address-1-help" class="form-text text-muted"></small>
                </div>

                <!-- address_2 -->
                <div class="input-group">
                    <label for="address-2">Address 2</label>
                    <input type="text" size="30" maxlength="50" class="form-control" id="address-2" name="address-2" aria-describedby="address-2-help" placeholder="Enter address, e.g. unit no." value="<?php echo $row['address_2']; ?> ">
                    <small id="address-2-help" class="form-text text-muted"></small>
                </div>

                <!-- city -->
                <div class="input-group">
                    <label for="city">City</label>
                    <input type="text" size="30" maxlength="50" class="form-control" id="city" name="city" aria-describedby="city-help" placeholder="Enter city" value="<?php echo $row['city']; ?> ">
                    <small id="city-help" class="form-text text-muted"></small>
                </div>

                <!-- province -->
                <div class="input-group">
                    <label for="province">Province</label>
                    <select class="selectpicker form-control" id="province" name="province" aria-describedby="province-help" placeholder="Enter province">
                        <?php
                        getCodes('province', $row['province_code']);
                        ?>
                    </select>
                    <small id="province-help" class="form-text text-muted"></small>
                </div>

                <!-- postal code -->
                <div class="input-group">
                    <label for="postal-code">Postal Code</label>
                    <input type="text" size="10" maxlength="10" style="max-width: 100px;" class="form-control" id="postal-code" name="postal-code" aria-describedby="postal-help" placeholder="Enter postal code" value="<?php echo $row['postal_code']; ?> ">
                    <small id="postal-code-help" class="form-text text-muted"></small>
                </div>

                <!-- phone -->
                <div class="input-group">
                    <label for="phone">Phone</label>
                    <input type="tel" size="20" maxlength="20" style="max-width: 200px;" class="form-control" id="phone" name="phone" aria-describedby="phone-help" placeholder="Enter main phone number" value="<?php echo formatPhone($row['phone']); ?> ">
                    <small id="phone-code-help" class="form-text text-muted"></small>
                </div>

                <!-- fax -->
                <div class="input-group">
                    <label for="fax">Fax</label>
                    <input type="tel" size="20" maxlength="20" style="max-width: 200px;" class="form-control" id="fax" name="fax" aria-describedby="fax-help" placeholder="Enter fax number" value="<?php echo formatPhone($row['fax']); ?> ">
                    <small id="fax-code-help" class="form-text text-muted"></small>
                </div>

                <!-- sms -->
                <div class="input-group">
                    <label for="sms">SMS</label>
                    <input type="tel" size="20" maxlength="20" style="max-width: 200px;" class="form-control" id="sms" name="sms" aria-describedby="sms-help" placeholder="Enter number for SMS" value="<?php echo formatPhone($row['sms']); ?> ">
                    <small id="sms-help" class="form-text text-muted"></small>
                </div>

                <!-- email -->
                <div class="input-group">
                    <label for="email">Email</label>
                    <input type="text" size="30" maxlength="50" class="form-control" id="email" name="email" aria-describedby="email-help" placeholder="Enter email address" value="<?php echo $row['email']; ?> ">
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
            </fieldset>
        </form>
    </div>
<?php
}
?>