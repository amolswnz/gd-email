<?php

  $currentUrl = 'http' . (isset($_SERVER['HTTPS']) ? 's' : '') . '://' . "{$_SERVER['HTTP_HOST']}{$_SERVER['REQUEST_URI']}";
  print_r($currentUrl);

  $sendEmailUrl = explode("/", $currentUrl);

  print_r($sendEmailUrl);

  array_pop($sendEmailUrl);  // file name
  array_pop($sendEmailUrl);  // folder name
echo "<br>";
  print_r($sendEmailUrl);

  array_push($sendEmailUrl, "cron");
  array_push($sendEmailUrl, "send-mail-template.php");

  $url = implode("/", $sendEmailUrl);
  print_r($url);
