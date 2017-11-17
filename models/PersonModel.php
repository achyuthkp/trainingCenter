<?php

require_once "TCPDatabase.php";

class PersonModel {

	public static function getByPersonId($personId) {
		$db = TCPDatabase::getConnection();
		$sql = "SELECT person_id, name FROM `person` WHERE `person_id` = :person_id";
		$stmt = $db->prepare($sql);
		$stmt->bindValue(":person_id", $personId);
		$ok = $stmt->execute();
		if ($ok) {
			return $stmt->fetch(PDO::FETCH_ASSOC);
		}
	}

	public static function getByLoginPassword($email, $password) {
		$db = TCPDatabase::getConnection();
		$sql = "SELECT * FROM `person` WHERE (`email` = :email AND `password`=:password)";
		$stmt = $db->prepare($sql);
		$stmt->bindValue(":email", $email);
		$stmt->bindValue(":password", $password);
		$ok = $stmt->execute();
		if ($ok) {
			return $stmt->fetch(PDO::FETCH_ASSOC);
		}
		else{
			return null;
		}
	}

	public static function redirect($url){
		if (headers_sent()){
			die('<script type="text/javascript">window.location.href="' . $url . '";</script>');
		}else{
			header('Location: ' . $url);
			die();
		}
	}
	
	public static function get($personId) {
		$db = TCPDatabase::getConnection();
		$stmt = $db->prepare("SELECT * FROM `person` WHERE `person_id` = :person_id");
		$stmt->bindValue(":person_id", $personId);
		$ok = $stmt->execute();
		if ($ok) {
			return json_encode($stmt->fetch(PDO::FETCH_ASSOC));
		}
		else{
			return false;
		}
	}

	//Get the first and last name of the person
	public static function getName($person_id) {
		$db = TCPDatabase::getConnection();
		$stmt = $db->prepare("SELECT * FROM `person` WHERE `person_id` = :person_id");
		$stmt->bindValue(":person_id", $person_id);
		$stmt->execute();
		$name = $stmt->fetch(PDO::FETCH_ASSOC);
		$val = $name['first_name'].' '.$name['last_name'];
		return $val;
	}

	public static function showName($person_id) {
		$db = TCPDatabase::getConnection();
		$stmt = $db->prepare("SELECT * FROM `person` WHERE `person_id` = :person_id");
		$stmt->bindValue(":person_id", $person_id);
		$stmt->execute();
		$name = $stmt->fetch(PDO::FETCH_ASSOC);
		print $name['first_name'].' '.$name['last_name'];
	}

}
	
?>