<!-- 
    Title:       landlords_dal.php
    Application: RentalBuddy
    Purpose:     Handles the landlord-related data access code
    Author:      G. Blandford, Group 5, INFO-5139-01-21W
    Date:        February 10th, 2021 (February 10th, 2021) 
-->
<?php

// Load common
define('__ROOT__', dirname(__FILE__));
require_once("../pages/common.php");

// Get landloards
function getLandlords()
{

    // create database connection
    $db_conn = connectDB();

    // SQL query
    $querySQL = "select
            l.landlord_id
            , l.legal_name
            , trim( concat(ifnull(salutations.description, ''), ' ', l.first_name, ' ', l.last_name ) ) as full_name
            , trim( concat(l.address_1, ' ', ifnull(l.address_2, ''), ', ', l.city, ' ', l.postal_code) ) as address
            , l.phone
            , l.sms
            , l.email
            , status_codes.description as status_code
            
        from landlords l
        inner join codes salutations on salutations.code_value = l.salutation_code and salutations.code_type = 'salutation'
        inner join codes status_codes on status_codes.code_value = l.status_code and status_codes.code_type = 'landlord_status'";

    if (isset($_SESSION['text-search'])) {
        if ((strlen($_SESSION['text-search']) > 0)) {
            $querySQL .= " where l.legal_name like :text_search";
        }
    }

    $querySQL .= " order by l.landlord_id;";

    $text_search = '%' . $_SESSION['text-search'] . '%';
    $data = array(":text_search" => $text_search);

    // prepare query
    $stmt = $db_conn->prepare($querySQL);

    // prepare error check
    if (!$stmt) {
        echo "<p>Error: " . $db_conn->errorCode() . "<br>Message: " . implode($db_conn->errorInfo()) . "</p><br>";
        exit(1);
    }

?>
    <div class="container-fluid">
            <legend class="text-light bg-dark" style="margin-top: 10px">Landlords</legend>
            <table id="table-responsive" class="table table-light table-responsive table-striped">
                <thead class="table-dark">
                    <!-- <tr>
                        <th class="text-light bg-dark" scope="row" colspan="6" style="text-align: left; border-radius: 5px;">Landlords</th>
                        <th></th>
                    </tr> -->
                    <tr>
                        <th scope="col"></th>
                        <th scope="col">No.</th>
                        <th scope="col">Name</th>
                        <th scope="col">Contact</th>             
                        <th scope="col">Address</th>             
                        <th scope="col">Phone</th>
                        <th scope="col">SMS</th>
                        <th scope="col">Email</th>
                        <th scope="col">Status</th>
                    </tr>
                </thead>
                <tbody id="tbody-landlords">
                    <?php

                    // execute query in database
                    $status = $stmt->execute($data);

                    if ($status) { // no error

                        if ($stmt->rowCount() > 0) { // Results!

                            // Display landlords
                            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {

                                // Landlord per row
                    ?>
                                <tr>
                                    <th><input type="radio" style="width:10px;" name="selected[]" value="<?php echo $row['landlord_id']; ?>"></th>
                                    <td><?php echo $row["landlord_id"]; ?></td>
                                    <td><?php echo $row["legal_name"]; ?></td>
                                    <td><?php echo $row["full_name"]; ?></td>
                                    <td><?php echo $row["address"]; ?></td>
                                    <td><?php echo formatPhone($row["phone"]); ?></td>
                                    <td><?php echo formatPhone($row["sms"]); ?></td>
                                    <td><?php echo $row["email"]; ?></td>
                                    <td style=" <?php echo ($row["status_code"] === "Active" ? "color: green" : "color: red"); ?>">
                                        <?php echo $row["status_code"] ?> </td>
                                </tr>
                            <?php
                            }
                            ?>
                </tbody>
            </table>
    </div>
<?php

                        } else {
                            // No landlords found 
?>
    <tr>
        <td></td>
        <td>No landlords found.</td>
    </tr>
<?php
                        }
                    } else {
                        // execute error
                        echo "<p>Error: " . $stmt->errorCode() . "<br>Message: " . implode($stmt->errorInfo()) . "</p><br>";

                        // close database connection
                        $db_conn = null;

                        exit(1);
                    }
                    // close database connection
                    $db_conn = null;
                }

// Get a landloards
function getLandlord() {

    // landlord
    $landlord_id = $_SESSION['landlord_id'];

    // create database connection
    $db_conn = connectDB();

    // SQL query
    $querySQL = "select
            l.landlord_id
            
            , l.legal_name

            , l.salutation_code            
            , l.first_name
            , l.last_name
            , l.address_1
            , l.address_2
            , l.city
            , l.province_code
            , l.postal_code
            , l.phone
            , l.fax
            , l.email
            , l.sms

            , l.status_code
            , l.last_updated
            , l.last_updated_user_id

        from landlords l

        where l.landlord_id = :landlord_id";

                    // assign value to :landlord_id
                    $data = array(":landlord_id" => $landlord_id);

                    // prepare query
                    $stmt = $db_conn->prepare($querySQL);

                    // prepare error check
                    if (!$stmt) {
                        echo "<p>Error: " . $db_conn->errorCode() . "<br>Message: " . implode($db_conn->errorInfo()) . "</p><br>";
                        exit(1);
                    }

                    // execute query in database
                    $status = $stmt->execute($data);

                    if ($status) { // no error

                        if ($stmt->rowCount() > 0) { // Found

                            // Store row in the session
                            $_SESSION['rowdata'] = $stmt->fetch(PDO::FETCH_ASSOC);
                        }
                    } else {
                        // execute error
                        echo "<p>Error: " . $stmt->errorCode() . "<br>Message: " . implode($stmt->errorInfo()) . "</p><br>";

                        // close database connection
                        $db_conn = null;

                        exit(1);
                    }
                    // close database connection
                    $db_conn = null;
                }
