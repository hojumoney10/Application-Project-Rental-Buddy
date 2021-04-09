<?php
// <!-- 
// Title:       navigationMenu.php
// Application: RentalBuddy
// Purpose:     Handles navigation for the RentalBuddy site
// Author:      T. Kim, Group 5, INFO-5139-01-21W
// Date:        February 16th, 2021 (January 30th, 2021) 

// 20210216    GPB     Added rental_properties.php to menu
// 20210217    SKC     Added service_request_tenant.php & lease_info_tenant.php to menu
// 20210219    GPB     Added temporary user selector for admin/landlord/tenant
// 20210220    GPB     Updated all links, and now menus are user-driven
//                     Added leases
// 20210306    THK     Added Calendar(for tenant)
// 20210307    GPB     Added Welcome/Logout
// 20210312    GPB     Added Notifications stub and badge
// -->

define('__PAGES__', dirname(__FILE__));
require_once(__PAGES__ . "/common.php");

// THIS WORKS;
$root = dirname(dirname(__FILE__));
// echo $root;

// //BUT THIS DOESN'T!! WHY??
// define('__ROOT__', dirname(dirname(__FILE__)));
// echo __ROOT__;

require($root . "/dal/notifications_dal.php");

session_start();
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['selected-user']) && isset($_POST['btn-select-user'])) {
    // do a fake login
    login($_POST['selected-user']);
}

$base_URL = ($_SERVER['HTTPS'] == 'on') ? 'https://' : 'http://';
//$base_URL .= ($_SERVER['SERVER_PORT'] != '80') ? $_SERVER['HTTP_HOST'].':'.$_SERVER['SERVER_PORT'] : $_SERVER['HTTP_HOST'];
$base_URL .= $_SERVER['HTTP_HOST'];
?>

<nav class="navbar navbar-expand-md navbar-dark bg-dark fixed-top">
    <div class="container-fluid">
        <a class="navbar-brand" href="<?php echo $base_URL . "/index.php" ?>">RentalBuddy</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarsExampleDefault" aria-controls="navbarsExampleDefault" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarsExampleDefault">
            <ul class="navbar-nav me-auto mb-2 mb-md-0">
                <li class="nav-item active">
                    <a class="nav-link" aria-current="page" href="<?php echo $base_URL . "/index.php" ?>">Home</a>
                </li>


                <?php

                // Check if we are viewing as a tenant or an admin/landlord
                if ($_SESSION['CURRENT_USER']['user_role_code'] == 'tenant') { ?>
                    <li class="nav-item">
                        <a class="nav-link" href="<?php echo $base_URL . "/pages/service_request.php" ?>">My Service
                            Requests</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?php echo $base_URL . "/pages/lease_info_tenant.php" ?>">My Lease</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?php echo $base_URL . "/pages/calendar.php" ?>">Calendar</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?php echo $base_URL . "/pages/tenant_payments.php" ?>">Payments</a>
                    </li>
                <?php
                } else { ?>
                    <li class="nav-item">
                        <a class="nav-link" href="<?php echo $base_URL . "/pages/tenants.php" ?>">Tenants</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?php echo $base_URL . "/pages/landlords.php" ?>">Landlords</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?php echo $base_URL . "/pages/rental_properties.php" ?>">Properties</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?php echo $base_URL . "/pages/leases.php" ?>">Leases</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?php echo $base_URL . "/pages/service_request.php" ?>">Service
                            Requests</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?php echo $base_URL . "/pages/users.php" ?>">Users</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?php echo $base_URL . "/pages/calendar.php" ?>">Calendar</a>
                    </li>
                <?php
                }
                ?>
            </ul>

            <?php
            if (isset($_SESSION['CURRENT_USER'])) {
            ?>
                <div style="float: right;">
                    <ul class="navbar-nav me-auto mb-2 mb-md-0">
                        <!-- Notifications -->
                        <li id="li-notification" class="nav-item">
                            <div id="div-notifications">
                                <a id="notification-icon" class="nav-link bi-envelope" style="font-size: 1.25rem; padding-top: 3px;" href="<?php echo $base_URL . "/pages/notifications.php" ?>">
                                <span class="badge bg-danger"><?php $notifications = countNotifications();
                                                                    echo ($notifications > 0 ? $notifications : "");
                                                                     ?></span></a>
                            </div>
                        </li>                    
                        <!-- Logout -->
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownMenuLink2" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                Hello, <?php echo $_SESSION['CURRENT_USER']['user_name']; ?>!
                            </a>
                            <ul class="dropdown-menu me-auto mb-2 mb-md-0" aria-labelledby="navbarDropdownMenuLink2">
                                <?php
                                // Check if we are viewing as a tenant or an admin/landlord
                                    if ($_SESSION['CURRENT_USER']['user_role_code'] == 'tenant') { ?>
                                        <li><a class="dropdown-item" href="<?php echo $base_URL . "/pages/tenant_profile.php" ?>">My Profile</a></li>
                                    <?php
                                    }
                                    ?>
                                <li><a class="dropdown-item" href="<?php echo $base_URL . "/pages/change_password.php" ?>">Change Password</a></li>
                                <li><a class="dropdown-item" href="<?php echo $base_URL . "/pages/logout.php" ?>">Logout</a></li>
                            </ul>
                        </li>
                    </ul>
                </div>
            <?php
            }
            ?>
        </div>
    </div>
</nav>
<?php

// Login
function login($user_id)
{

    // connect
    $dbc = connectDB();

    $qry = "select
                u.user_id
                , u.email
                , u.user_role_code
                , u.status_code
                , u.tenant_id
                , u.landlord_id
        from users as u
        where u.user_id = :user_id";

    $stmt = $dbc->prepare($qry);
    if (!$stmt) {
        echo "<p>Error in display prepare: " . $dbc->errorCode() . "</p>\n<p>Message " . implode($dbc->errorInfo()) . "</p>\n";
        exit(1);
    }

    // set data, doing this way handles SQL injections
    $data = array(":user_id" => $user_id);

    $status = $stmt->execute($data);

    if ($status) {
        if ($stmt->rowCount() > 0) {
            // Store USER row
            $_SESSION['CURRENT_USER'] = $stmt->fetch(PDO::FETCH_ASSOC);
        }
    } else {
        echo "<p>Error in display execute " . $stmt->errorCode() . "</p>\n<p>Message " . implode($stmt->errorInfo()) . "</p>\n";
        exit(1);
    }
}
?>