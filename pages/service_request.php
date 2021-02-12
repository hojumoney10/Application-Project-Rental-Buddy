<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="Mark Otto, Jacob Thornton, and Bootstrap contributors">
    <meta name="generator" content="Hugo 0.79.0">
    <title>Starter Template · Bootstrap v5.0</title>

    <link rel="canonical" href="https://getbootstrap.com/docs/5.0/examples/starter-template/">



    <!-- Bootstrap core CSS -->
    <link rel="stylesheet" href="../vendor/twbs/bootstrap/dist/css/bootstrap.min.css">

    <style>
    .bd-placeholder-img {
        font-size: 1.125rem;
        text-anchor: middle;
        -webkit-user-select: none;
        -moz-user-select: none;
        user-select: none;
    }

    @media (min-width: 768px) {
        .bd-placeholder-img-lg {
            font-size: 3.5rem;
        }
    }
    </style>


    <!-- Custom styles for this template -->
    <link href="../css/starter-template.css" rel="stylesheet">
    <?php
    include 'common.php';
    $db_conn = connectDB();
    $msg = "";

    // temporary value
    $tenant_id ='9999';
    $property_id='9998';
    ?>
</head>

<body>
    <div class="container">

        <?php
        require '../vendor/autoload.php';

        //define('_SERVER_PATH_', str_replace(basename(__FILE__), "", realpath(__FILE__)));
        include 'navigationMenu.php';

        dump($_POST);

        // input page
        if (isset($_POST['request'])) {
            inputPage();
        }
        // write function
        else if (isset($_POST['submit'])) {
            if ($_POST['reqType'] != 'Request Type') {
                insertRequest();
            } else {
                $msg = "Please select Request Type";
                msgHeader('red');
                inputPage();
            }
        }
        // list page
        else {
            viewPage();
        }

        function insertRequest()
        {
            global $db_conn;
            global $msg;
            $stmt = $db_conn->prepare("INSERT INTO requests (rental_property_id, tenant_id, request_type_code, description, status_code, priority_code, last_updated_user_id) values(?, ?, ?, ?, ?, ?, ?)");
            try {
                $array = [
                    $_POST['rentalId'],
                    $_POST['tenantId'],
                    $_POST['reqType'],
                    $_POST['reqContent'],
                    'new',
                    'medium',
                    'test_user_id'
                ];
                $db_conn->beginTransaction();
                $stmt->execute($array);
                $db_conn->commit();

                $msg="Request has benn inserted.";
                msgHeader('green');
                viewPage();

            } catch (Exception $e) {
                $db_conn->rollback();
                echo $e->getMessage();
            }
        }


        function loadCode($codeId)
        {

            global $db_conn;
            $results = [];
            $stmt = $db_conn->prepare("Select code_id, code_value, description from codes where code_type='" . $codeId . "' and is_enabled = 1");
            try {
                $stmt->execute();
                while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                    $tmp = [
                        $row['code_id'],
                        $row['code_value'],
                        $row['description']
                    ];
                    array_push($results, $tmp);
                }
                return $results;
            } catch (Exception $e) {
                $db_conn->rollback();
                echo $e->getMessage();
            }
        }

        function msgHeader($type)
        {
            global $msg;
            if (strlen($msg) > 0 && $type == 'green') {
                if (strlen($msg) > 0) {
                    $header = "<div class=\"alert alert-success\" role=\"alert\">" . $msg . "</div>";
                    echo $header;
                }
            } else if (strlen($msg) > 0 && $type == 'red') {
                $header = "<div class=\"alert alert-danger\" role=\"alert\">" . $msg . "</div>";
                echo $header;
            }
            unset($msg);
            unset($header);
        }

        function inputPage()
        {
            global $tenant_id;
            global $property_id;
            $html = '';
            $requestCodes = loadCode('request_type');
        ?>
        <form method="POST">
            <div class="row mb-3">
                <h3>Contact Info</h3>
                <h6>Please check your contact information, if you need, you can change your information in manage page.
                </h6>
                <h6>After Sprint2 or 3, we need to change this form that read data from session and user database</h6>
            </div>
            <div class="row mb-3">
                <!-- Tenant ID and rental_property_id is hidden -->
                <div class="col-sm-10">
                    <input type="hidden" class="form-control" id="tenantId" name='tenantId' value= <?php echo $tenant_id; ?>>
                    <input type="hidden" class="form-control" id="rentalId" name='rentalId' value= <?php echo $property_id; ?>>
                </div>
            </div>
            <div class="row mb-3">
                <label for="name" class="col-sm-2 col-form-label">Tenant Name</label>
                <div class="col-sm-10">
                    <input type="text" class="form-control" id="name" name='tenantName' placeholder="Test Name"
                        disabled>
                </div>
            </div>
            <div class="row mb-3">
                <label for="inputEmail3" class="col-sm-2 col-form-label">Tenant Email</label>
                <div class="col-sm-10">
                    <input type="email" class="form-control" id="inputEmail" name='tenantEmail'
                        placeholder="taehyungkim@outlook.com" disabled>
                </div>
            </div>
            <div class="row mb-3">
                <label for="phone" class="col-sm-2 col-form-label">Tenant Phone</label>
                <div class="col-sm-10">
                    <div class="row">
                        <div class="col-md">
                            <input type="text" class="form-control" id="phone" name='phone1' maxlength="3"
                                placeholder="123" disabled>
                        </div>-
                        <div class="col-md">
                            <input type="text" class="form-control" id="phone" name='phone2' maxlength="4"
                                placeholder="4567" disabled>
                        </div>-
                        <div class="col-md">
                            <input type="text" class="form-control" id="phone" name='phone3' maxlength="4"
                                placeholder="7890" disabled>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row mb-3">
                <h3>Request Maintenance</h3>
            </div>
            <div class="row mb-3">
                <label for="reqType" class="col-sm-2 col-form-label">Request Type</label>
                <div class="col-sm-10">
                    <select class="form-select" aria-label="reqType" name='reqType'>
                        <option selected>Request Type</option>
                        <?php
                            foreach ($requestCodes as $v1) {
                                $html .= '<option value="' . $v1[0] . '">' . $v1[1] . '</option>';
                            }
                            echo $html;
                            unset($html);
                            ?>
                    </select>
                </div>
            </div>
            <div class="row mb-3">
                <label for="content" class="col-sm-2 col-form-label">Request Content</label>
                <div class="col-sm-10">
                    <textarea class="form-control" id="reqContent" name='reqContent' rows="3"></textarea>
                </div>
            </div>

            <button type="submit" class="btn btn-primary" name="submit">Request</button>
        </form>

        <?php
        }

        function viewPage()
        {

        ?>
        <form method="POST">
            <div class="btn-group" role="group" aria-label="Basic mixed styles example">
                <button type="button" class="btn btn-danger">Left</button>
                <button type="button" class="btn btn-warning">Middle</button>
                <button type="submit" class="btn btn-success" id="request" name="request">Request
                    Maintenance</button>
            </div>
        </form>

        <?php
            global $db_conn;
            global $tenant_id;
            global $property_id;

            $html="";
            $stmt = $db_conn->prepare("Select r.request_id, r.request_date, r.rental_property_id, r.tenant_id, c.code_value, r.status_code, r.priority_code, r.last_updated
            from requests r 
            join codes c 
            on c.code_id = r.request_type_code
            
            where r.tenant_id='".$tenant_id."' and r.rental_property_id = ".$property_id);

            try {
                $html ="<table class='table table-striped table-hover'>
                <thead>
                  <tr>
                    <th scope='col'>#</th>
                    <th scope='col'>Request Type</th>
                    <th scope='col'>Request Date</th>
                    <th scope='col'>Status</th>
                    <th scope='col'>Priority</th>
                    <th scope='col'>Last Updated</th>
                  </tr>
                </thead>
                <tbody>
                  ";
                $stmt->execute();
                while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {

                    $html .= "<tr>
                    <th scope='row'>
                        <form id='requestTable".$row['request_id']."' method='POST'>
                            <a href='javascript:;' onclick='document.getElementById(\"requestTable".$row['request_id']."\").submit();'>".$row['request_id']."</a>
                            <input type='hidden' name='requestId' value=".$row['request_id'].">
                        </form>
                    </th>
                    <td>".$row['code_value']."</td>
                    <td>".$row['request_date']."</td>
                    <td>".$row['status_code']."</td>
                    <td>".$row['priority_code']."</td>
                    <td>".$row['last_updated']."</td></tr>";
                }
                $html .= "</tbody></table>";
                echo $html;
            } catch (Exception $e) {
                $db_conn->rollback();
                echo $e->getMessage();
            }
        }

        ?>
    </div>
    <script src="../vendor/twbs/bootstrap/dist/js/bootstrap.bundle.min.js"></script>


</body>

</html>