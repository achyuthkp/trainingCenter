<?php
include("header.php");
require_once("models/ProjectModel.php");
require_once("models/ClassModel.php");
?>
<link rel="stylesheet" type="text/css" href="css/tables.css">
<h1 class="heading-top">Existing Projects</h1>
<?php
$projects = ProjectModel::getMyProjects($person['person_id']);

print   '
        <div class="content">
            <center>
            <table class="table-fill">
                <thead>
                    <tr>
                    <th class="text-left">No.</th>
                    <th class="text-left">Created</th>
                    <th class="text-left">Title</th>
                    <th class="text-left">Details</th>
                    <th class="text-left">Deadline</th>
                    <th class="text-left">Modify</th>
                    </tr>
                </thead>
                <tbody class="table-hover">
        ';
                
            for($i=0; $i<count($projects); $i++){
                
                print   '
                        <tr>
                            <td class="text-left">'.($i+1).'</td>
                            <td class="text-left">'.$projects[$i]["created_at"].'</td>
                            <td class="text-left"><a href="project.php?pid='.$projects[$i]["project_id"].'">'.$projects[$i]["title"].'</a></td>
                            <td class="text-left"><strong>Subject: </strong>'.$projects[$i]["subject"].'<br/><br/><small><strong>Class: </strong>
                        ';
                            ClassModel::showName($projects[$i]["class_id"]);
                print   '<br/><br/>
                            <td class="text-left">'.$projects[$i]["deadline"].'</td>
                            <td class="text-left"><a href="ModifyProject.php?pid='.$projects[$i]["project_id"].'">Edit</a></td>
                        </tr>
                        ';
            }
            
print   '           </tbody>
                </table>
            </center>
        </div>';

