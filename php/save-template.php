<?php
    require_once 'connect-inc.php';

    $keys = implode(",", array_keys($_POST));
    $questionMarks = placeholders($_POST);

  try {
    $sql = "INSERT INTO EmailMsg ($keys) VALUES ($questionMarks)";
    echo $sql;
    $stmt = $pdo->prepare($sql);
    $count=1;
    foreach ($_POST as $value) {
        $stmt->bindValue($count++, $value);
    }
    $stmt->execute();
    $lastId = $pdo->lastInsertId();     // Returns last inserted id
  } catch(PDOException $e) {
      die( "<div class='alert alert-error'>ERROR : " . $e->getMessage() . "</div>");
  }
    echo " last id $lastId";
 ?>
