<?php
include("header.php");
require_once("models/ProjectModel.php");
require_once("models/TeamModel.php");
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
  global $messages;
  $team_id = $_GET['tid'];
  global $team_details;

  $team_details = TeamModel::getTeamDetails($team_id);

  if(!$team_details){
    echo '<script type="text/javascript">alert("Team not found!"); </script>';          
          PersonModel::redirect("viewTeam.php");                                       
  }

  if (!array_key_exists("summary", $messages)) {
    $messages["summary"] = "";
  }
  else {
    $messages["summary"] = "<span style='color: red;'>$messages[summary]</span>";
  }
  if (!array_key_exists("main", $messages)) {
    $messages["main"] = "";
  }
  else {
    $messages["main"] = "<span style='color: red;'>$messages[main]</span>";
  }

  $owner_id = $_SESSION['isLoggedIn'];
  $project_id = $team_details['project_id'];
  $project_details = ProjectModel::get($project_id);
  $project_name = $project_details["title"];
  $summary = $team_details["summary"];
  
  print <<<END_FORM
  <br><br><center>$messages[main]<br><br>
  <form method="POST" id="displayform">
  <table>
    <h3>Edit Team</h3>
    <h4>Modify your Team's details</h4>
      <tr>
        <th>Owner Id:</th>
        <td><input type="text" name="owner_id" value="$owner_id" readonly /></td>
      </tr>
      <tr>
        <th>Project:</th>
        <td><select name="project_id"><option value="$project_id">$project_name</option></select></td>
      </tr>
      <tr>
        <th>Summary:*</th>
        <td><textarea rows="4" name="summary" cols="50" placeholder="Enter team summary here">$summary</textarea>$messages[summary]
        <input type="hidden" name="team_id" value="$team_id">
        </td>
      </tr>
    </table>
    <button type="submit">Update Team Summary</button>
  </form>
  </center>
END_FORM;
}

function do_post() {
  global $messages;
  $team_id = (empty($_POST["team_id"])) ? "" : (trim($_POST["team_id"]));
  $summary = (empty($_POST["summary"])) ? "" : (trim($_POST["summary"]));
  $owner_id = (empty($_POST["owner_id"])) ? "" : (trim($_POST["owner_id"]));

  if ($summary == "") {
    $messages["summary"] = "Enter team summary";
    display_form();
  }
  else if (strlen($summary) < 10) {
    $messages["summary"] = "Summary too short";
    display_form();
  }
  else if ($owner_id == "") {
    PersonModel::redirect("location:logout.php");
  }
  else {
        try {
        $update = TeamModel::UpdateTeam($team_id, $summary);
        if($update){
          echo '<script type="text/javascript">alert("Summary updated successfully!"); </script>';
          PersonModel::redirect("viewTeam.php");
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
