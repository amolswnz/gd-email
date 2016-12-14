<?php
  require_once 'connect-inc.php';

  $keys = implode(",", array_keys($_POST));
  $questionMarks = placeholders($_POST);

  // Saving email message template
  try {
    $sql = "INSERT INTO EmailMsg ($keys) VALUES ($questionMarks)";
    $stmt = $pdo->prepare($sql);
    $count=1;
    foreach ($_POST as $value) {
        $stmt->bindValue($count++, $value);
    }
    $stmt->execute();
    $lastId = $pdo->lastInsertId();
  } catch(PDOException $e) {
    echo json_encode(0);
    die( "<div class='alert alert-error'>ERROR : " . $e->getMessage() . "</div>");
  }

  echo json_encode($lastId);
 ?>
