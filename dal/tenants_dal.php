<!-- 
    Title:       tenants_dal.php
    Application: RentalBuddy
    Purpose:     Handles the tenant-related data access code
    Author:      J. Foster & S. Jeong, Group 5, INFO-5139-01-21W
    Date:        February 18th, 2021 (February 18th, 2021) 
-->
<?php

// Load common
define('__ROOT__', dirname(__FILE__));
require_once("../pages/common.php");

// Get landloards
function getTenants()  
{

    // create database connection
    $db_conn = connectDB();

    // SQL query
    $querySQL = "select
            t.tenant_id
            , trim( concat(ifnull(salutations.description, ''), ' ', t.first_name, ' ', t.last_name ) ) as full_name
            , trim( concat(t.address_1, ' ', ifnull(t.address_2, ''), ', ', t.city, ' ', t.postal_code) ) as address
            , t.phone
            , t.email
            , status_codes.description as status_code
            
        from tenants t
        inner join codes salutations on salutations.code_value = t.salutation_code and salutations.code_type = 'salutation'
        inner join codes status_codes on status_codes.code_value = t.status_code and status_codes.code_type = 'tenant_status'";

        //need check
    if (isset($_SESSION['text-search'])) {
        if ((strlen($_SESSION['text-search']) > 0)) {
            $querySQL .= " where t.first_name like :text_search";
        }
    }

    $querySQL .= " order by t.tenant_id;";

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
            <legend class="text-light bg-dark" style="margin-top: 10px">Tenants</legend>
            <table id="table-responsive" class="table table-light table-responsive table-striped">
                <thead class="table-dark">
                    <!-- <tr>
                        <th class="text-light bg-dark" scope="row" colspan="6" style="text-align: left; border-radius: 5px;">Tenants</th>
                        <th></th>
                    </tr> -->
                    <tr>
                        <th scope="col"></th>
                        <th scope="col">No.</th>
                        <th scope="col">Name</th>             
                        <th scope="col">Address</th>             
                        <th scope="col">Phone</th>
                        <th scope="col">Email</th>
                        <th scope="col">Status</th>
                    </tr>
                </thead>
                <tbody id="tbody-tenant">
                    <?php

                    // execute query in database
                    $status = $stmt->execute($data);

                    if ($status) { // no error

                        if ($stmt->rowCount() > 0) { // Results!

                            // Display Tenatn
                            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {

                                // Tenant per row
                    ?>
                                <tr>
                                    <th><input type="radio" style="width:10px;" name="selected[]" value="<?php echo $row['tenant_id']; ?>"></th>
                                    <td><?php echo $row["tenant_id"]; ?></td>
                                    <td><?php echo $row["full_name"]; ?></td>
                                    <td><?php echo $row["address"]; ?></td>
                                    <td><?php echo formatPhone($row["phone"]); ?></td>
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
                            // No tenants found 
?>
    <tr>
        <td></td>
        <td>No tenants found.</td>
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
function getTenant() {

    // tenant
    $tenant_id = $_SESSION['tenant_id'];

    // create database connection
    $db_conn = connectDB();

    // SQL query
    $querySQL = "SELECT
            tenant_id
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
            , date_of_birth
            , gender
            , social_insurance_number
            , status_code
            , last_updated
            , last_updated_user_id

        FROM tenants

        WHERE tenant_id = :tenant_id";

                    // assign value to :tenant_id
                    $data = array(":tenant_id" => $tenant_id);

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
function saveTenant() {

    $tenant_id = $_SESSION['tenant_id'];
    $rowdata = $_SESSION['rowdata'];

 // print_r($tenant_id);
 // print_r($rowdata);

    // create database connection
    $db_conn = connectDB();

    if ( isset($_SESSION['userdata']) && !empty($_SESSION['userdata'] ) ) {
        $session_user_id = $_SESSION['userdata']['user_id'];
    } else {
        $session_user_id = "admin";
    }
    
    if ($tenant_id == 0) {

        // Add
        $querySQL = "INSERT INTO tenants (
                    salutation_code
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
                    , date_of_birth
                    , gender
                    , social_insurance_number
                    , status_code
                    , last_updated_user_id
                ) VALUES (
                        :salutation_code
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
                        , :date_of_birth
                        , :gender
                        , :social_insurance_number
                        , :status_code
                        , :session_user_id
                )";

        // assign data values
        $data = array(  ":salutation_code" => $rowdata['salutation_code'],
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
                        ":date_of_birth" => $rowdata['date_of_birth'],
                        ":gender" => $rowdata['gender'],
                        ":social_insurance_number" => $rowdata['social_insurance_number'],
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
                $tenant_id = $db_conn->lastInsertId(); // Get tenant_id

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
        $querySQL = "UPDATE tenants AS t
                SET 
                    t.salutation_code           = :salutation_code
                    , t.first_name              = :first_name
                    , t.last_name               = :last_name
                    , t.address_1               = :address_1
                    , t.address_2               = :address_2
                    , t.city                    = :city
                    , t.province_code           = :province_code
                    , t.postal_code             = :postal_code
                    , t.phone                   = :phone
                    , t.fax                     = :fax
                    , t.email                   = :email
                    , t.date_of_birth           = :date_of_birth
                    , t.gender                  = :gender
                    , t.social_insurance_number = :social_insurance_number
                    , t.status_code             = :status_code
                    , t.last_updated            = now()
                    , t.last_updated_user_id    = :session_user_id

            WHERE t.tenant_id = :tenant_id";

        // assign data values
        $data = array(  ":tenant_id" => $tenant_id,
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
                        ":date_of_birth" => $rowdata['date_of_birth'],
                        ":gender" => $rowdata['gender'],
                        ":social_insurance_number" => $rowdata['social_insurance_number'],
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
                //$tenant_id = $db_conn->lastInsertId(); // Get tenant_id

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