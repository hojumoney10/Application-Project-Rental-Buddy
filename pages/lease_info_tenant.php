<!-- 
    Title:       lease_info_tenant.php
    Application: RentalBuddy
    Purpose:     Handles the lease info view for tenant
    Author:      S. Choi, Group 5, INFO-5139-01-21W
    Date:        February 13th, 2021 (February 13th, 2021) 
-->

<?php
    session_start();
    require_once('common.php');
    require_once('navigationMenu.php');
    require_once '../vendor/autoload.php';
?>

<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="Lease Information (tenant)">
    <meta name="author" content="Sung-Kyu Choi (Daniel)">
    <title>RentalBuddy - Lease Information (tenant)</title>

    <link rel="canonical" href="https://getbootstrap.com/docs/5.0/examples/starter-template/">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="../vendor/twbs/bootstrap/dist/css/bootstrap.min.css">

    <!-- Custom styles for this template -->
    <link href="../css/starter-template.css" rel="stylesheet">
</head>

<body>
    <form action="lease_info_tenant.php" method="POST">
    <div class="mb-3">
        <p>Input to check Lease Information</p>
        <label for="exampleInputEmail1" class="form-label">Your email (tenant)</label>
        <input type="email" class="form-control" id="email" name="email">
    </div>
    <button type="submit" class="btn btn-primary">Submit</button>
    </form>

    <!-- jQuery, Popper.js, then Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.4.1.slim.min.js" integrity="sha384-J6qa4849blE2+poT4WnyKhv5vZF5SrPo0iEjwBvKU7imGFAV0wwj1yYfoRSJoZ+n" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
    <script src="../vendor/twbs/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>

<?php
    // Get rental property information
    if ($_SERVER['REQUEST_METHOD'] == 'POST'){
        
        if (isset($_POST['email'])) {
            $tenant_email = htmlentities(stripslashes(trim($_POST['email'])));

            $db_conn = connectDB();

            $get_email = $db_conn->prepare('SELECT email FROM tenants WHERE email="' . $tenant_email . '";');
            if (!$get_email) {
                $err = 'Something went wrong!';
                exit(1);
            }

            $status1 = $get_email->execute();

            while ($row = $get_email->fetch(PDO::FETCH_ASSOC)) {
                foreach($row as $k => $v){
                    $the_email = $v;
                }
            }

            $get_tenant_id = $db_conn->prepare('SELECT tenant_id FROM tenants WHERE email="' . $tenant_email . '";');
            if (!$get_tenant_id) {
                $err = 'Something went wrong!';
                exit(1);
            }

            $status2 = $get_tenant_id->execute();

            while ($row = $get_tenant_id->fetch(PDO::FETCH_ASSOC)) {
                foreach($row as $k => $v){
                    $the_tenant_id = $v;
                }
            }

            $get_lease = $db_conn->prepare('SELECT 
                l.lease_id, 
                rp.listing_reference, 
                CONCAT(t.first_name, " ", t.last_name) as full_name,
                l.start_date,
                l.end_date,
                l.payment_day,
                l.payment_frequency_code, 
                l.base_rent_amount,
                l.parking_amount,
                l.other_amount,
                l.payable_to, 
                l.deposit_amount, 
                l.key_deposit, 
                l.payment_type_code, 
                l.include_electricity, 
                l.include_heat, 
                l.include_water, 
                l.insurancy_policy_number, 
                l.status_code, 
                l.last_updated, 
                l.last_updated_user_id
            
             FROM leases l 
             INNER JOIN rental_properties rp ON l.rental_property_id = rp.rental_property_id
             INNER JOIN tenants t ON l.tenant_id = t.tenant_id
             WHERE l.tenant_id="' . $the_tenant_id . '";');
            
            if (!$get_lease) {
                $err = 'Something went wrong!';
                exit(1);
            }
            
            $status3 = $get_lease->execute();
            
            while ($row = $get_lease->fetch(PDO::FETCH_ASSOC)) {

        ?>
                <table class="table">
                    <thead>
                        <tr>
                            <th scope="col">Column</th>
                            <th scope="col">Information</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <th scope="row">Lease ID</th>
                            <td><?php echo $row["lease_id"]; ?></td>
                        </tr>
                        <tr>
                            <th scope="row">Listing Reference</th>
                            <td><?php echo $row["listing_reference"]; ?></td>
                        </tr>
                        <tr>
                            <th scope="row">Tenant Name</th>
                            <td><?php echo $row["full_name"]; ?></td>
                        </tr>
                        <tr>
                            <th scope="row">Start Date</th>
                            <td><?php echo $row["start_date"]; ?></td>
                        </tr>
                        <tr>
                            <th scope="row">End Date</th>
                            <td><?php echo $row["end_date"]; ?></td>
                        </tr>
                        <tr>
                            <th scope="row">Payment Day</th>
                            <td>Day <?php echo $row["payment_day"]; ?></td>
                        </tr>
                        <tr>
                            <th scope="row">Payment Frequency</th>
                            <td><?php echo $row["payment_frequency_code"]; ?></td>
                        </tr>
                        <tr>
                            <th scope="row">Base Rent Amount</th>
                            <td>$<?php echo $row["base_rent_amount"]; ?></td>
                        </tr>
                        <tr>
                            <th scope="row">Parking Amount</th>
                            <td>$<?php echo $row["parking_amount"]; ?></td>
                        </tr>
                        <tr>
                            <th scope="row">Other Amount</th>
                            <td>$<?php echo $row["other_amount"]; ?></td>
                        </tr>
                        <tr>
                            <th scope="row">Payable To</th>
                            <td><?php echo $row["payable_to"]; ?></td>
                        </tr>
                        <tr>
                            <th scope="row">Deposit Amount</th>
                            <td>$<?php echo $row["deposit_amount"]; ?></td>
                        </tr>
                        <tr>
                            <th scope="row">Key Deposit</th>
                            <td>$<?php echo $row["key_deposit"]; ?></td>
                        </tr>
                        <tr>
                            <th scope="row">Payment Type</th>
                            <td><?php echo $row["payment_type_code"]; ?></td>
                        </tr>
                        <tr>
                            <th scope="row">Electricity ?</th>
                            <td><input type="checkbox" id="include-electricity" name="include-electricity" <?php echo ($row['include_electricity']) ? "checked" : ""; ?>></td>
                            
                        </tr>
                        <tr>
                            <th scope="row">Heat ?</th>
                            <td><input type="checkbox" id="include-heat" name="include-heat" <?php echo ($row['include_heat']) ? "checked" : ""; ?>></td>
                        </tr>
                        <tr>
                            <th scope="row">Water ?</th>
                            <td><input type="checkbox" id="include-water" name="include-water" <?php echo ($row['include_water']) ? "checked" : ""; ?>></td>
                        </tr>
                        <tr>
                            <th scope="row">Insurancy Policy Number</th>
                            <td><?php echo $row["insurancy_policy_number"]; ?></td>
                        </tr>
                        <tr>
                            <th scope="row">Status</th>
                            <td><?php echo $row["status_code"]; ?></td>
                        </tr>
                        <tr>
                            <th scope="row">Last Updated</th>
                            <td><?php echo $row["last_updated"]; ?></td>
                        </tr>
                        <tr>
                            <th scope="row">Last Updated User ID</th>
                            <td><?php echo $row["last_updated_user_id"]; ?></td>
                        </tr>
                    </tbody>
                    </table>
                    
                <?php
            }
            $db_conn = null;
        }
    }

?>