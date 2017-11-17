<?php
require_once("TCPDatabase.php");


class ClassModel {

	//Get class id
	public static function getClassId($class_id) {
		$db = TCPDatabase::getConnection();
		$stmt = $db->prepare("SELECT * FROM `class` WHERE `class_id` = :class_id");
		$stmt->bindValue(":class_id", $class_id);
		$ok = $stmt->execute();
		if ($ok) {
			return $stmt->fetch(PDO::FETCH_ASSOC);
		}
		else{
			return false;
		}
	}

	//Get all members from a class
	public static function getClassMembers($class_id) {
		$db = TCPDatabase::getConnection();
		$stmt = $db->prepare("SELECT * FROM `class_member` WHERE `class_id` = :class_id");
		$stmt->bindValue(":class_id", $class_id);
		$stmt->execute();
		$class = $stmt->fetchAll();
		return $class;
	}
	

	//Select all elements from the class
	public static function getClassSelect() {
		$db = TCPDatabase::getConnection();
		$stmt = $db->prepare("SELECT * FROM `class`");
		$stmt->execute();
		$class = $stmt->fetchAll();
		$select = '';
		for($i=0; $i<count($class); $i++){
			$select .= '<option value="' .$class[$i]['class_id']. '">' .$class[$i]['name']. '</option>';
		}
		return $select;
	}

	//Get the class name
	public static function showName($class_id) {
		$db = TCPDatabase::getConnection();
		$stmt = $db->prepare("SELECT * FROM `class` WHERE `class_id` = :class_id");
		$stmt->bindValue(":class_id", $class_id);
		$stmt->execute();
		$class = $stmt->fetch(PDO::FETCH_ASSOC);
		print $class["name"];;
	}

	//Get the class the person belongs to 
	public static function getClass($personId) {
		$db = TCPDatabase::getConnection();
		$stmt = $db->prepare("SELECT * FROM `class_member` WHERE `person_id` = :person_id");
		$stmt->bindValue(":person_id", $personId);
		$ok = $stmt->execute();
		if ($ok) {
			return $stmt->fetch(PDO::FETCH_ASSOC);
		}
		else{
			return false;
		}
	}


}

?>