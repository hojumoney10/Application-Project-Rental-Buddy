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
function getLandlords() {

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
        inner join codes status_codes on status_codes.code_value = l.status_code  and status_codes.code_type = 'landlord_status'
                                   
        order by l.landlord_id;";
    
    // assign values from params
    // $data = array(":field" => $param1);
    
    // prepare query
    $stmt = $db_conn->prepare($querySQL);
    
    // prepare error check
    if(!$stmt) {
        echo "<p>Error: ".$db_conn->errorCode()."<br>Message: ".implode($db_conn->errorInfo())."</p><br>";
        exit(1);
    }
    
    // execute query in database
    // $status = $stmt->execute($data);
    $status = $stmt->execute();
    
    if($status) { // no error

        if($stmt->rowCount() > 0) { // Results!

            // Display landlords
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {  

                // Landlord per row
                echo '<tr>
                            <td>' . $row["landlord_id"] . '</td>
                            <td>' . $row["legal_name"] . '</td>
                            <td>' . formatPhone( $row["phone"] ) . '</td>
                            <td>' . $row["email"] . '</td>
                            <td>' . $row["status_code"] . '</td>
                            <td>
                                <form action="" method="post">
                                    <button type="submit" name="landlord_id" value="' . $row["landlord_id"] . '" class="btn btn-info fa fa-edit" data-toggle="popover" title="Edit"></button>
                                </form>
                            </td>
                        </tr>';
            }

        } else {

                // No landlords found 
                echo '<tr>
                        <td></td>
                        <td>No landlords found.</td>
                    </tr>';
        }

    } else {
        // execute error
        echo "<p>Error: ".$stmt->errorCode()."<br>Message: ".implode($stmt->errorInfo())."</p><br>";
        
        // close database connection
        $db_conn = null;
        
        exit(1);
    }
     // close database connection
    $db_conn = null;
}
?>