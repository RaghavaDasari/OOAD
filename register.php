<?php
  session_start();
  class MyDB extends SQLite3
  {
    function __construct()
    {
        $this->open('SMAAUCTS.db');
    }
  }
  if(isset($_POST['register'])){
    $db = new MyDB();
    $queryusers = "INSERT INTO users values('".$_POST['username']."','".$_POST['passw']."')";
    $result = $db->exec($queryusers);
    if($result == False){
      echo "<h1>Try another userName : '".$_POST['username']."' already exists</h1>";
    }
    else{
      $ImageName = $_FILES['photo']['name'];
      $fileElementName = 'photo';
      $path = 'ProfilePhotos/';
      $location = "-";
      if($_FILES['photo']['name'] != ""){
        $location = $path . $_FILES['photo']['name'];
        move_uploaded_file($_FILES['photo']['tmp_name'], $location);
      }
      $queryprofile = "INSERT INTO profile values('".$_POST['fname']." ".$_POST['lname']."','".$_POST['username']."',".$_POST['phone'].",'".$_POST['email']."','".$_POST['Gender']."','".$_POST['dob']."','".$_POST['address']."','".$location."')";
      $result = $db->exec($queryprofile);
      if($result == False)
      {
        echo "<h1>".$db->lastErrorMsg()."</h1>";
      }
      else{
        $_SESSION['user'] = $_POST['username'];
        $_SESSION['pp'] = $location;
        header("Location: profile.php");
      }
    }
  }
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <title>Registration-Portal</title>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <link rel="stylesheet" href="CSS/register.css">
  <link rel="stylesheet" href="CSS/font-awesome-4.7.0/css/font-awesome.css">
</head>
  <body>
    <div class="filter"></div>
    <form method="post" action="register.php" enctype="multipart/form-data">
      <div class="icon">
        <i class="fa fa-user-circle-o" aria-hidden="true"></i>
      </div>
      <input type="text" name="fname" autocomplete="off" value="<?php echo $_SESSION['first']?>" placeholder="First Name" required><br>
      <input type="text" name="lname" autocomplete="off" value="<?php echo $_SESSION['last']?>" placeholder="Last Name" required><br>
      <input type="text" name="username" autocomplete="off" value="" placeholder="User Name" required><br>
      <input type="password" id="password" name="passw" placeholder="Password" required><br>
      <input type="password" id="pwd" name="reenter" placeholder="Re-Enter Password" required><i class="fa fa-eye-slash" id="reenter" aria-hidden="true"></i><br>
      <span id="errmsg">Make sure of strong password...!</span><br>
      <input type="text" name="phone" autocomplete="off" pattern="[0-9]{10}" placeholder="Contact" required>
      <input type="email" name="email" autocomplete="off" placeholder="Email-Id" value="<?php echo $_SESSION['email']?>" required><br>
      <div class="box">
        Gender:
        <label>
          <input type="radio" name="Gender" value="M" checked>
          <span class="male">Male</span>
        </label>
        <label>
          <input type="radio" name="Gender" value="F">
          <span class="female">Female</span>
        </label>
      </div>
      <input type="date" name="dob" placeholder="Date of Birth" required><br>
      <textarea name="address" rows="8" cols="80" placeholder="Address for Communication" required></textarea><br>
      <input type="file" name="photo" id="photo"><label for="photo">Profile Pic</label><br>
      <input type="submit" name="register" value="Get Started">
    </form>
    <script type="text/javascript" src="JS/jquery.js"></script>
    <script type="text/javascript">
      $(document).ready(function(){
        $('#reenter').on("click",function(){
          var $pwd = $("#pwd");
          $('#reenter').toggleClass("fa-eye-slash");
          $('#reenter').toggleClass("fa-eye");
          if ($pwd.attr('type') === 'password'){
            $pwd.attr('type', 'text');
          }
          else{
            $pwd.attr('type', 'password');
          }
        });
        var $pas = $("#pwd");
        var $opas = $("#password");
        $pas.keyup(function(){
          var $spa = $("form #errmsg");
          var $password = $pas.val();
          if($password == $opas.val() && $password != ""){
            $spa.html("Account Secured");
            $spa.css('color','green');
          }
          else if($password != ""){
            $spa.html("Failed in Account Security");
            $spa.css('color','red');
          }
          else{
            $spa.html("Make sure of strong password...!");
            $spa.css('color','turquoise');
          }
        });
        $('input[type="date"]').each(function() {
          var el = this, type = $(el).attr('type');
          if ($(el).val() == '') $(el).attr('type', 'text');
          $(el).focus(function() {
            $(el).attr('type', type);
            el.click();
          });
          $(el).blur(function(){
            if ($(el).val() == '') $(el).attr('type', 'text');
          });
        });
      });
    </script>
  </body>
</html>
