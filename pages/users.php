<?php
session_start();

//login 
include_once("./check_session.php");

// session_unset();
$_SESSION['PAGE'] = "users";
if (!isset($_SESSION['PAGEMODE'])){
    $_SESSION['PAGEMODE'] = "LIST";
    $_SESSION['PAGENUM'] = 0;
}
?>
<!DOCTYPE html>
<html>

<head>
    <!-- 
        Title:       users.php
        Application: RentalBuddy
        Purpose:     Handles the crud functions of users
        Author:      G. Blandford & S. Jeong,  Group 5, INFO-5139-01-21W
        Date:        March 9th, 2021 (February 18th, 2021)

    -->

    <title>RentalBuddy - Users</title>

    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="RentalBuddy Users">
    <meta name="author" content="Graham Blandford, Sirlin Jeong">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="../vendor/twbs/bootstrap/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link href="node_modules/bootstrap-icons/font//bootstrap-icons.css" rel="stylesheet">

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
    require_once("../dal/users_dal.php");

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
    } else {

    }

     // var_dump( $_SERVER['REQUEST_METHOD'] );
     // var_dump( $_SESSION );
     // var_dump( $_POST );
     // var_dump( $_GET );

    // $_ POSTing or $_GETting?
    IF ($_SERVER['REQUEST_METHOD'] == 'GET' || $_SESSION['PAGEMODE'] == "LIST" ) {

        // Display Users
        formDisplayUsers();

    } else if ( $_SESSION['PAGEMODE'] == "ADD" || $_SESSION['PAGEMODE'] == "EDIT" || $_SESSION['PAGEMODE'] == "VIEW" ) {

        switch ($_SESSION['PAGENUM']) {

            case 0: // Show Form
                $_SESSION['PAGENUM'] = 1; // Set to validate

                // ADD record
                if ($_SESSION['PAGEMODE'] == "ADD") {

                    // Empty record
                    $_SESSION['user_id'] = 0;
                    $_SESSION['rowdata'] = array();

                    // Show user page
                    formUser();

                // EDIT RECORD
                } else if (isset($_POST['selected']) ) {
                    
                    
                    // Get Selected user 
                    $_SESSION['user_id'] = $_POST['selected'][0];
                    
                    
                    // Get user data
                    getUser();
                    
                    // Show user
                    formUser();
                    
                    // LIST RECORDS
                } else {
                    
                    formDisplayUsers(); 
                }
                break;

            case 1: // Save
                if ( ( isset($_POST['btn-save'] ) ) && ( $_POST['btn-save'] == "Save" ) ) {
                    
                    // Validate
                    $err_msgs = validateUser();

                    if (count($err_msgs) > 0) {

                        displayErrors($err_msgs);
                        formUser();
                    } else {
                        
                        // Save to database                     
                        saveUser();
                        $_SESSION['PAGENUM'] = 0;

                        // Clear row
                        unset($_SESSION['rowdata']);

                        $_SESSION['PAGEMODE'] = "LIST";
                        formDisplayUsers();
                    }

                } else if  ( ( isset($_POST['btn-cancel'] ) ) && ( $_POST['btn-cancel'] == "Cancel" || $_POST['btn-cancel'] == "OK" ) ) {

                    $_SESSION['PAGEMODE'] = "LIST";
                    $_SESSION['PAGENUM'] = 0;

                    // Clear current data
                    unset($_SESSION['user_id']);
                    unset($_SESSION['rowdata']);

                    formDisplayUsers();
                }
                break;
            default:
        }
    } else if ($_SESSION['PAGEMODE'] == "LIST") {

        formDisplayUsers();
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

function validateUser() {

    $rowdata = $_SESSION['rowdata'];

    //$rowdata['user_id'] = $_POST['user-id'];
    $rowdata['salutation_code'] = $_POST['salutation'];

    $err_msgs = [];

    // User ID
	if( !isset($_POST['user-id'] ) ) {
		$err_msgs[] = "The User ID is required";
	} else {
		$rowdata['user_id'] = $_POST['user-id'];
		if (strlen($rowdata['user_id']) == 0){
			$err_msgs[] = "A user ID is required";
		} else if (strlen( $rowdata['user_id'] ) > 50 ){
			$err_msgs[] = "The user ID exceeds 50 characters";
		}
	}

    // Password
	if( !isset($_POST['password'] ) ) {
		$err_msgs[] = "The password is required";
	} else {
		$rowdata['password'] = $_POST['password'];
		if (strlen($rowdata['password']) == 0){
			$err_msgs[] = "A password is required";
		} else if (strlen( $rowdata['password'] ) > 50 ) {
			$err_msgs[] = "The password exceeds 50 characters";
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

    // user role code
	if( !isset($_POST['user-role'] ) ) {
		$err_msgs[] = "A user role is required";
	} else {
		$rowdata['user_role_code'] = $_POST['user-role'];
		if (strlen($rowdata['user_role_code']) == 0){
			$err_msgs[] = "A user role is required";
		} else if (strlen($rowdata['user_role_code']) > 10 ){
			$err_msgs[] = "The user role exceeds 10 characters";
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
    
    // tenant id
    if( isset($_POST['tenant-id'] ) ) {
        $rowdata['tenant_id'] = $_POST['tenant-id'];
	} 

    // landlord id
    if( isset($_POST['landlord-id'] ) ) {
        $rowdata['landlord_id'] = $_POST['landlord-id'];
    } 
        
    $_SESSION['rowdata'] = $rowdata;
	return $err_msgs;
}

function formDisplayUsers()
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
        getUsers();

        // // Search Bar
        // getSearch($fvalue);

        // Get Standard CRUD buttons
        getCRUDButtons();
        ?>

    </form>
    </div>
<?php }

function formUser()
{
    // Get the data
    $row = $_SESSION['rowdata'];

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
                    ?>User Details</legend>

                <!-- User ID -->
                <div class="input-group">
                    <label for="user-id">User ID</label>
                    <input type="text" size="30" maxlength="50" class="form-control" id="user-id" name="user-id" aria-describedby="user-id-help" placeholder="Enter user ID" value="<?php echo $row['user_id']; ?>" required<?php echo ($_SESSION['PAGEMODE'] == 'VIEW') ? " readonly" : ""?>>
                    <small id="user-id-help" class="form-text text-muted"></small>
                </div>

                <!-- password -->
                <div class="input-group">
                    <label for="password">password</label>
                    <input type="password" size="30" maxlength="50" class="form-control" id="password" name="password" aria-describedby="password-help" value="<?php echo $row['password']; ?>" required<?php echo ($_SESSION['PAGEMODE'] == 'VIEW') ? " readonly" : ""?>>
                    <small id="password-help" class="form-text text-muted"></small>
                </div>

                <!-- Email -->
                <div class="input-group">
                    <label for="email">Email</label>
                    <input type="email" size="30" maxlength="50" class="form-control" id="email" name="email" aria-describedby="email-help" placeholder="Enter email address" value="<?php echo $row['email']; ?>" required<?php echo ($_SESSION['PAGEMODE'] == 'VIEW') ? " readonly" : ""?>>
                    <small id="email-help" class="form-text text-muted"></small>
                </div>

                <!--  User Role  -->
                <div class="input-group">
                    <label for="user-role">User Role</label>
                    <select class="selectpicker form-control" style="max-width: 100px" id="user-role" name="user-role" aria-describedby="user-role-help" placeholder="Enter user role" required<?php echo ($_SESSION['PAGEMODE'] == 'VIEW') ? " readonly" : ""?>>
                        <?php
                        getCodes('user_role', $row['user_role_code']);
                        ?>
                    </select>
                    <small id="user-role-help" class="form-text text-muted"></small>
                </div>      

                <!--  status  -->
                <div class="input-group">
                    <label for="status-code">Status</label>
                    <select class="selectpicker form-control" style="max-width: 100px" id="status-code" name="status-code" aria-describedby="status-code-help" placeholder="Enter status" required<?php echo ($_SESSION['PAGEMODE'] == 'VIEW') ? " readonly" : ""?>>
                        <?php
                        getCodes('user_status', $row['status_code']);
                        ?>
                    </select>
                    <small id="status-code-help" class="form-text text-muted"></small>
                </div>           

                <!--tenant_id-->
                <div class="input-group">
                    <label for="tenant-id">Tenant No.</label>
                    <input type="text" size="10" maxlength="10" class="form-control" style="max-width: 80px" id="tenant-id" name="tenant-id" aria-describedby="tenant-id-help" placeholder="" value="<?php echo $row['tenant_id']; ?>" required<?php echo ($_SESSION['PAGEMODE'] == 'VIEW') ? " readonly" : ""?>>
                    <small id="tenant-id-help" class="form-text text-muted"></small>

                    <input type="text" class="form-control" id="tenant-name" name="tenant-name" value="<?php echo $row['tenant_name']; ?>" readonly>
                    <small id="tenant-name-help" class="form-text text-muted"></small>
                </div>

                <!--landlord_id-->
                <div class="input-group">
                    <label for="landlord-id">Landlord No.</label>
                    <input type="text" size="10" maxlength="10" class="form-control" style="max-width: 80px" id="landlord-id" name="landlord-id" aria-describedby="landlord-id-help" placeholder="" value="<?php echo $row['landlord_id']; ?>" required<?php echo ($_SESSION['PAGEMODE'] == 'VIEW') ? " readonly" : ""?>>
                    <small id="landlord-id-help" class="form-text text-muted"></small>

                    <input type="text" class="form-control" id="landlord-name" name="landlord-name" value="<?php echo $row['landloard_name']; ?>" readonly>
                    <small id="landloard-name-help" class="form-text text-muted"></small>
                </div>

                <table>
                    <tr>
<?php 
                if ($_SESSION['PAGEMODE'] == 'EDIT' || $_SESSION['PAGEMODE'] == 'ADD') { ?>
                        <td><input type="submit" class="btn btn-success btn-crud" name="btn-save" value="Save"></td>
<?php            }
?>
                        <td>
                                <input type="submit" form="form-cancel" class="btn <?php echo ($_SESSION['PAGEMODE'] == 'EDIT' || $_SESSION['PAGEMODE'] == 'ADD' ) ? 'btn-secondary' : 'btn-primary'; ?> btn-crud" name="btn-cancel" value="<?php echo ($_SESSION['PAGEMODE'] == 'EDIT' || $_SESSION['PAGEMODE'] == 'ADD' ) ? 'Cancel' : 'OK'; ?>">
                        </td>
                    </tr>
                </table>
            </fieldset>
        </form>
        <!-- empty form for cancel button -->
        <form id="form-cancel" hidden><form>
    </div>
<?php
}
?>
