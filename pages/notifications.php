<?php
session_start();
include_once("./check_session.php");

// session_unset();
$_SESSION['PAGE'] = "notifications";
if (!isset($_SESSION['PAGEMODE'])){
    $_SESSION['PAGEMODE'] = "LIST";
    $_SESSION['PAGENUM'] = 0;
}
?>
<!DOCTYPE html>
<html>

<head>
    <!-- 
        Title:       notifications.php
        Application: RentalBuddy
        Purpose:     Handles the display/reading of notifications
        Author:      G. Blandford,  Group 5, INFO-5139-01-21W
        Date:        March 11th, 2021 (March 11th, 2021)
    -->

    <title>RentalBuddy - Notifications</title>

    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="RentalBuddy Notifications">
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

    // data access layer
    require_once("../dal/codes_dal.php");

    // Check POST ACTIONS first
    if ( isset($_POST['btn-view'] ) && ($_POST['btn-view'] == "View") ) {
        $_SESSION['PAGEMODE'] = "VIEW";
        $_SESSION['PAGENUM'] = 0;

    } else if ( isset($_POST['btn-cancel'] ) && ( $_POST['btn-cancel'] == "Cancel") ) {
        $_SESSION['PAGENUM'] = 0;
        $_SESSION['PAGEMODE'] = "LIST";
    } else {

    }

    //  var_dump( $_SERVER['REQUEST_METHOD'] );
    //  var_dump( $_SESSION );
     // var_dump( $_POST );
     // var_dump( $_GET );

    // $_ POSTing or $_GETting?
    IF ($_SERVER['REQUEST_METHOD'] == 'GET' || $_SESSION['PAGEMODE'] == "LIST" ) {

        // Display Tenants
        formDisplayNotifications();

    } else if ( $_SESSION['PAGEMODE'] == "ADD" || $_SESSION['PAGEMODE'] == "EDIT" || $_SESSION['PAGEMODE'] == "VIEW" ) {

        switch ($_SESSION['PAGENUM']) {

            case 0: // Show Form
                $_SESSION['PAGENUM'] = 1; // Set to validate

                // ADD record
                if ($_SESSION['PAGEMODE'] == "ADD") {

                    // Empty record
                    $_SESSION['notification_id'] = 0;
                    $_SESSION['rowdata'] = array();

                    // Show tenant page
                    formNotification();

                // EDIT RECORD
                } else if (isset($_POST['selected']) && strlen($_POST['selected'][0] > 0 ) ) {

                    // Get Selected tenant 
                    $_SESSION['notification_id'] = $_POST['selected'][0];

                    // Get notification data
                    getNotification();

                    // Show tenant
                    formNotification();

                // LIST RECORDS
                } else {

                    formDisplayNotifications(); 
                }
                break;

            case 1: // Save
                if  ( ( isset($_POST['btn-cancel'] ) ) && ( $_POST['btn-cancel'] == "Cancel" || $_POST['btn-cancel'] == "OK" ) ) {

                    $_SESSION['PAGEMODE'] = "LIST";
                    $_SESSION['PAGENUM'] = 0;

                    // Clear current data
                    unset($_SESSION['notification_id']);
                    unset($_SESSION['rowdata']);

                    formDisplayNotifications();
                }
                break;
            default:
        }
    } else if ($_SESSION['PAGEMODE'] == "LIST") {

        formDisplayNotifications();
    }

    // We can do anything here AFTER the form has loaded
    ?>

    <!-- Custom JS -->
    <!-- <script src="./js/script.js"></script> -->

    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script src="../vendor/twbs/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
    <link href="../node_modules/bootstrap-icons/font//bootstrap-icons.css" rel="stylesheet">
 </body>

</html>

<?php

function formDisplayNotifications()
{
?>
    <form method="POST">
        <?php

        // Get Data
        getNotifications();

        ?>
            <div class="container-fluid container-crud">
                <table>
                    <tr>
                        <td><input type="submit" class="btn btn-secondary btn-crud" name="btn-view" value="View"></td>
                        <!-- <td><input type="submit" class="btn btn-danger btn-crud" name="btn-delete" value="Delete"></td> -->
                    </tr>
                </table>
                </div>   
        </form>
    </div>
<?php }

function formNotification()
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
                    ?>Notification</legend>

                <!-- From -->
                <div class="input-group">
                    <label for="sender-user-id">From</label>
                    <input type="text" class="form-control" id="sender-user-id" name="sender-user-id" aria-describedby="sender-user-id-help" placeholder="" value="<?php echo $row['sender_user_id']; ?>" readonly>
                    <small id="sender-user-id-help" class="form-text text-muted"></small>
                </div>

                <!-- Name -->
                <div class="input-group">
                    <label for="sender-name">Name</label>
                    <input type="text" class="form-control" id="sender-name" name="sender-name" aria-describedby="sender-name-help" placeholder="" value="<?php echo $row['sender_name']; ?>" readonly>
                    <small id="sender-name-help" class="form-text text-muted"></small>
                </div>

                <!-- Details -->
                <div class="input-group">
                    <label for="details">Details</label>
                    <textarea rows="10" cols="50" style="height:100%;" class="form-control" id="details" name="details" aria-describedby="details-help" placeholder="Notification details" required<?php echo ($_SESSION['PAGEMODE'] == 'VIEW') ? " readonly" : ""?>><?php echo $row['details']; ?></textarea>
                    <small id="details-help" class="form-text text-muted"></small>
                </div>

                <!-- Sent date -->
                <div class="input-group">
                    <label for="sent-datetime">Sent</label>
                    <input type="text" class="form-control" id="sent-datetime" name="sent-datetime" aria-describedby="sent-datetime" placeholder="Sent date and time" value="<?php echo $row['sent_datetime']; ?>" required<?php echo ($_SESSION['PAGEMODE'] == 'VIEW') ? " readonly" : ""?>>
                    <small id="sent-datetime-help" class="form-text text-muted"></small>
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