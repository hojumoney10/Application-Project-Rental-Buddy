<?php

// Title:       notifications_dal.php
// Application: RentalBuddy
// Purpose:     Handles the notification-related data access code
// Author:      G. Blandford, Group 5, INFO-5139-01-21W
// Date:        March 11th, 2021 (March 11th, 2021) 

define('__DAL__', dirname(__FILE__));
define('__ROOT__', dirname(__DAL__));

include(__ROOT__ . "pages/common.php");

// Get notifications
function getNotifications()
{
    // create database connection
    $db_conn = connectDB();

    // SQL query
    $querySQL = "select
            n.*
            
        from notifications n;";


    if (isset($_SESSION['text-search'])) {
        if ((strlen($_SESSION['text-search']) > 0)) {
            $querySQL .= " where rp.address_1 like :text_search";
        }
    }

    $querySQL .= " order by rp.rental_property_id;";

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
            <legend class="text-light bg-dark" style="margin-top: 10px">Rental Properties</legend>
            <table id="table-responsive" class="table table-light table-responsive table-striped">
                <thead class="table-dark">
                    <tr>
                        <th scope="col"></th>
                        <th scope="col">No.</th>
                        <th scope="col">Ref.</th>
                        <th scope="col">Address</th>
                        <th scope="col">Bedrooms</th>
                        <th scope="col">Type</th>
                        <th scope="col">Rental</th>
                        <th scope="col">Status</th>
                    </tr>
                </thead>
                <tbody id="tbody-landlords">
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
                                    <th><input type="radio" style="width:10px;" name="selected[]" value="<?php echo $row['rental_property_id']; ?>"></th>
                                    <td><?php echo $row["rental_property_id"]; ?></td>
                                    <td><?php echo $row["listing_reference"]; ?></td>
                                    <td><?php echo $row["address"]; ?></td>
                                    <td><?php echo $row["number_bedrooms"]; ?></td>
                                    <td><?php echo $row["property_type_code"]; ?></td>
                                    <td><?php echo $row["rental_duration_type_code"]; ?></td>
                                    <td style=" <?php 
                                            echo 
                                            
                                                ($row["status_code"] === "Leased" ? "color: red" : "color: green"); 
                                            ?>">
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
                            // No rental properties found 
?>
    <tr>
        <td></td>
        <td>No rental properties found.</td>
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

// Return a count of the notifications
function countNotifications() {

    $numberNotifications = 0;

    // create database connection
    $db_conn = connectDB();

    if ( isset($_SESSION['CURRENT_USER']) && !empty($_SESSION['CURRENT_USER'] ) ) {
        $session_user_id = $_SESSION['CURRENT_USER']['user_id'];
    } else {
        return $numberNotifications; 
    }

    // SQL query
    $querySQL = "select 
            count(*) as number_notifications
            
        from notifications n
        where n.notification_status = 'unread'
        and n.recipient_user_id = :recipient_user_id;";

    $data = array(":recipient_user_id" => $session_user_id);

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
        if ($stmt->rowCount() > 0) { // Results!
            $numberNotifications = $stmt->fetch(PDO::FETCH_ASSOC)['number_notifications'];
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

    // Return value
    return $numberNotifications;
}

// Get a single notification
function getNotification() {

    // // landlord
    // $notification_id = $_SESSION['notification_id'];

    // // create database connection
    // $db_conn = connectDB();

    // // SQL query
    // $querySQL = "select
    //         rp.rental_property_id
            
    //         , rp.listing_reference
    //         , rp.address_1
    //         , rp.address_2
    //         , rp.city
    //         , rp.province_code
    //         , rp.postal_code

    //         , rp.latitude 
    //         , rp.longitude 
    //         , rp.number_bedrooms 
    //         , rp.property_type_code 
    //         , rp.parking_space_type_code 
    //         , rp.number_parking_spaces
    //         , rp.rental_duration_code 
    //         , rp.smoking_allowed
    //         , rp.insurance_required 

    //         , rp.status_code
    //         , rp.last_updated
    //         , rp.last_updated_user_id

    //         , l.landlord_id
    //         , l.legal_name as landlord_legal_name

    //     from rental_properties rp
    //     left join landlord_rental_properties lrp on lrp.rental_property_id = rp.rental_property_id
    //     inner join landlords l on l.landlord_id = lrp.landlord_id

    //     where rp.rental_property_id = :rental_property_id";

    // // assign value to :rental_property_id
    // $data = array(":rental_property_id" => $rental_property_id);

    // // prepare query
    // $stmt = $db_conn->prepare($querySQL);

    // // prepare error check
    // if (!$stmt) {
    //     echo "<p>Error: " . $db_conn->errorCode() . "<br>Message: " . implode($db_conn->errorInfo()) . "</p><br>";
    //     exit(1);
    // }

    // // execute query in database
    // $status = $stmt->execute($data);

    // if ($status) { // no error

    //     if ($stmt->rowCount() > 0) { // Found

    //         // Store row in the session
    //         $_SESSION['rowdata'] = $stmt->fetch(PDO::FETCH_ASSOC);
    //     }
    // } else {
    //     // execute error
    //     echo "<p>Error: " . $stmt->errorCode() . "<br>Message: " . implode($stmt->errorInfo()) . "</p><br>";

    //     // close database connection
    //     $db_conn = null;

    //     exit(1);
    // }
    // // close database connection
    // $db_conn = null;
}

// Create a notification
function createNotification() {

    // create database connection
    $db_conn = connectDB();

    $rowdata = $_SESSION['rowdata'];

    if ( isset($_SESSION['CURRENT_USER']) && !empty($_SESSION['CURRENT_USER'] ) ) {
        $session_user_id = $_SESSION['CURRENT_USER']['user_id'];
    } else {
        $session_user_id = "admin"; 
    }

    // Add
    $querySQL = "insert into notifications (
                    parent_notification_id
                    , sender_user_id
                    , recipient_user_id
                    , details
                    , entity_type
                    , entity_type_id
                    , sent_datetime
                    , notification_status
            ) values (
                    :parent_notification_id
                    , :session_user_id
                    , :recipient_user_id
                    , :details
                    , :entity_type
                    , :entity_type_id
                    , now()
                    , :notification_status
            )";

    // assign data values
    $data = array(  ":parent_notification_id" => $rowdata['parent_notification_id'],
                    ":session_user_id" => $session_user_id,
                    ":recipient_user_id" => $rowdata['recipient_user_id'],
                    ":details" => $rowdata['details'],
                    ":entity_type" => $rowdata['entity_type'],
                    ":entity_type_id" => $rowdata['entity_type_id'],
                    ":notification_status" => 'unread'
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
                       
        if (!$status) {

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

// Mark notification as read
function markNotificationAsRead() {

    $notification_id = $_SESSION['notification_id'];

    // create database connection
    $db_conn = connectDB();

    // Mark as read
    $querySQL = "update notifications as n
            set 
                n.notification_status = 'read'
        where n.notification_id = :notification_id";

    // assign data values
    $data = array(  ":notification_id" => $notification_id);

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

        if (!$status) {
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

// Mark notification as deleted
function deleteNotification() {

    $notification_id = $_SESSION['notification_id'];

    // create database connection
    $db_conn = connectDB();

    // Mark as read
    $querySQL = "update notifications as n
            set 
                n.notification_status = 'deleted'
        where n.notification_id = :notification_id";

    // assign data values
    $data = array(  ":notification_id" => $notification_id);

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

        if (!$status) {
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
?>