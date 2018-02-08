<?php
define('DBHOST','localhost');
define('DBUSER','root');
define('DBPASS','codegorilla');
define('DBNAME','blog');

try {
  $db = new PDO("mysql:host=".DBHOST.";dbname=".DBNAME."", DBUSER, DBPASS);
  $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
  echo "Error!: " . $e->getMessage() . "<br/>";
  die();
}

date_default_timezone_set('Europe/Amsterdam');
?>
