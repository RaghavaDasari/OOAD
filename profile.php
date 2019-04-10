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
  if(isset($_POST['modify'])){
    $_SESSION['error'] = "";
    $n_name = $_POST['name'];
    $pass = $_POST['pass'];
    $n_phone = $_POST['phone'];
    $n_email = $_POST['email'];
    $n_pass = $_POST['npass'];
    $result = $db->query("select password from users where uname='".$user."'");
    $res = $result->fetchArray(SQLITE3_ASSOC);
    if($res['password'] == $pass){
      if($n_name != ""){
        $result = $db->exec("update profile set name='".$n_name."' where uname='".$user."'");
      }
      if($n_phone != ""){
        $result = $db->exec("update profile set phone=".$n_phone." where uname='".$user."'");
      }
      if($n_email != ""){
        $result = $db->exec("update profile set email='".$n_email."' where uname='".$user."'");
      }
      if($n_pass != ""){
        $result = $db->exec("update users set password='".$n_pass."' where uname='".$user."'");
      }
      $_SESSION['error'] = "<br>Changes done..:)";
    }
    else{
      $_SESSION['error'] = "<br>Try again..!";
    }
    header("Location: profile.php");
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
  <link rel="stylesheet" href="CSS/profile-section-1.css">
  <link rel="stylesheet" href="CSS/profile-section-2.css">
  <link rel="stylesheet" href="CSS/profile-section-5.css">
  <script type="text/javascript" src="JS/jquery.js"></script>
</head>
  <body>
    <div class="intro-half">
      <div id="background"></div>
      <?php
        echo "<h1>Welcome ".$user." to Smart World<br>";
        echo "We are happy to see you here</h1>";
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
      <div class="section" id="section1">
        <h1>Auction Home</h1>
        <h2>Upload Item :</h2>
        <div class="item-upload">
          <i class="fa fa-upload" aria-hidden="true"></i>
          <div class="wrapper">
            <i class="fa fa-close" arial-hidden="true"></i>
            <form class="upload" action="" method="post">
              <input type="text" name="itemname" placeholder="Item Name">
              <input type="text" name="cost" placeholder="Initial Cost">
              <label for="file" class="file-label">Select File to upload</label><input id="file" type="file" name="itempic">
              <textarea name="description" placeholder="Item Description..."></textarea>
              <input type="submit" name="upload" value="Start Auctioning">
            </form>
          </div>
        </div>
        <form class="search" action="" method="post">
          <label>
            <i class="fa fa-search" arial-hidden="true"></i>
            <input type="text" name="search" placeholder="" data="Search for Item" autocomplete="off">
          </label>
          <input type="submit" name="search" value="search">
        </form>
      </div>
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
          <label>Your Password : </label><input type="text" name="pass" placeholder="" data = "Enter Your Password" autocomplete="off" required><?php echo "<i>".$_SESSION['error']."</i>"; ?><br><br>
          <label>New Name : </label><input type="text" name="name" placeholder="" data="New Name" autocomplete="off"><br><br>
          <label>New Phone Number : </label><input type="text" name="phone" placeholder="" data="New Phone Number" autocomplete="off"><br><br>
          <label>New Email-Id : </label><input type="text" name="email" placeholder="" data="New Email" autocomplete="off"><br><br>
          <label>
            <input type="checkbox" name="password" value="password">
            <span>Change Password</span>
            <label for="npass"><br><br>New Password : </label><input type="text" name="npass" placeholder="" data="New Password" autocomplete="off">
          </label><br><br>
          <input type="submit" name="modify" value="Modify"><br>
        </form>
      </div>
      <div class="section" id="section3"></div>
      <div class="section" id="section4"></div>
      <div class="section" id="section5">
        <div id="bgimg"></div>
        <form method="post" id="logform" action="">
          <p>If you are suerly want to Quit...!<br><i class="fa fa-2x fa-hand-o-down" aria-hidden="true"></i></p>
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
      var $chk = $('.finalhalf #section2 #settings input[type="checkbox"]');
      $chk.click(function(){
        if($(this).prop("checked") == true){
          $(".finalhalf #section2 #settings input[name=\"npass\"]").prop('required',true);
        }
        else{
          $(".finalhalf #section2 #settings input[name=\"npass\"]").prop('required',false);
        }
      });
      $('.finalhalf #section1 .item-upload').click(function(){
        if(!$(this).hasClass("active")){
          $(this).addClass('active');
        }
        $('.finalhalf #section1 .item-upload .fa-upload').css('visibility','hidden');
      });
      $('.finalhalf #section1 .item-upload .wrapper .fa-close').click(function(event){
        event.stopPropagation();
        $('.finalhalf #section1 .item-upload').removeClass("active");
        $('.finalhalf #section1 .item-upload .fa-upload').css('visibility','visible');
      });
      var $file = $('.finalhalf #section1 .active .wrapper .upload #file');
      $file.on("change",function(e){
        var $name = e.target.value.split("\\").pop();
        if($name != ""){
          $('.finalhalf #section1 .active .wrapper .upload .file-label').text($name);
        }
        else{
          $('.finalhalf #section1 .active .wrapper .upload .file-label').text("Select File to upload");
        }
      });
      var $handle = $('.finalhalf #section1 .search input[type="text"]');
      $('.finalhalf #section1 .search .fa-search').click(function(e){
        $(this).animate({left : '65%'},500);
        $handle.animate({width : '60%'},500);
        $('.finalhalf #section1 .search input[type="submit"]').animate({opacity : '1'});
        $handle.attr("placeholder", $handle.attr("data"));
        $handle.focus();
        e.preventPropogation();
      });
      $handle.blur(function(e){
        if($(this).val() == "")
        {
          $('.finalhalf #section1 .search .fa-search').animate({left : '11%'},500);
          $('.finalhalf #section1 .search input[type="submit"]').animate({opacity : '0'});
          $handle.animate({width : '7%'},500);
          $handle.attr("placeholder", "");
        }
        e.preventPropogation();
      });
    });
  </script>
</html>
