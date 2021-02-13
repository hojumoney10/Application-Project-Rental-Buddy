<?php
session_start();

$_SESSION['page'] = "landlords";

// Includes
// require_once("./includes/header.php");
// require_once("./includes/search.php");

// Database
// require_once("./includes/db/db.php"); 
// require_once("./includes/db/dbAlbums.php");

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

        /* nav,
        header,
        div,
        section,
        aside,
        footer {
            display: block;
        } */

        /* .display-4 {
            font-weight: bold;
            text-align: center;
        } */

        nav {
            margin-top: 20px;
        }

        .btn-crud img {
            color: white;
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
    require_once("../dal/landlords_dal.php");

    if (isset($_POST['ct_b_add']) && ($_POST['ct_b_add'] == "Add Album")) {
        $_SESSION['mode'] = "ADD";
        $_SESSION['add_part'] = 0;
    } else if (isset($_POST['ct_b_edit']) && ($_POST['ct_b_edit'] == "Edit Landlord")) {
        $_SESSION['mode'] = "EDIT";
        // } else if (isset($_POST['ct_b_delete']) && ($_POST['ct_b_delete'] == "Delete Album")) {
        //     $_SESSION['mode'] = "DELETE";
    } else if (isset($_POST['ct_b_view']) && ($_POST['ct_b_view'] == "View Landlord")) {
        $_SESSION['mode'] = "VIEW";
    } else if (isset($_POST['ct_b_cancel']) && ($_POST['ct_b_cancel'] == "Cancel")) {
        if ($_SESSION['mode'] == "ADD") {
            $_SESSION['add_part'] = 0;
            //clearAddArtistFromSession();
        }
        $_SESSION['mode'] = "LIST";
    } else {
        $_SESSION['mode'] = "LIST";
    }

    //     if(($_SESSION['mode'] == "Add") && ($_SERVER['REQUEST_METHOD'] == "GET")){ 
    //     	switch ($_SESSION['add_part']) {
    //     		case 0:
    //     		case 1:
    //     			//formContactType();
    //     			break;
    //     		case 2:
    //     			//formContactName();
    //     			break;
    //     		case 3:
    //     			//formContactAddress();
    //     			break;
    //     		case 4:
    //     			//formContactPhone();
    //     			break;
    //     		case 5:
    //     			//formContactEmail();
    //     			break;
    //     		default:
    //     	}
    //     } else if($_SESSION['mode'] == "Add"){ 
    //     	switch ($_SESSION['add_part']) {
    //     		case 0:
    //     			echo "<h1> Add New Contact </h1>\n";
    //     			$_SESSION['add_part'] = 1;
    //     			//formContactType();
    //     			break;
    //     		case 1:
    //     			echo "<h1> Add New Contact </h1>\n";
    //     			$err_msgs = validateContactType();
    //     			if (count($err_msgs) > 0){
    //     				//displayErrors($err_msgs);
    //     				//formContactType();
    //     			} else {
    //     				contactTypePostToSession();
    //     				$_SESSION['add_part'] = 2;
    //     				//formContactName();
    //     			}
    //     			break;
    //     		case 2:
    //     			echo "<h1> Add New Contact </h1>\n";
    //     			$err_msgs = validateContactName();
    //     			if (count($err_msgs) > 0){
    //     				//displayErrors($err_msgs);
    //     				//formContactName();
    //     			} else if ((isset($_POST['ct_b_next']))
    //     					&& ($_POST['ct_b_next'] == "Next")){
    //     				contactNamePostToSession();
    //     				$_SESSION['add_part'] = 3;
    //     				formContactAddress();
    //     			} else if ((isset($_POST['ct_b_back']))
    //     						&& ($_POST['ct_b_back'] == "Back")){
    //     				contactNamePostToSession();
    //     				$_SESSION['add_part'] = 1;
    //     				formContactType();
    //     			}
    //     			break;
    //     		case 3:
    //     			echo "<h1> Add New Contact </h1>\n";
    //     			$err_msgs = validateContactAddress();
    //     			if ((!isset($_POST['ct_b_skip'])) && (count($err_msgs) > 0)){
    //     				displayErrors($err_msgs);
    //     				formContactAddress();
    //     			} else if (isset($_POST['ct_b_skip'])){
    //     				$_SESSION['add_part'] = 4;
    //     				formContactPhone();
    //     			} else if ((isset($_POST['ct_b_next']))
    //     					&& ($_POST['ct_b_next'] == "Next")){
    //     				contactAddressPostToSession();
    //     				$_SESSION['add_part'] = 4;
    //     				formContactPhone();
    //     			} else if ((isset($_POST['ct_b_back']))
    //     						&& ($_POST['ct_b_back'] == "Back")){
    //     				contactAddressPostToSession();
    //     				$_SESSION['add_part'] = 2;
    //     				formContactName();
    //     			}
    //     			break;
    //     		case 4:
    //     			echo "<h1> Add New Contact </h1>\n";
    //     			$err_msgs = validateContactPhone();
    //     			if ((!isset($_POST['ct_b_skip'])) && (count($err_msgs) > 0)){
    //     				displayErrors($err_msgs);
    //     				formContactPhone();
    //     			} else if (isset($_POST['ct_b_skip'])){
    //     				$_SESSION['add_part'] = 5;
    //     				formContactEmail();
    //     			} else if ((isset($_POST['ct_b_next']))
    //     					&& ($_POST['ct_b_next'] == "Next")){
    //     				contactPhonePostToSession();
    //     				$_SESSION['add_part'] = 5;
    //     				formContactEmail();
    //     			} else if ((isset($_POST['ct_b_back']))
    //     						&& ($_POST['ct_b_back'] == "Back")){
    //     				contactPhonePostToSession();
    //     				$_SESSION['add_part'] = 3;
    //     				formContactAddress();
    //     			}
    //     			break;
    //     		case 5:
    //     			echo "<h1> Add New Contact </h1>\n";rY
    //     			$err_msgs = validateContactEmail();
    //     			if ((!isset($_POST['ct_b_skip'])) && (count($err_msgs) > 0)){
    //     				displayErrors($err_msgs);
    //     				formContactEmail();
    //     			} else if (isset($_POST['ct_b_skip'])){
    //     				$_SESSION['add_part'] = 6;
    //     				formContactWeb();
    //     			} else if ((isset($_POST['ct_b_next']))
    //     					&& ($_POST['ct_b_next'] == "Next")){
    //     				contactEmailPostToSession();
    //     				$_SESSION['add_part'] = 6;
    //     				formContactWeb();
    //     			} else if ((isset($_POST['ct_b_back']))
    //     						&& ($_POST['ct_b_back'] == "Back")){
    //     				contactEmailPostToSession();
    //     				$_SESSION['add_part'] = 4;
    //     				formContactPhone();
    //     			}
    //     			break;
    //     		case 6:
    //     			echo "<h1> Add New Contact </h1>\n";
    //     			$err_msgs = validateContactWeb();
    //     			if ((!isset($_POST['ct_b_skip'])) && (count($err_msgs) > 0)){
    //     				displayErrors($err_msgs);
    //     				formContactWeb();
    //     			} else if (isset($_POST['ct_b_skip'])){
    //     				$_SESSION['add_part'] = 7;
    //     				formContactNote();
    //     			} else if ((isset($_POST['ct_b_next']))
    //     					&& ($_POST['ct_b_next'] == "Next")){
    //     				contactWebPostToSession();
    //     				$_SESSION['add_part'] = 7;
    //     				formContactNote();
    //     			} else if ((isset($_POST['ct_b_back']))
    //     						&& ($_POST['ct_b_back'] == "Back")){
    //     				contactWebPostToSession();
    //     				$_SESSION['add_part'] = 5;
    //     				formContactEmail();
    //     			}
    //     			break;
    //     		case 7:
    //     			echo "<h1> Add New Contact </h1>\n";
    //     			$err_msgs = validateContactNote();
    //     			if ((!isset($_POST['ct_b_skip'])) && (count($err_msgs) > 0)){
    //     				displayErrors($err_msgs);
    //     				formContactNote();
    //     			} else if (isset($_POST['ct_b_skip'])){
    //     				$_SESSION['add_part'] = 8;
    //     				formContactSave();
    //     			} else if ((isset($_POST['ct_b_next']))
    //     					&& ($_POST['ct_b_next'] == "Next")){
    //     				contactNotePostToSession();
    //     				$_SESSION['add_part'] = 8;
    //     				formContactSave();
    //     			} else if ((isset($_POST['ct_b_back']))
    //     						&& ($_POST['ct_b_back'] == "Back")){
    //     				contactNotePostToSession();
    //     				$_SESSION['add_part'] = 6;
    //     				formContactWeb();
    //     			}
    //     			break;
    //     		case 8:
    //     			if ((isset($_POST['ct_b_next']))
    //     					&& ($_POST['ct_b_next'] == "Save")){
    //     				$db_conn = connectDB();
    //     				saveContact($db_conn);
    //     				$db_conn = NULL;
    //     				$_SESSION['add_part'] = 0;
    //     				clearAddContactFromSession();
    //     				$_SESSION['mode'] = "Display";
    //     				formContactDisplay();
    //     			} else if ((isset($_POST['ct_b_back']))
    //     						&& ($_POST['ct_b_back'] == "Back")){
    //     				echo "<h1> Add New Contact </h1>\n";
    //     				$_SESSION['add_part'] = 7;
    //     				formContactNote();
    //     			}
    //     			break;
    //     		default:
    //     	}
    //     } else if($_SESSION['mode'] == "EDIT"){ 
    //     } else if($_SESSION['mode'] == "DELETE"){ 
    //     } else if($_SESSION['mode'] == "VIEW"){ 
    //     } else if($_SESSION['mode'] == "DISPLAY"){ 
    //     	formDisplayAlbum();
    //     } 
    formDisplayLandlord();
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
    function formDisplayLandlord()
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
<?php } ?>