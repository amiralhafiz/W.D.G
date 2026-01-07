<!-- author : Amir Al-Hafiz -->
<?php
   $database_url = getenv('DATABASE_URL');
   
   $conn = pg_connect($database_url);
   
   if (!$conn) {
    header('Location: dbcheck.php');
    echo "<div class='container' style='text-align:center;'><img src='assets/images/disconnect.png' class='img-fluid'>" . PHP_EOL . "</div><br>";
    echo "<div class='container' style='padding-bottom:50px;'><h4><b><span style='color:red;'>No database connection</span></b></h4>" . PHP_EOL;
    echo "<b>Failed</b> : Unable to connect to PostgreSQL or credential database incorrect" . PHP_EOL . "<br>";
    echo "<b>Debugging Error</b> : " . pg_last_error() . PHP_EOL . "</div>";
    exit;
   }
   ?>
