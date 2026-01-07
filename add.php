<!-- author : Amir Al-Hafiz -->
<?php
   include_once("config.php");
   
   $selectresult = pg_query($conn, "SELECT * FROM wdg_users ORDER BY id DESC"); 
    
   if(isset($_POST['Submit'])) {    
       $name = pg_escape_string($conn, $_POST['name']);
       $phonenumber = pg_escape_string($conn, $_POST['phonenumber']);
       $email = pg_escape_string($conn, $_POST['email']);
           
       $result = pg_query($conn, "INSERT INTO wdg_users(name,phonenumber,email,date) VALUES('$name','$phonenumber','$email', now())");
           
       header("location:add.php?submitted=successfully");
       exit;
   }
   ?>
<!DOCTYPE html>
<html lang="en">
   <head>
      <meta charset="utf-8">
      <meta http-equiv="X-UA-Compatible" content="IE=edge">
      <meta name="viewport" content="width=device-width, initial-scale=1">
      <title>W.D.G - Add Member</title>
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
                  <a class="nav-link active" href="add.php">Add Member</a>
               </li>
               <li class="nav-item">
                  <a class="nav-link" href="dbcheck.php">DB Health</a>
               </li>
            </ul>
            <ul class="nav navbar-nav navbar-right">
               <li class="nav-item">
                  <a class="nav-link" href="index.php"><?php $count=pg_num_rows($selectresult); echo $count . " member(s) has been added "; ?></a>
               </li>
               <li class="nav-item">
                  <a class="nav-link disabled" href="#">Help</a>
               </li>
            </ul>
         </div>
      </nav>
   </header>
   <body>
      <div class="container-fluid" id="formbox">
         <div class="row">
            <div class="col-md-6 offset-md-3">
               <h4>Add new member :</h4>
               <form action="" method="post" name="form1">
                  <div class="form-group">
                     <label for="inputName">Name</label>
                     <input type="text" class="form-control" name="name" required>
                  </div>
                  <div class="form-row">
                     <div class="form-group col-md-6">
                        <label for="inputPhone4">Phone Number</label>
                        <input type="text" class="form-control" name="phonenumber" required>
                     </div>
                     <div class="form-group col-md-6">
                        <label for="inputEmail4">Email</label>
                        <input type="email" class="form-control" name="email" required>
                     </div>
                  </div>
                  <button type="submit" class="btn btn-primary" name="Submit">Add New Member</button>
               </form>
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
