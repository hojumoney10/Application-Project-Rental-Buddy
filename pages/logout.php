
<!-- 
    Title:       logout.php
    Application: RentalBuddy
    Purpose:     Handles logout, destroy session and redirect to login
    Author:      G. Blandford,  Group 5, INFO-5094-01-21W
    Date:        March 1st, 2021 (March 1st, 2021)
-->
<?php
    session_start();
    session_destroy();

    header('location: login.php');
    die();
?>
