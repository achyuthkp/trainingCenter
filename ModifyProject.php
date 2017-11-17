<?php
include("header.php");

require_once("models/ClassModel.php");
require_once("models/ProjectModel.php");
?>
<link rel="stylesheet" type="text/css" href="css/forms.css">
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
  $project_id = $_GET['pid'];
  global $project_details;
  $project_details = ProjectModel::get($project_id);
  if(!$project_details){
    echo '<script type="text/javascript">alert("Project not found!"); </script>';
          PersonModel::redirect("viewProject.php");
  }
  $class = ClassModel::getClassId($project_details['class_id']);
  global $messages;
  if (!array_key_exists("class_id", $messages)) {
    $messages["class_id"] = "";
  }
  else {
    $messages["class_id"] = "<span style='color: red;'>$messages[class_id]</span>";
  }
  
  if (!array_key_exists("title", $messages)) {
    $messages["title"] = "";
  }
  else {
    $messages["title"] = "<span style='color: red;'>$messages[title]</span>";
  }
  
  if (!array_key_exists("deadline", $messages)) {
    $messages["deadline"] = "";
  }
  else {
    $messages["deadline"] = "<span style='color: red;'>$messages[deadline]</span>";
  }
  
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
  $class_id = $project_details['class_id'];
  $title = $project_details['title'];
  $subject = $project_details['subject'];
  $class_name = $class['name'];
  $tomorrow = date("Y-m-d", strtotime("+1 days"));
  $deadline = substr($project_details['deadline'], 0, 10);
  
  print <<<END_FORM
  <center>$messages[main]<br/>
  <form method="POST" id="displayform">
    <table>
      <h3>Edit Project</h3>
      <h4>Modify your Project details</h4>
      <tr>
        <th>Owner Id:</th>
        <td><input type="text" name="owner_id" value="$person_id" disabled /></td>
        <td></td>
      </tr>
      <tr>
        <th>Class:</th>
        <td><select name="class_id" disabled ><option value="$class_id">$class_name</option></select></td>
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
        <td><input type="date" min="$tomorrow" value="$deadline" name="deadline"></td>
        <td>$messages[deadline]
        <input type="hidden" name="project_id" value="$project_id">
        </td>
      </tr>
    </table>
    <br/>   
    <button type="submit">Modify Project</button>
  </form>
  </center>
END_FORM;
}

function do_post() {
  global $messages;
  $title = (empty($_POST["title"])) ? "" : (trim($_POST["title"]));
  $deadline = (empty($_POST["deadline"])) ? "" : (trim($_POST["deadline"]));
  $subject = (empty($_POST["subject"])) ? "" : (trim($_POST["subject"]));
  $person_id = (empty($_POST["owner_id"])) ? "" : (trim($_POST["owner_id"]));
  $project_id = (empty($_POST["project_id"])) ? "" : (trim($_POST["project_id"]));
  
  if ($title == "") {
    $messages["title"] = "Enter project title";
    display_form();
  }
  else if (strlen($title) < 5) {
    $messages["title"] = "Title too short";
    display_form();
  }
  else if ($deadline == "") {
    $messages["deadline"] = "Enter deadline";
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
  else {
        
        try {
        $update = ProjectModel::Update($project_id, $title, $deadline, $subject);
        if($update){
          echo '<script type="text/javascript">alert("Project updated successfully!"); </script>';
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

