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
      $ressection2 = $result->fetchArray(SQLITE3_ASSOC);
      $pro = $ressection2['profilepic'];
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
  <link rel="stylesheet" href="CSS/profile-section-3.css">
  <link rel="stylesheet" href="CSS/profile-section-4.css">
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
            <form class="upload" action="" method="post" enctype="multipart/form-data">
              <input type="text" name="itemname" placeholder="Item Name" autocomplete="off">
              <input type="text" name="cost" placeholder="Initial Cost" autocomplete="off">
              <label for="file" class="file-label">Select File to upload</label><input type="file" id="file" name="itempic" >
              <textarea name="description" placeholder="Item Description..."></textarea>
              <input type="submit" name="upload" value="Start Auctioning">
            </form>
            <?php
              if(isset($_POST['upload'])){
                $ImageName = $_FILES['itempic']['name'];
                $fileElementName = 'itempic';
                $path = 'Items/';
                $location = "-";
                if($_FILES['itempic']['name'] != ""){
                  $location = $path . $_FILES['itempic']['name'];
                  move_uploaded_file($_FILES['itempic']['tmp_name'], $location);
                }
                $db->exec("Insert into Items(name,icost,ccost,fcost,itempic,description,finaldate,upby) values('".$_POST['itemname']."',".$_POST['cost'].",".$_POST['cost'].",0,'".$location."','".$_POST['description']."',datetime('now','+7 days'),'".$user."')");
                header("Location: profile.php");
              }
            ?>
          </div>
        </div>
        <form class="search" action="" method="post">
          <label>
            <i class="fa fa-search" arial-hidden="true"></i>
            <input type="text" name="search-text" placeholder="" data="Search for Item" autocomplete="off">
          </label>
          <input type="submit" name="search" value="search">
        </form>
        <div class="search-result">
          <?php
            if(isset($_POST['search'])){
              $sear = $_POST['search-text'];
              if($sear != ""){
                $result = $db->query("Select * from Items where name='".$sear."' and finaldate > datetime('now')");
                if($result instanceof SQLite3Result){
                  $flag = False;
                  while($res = $result->fetchArray(SQLITE3_ASSOC)){
                    $flag = True;
                    echo "<div id=\"".$res['itemid']."\" class=\"item\">";
                    if($res['itempic'] != "")
                      echo "<img src=\"".$res['itempic']."\" alt=\"".$res['itemname']."\">";
                    else
                      echo "<i class=\"fa fa-file-image-o\"></i>";
                    echo "<div id=\"content\">
                      <p>Item Name : ".$res['name']."</p>
                      <p>Initial Cost : ".$res['icost']."</p>
                      <p>Current Cost : ".$res['ccost']."</p>
                      <p>Auction Ends in :<br><span>".$res['finaldate']."</span></p>
                      <form id=\"bid\" action=\"\"><input type=\"submit\" name=\"bid-".$res['itemid']."\" value=\"Bid\"></form>
                      </div>
                    </div>";
                  }
                  if($flag == false){
                    echo "<h2>Successful search with no result..!</h2>";
                  }
                }
                else{
                  echo "<h2>Sorry!Failed to search</h2>";
                }
              }
              else{
                echo "<h2>Type Something to Search....!!</h2>";
              }
            }
            else{
              $result = $db->query("Select * from Items where finaldate > datetime('now')");
              if($result instanceof SQLite3Result){
                while($res = $result->fetchArray(SQLITE3_ASSOC)){
                  echo "<div id=\"".$res['itemid']."\" class=\"item\">";
                  if($res['itempic'] != "")
                    echo "<img src=\"".$res['itempic']."\" alt=\"".$res['itemname']."\">";
                  else
                    echo "<i class=\"fa fa-file-image-o\"></i>";
                  echo "<div id=\"content\">
                      <p>Item Name : ".$res['name']."</p>
                      <p>Initial Cost : ".$res['icost']."</p>
                      <p>Current Cost : ".$res['ccost']."</p>
                      <p>Auction Ends in :<br><span>".$res['finaldate']."</span></p>
                      <form id=\"bid\" action=\"\"><input type=\"submit\" name=\"bid-".$res['itemid']."\" value=\"Bid\"></form>
                    </div>
                  </div>";
                }
            }
          }
          ?>
        </div>
        <form class="check" action="" method="post">
          <input type="submit" name="payment" value="Check-Due"><br>
          <input type="submit" name="itemstatus" value="Auction-status">
        </form>
      </div>
      <div class="section" id="section2">
        <h1>Personal Settings <i class="fa fa-spin fa-cog" aria-hidden="true"></i></h1>
        <div class="box">
          <div class="content">
            <center><h1 style="color: black;font-size: 2.5vh;">currently <?php echo "$user"?>'s</h1></center>
            <p>
            Name : <?php echo $ressection2['Name'];?><br>
            Age : <?php echo date_diff(date_create($res['DOB']), date_create('today'))->y;?> years<br>
            E-Mail : <?php echo $ressection2['email'];?><br>
            Gender : <?php echo $ressection2['gender'];?><br>
            Date of Birth : <?php echo $ressection2['DOB'];?><br>
            Phone Number : <?php echo $ressection2['phone'];?><br>
                Address: <br><?php echo $ressection2['address'];?><br>
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
      <div class="section" id="section3">
        <h1>On Going Auctions...</h1>
        <div class="wrapper">
          <?php
          $result = $db->query("Select * from Items where finaldate > datetime('now')");
          if($result instanceof SQLite3Result){
            while($res = $result->fetchArray(SQLITE3_ASSOC)){
              echo "<div id=\"".$res['itemid']."\" class=\"item\">";
              if($res['itempic'] != "")
                echo "<img src=\"".$res['itempic']."\" alt=\"".$res['itemname']."\">";
              else
                echo "<i class=\"fa fa-file-image-o\"></i>";
              echo "<div id=\"content\">
                  <p>Item Name : ".$res['name']."</p>
                  <p>Initial Cost : ".$res['icost']."</p>
                  <p>Current Cost : ".$res['ccost']."</p>
                  <p>Auction Ends in :<br><span>".$res['finaldate']."</span></p>
                  <form id=\"bid\" action=\"\"><input type=\"submit\" name=\"bid-".$res['itemid']."\" value=\"Bid\"></form>
                </div>
              </div>";
            }
          }
          ?>
        </div>
      </div>
      <div class="section" id="section4">
        <h1>Auctions Won...</h1>
        <div class="wrapper">
          <?php
          $result = $db->query("Select * from Items where finaldate < datetime('now') and wonby = '".$user."'");
          if($result instanceof SQLite3Result){
            while($res = $result->fetchArray(SQLITE3_ASSOC)){
              echo "<div id=\"".$res['itemid']."\" class=\"item\">";
              if($res['itempic'] != "")
                echo "<img src=\"".$res['itempic']."\" alt=\"".$res['itemname']."\">";
              else
                echo "<i class=\"fa fa-file-image-o\"></i>";
              echo "<div id=\"content\">
                  <p>Item Name : ".$res['name']."</p>
                  <p>Initial Cost : ".$res['icost']."</p>
                  <p>Final Cost : ".$res['ccost']."</p>
                  <p>Description : ".$res['description']."</p>
                  <p><br><span>".$res['finaldate']."</span></p>
                  <form id=\"pay\" action=\"\"><input type=\"submit\" name=\"pay-".$res['itemid']."\" value=\"Pay\"></form>
                </div>
              </div>";
            }
          }
          ?>
        </div>
      </div>
      <div class="section" id="section5">
        <div id="bgimg"></div>
        <form method="post" id="logform" action="index.html">
          <p>If you are suerly want to Quit...!<br><i class="fa fa-2x fa-hand-o-down" aria-hidden="true"></i></p>
          <input id="logout" type="submit" name="submit-logout" value="Then Proceed!">
        </form>
      </div>
    </div>
 </body>
 <?php
 if(isset($_POST['submit-logout'])){
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
      var $file = $('.finalhalf #section1 .item-upload .wrapper .upload #file');
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



      var $item = $('.item');
      $item.each(function(){
        var $sp = $(this).find('span');
        var $countDownDate = new Date($sp.text()).getTime();
        var x = setInterval(function(){
          var now = new Date().getTime();
          var distance = $countDownDate - now;
          var days = Math.floor(distance / (1000 * 60 * 60 * 24));
          var hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
          var minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
          var seconds = Math.floor((distance % (1000 * 60)) / 1000);
          var $rem = days + "d " + hours + "h "+ minutes + "m " + seconds + "s ";
          $sp.text($rem);
          if (distance < 0) {
            clearInterval(x);
            $sp.text("Auction Completed");
          }
        }, 1000);
      });
    });
  </script>
</html>
