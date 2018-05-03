<!-- author : Amir Al-Hafiz -->
<?php
   //including the database connection file
   include("config.php");
    
   //getting id of the data from url
   $id = $_GET['id'];
    
   //deleting the row from table
   $result = mysqli_query($mysqli, "DELETE FROM wdg_users WHERE id=$id");
    
   //redirecting to the display page (index.php in our case)
   header("Location:index.php");
   ?>