<?php
class TCPDatabase {

	/** Give a connection to the training DB, in UTF-8 */
	public static function getConnection() {
		$dbname = "training_center";
		$dbhost = "localhost";
		$dbuser = "root";
		$dbpass = "root";
		$db_con = new PDO("mysql:host={$dbhost};dbname={$dbname}", $dbuser, $dbpass);
		$db_con->exec("SET character_set_client = 'utf8'");
		return $db_con;
	}
}
