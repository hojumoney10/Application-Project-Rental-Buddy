<!-- 
    Title:       check_session.php
    Application: RentalBuddy
    Purpose:     Include to do a check on logged in user
    Author:      G. Blandford,  Group 5, INFO-5094-01-21W
    Date:        March 7th, 2021 (March 1st, 2021)
-->

<?php

// Check for session user
if ( !empty( $_SESSION['CURRENT_USER'] ) ) {
	$user_id = $_SESSION['CURRENT_USER'];
}
else {
	header("Location: /pages/login.php");
	die();
}
?>
