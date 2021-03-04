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
    <title>RentalBuddy:Personal Calendar</title>

    <link rel="canonical" href="https://getbootstrap.com/docs/5.0/examples/starter-template/">

    <!-- Bootstrap core CSS -->
    <link rel="stylesheet" href="../vendor/twbs/bootstrap/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="../vendor/benhall14/php-calendar/html/css/calendar.css">

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


        // 불러올 정보
        // 1. 납부 정보
        // 2. 노티피케이션
        // 3. 서비스 리퀘스트 정보

        # create the calendar object

        $calendar = new Calendar();

        # or for multiple events

        $events = array();

        $events[] = array(
            'start' => '2021-03-14',
            'end' => '2021-03-14',
            'summary' => 'My Birthday',
            'mask' => true,
            'classes' => ['myclass', 'abc']
        );

        $events[] = array(
            'start' => '2021-03-25',
            'end' => '2021-03-25',
            'summary' => 'Christmas',
            'mask' => true
        );

        $calendar->addEvents($events);

        # finally, to draw a calendar

        echo $calendar->draw(date('Y-m-d')); # draw this months calendar

        # this can be repeated as many times as needed with different dates passed, such as:

        echo $calendar->draw(date('Y-01-01')); # draw a calendar for January this year

        echo $calendar->draw(date('Y-02-01')); # draw a calendar for February this year

        echo $calendar->draw(date('Y-03-01')); # draw a calendar for March this year

        echo $calendar->draw(date('Y-04-01')); # draw a calendar for April this year

        echo $calendar->draw(date('Y-05-01')); # draw a calendar for May this year

        echo $calendar->draw(date('Y-06-01')); # draw a calendar for June this year

        # to use the pre-made color schemes, include the calendar.css stylesheet and pass the color choice to the draw method, such as:

        echo $calendar->draw(date('Y-m-d'));            # print a (default) turquoise calendar

        echo $calendar->draw(date('Y-m-d'), 'purple');  # print a purple calendar

        echo $calendar->draw(date('Y-m-d'), 'pink');    # print a pink calendar

        echo $calendar->draw(date('Y-m-d'), 'orange');  # print a orange calendar

        echo $calendar->draw(date('Y-m-d'), 'yellow');  # print a yellow calendar

        echo $calendar->draw(date('Y-m-d'), 'green');   # print a green calendar

        echo $calendar->draw(date('Y-m-d'), 'grey');    # print a grey calendar

        echo $calendar->draw(date('Y-m-d'), 'blue');    # print a blue calendar

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