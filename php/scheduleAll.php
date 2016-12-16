<?php
  require_once 'connect-inc.php';

  $titleOfSchedule = array_shift($_POST);

  $arrayCounter = 0;
  $previousIndex = 1;
  $insertDataArrayUnformatted = array( array());
  foreach ($_POST as $key => $value) {
    // Gets current key number eg from dateVersion_2 gives 2
    $currentIndex = filter_var($key, FILTER_SANITIZE_NUMBER_INT);
    // Check if the current item belongs to same group or not
    // Done by checking previous and current index equality
    if($previousIndex != $currentIndex) {
      $previousIndex = $currentIndex;
      $arrayCounter++;
      // Increment array counter to add all the group variable in one array
    }
    // Removing number from the key name
    $keyNameClean = str_replace("_$currentIndex", "", $key);
    $insertDataArrayUnformatted[$arrayCounter][$keyNameClean] = $value;
  }

  $count = 0;
  $insertData = array(array());
  // Formatting the $insertDataArrayUnformatted
  foreach ($insertDataArrayUnformatted as $data) {
    // Consider date ordered in following priorities
      // Priority 1 - dateVersion
      // Priority 2 - textVersion
      // Priority 3 - textVersionWrtPrevAction
      // Execution is done in reverse order - First check for textVersionWrtPrevAction then textVersion and finally dateVersion
    // var_dump($data);

    try {                                                         // **************** JUST FOR DEBUGGING PURPOSES ***** ///
      if(isset($data['textVersionWrtPrevAction']))
        $insertData[$count]['specifiedDateTime'] = (new DateTime($data['textVersionWrtPrevAction']))->format("Y-m-d H:i:s");
    } catch(Exception $e) {                                       // **************** CAN BE REMOVED ***** ///
      $insertData[$count]['specifiedDateTime'] = (new DateTime())->format("Y-m-d H:i:s");
        // SETTING DATE TO DEFALT
    }

    try {                                                         // **************** JUST FOR DEBUGGING PURPOSES ***** ///
      if(isset($data['textVersion']))
        $insertData[$count]['specifiedDateTime'] = (new DateTime($data['textVersion']))->format("Y-m-d H:i:s");
    } catch(Exception $e) {                                       // **************** CAN BE REMOVED ***** ///
      $insertData[$count]['specifiedDateTime'] = (new DateTime())->format("Y-m-d H:i:s");
        // SETTING DATE TO DEFALT
    }

    try {                                                         // **************** JUST FOR DEBUGGING PURPOSES ***** ///
      if(isset($data['dateVersion']))
        $insertData[$count]['specifiedDateTime'] = (new DateTime($data['dateVersion']))->format("Y-m-d H:i:s");
    } catch(Exception $e) {                                       // **************** CAN BE REMOVED ***** ///
      $insertData[$count]['specifiedDateTime'] = (new DateTime())->format("Y-m-d H:i:s");
        // SETTING DATE TO DEFALT
    }                                                             // **************** AFTER ALL THE TESTING HAS BEEN FINISHED ***** ///

    // Additional parameters configuarion
    $extraOptions = array();
    isset($data['sendEmail']) ? $extraOptions['sendEmail'] = true : $extraOptions['sendEmail'] = false;
    isset($data['sendCopy']) ?  $extraOptions['sendCopy'] = true : $extraOptions['sendCopy'] = false;
    if(isset($data['reminderText']) && !empty($data['reminderText']))
      $extraOptions['reminderText'] = $data['reminderText'];
    else
      $extraOptions['reminderText'] = false;
    // Addtional parameters store as json string - { sendEmail: true/false, sendCopy: true/false, sendReminder: false/Text }
    $insertData[$count]['additionalParam'] = json_encode($extraOptions);

    $insertData[$count]['emailMsgId'] = $data['templateId'];      // emailMsgId
    $insertData[$count]['scheduleTitle'] = $titleOfSchedule;     // Every entry has same schedule title

    // CURL string
      $scheduledDate = new DateTime($insertData[$count]['specifiedDateTime']);

      $minutes = (int) $scheduledDate->format('i');
      $hours   = (int) $scheduledDate->format('H');
      $date    = (int) $scheduledDate->format('d');
      $month   = (int) $scheduledDate->format('m');

      $curlScheduleString = "$minutes $hours $date $month * ";

      $currentUrl = 'http' . (isset($_SERVER['HTTPS']) ? 's' : '') . '://' .
                              "{$_SERVER['HTTP_HOST']}{$_SERVER['REQUEST_URI']}";

      // Creating send email page url wrt current folder structure
      $sendEmailUrlArray = explode("/", $currentUrl);           // creating array from url string
      array_pop($sendEmailUrlArray);                            // removing current fileName from the url
      array_pop($sendEmailUrlArray);                            // going up one folder
      array_push($sendEmailUrlArray, "cron");                   // getting inside cron job folder
      array_push($sendEmailUrlArray, "send-mail-template.php"); // The file which needs to be executed
      $sendEmailUrl = implode("/", $sendEmailUrlArray);         // Creating string from array

      $sendEmailUrl .= "?id=" . $data['templateId'];            // Adding templateId to the url

      $curlString = ' curl "' . $sendEmailUrl . '"';
      $outputFileName = "/Applications/MAMP/htdocs/email/email/log/output.log";
      $errorFileName = "/Applications/MAMP/htdocs/email/email/log/error.log";

      $outputFile = " >> $outputFileName";
      $errorLogFile = "2>>$errorFileName";
      // echo "<h1>$curlScheduleString $curlString $outputFile $errorLogFile</h1>";
      $curlScheduleString .= "$curlString $outputFile $errorLogFile";
      echo "<h1>$curlScheduleString";

    $insertData[$count]['curlString'] = $curlScheduleString;     // Every entry has same schedule title

    $count++; // increasing array counter for insertData varible
  }

  // var_dump($insertData);
  $dataFields = array_keys($insertData[0]);
  array_keys($insertData[0] );
  $pdo->beginTransaction();
      $dataToInsert = array();
      foreach($insertData as $dataValue){
          $questionMarks[] = '('  . placeholders($dataValue) . ')';
          $dataToInsert = array_merge($dataToInsert, array_values($dataValue));
      }
      $sql = "INSERT INTO Schedule (" . implode(",", $dataFields) . ") VALUES " . implode(',', $questionMarks);
      $stmt = $pdo->prepare($sql);
      try {
          $stmt->execute($dataToInsert);
      } catch (PDOException $e){
          echo "<div class='alert alert-error'>ERROR : " . $e->getMessage() . "</div>";
      }
  $pdo->commit();
