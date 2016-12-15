<?php
    require_once 'connect-inc.php';
    $sql = "SELECT * FROM EmailMsg WHERE id = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->bindValue(1, $_POST['id']);
    $stmt->execute();
    $result = $stmt->fetch();
    echo json_encode($result);
