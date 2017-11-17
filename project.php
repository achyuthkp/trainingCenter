<?php
require_once("models/ClassModel.php");
require_once("models/ProjectModel.php");
require_once("models/PersonModel.php");
require_once("models/TeamModel.php");
include ("header.php");
?>
<link rel="stylesheet" type="text/css" href="css/tables.css">
<script type="text/javascript">
$(document).ready(function(){
        $("a").removeClass("active");
});

</script>
<h1 class="heading-top">Project Details</h1>
<div class="content">
<?php

  $project_id = $_GET['pid'];
  $project_details = ProjectModel::get($project_id);
 
  $class = ClassModel::getClassId($project_details['class_id']);
  $owner_details = json_decode(PersonModel::get($project_details['owner_id']), true);
  $owner_name = $owner_details['first_name'].' '.$owner_details['last_name'];
  $class_id = $project_details['class_id'];
  $title = $project_details['title'];
  $subject = $project_details['subject'];
  $class_name = $class['name'];
  $deadline = substr($project_details['deadline'], 0, 10);
  $created = substr($project_details['created_at'], 0, 10);
  $project_teams = TeamModel::getTeamByProject($project_id);
  $teams = '';
  $check_members = ClassModel::getClassMembers($project_details["class_id"]);
  $not_members = array();
  $not_members_list = '';
  $flag = array();
  if (count($project_teams)==0) {
    for ($i=0; $i < count($check_members); $i++) { 
      $not_members_list .= '* <a href="profile.php?pid=' .$check_members[$i]['person_id']. '">' .PersonModel::getName($check_members[$i]['person_id']). '</a><br/>';
    }
  }
  else {
    for($i=0; $i<count($project_teams); $i++){
      reset($flag);
      $teams .= '* <a href="team.php?tid=' .$project_teams[$i]['team_id']. '"> Team #' .$project_teams[$i]['team_id']. '</a>
            <br/><small>(' .$project_teams[$i]['summary']. ')</small><br/><br/>';
      for($j=0; $j<count($check_members); $j++){
        if (TeamModel::IsInTeam($check_members[$j]['person_id'], $project_teams[$i]['team_id'])) {
            if(($key = array_search($check_members[$j]['person_id'], $not_members)) !== false) {
              unset($not_members[$key]);
            }
            $flag[$j] = 1;
        }
        else {      
          if (!in_array($check_members[$j]['person_id'], $not_members) && current($flag)!=1) {
            $not_members[] = $check_members[$j]['person_id'];
          }
        }
      }
    }
    for($j=0; $j<count($check_members); $j++){
      if (in_array($check_members[$j]['person_id'], $not_members)) {
        $not_members_list .= '* <a href="profile.php?pid=' .$check_members[$j]['person_id']. '">' .PersonModel::getName($check_members[$j]['person_id']). '</a><br/>';
      }
    }
  }
  print <<<END_FORM
  <center>
    <table class="table-fill">
      <tbody class="table-hover">
      <tr>
      <td class="text-left">Project Title</td>
      <td class="text-left">$title</td>
      </tr>
      <tr>
      <td class="text-left">Subject</td>
      <td class="text-left">$subject</td>
      </tr>
      <tr>
      <td class="text-left">Owner</td>
      <td class="text-left">$owner_name</td>
      </tr>
      <tr>
      <td class="text-left">Class</td>
      <td class="text-left">$class_name</td>
      </tr>
      <tr>
      <td class="text-left">Deadline</td>
      <td class="text-left">$deadline</td>
      </tr>
      <tr>
      <td class="text-left">Team(s)</td>
      <td class="text-left">$teams</td>
      </tr>
      <tr>
      <td class="text-left">Class Members (Not in team)</td>
      <td class="text-left">$not_members_list</td>
      </tr>
      <tr>
      <td class="text-left">Registered</td>
      <td class="text-left">$created</td>
      </tr>
      </tbody>
    </table>
  </center>
END_FORM;
?>
</div>

