<!-- 
    Title:       users_dal.php
    Application: RentalBuddy
    Purpose:     Handles the user-related data access code
    Author:      G. Blandford & S. Jeong, Group 5, INFO-5139-01-21W
    Date:        March 9th, 2021 (February 18th, 2021)
-->
<?php

// Load common
define('__ROOT__', dirname(__FILE__));
require_once("../pages/common.php");

// Get landloards
function getUsers()  
{

    // create database connection
    $db_conn = connectDB();

    // SQL query
    $querySQL = "select
    u.user_id
    , u.email
    , u.user_role_code
    , user_role_codes.description as user_role_code_description
    , status_codes.description as status_code
    
    , ifnull(u.tenant_id,  '') as tenant_id
    , trim( concat(ifnull(salutations.description, ''), ' ', t.first_name, ' ', t.last_name ) ) as tenant_name
    
    , ifnull(u.landlord_id,  '') as landlord_id
    , trim( concat(ifnull(salutations.description, ''), ' ', l.first_name, ' ', l.last_name ) ) as landlord_name
    
from users u

left join tenants t on t.tenant_id = u.tenant_id
left join codes salutations on salutations.code_value = t.salutation_code and salutations.code_type = 'salutation'

left join landlords l on l.landlord_id = u.landlord_id


inner join codes user_role_codes on user_role_codes.code_value = u.user_role_code and user_role_codes.code_type = 'user_role'
inner join codes status_codes on status_codes.code_value = u.status_code and status_codes.code_type = 'user_status'";

        //need check
    // if (isset($_SESSION['text-search'])) {
    //     if ((strlen($_SESSION['text-search']) > 0)) {   
    //         $querySQL .= " where u.user_id";
    //     }
    // }

    $querySQL .= " order by u.user_id;";

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
            <legend class="text-light bg-dark" style="margin-top: 10px">Users</legend>
            <table id="table-responsive" class="table table-light table-responsive table-striped">
                <thead class="table-dark">
                    <tr>
                        <th scope="col"></th>
                        <th scope="col">User ID</th>
                        <th scope="col">Email</th>             
                        <th scope="col">Role</th>
                        <th scope="col">Tenant</th>
                        <th scope="col">Landlord</th>
                        <th scope="col">Status</th>
                    </tr>
                </thead>
                <tbody id="tbody-user">
                    <?php

                    // execute query in database
                    $status = $stmt->execute($data);

                    if ($status) { // no error

                        if ($stmt->rowCount() > 0) { // Results!

                            // Display Tenatn
                            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {

                                // user per row
                    ?>
                                <tr>
                                    <th><input type="radio" style="width:10px;" name="selected[]" value="<?php echo $row['user_id']; ?>"></th>
                                    <td><?php echo $row["user_id"]; ?></td>
                                    <td><?php echo $row["email"]; ?></td>
                                    <td><?php echo $row["user_role_code_description"]; ?></td>
                                    <td><a href="/pages/tenants.php"><?php echo $row["tenant_name"]; ?></a></td>
                                    <td><a href="/pages/landlords.php"><?php echo $row["landlord_name"]; ?></a></td>
                                    <td style=" <?php echo ($row["status_code"] === "Enabled" ? "color: green" : "color: red"); ?>">
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
                            // No users found 
?>
    <tr>
        <td></td>
        <td>No users found.</td>
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

// Get a User
function getUser() {

    // user
    $user_id = $_SESSION['user_id'];

    // create database connection
    $db_conn = connectDB();

    // SQL query
    $querySQL = "SELECT
            u.user_id         
            , u.email
            , u.user_role_code
            , u.status_code
            , ifnull(u.tenant_id,  '') as tenant_id
            , trim( concat(ifnull(salutations.description, ''), ' ', t.first_name, ' ', t.last_name ) ) as tenant_name
            , ifnull(u.landlord_id,  '') as landlord_id
            , trim( concat(ifnull(salutations.description, ''), ' ', l.first_name, ' ', l.last_name ) ) as landlord_name
            , u.last_login
            , u.last_updated
            , u.last_updated_user_id

        FROM users u

        left join tenants t on t.tenant_id = u.tenant_id
        left join landlords l on l.landlord_id = u.landlord_id
        left join codes salutations on salutations.code_value = t.salutation_code and salutations.code_type = 'salutation'

        inner join codes user_role_codes on user_role_codes.code_value = u.user_role_code and user_role_codes.code_type = 'user_role'
        inner join codes status_codes on status_codes.code_value = u.status_code and status_codes.code_type = 'user_status'

        WHERE u.user_id = :user_id";

                    // assign value to :user_id
                    $data = array(":user_id" => $user_id);

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
// Get a Users
function saveUser() {
    
    $user_id = $_SESSION['user_id'];
    $rowdata = $_SESSION['rowdata'];

  //print_r($user_id);
 // print_r($rowdata);

    // create database connection
    $db_conn = connectDB();

    if ( isset($_SESSION['CURRENT_USER']) && !empty($_SESSION['CURRENT_USER'] ) ) {
        $session_user_id = $_SESSION['CURRENT_USER']['user_id'];
    } else {
        $session_user_id = "admin";
    }
    
    if ($user_id == 0) {

        // Add
        $querySQL = "INSERT INTO users (
                    `user_id`
                    , `password`
                    , `email`
                    , `user_role_code`
                    , `status_code`
                    , `tenant_id`
                    , `landlord_id`
                    , `last_updated_user_id`
                ) VALUES (
                        :user_id
                        , :password
                        , :email
                        , :user_role_code
                        , :status_code
                        , :tenant_id
                        , :landlord_id
                        , :session_user_id
                )";

        // assign data values
        $data = array(  ":user_id" => $rowdata['user_id'],
                        ":password" => "md5(" . $rowdata['user_id'] . ")",
                        ":email" => $rowdata['email'],
                        ":user_role_code" => $rowdata['user_role_code'],
                        ":status_code" => $rowdata['status_code'],
                        ":tenant_id" => ($rowdata['tenant_id'] > "" ? $rowdata['tenant_id'] : null),
                        ":landlord_id" => ($rowdata['landlord_id'] > "" ? $rowdata['landlord_id'] : null),
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
                $user_id = $db_conn->lastInsertId(); // Get user_id

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
        $querySQL = "UPDATE users AS u
                SET 
                      u.user_id                 = :user_id
                    , u.email                   = :email
                    , u.user_role_code          = :user_role_code
                    , u.status_code             = :status_code
                    , u.tenant_id               = :tenant_id
                    , u.landlord_id             = :landlord_id
                    , u.last_login              = now()
                    , u.last_updated            = now()
                    , u.last_updated_user_id    = :session_user_id

            WHERE u.user_id = :selected_user_id";

        // assign data values
        $data = array(  ":user_id" => $rowdata['user_id'],
                        ":email" => $rowdata['email'],
                        ":user_role_code" => $rowdata['user_role_code'],
                        ":status_code" => $rowdata['status_code'],
                        ":tenant_id" => ($rowdata['tenant_id'] > "" ? $rowdata['tenant_id'] : null),
                        ":landlord_id" => ($rowdata['landlord_id'] > "" ? $rowdata['landlord_id'] : null),
                        ":session_user_id" => $session_user_id,
                        ":selected_user_id" => $_SESSION['user_id']
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
                //$user_id = $db_conn->lastInsertId(); // Get user_id

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