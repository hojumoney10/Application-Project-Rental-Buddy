<?php
session_start();
include_once("./check_session.php");
?>
<!-- 
    Title:       calendar.php
    Application: RentalBuddy
    Purpose:     Handling calendar 
    Author:      T. Kim, Group 5, INFO-5139-01-21W
    Date:        March 7th, 2021, (March 6th, 2021)

    20210307     GPB    Check user logged in - removed TK code further down
    20210312     TK     Added make event function
-->

<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <title>RentalBuddy - Personal Calendar</title>

    <link rel="canonical" href="https://getbootstrap.com/docs/5.0/examples/starter-template/">
    <!-- Bootstrap core CSS -->
    <link rel="stylesheet" href="../vendor/twbs/bootstrap/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="../vendor/benhall14/php-calendar/html/css/calendar.css">
    <link rel="stylesheet" href="../node_modules/bootstrap-icons/font/bootstrap-icons.css">

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
    //require_once("../dal/notification_dal.php");
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

        # create the calendar object

        $calendar = new Calendar();

        # or for multiple events

        $events = array();

        // Please login message
        // if (!isset($_SESSION['CURRENT_USER']['user_id'])) {
        //     $msg = "Please login..";
        //     msgHeader('red');
        // }

        $userRole = $_SESSION['CURRENT_USER']['user_role_code'];
        $user_id = $_SESSION['CURRENT_USER']['user_id'];
        // if Tenant
        if ($userRole == 'tenant') {
            $tenant_id = $_SESSION['CURRENT_USER']['tenant_id'];
            $property_id = checkPropertyId($tenant_id);
            drawCalendarButton();
            addEvent();
            addDday();
            addRequestHeader();
            addRequestDetail();
            addPaymentDay();
            addAppointmentTenant();
            if (isset($_POST['timestamp'])) {
                if (isset($_POST['next'])) {
                    drawCalendar($_POST['timestamp'], 'next');
                } else if (isset($_POST['previous'])) {
                    drawCalendar($_POST['timestamp'], 'previous');
                }
            } else {
                drawCalendar(strtotime("Now"), 'now');
            }
        }
        // if landrord
        else if ($userRole == 'landlord' && !isset($_POST['eventsubmit'])) {
            $landlord_id = $_SESSION['CURRENT_USER']['landlord_id'];
            $rental_property_ids = makeRentalPropertyIdArray($landlord_id);
            if (count($rental_property_ids) == 0) {
                $msg = "There are no properties held by landlord.";
                msgHeader("red");
            } else {
                drawCalendarButton();
                addEvent();
                addRequestHeader();
                addRequestDetail();
                addPaymentDay();
                addAppointmentLandlord();
                if (isset($_POST['timestamp'])) {
                    if (isset($_POST['next'])) {
                        drawCalendar($_POST['timestamp'], 'next');
                    } else if (isset($_POST['previous'])) {
                        drawCalendar($_POST['timestamp'], 'previous');
                    }
                } else {
                    drawCalendar(strtotime("Now"), 'now');
                }
            }
        }
        // insert event
        else if ($userRole == 'landlord' && isset($_POST['eventsubmit'])) {
            if ($_POST['datetime'] == "" || $_POST['datetime'] == "eventdescription") {
                $msg = "Please insert date&time or description.";
                msgHeader('red');
                $landlord_id = $_SESSION['CURRENT_USER']['landlord_id'];
                drawCalendarButton();
                addEvent();
                addRequestHeader();
                addRequestDetail();
                addPaymentDay();
                addAppointmentLandlord();
                drawCalendar(strtotime("Now"), 'now');
            } else {
                insertEvent();
            }
        } else if ($userRole == 'admin') {
            $msg = "Calendar feature is not supported for Administrator account.";
            msgHeader('red');
        }

        function insertEvent()
        {
            global $db_conn;
            global $user_id;
            global $msg;

            $stmt = $db_conn->prepare("INSERT INTO requests (request_date, rental_property_id, request_type_code, description, status_code, priority_code, appointment_date_time, is_notification, last_updated_user_id) values(?, ?, ?, ?, ?, ?, ?, ?, ?)");
            try {
                $array = [
                    date('Y-m-d H:i:s', strtotime($_POST['datetime'])),
                    $_POST['selectedPropertyId'],
                    70,
                    $_POST['eventdescription'],
                    63,
                    65,
                    date('Y-m-d H:i:s', strtotime($_POST['datetime'])),
                    1,
                    $user_id
                ];

                $db_conn->beginTransaction();
                $stmt->execute($array);
                $db_conn->commit();
            } catch (Exception $e) {
                $db_conn->rollback();
                echo $e->getMessage();
            }

            // insert notification
            //property id -> user_id
            //createNotification('',$user_id,);

            $msg = "Event has been inserted.";
            msgHeader('green');
        ?>
        <script>
        setTimeout(function() {
            location.href = window.location.pathname;
        }, 1000);
        </script>
        <?php
        }

        //Add notification
        //waiting...
        function drawCalendarButton()
        {
            global $userRole;

            $html = "
            <form method=\"POST\">
                <div class=\"d-flex justify-content-between\" style=\"margin-bottom:5px;\">
                    <button type=\"submit\" name=\"previous\" class=\"btn btn-warning\"><i class=\"bi bi-arrow-left-circle\"></i> Previous</button>";
            if ($userRole == 'landlord') {
                $html .= "<button type=\"button\" class=\"btn btn-primary\" data-bs-toggle=\"modal\" data-bs-target=\"#exampleModal\"><i class=\"bi bi-calendar-event\"></i> Make event</button>";

            ?>
        <form method="POST">
            <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel"
                aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLabel">Make event</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">



                            <div class="row mb-3">
                                <label for="datetimepicker1" class="col-sm-4 col-form-label">Date and Time</label>
                                <div class="col-sm-7">
                                    <div class="input-group date" id="datetimepicker1" data-target-input="nearest">
                                        <input type="text" class="form-control datetimepicker-input"
                                            data-target="#datetimepicker1" name="datetime" />
                                        <div class="input-group-append" data-target="#datetimepicker1"
                                            data-toggle="datetimepicker">
                                            <div class="input-group-text"><i class="fa fa-calendar"
                                                    style="padding-top: 7px; padding-bottom:7px"></i></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label for="selectproperty" class="col-sm-4 col-form-label">Select Property</label>
                                <div class="col-sm-7">

                                    <select id="selectproperty" class="form-select" aria-label="Select Property"
                                        name="selectedPropertyId">
                                        <?php
                                                global $landlord_id;
                                                $selectbox = '';
                                                $rental_property_names = returnRentalPropertyIdAndNameArray($landlord_id);
                                                foreach ($rental_property_names as $v1) {
                                                    $selectbox .= '<option value="' . $v1[0] . '">' . $v1[1] . '</option>';
                                                }
                                                echo $selectbox;
                                                unset($selectbox);
                                                ?>

                                    </select>

                                </div>
                            </div>

                            <div class="row mb-3">
                                <label for="eventdescription" class="col-sm-4 col-form-label">Event Description</label>
                                <div class="col-sm-7">
                                    <textarea rows="3" type="text" class="form-control" id="eventdescription"
                                        name='eventdescription'></textarea>
                                </div>
                            </div>



                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>

                            <button type="submit" name="eventsubmit" class="btn btn-primary">Make Event</button>
                        </div>
                    </div>
                </div>
            </div>
        </form>
        <?php
            }
            $html .= "<button type=\"submit\" name=\"next\" class=\"btn btn-success\">Next <i class=\"bi bi-arrow-right-circle\"></i></button>
                </div>
            ";
            echo $html;
            unset($html);
        }

        function returnRentalPropertyIdAndNameArray($landlord_id)
        {
            $db_conn = connectDB();
            $stmt = $db_conn->prepare("select l.rental_property_id,
                                        r.listing_reference 
                                        from landlord_rental_properties l
                                        join rental_properties r
                                        on l.rental_property_id = r.rental_property_id
                                        where l.landlord_id=?");
            try {

                $results = [];
                $stmt->execute(array($landlord_id));
                while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                    $tmp = [
                        $row['rental_property_id'],
                        $row['listing_reference']
                    ];
                    array_push($results, $tmp);
                }
                return $results;
                unset($results);
            } catch (Exception $e) {
                $db_conn->rollback();
                echo $e->getMessage();
            }
        }

        function addEvent()
        {
            global $events;
            $eventArr = collectEvent();
            foreach ($eventArr as &$value) {
                $events[] = array(
                    'start' => $value[2],
                    'end' => $value[2],
                    'summary' => '<div id="event" class="header"><i class="bi bi-alarm"></i> Event: ' . $value[3] . '</div><div id="event" class="detail">#' . $value[0] . ". " . $value[1] . '</div>',
                    'mask' => true
                );
            }
            unset($value);
        }


        function addRequestHeader()
        {
            global $events;
            //Add Request
            $requestheaders = collectServiceRequestHeader();
            foreach ($requestheaders as &$value) {
                $events[] = array(
                    'start' => $value[2],
                    'end' => $value[2],
                    'summary' => '<div id="request" class="header"><i class="bi bi-check"></i> Request Added</div><div id="request" class="headercontent">#' . $value[0] . ". " . $value[1] . '</div>',
                    'mask' => true
                );
            }
            unset($value);
        }

        function addRequestDetail()
        {
            global $events;
            //Add RequestDetail
            $requestDetail = collectServiceRequestDetail();
            foreach ($requestDetail as &$value) {
                $events[] = array(
                    'start' => $value[2],
                    'end' => $value[2],
                    'summary' => '<div id="request" class="detail"><i class="bi bi-check-all"></i> Request History Added</div><div id="request" class="detailcontent">#' . $value[0] . ". " . $value[1] . '</div>',
                    'mask' => true
                );
            }
            unset($value);
        }

        function addDday()
        {
            global $events;
            $paymentDay = collectPaymentDay();
            $startDate = new DateTime();
            $endDate = new DateTime($paymentDay[5]);
            $interval = $startDate->diff($endDate);

            $events[] = array(
                'start' => date("Y-m-d"),
                'end' => date("Y-m-d"),
                'summary' => '<div id="d-day" class="header"><i class="bi bi-calendar-check"></i> End of lease: -' . $interval->days . '</div>'
            );
        }

        function addAppointmentTenant()
        {
            global $events;
            $appointmentDay = collectAppointment();
            foreach ($appointmentDay as &$value) {
                $events[] = array(
                    'start' => $value[2],
                    'end' => $value[2],
                    'summary' => '<div id="appointment" class="header"><i class="bi bi-alarm"></i> Appointment: ' . $value[3] . '</div><div id="appointment" class="detail">#' . $value[0] . ". " . $value[1] . '</div>',
                    'mask' => true
                );
            }
            unset($value);
        }

        function addAppointmentLandlord()
        {
            global $events;
            $appointmentDay = collectAppointment();
            foreach ($appointmentDay as &$value) {
                $events[] = array(
                    'start' => $value[2],
                    'end' => $value[2],
                    'summary' => '<div id="appointment" class="header"><i class="bi bi-alarm"></i> Appointment: ' . $value[3] . '</div><div id="appointment" class="detail">#' . $value[0] . ". " . $value[1] . '<br>with ' . checkTenantName($value[4]) . '</div>',
                    'mask' => true
                );
            }
            unset($value);
        }

        function addPaymentDay()
        {
            global $userRole;
            global $events;
            //Add payment day
            if ($userRole == 'tenant') {
                $paymentDay = collectPaymentDay();
                $startDate = new DateTime($paymentDay[4]);
                $endDate = new DateTime($paymentDay[5]);
                $interval = $startDate->diff($endDate);

                $maxMonth = (($interval->format('%y')) * 12 + $interval->format('%m'));


                $startYearMonthDay_str = substr($paymentDay[4], 0, -2);
                if (strlen($paymentDay[0]) == 1) {
                    $paymentDay[0] = "0" . $paymentDay[0];
                }
                $startYearMonthDay_str = $startYearMonthDay_str . $paymentDay[0];

                for ($i = 1; $i <= $maxMonth; $i++) {
                    $sum = $paymentDay[1] + $paymentDay[2] + $paymentDay[3];
                    $events[] = array(
                        'start' => $startYearMonthDay_str,
                        'end' => $startYearMonthDay_str,
                        'summary' => '<div id="payment" class="subject"><i class="bi bi-cash-stack"></i> Payment Day: $' . $sum . '</div><div id="payment" class="content">Rent $' . $paymentDay[1] . "<br>Parking $" . $paymentDay[2] . "<br>Other $" . $paymentDay[3] . '</div>',
                        'mask' => true
                    );
                    $startYearMonthDay_str = strtotime("$startYearMonthDay_str +1 month");
                    $startYearMonthDay_str = date("Y-m-d", $startYearMonthDay_str);
                }
            }
        }

        function drawCalendar($stamp, $stat)
        {
            global $calendar;
            global $events;

            $calendar->addEvents($events);

            $stamp = date("Y-m-d", $stamp);

            if ($stat == 'next') {
                $stamp = strtotime("$stamp +1 month");
            } else if ($stat == 'previous') {
                $stamp = strtotime("$stamp -1 month");
            } else if ($stat == 'now') {
                $stamp = strtotime("now");
            }
            $back_colors = array('purple', 'pink', 'orange', 'yellow', 'green', 'grey', 'blue');
            $i = rand(0, count($back_colors));
            // now
            //$timestamp = strtotime("Now");
            echo $calendar->draw(date('Y-m-d', $stamp), $back_colors[$i]);
            ?>

        <input type="hidden" class="form-control" id="timestamp" name='timestamp' value=<?php echo $stamp; ?>>
        </form>
        <?php
        }


        function collectPaymentDay()
        {
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



        function collectAppointment()
        {
            //for tenant and landlord
            global $userRole;
            global $db_conn;
            global $tenant_id;
            $results = [];
            if ($userRole == 'tenant') {
                $stmt = $db_conn->prepare("SELECT request_id, description, date(appointment_date_time) as date, time(appointment_date_time) as time FROM requests WHERE is_notification='0' and request_type_code='69' and status_code='63' and tenant_id=?");
                try {
                    $stmt->execute(array($tenant_id));
                    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                        $tmp = [
                            $row['request_id'],
                            $row['description'],
                            $row['date'],
                            $row['time']
                        ];
                        array_push($results, $tmp);
                    }
                    return $results;
                } catch (Exception $e) {
                    $db_conn->rollback();
                    echo $e->getMessage();
                }
            } else if ($userRole == 'landlord') {
                global $landlord_id;
                $rental_property_ids = makeRentalPropertyIdArray($landlord_id);
                if (count($rental_property_ids) > 0) {
                    $in  = str_repeat('?,', count($rental_property_ids) - 1) . '?';
                }


                $sql = "SELECT request_id, 
                description, 
                date(appointment_date_time) as date, 
                time(appointment_date_time) as time,
                tenant_id 
                FROM requests WHERE is_notification='0' and request_type_code='69' and status_code='63' and rental_property_id IN ($in)";

                $stmt = $db_conn->prepare($sql);

                try {
                    $stmt->execute($rental_property_ids);

                    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                        $tmp = [
                            $row['request_id'],
                            $row['description'],
                            $row['date'],
                            $row['time'],
                            $row['tenant_id']
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

        function collectServiceRequestHeader()
        {
            global $userRole;
            global $db_conn;
            global $tenant_id;

            $results = [];
            if ($userRole == 'tenant') {
                $stmt = $db_conn->prepare("SELECT request_id, description, date(request_date) as date FROM requests WHERE  is_notification='0' and tenant_id=?");
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
                $sql = "SELECT request_id, description, date(request_date) as date FROM requests WHERE is_notification='0'";

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

        function collectEvent()
        {
            //for tenant and landlord
            global $userRole;
            global $db_conn;
            global $tenant_id;
            $results = [];

            if ($userRole == 'tenant') {
                $rental_pId = checkRentalPropertyId($tenant_id);
                $stmt = $db_conn->prepare("SELECT request_id, description, date(appointment_date_time) as date, time(appointment_date_time) as time FROM requests WHERE is_notification='1' and request_type_code='70' and rental_property_id=?");
                try {
                    $stmt->execute(array($rental_pId));
                    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                        $tmp = [
                            $row['request_id'],
                            $row['description'],
                            $row['date'],
                            $row['time']
                        ];
                        array_push($results, $tmp);
                    }
                    return $results;
                } catch (Exception $e) {
                    $db_conn->rollback();
                    echo $e->getMessage();
                }
            } else if ($userRole == 'landlord') {
                global $landlord_id;
                $rental_property_ids = makeRentalPropertyIdArray($landlord_id);
                $in  = str_repeat('?,', count($rental_property_ids) - 1) . '?';

                $sql = "SELECT request_id, 
                description, 
                date(appointment_date_time) as date, 
                time(appointment_date_time) as time,
                tenant_id 
                FROM requests WHERE is_notification='1' and request_type_code='70' and rental_property_id IN ($in)";

                $stmt = $db_conn->prepare($sql);

                try {
                    $stmt->execute($rental_property_ids);

                    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                        $tmp = [
                            $row['request_id'],
                            $row['description'],
                            $row['date'],
                            $row['time'],
                            $row['tenant_id']
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
            WHERE r.is_notification='0'
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
                WHERE r.is_notification='0' ";

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