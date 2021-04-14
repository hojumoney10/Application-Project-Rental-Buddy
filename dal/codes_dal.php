<!-- 
    Title:       codes_dal.php
    Application: RentalBuddy
    Purpose:     Handles the codes table data access code
    Author:      G. Blandford, Group 5, INFO-5139-01-21W
    Date:        February 10th, 2021 (February 10th, 2021) 

    20210413    GPB Added getDiscountValue()
-->
<?php

// Load common
define('__ROOT__', dirname(__FILE__));
require_once("../pages/common.php");

// Get codes
function getCodes($code_type, $current_value = "") {

        
    // connect
    $dbc = connectDB();
    
    $qry = "select
                codes.code_id
                , codes.code_type
                , codes.code_value
                , codes.description
                , codes.is_default
                , codes.css_styling
                , codes.data_value_numeric
        from codes
                
        where codes.code_type = :code_type
        and codes.is_enabled = 1

        order by codes.sort_order, codes.code_id;";
    
    $stmt = $dbc->prepare($qry);
    if (!$stmt){
        echo "<p>Error in display prepare: ".$dbc->errorCode()."</p>\n<p>Message ".implode($dbc->errorInfo())."</p>\n";
        exit(1);
    }
    
    // set data, doing this way handles SQL injections
    $data = array(":code_type" => $code_type);
    
    $status = $stmt->execute($data);
    
    if ($status){
        if ($stmt->rowCount() > 0) {
            $found_current = 0;
            ?>
            
                <!-- <select id="<?php echo $code_type ?>" class="form-control" name="<?php echo $code_type ?>" required placeholder="Select <?php echo $code_type ?> type" aria-describedby="<?php echo $code_type ?>-help"> -->
    <?php while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){ 
        
    ?>
    
                        <option value="<?php echo $row['code_value'];?>" id="<?php 
                                                            echo $code_type . '-' . $row['description'];
                                                            ?>"
                                                            style="<?php 
                                                                if (!empty($row['css_styling'])) {
                                                                    echo $row['css_styling'];
                                                                 };
                                                            ?>"
                                                            data-discount="<?php 
                                                                echo $row['data_value_numeric'];                                             
                                                            ?>"
                        
            <?php
                if ($row['code_value'] == $current_value) {
                    echo ' selected'; 
                    $found_current = 1;
                } else if ($row['is_default'] == 1 && !$found_current) {
                    echo ' selected'; 
                } 
                ?>>
                            <?php echo $row['description'] ?>
                        </option>
    <?php } ?>
                <!-- </select> -->
<?php
		} 
	} else {
		echo "<p>Error in display execute ".$stmt->errorCode()."</p>\n<p>Message ".implode($stmt->errorInfo())."</p>\n";
		exit(1);
	}
}

// Get codes
function getDiscountValue($code_value) {
       
    // connect
    $dbc = connectDB();
    
    $qry = "select
                codes.data_value_numeric
        from codes
                
        where codes.code_type = 'discount_code'
        and codes.code_value = :code_value
        and codes.is_enabled = 1";
    
    $stmt = $dbc->prepare($qry);
    if (!$stmt){
        echo "<p>Error in display prepare: ".$dbc->errorCode()."</p>\n<p>Message ".implode($dbc->errorInfo())."</p>\n";
        exit(1);
    }
    
    // set data, doing this way handles SQL injections
    $data = array(":code_value" => $code_value);
    
    $status = $stmt->execute($data);
    $discount = 0;

    if ($status) {
        if ($stmt->rowCount() > 0) {
           $discount = $stmt->fetch(PDO::FETCH_ASSOC)['data_value_numeric'];
        }
    }
    return $discount;
}
?>