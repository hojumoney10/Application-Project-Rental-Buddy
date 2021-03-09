<!-- 
    Title:       common.php
    Application: RentalBuddy
    Purpose:     Common functions and code
    Author:      T. Kim, G. Blandford, Group 5, INFO-5139-01-21W
    Date:        February 10th, 2021 (February 13th, 2021) 

    20210213    GPB     Added UnformatPhone (untested) 
                        formatPhone: do not format empty phone

    20210214    TK      Added format_date function ex : last updated almost 4hours ago.
                        Added selectCodeValue function 
                                          
    20210215    GPB     Added displayErrors function : displays array of error messages in a dismissible bootstrap alert

    20210220    TK      Added checkUserRoleCode function : input userid, output user_role_code
                        Added checkTenantId function : input userID, output tenantID
                        Added checkLandlord function : input userID, output landlordID
                        Added makeRentalPropertyIdArray : input landlordId, output properties Array
    20210309    TK      Added checkTenantName : input tenantId, output tenantName
                          
-->

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
    if ($fPhone == "") {
        return $fPhone;
    }

    $fPhone = "(" . substr($fPhone, 0, 3) . ") " . substr($fPhone, 3, 3) . "-" . substr($fPhone, 6, 4);
    return $fPhone;
}

// Unformat phone
function unformatPhone($phone)
{
    // Only returns characters
    $uPhone = preg_replace('/[^0-9]+/', '', $phone);
    return $uPhone; 
}

// Calculate Time difference 
function format_date($time)
{
    $t = time() - $time;
    $f = array(
        '31536000' => ' years',
        '2592000' => ' months',
        '604800' => ' weeks',
        '86400' => ' days',
        '3600' => ' hours',
        '60' => ' minutes',
        '1' => ' seconds'
    );
    foreach ($f as $k => $v) {
        if (0 != $c = floor($t / (int)$k)) {
            return $c . $v . ' ago';
        }
    }
}

// put code number, return code value
function selectCodeValue($codeId)
{
    $db_conn = connectDB();
    $stmt = $db_conn->prepare("Select code_value from codes where code_id=? and is_enabled = 1");
    try {
        $stmt->execute(array($codeId));
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            return $row['code_value'];
        }
    } catch (Exception $e) {
        $db_conn->rollback();
        echo $e->getMessage();
    }
}

// Display errors using a dismissible alert
function displayErrors($err_msgs) {

    ?>
        <div class="container-fluid">
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
    <?php
    
        foreach( $err_msgs as $value ) {
            ?>
            <?php echo $value; ?></br>
            <?php
        }
    ?>
                </br>
                <button type="button" class="btn btn-danger close" data-bs-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        </div> 
    <?php
}

function checkUserRoleCode($user_id){
    $db_conn = connectDB();
    $stmt = $db_conn->prepare("select user_role_code from users where user_id=? and status_code = 'enabled'");
    try {
        $stmt->execute(array($user_id));
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            unset($user_id);
            return $row['user_role_code'];
        }
    } catch (Exception $e) {
        $db_conn->rollback();
        echo $e->getMessage();
    }
}

function checkTenantId($user_id){
    $db_conn = connectDB();
    $stmt = $db_conn->prepare("select tenant_id from users where user_id=? and status_code = 'enabled'");
    try {
        $stmt->execute(array($user_id));
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            unset($user_id);
            return $row['tenant_id'];
        }
    } catch (Exception $e) {
        $db_conn->rollback();
        echo $e->getMessage();
    }
}

function checkPropertyId($tenant_id){
    $db_conn = connectDB();
    $stmt = $db_conn->prepare("select rental_property_id from leases where tenant_id=?");
    try {
        $stmt->execute(array($tenant_id));
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            unset($tenant_id);
            return $row['rental_property_id'];
        }
    } catch (Exception $e) {
        $db_conn->rollback();
        echo $e->getMessage();
    }
}

function checkLandlordId($user_id){
    $db_conn = connectDB();
    $stmt = $db_conn->prepare("select landlord_id from users where user_id=? and status_code = 'enabled'");
    try {
        $stmt->execute(array($user_id));
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            unset($user_id);
            return $row['landlord_id'];
        }
    } catch (Exception $e) {
        $db_conn->rollback();
        echo $e->getMessage();
    }
}

function makeRentalPropertyIdArray($landlord_id){
    $db_conn = connectDB();
    $stmt = $db_conn->prepare("select rental_property_id from landlord_rental_properties where landlord_id=?");
    try {
        $tmp=[];
        $stmt->execute(array($landlord_id));
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            array_push($tmp, $row['rental_property_id']);
        }
        return $tmp;
    } catch (Exception $e) {
        $db_conn->rollback();
        echo $e->getMessage();
    }
}

function checkTenantName($tenant_id){
    $db_conn = connectDB();
    $stmt = $db_conn->prepare("select salutation_code, first_name, last_name from tenants where tenant_id=?");
    try {
        $tmp=[];
        $stmt->execute(array($tenant_id));
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            unset($tenant_id);
            return $row['salutation_code'].'. '.$row['first_name'].' '.$row['last_name'];
        }
    } catch (Exception $e) {
        $db_conn->rollback();
        echo $e->getMessage();
    }
}

?>