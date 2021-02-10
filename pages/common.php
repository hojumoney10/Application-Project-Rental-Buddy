<?php
// time and date correction.
date_default_timezone_set('America/Toronto');


// For database connection
define("DBHOST", "localhost");
define("DBDB",   "rental");
define("DBUSER", "rental");
define("DBPW",   "SScAGAMfi4g0gwgp");


//Connect to database using PDO method
function connectDB()
{
    try{
        $dbconn = new PDO('mysql:host=' . DBHOST . ';dbname=' . DBDB  .';charset=utf8', DBUSER, DBPW);
    
        $dbconn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        return $dbconn;
    }
    catch(Exception $e) {
        echo 'Failed to obtain database handle : '.$e->getMessage();
    }

}

function sanitize_html($arg)
{
    $sanitizedArray = array();
    foreach ($arg as $key => $value) {
        $sanitizedArray[$key] = htmlentities($value);
    }
    //return the array of sanitized values
    return $sanitizedArray;
}

// Function to format phones
function formatPhone($phone) {

    $fPhone = trim($phone);
    $fPhone = "(" . substr($fPhone, 0, 3) . ") " . substr($fPhone, 3, 3) . "-" . substr($fPhone, 6, 4);
    return $fPhone;
};