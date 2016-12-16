<?php
  define("HOST","localhost");
  define("DATABASE","gd_email");
  define("USERNAME","root");
  define("PASSWORD","root");
  date_default_timezone_set('Pacific/Auckland');  // Set NZ TimeZone

  /* Creating database connection */
  try {
   $pdo = new PDO("mysql:host=" . HOST . ";dbname=" . DATABASE, USERNAME, PASSWORD);
   $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
   $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
   $pdo->exec("SET NAMES 'utf8'");
   return $pdo;
  } catch(PDOException $e) {
   die("<div class='alert alert-danger'>ERROR : " . $e->getMessage() . "</div>");
  }

/* Helper function to generate placeholders */
function placeholders($dataArray) {
    $result = array();
    $count = sizeof($dataArray);
    for($x=0; $x < $count; $x++) {
        $result[] = "?";
    }
    return implode(",", $result);
} 
