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

$team_id = $_GET['tid'];
$person_id = $_SESSION['isLoggedIn'];
$class_id = ClassModel::getClass($person_id);
$person = json_decode(PersonModel::get($_SESSION['isLoggedIn']), true);


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

<h1 class="main_title">Students List:</h1>
<?php

$team_id = $_GET['tid'];
$person_id = $_SESSION['isLoggedIn'];

try {
    $class_members = ClassModel::getClassMembers($class_id["class_id"]);
} catch (PDOException $exc) {
    $msg = $exc->getMessage();
    $code = $exc->getCode();
    print "$msg (error code $code)";
}

print '<ul id="sortable2">';

for($i=0; $i<count($class_members); $i++){
    $student_ID = $class_members[$i]['person_id'];
    $student = json_decode(PersonModel::get($student_ID), true);
    
    if(!TeamModel::IsInTeam($student_ID, $team_id)){
        print "<li id=\"$student_ID \">
            <span></span>
            <div><h2>$student[first_name] $student[last_name]</h2></div>
        </li>";
    }
}
    
print '</ul>';

function display_form() {

	global $messages;
    $team_id = $_GET['tid'];
    $person_id = $_SESSION['isLoggedIn'];
    $class_id = ClassModel::getClass($person_id);
	$person_id = ($_SERVER["REQUEST_METHOD"] == "GET") ? "" : $_POST["person_id"];
	try {
        $class_members = ClassModel::getClassMembers($class_id["class_id"]);
    } catch (PDOException $exc) {
        $msg = $exc->getMessage();
        $code = $exc->getCode();
        print "$msg (error code $code)";
    }

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
                    <td><select name="person_id"><option value="">Select Member to Add</option>
END_FORM;

print '<ul id="sortable2">';

for($i=0; $i<count($class_members); $i++){
    $student_ID = $class_members[$i]['person_id'];
    $student = json_decode(PersonModel::get($student_ID), true);
    
    if(!TeamModel::IsInTeam($student_ID, $team_id)){
        print "<option value=$student[person_id]>$student[first_name] $student[last_name]</option>";
    }
}
    
print <<<END_FORM
                    <td>$messages[person_id]</td>
                </tr>         
            </table>
            <br/><br/>
            <button type="submit">Add</button>
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
		$messages["person_id"] = "Add a new member";
		display_form();
	}
	
	else {
		try {
			$addperson = TeamModel::addMember($person_id, $team_id);
			if(!$addperson){
				echo 'success';
				PersonModel::redirect("addMembers.php?tid=".$team_id);
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