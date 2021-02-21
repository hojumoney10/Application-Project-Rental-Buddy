<!-- 
    Title:       leases_dal.php
    Application: RentalBuddy
    Purpose:     Handles the lease-related data access code
    Author:      G. Blandford, S. Jeong Group 5, INFO-5139-01-21W
    Date:        February 10th, 2021 (February 10th, 2021) 
-->
<?php

// Load common
define('__ROOT__', dirname(__FILE__));
require_once("../pages/common.php");

// Get landloards
function getLeases()
{

    // create database connection
    $db_conn = connectDB();

    // SQL query
    $querySQL = "select
            l.lease_id
            , l.rental_property_id 
            , l.tenant_id
            , l.payment_day 
            , l.payment_frequency_code
            , l.base_rent_amount
            , l.payment_type_code
            , status_codes.description as status_code
            
        from leases l


        inner join codes status_codes on status_codes.code_value = l.status_code and status_codes.code_type = 'lease_status'";
        
        //inner join codes payment_type_code on payment_type.code_value = l.payment_type and payment_type.code_type = 'payment_type_code'
        // inner join codes payment_frequency on payment_frequency.code_value = l.payment_frequency and payment_frequency.code_type = 'payment_frequency'

        if (isset($_SESSION['text-search'])) {
        if ((strlen($_SESSION['text-search']) > 0)) {
            $querySQL .= " where l.lease_id";
        }
    }

    $querySQL .= " order by l.lease_id;";

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
            <legend class="text-light bg-dark" style="margin-top: 10px">Leases</legend>
            <table id="table-responsive" class="table table-light table-responsive table-striped">
                <thead class="table-dark">
                    <!-- <tr>
                        <th class="text-light bg-dark" scope="row" colspan="6" style="text-align: left; border-radius: 5px;">Leases</th>
                        <th></th>
                    </tr> -->
                    <tr>
                        <th scope="col"></th>
                        <th scope="col">No.</th>
                        <th scope="col">Rental Property No.</th>
                        <th scope="col">Tenant No.</th>
                        <th scope="col">Payment Day</th>
                        <th scope="col">payment frequency</th>             
                        <th scope="col">Base Rent Amount</th>             
                        <th scope="col">Payment Type Code</th>
                        <th scope="col">Status</th>
                    </tr>
                </thead>
                <tbody id="tbody-leases">
                    <?php

                    // execute query in database
                    $status = $stmt->execute($data);

                    if ($status) { // no error

                        if ($stmt->rowCount() > 0) { // Results!

                            // Display leases
                            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {

                                // lease per row
                    ?>
                                <tr>
                                    <th><input type="radio" style="width:10px;" name="selected[]" value="<?php echo $row['lease_id']; ?>"></th>
                                    <td><?php echo $row["lease_id"]; ?></td>
                                    <td><a href="/pages/rental_properties.php"><?php echo $row["rental_property_id"]; ?></a></td>
                                    <td><a href="/pages/tenants.php"><?php echo $row["tenant_id"]; ?></a></td>
                                    <td><?php echo $row["payment_day"]; ?></td>
                                    <td><?php echo $row["payment_frequency_code"]; ?></td>
                                    <td><?php echo $row["base_rent_amount"]; ?></td>
                                    <td><?php echo $row["payment_type_code"]; ?></td>
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
                            // No leases found 
?>
    <tr>
        <td></td>
        <td>No leases found.</td>
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
function getLease() {

    // lease
    $lease_id = $_SESSION['lease_id'];

    // create database connection
    $db_conn = connectDB();

    // SQL query
    $querySQL = "SELECT
            l.lease_id            
            , l.rental_property_id
            , l.tenant_id            
            , l.start_date
            , l.end_date
            , l.payment_day
            , l.payment_frequency_code
            , l.base_rent_amount
            , l.parking_amount
            , l.other_amount
            , l.payable_to
            , l.deposit_amount
            , l.key_deposit
            , l.payment_type_code
            , l.include_electricity
            , l.include_heat
            , l.include_water
            , l.insurancy_policy_number
            , l.status_code
            , l.last_updated
            , l.last_updated_user_id

        from leases l

        where l.lease_id = :lease_id";

                    // assign value to :lease_id
                    $data = array(":lease_id" => $lease_id);

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
function saveLease() {

    $lease_id = $_SESSION['lease_id'];
    $rowdata = $_SESSION['rowdata'];

 //print_r($lease_id);
 //print_r($rowdata);

    // create database connection
    $db_conn = connectDB();

    if ( isset($_SESSION['userdata']) && !empty($_SESSION['userdata'] ) ) {
        $session_user_id = $_SESSION['userdata']['user_id'];
    } else {
        $session_user_id = "admin";
    }

    if ($lease_id == 0) {

        // Add
        $querySQL = "INSERT into leases (
                    rental_property_id
                    , tenant_id
                    , start_date
                    , end_date
                    , payment_day
                    , payment_frequency_code
                    , base_rent_amount
                    , parking_amount
                    , other_amount
                    , payable_to
                    , deposit_amount
                    , key_deposit
                    , payment_type_code
                    , include_electricity
                    , include_heat
                    , include_water
                    , insurancy_policy_number
                    , status_code
                    , last_updated_user_id
                ) values (
                        :rental_property_id
                        , :tenant_id
                        , :start_date
                        , :end_date
                        , :payment_day
                        , :payment_frequency_code
                        , :base_rent_amount
                        , :parking_amount
                        , :other_amount
                        , :payable_to
                        , :deposit_amount
                        , :key_deposit
                        , :payment_type_code
                        , :include_electricity
                        , :include_heat
                        , :include_water
                        , :insurancy_policy_number
                        , :status_code
                        , :session_user_id
                )";

        // assign data values
        $data = array(  ":rental_property_id" => $rowdata['rental_property_id'],
                        ":tenant_id" => $rowdata['tenant_id'],
                        ":start_date" => $rowdata['start_date'],
                        ":end_date" => $rowdata['end_date'],
                        ":payment_day" => $rowdata['payment_day'],
                        ":payment_frequency_code" => $rowdata['payment_frequency_code'],
                        ":base_rent_amount" => $rowdata['base_rent_amount'],
                        ":parking_amount" => $rowdata['parking_amount'],
                        ":other_amount" => $rowdata['other_amount'],
                        ":payable_to" => $rowdata['payable_to'],
                        ":deposit_amount" => $rowdata['deposit_amount'],
                        ":key_deposit" => $rowdata['key_deposit'],
                        ":payment_type_code" => $rowdata['payment_type_code'],
                        ":include_electricity" => $rowdata['include_electricity'],
                        ":include_heat" => $rowdata['include_heat'],
                        ":include_water" => $rowdata['include_water'],
                        ":insurancy_policy_number" => $rowdata['insurancy_policy_number'],
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
                $lease_id = $db_conn->lastInsertId(); // Get lease_id

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
        $querySQL = "update leases as l
                set 
                    l.rental_property_id        = :rental_property_id
                    , l.tenant_id               = :tenant_id
                    , l.start_date              = :start_date
                    , l.end_date                = :end_date
                    , l.payment_day             = :payment_day
                    , l.payment_frequency_code  = :payment_frequency_code
                    , l.base_rent_amount        = :base_rent_amount
                    , l.parking_amount          = :parking_amount
                    , l.other_amount            = :other_amount
                    , l.payable_to              = :payable_to
                    , l.deposit_amount          = :deposit_amount
                    , l.key_deposit             = :key_deposit
                    , l.payment_type_code       = :payment_type_code
                    , l.include_electricity     = :include_electricity
                    , l.include_heat            = :include_heat
                    , l.include_water           = :include_water
                    , l.insurancy_policy_number = :insurancy_policy_number
                    , l.status_code             = :status_code
                    , l.last_updated            = now()
                    , l.last_updated_user_id    = :session_user_id

            where l.lease_id = :lease_id";

        // assign data values
        $data = array(  ":lease_id" => $lease_id,
                        ":rental_property_id" => $rowdata['rental_property_id'],
                        ":tenant_id" => $rowdata['tenant_id'],
                        ":start_date" => $rowdata['start_date'],
                        ":end_date" => $rowdata['end_date'],
                        ":payment_day" => $rowdata['payment_day'],
                        ":payment_frequency_code" => $rowdata['payment_frequency_code'],
                        ":base_rent_amount" => $rowdata['base_rent_amount'],
                        ":parking_amount" => $rowdata['parking_amount'],
                        ":other_amount" => $rowdata['other_amount'],
                        ":payable_to" => $rowdata['payable_to'],
                        ":deposit_amount" => $rowdata['deposit_amount'],
                        ":key_deposit" => $rowdata['key_deposit'],
                        ":payment_type_code" => $rowdata['payment_type_code'],
                        ":include_electricity" => $rowdata['include_electricity'],
                        ":include_heat" => $rowdata['include_heat'],
                        ":include_water" => $rowdata['include_water'],
                        ":insurancy_policy_number" => $rowdata['insurancy_policy_number'],
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
                //$lease_id = $db_conn->lastInsertId(); // Get lease_id

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