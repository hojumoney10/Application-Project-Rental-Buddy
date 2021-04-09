<!-- 
    Title:       tenants_dal.php
    Application: RentalBuddy
    Purpose:     Handles the tenant-related data access code
    Author:      J. Foster & S. Jeong, Group 5, INFO-5139-01-21W
    Date:        April 2nd, 2021 (February 18th, 2021) 

    20210312    GPB Corrected $session_user_id to use CURRENT_USER
    20210402    GPB Add getTenantPayments()

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
            $querySQL .= " where t.first_name like :text_search or t.last_name like :text_search";
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
function getTenant()
{

    // tenant
    $tenant_id = $_SESSION['tenant_id'];

    // create database connection
    $db_conn = connectDB();

    // SQL query
    $querySQL = "SELECT
                    t.tenant_id
                    , t.salutation_code            
                    , t.first_name
                    , t.last_name
                    , trim( concat(ifnull(salutations.description, ''), ' ', t.first_name, ' ', t.last_name ) ) as full_name
                    , t.address_1
                    , t.address_2
                    , t.city
                    , t.province_code
                    , t.postal_code
                    , t.phone
                    , t.fax
                    , t.email
                    , t.date_of_birth
                    , t.gender
                    , t.social_insurance_number
                    , t.contact_phone
                    , t.contact_sms
                    , t.contact_email
                    , t.status_code
                    , t.last_updated
                    , t.last_updated_user_id

                    FROM tenants t
                    left JOIN codes salutations on salutations.code_value = t.salutation_code and salutations.code_type = 'salutation'
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

// Get the payments for a tenant
function getTenantPayments($tenant_id)
{
        // create database connection
        $db_conn = connectDB();

        // SQL query
        $querySQL = "select
                        tp.tenant_payment_id
                        , payment_types.description as payment_type_code
                        , tp.description
                        , tp.payment_date
                        , tp.payment_due
                        , tp.discount_coupon_code
                        , tp.discount
                        , tp.payment_amount
                        , tp.card_holder
                        , tp.card_number
                        , tp.card_expiry
                        , tp.card_CVV
                        , status_codes.description as status_code
                        
                    from tenant_payments as tp
                    inner join codes payment_types on payment_types.code_value = tp.payment_type_code and payment_types.code_type = 'payment_type'
                    inner join codes status_codes on status_codes.code_value = tp.status_code and status_codes.code_type = 'payment_status'
                    
                    where tp.tenant_id = :tenant_id;";

        $querySQL .= " order by tp.payment_date desc;";

        $data = array(":tenant_id" => $tenant_id);

        // prepare query
        $stmt = $db_conn->prepare($querySQL);

        // prepare error check
        if (!$stmt) {
            echo "<p>Error: " . $db_conn->errorCode() . "<br>Message: " . implode($db_conn->errorInfo()) . "</p><br>";
            exit(1);
        }
?>
                <div class="container-fluid">
                    <legend class="text-light bg-dark" style="margin-top: 10px">Tenant Payments</legend>
                    <table id="table-responsive" class="table table-light table-responsive table-striped">
                        <thead class="table-dark">
                            <tr>
                                <th></th>
                                <th scope="col">No.</th>
                                <th scope="col">Description</th>
                                <th scope="col">Payment Date</th>
                                <th scope="col">Paid By</th>
                                <th scope="col">Paid</th>
                                <th scope="col">Status</th>
                            </tr>
                        </thead>
                        <tbody id="tbody-tenant-payments">
                            <?php

                            // execute query in database
                            $status = $stmt->execute($data);

                            if ($status) { // no error

                                if ($stmt->rowCount() > 0) { // Results!

                                    // Display rental properties
                                    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                                        // Rental property per row
                            ?>
                                        <tr>
                                            <th><input type="radio" style="width:10px;" name="selected[]" value="<?php echo $row['tenant_payment_id']; ?>"></th>
                                            <td><?php echo $row["tenant_payment_id"]; ?></td>
                                            <td><?php echo $row["description"]; ?></td>
                                            <td><?php echo formatDate($row["payment_date"]); ?></td>
                                            <td><?php echo $row["payment_type_code"]; ?></td>
                                            <td><?php echo $row["payment_amount"]; ?></td>
                                            <td style=" <?php
                                                        echo ($row["status_code"] === "Paid" ? "color: green" : "color: red");
                                                        ?>">
                                                <?php echo $row["status_code"] ?>
                                            </td>
                                        </tr>
                                    <?php
                                    }
                                    ?>
                        </tbody>
                    </table>
                </div>
            
<?php

                    } else {
                        // No tenant payments found 
?>
    <tr>
        <td></td>
        <td>No tenant payments.</td>
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



// Save Profile
function saveTenantProfile()
{

    $tenant_id = $_SESSION['tenant_id'];
    $rowdata = $_SESSION['rowdata'];

    // create database connection
    $db_conn = connectDB();

    if (isset($_SESSION['CURRENT_USER']) && !empty($_SESSION['CURRENT_USER'])) {
        $session_user_id = $_SESSION['CURRENT_USER']['user_id'];
    } else {
        $session_user_id = "admin";
    }

    // Update
    $querySQL = "UPDATE tenants AS t
SET 
    t.contact_phone             = :contact_phone
    , t.contact_sms             = :contact_sms
    , t.contact_email           = :contact_email
    , t.phone                   = :phone
    , t.fax                     = :fax
    , t.email                   = :email
    , t.last_updated            = now()
    , t.last_updated_user_id    = :session_user_id

WHERE t.tenant_id = :tenant_id";

    // assign data values
    $data = array(
        ":tenant_id" => $tenant_id,
        ":contact_phone" => $rowdata['contact_phone'],
        ":contact_sms" => $rowdata['contact_sms'],
        ":contact_email" => $rowdata['contact_email'],
        ":phone" => $rowdata['phone'],
        ":fax" => $rowdata['fax'],
        ":email" => $rowdata['email'],
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

// Save Tenant
function saveTenant()
{

    $tenant_id = $_SESSION['tenant_id'];
    $rowdata = $_SESSION['rowdata'];

    // print_r($tenant_id);
    // print_r($rowdata);

    // create database connection
    $db_conn = connectDB();

    if (isset($_SESSION['CURRENT_USER']) && !empty($_SESSION['CURRENT_USER'])) {
        $session_user_id = $_SESSION['CURRENT_USER']['user_id'];
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
        $data = array(
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
        $data = array(
            ":tenant_id" => $tenant_id,
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