// Get a landloards
function saveLandlord() {

    $landlord_id = $_SESSION['landlord_id'];
    $rowdata = $_SESSION['rowdata'];

// print_r($landlord_id);
// print_r($rowdata);

    // create database connection
    $db_conn = connectDB();

    if ( isset($_SESSION['userdata']) && !empty($_SESSION['userdata'] ) ) {
        $session_user_id = $_SESSION['userdata']['user_id'];
    } else {
        $session_user_id = "admin";
    }

    if ($landlord_id == 0) {

        // Add
        $querySQL = "insert into landlords (
                    legal_name
                    , salutation_code
                    , first_name
                    , last_name
                    , address_1
                    , address_2
                    , city
                    , province_code
                    , postal_code
                    , phone
                    , fax
                    , email
                    , sms
                    , status_code
                    , last_updated_user_id
                ) values (
                        :legal_name
                        , :salutation_code
                        , :first_name
                        , :last_name
                        , :address_1
                        , :address_2
                        , :city
                        , :province_code
                        , :postal_code
                        , :phone
                        , :fax
                        , :email
                        , :sms
                        , :status_code
                        , :session_user_id
                )";

        // assign data values
        $data = array(  ":legal_name" => $rowdata['legal_name'],
                        ":salutation_code" => $rowdata['salutation_code'],
                        ":first_name" => $rowdata['first_name'],
                        ":last_name" => $rowdata['last_name'],
                        ":address_1" => $rowdata['address_1'],
                        ":address_2" => $rowdata['address_2'],
                        ":city" => $rowdata['city'],
                        ":province_code" => $rowdata['province_code'],
                        ":postal_code" => $rowdata['postal_code'],
                        ":phone" => $rowdata['phone'],
                        ":fax" => $rowdata['fax'],
                        ":email" => $rowdata['email'],
                        ":sms" => $rowdata['sms'],
                        ":status_code" => $rowdata['status_code'],
                        ":session_user_id" => $session_user_id
                    );

        // Transaction Start
       $db_conn->beginTransaction(); 
                            
            // prepare query
            $stmt = $db_conn->prepare($querySQL);

            // prepare error check
            if (!$stmt) {

                echo "<p>Error: " . $db_conn->errorCode() . "<br>Message: " . implode($db_conn->errorInfo()) . "</p><br>";
                $db_conn->rollback(); // Transaction Rollback
                exit(1);
            }

            // execute query in database
            $status = $stmt->execute($data);

            if ($status) { 

                // Should give the identity 
                $landlord_id = $db_conn->lastInsertId(); // Get landlord_id

            } else {
                // execute error
                echo "<p>Error: " . $stmt->errorCode() . "<br>Message: " . implode($stmt->errorInfo()) . "</p><br>";
                $db_conn->rollback(); // Transaction Rollback

                // close database connection
                $db_conn = null;
                exit(1);
            }

        // Transaction Commit
        $db_conn->commit();

        // close database connection
        $db_conn = null;
        
    } else {

        // Update
        $querySQL = "update landlords as l
                set 
                    l.legal_name                = :legal_name
                    , l.salutation_code         = :salutation_code
                    , l.first_name              = :first_name
                    , l.last_name               = :last_name
                    , l.address_1               = :address_1
                    , l.address_2               = :address_2
                    , l.city                    = :city
                    , l.province_code           = :province_code
                    , l.postal_code             = :postal_code
                    , l.phone                   = :phone
                    , l.fax                     = :fax
                    , l.email                   = :email
                    , l.sms                     = :sms
                    , l.status_code             = :status_code
                    , l.last_updated            = now()
                    , l.last_updated_user_id    = :session_user_id

            where l.landlord_id = :landlord_id";

        // assign data values
        $data = array(  ":landlord_id" => $landlord_id,
                        ":legal_name" => $rowdata['legal_name'],
                        ":salutation_code" => $rowdata['salutation_code'],
                        ":first_name" => $rowdata['first_name'],
                        ":last_name" => $rowdata['last_name'],
                        ":address_1" => $rowdata['address_1'],
                        ":address_2" => $rowdata['address_2'],
                        ":city" => $rowdata['city'],
                        ":province_code" => $rowdata['province_code'],
                        ":postal_code" => $rowdata['postal_code'],
                        ":phone" => $rowdata['phone'],
                        ":fax" => $rowdata['fax'],
                        ":email" => $rowdata['email'],
                        ":sms" => $rowdata['sms'],
                        ":status_code" => $rowdata['status_code'],
                        ":session_user_id" => $session_user_id
                    );

        // Transaction Start
       $db_conn->beginTransaction(); 
                            
            // prepare query
            $stmt = $db_conn->prepare($querySQL);

            // prepare error check
            if (!$stmt) {

                echo "<p>Error: " . $db_conn->errorCode() . "<br>Message: " . implode($db_conn->errorInfo()) . "</p><br>";
                $db_conn->rollback(); // Transaction Rollback
                exit(1);
            }

            // execute query in database
            $status = $stmt->execute($data);

            if ($status) { 
                
                // Nothing to do for an update
                //$landlord_id = $db_conn->lastInsertId(); // Get landlord_id

            } else {
                // execute error
                echo "<p>Error: " . $stmt->errorCode() . "<br>Message: " . implode($stmt->errorInfo()) . "</p><br>";
                $db_conn->rollback(); // Transaction Rollback

                // close database connection
                $db_conn = null;
                exit(1);
            }

        // Transaction Commit
        $db_conn->commit();

        // close database connection
        $db_conn = null;
    }
}                
?>