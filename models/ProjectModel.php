<?php

require_once("TCPDatabase.php");


class ProjectModel {


  //Get all the information from the current project
  public static function get($project_id) {
    $db = TCPDatabase::getConnection();
    $stmt = $db->prepare("SELECT * FROM `project` WHERE `project_id` = :project_id");
    $stmt->bindValue(":project_id", $project_id);
    $ok = $stmt->execute();
    if ($ok) {
      return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    else{
      return false;
    }
  }
  
  
  //Get all the projects associated with the current trainer
  public static function getMyProjects($person_id) {
    $db = TCPDatabase::getConnection();
    $stmt = $db->prepare("SELECT * FROM `project` WHERE `owner_id` = :person_id");
    $stmt->bindValue(":person_id", $person_id);
    $stmt->execute();
    $project = $stmt->fetchAll();
      return $project;
  }
  

  //Get all the information from the project for a specific class
  public static function getProjectSelect($class_id) {
    $db = TCPDatabase::getConnection();
    $stmt = $db->prepare("SELECT * FROM `project` WHERE `class_id` = :class_id");
    $stmt->bindValue(":class_id", $class_id);
    $stmt->execute();
    $project = $stmt->fetchAll();
    $select = '';
    for($i=0; $i<count($project); $i++){
      $select .= '<option value="' .$project[$i]['project_id']. '">' .$project[$i]['title']. '</option>';
    }
    return $select;
  }
  
  //add a new project
  public static function add($person_id, $class_id, $title, $deadline, $subject) {
    $db = TCPDatabase::getConnection();
    $sql = "INSERT INTO `project`(`owner_id`, `class_id`, `title`, `deadline`, `subject`) VALUES (:person_id, :class_id, :title, :deadline, :subject)";
    $stmt = $db->prepare($sql);
    $stmt->bindValue(":person_id", $person_id);
    $stmt->bindValue(":class_id", $class_id);
    $stmt->bindValue(":title", $title);
    $stmt->bindValue(":deadline", $deadline);
    $stmt->bindValue(":subject", $subject);
    $ok = $stmt->execute();
    if ($ok) {
      return true;
    }
    else{
      return false;
    }
  }
  

  //Update the Project 
  public static function Update($project_id, $title, $deadline, $subject) {
    $db = TCPDatabase::getConnection();
    $stmt = $db->prepare("UPDATE `project` SET `title` = :title , `deadline` = :deadline , `subject` = :subject WHERE `project_id` = :project_id");
    $stmt->bindValue(":title", $title);
    $stmt->bindValue(":deadline", $deadline);
    $stmt->bindValue(":subject", $subject);
    $stmt->bindValue(":project_id", $project_id);
    $ok = $stmt->execute();
    if ($ok) {
      return true;
    }
    else{
      return false;
    }
  } 
}

?>