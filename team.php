<?php
include("header.php");

require_once("models/ProjectModel.php");
require_once("models/PersonModel.php");
require_once("models/TeamModel.php");

?>
<link rel="stylesheet" type="text/css" href="css/tables.css">
<script type="text/javascript">
$(document).ready(function(){
        $("a").removeClass("active");
});
document.title = "Team Details";
</script>
<h1 class="heading-top">Team Details</h1>
<div class="content">
  
<?php

  $team_id = $_GET['tid'];
  global $team_details;
  $team_details = TeamModel::getTeamDetails($team_id);
  if(!$team_details){
    echo '<script type="text/javascript">alert("Invalid team link!"); </script>';
          PersonModel::redirect("index.php");
  }
  
  $owner_id = $team_details['owner_id'];
  $owner_details = json_decode(PersonModel::get($team_details['owner_id']), true);
  $owner_name = $owner_details['first_name'].' '.$owner_details['last_name'];
  $project_id = $team_details["project_id"];
  $project_details = ProjectModel::get($project_id);
  $project_name = $project_details["title"];
  $summary = $team_details["summary"];
  $members = json_decode(TeamModel::getTeam($team_id), true);
  $member_names = '';
  for($i=0; $i<count($members); $i++){
        $member_names .= '* <a href="profile.php?pid='.$members[$i]['student_id'].'">'. PersonModel::getName($members[$i]['student_id']) .'</a><br/>';
  }
  
  print <<<END_FORM
  <center>
    <table class="table-fill">
    <tbody class="table-hover">
    <tr>
    <td class="text-left">Team Name</td>
    <td class="text-left">Team #$team_id</td>
    </tr>
    <tr>
    <td class="text-left">Project</td>
    <td class="text-left">$project_name</td>
    </tr>
    <tr>
    <td class="text-left">Owner</td>
    <td class="text-left"><a href="profile.php?pid=$owner_id">$owner_name</a></td>
    </tr>
    <tr>
    <td class="text-left">Summary</td>
    <td class="text-left">$summary</td>
    </tr>
    <tr>
    <td class="text-left">Members</td>
    <td class="text-left">$member_names</td>
    </tr>
    </tbody>
    </table>
  </center>
END_FORM;

?>

</div>

