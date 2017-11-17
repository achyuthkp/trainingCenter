<?php
include("header.php");
?>
<link rel="stylesheet" type="text/css" href="css/forms.css">
<div class="content">

<?php
require_once("models/ClassModel.php");
require_once("models/ProjectModel.php");
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
  $class_id = ($_SERVER["REQUEST_METHOD"] == "GET") ? "" : $_POST["class_id"];
  if (!array_key_exists("class_id", $messages)) {
    $messages["class_id"] = "";
  }
  else {
    $messages["class_id"] = "<span style='color: red;'>$messages[class_id]</span>";
  }
  
  $title = ($_SERVER["REQUEST_METHOD"] == "GET") ? "" : $_POST["title"];
  if (!array_key_exists("title", $messages)) {
    $messages["title"] = "";
  }
  else {
    $messages["title"] = "<span style='color: red;'>$messages[title]</span>";
  }
  
  $deadline = ($_SERVER["REQUEST_METHOD"] == "GET") ? "" : $_POST["deadline"];
  if (!array_key_exists("deadline", $messages)) {
    $messages["deadline"] = "";
  }
  else {
    $messages["deadline"] = "<span style='color: red;'>$messages[deadline]</span>";
  }
  
  $subject = ($_SERVER["REQUEST_METHOD"] == "GET") ? "" : $_POST["subject"];
  if (!array_key_exists("subject", $messages)) {
    $messages["subject"] = "";
  }
  else {
    $messages["subject"] = "<span style='color: red;'>$messages[subject]</span>";
  }
  if (!array_key_exists("main", $messages)) {
    $messages["main"] = "";
  }
  else {
    $messages["main"] = "<span style='color: red;'>$messages[main]</span>";
  }

  $person_id = $_SESSION['isLoggedIn'];
  $classes = ClassModel::getClassSelect();
  $tomorrow = date("Y-m-d", strtotime("+1 days"));
  
  print <<<END_FORM
  <center>$messages[main]<br/>
  <form method="POST" id="displayform">
    <table>
      <h3>New Project</h3>
      <h4>Enter your Project details</h4>
      <tr>
        <th>Select Class:*</th>
        <td><select name="class_id">$classes</select></td>
        <td>$messages[class_id]</td>
      </tr>
      <tr>
        <th>Project Title:*</th>
        <td><input type="text" name="title" value="$title" placeholder="Project title" required /></td>
        <td>$messages[title]</td>
      </tr>
      <tr>
        <th>Subject:*</th>
        <td><input type="text" name="subject" value="$subject" placeholder="Enter subject" /></td>
        <td>$messages[subject]</td>
      </tr>
      <tr>
        <th>Deadline:*</th>
        <td><input type="date" min="$tomorrow" name="deadline"></td>
        <td>$messages[deadline]</td>
      </tr>
    </table>
    <input type="hidden" name="owner_id" value="$person_id" readonly />
    <br/>
    <button type="submit">Add Project</button>
    <button type="reset">Reset</button>
  </form>
  </center>
END_FORM;
}

function do_post() {
  global $messages;
  $class_id = (empty($_POST["class_id"])) ? "" : (trim($_POST["class_id"]));
  $title = (empty($_POST["title"])) ? "" : (trim($_POST["title"]));
  $deadline = (empty($_POST["deadline"])) ? "" : (trim($_POST["deadline"]));
  $subject = (empty($_POST["subject"])) ? "" : (trim($_POST["subject"]));
  $person_id = (empty($_POST["owner_id"])) ? "" : (trim($_POST["owner_id"]));
  
  if ($class_id == "") {
    $messages["class_id"] = "Select a class";
    display_form();
  }
  else if ($title == "") {
    $messages["title"] = "Enter project title";
    display_form();
  }
  else if (strlen($title) < 5) {
    $messages["title"] = "Title too short";
    display_form();
  }
  else if ($subject == "") {
    $messages["subject"] = "Enter subject";
    display_form();
  }
  else if (strlen($subject) < 5) {
    $messages["subject"] = "Subject too short";
    display_form();
  }
  else if ($deadline == "") {
    $messages["deadline"] = "Enter deadline";
    display_form();
  }
  else {
        
        try {
        $add = ProjectModel::add($person_id, $class_id, $title, $deadline, $subject);
        if($add){
          echo '<script type="text/javascript">alert("Project added successfully!"); </script>';
          PersonModel::redirect("viewProject.php");
        }
        else{
          $messages["main"] = "Temporary error, try again later!";
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
