<!-- 
    Title:       rental_properties_dal.php
    Application: RentalBuddy
    Purpose:     Handles the rental ptoperty-related data access code
    Author:      G. Blandford, Group 5, INFO-5139-01-21W
    Date:        February 15th, 2021 (February 15th, 2021) 
-->
<?php

// Load common
define('__ROOT__', dirname(__FILE__));
require_once("../pages/common.php");

// Get landloards
function getProperties()
{

    // create database connection
    $db_conn = connectDB();

    // SQL query
    $querySQL = "select
            rp.rental_property_id
            , trim( concat(rp.address_1, ' ', ifnull(rp.address_2, ''), ', ', rp.city, ' ', rp.postal_code) ) as address
            , rp.latitude
            , rp.longitude
            , rp.number_bedrooms
            
            , property_types.description as property_type_code
            , parking_space_types.description as parking_space_type_code
            , rp.number_parking_spaces
            , rental_durations.description as rental_duration_type_code
            , rp.smoking_allowed
            , rp.insurance_required

            , status_codes.description as status_code
            
        from landlords l
        inner join codes property_types on property_types.code_value = rp.property_type_code and property_types.code_type = 'property_type'
        inner join codes parking_space_types on parking_space_types.code_value = rp.parking_space_type_code and parking_space_types.code_type = 'parking_space'
        inner join codes rental_durations on rental_durations.code_value = rp.rental_duration_code and rental_durations.code_type = 'rental_duration'
        inner join codes status_codes on status_codes.code_value = rp.status_code and status_codes.code_type = 'property_status'";

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
                                    <td><?php echo $row["address"]; ?></td>
                                    <td><?php echo $row["number_bedrooms"]; ?></td>
                                    <td><?php echo $row["property_type_code"]; ?></td>
                                    <td><?php echo $row["rental_duration_type_code"]; ?></td>
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

// Get a rental property
function getProperty() {

    // landlord
    $rental_property_id = $_SESSION['rental_property_id'];

    // create database connection
    $db_conn = connectDB();

    // SQL query
    $querySQL = "select
            rp.rental_property_id
            
            , rp.address_1
            , rp.address_2
            , rp.city
            , rp.province_code
            , rp.postal_code

            , rp.latitude 
            , rp.longitude 
            , rp.number_bedrooms 
            , rp.property_type_code 
            , rp.parking_space_type_code 
            , rp.number_parking_spaces
            , rp.rental_duration_code 
            , rp.smoking_allowed
            , rp.insurance_required 

            , rp.status_code
            , rp.last_updated
            , rp.last_updated_user_id

        from rental_properties rp

        where rp.rental_property_id = :rental_property_id";

    // assign value to :rental_property_id
    $data = array(":rental_property_id" => $rental_property_id);

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

// Save a rental property
function saveProperty() {

    $rental_property_id = $_SESSION['rental_property_id'];
    $rowdata = $_SESSION['rowdata'];

    // create database connection
    $db_conn = connectDB();

    if ( isset($_SESSION['userdata']) && !empty($_SESSION['userdata'] ) ) {
        $session_user_id = $_SESSION['userdata']['user_id'];
    } else {
        $session_user_id = "admin";
    }

    if ($rental_property_id == 0) {

        // Add
        $querySQL = "insert into rental_properties (
                        address_1
                        , address_2
                        , city
                        , province_code
                        , postal_code
                        , latitude 
                        , longitude 
                        , number_bedrooms 
                        , property_type_code 
                        , parking_space_type_code 
                        , number_parking_spaces
                        , rental_duration_code 
                        , smoking_allowed
                        , insurance_required 
                        , status_code
                        , last_updated_user_id
                ) values (
                        :address_1
                        , :address_2
                        , :city
                        , :province_code
                        , :postal_code
                        , :latitude 
                        , :longitude 
                        , :number_bedrooms 
                        , :property_type_code 
                        , :parking_space_type_code 
                        , :number_parking_spaces
                        , :rental_duration_code 
                        , :smoking_allowed
                        , :insurance_required 
                        , :status_code
                        , :session_user_id
                )";

        // assign data values
        $data = array(  ":address_1" => $rowdata['address_1'],
                        ":address_2" => $rowdata['address_2'],
                        ":city" => $rowdata['city'],
                        ":province_code" => $rowdata['province_code'],
                        ":postal_code" => $rowdata['postal_code'],
                        ":latitude" => $rowdata['latitude'],
                        ":longitude" => $rowdata['longitude'],
                        ":number_bedrooms" => $rowdata['number_bedrooms'],
                        ":property_type_code" => $rowdata['property_type_code'],
                        ":parking_space_type_code" => $rowdata['parking_space_type_code'],
                        ":number_parking_spaces" => $rowdata['number_parking_spaces'],
                        ":rental_duration_code" => $rowdata['rental_duration_code'],
                        ":smoking_allowed" => $rowdata['smoking_allowed'],
                        ":insurance_required" => $rowdata['insurance_required'],
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
                $rental_property_id = $db_conn->lastInsertId(); // Get landlord_id

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
        $querySQL = "update rental_properties as rp
                set 
                    rp.address_1                = :address_1
                    , rp.address_2              = :address_2
                    , rp.city                   = :city
                    , rp.province_code          = :province_code
                    , rp.postal_code            = :postal_code

                    , rp.latitude               = :latitude
                    , rp.longitude              = :longitude
                    , rp.number_bedrooms        = :number_bedrooms
                    , rp.property_type_code     = :property_type_code
                    , rp.parking_space_type_code= :parking_space_type_code
                    , rp.number_parking_spaces  = :number_parking_spaces
                    , rp.rental_duration_code   = :rental_duration_code
                    , rp.smoking_allowed        = :smoking_allowed
                    , rp.insurance_required     = :insurance_required

                    , rp.status_code             = :status_code
                    , rp.last_updated            = now()
                    , rp.last_updated_user_id    = :session_user_id

            where l.landlord_id = :landlord_id";

        // assign data values
        $data = array(  ":rental_property_id" => $rental_property_id,
                        ":address_1" => $rowdata['address_1'],
                        ":address_2" => $rowdata['address_2'],
                        ":city" => $rowdata['city'],
                        ":province_code" => $rowdata['province_code'],
                        ":postal_code" => $rowdata['postal_code'],
                        ":latitude" => $rowdata['latitude'],
                        ":longitude" => $rowdata['longitude'],
                        ":number_bedrooms" => $rowdata['number_bedrooms'],
                        ":property_type_code" => $rowdata['property_type_code'],
                        ":parking_space_type_code" => $rowdata['parking_space_type_code'],
                        ":number_parking_spaces" => $rowdata['number_parking_spaces'],
                        ":rental_duration_code" => $rowdata['rental_duration_code'],
                        ":smoking_allowed" => $rowdata['smoking_allowed'],
                        ":insurance_required" => $rowdata['insurance_required'],
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