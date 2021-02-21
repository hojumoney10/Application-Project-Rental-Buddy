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
    <link rel="stylesheet" href="/vendor/twbs/bootstrap/dist/css/bootstrap.min.css">

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
    <link href="css/starter-template.css" rel="stylesheet">
  </head>
  <body>
    <?php
    //define('_SERVER_PATH_', str_replace(basename(__FILE__), "", realpath(__FILE__)));
    include 'pages/navigationMenu.php';
    ?>

<main class="container">

  <div class="starter-template text-center py-5 px-3">
    <h1>Live Demo!</h1>
    <p class="lead">Please visit our live demo site on AWS.<br> <a href="https://rental.fanshawe21w.tk/"> https://rental.fanshawe21w.tk/ </a><br>Using Travis, AWS Code deploy, and S3, Github's master branch commit is reflected in real time.</p>
  </div>

</main><!-- /.container -->


<script src="/vendor/twbs/bootstrap/dist/js/bootstrap.bundle.min.js"></script>

      
  </body>
</html>
