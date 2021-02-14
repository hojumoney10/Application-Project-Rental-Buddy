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
            // view detail page
        } else if (isset($_POST['requestId'])) {
            showRequestDetail();
        } else if (isset($_POST['solutionSubmit'])) {
            if ($_POST['solType'] != 'Solution Type') {
                updateSolution();
            } else {
                $msg = "Please select Solution Type";
                msgHeader('red');
                viewPage();
            }
        }
        // change priority or status
        else if (isset($_POST['low']) || isset($_POST['medium']) || isset($_POST['high']) || isset($_POST['received']) || isset($_POST['inprogress']) || isset($_POST['completed'])) {
            $key = array_keys($_POST);
            updateRequest($key[2]);
        }

        // view list page
        else {
            viewPage();
        }
        // Change Priority 와 Change Status 항목을 동적으로 뿌리고,
        // 그것을 클릭해서 Requests가 업데이트 될 때는 Code_id가 입력되도록 변경한다.
        // 그 뒤 디테일 뷰에서 코드로 표시되는 Priority와 Status를 문구가 표시되도록 변경

        // 솔루션에 히스토리 기능을 부여하는 것을 고려

        // 리스트에 필터 기능 부여를 고려
        
        function updateRequest($value)
        {
            global $db_conn;
            $sql = "UPDATE requests SET";
            if ($value == 'low' || $value == 'medium' || $value == 'high') {
                
                $data = ['priorityCode' => $value];
                $sql .= " priority_code=:priorityCode";
            } else if ($value == 'received' || $value == 'inprogress' || $value == 'completed') {
                $data = ['statusCode' => $value];
                $sql .= " status_code=:statusCode";
            }

            $data['updateTime'] = date("Y-m-d H:i:s");
            $data['userId'] = $_POST['user_id'];
            $data['requestId'] = $_POST['request_id'];
            $sql .= ", last_updated=:updateTime, last_updated_user_id=:userId WHERE request_id=:requestId";
            try {
                $stmt = $db_conn->prepare($sql);
                $stmt->execute($data);
                viewPage();
            } catch (Exception $e) {
                $db_conn->rollback();
                echo $e->getMessage();
            }
        }

        function updateSolution()
        {
            global $db_conn;
            global $msg;
            $data = [
                'solutionType' => $_POST['solType'],
                'solContent' => $_POST['solContent'],
                'requestId' => $_POST['request_id'],
                'updateTime' => date("Y-m-d H:i:s")
            ];
            try {
                $sql = "UPDATE requests SET solution_code=:solutionType, solution_description=:solContent, solution_date=:updateTime, last_updated=:updateTime  WHERE request_id=:requestId";
                $stmt = $db_conn->prepare($sql);
                $stmt->execute($data);

                viewPage();
            } catch (Exception $e) {
                $db_conn->rollback();
                echo $e->getMessage();
            }
        }


        function showRequestDetail()
        {
            global $db_conn;
            global $tenant_id;
            global $property_id;


            $tenants = loadTenantsInfo();

            $html = "";
            $stmt = $db_conn->prepare("Select r.request_id, r.request_date, r.rental_property_id, r.tenant_id, c.code_value, r.status_code, r.priority_code, r.last_updated, r.description, r.solution_description, r.solution_date, r.solution_code
            from requests r 
            join codes c 
            on c.code_id = r.request_type_code
            
            where r.tenant_id='" . $tenant_id . "' and r.rental_property_id = " . $property_id . " and r.request_id =" . $_POST['requestId']);
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
                    <?php echo $row['code_value'] ?>
                </div>

                <div class="col-sm ps-4">
                    <p class="fw-bold">Priority</p>
                </div>

                <div class="col-sm">
                    <?php echo $row['priority_code'] ?>
                </div>

                <div class="col-sm ps-4">
                    <p class="fw-bold">Status</p>
                </div>

                <div class="col-sm">
                    <?php echo $row['status_code'] ?>
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
                    ?>
        <br>
        <form method="POST">
            <input type="hidden" class="form-control" id="request_id" name='request_id'
                value=<?php echo $_POST['requestId']; ?>>
            <input type="hidden" class="form-control" id="user_id" name='user_id' value=<?php echo $user_id; ?>>
            <div class="row mb-3">
                <label for="priority" class="col-sm-2 col-form-label">Change Priority</label>
                <div class="btn-group col-sm-4" role="group" id="priority" aria-label="PriorityChange">
                    <button type="submit" class="btn btn-success" name="low">Low</button>
                    <button type="submit" class="btn btn-warning" name="medium">Medium</button>
                    <button type="submit" class="btn btn-danger" name="high">High</button>
                </div>
                <label for="status" class="col-sm-2 col-form-label">Change Status </label>
                <div class="btn-group col-sm-4" id="status" role="group" aria-label="PriorityChange">
                    <button type="submit" class="btn btn-outline-primary" name="received">Received</button>
                    <button type="submit" class="btn btn-outline-primary" name="inprogress">In progress</button>
                    <button type="submit" class="btn btn-outline-primary" name="completed">Completed</button>
                </div>
            </div>
        </form>
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
                    <p class="text-start">Completed by Manager almost
                        <?php echo (format_date(strtotime($row['solution_date']))) ?>.</p>
                </div>
            </div>

            <div class="row">
                <div class="col-sm ps-4">
                    <p class="fw-bold">Solution Type</p>
                </div>

                <div class="col-sm">
                    <?php echo selectCodeValue($row['solution_code']); ?>
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
                value=<?php echo $_POST['requestId']; ?>>
            <button type="submit" class="btn btn-primary" name="solutionSubmit">Update</button>
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
                    <td>" . selectCodeValue($row['status_code']). "</td>
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