<?php
session_start();
include_once("./pages/check_session.php");
?>
<!-- 
    Title:       index.php
    Application: RentalBuddy
    Purpose:     Home page 
    Author:      T. Kim, Group 5, INFO-5139-01-21W
    Date:        March 7th, 2021 (January 30th, 2021) 

    20210307     GPB     Login checks
    20210312     GPB     Added bootstrap icons    
-->

<!doctype html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="description" content="RentalBuddy Home">
  <meta name="author" content="Group 5">
  <meta name="generator" content="Hugo 0.79.0">

  <title>RentalBuddy - Home</title>

  <link rel="canonical" href="https://getbootstrap.com/docs/5.0/examples/starter-template/">


  <!-- Bootstrap core CSS -->
  <link rel="stylesheet" href="vendor/twbs/bootstrap/dist/css/bootstrap.min.css">

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

    .hero-image {
      color: white;
      background-image: url("/images/sunset-forks.jpg"); /* The image used */
      background-color: #cccccc; /* Used if the image is unavailable */
      background-position: center; /* Center the image */
      height: 700px;
      background-repeat: no-repeat; /* Do not repeat the image */
      background-size: cover; /* Resize the background image to cover the entire container */
      position: relative;
    }

    .hero-text {
      text-align: center;
      position: absolute;
      top: 50%;
      left: 50%;
      transform: translate(-50%, -50%);
      color: white;
      background-color: black;
      opacity: 0.65;
    }

    .hero-text h3 {
      opacity: 2.0;
    } 

  </style>


  <!-- Custom styles for this template -->
  <link href="css/starter-template.css" rel="stylesheet">
</head>

<body>
  <?php
  //define('_SERVER_PATH_', str_replace(basename(__FILE__), "", realpath(__FILE__)));
  require('pages/navigationMenu.php');

  ?>

  <main class="container">


<!-- 
    <div class="starter-template text-center py-5 px-3">
      <h1>Live Demo!</h1>
      <p class="lead">Please visit our live demo site on AWS.<br> <a href="https://rental.fanshawe21w.tk/"> https://rental.fanshawe21w.tk/ </a><br>Using Travis, AWS Code deploy, and S3, Github's master branch commit is reflected in real time.</p>
    </div> -->

  <div class="starter-template text-center py-5 px-3 hero-image">
    <div class="hero-text starter-template text-center py-5 px-3">
        <h1 style="font-size:50px">Live Demo!</h1>
        <h3>Please visit our live demo site on AWS:</h3>
        <a href="https://rental.fanshawe21w.tk/"> https://rental.fanshawe21w.tk/ </a><br>
        <p>Using Travis, AWS Code deploy, and S3, Github's master branch commit is reflected in real time.</p>
    </div>
  </div>

  </main><!-- /.container -->

  <script src="vendor/twbs/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
  <link href="./node_modules/bootstrap-icons/font//bootstrap-icons.css" rel="stylesheet">

</body>

</html>