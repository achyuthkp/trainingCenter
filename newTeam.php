<?php
include("header.php");
?>
<link rel="stylesheet" type="text/css" href="css/forms.css">

<div class="content">
  
<?php
require_once("models/ProjectModel.php");
require_once("models/ClassModel.php");
require_once("models/TeamModel.php");

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
  $project_id = ($_SERVER["REQUEST_METHOD"] == "GET") ? "" : $_POST["project_id"];
  if (!array_key_exists("project_id", $messages)) {
    $messages["project_id"] = "";
  }
  else {
    $messages["project_id"] = "<span style='color: red;'>$messages[project_id]</span>";
  }
  
  $summary = ($_SERVER["REQUEST_METHOD"] == "GET") ? "" : $_POST["summary"];
  $owner_id = $_SESSION['isLoggedIn'];
  $class_id = ClassModel::getClass($owner_id);
  $projects = ProjectModel::getProjectSelect($class_id['class_id']);
  
  print <<<END_FORM
  <center>
  <form method="POST" id="displayform">
    <table>
      <h3>New Team</h3>
      <h4>Enter your Team details</h4>
      <tr>
        <th>Owner Id:</th>
        <td><input type="text" name="owner_id" value="$owner_id" readonly /></td>
        <td></td>
      </tr>
      <tr>
        <th>Select Project:*</th>
        <td><select name="project_id">$projects</select>$messages[project_id]</td>
        <td></td>
      </tr>
      <tr>
        <th>Summary:</th>
        <td><textarea rows="3" name="summary" value="$summary" cols="50" placeholder="Enter team summary here..."></textarea></td>
      </tr>
    </table>
    <button type="submit">Save & Add Members</button>
    <button type="reset">Reset</button>
  </form>
  </center>
END_FORM;
}

function do_post() {
  global $messages;
  $project_id = (empty($_POST["project_id"])) ? "" : (trim($_POST["project_id"]));
  $summary = (empty($_POST["summary"])) ? "" : (trim($_POST["summary"]));
  $owner_id = (empty($_POST["owner_id"])) ? "" : (trim($_POST["owner_id"]));
  
  if ($project_id == "") {
    $messages["project_id"] = "Select a project";
    display_form();
  }
  else if ($owner_id == "") {
    header("location:logout.php");
  }
  else {
        
        try {
        $add = TeamModel::addTeam($project_id, $owner_id, $summary);
        if($add){
          $team_id = $_SESSION['team_id'];
          PersonModel::redirect("addMembers.php?tid=".$team_id);
        }
        else{
          print "Temporary error, try again later!<br><br>";
        }
          
        } catch (PDOException $exc) {
          /* Each time we access a DB, an exception may occur */
          $msg = $exc->getMessage();
          $code = $exc->getCode();
          print "$msg (error code $code)";
        }
    
  }
}

?>

</div>
