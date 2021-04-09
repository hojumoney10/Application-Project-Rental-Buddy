<!-- 
    File: JavaScript.js
    Author: T.Kim
    Date: Jan 30, 2021
    Description: Menu file. 
-->

<!-- 
    Title:       navigationMenu.php
    Application: RentalBuddy
    Purpose:     Handles navigation for the RentalBuddy site
    Author:      T. Kim, Group 5, INFO-5139-01-21W
    Date:        February 16th, 2021 (January 30th, 2021) 

    20210216    GPB     Added rental_properties.php to menu
-->

<?php 

$base_URL = ($_SERVER['HTTPS'] == 'on') ? 'https://' : 'http://';
//$base_URL .= ($_SERVER['SERVER_PORT'] != '80') ? $_SERVER['HTTP_HOST'].':'.$_SERVER['SERVER_PORT'] : $_SERVER['HTTP_HOST'];
$base_URL .= $_SERVER['HTTP_HOST'];
?>
<nav class="navbar navbar-expand-md navbar-dark bg-dark fixed-top">
    <div class="container-fluid">
        <a class="navbar-brand" href="<?php echo $base_URL."/index.php"?>">RentalBuddy</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarsExampleDefault"
            aria-controls="navbarsExampleDefault" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarsExampleDefault">
            <ul class="navbar-nav me-auto mb-2 mb-md-0">
                <li class="nav-item active">
                    <a class="nav-link" aria-current="page" href="<?php echo $base_URL."/index.php"?>">Home</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="<?php echo $base_URL.""?>">Tenants</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="<?php echo $base_URL."/pages/service_request.php"?>">Service Request(TEMP)</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="<?php echo $base_URL."/pages/landlords.php"?>">Landlords</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="<?php echo $base_URL."/pages/rental_properties.php"?>">Properties</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="<?php echo $base_URL . "/pages/leases.php" ?>">Leases</a>
                </li>
<<<<<<< Updated upstream
=======


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
                    <li class="nav-item">
                        <a class="nav-link" href="<?php echo $base_URL . "/pages/contact.php" ?>">Contact</a>
                    </li>
                <?php
                }
                ?>
>>>>>>> Stashed changes
            </ul>
        </div>
    </div>
</nav>
