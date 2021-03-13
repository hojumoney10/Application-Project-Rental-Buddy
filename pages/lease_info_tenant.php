<?php
    session_start();
    include_once("./check_session.php");

    require_once('common.php');
?>
<!-- 
    Title:       lease_info_tenant.php
    Application: RentalBuddy
    Purpose:     Handles the lease info view for tenant
    Author:      S. Choi, Group 5, INFO-5139-01-21W
    Date:        March 7th, 2021 (February 13th, 2021) 

    20210222     GPB    Corrected JOINS to codes tables and provided more user-friendly aliases
                        Moved navigationMenu to inside <body>
    20210307     GPB    Check user logged in    
    20210311     GPB    Added bootstrap icons link

-->

<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="Lease Information (tenant)">
    <meta name="author" content="Sung-Kyu Choi (Daniel)">
    <title>RentalBuddy - Lease Information (Tenant)</title>

    <link rel="canonical" href="https://getbootstrap.com/docs/5.0/examples/starter-template/">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="../vendor/twbs/bootstrap/dist/css/bootstrap.min.css">

    <!-- Custom styles for this template -->
    <link href="../css/starter-template.css" rel="stylesheet">
</head>

<body>
<?php
    require_once('navigationMenu.php');
    require_once '../vendor/autoload.php';
    ?>

    <div class="container">
        
        <?php

        // Get rental property information
        $db_conn = connectDB();

        $userRole = checkUserRoleCode($_SESSION['CURRENT_USER']['user_id']);

        if ($userRole == 'tenant') {
            $user_id = $_SESSION['CURRENT_USER']['user_id'];
            $tenant_id = checkTenantId($user_id);

            // SQL query
            $get_lease = 'SELECT 
                l.lease_id, 
                rp.listing_reference, 
                CONCAT(t.first_name, " ", t.last_name) as full_name,
                l.start_date,
                l.end_date,
                frequency_codes.description as payment_frequency,				
                l.payment_day,
                l.base_rent_amount,
                l.parking_amount,
                l.other_amount,
                l.payable_to, 
                l.deposit_amount, 
                l.key_deposit, 

                l.include_electricity, 
                l.include_heat, 
                l.include_water, 
                l.insurancy_policy_number, 
                l.last_updated, 
                l.last_updated_user_id
                
                FROM leases l 
                INNER JOIN rental_properties rp ON l.rental_property_id = rp.rental_property_id
                INNER JOIN tenants t ON l.tenant_id = t.tenant_id
                INNER JOIN codes frequency_codes on l.payment_frequency_code = frequency_codes.code_value and frequency_codes.code_type = "payment_frequency"
                INNER JOIN codes payment_types on l.payment_type_code = payment_types.code_value and payment_types.code_type = "payment_type"
                INNER JOIN codes status_codes on l.status_code = status_codes.code_value and status_codes.code_type = "lease_status"	

                WHERE l.tenant_id=  :tenant_id';

            $data = array(":tenant_id" => $tenant_id);

            $stmt = $db_conn->prepare($get_lease);
                
            if (!$stmt) {
                $err = 'Something went wrong!';
                exit(1);
            }
                
            $status = $stmt->execute($data);
                
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {

        ?>
                <h3>View My Lease Information</h3>

                <div class="border" style="background-color: #ffffdd;">
                    <div class="row" style="padding-top: 15px;">
                        <div class="col-sm ps-4">
                            <p class="fw-bold" style="color: #006600;">Basic Details</p>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm ps-4">
                            <p class="fw-bold">Lease Reference</p>
                        </div>
                        <div class="col-sm ps-4">
                            <p><?php echo $row["listing_reference"]; ?></p>
                        </div>
                        <div class="col-sm ps-4">
                            <p class="fw-bold">Start Date</p>
                        </div>
                        <div class="col-sm ps-4">
                            <p><?php echo $row["start_date"]; ?></p>
                        </div>
                        <div class="col-sm ps-4">
                            <p class="fw-bold">End Date</p>
                        </div>
                        <div class="col-sm ps-4">
                            <p><?php echo $row["end_date"]; ?></p>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-sm ps-4">
                            <p class="fw-bold" style="color: #006600;">Payment Details</p>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm ps-4">
                            <p class="fw-bold">Payment Day</p>
                        </div>
                        <div class="col-sm ps-4">
                            <p>Day <?php echo $row["payment_day"]; ?></p>
                        </div>
                        <div class="col-sm ps-4">
                            <p class="fw-bold">Payment Frequency</p>
                        </div>
                        <div class="col-sm ps-4">
                            <p><?php echo $row["payment_frequency"]; ?></p>
                        </div>
                        <div class="col-sm ps-4">
                            <p class="fw-bold">Payable To</p>
                        </div>
                        <div class="col-sm ps-4">
                            <p><?php echo $row["payable_to"]; ?></p>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm ps-4">
                            <p class="fw-bold">Base Rent Amount</p>
                        </div>
                        <div class="col-sm ps-4">
                            <p>$<?php echo $row["base_rent_amount"]; ?></p>
                        </div>
                        <div class="col-sm ps-4">
                            <p class="fw-bold">Parking Amount</p>
                        </div>
                        <div class="col-sm ps-4">
                            <p>$<?php echo $row["parking_amount"]; ?></p>
                        </div>
                        <div class="col-sm ps-4">
                            <p class="fw-bold">Other Amount</p>
                        </div>
                        <div class="col-sm ps-4">
                            <p>$<?php echo $row["other_amount"]; ?></p>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm ps-4">
                            <p class="fw-bold">Deposit Amount</p>
                        </div>
                        <div class="col-sm ps-4">
                            <p>$<?php echo $row["deposit_amount"]; ?></p>
                        </div>
                        <div class="col-sm ps-4">
                            <p class="fw-bold">Key Deposit</p>
                        </div>
                        <div class="col-sm ps-4">
                            <p>$<?php echo $row["key_deposit"]; ?></p>
                        </div>
                        <div class="col-sm ps-4">
                            <p class="fw-bold">Payment Type</p>
                        </div>
                        <div class="col-sm ps-4">
                            <p><?php echo $row["payment_type"]; ?></p>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-sm ps-4">
                            <p class="fw-bold" style="color: #006600;">Other Details</p>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm ps-4">
                            <p class="fw-bold">Electricity</p>
                        </div>
                        <div class="col-sm ps-4">
                            <input type="checkbox" id="include-electricity" name="include-electricity" <?php echo ($row['include_electricity']) ? "checked" : ""; ?>>
                        </div>
                        <div class="col-sm ps-4">
                            <p class="fw-bold">Heat</p>
                        </div>
                        <div class="col-sm ps-4">
                            <input type="checkbox" id="include-heat" name="include-heat" <?php echo ($row['include_heat']) ? "checked" : ""; ?>>
                        </div>
                        <div class="col-sm ps-4">
                            <p class="fw-bold">Water</p>
                        </div>
                        <div class="col-sm ps-4">
                            <input type="checkbox" id="include-water" name="include-water" <?php echo ($row['include_water']) ? "checked" : ""; ?>>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm ps-4">
                            <p class="fw-bold">Insurancy Policy #</p>
                        </div>
                        <div class="col-sm ps-4">
                            <p><?php echo $row["insurancy_policy_number"]; ?></p>
                        </div>
                        <div class="col-sm ps-4">
                            <p class="fw-bold">Status</p>
                        </div>
                        <div class="col-sm ps-4">
                            <p><?php echo $row["lease_status"]; ?></p>
                        </div>
                        <div class="col-sm ps-4"></div>
                        <div class="col-sm ps-4"></div>
                    </div>
                </div>
        <?php
            }
            $db_conn = null;
        }
        ?>
    </div>

    <!-- jQuery, Popper.js, then Bootstrap JS -->
    <!-- <script src="https://code.jquery.com/jquery-3.4.1.slim.min.js" integrity="sha384-J6qa4849blE2+poT4WnyKhv5vZF5SrPo0iEjwBvKU7imGFAV0wwj1yYfoRSJoZ+n" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script> -->
    <script src="../vendor/twbs/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
    <link href="../node_modules/bootstrap-icons/font//bootstrap-icons.css" rel="stylesheet">
</body>

</html>