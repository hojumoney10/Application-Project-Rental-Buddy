<?php
session_start();
include_once("./check_session.php");

// session_unset();
$_SESSION['PAGE'] = "tenant_profile";

if (!isset($_SESSION['PAGEMODE'])){
    $_SESSION['PAGEMODE'] = "EDIT";
    $_SESSION['PAGENUM'] = 0;
}

?>
<!DOCTYPE html>
<html>

<head>
    <!-- 
        Title:       tenant_profile.php
        Application: RentalBuddy
        Purpose:     Handles update of a tenant profile and preferences
        Author:      G. Blandford,  Group 5, INFO-5139-01-21W
        Date:        March 13th, 2021 (March 14th, 2021)

    -->

    <title>RentalBuddy - Tenant Profile</title>

    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="RentalBuddy Tenant Profile">
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

    <!-- Custom scripts -->
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

        .form-check-input-tenant {
            margin: 10px 0 !important;
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

        #fieldset-prefs {
            border: 1px solid lightgray;
            margin: 20px 2px;
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

    // data access layer
    require_once("../dal/codes_dal.php");
    require_once("../dal/tenants_dal.php");

    // var_dump( $_SERVER['REQUEST_METHOD'] );
    // var_dump( $_SESSION );
    // var_dump( $_POST );
    // var_dump( $_GET );

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {

        // SAVE
        if  ( ( isset($_POST['btn-save'] ) ) && ( $_POST['btn-save'] == "Save" ) )  {

            // Validate
            $err_msgs = validateTenant();

            if (count($err_msgs) > 0) {

                displayErrors($err_msgs);
                formTenant();

            } else {
                // Save to database                     
                saveTenant();

                // Redisplay tenant
                formTenant();

                // Show message
                ?>
                    <script>
                        showMessages("Changes have been saved.");
                    </script>
                <?php

            }
        } 
    } 

    else {

        $_SESSION['PAGENUM'] = 1; // Set to validate

        // EDIT record
    
        // Get Selected tenant 
        $_SESSION['tenant_id'] = $_SESSION['CURRENT_USER']['tenant_id'];

        // Get tenant data
        getTenant();

        // Show tenant
        formTenant();
    }
    ?>

    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script src="../vendor/twbs/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
    <link href="../node_modules/bootstrap-icons/font//bootstrap-icons.css" rel="stylesheet">
</body>

</html>

<?php

function validateTenant() {

    $rowdata = $_SESSION['rowdata'];

    $rowdata['tenant_id'] = $_POST['tenant-id'];
    $rowdata['salutation_code'] = $_POST['salutation'];

    $err_msgs = [];

    // phone
	if( !isset($_POST['phone'] ) ) {
		$err_msgs[] = "A phone number is required";
	} else {
		$rowdata['phone'] = unformatPhone($_POST['phone']);
		if (strlen($rowdata['phone']) == 0){
			$err_msgs[] = "A phone number is required";
		} else if (strlen( $rowdata['phone']) > 20 ){
			$err_msgs[] = "The phone number exceeds 20 characters";
		}
	}

    // fax
	if( isset($_POST['fax'] ) ) {
		$rowdata['fax'] = unformatPhone($_POST['fax']);
		if (strlen( $rowdata['fax']) > 20 ){
			$err_msgs[] = "The fax number exceeds 20 characters";
		}
	}    

    // email
	if( !isset($_POST['email'] ) ) {
		$err_msgs[] = "An email address is required";
	} else {
		$rowdata['email'] = $_POST['email'];
		if (strlen($rowdata['email']) == 0){
			$err_msgs[] = "An email address is required";
		} else if (strlen( $rowdata['email']) > 100 ){
			$err_msgs[] = "The email address exceeds 100 characters";
		}
	}

    // contact phone
    $rowdata['contact_phone'] = (int)isset($_POST['contact-phone']);

    // contact sms
    $rowdata['contact_sms'] = (int)isset($_POST['contact-sms']);

    // contact email
    $rowdata['contact_email'] = (int)isset($_POST['contact-email']);
        
    $_SESSION['rowdata'] = $rowdata;
	return $err_msgs;
}


function formTenant()
{
    // Get the data
    $row = $_SESSION['rowdata'];

?>
    <div class="container-fluid">

        <form class="form form-inline" method="POST" style="padding-right: 30px;">
            <fieldset class="bg-light">
                <legend class="text-light bg-dark">Tenant Profile</legend>

                <!-- Tenant no.-->
                <div class="input-group">
                    <label for="tenant-id">Tenant No.</label>
                    <input type="text" size="10" maxlength="10" class="form-control" style="max-width: 100px" id="tenant-id" name="tenant-id" aria-describedby="tenant-id-help" placeholder="" value="<?php echo $row['tenant_id']; ?>" readonly>
                    <small id="tenant-help" class="form-text text-muted"></small>
                </div>

                <!-- full name -->
                <div class="input-group">
                    <label for="full-name">Full Name</label>
                    <input type="text" size="30" maxlength="50" class="form-control" id="full-name" name="full-name" aria-describedby="full-name-help" placeholder="Enter full name" value="<?php echo $row['full_name']; ?>" readonly>
                    <small id="full-name-help" class="form-text text-muted"></small>
                </div>

                <!-- phone & fax -->
                <div class="input-group">
                    <label for="phone">Phone/Fax</label>
                    <input type="tel" size="15" maxlength="15" style="max-width: 50%;" class="form-control" id="phone" name="phone" aria-describedby="phone-help" placeholder="Enter main phone number" value="<?php echo formatPhone($row['phone']); ?>" required<?php echo ($_SESSION['PAGEMODE'] == 'VIEW') ? " readonly" : ""?>>
                    <small id="phone-code-help" class="form-text text-muted"></small>

                    <input type="tel" size="15" maxlength="15" style="max-width: 50%;" class="form-control" id="fax" name="fax" aria-describedby="fax-help" placeholder="Enter fax number" value="<?php echo formatPhone($row['fax']); ?>"<?php echo ($_SESSION['PAGEMODE'] == 'VIEW') ? " readonly" : ""?>>
                    <small id="fax-code-help" class="form-text text-muted"></small>
                </div>

                <!-- email -->
                <div class="input-group">
                    <label for="email">Email</label>
                    <input type="email" size="30" maxlength="50" class="form-control" id="email" name="email" aria-describedby="email-help" placeholder="Enter email address" value="<?php echo $row['email']; ?>" required<?php echo ($_SESSION['PAGEMODE'] == 'VIEW') ? " readonly" : ""?>>
                    <small id="email-help" class="form-text text-muted"></small>
                </div>

                <!-- Contact prefs -->
                <label for="fieldset-pref">Contact Preferences</label>

                <fieldset id="fieldset-prefs" class="bg-light">

                    <!-- contact phone  -->
                    <div class="input-group">
                        <label for="contact-phone">Phone</label>
                        <input type="checkbox" class="form-check-input form-check-input-tenant" id="contact-phone" name="contact-phone" <?php echo ($row['contact_phone']) ? "checked" : ""; ?> <?php echo ($_SESSION['PAGEMODE'] == 'VIEW') ? " readonly" : "" ?>>
                    </div>

                    <!-- contact sms  -->
                    <div class="input-group">
                        <label for="contact-sms">SMS</label>
                        <input type="checkbox" class="form-check-input form-check-input-tenant" id="contact-sms" name="contact-sms" <?php echo ($row['contact_sms']) ? "checked" : ""; ?> <?php echo ($_SESSION['PAGEMODE'] == 'VIEW') ? " readonly" : "" ?>>
                    </div>

                    <!-- contact email  -->
                    <div class="input-group">
                        <label for="contact-email">Email</label>
                        <input type="checkbox" class="form-check-input form-check-input-tenant" id="contact-email" name="contact-email" <?php echo ($row['contact_email']) ? "checked" : ""; ?> <?php echo ($_SESSION['PAGEMODE'] == 'VIEW') ? " readonly" : "" ?>>
                    </div>

                </fieldset>
    
                <table>
                    <tr>
                        <td><input type="submit" class="btn btn-success btn-crud" name="btn-save" value="Save"></td>
                    </tr>
                </table>
            </fieldset>
        </form>
        <!-- empty form for cancel button -->

        <!-- Message area -->
        <div id="div-messages"></div>
    </div>
<?php
}
?>