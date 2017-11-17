<?php
require_once "/models/PersonModel.php";
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
    <div class="content">

<?php
$messages = array();
switch ($_SERVER["REQUEST_METHOD"]) {
  case "GET":
    display_form();
    break;
  case "POST":
    do_post();
    break;
  default:
    die("Not implemented");
}

function display_form() {
  global $messages;
  $email = ($_SERVER["REQUEST_METHOD"] == "GET") ? "" : $_POST["email"];
  if (!array_key_exists("email", $messages)) {
    $messages["email"] = "";
  }
  else {
    $messages["email"] = "<span style='color: red;'>$messages[email]</span>";
  }
  $pass = ($_SERVER["REQUEST_METHOD"] == "GET") ? "" : $_POST["pass"];
  if (!array_key_exists("pass", $messages)) {
    $messages["pass"] = "";
  }
  else {
    $messages["pass"] = "<span style='color: red;'>$messages[pass]</span>";
  }
  if (!array_key_exists("main", $messages)) {
    $messages["main"] = "";
  }
  else {
    $messages["main"] = "<span style='color: red;'>$messages[main]</span>";
  }

  print <<<END_FORM
  <center>$messages[main]$messages[email]$messages[pass]<br><br>
  <div class="login-card">
    <h1>Login</h1><br>
    <form method="POST" autocomplete="off">
    <input type="text" name="email" value="$email" placeholder="Email ID" autocomplete="off">
    <p></p>
    <input type="password" name="pass" value="$pass" placeholder="Password" autocomplete="off">
    <p></p>
    <input type="submit" name="login" class="login login-submit" value="Login">
  </form>
  </div>
  </center>
END_FORM;
}

function do_post() {
  global $messages;
  $email = (empty($_POST["email"])) ? "" : (trim($_POST["email"]));
  $pass = (empty($_POST["pass"])) ? "" : (trim($_POST["pass"]));
  if ($email== "") {
    $messages["email"] = "Enter Email Id";
    display_form();
  }
  else if ($pass == "") {
    $messages["pass"] = "Enter Password";
    display_form();
  }
  else {
        try {
          $login = PersonModel::getByLoginPassword($email,$pass);
          if($login != null){
          	session_start();
            $_SESSION['isLoggedIn'] = ($login['person_id']);
            
            PersonModel::redirect("welcome.php");
          }
          else{
            $messages["main"] = "Invalid email address or password!";
            display_form();
          }
          
        } catch (PDOException $exc) {
          $msg = $exc->getMessage();
          $code = $exc->getCode();
          print "$msg (error code $code)";
        }
    
  }
}

?>
</div>

