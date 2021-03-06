<?php
session_start();
include_once("./check_session.php");
?>
<!-- 
    Title:       service_request.php
    Application: RentalBuddy
    Purpose:     Handling Service request 
    Author:      T. Kim, J. Foster, Group 5, INFO-5139-01-21W
    Date:        March 19th, 2021 (February 10th, 2021) 

    20210307     GPB    Check user logged in & remove login/session_start further down
    20210311     GPB    Added bootstrap icons link
    20210319     TK     Added Upload Rental Document feature 
 
-->
<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="Mark Otto, Jacob Thornton, and Bootstrap contributors">
    <meta name="generator" content="Hugo 0.79.0">
    <title>RentalBuddy - Service Request</title>

    <link rel="canonical" href="https://getbootstrap.com/docs/5.0/examples/starter-template/">

    <script src="../vendor/twbs/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Bootstrap core CSS -->
    <link rel="stylesheet" href="../node_modules/bootstrap-icons/font//bootstrap-icons.css">
    <link rel="stylesheet" href="../vendor/twbs/bootstrap/dist/css/bootstrap.min.css">
    <link href="../node_modules/bootstrap-icons/font//bootstrap-icons.css" rel="stylesheet">

    <!-- Datetime Picker -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"
        integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous">
    </script>
    <link rel="stylesheet" href="https://netdna.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.css" />
    <link rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/tempusdominus-bootstrap-4/5.0.0-alpha14/css/tempusdominus-bootstrap-4.min.css" />
    <script type="text/javascript"
        src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.22.2/moment-with-locales.min.js"></script>
    <script type="text/javascript"
        src="https://cdnjs.cloudflare.com/ajax/libs/tempusdominus-bootstrap-4/5.0.0-alpha14/js/tempusdominus-bootstrap-4.min.js">
    </script>


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
    $tenant_id = '';
    $property_id = '';
    $user_id = '';
    /////////////////////////////////////////


    ?>
</head>

