<!-- author : Amir Al-Hafiz -->
<?php
   // including the database connection file
   include_once("config.php");
    
   if(isset($_POST['update']))
   {    
       $id = $_POST['id'];
       
       $name=$_POST['name'];
       $phonenumber=$_POST['phonenumber'];
       $email=$_POST['email'];    
       
       //updating the table
       $result = mysqli_query($mysqli, "UPDATE wdg_users SET name='$name',phonenumber='$phonenumber',email='$email', date=now() WHERE id=$id");
           
       //redirectig to the display pphonenumber. In our case, it is index.php
       header("Location: index.php");
   }
   
   //getting id from url
   $id = $_GET['id'];
    
   //selecting data associated with this particular id
   $result = mysqli_query($mysqli, "SELECT * FROM wdg_users WHERE id=$id");
    
   while($res = mysqli_fetch_array($result))
   {
       $name = $res['name'];
       $phonenumber = $res['phonenumber'];
       $email = $res['email'];
   }
   ?>
<!DOCTYPE html>
<html lang="en">
   <head>
      <!-- Required meta tags -->
      <meta charset="utf-8">
      <meta http-equiv="X-UA-Compatible" content="IE=edge">
      <meta name="viewport" content="width=device-width, initial-scale=1">
      <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
      <title>W.D.G - Edit Member</title>
      <!-- Bootstrap CSS -->
      <!--<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">-->
      <link href="assets/css/bootstrap.css" rel="stylesheet">
      <link href="assets/css/sticky.css" rel="stylesheet">
      <!-- Scripting -->
      <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
      <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
      <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
      <!-- Include all compiled plugins (below), or include individual files as needed -->
      <script src="assets/js/bootstrap.min.js"></script>
      <script src="assets/js/sticky.js"></script>
      <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
      <!-- WARNING: Respond.js doesn't work if you view the pphonenumber via file:// -->
      <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
      <![endif]-->
   </head>
   <header>
      <nav class="navbar navbar-expand-lg navbar-light bg-light" data-toggle="sticky-onscroll">
         <a class="nav-link" href="index.php">W.D.G<span class="sr-only">(current)</span></a>
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
                  <a class="nav-link disabled" href="#Disabled"><?php $count=mysqli_num_rows($result); echo $count . " data to update "; ?></a>
               </li>
               <li class="nav-item">
                  <a class="nav-link disabled" href="#Disabled">Help</a>
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
      <div class="container-fluid" id="formbox">
         <div class="row">
            <div class="col-md-6 offset-md-3">
               <h4>Edit details :</h4>
               <form action="" method="post" name="form1">
                  <div class="form-group">
                     <label for="inputName">Name</label>
                     <input type="text" class="form-control" name="name" value="<?php echo $name;?>">
                  </div>
                  <div class="form-row">
                     <div class="form-group col-md-6">
                        <label for="inputPhone4">Phone Number</label>
                        <input type="text" class="form-control" name="phonenumber" value="<?php echo $phonenumber;?>">
                     </div>
                     <div class="form-group col-md-6">
                        <label for="inputEmail4">Email</label>
                        <input type="email" class="form-control" name="email" value="<?php echo $email;?>">
                     </div>
                     <div class="form-group col-md-6">
                        <input type="hidden" class="form-control" name="id" value="<?php echo $_GET['id'];?>">
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