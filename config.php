<!-- author : Amir Al-Hafiz -->
<?php
   $servername = "";
   $username = "";
   $password = "";
   $dbname = "w.d.g_base";
    
   $mysqli = mysqli_connect($servername, $username, $password, $dbname); 
   
   // Check connection
   if (!$mysqli) {
    header('Location: dbcheck.php');
    // Not Connect
    echo "<div class='container' style='text-align:center;'><img src='assets/images/disconnect.png' class='img-fluid'>" . PHP_EOL . "</div><br>";
    echo "<div class='container' style='padding-bottom:50px;'><h4><b><span style='color:red;'>No database connection</span></b></h4>" . PHP_EOL;
    echo "<b>Failed</b> : Unable to connect to MySQL or credential database incorrect" . PHP_EOL . "<br>";
    echo "<b>Debugging Error</b> : " . mysqli_connect_error() . PHP_EOL . "<br>";
    echo "<b>Debugging Errno</b> : " . mysqli_connect_errno() . PHP_EOL . "</div>";
    exit;
   }
   ?>