<!-- author : Amir Al-Hafiz -->
<?php
   include_once("config.php");
    
   if(isset($_POST['update']))
   {    
       $id = intval($_POST['id']);
       
       $name = pg_escape_string($conn, $_POST['name']);
       $phonenumber = pg_escape_string($conn, $_POST['phonenumber']);
       $email = pg_escape_string($conn, $_POST['email']);    
       
       $result = pg_query($conn, "UPDATE wdg_users SET name='$name',phonenumber='$phonenumber',email='$email', date=now() WHERE id=$id");
           
       header("Location: index.php");
       exit;
   }
   
   $id = intval($_GET['id']);
    
   $result = pg_query($conn, "SELECT * FROM wdg_users WHERE id=$id");
   
   $name = '';
   $phonenumber = '';
   $email = '';
    
   while($res = pg_fetch_assoc($result))
   {
       $name = $res['name'];
       $phonenumber = $res['phonenumber'];
       $email = $res['email'];
   }
   ?>
<!DOCTYPE html>
<html lang="en">
   <head>
      <meta charset="utf-8">
      <meta http-equiv="X-UA-Compatible" content="IE=edge">
      <meta name="viewport" content="width=device-width, initial-scale=1">
      <title>W.D.G - Edit Member</title>
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
                  <a class="nav-link" href="dbcheck.php">DB Health</a>
               </li>
            </ul>
            <ul class="nav navbar-nav navbar-right">
               <li class="nav-item">
                  <a class="nav-link disabled" href="#Disabled"><?php $count=pg_num_rows($result); echo $count . " data to update "; ?></a>
               </li>
               <li class="nav-item">
                  <a class="nav-link disabled" href="#Disabled">Help</a>
               </li>
            </ul>
         </div>
      </nav>
   </header>
   <body>
      <div class="container-fluid" id="formbox">
         <div class="row">
            <div class="col-md-6 offset-md-3">
               <h4>Edit details :</h4>
               <form action="" method="post" name="form1">
                  <div class="form-group">
                     <label for="inputName">Name</label>
                     <input type="text" class="form-control" name="name" value="<?php echo htmlspecialchars($name);?>">
                  </div>
                  <div class="form-row">
                     <div class="form-group col-md-6">
                        <label for="inputPhone4">Phone Number</label>
                        <input type="text" class="form-control" name="phonenumber" value="<?php echo htmlspecialchars($phonenumber);?>">
                     </div>
                     <div class="form-group col-md-6">
                        <label for="inputEmail4">Email</label>
                        <input type="email" class="form-control" name="email" value="<?php echo htmlspecialchars($email);?>">
                     </div>
                     <div class="form-group col-md-6">
                        <input type="hidden" class="form-control" name="id" value="<?php echo intval($_GET['id']);?>">
                     </div>
                  </div>
                  <button type="submit" class="btn btn-primary" name="update">Update Details</button>
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
