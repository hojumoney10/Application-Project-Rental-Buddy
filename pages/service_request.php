<!-- 
    Title:       service_request.php
    Application: RentalBuddy
    Purpose:     Handling Service request 
    Author:      T. Kim, Group 5, INFO-5139-01-21W
    Date:        February 10th, 2021 
-->
<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="Mark Otto, Jacob Thornton, and Bootstrap contributors">
    <meta name="generator" content="Hugo 0.79.0">
    <title>RentalBuddy:Service Request</title>

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

    // temporary value///////////////////////
    $tenant_id = '1';
    $property_id = '9998';
    $user_id = 'tenant'
    /////////////////////////////////////////


    ?>
</head>

<body>
    <div class="container">

        <?php
        require '../vendor/autoload.php';

        //define('_SERVER_PATH_', str_replace(basename(__FILE__), "", realpath(__FILE__)));
        include 'navigationMenu.php';

        dump($_POST);

        // input form page
        if (isset($_POST['request'])) {
            inputPage();
        }
        // write function
        else if (isset($_POST['submit'])) {
            if ($_POST['reqType'] != 'Request Type'&&$_POST['reqContent'] != '') {
                insertRequest();
            } else {
                if($_POST['reqType'] == 'Request Type'){
                    $msg = "Please select Request Type";
                }else if($_POST['reqContent'] == ''){
                    $msg = "Please insert Request description";
                }
                msgHeader('red');
                inputPage();
            }
            // view detail page
        } else if (isset($_POST['requestId'])) {
            showRequestDetail();
        } 
            // solution update
        else if (isset($_POST['solutionSubmit'])) {
            if ($_POST['solType'] != 'Solution Type'&&$_POST['solContent']!='') {
                updateSolution();
            } else {
                if($_POST['solType'] == 'Solution Type'){
                    $msg = "Please select Solution Type";
                } else if($_POST['solContent']==''){
                    $msg = "Please insert Solution description";
                }
                msgHeader('red');
                //viewPage();
                showRequestDetail($_POST['request_id']);
            }
        }
        // change priority or status
        else if (isset($_POST['priority'])) {
            $key = array_keys($_POST);
            updateRequest($key[3], 'priority');
        } else if (isset($_POST['status'])) {
            $key = array_keys($_POST);
            updateRequest($key[3], 'status');
        }
        // view list page
        else {
            viewPage();
        }



        function insertRequestDetail($requestId, $desc){
            global $db_conn;
            global $user_id;

            $stmt = $db_conn->prepare("INSERT INTO requests_detail (request_id, description, create_user_id, last_user_id) values(?, ?, ?, ?)");
            try {
                $array = [
                    $requestId,
                    $desc,
                    $user_id,
                    $user_id
                ];

                $db_conn->beginTransaction();
                $stmt->execute($array);
                $db_conn->commit();
            } catch (Exception $e) {
                $db_conn->rollback();
                echo $e->getMessage();
            }
        }



        function updateRequest($value, $type)
        {
            global $db_conn;
            global $msg;
            if ($type == 'priority') {
                $stmt = $db_conn->prepare("select code_id from codes where code_type='request_priority' and code_value ='" . $value . "' and is_enabled='1'");
            } else if ($type == 'status') {
                $stmt = $db_conn->prepare("select code_id from codes where code_type='request_status' and code_value ='" . $value . "' and is_enabled='1'");
            }
            try {
                $stmt->execute();
                while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                    $code_id = $row['code_id'];
                }
            } catch (Exception $e) {
                $db_conn->rollback();
                echo $e->getMessage();
            }

            unset($sql);
            $sql = "UPDATE requests SET";
            if ($type == 'priority') {
                $data = ['priorityCode' => $code_id];
                $sql .= " priority_code=:priorityCode";
            } else if ($type == 'status') {
                $data = ['statusCode' => $code_id];
                $sql .= " status_code=:statusCode";
            }

            $data['updateTime'] = date("Y-m-d H:i:s");
            $data['userId'] = $_POST['user_id'];
            $data['requestId'] = $_POST['request_id'];
            $sql .= ", last_updated=:updateTime, last_updated_user_id=:userId WHERE request_id=:requestId";
            try {
                $stmt = $db_conn->prepare($sql);
                $stmt->execute($data);
                $msg = $type . " has been updated.";
                msgHeader('green');

                //update request detail
                insertRequestDetail($_POST['request_id'], $type.' is changed to '.$value);

                viewPage();
            } catch (Exception $e) {
                $db_conn->rollback();
                echo $e->getMessage();
            }
            

        }

        function updateSolution()
        {
            global $db_conn;
            global $user_id;
            global $msg;
            $data = [
                'solutionType' => $_POST['solType'],
                'solContent' => $_POST['solContent'],
                'requestId' => $_POST['request_id'],
                'updateTime' => date("Y-m-d H:i:s"),
                'userId' => $user_id
            ];
            try {
                $sql = "UPDATE requests SET solution_code=:solutionType, solution_description=:solContent, solution_date=:updateTime, last_updated=:updateTime, last_updated_user_id=:userId  WHERE request_id=:requestId";
                $stmt = $db_conn->prepare($sql);
                $stmt->execute($data);
                $msg = "Solution has been updated.";
                msgHeader('green');
                viewPage();
            } catch (Exception $e) {
                $db_conn->rollback();
                echo $e->getMessage();
            }
        }

        function showRequestDetail($reqId ="")
        {
            if($reqId ==""){
                $reqId = $_POST['requestId'];
            }
            global $db_conn;
            global $tenant_id;
            global $property_id;

            $tenants = loadTenantsInfo();

            $html = "";
            $stmt = $db_conn->prepare("Select r.request_id, r.request_date, r.rental_property_id, r.tenant_id, c.description as 'typeValue', r.status_code, r.priority_code, r.last_updated, r.description, r.solution_description, r.solution_date, r.solution_code, p.description as 'priorityValue', s.description as 'statusValue'
            
            from requests r 
            join codes c 
            join codes p
            join codes s
            on c.code_id = r.request_type_code and p.code_id = r.priority_code and s.code_id = r.status_code
            
            where r.tenant_id='" . $tenant_id . "' and r.rental_property_id = " . $property_id . " and r.request_id =" . $reqId );
            try {
                $stmt->execute();
                while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        ?>
        <h3>Request Detail</h3>
        <div class="border" style="background-color: #ffffdd;">

            <div class="row">
                <div class="col-sm ps-4 pt-3">
                    <p class="text-start">Requested by <?php echo $tenants[1] . " " . $tenants[2] ?> almost
                        <?php echo (format_date(strtotime($row['request_date']))) ?>. Updated about
                        <?php echo (format_date(strtotime($row['last_updated']))) ?><br>
                        <?php echo $tenants[3] . " " . $tenants[4] . " " . $tenants[5] . " " . $tenants[6] . " " . $tenants[7] ?>
                    </p>
                </div>
            </div>

            <div class="row">
                <div class="col-sm ps-4">
                    <p class="fw-bold">Request Type</p>
                </div>

                <div class="col-sm">
                    <span class="badge bg-primary"><?php echo $row['typeValue'] ?></span>
                </div>

                <div class="col-sm ps-4">
                    <p class="fw-bold">Priority</p>
                </div>

                <div class="col-sm">
                    <span class="badge bg-warning text-dark"><?php echo $row['priorityValue'] ?></span>
                </div>

                <div class="col-sm ps-4">
                    <p class="fw-bold">Status</p>
                </div>

                <div class="col-sm">
                    <span class="badge bg-info text-dark"><?php echo $row['statusValue'] ?></span>
                </div>
            </div>

            <div class="row">
                <div class="col-sm ps-4 pe-4">
                    <hr>
                    <p class="fw-bold">Description</p>
                </div>
            </div>

            <div class="row">
                <div class="col-sm ps-4 pe-4 pb-4">
                    <pre><?php echo $row['description'] ?></pre>
                </div>
            </div>

        </div>

        <?php if ($row['status_code'] != 'completed') {
                        global $user_id;
                        $priorities = loadCode('request_priority');
                        $status = loadCode('request_status');
                    ?>
        <br>

        <div class="row mb-3">


            <label for="priority" class="col-sm-2 col-form-label">Change Priority</label>
            <div class="btn-group col-sm-4" role="group" id="priority" aria-label="PriorityChange">
                <form method="POST">
                    <input type="hidden" class="form-control" id="request_id" name='request_id'
                        value=<?php echo $reqId;  ?>>
                    <input type="hidden" class="form-control" id="user_id" name='user_id' value=<?php echo $user_id; ?>>
                    <input type="hidden" class="form-control" id="user_id" name='priority' value=''>
                    <?php foreach ($priorities as $v1) {
                                        $html = "<button type='submit' class='btn btn-outline-primary' name='" . $v1[1] . "'>" . $v1[2] . "</button>";
                                        echo $html;
                                    } ?>
                </form>
            </div>

            <label for="status" class="col-sm-2 col-form-label">Change Status </label>
            <div class="btn-group col-sm-4" id="status" role="group" aria-label="StatusChange">
                <form method="POST">
                    <input type="hidden" class="form-control" id="request_id" name='request_id'
                        value=<?php echo $reqId;  ?>>
                    <input type="hidden" class="form-control" id="user_id" name='user_id' value=<?php echo $user_id; ?>>
                    <input type="hidden" class="form-control" id="user_id" name='status' value=''>
                    <?php foreach ($status as $v1) {
                                        $html = "<button type='submit' class='btn btn-outline-primary' name='" . $v1[1] . "'>" . $v1[2] . "</button>";
                                        echo $html;
                                        unset($html);
                                    } ?>
                </form>
            </div>

        </div>

        <?php
                    } ?>
        <br>


        <h3 class="pt-4">Solution</h3>
        <!-- It depends on the exist of solution information from database.  -->
        <?php
                    if (strlen($row['solution_code']) > 0) {
                        // Show solution content
                    ?>
        <div class="border" style="background-color: #ffffdd;">

            <div class="row">
                <div class="col-sm ps-4 pt-3">
                    <p class="text-start">Completed almost
                        <?php echo (format_date(strtotime($row['solution_date']))) ?>.</p>
                </div>
            </div>

            <div class="row">
                <div class="col-sm ps-4">
                    <p class="fw-bold">Solution Type</p>
                </div>

                <div class="col-sm">
                    <span class="badge bg-success"><?php echo selectCodeValue($row['solution_code']); ?></span>
                </div>
                <div class="col-sm">

                </div>
                <div class="col-sm">

                </div>
            </div>

            <div class="row">
                <div class="col-sm ps-4 pe-4">
                    <hr>
                    <p class="fw-bold">Solution description</p>
                </div>
            </div>

            <div class="row">
                <div class="col-sm ps-4 pe-4 pb-4">
                    <pre><?php echo $row['solution_description'] ?></pre>
                </div>
            </div>

        </div>


        <?php
                    } else {
                        // Show solution insert form
                        $solutionCodes = loadCode('request_solution');
                    ?>
        <form method="POST">
            <div class="row mb-3">
                <label for="reqType" class="col-sm-2 col-form-label">Solution Type</label>
                <div class="col-sm-10">
                    <select class="form-select" aria-label="solType" name='solType'>
                        <option selected>Solution Type</option>
                        <?php
                                        foreach ($solutionCodes as $v1) {
                                            $html .= '<option value="' . $v1[0] . '">' . $v1[1] . '</option>';
                                        }
                                        echo $html;
                                        unset($html);
                                        unset($solutionCodes);
                                        ?>
                    </select>
                </div>
            </div>
            <div class="row mb-3">
                <label for="content" class="col-sm-2 col-form-label">Solution Description</label>
                <div class="col-sm-10">
                    <textarea class="form-control" id="solContent" name='solContent' rows="3"></textarea>
                </div>
            </div>
            <input type="hidden" class="form-control" id="request_id" name='request_id'
                value=<?php echo $reqId  ?>>
            <div class="d-flex justify-content-end">
                <button type="submit" class="btn btn-primary" name="solutionSubmit">Update</button>
            </div>
        </form>
        <?php

                    }
                    ?>
        <?php
                }
            } catch (Exception $e) {
                $db_conn->rollback();
                echo $e->getMessage();
            }

            // History
            $stmt = $db_conn->prepare("Select description, create_date, create_user_id, last_updated_date, last_user_id
            from requests_detail
            where request_id=" . $reqId ." order by request_detail_id" );

            try {
                $stmt->execute();
                while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {

                    $html.="<br><div class=\"card\">
                    <div class=\"card-header\">
                      Created at ".$row['create_date'].", Last Modified ".$row['last_updated_date']."
                    </div>
                    <div class=\"card-body\">
                      <h5 class=\"card-title\">".$row['description']."</h5>
                      <p class=\"card-text\">Created by ".$row['create_user_id'].", Last Modified ".$row['last_user_id']."</p>
                    </div>
                  </div>
                  ";
                }
                echo $html;
            }catch (Exception $e) {
                $db_conn->rollback();
                echo $e->getMessage();
            }



        }

        function insertRequest()
        {
            global $db_conn;
            global $msg;
            global $user_id;
            $stmt = $db_conn->prepare("INSERT INTO requests (rental_property_id, tenant_id, request_type_code, description, status_code, priority_code, last_updated_user_id) values(?, ?, ?, ?, ?, ?, ?)");
            try {
                $array = [
                    $_POST['rentalId'],
                    $_POST['tenantId'],
                    $_POST['reqType'],
                    $_POST['reqContent'],
                    '60',
                    '65',
                    $user_id
                ];

                $db_conn->beginTransaction();
                $stmt->execute($array);
                $db_conn->commit();

                $msg = "Request has benn inserted.";
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
            $stmt = $db_conn->prepare("Select code_id, code_value, description from codes where code_type='" . $codeId . "' and is_enabled = 1 Order by sort_order");
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
        function loadTenantsInfo()
        {
            global $db_conn;
            global $user_id;
            $stmt = $db_conn->prepare("select t.salutation_code, t.first_name, t.last_name, t.address_1, t.address_2, t.city, t.province_code, t.postal_code, t.phone, t.email 
            from tenants t
            join users u
            on u.tenant_id = t.tenant_id
            where u.user_id='" . $user_id . "'");
            try {
                $stmt->execute();
                while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                    $tmp = [
                        $row['salutation_code'],
                        $row['first_name'],
                        $row['last_name'],
                        $row['address_1'],
                        $row['address_2'],
                        $row['city'],
                        $row['province_code'],
                        $row['postal_code'],
                        $row['phone'],
                        $row['email']

                    ];

                    return $tmp;
                }
            } catch (Exception $e) {
                $db_conn->rollback();
                echo $e->getMessage();
            }
        }

        function inputPage()
        {
            global $tenant_id;
            global $property_id;
            $html = '';
            $requestCodes = loadCode('request_type');
            // tenant information load
            $tenants = loadTenantsInfo();
            ?>
        <form method="POST">
            <div class="row mb-3">
                <h3>Contact Info</h3>
                <h6>Please check your contact information, if you need, you can change your information in manage page.
                </h6>
            </div>
            <div class="row mb-3">
                <!-- Tenant ID and rental_property_id is hidden -->
                <div class="col-sm-10">
                    <input type="hidden" class="form-control" id="tenantId" name='tenantId'
                        value=<?php echo $tenant_id; ?>>
                    <input type="hidden" class="form-control" id="rentalId" name='rentalId'
                        value=<?php echo $property_id; ?>>
                </div>
            </div>
            <div class="row mb-3">
                <label for="name" class="col-sm-2 col-form-label">Tenant Name</label>
                <div class="col-sm-10">
                    <input type="text" class="form-control" id="name" name='tenantName'
                        placeholder="<?php echo $tenants['1'] . " " . $tenants['2']; ?>" disabled>
                </div>
            </div>
            <div class="row mb-3">
                <label for="inputEmail3" class="col-sm-2 col-form-label">Tenant Email</label>
                <div class="col-sm-10">
                    <input type="email" class="form-control" id="inputEmail" name='tenantEmail'
                        placeholder="<?php echo $tenants[9]; ?>" disabled>
                </div>
            </div>
            <div class="row mb-3">
                <label for="phone" class="col-sm-2 col-form-label">Tenant Phone</label>
                <div class="col-sm-10">
                    <div class="row">
                        <div class="col-md">
                            <input type="text" class="form-control" id="phone" name='phone1'
                                placeholder="<?php echo $tenants[8]; ?>" disabled>
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
                            unset($requestCodes);
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
            <div class="d-flex justify-content-end">
                <button type="submit" class="btn btn-primary" name="submit">Request</button>
            </div>
        </form>

        <?php
        }

        function viewPage()
        {

        ?>

        <form method="POST">
            <div class="d-flex justify-content-end">
                <button type="submit" class="btn btn-success" id="request" name="request">Request Maintenance</button>
            </div>
        </form>


        <?php
            global $db_conn;
            global $tenant_id;
            global $property_id;

            $html = "";
            $stmt = $db_conn->prepare("Select r.request_id, r.request_date, r.rental_property_id, r.tenant_id, c.code_value, r.status_code, r.priority_code, r.last_updated
            from requests r 
            join codes c 
            on c.code_id = r.request_type_code
            
            where r.tenant_id='" . $tenant_id . "' and r.rental_property_id = " . $property_id);

            try {
                $html = "<table class='table table-striped table-hover'>
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
                        <form id='requestTable" . $row['request_id'] . "' method='POST'>
                            <a href='javascript:;' onclick='document.getElementById(\"requestTable" . $row['request_id'] . "\").submit();'>" . $row['request_id'] . "</a>
                            <input type='hidden' name='requestId' value=" . $row['request_id'] . ">
                        </form>
                    </th>
                    <td>" . $row['code_value'] . "</a>
                    </td>
                    <td><form id='requestTable" . $row['request_id'] . "' method='POST'>
                    <a href='javascript:;' onclick='document.getElementById(\"requestTable" . $row['request_id'] . "\").submit();'>" . $row['request_date'] . "<input type='hidden' name='requestId' value=" . $row['request_id'] . ">
                    </form></td>
                    <td>" . selectCodeValue($row['status_code']) . "</td>
                    <td>" . selectCodeValue($row['priority_code']) . "</td>
                    <td>" . $row['last_updated'] . "</td></tr>";
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