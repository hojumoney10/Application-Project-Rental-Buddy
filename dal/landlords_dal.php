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
            , l.city
            , l.postal_code
            , l.phone
            , l.email
            , status_codes.description as status_code
            
        from landlords l
        inner join codes status_codes on status_codes.code_value = l.status_code  and status_codes.code_type = 'landlord_status'";

    if (isset($_SESSION['searchtext'])) {
        if ((strlen($_SESSION['searchtext']) > 0)) {
            $querySQL .= " where l.legal_name like :searchtext";
        }
    }

    $querySQL .= " order by l.landlord_id;";

    $searchtext = '%' . $_SESSION['searchtext'] . '%';
    $data = array(":searchtext" => $searchtext);

    // prepare query
    $stmt = $db_conn->prepare($querySQL);

    // prepare error check
    if (!$stmt) {
        echo "<p>Error: " . $db_conn->errorCode() . "<br>Message: " . implode($db_conn->errorInfo()) . "</p><br>";
        exit(1);
    }

?>
    <div class="container-fluid">
        <table id="table-responsive" class="table table-light table-responsive table-striped">
            <thead class="thead-dark">
                <!-- <tr>
                    <th scope="row" colspan="6" style="text-align: left;">Landlords</th>
                </tr> -->
                <tr>
                    <th scope="col"></th>
                    <th scope="col">No.</th>
                    <th scope="col">Name</th>
                    <th scope="col">Phone</th>
                    <th scope="col">Email</th>
                    <th scope="col">Status</th>
                    <th scope="col"></th>
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
            function getLandlord($landlord_id)
            {

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
                        $_SESSION['landlord_data'] = $stmt->fetch(PDO::FETCH_ASSOC);
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
?>