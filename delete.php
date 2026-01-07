<!-- author : Amir Al-Hafiz -->
<?php
   include("config.php");
    
   $id = intval($_GET['id']);
    
   $result = pg_query($conn, "DELETE FROM wdg_users WHERE id=$id");
    
   header("Location:index.php");
   exit;
   ?>
