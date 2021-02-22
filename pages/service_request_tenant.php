<!-- 
    Title:       service_request_tenant.php
    Application: RentalBuddy
    Purpose:     Handles the service request view for tenant
    Author:      S. Choi, Group 5, INFO-5139-01-21W
    Date:        February 13th, 2021 (February 13th, 2021) 


    *** This file has been REPLACED BY service_request.php ***
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
    <title>RentalBuddy - View All Service Requests (tenant)</title>

    <link rel="canonical" href="https://getbootstrap.com/docs/5.0/examples/starter-template/">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="../vendor/twbs/bootstrap/dist/css/bootstrap.min.css">

    <!-- Custom styles for this template -->
    <link href="../css/starter-template.css" rel="stylesheet">
</head>

<body>
    <form action="service_request_tenant.php" method="POST">
    <div class="mb-3">
        <p>Input to view All Service Requests</p>
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
    // Get service request information
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
                    console_log($v);
                    $the_email = $v;
                }
            }

            $get_tenant_id = $db_conn->prepare('SELECT tenant_id FROM tenants WHERE email="' . $the_email . '";');
            if (!$get_tenant_id) {
                $err = 'Something went wrong!';
                exit(1);
            }

            $status2 = $get_tenant_id->execute();

            while ($row = $get_tenant_id->fetch(PDO::FETCH_ASSOC)) {
                foreach($row as $k => $v){
                    console_log($v);
                    $the_tenant_id = $v;
                }
            }

            $get_requests = $db_conn->prepare('SELECT 
                r.request_id,
                r.request_date,
                r.rental_property_id,
                CONCAT(t.first_name, " ", t.last_name) as full_name,
                c.description as request_type,
                r.description,
                c1.description as status_code,
                c2.description as priority_code, 
                r.last_updated,
                r.last_updated_user_id
                
             FROM requests r 
             INNER JOIN tenants t ON r.tenant_id = t.tenant_id
             INNER JOIN codes c ON c.code_id = r.request_type_code
             INNER JOIN codes c1 ON c1.code_id = r.status_code
             INNER JOIN codes c2 ON c2.code_id = r.priority_code
             WHERE r.tenant_id="' . $the_tenant_id . '" ORDER BY r.request_date DESC;');
            
            if (!$get_requests) {
                $err = 'Something went wrong!';
                exit(1);
            }
            
            $status3 = $get_requests->execute();
            
            while ($row = $get_requests->fetch(PDO::FETCH_ASSOC)) {

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
                            <th scope="row">Request ID</th>
                            <td><?php echo $row["request_id"]; ?></td>
                        </tr>
                        <tr>
                            <th scope="row">Request Date</th>
                            <td><?php echo $row["request_date"]; ?></td>
                        </tr>
                        <tr>
                            <th scope="row">Rental Property ID</th>
                            <td><?php echo $row["rental_property_id"]; ?></td>
                        </tr>
                        <tr>
                            <th scope="row">Tenant Name</th>
                            <td><?php echo $row["full_name"]; ?></td>
                        </tr>
                        <tr>
                            <th scope="row">Request Type</th>
                            <td><?php echo $row["request_type"]; ?></td>
                        </tr>
                        <tr>
                            <th scope="row">Description</th>
                            <td><?php echo $row["description"]; ?></td>
                        </tr>
                        <tr>
                            <th scope="row">Status</th>
                            <td><?php echo $row["status_code"]; ?></td>
                        </tr>
                        <tr>
                            <th scope="row">Priority</th>
                            <td><?php echo $row["priority_code"]; ?></td>
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