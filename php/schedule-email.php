<?php
  require_once 'connect-inc.php';

  // print_r($_POST);

  if(isset($_POST['dateVersion']) && !empty($_POST['dateVersion'])) {
    $scheduledDate = new DateTime($_POST['dateVersion']);
  }
  else {
    try {
      $scheduledDate = new DateTime($_POST['textVersion']);
    } catch (Exception $e) {
      echo json_encode(array("ERROR" => "Invalid datetime format given in the string representation"));
      exit(1);
    }
  }

  $minutes =  (int)$scheduledDate->format('i');
  $hours = (int)$scheduledDate->format('H');
  $date = (int)$scheduledDate->format('d');
  $month = (int)$scheduledDate->format('m');

  $curlScheduleString = "$minutes $hours $date $month * ";
  echo "<h2> $curlScheduleString";
//
//   // Parameter incomplete
//   $filename = "http://localhost:8888/email/email/cron/send-mail-template.php?id=$_POST[templateId]";
//
// echo "<h1>" . 'http' . (isset($_SERVER['HTTPS']) ? 's' : '') . '://' . "{$_SERVER['HTTP_HOST']}{$_SERVER['REQUEST_URI']}"  .
// "</h1>";

  $currentUrl = 'http' . (isset($_SERVER['HTTPS']) ? 's' : '') . '://' . "{$_SERVER['HTTP_HOST']}{$_SERVER['REQUEST_URI']}";
  $sendEmailUrlArray = explode("/", $currentUrl);
  array_pop($sendEmailUrlArray);  // removing current fileName from the url
  array_pop($sendEmailUrlArray);  // goining up one folder
  array_push($sendEmailUrlArray, "cron"); // getting inside cron job folder
  array_push($sendEmailUrlArray, "send-mail-template.php"); // The file which needs to be executed
  $sendEmailUrl = implode("/", $sendEmailUrlArray);

  $sendEmailUrl .= "?id=$_POST[templateId]";  // Adding templateId to the url

  echo " curl '$sendEmailUrl' ";
  echo "</h2>";


  $curlString = $curlScheduleString . ' curl "' . $sendEmailUrl . '"';
  // $curlScheduleString            * * * * *x
  // $curlString                    curl http://localhost:8888/email/cron/job1.php
  // $outputFile                    >> /Applications/MAMP/htdocs/email/output.log
  // $errorLogFile                  2>>/Applications/MAMP/htdocs/email/error.log

  $outputFileName = "/Applications/MAMP/htdocs/email/email/log/output.log";
  $errorFileName = "/Applications/MAMP/htdocs/email/email/log/error.log";

  $outputFile = " >> $outputFileName";
  $errorLogFile = "2>>$errorFileName";

  echo "<h4>Final string <br>$curlString $outputFile $errorLogFile</h4>";

  $sql = "INSERT INTO Schedule (emailMsgId, title, curlString) VALUES (?, ?, ?)";
  $stmt = $pdo->prepare($sql);
  $stmt->bindValue(1, $_POST['templateId']);
  $stmt->bindValue(2, $_POST['title']);
  $stmt->bindValue(3, "$curlString $outputFile $errorLogFile");
  $stmt->execute();
  $lastId = $pdo->lastInsertId();     // Returns last inserted id
  echo json_encode(array("SUCCESS", "Email job scheduled"));

  // Schedule
    // id
    // emailMsgId
    // title
    // curlString
    // status - "Active" / "Inactive"
    // dateCreated
    // dateUpdated




  //
  // try {
  //   $sql = "INSERT INTO Schedule ($keys) VALUES ($questionMarks)";
  //   echo $sql;
  //   $stmt = $pdo->prepare($sql);
  //   $count=1;
  //   foreach ($_POST as $value) {
  //       $stmt->bindValue($count++, $value);
  //   }
  //   $stmt->execute();
  //   $lastId = $pdo->lastInsertId();     // Returns last inserted id
  // } catch(PDOException $e) {
  //     die( "<div class='alert alert-error'>ERROR : " . $e->getMessage() . "</div>");
  // }
  //   echo " last id $lastId";
  ?>
