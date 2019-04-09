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
    $query = "select * from profile where uname = '".$user."'";
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
  <link rel="stylesheet" href="CSS/profile-section-2.css">
  <link rel="stylesheet" href="CSS/profile-section-5.css">
  <script type="text/javascript" src="JS/jquery.js"></script>
</head>
  <body>
    <div class="intro-half">
      <div id="background"></div>
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
          <li><a href="#section1"><i class="fa fa-home" aria-hidden="true"></i></a></li>
          <li><a href="#section2"><i class="fa fa-cog" aria-hidden="true"></i></a></li>
          <li><a href="#section3"><i class="fa fa-calendar" aria-hidden="true"></i></a></li>
          <li><a href="#section4"><i class="fa fa-trophy" aria-hidden="true"></i></a></li>
          <li id="log"><a href="#section5"><i class="fa fa-sign-out" aria-hidden="true"></i></a></li>
        </ul>
      </div>
    </div>
    <div class="finalhalf">
      <div class="section" id="section1"><h1>Hello</h1></div>
      <div class="section" id="section2">
        <h1>Personal Settings <i class="fa fa-spin fa-cog" aria-hidden="true"></i></h1>
        <div class="box">
          <div class="content">
            <center><h1 style="color: black;font-size: 2.5vh;">currently <?php echo "$user"?>'s</h1></center>
            <p>
            Name : <?php echo $res['Name'];?><br>
            Age : <?php echo date_diff(date_create($res['DOB']), date_create('today'))->y;?> years<br>
            E-Mail : <?php echo $res['email'];?><br>
            Gender : <?php echo $res['gender'];?><br>
            Date of Birth : <?php echo $res['DOB'];?><br>
            Phone Number : <?php echo $res['phone'];?><br>
                Address: <br><?php echo $res['address'];?><br>
            </p>
          </div>
          <div class="details">
            <div class="image">
              <?php
                if($pro == ""){
                  echo "<i class=\"fa fa-user-circle-o\" aria-hidden=\"true\"></i>";
                }
                else{
                  echo "<img src=\"".$pro."\" alt=\"Profile Picture of \"".$user.">";
                }
              ?>
            </div>
            <h3>One of the Smartest,<br><span>Bidder/Auctioner</span></h3>
          </div>
        </div>
        <form id="settings" action="#" method="post">
          <h2>Fill the Required</h2><br>
          <label>New User Name : </label><input type="text" name="uname" placeholder="" data="New User Name" autocomplete="off"><br><br>
          <label>New Name : </label><input type="text" name="name" placeholder="" data="New Name" autocomplete="off"><br><br>
          <label>New Phone Number : </label><input type="text" name="phone" placeholder="" data="New Phone Number" autocomplete="off"><br><br>
          <label>New Email-Id : </label><input type="text" name="email" placeholder="" data="New Email" autocomplete="off"><br><br>
          <label>
            <input type="checkbox" name="password" >
            <span>Change Password</span>
          </label><br><br>
          <input type="submit" name="modify" value="Modify"><br>
        </form>
      </div>
      <div class="section" id="section3"></div>
      <div class="section" id="section4"></div>
      <div class="section" id="section5">
        <div id="bgimg"></div>
        <form method="post" id="logform" action="">
          <p>Are You Sure You want to Quit?<br><i class="fa fa-2x fa-hand-o-down" aria-hidden="true"></i></p>
          <input id="logout" type="submit" name="submit" value="Then Proceed!">
        </form>
      </div>
    </div>
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
      var $inp = $('.finalhalf #section2 #settings input[type="text"]');
      $inp.focus(function(){
        $(this).attr("placeholder" , String($(this).attr("data")));
        $(this).css('width','40%');
        $(this).css('height','8%');
      });
      $inp.blur(function(){
        if($(this).val() != ""){
          $(this).css('width','40%');
          $(this).css('height','8%');
        }
        else{
          $(this).css('width','30px');
          $(this).css('height','30px');
          $(this).attr("placeholder" , "");
        }
      });
    });
  </script>
</html>
