<!-- 
        Title:       vacancy_rental_dal.php
        Application: RentalBuddy
        Purpose:     vacancy rental properties
        Author:      S. JEong,  Group 5, INFO-5094-01-21W
        Date:        April 5st, 2021
-->
<?php

// Load common
define('__ROOT__', dirname(__FILE__));
require_once("../pages/common.php");


function get_vacancyRentalProperties()
{
    // create database connection
    $db_conn = connectDB();

    // SQL query
    $querySQL = "select
            rp.rental_property_id
            
            , rp.listing_reference
            , trim( concat(rp.address_1, ' ', ifnull(rp.address_2, ''), ', ', rp.city) ) as address

            , rp.latitude 
            , rp.longitude 
            , rp.number_bedrooms 
            , rp.property_type_code 
            , rp.parking_space_type_code 
            , rp.number_parking_spaces
            , rp.rental_duration_code 
            , rp.smoking_allowed
            , rp.insurance_required 
            , rp.photo

            , rp.status_code
            , rp.last_updated
            , rp.last_updated_user_id

            , l.landlord_id
            , l.legal_name as landlord_legal_name
            , l.email as landlord_email

        from rental_properties rp
        left join landlord_rental_properties lrp on lrp.rental_property_id = rp.rental_property_id
        inner join landlords l on l.landlord_id = lrp.landlord_id

        inner join codes property_types on property_types.code_value = rp.property_type_code and property_types.code_type = 'property_type'
        inner join codes parking_space_types on parking_space_types.code_value = rp.parking_space_type_code and parking_space_types.code_type = 'parking_space'
        inner join codes rental_durations on rental_durations.code_value = rp.rental_duration_code and rental_durations.code_type = 'rental_duration'
        inner join codes status_codes on status_codes.code_value = rp.status_code and status_codes.code_type = 'property_status'";

    if (isset($_SESSION['text-search'])) {
        if ((strlen($_SESSION['text-search']) > 0)) {
            $querySQL .= " where rp.address_1 like :text_search";
        }
    }

    $querySQL .= "where rp.status_code = 'available' or rp.status_code = 'Available'
                  order by rp.last_updated DESC";

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
    <br><br><br>
    <div class="container-fluid">
            <legend class="text-light bg-secondary" style="margin-top: 10px">Vacancy Rental Property</legend>
            <div class="row" id="contactCard">
                    <?php

                    // execute query in database
                    $status = $stmt->execute($data);

                    if ($status) { // no error

                        if ($stmt->rowCount() > 0) { // Results!

                            // Display rental properties
                            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                                // Rental property per row
                    ?>
                                <div class="card" style="width: 18rem;">
                                    <div style="height:45%;">
                                        <img style="margin:auto;" src=<?php echo "../rental_property_photo/" . $row["photo"];?> class="card-img-top" alt="...">
                                    </div>
                                    <div class="card-body">
                                        <h5 style="height:50px;" class="card-title"><?php echo $row["address"]; ?></h5>
                                        <p style="height:50px;" class="card-text">
                                            Bedrooms Num: <?php echo $row["number_bedrooms"]; ?><br>
                                            Type: <?php echo $row["property_type_code"]; ?><br>
                                        </p>
                                        <p>CONTACT TO: <br><?php echo $row["landlord_email"]; ?></p>
                                    </div>
                                </div>  
                            <?php
                            }
                            ?>
            </div>
    </div>
<?php

                        } else {
                            // No rental properties found 
                        echo "No rental properties found.";
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

