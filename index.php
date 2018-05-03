<!-- author : Amir Al-Hafiz -->
<?php
   // including the database connection file
   include_once ("config.php");
   
   // fetching data in descending order (lastest entry first)
   // $result = mysql_query("SELECT * FROM users ORDER BY id DESC"); // mysql_query is deprecated
   $result = mysqli_query($mysqli, "SELECT * FROM wdg_users ORDER BY id DESC"); // using mysqli_query instead
   ?>
<!DOCTYPE html>
<html lang="en">
   <head>
      <!-- Required meta tags -->
      <meta charset="utf-8">
      <meta http-equiv="X-UA-Compatible" content="IE=edge">
      <meta name="viewport" content="width=device-width, initial-scale=1">
      <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
      <title>W.D.G</title>
      <!-- Bootstrap CSS -->
      <!--<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">-->
      <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
      <link href="assets/css/bootstrap.css" rel="stylesheet">
      <link href="assets/css/sticky.css" rel="stylesheet">
      <!-- Scripting -->
      <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
      <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
      <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
      <!-- Include all compiled plugins (below), or include individual files as needed -->
      <script src="/assets/js/bootstrap.min.js"></script>
      <script src="/assets/js/sticky.js"></script>
      <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
      <!-- WARNING: Respond.js doesn't work if you view the pphonenumber via file:// -->
      <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
      <![endif]-->
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
               <!--<li class="nav-item active">
                  <a class="nav-link" href="#">Home<span class="sr-only">(current)</span></a>
                  </li>-->
               <li class="nav-item">
                  <a class="nav-link" href="add.php">Add Member</a>
               </li>
               <li class="nav-item">
                  <a class="nav-link" href="dbcheck.php">DB Health</a>
               </li>
            </ul>
            <ul class="nav navbar-nav navbar-right">
               <li class="nav-item">
                  <a class="nav-link" href="index.php"><?php $count=mysqli_num_rows($result); echo $count . " member(s) has been added "; ?></a>
               </li>
               <li class="nav-item">
                  <a class="nav-link disabled" href="#">Help</a>
               </li>
               <!--<li class="nav-item">
                  <a class="nav-link disabled" href="#">XXXXXXX</a>
                  </li>
                  <li class="nav-item dropdown">
                  <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                  Dropdown
                  </a>
                  <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                     <a class="dropdown-item" href="#">XXXXXXX</a>
                     <a class="dropdown-item" href="#">XXXXXXX</a>
                     <div class="dropdown-divider"></div>
                     <a class="dropdown-item" href="#">XXXXXXX</a>
                  </div>
                  </li>-->
            </ul>
            <!--<form class="form-inline my-2 my-lg-0">
               <input class="form-control mr-sm-2" type="search" placeholder="Search" aria-label="Search">
               <button class="btn btn-outline-success my-2 my-sm-0" type="submit">Search</button>
               </form>-->
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
                     // while($res = mysql_fetch_array($result)) { // mysql_fetch_array is deprecated, we need to use mysqli_fetch_array
                     while ($res = mysqli_fetch_array($result)) {
                         echo "<tr>";
                         echo "<th scope='row'>" . $res['id'] . "</th>";
                         echo "<td>" . $res['name'] . "</td>";
                         echo "<td>" . $res['phonenumber'] . "</td>";
                         echo "<td>" . $res['email'] . "</td>";
                         echo "<td><a href=\"edit.php?id=$res[id]\">Edit</a> | <a href=\"delete.php?id=$res[id]\" onClick=\"return confirm('Are you sure you want to delete this member ?')\">Delete</a></td>";
                         echo "</tr>";
                     }
                     ?>
               </tbody>
            </table>
         </div>
      </div>
      <div class="container-fluid" id="footer">
         Simple example for Bootstrap 4, Php & MySqli application using ( Insert, Select, Update, Delete )
      </div>
      <!-- Optional JavaScript -->
      <!-- jQuery first, then Popper.js, then Bootstrap JS -->
      <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
      <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
      <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
   </body>
   <footer class="footer">
      <div class="container-fluid">
         <?php include_once ("footer.php"); ?>
      </div>
   </footer>
</html>