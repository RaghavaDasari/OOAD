<?php
  class MyDB extends SQLite3
  {
    function __construct()
    {
        $this->open('SMAAUCTS.db');
    }
  }
  $flag = 2;
  if(isset($_POST['login']))
  {
    session_start();
    $_SESSION['error'] = "";
    $db = new MyDB();
    $query = "select * from users where uname = '".$_POST['username']."'";
    $result = $db->query($query);
    $res = $result->fetchArray(SQLITE3_ASSOC);
    if($res != "")
    {
      if($_POST['password'] == $res['password'])
      {
        $_SESSION['user'] = $_POST['username'];
        $flag = 1;
      }
      else{
        $_SESSION['error'] = "Sorry! wrong password.";
        header("Location: form.php");
      }
    }
    else{
      $_SESSION['error'] = "User-Name Not Found...!";
      header("Location: form.php");
    }
  }
  if(isset($_POST['signup']))
  {
    session_start();
    $_SESSION['first'] = $_POST['first'];
    $_SESSION['last'] = $_POST['last'];
    $_SESSION['email'] = $_POST['email'];
    header("Location: register.php");
  }
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="CSS/form.css">
    <script type="text/javascript" src="JS/jquery.js"></script>
    <script type="text/javascript" src="JS/form.js"></script>
    <title>LogIn to SMA-AUCTS</title>
  </head>
  <body>
    <div id="box">
      <div id="main"></div>
      <form id="loginform" method="post" action="form.php">
        <h1>LOGIN</h1>
        <input type="text" name="username" autocomplete="off" required/><lable id="name">User Name</lable><br>
        <input type="password" name="password" required/><lable id="pass">Password</lable><br>
        <input type="submit" name='login' value="LOGIN">
        <span><?php
        session_start();
         echo $_SESSION['error']
          ?>
        </span>
      </form>
      <form id="signupform" method="post" action="">
        <h1>SIGN UP</h1>
        <input type="text" name="first" required/><lable id="fname">First Name</lable><br>
        <input type="text" name="last" required/><lable id="lname">Last Name</lable><br>
        <input type="email" name="email" required/><lable id="email">Email</lable><br>
        <input type="submit" name='signup' value="SIGN UP">
      </form>
      <div id="login_msg">Have an account?</div>
      <div id="signup_msg">Don't have an account?</div>
      <button id="login_btn">LOGIN</button>
      <button id="signup_btn">SIGN UP</button>
    </div>
    <?php
      if($flag == 1){
        echo '<script>
          window.location.href = "profile.php";
        </script>';
      }
     ?>
  </body>
</html>
