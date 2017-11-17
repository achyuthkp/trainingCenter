<?php
session_start();    
require_once('models/PersonModel.php');
if(isset($_SESSION['isLoggedIn'])){                                                        
	$person = json_decode(PersonModel::get($_SESSION['isLoggedIn']), true); 
	?>
    <!DOCTYPE html>
    <html>
    <head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <link rel='stylesheet prefetch' href='http://ajax.googleapis.com/ajax/libs/jqueryui/1.11.2/themes/smoothness/jquery-ui.css'>
    <link rel="stylesheet" href="css/style.css">
    <script src='http://cdnjs.cloudflare.com/ajax/libs/jquery/2.1.3/jquery.min.js'></script>
    <script src='http://ajax.googleapis.com/ajax/libs/jqueryui/1.11.2/jquery-ui.min.js'></script>
    </head>
    <body>
    <div id="main">
  <div class="container">
    <nav>
      <div class="nav-xbootstrap">
        <ul>
            <li><a id="li-home" class="active" href="welcome.php">Home</a></li>
            <!-- SHOW MENUS FOR TRAINER OR STUDENT ACCORDINGLY -->
            <?php if($person['is_trainer']=='1'){ ?>
                <li><a id="li-addproject" href="newProject.php">Create a new Project</a></li>
                <li><a id="li-viewproject" href="viewProject.php">My Projects</a></li>
            <?php } else { ?>
                <li><a id="li-Newteam" href="newTeam.php">New Team</a></li>
                <li><a id="li-viewteam" href="viewTeam.php">Existing Teams</a></li>
            <?php } ?>
          <li><a href="javascript:void(0)">Hi, <?=$person['first_name']?>!<span class="glyphicon glyphicon-chevron-down iconsize"></span></a>
            <ul class="dropdown">
              <li><a href="profile.php?pid=<?=$_SESSION['isLoggedIn']?>">My Profile</a></li>
              <li><a href="logout.php">Logout</a></li>
            </ul>
          </li>
        </ul>
      </div>
      <div class="nav-bg-xbootstrap">
        <div class="navbar-xbootstrap"> <span></span> <span></span> <span></span> </div>
        <a href="#" class="title-mobile">Training Center</a>
      </div>
    </nav>
    <script type="text/javascript">
        $(document).ready(function(){
            $('.navbar-xbootstrap').click(function(){
                $('.nav-xbootstrap').toggleClass('visible');
                $('body').toggleClass('cover-bg');
            });
        });
    </script>
<?php    
}
else{
        PersonModel::redirect("index.php");     
    }
?>