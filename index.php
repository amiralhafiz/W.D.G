<!-- author : Amir Al-Hafiz -->
<?php
   include_once ("config.php");
   
   $result = pg_query($conn, "SELECT * FROM wdg_users ORDER BY id DESC");
   ?>
<!DOCTYPE html>
<html lang="en">
   <head>
      <meta charset="utf-8">
      <meta http-equiv="X-UA-Compatible" content="IE=edge">
      <meta name="viewport" content="width=device-width, initial-scale=1">
      <title>W.D.G</title>
      <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
      <link href="assets/css/bootstrap.css" rel="stylesheet">
      <link href="assets/css/sticky.css" rel="stylesheet">
      <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
      <script src="assets/js/popper.min.js"></script> 
      <script src="assets/js/bootstrap.min.js"></script> 
      <script src="assets/js/sticky.js"></script>
   </head>
   <header>
      <div id="top">
         <img src="assets/images/welcome.png" class="img-fluid">
      </div>
      <nav class="navbar navbar-expand-lg navbar-light bg-light" data-toggle="sticky-onscroll">
         <a class="nav-link active" href="index.php">W.D.G<span class="sr-only">(current)</span></a>
         <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
         <span class="navbar-toggler-icon"></span>
         </button>
         <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav mr-auto">
               <li class="nav-item">
                  <a class="nav-link" href="add.php">Add Member</a>
               </li>
               <li class="nav-item">
                  <a class="nav-link" href="dbcheck.php">DB Health</a>
               </li>
            </ul>
            <ul class="nav navbar-nav navbar-right">
               <li class="nav-item">
                  <a class="nav-link" href="index.php"><?php $count=pg_num_rows($result); echo $count . " member(s) has been added "; ?></a>
               </li>
               <li class="nav-item">
                  <a class="nav-link disabled" href="#">Help</a>
               </li>
            </ul>
         </div>
      </nav>
   </header>
   <body>
      <div class="container-fluid" id="box">
         <div class="table-responsive">
            <table class="table table-hover" style="background: #fff;text-align:center;">
               <thead class="thead-dark">
                  <tr>
                     <th scope="col">ID</th>
                     <th scope="col">Name</th>
                     <th scope="col">Phone</th>
                     <th scope="col">Email</th>
                     <th scope="col">Remarks</th>
                  </tr>
               </thead>
               <tbody>
                  <?php
                     while ($res = pg_fetch_assoc($result)) {
                         echo "<tr>";
                         echo "<th scope='row'>" . $res['id'] . "</th>";
                         echo "<td>" . $res['name'] . "</td>";
                         echo "<td>" . $res['phonenumber'] . "</td>";
                         echo "<td>" . $res['email'] . "</td>";
                         echo "<td><a href=\"edit.php?id=" . $res['id'] . "\">Edit</a> | <a href=\"delete.php?id=" . $res['id'] . "\" onClick=\"return confirm('Are you sure you want to delete this member ?')\">Delete</a></td>";
                         echo "</tr>";
                     }
                     ?>
               </tbody>
            </table>
         </div>
      </div>
      <div class="container-fluid" id="footer">
         Simple example for Bootstrap 4, Php & PostgreSQL application using ( Insert, Select, Update, Delete )
      </div>
   </body>
   <footer class="footer">
      <div class="container-fluid">
         <?php include_once ("footer.php"); ?>
      </div>
   </footer>
</html>
