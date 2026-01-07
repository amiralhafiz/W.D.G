<!-- author : Amir Al-Hafiz -->
<!DOCTYPE html>
<html lang="en">
   <head>
      <meta charset="utf-8">
      <meta http-equiv="X-UA-Compatible" content="IE=edge">
      <meta name="viewport" content="width=device-width, initial-scale=1">
      <title>Database Health</title>
      <link href="assets/css/bootstrap.css" rel="stylesheet">
      <link href="assets/css/sticky.css" rel="stylesheet">
      <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
      <script src="assets/js/bootstrap.min.js"></script>
      <script src="assets/js/sticky.js"></script>
   </head>
   <header>
      <nav class="navbar navbar-expand-lg navbar-light bg-light" data-toggle="sticky-onscroll">
         <a class="nav-link" href="index.php">W.D.G<span class="sr-only">(current)</span></a>
         <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
         <span class="navbar-toggler-icon"></span>
         </button>
         <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav mr-auto">
               <li class="nav-item">
                  <a class="nav-link" href="add.php">Add Member</a>
               </li>
               <li class="nav-item">
                  <a class="nav-link active" href="dbcheck.php">DB Health</a>
               </li>
            </ul>
            <ul class="nav navbar-nav navbar-right">
               <li class="nav-item">
                  <a class="nav-link disabled" href="#">Help</a>
               </li>
            </ul>
         </div>
      </nav>
   </header>
   <body>
      <div class="container-fluid">
         <div class="row">
            <div class="col-md-6 offset-md-3">
               <?php
                  $database_url = getenv('DATABASE_URL');
                  $conn = pg_connect($database_url);
                  
                  if ($conn) {
                   echo "<div class='container' style='text-align:center;'><img src='assets/images/connect.png' class='img-fluid'>" . PHP_EOL . "</div><br>";
                   echo "<div class='container'><h4><b><span style='color:green;'>Database Connected</span></b></h4>" . PHP_EOL;
                   echo "<b>Success</b> :<br> A proper connection to PostgreSQL was made! The database is great." . PHP_EOL . "<br>";
                   echo "<b>Host information</b> :<br> " . pg_host($conn) . PHP_EOL . "</div>";
                  } else {
                   echo "<div class='container' style='text-align:center;'><img src='assets/images/disconnect.png' class='img-fluid'>" . PHP_EOL . "</div><br>";
                   echo "<div class='container' style='padding-bottom:50px;'><h4><b><span style='color:red;'>No database connection</span></b></h4>" . PHP_EOL;
                   echo "<b>Failed</b> : Unable to connect to PostgreSQL" . PHP_EOL . "<br>";
                   echo "<b>Debugging Error</b> : " . pg_last_error() . PHP_EOL . "</div>";
                  }
                  ?>
            </div>
         </div>
      </div>
   </body>
   <footer class="footer">
      <div class="container-fluid">
         <?php include_once ("footer.php"); ?>
      </div>
   </footer>
</html>
