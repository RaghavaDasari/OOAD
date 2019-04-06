<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <title>User-Dashboard</title>
</head>
  <body>
    <?php
      session_start();
      echo "<h1>Welcome ".$_SESSION['user']." to Smart World</h1>";
    ?>
    <form method="post" action="">
      <input type="submit" name="submit" value="logout">
    </form>
 </body>
 <?php
 if(isset($_POST['submit'])){
   if(session_destroy())
   {
     header("Location: index.html");
   }
 }
  ?>
</html>
