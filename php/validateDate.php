<?php
  date_default_timezone_set('Pacific/Auckland');  // Set NZ TimeZone
  try {
    $scheduledDate = new DateTime($_POST['textDate']);
    echo json_encode(array("success" => $scheduledDate->format("l jS \of F Y H:i:s")));
  } catch (Exception $e) {
    echo json_encode(array("error" => "Invalid datetime format given in the string representation"));
  }
