<?php
    require_once 'connect-inc.php';
    $sql = "SELECT * FROM EmailMsg";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    $results = $stmt->fetchAll();
