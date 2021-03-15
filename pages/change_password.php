<?php
session_start();
include_once("./check_session.php");

// session_unset();
$_SESSION['PAGE'] = "change_password";

if (!isset($_SESSION['PAGEMODE'])){
    $_SESSION['PAGEMODE'] = "EDIT";
    $_SESSION['PAGENUM'] = 0;
}

?>
<!DOCTYPE html>
<html>

<head>
    <!-- 
        Title:       change_password.php
        Application: RentalBuddy
        Purpose:     Handles changing a user password
        Author:      G. Blandford,  Group 5, INFO-5139-01-21W
        Date:        March 14th, 2021 (March 14th, 2021)

    -->

    <title>RentalBuddy - Change Password</title>

    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="RentalBuddy Change Password">
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
    require_once("../dal/users_dal.php");

    // var_dump( $_SERVER['REQUEST_METHOD'] );
    // var_dump( $_SESSION );
    // var_dump( $_POST );
    // var_dump( $_GET );

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {

        // SAVE
        if  ( ( isset($_POST['btn-save'] ) ) && ( $_POST['btn-save'] == "Update" ) )  {

            // Validate
            $err_msgs = validatePassword();

            if (count($err_msgs) > 0) {

                displayErrors($err_msgs);
                formPassword();

            } else {
                // Save to database                     
                savePassword();

                // Redisplay tenant
                formPassword();

                // Show message
                ?>
                    <script>
                        showMessages("Password has been updated.");
                    </script>
                <?php

            }
        } 
    } 

    else {

        $_SESSION['PAGENUM'] = 1; // Set to validate

        // EDIT record
    
        // Get Selected tenant 
        $_SESSION['user_id'] = $_SESSION['CURRENT_USER']['user_id'];

        // // Get tenant data
        // getTenant();

        // Show tenant
        formPassword();
    }
    ?>

    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script src="../vendor/twbs/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
    <link href="../node_modules/bootstrap-icons/font//bootstrap-icons.css" rel="stylesheet">
</body>

</html>

<?php

function validatePassword() {

    $rowdata = [];
    $err_msgs = [];

    // "^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,10}$"
    // Regex for password
    // 8-10 characters
    // One uppercase, one lowercase, 1 number, 1 special-character
    //
    // https://stackoverflow.com/questions/19605150/regex-for-password-must-contain-at-least-eight-characters-at-least-one-number-a

    // password
	if( !isset($_POST['password'] ) ) {
		$err_msgs[] = "An password is required";
	} else if( !isset($_POST['confirm'] ) ) {
		$err_msgs[] = "Password must be entered twice";
	} else {
    
        $password = $_POST['password'];
        $confirm = $_POST['confirm'];

        if ($password != $confirm) {
            $err_msgs[] = "Passwords must match";
        } else if ( preg_match("/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/i", $password) ) {
            $rowdata['password'] = $password;
            $rowdata['confirm'] = $confirm;
        } else {
            $err_msgs[] = "Password must be at least 8 characters, containing one uppercase letter, one lowercase letter, one digit and one special character";
        }
    }
    $_SESSION['rowdata'] = $rowdata;

	return $err_msgs;
}

function formPassword()
{
    // Get the data
    $row = [];

?>
    <div class="container-fluid">

        <form class="form form-inline" method="POST" style="padding-right: 30px;">
            <fieldset class="bg-light">
                <legend class="text-light bg-dark">Tenant Profile</legend>

                <!-- password -->
                <div class="input-group">
                    <label for="password">Password</label>
                    <input type="password" size="30" maxlength="50" class="form-control" id="password" name="password" aria-describedby="password-help" placeholder="Enter password" value="" required<?php echo ($_SESSION['PAGEMODE'] == 'VIEW') ? " readonly" : ""?>>
                    <small id="password-help" class="form-text text-muted"></small>
                </div>

                <!-- confirm  -->
                <div class="input-group">
                    <label for="confirm">Confirm</label>
                    <input type="password" size="30" maxlength="50" class="form-control" id="confirm" name="confirm" aria-describedby="email-help" placeholder="Re-enter password" value="" required<?php echo ($_SESSION['PAGEMODE'] == 'VIEW') ? " readonly" : ""?>>
                    <small id="confirm-help" class="form-text text-muted"></small>
                </div>
                <table>
                    <tr>
                        <td><input type="submit" class="btn btn-success btn-crud" name="btn-save" value="Update"></td>
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