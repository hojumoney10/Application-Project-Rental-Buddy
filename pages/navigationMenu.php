<!-- 
    File: JavaScript.js
    Author: T.Kim
    Date: Jan 30, 2021
    Description: Menu file. 
-->
<?php 

$base_URL = ($_SERVER['HTTPS'] == 'on') ? 'https://' : 'http://';
//$base_URL .= ($_SERVER['SERVER_PORT'] != '80') ? $_SERVER['HTTP_HOST'].':'.$_SERVER['SERVER_PORT'] : $_SERVER['HTTP_HOST'];
$base_URL .= $_SERVER['HTTP_HOST'];
?>
<nav class="navbar navbar-expand-md navbar-dark bg-dark fixed-top">
  <div class="container-fluid">
    <a class="navbar-brand" href="<?php echo $base_URL."/index.php"?>">RentalBuddy</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarsExampleDefault" aria-controls="navbarsExampleDefault" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="navbarsExampleDefault">
      <ul class="navbar-nav me-auto mb-2 mb-md-0">
        <li class="nav-item active">
          <a class="nav-link" aria-current="page" href="<?php echo $base_URL."/index.php"?>">Home</a>
        </li>
        <li class="nav-item">

          <a class="nav-link" href="<?php echo $base_URL.""?>">Tenants</a>

          <a class="nav-link" href="<?php echo $base_URL."/pages/service_request.php"?>">Service Request</a>>>>>>>> Stashed changes
        </li>
        <li class="nav-item">
          <a class="nav-link" href="<?php echo $base_URL."/pages/landlords.php"?>">Landlords</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="<?php echo $base_URL.""?>">Properties</a>
        </li>
      </ul>
    </div>
  </div>
</nav>