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
function getNotifications() {

    // create database connection   
    $db_conn = connectDB();

    // SQL query
    $querySQL = "select
            n.notification_id
            , n.sender_user_id
            , ifnull(ifnull(l.legal_name, concat(t.first_name, ' ', t.last_name) ), n.sender_user_id) as sender_name
            , n.recipient_user_id
            , n.details
            , n.entity_type
            , n.entity_type_id
            , n.sent_datetime
            , n.notification_status
            
        from notifications as n
        inner join users u on u.user_id = n.sender_user_id
        left join tenants t on t.tenant_id = u.tenant_id
        left join landlords l on l.landlord_id = u.landlord_id

        where n.recipient_user_id = :session_user_id
        and n.notification_status <> 'deleted'";

    $querySQL .= " order by n.sent_datetime desc;";

    $session_user_id = $_SESSION['CURRENT_USER']['user_id'];

    $data = array(":session_user_id" => $session_user_id);

    // prepare query
    $stmt = $db_conn->prepare($querySQL);

    // prepare error check
    if (!$stmt) {
        echo "<p>Error: " . $db_conn->errorCode() . "<br>Message: " . implode($db_conn->errorInfo()) . "</p><br>";
        exit(1);
    }
?>
    <div class="container-fluid">
        <legend class="text-light bg-dark" style="margin-top: 10px">Notifications</legend>
        <table id="table-responsive" class="table table-light table-responsive table-striped">
            <thead class="table-dark">
                <tr>
                    <th scope="col"></th>
                    <th scope="col">From</th>
                    <th scope="col">Name</th>
                    <th scope="col">Details</th>
                    <th scope="col">Sent</th>
                </tr>
            </thead>
            <tbody id="tbody-notifications">
    <?php

    // execute query in database
    $status = $stmt->execute($data);

    if ($status) { // no error

        if ($stmt->rowCount() > 0) { // Results!
            // Display notifications
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                // row
    ?>
                <tr <?php
                    echo ($row["notification_status"] === "unread" ? 'style="font-weight:bold"' : ''); ?>">
                    
                    <th><input type="radio" style="max-width:10px;" name="selected[]" value="<?php echo $row['notification_id']; ?>"></th>
                    <td><?php echo $row["sender_user_id"]; ?></td>
                    <td><?php echo $row["sender_name"]; ?></td>
                    <td style="white-space: nowrap; text-overflow:ellipsis; overflow: hidden; max-width:200px;"><?php echo $row["details"]; ?></td>
                    <td style="max-width: 100px;"><?php echo $row["sent_datetime"]; ?></td>
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
        <td>No notifications found.</td>
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
function countNotifications()
{

    $numberNotifications = 0;

    // create database connection
    $db_conn = connectDB();

    if (isset($_SESSION['CURRENT_USER']) && !empty($_SESSION['CURRENT_USER'])) {
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
// Also, makes as read
function getNotification()
{
    // notification
    $notification_id = $_SESSION['notification_id'];

    // create database connection
    $db_conn = connectDB();

    // SQL query
    $querySQL = "select
                n.notification_id
                , n.sender_user_id
                , ifnull(ifnull(l.legal_name, concat(t.first_name, ' ', t.last_name) ), n.sender_user_id) as sender_name
                , n.recipient_user_id
                , n.details
                , n.entity_type
                , n.entity_type_id
                , n.sent_datetime
                , n.notification_status
                
            from notifications as n
            inner join users u on u.user_id = n.sender_user_id
            left join tenants t on t.tenant_id = u.tenant_id
            left join landlords l on l.landlord_id = u.landlord_id

            where n.notification_id = :notification_id";

    // assign value to :rental_property_id
    $data = array(":notification_id" => $notification_id);

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

    // Mark as read
    markNotificationAsRead();
}


// Create a notification
function createNotification(
        $parent_notification_id = 0 // Future use
        , $sender_user_id = ""      // leave blank usually
        , $recipient_user_id        // 'to'
        , $details                  // notification details
        , $entity_type = ""         // Future use: specify entity if linked to an object
        , $entity_type_id = 0       //              id of the entity object
) {
    // create database connection
    $db_conn = connectDB();

    if (isset($_SESSION['CURRENT_USER']) && !empty($_SESSION['CURRENT_USER'])) {
        $session_user_id = $_SESSION['CURRENT_USER']['user_id'];
    } else {
        $session_user_id = "admin";
    }

    if ($sender_user_id == "") {
        $sender_user_id = $session_user_id;
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
        , :sender_user_id
        , :recipient_user_id
        , :details
        , :entity_type
        , :entity_type_id
        , now()
        , :notification_status
)";

    // assign data values
    $data = array(
        ":parent_notification_id" => $parent_notification_id,
        ":sender_user_id" => $sender_user_id,
        ":recipient_user_id" => $recipient_user_id,
        ":details" => $details,
        ":entity_type" => $entity_type,
        ":entity_type_id" => $entity_type_id,
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
function markNotificationAsRead()
{

    $notification_id = $_SESSION['notification_id'];

    // create database connection
    $db_conn = connectDB();

    // Mark as read
    $querySQL = "update notifications as n
set 
    n.notification_status = 'read'
where n.notification_id = :notification_id";

    // assign data values
    $data = array(":notification_id" => $notification_id);

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
function deleteNotification()
{

    $notification_id = $_SESSION['notification_id'];

    // create database connection
    $db_conn = connectDB();

    // Mark as read
    $querySQL = "update notifications as n
set 
    n.notification_status = 'deleted'
where n.notification_id = :notification_id";

    // assign data values
    $data = array(":notification_id" => $notification_id);

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