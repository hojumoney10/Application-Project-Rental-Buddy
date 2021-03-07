<!-- 
    Title:       calendar.php
    Application: RentalBuddy
    Purpose:     Handling calendar 
    Author:      T. Kim, Group 5, INFO-5139-01-21W
    Date:        March 6th, 2021
    // To do: Adding Payment day for landlord
              Adding Notification <- waiting for related feature development
-->
<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <title>RentalBuddy:Personal Calendar</title>

    <link rel="canonical" href="https://getbootstrap.com/docs/5.0/examples/starter-template/">
    <!-- Bootstrap core CSS -->
    <link rel="stylesheet" href="../vendor/twbs/bootstrap/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="../vendor/benhall14/php-calendar/html/css/calendar.css">
    <link rel="stylesheet" href="../node_modules/bootstrap-icons/font/bootstrap-icons.css">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Roboto+Condensed:wght@400;700&display=swap');

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

        session_start();

        require '../vendor/autoload.php';
        include 'navigationMenu.php';

        use benhall14\phpCalendar\Calendar as Calendar;
        // Please login message
        if (!isset($_SESSION['CURRENT_USER']['user_id'])) {
            $msg = "Please login..";
            msgHeader('red');
        }
        $userRole = checkUserRoleCode($_SESSION['CURRENT_USER']['user_id']);

        // if Tenant
        if ($userRole == 'tenant') {
            $user_id = $_SESSION['CURRENT_USER']['user_id'];
            $tenant_id = checkTenantId($user_id);
            $property_id = checkPropertyId($tenant_id);
        }
        // if landrord
        else if ($userRole == 'landlord') {
            $user_id = $_SESSION['CURRENT_USER']['user_id'];
            $landlord_id = checkLandlordId($user_id);
        } else if ($userRole == 'admin') {
            $user_id = $_SESSION['CURRENT_USER']['user_id'];
        }

        # create the calendar object

        $calendar = new Calendar();

        # or for multiple events

        $events = array();

        //Add notification
        //waiting...

        //Add Request
        $requestheaders = collectServiceRequestHeader();
        foreach ($requestheaders as &$value) {
            $events[] = array(
                'start' => $value[2],
                'end' => $value[2],
                'summary' => '<div id="request" class="header"><i class="bi bi-check"></i> Request Added</div><div id="request" class="headercontent">#' . $value[0] . ". " . $value[1] . '</div><br>',
                'mask' => true
            );
        }
        unset($value);

        //Add RequestDetail
        $requestDetail = collectServiceRequestDetail();
        foreach ($requestDetail as &$value) {
            $events[] = array(
                'start' => $value[2],
                'end' => $value[2],
                'summary' => '<div id="request" class="detail"><i class="bi bi-check-all"></i> Request History Added</div><div id="request" class="detailcontent">#' . $value[0] . ". " . $value[1] . '</div><br>',
                'mask' => true
            );
        }
        unset($value);

        //Add payment day
        if ($userRole == 'tenant'){
            $paymentDay =collectPaymentDay();
            $startDate = new DateTime($paymentDay[4]);
            $endDate = new DateTime($paymentDay[5]);
            $interval = $startDate -> diff($endDate);
            
            $maxMonth = (($interval->format('%y')) * 12 + $interval->format('%m'));


            $startYearMonthDay_str = substr($paymentDay[4], 0 ,-2);
            if(strlen($paymentDay[0])==1){
                $paymentDay[0] = "0".$paymentDay[0];
            }
            $startYearMonthDay_str= $startYearMonthDay_str.$paymentDay[0];

            for($i = 1; $i <= $maxMonth;$i++){

                $events[] = array(
                    'start' => $startYearMonthDay_str,
                    'end' => $startYearMonthDay_str,
                    'summary' => '<div id="payment" class="subject"><i class="bi bi-cash-stack"></i> Payment Day</div><div id="payment" class="content">Rent $' . $paymentDay[1] . "<br>Parking $" . $paymentDay[2] ."<br>Other $". $paymentDay[3]. '</div><br>',
                    'mask' => true
                );
                $startYearMonthDay_str = strtotime("$startYearMonthDay_str +1 month");
                $startYearMonthDay_str = date("Y-m-d", $startYearMonthDay_str);
            }
        }
        

        function collectPaymentDay(){
            global $userRole;
            global $db_conn;
            global $tenant_id;

            $results = [];
            if ($userRole == 'tenant') {
                $stmt = $db_conn->prepare("SELECT payment_day, base_rent_amount, parking_amount, other_amount, start_date, end_date FROM leases 
                WHERE status_code='active' and tenant_id=?");
            }
            try {
                $stmt->execute(array($tenant_id));
                while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                    $tmp = [
                        $row['payment_day'],
                        $row['base_rent_amount'],
                        $row['parking_amount'],
                        $row['other_amount'],
                        $row['start_date'],
                        $row['end_date']
                    ];
                }
                return $tmp;
            } catch (Exception $e) {
                $db_conn->rollback();
                echo $e->getMessage();
            }
        }

        function collectServiceRequestHeader()
        {
            global $userRole;
            global $db_conn;
            global $tenant_id;

            $results = [];
            if ($userRole == 'tenant') {
                $stmt = $db_conn->prepare("SELECT request_id, description, date(request_date) as date FROM requests WHERE last_updated BETWEEN DATE_ADD(NOW(),INTERVAL -1 MONTH ) AND DATE_ADD(NOW(),INTERVAL +3 MONTH) and tenant_id=?");
                try {
                    $stmt->execute(array($tenant_id));
                    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                        $tmp = [
                            $row['request_id'],
                            $row['description'],
                            $row['date']
                        ];
                        array_push($results, $tmp);
                    }
                    return $results;
                } catch (Exception $e) {
                    $db_conn->rollback();
                    echo $e->getMessage();
                }
            } else if ($userRole == 'landlord' || $userRole == 'admin') {
                $sql = "SELECT request_id, description, date(request_date) as date FROM requests WHERE last_updated BETWEEN DATE_ADD(NOW(),INTERVAL -1 MONTH ) AND DATE_ADD(NOW(),INTERVAL +3 MONTH) ";

                if ($userRole == 'landlord') {
                    global $landlord_id;
                    $rental_property_ids = makeRentalPropertyIdArray($landlord_id);
                    $in  = str_repeat('?,', count($rental_property_ids) - 1) . '?';
                    $sql .= "and rental_property_id IN ($in)";
                }
                $stmt = $db_conn->prepare($sql);

                try {
                    if ($userRole == 'landlord') {
                        $stmt->execute($rental_property_ids);
                    } else if ($userRole == 'admin') {
                        $stmt->execute();
                    }

                    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                        $tmp = [
                            $row['request_id'],
                            $row['description'],
                            $row['date']
                        ];
                        array_push($results, $tmp);
                    }
                    return $results;
                } catch (Exception $e) {
                    $db_conn->rollback();
                    echo $e->getMessage();
                }
            }
        }

        function collectServiceRequestDetail()
        {
            global $userRole;
            global $db_conn;
            global $tenant_id;

            $results = [];
            if ($userRole == 'tenant') {
                $stmt = $db_conn->prepare("SELECT 
            r.request_id as r_id
            , r.description as r_desc
            , date(r.request_date) as r_date
            , rd.request_id as rd_id
            , rd.description as rd_desc
            , date(rd.create_date) as detail_date 
            FROM requests r JOIN requests_detail rd 
            ON r.request_id = rd.request_id 
            WHERE rd.create_date BETWEEN DATE_ADD(NOW(),INTERVAL -1 MONTH ) AND DATE_ADD(NOW(),INTERVAL +3 MONTH)
            and r.tenant_id=?");
                try {
                    $stmt->execute(array($tenant_id));
                    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                        $tmp = [
                            $row['rd_id'],
                            $row['rd_desc'],
                            $row['detail_date']
                        ];
                        array_push($results, $tmp);
                    }
                    return $results;
                } catch (Exception $e) {
                    $db_conn->rollback();
                    echo $e->getMessage();
                }
            } else if ($userRole == 'landlord' || $userRole == 'admin') {
                $sql = "SELECT 
                r.request_id as r_id
                , r.description as r_desc
                , date(r.request_date) as r_date
                , rd.request_id as rd_id
                , rd.description as rd_desc
                , date(rd.create_date) as detail_date 
                FROM requests r JOIN requests_detail rd 
                ON r.request_id = rd.request_id 
                WHERE rd.create_date BETWEEN DATE_ADD(NOW(),INTERVAL -1 MONTH ) AND DATE_ADD(NOW(),INTERVAL +3 MONTH) ";

                if ($userRole == 'landlord') {
                    global $landlord_id;
                    $rental_property_ids = makeRentalPropertyIdArray($landlord_id);
                    $in  = str_repeat('?,', count($rental_property_ids) - 1) . '?';
                    $sql .= "and r.rental_property_id IN ($in)";
                }

                $stmt = $db_conn->prepare($sql);
                try {
                    if ($userRole == 'landlord') {
                        $stmt->execute($rental_property_ids);
                    } else if ($userRole == 'admin') {
                        $stmt->execute();
                    }
                    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                        $tmp = [
                            $row['rd_id'],
                            $row['rd_desc'],
                            $row['detail_date']
                        ];
                        array_push($results, $tmp);
                    }
                    return $results;
                } catch (Exception $e) {
                    $db_conn->rollback();
                    echo $e->getMessage();
                }
            }
        }

        $calendar->addEvents($events);

        //purple, pink, orange, yellow, green, grey, blue
        // -1
        $timestamp = strtotime("-1 month");
        echo $calendar->draw(date('Y-m-d', $timestamp), 'purple');

        // now
        $timestamp = strtotime("Now");
        echo $calendar->draw(date('Y-m-d', $timestamp), 'green');

        // +1
        $timestamp = strtotime("+1 month");
        echo $calendar->draw(date('Y-m-d', $timestamp), 'grey');

        // +2
        $timestamp = strtotime("+2 month");
        echo $calendar->draw(date('Y-m-d', $timestamp), 'blue');

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

        ?>
    </div>
    <script src="../vendor/twbs/bootstrap/dist/js/bootstrap.bundle.min.js"></script>


</body>

</html>