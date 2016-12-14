<?php
  require_once 'connect-inc.php';

  if(isset($_POST['dateVersion']) && !empty($_POST['dateVersion'])) {
    $scheduledDate = new DateTime($_POST['dateVersion']);
  }
  else {
    try {
      $scheduledDate = new DateTime($_POST['textVersion']);
    } catch (Exception $e) {
      echo json_encode(array("ERROR" => "Invalid datetime format given in the string representation"));
      die( "<div class='alert alert-error'>ERROR : " . $e->getMessage() . "</div>");
    }
  }

  $minutes = (int)$scheduledDate->format('i');
  $hours   = (int)$scheduledDate->format('H');
  $date    = (int)$scheduledDate->format('d');
  $month   = (int)$scheduledDate->format('m');

  $curlScheduleString = "$minutes $hours $date $month * ";

  $currentUrl = 'http' . (isset($_SERVER['HTTPS']) ? 's' : '') . '://' .
                    "{$_SERVER['HTTP_HOST']}{$_SERVER['REQUEST_URI']}";
  $sendEmailUrlArray = explode("/", $currentUrl);
  array_pop($sendEmailUrlArray);  // removing current fileName from the url
  array_pop($sendEmailUrlArray);  // goining up one folder
  array_push($sendEmailUrlArray, "cron"); // getting inside cron job folder
  array_push($sendEmailUrlArray, "send-mail-template.php"); // The file which needs to be executed
  $sendEmailUrl = implode("/", $sendEmailUrlArray); // Creating string from array

  $sendEmailUrl .= "?id=$_POST[templateId]";  // Adding templateId to the url

  $curlString = ' curl "' . $sendEmailUrl . '"';
  $outputFileName = "/Applications/MAMP/htdocs/email/email/log/output.log";
  $errorFileName = "/Applications/MAMP/htdocs/email/email/log/error.log";

  $outputFile = " >> $outputFileName";
  $errorLogFile = "2>>$errorFileName";

  // echo "<h4>Final string <br>$curlScheduleString $curlString $outputFile $errorLogFile</h4>";
  // $curlScheduleString            Min Hour Day Month DoW
  // $curlString                    curl http://localhost:8888/email/cron/job1.php
  // $outputFile                    >> /Applications/MAMP/htdocs/email/output.log
  // $errorLogFile                  2>>/Applications/MAMP/htdocs/email/error.log

  $sql = "INSERT INTO Schedule (emailMsgId, title, curlString) VALUES (?, ?, ?)";
  $stmt = $pdo->prepare($sql);
  $stmt->bindValue(1, $_POST['templateId']);
  $stmt->bindValue(2, $_POST['title']);
  $stmt->bindValue(3, "$curlString $outputFile $errorLogFile");
  $stmt->execute();
  echo json_encode(array("SUCCESS", "Email job scheduled"));
