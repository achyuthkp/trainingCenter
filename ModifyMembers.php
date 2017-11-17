<?php
include ("header.php");
require_once("models/ClassModel.php");
require_once("models/PersonModel.php");
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

if(!isset($_SESSION['isLoggedIn'])){
	PersonModel::redirect("login.php");
}

$person_id = $_SESSION['isLoggedIn'];
$team_id = $_GET['tid'];

try {
	$team = json_decode(TeamModel::getTeam($team_id), true);
} catch (PDOException $exc) {
	$msg = $exc->getMessage();
	$code = $exc->getCode();
	print "$msg (error code $code)";
}

print '<h1>Team Members:</h1><ul id="sortable">';

for($i=0; $i<count($team); $i++){
	$studentID = $team[$i]["student_id"];
	print "<li id=\"$studentID \">
	<span></span>
	<div><h2>".PersonModel::getName($team[$i]["student_id"])."</h2></div>
	</li>";
}

print '</ul>';
?>
<?php

$team_id = $_GET['tid'];
$person_id = $_SESSION['isLoggedIn'];
            
function display_form() {
	global $messages;
    $team_id = $_GET['tid'];
	$person_id = ($_SERVER["REQUEST_METHOD"] == "GET") ? "" : $_POST["person_id"];
    $team = TeamModel::getTeamMembersSelect($team_id);
	if (!array_key_exists("person_id", $messages)) {
		$messages["person_id"] = "";
	}
	else {
		$messages["person_id"] = "<span style='color: red;'>$messages[person_id]</span>";
	}

	print <<<END_FORM

        <form method="POST" class="StyledForm">
            <table>
              <tr>
                <th>Enter Person ID:</th>
                <td><select name="person_id"><option value="">Select Member to Remove</option>$team</td>
                <td>$messages[person_id]</td>
              </tr>
                    
            </table>
            <br/><br/>
            <button type="submit">Remove</button>
            <button type="reset">Reset</button>
        </form>
    </center>
END_FORM;

}           
            
function do_post() {
	global $messages;
	$person_id = (empty($_POST["person_id"])) ? "" : (trim($_POST["person_id"]));
	$team_id = $_GET['tid'];

	if ($person_id== "") {
		$messages["person_id"] = "Select Person";
		display_form();
	}

    else if (TeamModel::IsTeamOwner($person_id,$team_id)) {
        $messages["person_id"] = "You cannot remove the creator of a Team";
        display_form();
    }
	
	else {
		try {
			$addperson = TeamModel::removeMember($person_id, $team_id);
			if(!$addperson){
				echo 'success';
				PersonModel::redirect("ModifyMembers.php?tid=".$team_id);
			}
			else{
				
				$messages["person_id"] = "Invalid id!";
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