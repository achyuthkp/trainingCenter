<?php
include("header.php");
require_once("models/ProjectModel.php");
require_once("models/PersonModel.php");
require_once("models/TeamModel.php");
?>
<link rel="stylesheet" type="text/css" href="css/tables.css">
<h1 class="heading-top">Existing Teams</h1>

<?php
$teams = TeamModel::getMyTeams($person['person_id']);

print   '
        <div class="content">
            <center>
            <table class="table-fill">
                <thead>
                    <tr>
                    <th class="text-left">No.</th>
                    <th class="text-left">Team</th>
                    <th class="text-left">Details</th>
                    <th class="text-left">Members</th>
                    <th class="text-left">Edit</th>
                    </tr>
                </thead>
                <tbody class="table-hover">
        ';
                
            for($i=0; $i<count($teams); $i++){
                
                $project = ProjectModel::get($teams[$i]['project_id']);
                $members = json_decode(TeamModel::getTeam($teams[$i]['team_id']), true);
                
                print   '
                        <tr>
                            <td class="text-left">'.($i+1).'</td>
                            <td class="text-left">
                                <strong><a href="team.php?tid='.$teams[$i]["team_id"].'">Team # '.$teams[$i]["team_id"].' </a></strong><br/><br/><small><i>(Created: '.$teams[$i]["created_at"].')</i></small></td>
                            <td class="text-left">
                                <strong>Project: </strong><a href="project.php?pid='.$teams[$i]['project_id'].'">'.$project["title"].'</a>
                                <br/><br/>
                                <strong><small>Summary: </strong>'.$teams[$i]['summary'].'
                                <br/><br/>
                            <td class="text-left">
                        ';
                        
                        for($j=0; $j<count($members); $j++){
                            print '* ';
                            PersonModel::showName($members[$j]['student_id']);
                            print '</br>';
                        }
                
                print   '
                            </td>
                            <td class="text-left"><a style="float:left;margin-right:15px;" href="ModifyTeam.php?tid='.$teams[$i]['team_id'].'">Edit Team</a></br></br><a style="float:left;" href="addMembers.php?tid='.$teams[$i]['team_id'].'">Add Members</a></br></br><a style="float:left;" href="ModifyMembers.php?tid='.$teams[$i]['team_id'].'">Remove Members</a></td>
                        </tr>
                        ';
            }
            
print '
                    </tbody>
                </table>
            </center>
        </div>';
?>