<body>
    <div class="container">

        <?php
        // session_start();

        require '../vendor/autoload.php';
        include 'navigationMenu.php';

        // Please login message
        // if (!isset($_SESSION['CURRENT_USER']['user_id'])) {
        //     $msg = "Please login..";
        //     msgHeader('red');
        // }
        //$userRole = checkUserRoleCode($_SESSION['CURRENT_USER']['user_id']);

        $userRole = $_SESSION['CURRENT_USER']['user_role_code'];
        $user_id = $_SESSION['CURRENT_USER']['user_id'];
        // if Tenant
        if ($userRole == 'tenant') {
            $tenant_id = $_SESSION['CURRENT_USER']['tenant_id'];
            $property_id = checkPropertyId($tenant_id);
        }
        // if landrord
        else if ($userRole == 'landlord') {
            $landlord_id = $_SESSION['CURRENT_USER']['landlord_id'];
        }

        // input form page
        if (isset($_POST['request']) || isset($_POST['appointment'])) {
            inputPage();
        }
        // email landlord
        else if (isset($_POST['email'])) {
            emailPage();
        }
        // write function
        else if (isset($_POST['submit'])) {
            $array_file_extension = array('docx', 'txt', 'pdf', 'jpeg', 'jpg', 'png');
            $errArray = array();
            foreach ($_FILES as $eachfile) {
                // When file exist
                if ($eachfile['name'] != "") {
                    if ($eachfile['type'] == "" || $eachfile['tmp_name'] == "" || $eachfile["size"] == 0 || $eachfile['error'] != 0) {
                        array_push($errArray, $eachfile['name']);
                    } else {
                        $ext = pathinfo(strtolower($eachfile['name']), PATHINFO_EXTENSION);
                        if (!in_array($ext, $array_file_extension)) {
                            array_push($errArray, $eachfile['name']);
                        }
                    }
                }
            }

            if ($_POST['reqType'] == 'Request Type') {
                $msg = "Please select Request Type";
                msgHeader('red');
            } else if ($_POST['reqContent'] == '') {
                $msg = "Please insert Request description";
                msgHeader('red');
            } else if(count($errArray)>0){
                $msg ="There is an error in the file: ";
                foreach($errArray as $fileN){
                    $msg .= $fileN. " ";
                }
                msgHeader('red');
            } else {
                insertRequest();
            }
        } // appointment  write function
        else if (isset($_POST['appointment_submit'])) {
            if ($_POST['reqContent'] != '' && $_POST['datetime'] != '') {
                insertAppointment();
            } else {
                if ($_POST['datetime'] == '') {
                    $msg = "Please select date and time";
                } else if ($_POST['reqContent'] == '') {
                    $msg = "Please insert Request description";
                }
                msgHeader('red');
                //inputPage();
            }
        } //email write function
        else if (isset($_POST['send'])) {
            if ($_POST['emailBody'] != '') {
                global $property_id;
                global $db_conn;
                $recipient = returnLandlordUserIdUsingLandlordId($property_id);
                $lastId = $db_conn->lastInsertId();
                createNotification(0, $user_id, $recipient, 'Please check new Email: ' . $_POST['emailSubject'], '', $lastId);
                phpMail();
                viewPage();
            } else {
                if ($_POST['emailBody'] == '') {
                    $msg = "Please insert an Email body";
                }
                msgHeader('red');
            }
        } else if (isset($_POST['requestId'])) {
            // view detail page
            showRequestDetail();
        }
        // solution update
        else if (isset($_POST['solutionSubmit'])) {
            if ($_POST['solContent'] != '') {
                updateSolution();
            } else {
                if ($_POST['solContent'] == '') {
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

        function insertRequestDetail($requestId, $desc)
        {
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
            msgHeader('green');
            showRequestDetail($requestId);
        }

        function updateRequest($value, $type)
        {
            global $db_conn;
            global $msg;
            if ($type == 'priority') {
                $stmt = $db_conn->prepare("select code_id from codes where code_type='request_priority' and code_value =? and is_enabled='1'");
            } else if ($type == 'status') {
                $stmt = $db_conn->prepare("select code_id from codes where code_type='request_status' and code_value =? and is_enabled='1'");
            }
            try {

                $stmt->execute(array($value));
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
                //msgHeader('green');

                //update request detail
                insertRequestDetail($_POST['request_id'], $type . ' is changed to ' . $value);

                //Request ID??? tenant ID ??????
                $recipient = returnTenantUserIdUsingRequestId($_POST['request_id']);
                createNotification(
                    0,
                    $data['userId'],
                    $recipient,
                    'Request ID: ' . $_POST['request_id'] . ', ' . $type . ' is changed to ' . $value,
                    '',
                    0
                );
                //viewPage();
            } catch (Exception $e) {
                $db_conn->rollback();
                echo $e->getMessage();
            }
        }

        function updateSolution()
        {

            global $msg;

            $desc = 'Task: ' . $_POST['solContent'];
            $msg = "Task contents has been updated.";
            insertRequestDetail($_POST['request_id'], $desc);


            //viewPage();
        }

        function showRequestDetail($reqId = "")
        {
            if ($reqId == "") {
                $reqId = $_POST['requestId'];
            }
            global $db_conn;
            global $tenant_id;
            global $property_id;

            $tenants = loadTenantsInfo();
            $filearray = loadFilesInfo($reqId);

            $html = "";
            $stmt = $db_conn->prepare("Select r.request_id, r.request_date, r.rental_property_id, r.tenant_id, c.description as 'typeValue', r.status_code, r.priority_code, r.last_updated, r.description, p.description as 'priorityValue', s.description as 'statusValue', r.appointment_date_time, r.file
            
            from requests r 
            join codes c 
            join codes p
            join codes s
            on c.code_id = r.request_type_code and p.code_id = r.priority_code and s.code_id = r.status_code
            
            where r.request_id =?");
            try {
                $stmt->execute(array($reqId));
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
            <?php
                        if (!is_null($row['appointment_date_time'])) {
                        ?>
            <div class="row">
                <div class="col-sm ps-4 pe-4">
                    <hr>
                    <p class="fw-bold">Requested the Appointment Date&Time</p>
                </div>
            </div>
            <div class="row">
                <div class="col-sm ps-4 pe-4 pb-4">
                    <pre><?php echo $row['appointment_date_time'] ?></pre>
                </div>
            </div>
            <?php
                        }
                        ?>
            <?php
                        if (count($filearray) > 0) {
                        ?>
            <div class="row">
                <div class="col-sm ps-4 pe-4">
                    <hr>
                    <p class="fw-bold">Uploaded file</p>
                </div>
            </div>
            <?php
                            foreach ($filearray as $file) {
                            ?>
            <div class="row">
                <div class="col-sm ps-4 pe-4 pb-4" style="padding-bottom:2px !important">
                    <a href="/request_file/<?php echo $file ?>" target="_blank">
                        <?php echo $file ?></a>
                </div>
            </div>
            <?php
                            }
                        }
                        ?>
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

        <?php
                    // TK. Warning!!! Hardcoding here
                    global $userRole;
                    if ($row['status_code'] != '63' && $userRole != 'tenant') {
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

        <form method="POST">
            <div class="input-group input-group-lg">
                <span class="input-group-text" id="inputGroup-sizing-lg">Task Contents</span>
                <textarea class="form-control" aria-label="Task Content" aria-describedby="inputGroup-sizing-lg"
                    name='solContent'></textarea>
                <input type="hidden" class="form-control" id="request_id" name='request_id' value=<?php echo $reqId  ?>>
                <button type="submit" class="btn btn-primary" name="solutionSubmit">Update</button>
            </div>
        </form>


        <?php
                }
            } catch (Exception $e) {
                $db_conn->rollback();
                echo $e->getMessage();
            }
            $html = "<h3 class=\"pt-4\">History</h3>";
            // History
            $stmt = $db_conn->prepare("Select description, create_date, create_user_id, last_updated_date, last_user_id
            from requests_detail
            where request_id=? order by request_detail_id");
            $i = 0;
            try {
                $stmt->execute(array($reqId));
                while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                    $i++;
                    $html .= "<br><div class=\"card\">
                    <div class=\"card-header\">
                    <span class=\"badge bg-warning text-dark\">#" . $i . "</span>&nbsp;Created at " . $row['create_date'] . ", Last Modified " . $row['last_updated_date'] . "
                    </div>
                    <div class=\"card-body\">
                    <pre><h5 class=\"card-title\">" . $row['description'] . "</h5></pre>
                      <p class=\"card-text\">Created by " . $row['create_user_id'] . ", Last Modified " . $row['last_user_id'] . "</p>
                    </div>
                  </div>
                  ";
                }
                if ($i >= 1) {
                    echo $html;
                }
            } catch (Exception $e) {
                $db_conn->rollback();
                echo $e->getMessage();
            }
        }

        function insertRequest()
        {
            $destinations = array();
            //file upload
            $destination_path = '../request_file/';
            foreach ($_FILES as $eachfile) {
                if($eachfile['tmp_name']!=""){
                    $FileExt = substr(strrchr($eachfile['name'], "."), 1);
                    $FileName = substr($eachfile['name'], 0, strlen($eachfile['name']) - strlen($FileExt) - 1);
                    $destination_file = $FileName . '_' . time() . '.' . $FileExt;
                    if (!move_uploaded_file($eachfile['tmp_name'], $destination_path . $destination_file)) {
                        echo "<p>Error: File Upload<br>Message: File upload failed.</p><br>";
                        return;
                    }
                    array_push($destinations, $destination_file);
                }
            }

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
                $lastId = $db_conn->lastInsertId();
                $db_conn->commit();
                // Insert into Document Table
                if (count($destinations) > 0) {
                    foreach ($destinations as $fileInfo) {
                        $stmt = $db_conn->prepare("INSERT INTO documents (tenant_id, request_id, filename, last_updated_user_id) values(?, ?, ?, ?)");
                        $array = [
                            $_POST['tenantId'],
                            $lastId,
                            $fileInfo,
                            $user_id
                        ];
                        $db_conn->beginTransaction();
                        $stmt->execute($array);
                        $db_conn->commit();
                    }
                }

                // insert notification
                $recipient = returnLandlordUserIdUsingLandlordId($_POST['rentalId']);
                createNotification(0, $user_id, $recipient, 'Please check new Request: ' . $_POST['reqContent'], 'requests', $lastId);

                $msg = "Request has been inserted.";
                msgHeader('green');
                phpMail();
                viewPage();
            } catch (Exception $e) {
                $db_conn->rollback();
                echo $e->getMessage();
            }
        }

        function insertAppointment()
        {
            global $db_conn;
            global $msg;
            global $user_id;
            $stmt = $db_conn->prepare("INSERT INTO requests (rental_property_id, tenant_id, request_type_code, description, status_code, priority_code, last_updated_user_id, appointment_date_time) values(?, ?, ?, ?, ?, ?, ?, ?)");
            try {
                $array = [
                    $_POST['rentalId'],
                    $_POST['tenantId'],
                    '69',
                    $_POST['reqContent'],
                    '60',
                    '65',
                    $user_id,
                    date($_POST['datetime'])
                ];

                $db_conn->beginTransaction();
                $stmt->execute($array);
                $db_conn->commit();
                $lastId = $db_conn->lastInsertId();

                // insert notification
                $recipient = returnLandlordUserIdUsingLandlordId($_POST['rentalId']);
                createNotification(0, $user_id, $recipient, 'Please check new Appointment: ' . $_POST['reqContent'], 'requests', $lastId);

                $msg = "Request has been inserted.";
                msgHeader('green');
                phpMail();
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
            $stmt = $db_conn->prepare("Select code_id, code_value, description from codes where code_type=? and is_enabled = 1 and code_id not in('69') Order by sort_order");
            try {
                $stmt->execute(array($codeId));
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
        function loadFilesInfo($req_id)
        {
            global $db_conn;
            global $user_id;
            $stmt = $db_conn->prepare("select filename 
            from documents
            where status_code='active' and request_id=?");
            try {
                $tmp = array();
                $stmt->execute(array($req_id));
                while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                    array_push($tmp, $row['filename']);
                }
                return $tmp;
            } catch (Exception $e) {
                $db_conn->rollback();
                echo $e->getMessage();
            }
        }
        function loadTenantsInfo()
        {
            global $db_conn;
            global $user_id;
            $stmt = $db_conn->prepare("select t.salutation_code, t.first_name, t.last_name, t.address_1, t.address_2, t.city, t.province_code, t.postal_code, t.phone, t.email 
            from tenants t
            join users u
            on u.tenant_id = t.tenant_id
            where u.user_id=?");
            try {
                $stmt->execute(array($user_id));
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
            $landlordEmail = loadLandlordEmail();
            ?>
        <form method="POST" enctype="multipart/form-data">
            <div class="row mb-3">
                <h3>Contact Info</h3>
                <h6>Please check your contact information, if you need, you can change your information in manage page.
                </h6>
            </div>
            <div class="row mb-3">
                <!-- Tenant ID, rental_property_id, landlordFN and landlordLN is hidden -->
                <div class="col-sm-10">
                    <input type="hidden" class="form-control" id="tenantId" name='tenantId'
                        value=<?php echo $tenant_id; ?>>
                    <input type="hidden" class="form-control" id="rentalId" name='rentalId'
                        value=<?php echo $property_id; ?>>
                    <input type="hidden" class="form-control" id="landlordFN" name='landlordFN'
                        value=<?php echo $landlordEmail[0]; ?>>
                    <input type="hidden" class="form-control" id="landlordLN" name='landlordLN'
                        value=<?php echo $landlordEmail[1]; ?>>
                </div>
            </div>
            <div class="row mb-3">
                <label for="name" class="col-sm-2 col-form-label">Tenant Name</label>
                <div class="col-sm-10">
                    <input type="text" class="form-control" id="name" name='tenantName'
                        value="<?php echo $tenants['1'] . " " . $tenants['2']; ?>" readonly>
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
                    <input type="text" class="form-control" id="phone" name='phone1'
                        placeholder="<?php echo $tenants[8]; ?>" disabled>
                </div>
            </div>
            <div class="row mb-3">
                <?php if (isset($_POST['request'])) { ?>
                <h3>Request Maintenance</h3>
                <?php } else if (isset($_POST['appointment'])) { ?>
                <h3>Make Appointment</h3>
                <?php } ?>
            </div>
            <div class="row mb-3">
                <?php if (isset($_POST['request'])) { ?>
                <label for="reqType" class="col-sm-2 col-form-label">Request Type</label>
                <?php } else if (isset($_POST['appointment'])) { ?>
                <label for="reqType" class="col-sm-2 col-form-label">Date and Time</label>
                <?php } ?>

                <?php if (isset($_POST['request'])) { ?>
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

                <?php } else if (isset($_POST['appointment'])) { ?>
                <div class="col-sm-6">
                    <div class="input-group date" id="datetimepicker1" data-target-input="nearest">
                        <input type="text" class="form-control datetimepicker-input" data-target="#datetimepicker1"
                            name="datetime" />
                        <div class="input-group-append" data-target="#datetimepicker1" data-toggle="datetimepicker">
                            <div class="input-group-text"><i class="fa fa-calendar"
                                    style="padding-top: 7px; padding-bottom:7px"></i></div>
                        </div>
                    </div>
                </div>
                <script type="text/javascript">
                $(function() {
                    $('#datetimepicker1').datetimepicker({
                        minDate: moment(),
                        format: 'YYYY-MM-DD HH:mm',
                        useCurrent: true
                    });
                });
                </script>
                <?php } ?>

            </div>
            <div class="row mb-3">
                <label for="content" class="col-sm-2 col-form-label">Request Content</label>
                <div class="col-sm-10">
                    <textarea class="form-control" id="reqContent" name='reqContent' rows="3"></textarea>
                </div>
            </div>
            <?php if (isset($_POST['request'])) { ?>
            <div class="row mb-3">
                <label for="formFile1" class="col-sm-2 col-form-label">Upload File 1</label>
                <div class="col-sm-10">
                    <input class="form-control" type="file" id="formFile1" name="request-file1">
                    <div id="formFile" class="form-text">pdf, docx, txt, png, jpg and jpeg extension is allowed.</div>
                </div>
            </div>
            <div class="row mb-3">
                <label for="formFile2" class="col-sm-2 col-form-label">Upload File 2</label>
                <div class="col-sm-10">
                    <input class="form-control" type="file" id="formFile2" name="request-file2">
                    <div id="formFile" class="form-text">pdf, docx, txt, png, jpg and jpeg extension is allowed.</div>
                </div>
            </div>
            <div class="row mb-3">
                <label for="formFile3" class="col-sm-2 col-form-label">Upload File 3</label>
                <div class="col-sm-10">
                    <input class="form-control" type="file" id="formFile3" name="request-file3">
                    <div id="formFile" class="form-text">pdf, docx, txt, png, jpg and jpeg extension is allowed.</div>
                </div>
            </div>
            <?php } ?>

            <div class="d-flex justify-content-end">
                <?php if (isset($_POST['request'])) { ?>
                <button type="submit" class="btn btn-primary" name="submit">Request</button>
                <?php } else if (isset($_POST['appointment'])) { ?>
                <button type="submit" class="btn btn-primary" name="appointment_submit">Submit</button>
                <?php } ?>

            </div>
        </form>

        <?php
        }

        function loadLandlordEmail() //loads the landlord first_name, last_name and email | JF
        {
            global $db_conn;
            global $user_id;
            $tenant_id = checkTenantId($user_id);
            $stmt = $db_conn->prepare('SELECT l.first_name, l.last_name, l.email
            FROM landlords l
            JOIN landlord_rental_properties lrp ON l.landlord_id = lrp.landlord_id
            JOIN leases ls ON lrp.rental_property_id = ls.rental_property_id
            WHERE l.landlord_id = lrp.landlord_id AND lrp.rental_property_id = ls.rental_property_id AND ls.tenant_id = :tenant_id');
            try {
                $stmt->execute(array(":tenant_id" => $tenant_id));
                while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                    $tmp = [
                        $row['first_name'],
                        $row['last_name'],
                        $row['email']
                    ];

                    return $tmp;
                }
            } catch (Exception $e) {
                $db_conn->rollback();
                echo $e->getMessage();
            }
        }

        function emailPage()
        { //displays the email creation page with tenant and landlord emails | JF
            global $tenant_id;
            // load tenant and landlord information
            $tenants = loadTenantsInfo();
            $landlordEmail = loadLandlordEmail();
        ?>
        <form method="POST">
            <div class="row mb-3">
                <h3>Email Landlord</h3>
                <h6>Please check your contact information, if you need, you can change your information in manage page.
                </h6>
            </div>
            <div class="row mb-3">
                <!-- landlord first_name, last_name is hidden -->
                <div class="col-sm-10">
                    <input type="hidden" class="form-control" id="landlordFN" name='landlordFN'
                        value=<?php echo $landlordEmail[0]; ?>>
                    <input type="hidden" class="form-control" id="landlordLN" name='landlordLN'
                        value=<?php echo $landlordEmail[1]; ?>>
                    <input type="hidden" class="form-control" id="tenantFN" name='tenantFN'
                        value=<?php echo $tenants[1]; ?>>
                    <input type="hidden" class="form-control" id="tenantLN" name='tenantLN'
                        value=<?php echo $tenants[2]; ?>>
                </div>
            </div>
            <div class="row mb-3">
                <label for="tenantEmail" class="col-sm-2 col-form-label">From</label>
                <div class="col-sm-10">
                    <input type="email" class="form-control" id="tenantEmail" name='tenantEmail'
                        placeholder="<?php echo $tenants[9]; ?>" disabled>
                </div>
            </div>
            <div class="row mb-3">
                <label for="landlordEmail" class="col-sm-2 col-form-label">To</label>
                <div class="col-sm-10">
                    <input type="email" class="form-control" id="landlordEmail" name='landlordEmail'
                        placeholder="<?php echo $landlordEmail[2]; ?>" disabled>
                </div>
            </div>
            <div class="row mb-3">
                <label for="emailSubject" class="col-sm-2 col-form-label">Subject</label>
                <div class="col-sm-10">
                    <input type="text" class="form-control" id="emailSubject" name='emailSubject'>
                </div>
            </div>
            <div class="row mb-3">
                <label for="emailBody" class="col-sm-2 col-form-label">Email Body</label>
                <div class="col-sm-10">
                    <textarea class="form-control" id="emailBody" name='emailBody' rows="12"></textarea>
                </div>
            </div>
            <div class="d-flex justify-content-end">
                <button type="submit" class="btn btn-primary" name="send">Send</button>
            </div>
        </form>

        <?php
        }

        function viewPage()
        {

        ?>

        <?php
            global $userRole;

            if ($userRole == 'tenant') {
                $html = "<h1>Your Request History</h1><form method=\"POST\">
        <div class=\"d-flex justify-content-end\">
        <button type=\"submit\" class=\"btn btn-warning\" id=\"appointment\" name=\"appointment\" style=\"margin-right:5px;\">Make Appointment</button>
        <button type=\"submit\" class=\"btn btn-success\" id=\"request\" name=\"request\" style=\"margin-right:5px;\">Request Maintenance</button>
        <button type=\"submit\" class=\"btn btn-secondary btn-crud\" id=\"email\" name=\"email\">Email Landlord</button>
        </div>
        </form>";
                echo $html;
            } ?>


        <?php
            global $db_conn;
            global $tenant_id;
            //global $property_id;

            $html = "";

            if ($userRole == 'tenant') {

                $stmt = $db_conn->prepare("Select r.request_id, r.request_date, r.rental_property_id, r.tenant_id, c.code_value, r.status_code, r.priority_code, r.last_updated
            from requests r 
            join codes c 
            on c.code_id = r.request_type_code
            
            where r.is_notification='0' and r.tenant_id=? order by r.request_id desc");

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
                    $stmt->execute(array($tenant_id));
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
            } else if ($userRole == 'landlord' || $userRole == 'admin') {
                $html = '<h1>Request</h1>';
                $sql = "select r.request_id, r.request_date, r.rental_property_id, r.tenant_id, c.code_value, r.status_code, r.priority_code, r.last_updated, rp.address_1
                from requests r 
                join codes c
                join rental_properties rp 
                on c.code_id = r.request_type_code
                and r.rental_property_id = rp.rental_property_id 
                where r.is_notification='0'";

                if ($userRole == 'landlord') {
                    global $landlord_id;
                    $rental_property_ids = makeRentalPropertyIdArray($landlord_id);
                    $in  = str_repeat('?,', count($rental_property_ids) - 1) . '?';
                    $sql .= " and r.rental_property_id IN ($in)";
                }
                $sql .= " order by r.request_id desc";
                $stmt = $db_conn->prepare($sql);

                try {
                    $html .= "<table class='table table-striped table-hover'>
                    <thead>
                      <tr>
                        <th scope='col'>#</th>
                        <th scope='col'>Property</th>
                        <th scope='col'>Request Type</th>
                        <th scope='col'>Request Date</th>
                        <th scope='col'>Status</th>
                        <th scope='col'>Priority</th>
                        <th scope='col'>Last Updated</th>
                      </tr>
                    </thead>
                    <tbody>
                      ";
                    if ($userRole == 'landlord') {
                        $stmt->execute($rental_property_ids);
                    } else if ($userRole == 'admin') {
                        $stmt->execute();
                    }

                    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {

                        $html .= "<tr>
                        <th scope='row'>
                            <form id='requestTable" . $row['request_id'] . "' method='POST'>
                                <a href='javascript:;' onclick='document.getElementById(\"requestTable" . $row['request_id'] . "\").submit();'>" . $row['request_id'] . "</a>
                                <input type='hidden' name='requestId' value=" . $row['request_id'] . ">
                            </form>
                        </th>
                        <td>" . $row['address_1'] . "</a>
                        </td>
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
        }

        ?>
    </div>



</body>

</html>