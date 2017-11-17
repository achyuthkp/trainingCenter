<?php

require_once "TCPDatabase.php";

class TeamModel {


	//Add a new member to the team
	public static function addMember($person_id,$team_id) {
		$db = TCPDatabase::getConnection();
		$sql = "INSERT INTO `team_member`(`team_id`, `student_id`) VALUES (:team_id, :student_id)";
		$stmt = $db->prepare($sql);
		$stmt->bindValue(":team_id", $team_id);
		$stmt->bindValue(":student_id",$person_id);
		$ok = $stmt->execute();
		
	}
	
	//Remove a member from the team
	public static function removeMember($person_id,$team_id) {
		$db = TCPDatabase::getConnection();
		$sql = "DELETE FROM `team_member` WHERE `student_id`=:student_id AND `team_id`=:team_id";
		$stmt = $db->prepare($sql);
		$stmt->bindValue(":team_id", $team_id);
		$stmt->bindValue(":student_id",$person_id);
		$ok = $stmt->execute();
	
	}

	//Check if a member is the owner of the team
	public static function IsTeamOwner($owner_id, $team_id) {
		$db = TCPDatabase::getConnection();
		$sql = "SELECT * 
				FROM `team` 
				WHERE `team_id` = :team_id AND `owner_id` = :owner_id";
		$stmt = $db->prepare($sql);
		$stmt->bindValue(":team_id", $team_id);
		$stmt->bindValue(":owner_id", $owner_id);
		$stmt->execute();
		if ($stmt->fetchColumn()>=1) {
			return true;
		}
		else{
			return false;
		}
	}

	//Check if the person is already in the team
	public static function IsInTeam($person_id, $team_id) {
		$db = TCPDatabase::getConnection();
		$sql = "SELECT *
              FROM `team_member`
              WHERE `team_id` = :team_id AND `student_id` = :person_id";
		$stmt = $db->prepare($sql);
		$stmt->bindValue(":team_id", $team_id);
		$stmt->bindValue(":person_id", $person_id);
		$stmt->execute();
		if ($stmt->fetchColumn()>=1) {
			return true;
		}
		else{
			return false;
		}
	}

	// Get all the teams from a specific person
	public static function getMyTeams($person_id) {
	    $db = TCPDatabase::getConnection();
	    $stmt = $db->prepare("SELECT * FROM `team` WHERE `owner_id` = :person_id");
	    $stmt->bindValue(":person_id", $person_id);
	    $stmt->execute();
	    $team = $stmt->fetchAll();
	    return $team;
	}

	//Get all the elements associated with the team
	public static function getTeamDetails($team_id) {
	    $db = TCPDatabase::getConnection();
	    $stmt = $db->prepare("SELECT * FROM `team` WHERE `team_id` = :team_id");
	    $stmt->bindValue(":team_id", $team_id);
	    $ok = $stmt->execute();
	    if($ok){
	      return $stmt->fetch(PDO::FETCH_ASSOC);
	    }
	    else{
	      return false;
	    }
	}

  	//Get all team members from a specific team
  	public static function getTeam($team_id) {
	    $db = TCPDatabase::getConnection();
	    $stmt = $db->prepare("SELECT * FROM `team_member` WHERE `team_id` = :team_id");
	    $stmt->bindValue(":team_id", $team_id);
	    $stmt->execute();
	    $team = $stmt->fetchAll();
	    return json_encode($team);
  	}
  
	// Add a new team
	public static function addTeam($project_id, $owner_id, $summary) {
	    $db = TCPDatabase::getConnection();
	    $sql = "INSERT INTO `team`(`project_id`, `owner_id`, `summary`) VALUES (:project_id, :owner_id, :summary)";
	    $stmt = $db->prepare($sql);
	    $stmt->bindValue(":project_id", $project_id);
	    $stmt->bindValue(":owner_id", $owner_id);
	    $stmt->bindValue(":summary", $summary);
	    $ok = $stmt->execute();
	    $team_id = $db->lastInsertId();
	    $_SESSION['team_id'] = $team_id;
	    if ($ok) {
	    	$stmt = $db->prepare("INSERT INTO `team_member`(`team_id`, `student_id`) VALUES (:team_id, :student_id)");
	    	$stmt->bindValue(":team_id", $team_id);
	      	$stmt->bindValue(":student_id", $owner_id);
	      	$stmt->execute();
	        return true;
	    }
	    else{
		    return false;
	    }
	}

	// Update the team
	public static function UpdateTeam($team_id, $summary) {
	    $db = TCPDatabase::getConnection();
	    $stmt = $db->prepare("UPDATE `team` SET `summary` = :summary WHERE `team_id` = :team_id");
	    $stmt->bindValue(":team_id", $team_id);
	    $stmt->bindValue(":summary", $summary);
	    $ok = $stmt->execute();
	    if ($ok) {
	    	return true;
	      
	    }
	    else{
	      	return false;
	    }
	}

	//Get all the team from a project
	public static function getTeamByProject($project_id) {
		$db = TCPDatabase::getConnection();
		$stmt = $db->prepare("SELECT * FROM `team` WHERE `project_id` = :project_id");
		$stmt->bindValue(":project_id", $project_id);
		$stmt->execute();
		$team = $stmt->fetchAll();
		return $team;
	}

	//Get all the information from the project for a specific class
	public static function getTeamMembersSelect($team_id) {
	    $db = TCPDatabase::getConnection();
	    $stmt = $db->prepare("SELECT * FROM `person` INNER JOIN `team_member` ON `person`.`person_id` = `team_member`.`student_id` WHERE `team_id` = :team_id");
	    $stmt->bindValue(":team_id", $team_id);
	    $stmt->execute();
	    $teamMembers = $stmt->fetchAll();
	    $select = '';
	    for($i=0; $i<count($teamMembers); $i++){
	      $select .= '<option value="' .$teamMembers[$i]['person_id']. '">' .$teamMembers[$i]['first_name'].' '.$teamMembers[$i]['last_name']. '</option>';
	    }
	    return $select;
	}

}