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
</head>

<body>
    <div class="container">
        <?php
  require '../vendor/autoload.php';

  //define('_SERVER_PATH_', str_replace(basename(__FILE__), "", realpath(__FILE__)));
  include 'navigationMenu.php';

  dump($_POST);

  if(isset($_POST['request'])){
    inputPage();
  }else{
    viewPage();
  }

function inputPage(){
  ?>
        <form method="POST">
            <div class="row mb-3">
                <h3>Contact Info</h3>
                <h6>After Sprint2 or 3, we need to change this form that read data from session and user database</h6>
            </div>
            <div class="row mb-3">
                <label for="name" class="col-sm-2 col-form-label">Name</label>
                <div class="col-sm-10">
                    <input type="text" class="form-control" id="name">
                </div>
            </div>
            <div class="row mb-3">
                <label for="inputEmail3" class="col-sm-2 col-form-label">Email</label>
                <div class="col-sm-10">
                    <input type="email" class="form-control" id="inputEmail3">
                </div>
            </div>
            <div class="row mb-3">
                <label for="phone" class="col-sm-2 col-form-label">Phone</label>
                <div class="col-sm-10">
                    <div class="row">
                        <div class="col-md">
                            <input type="text" class="form-control" id="phone" maxlength="3">
                        </div>-
                        <div class="col-md">
                            <input type="text" class="form-control" id="phone" maxlength="4">
                        </div>-
                        <div class="col-md">
                            <input type="text" class="form-control" id="phone" maxlength="4">
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
                    <select class="form-select" aria-label="reqType">
                        <option selected>Request Type</option>
                        <option value="1">One</option>
                        <option value="2">Two</option>
                        <option value="3">Three</option>
                    </select>
                </div>
            </div>
            <div class="row mb-3">
                <label for="content" class="col-sm-2 col-form-label">Request Content</label>
                <div class="col-sm-10">
                <textarea class="form-control" id="content" rows="3"></textarea>
                </div>
            </div>
            
            <button type="submit" class="btn btn-primary" name="submit">Request</button>
        </form>



        <?php
}

function viewPage(){
  ?>
        <main class="container">

            <div class="starter-template text-center py-5 px-3">
                <h1>Service Request Main Page</h1>
                <p class="lead">This is a view Page</p>

            </div>
            <form method="POST">
                <div class="btn-group" role="group" aria-label="Basic mixed styles example">
                    <button type="button" class="btn btn-danger">Left</button>
                    <button type="button" class="btn btn-warning">Middle</button>
                    <button type="submit" class="btn btn-success" id="request" name="request">Request
                        Maintenance</button>
                </div>
            </form>

            <?php
}
  
?>
    </div>
    <script src="../vendor/twbs/bootstrap/dist/js/bootstrap.bundle.min.js"></script>


</body>

</html>