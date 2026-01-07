<?php
declare(strict_types=1);

require_once "config.php";

$users = $userRepo->getAllUsers();
$userCount = $userRepo->getUserCount();
?>
<!DOCTYPE html>
<html lang="en">
   <head>
      <meta charset="utf-8">
      <meta http-equiv="X-UA-Compatible" content="IE=edge">
      <meta name="viewport" content="width=device-width, initial-scale=1">
      <title>W.D.G - Modern Member Management</title>
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
         <img src="assets/images/welcome.png" class="img-fluid" alt="Welcome">
      </div>
      <nav class="navbar navbar-expand-lg navbar-light bg-light" data-toggle="sticky-onscroll">
         <a class="nav-link active" href="index.php">W.D.G</a>
         <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent">
            <span class="navbar-toggler-icon"></span>
         </button>
         <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav mr-auto">
               <li class="nav-item"><a class="nav-link" href="add.php">Add Member</a></li>
               <li class="nav-item"><a class="nav-link" href="dbcheck.php">DB Health</a></li>
            </ul>
            <ul class="nav navbar-nav navbar-right">
               <li class="nav-item">
                  <span class="nav-link"><?= htmlspecialchars((string)$userCount) ?> member(s) added</span>
               </li>
            </ul>
         </div>
      </nav>
   </header>
   <body>
      <div class="container-fluid" id="box">
         <div class="table-responsive">
            <table class="table table-hover" style="background: #fff; text-align: center;">
               <thead class="thead-dark">
                  <tr>
                     <th>ID</th>
                     <th>Name</th>
                     <th>Phone</th>
                     <th>Email</th>
                     <th>Actions</th>
                  </tr>
               </thead>
               <tbody>
                  <?php foreach ($users as $user): ?>
                     <tr>
                        <td><?= htmlspecialchars((string)$user['id']) ?></td>
                        <td><?= htmlspecialchars($user['name']) ?></td>
                        <td><?= htmlspecialchars($user['phonenumber']) ?></td>
                        <td><?= htmlspecialchars($user['email']) ?></td>
                        <td>
                           <a href="edit.php?id=<?= (int)$user['id'] ?>" class="btn btn-sm btn-outline-primary">Edit</a>
                           <a href="delete.php?id=<?= (int)$user['id'] ?>" class="btn btn-sm btn-outline-danger" onclick="return confirm('Are you sure?')">Delete</a>
                        </td>
                     </tr>
                  <?php endforeach; ?>
               </tbody>
            </table>
         </div>
      </div>
      <footer class="footer mt-auto py-3 bg-light">
         <div class="container-fluid text-center">
            <?php include_once ("footer.php"); ?>
         </div>
      </footer>
   </body>
</html>
