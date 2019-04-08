<?php
  session_start();
  class MyDB extends SQLite3
  {
    function __construct()
    {
        $this->open('SMAAUCTS.db');
    }
  }
  $db = new MyDB();
  $user = $_SESSION['user'];
  if($user != 'Admin'){
    $query = "select profilepic from profile where uname = '".$user."'";
    $result = $db->query($query);
    if($result == False){
      echo "<h1>".$result.$db->lastErrorMsg()."</h1>";
    }
    else{
      $res = $result->fetchArray(SQLITE3_ASSOC);
      $pro = $res['profilepic'];
    }
  }
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <title>User-Dashboard</title>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <link rel="stylesheet" href="CSS/font-awesome-4.7.0/css/font-awesome.min.css">
  <link rel="stylesheet" href="CSS/profile.css">
  <link rel="stylesheet" href="CSS/profile-menu.css">
  <script type="text/javascript" src="JS/jquery.js"></script>
</head>
  <body>
    <div class="intro-half">
      <?php
        echo "<h1>Welcome ".$user." to Smart World</h1>";
        if($pro == ""){
          echo "<i class=\"fa fa-user-circle-o\" aria-hidden=\"true\"></i>";
          echo "<p id=\"sugg\">Make Your Profile complete By Uploading a profile photo.</p>";
        }
        else{
          echo "<img src=\"".$pro."\" alt=\"Profile Picture of \"".$user.">";
        }
      ?>
      <div class="share">
        <div class="toogle"></div>
        <ul>
          <li><a href="#"><i class="fa fa-home" aria-hidden="true"></i></a></li>
          <li><a href="#"><i class="fa fa-cog" aria-hidden="true"></i></a></li>
          <li><a href="#"><i class="fa fa-calendar" aria-hidden="true"></i></a></li>
          <li><a href="#"><i class="fa fa-trophy" aria-hidden="true"></i></a></li>
          <li id="log"><a href="#"><i class="fa fa-sign-out" aria-hidden="true"></i></a></li>
        </ul>
      </div>
    </div>
    <form method="post" id="logform" action="">
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
  <script type="text/javascript">
    $(document).ready(function(){
      $(".toogle").click(function(){
        $(".toogle").toggleClass("active");
        $(".share").toggleClass("active");
      });
      $(".share #log").click(function(){
        $('#logform').submit();
      });
    });
  </script>
</html>
