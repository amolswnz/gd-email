<!--
  Additional parameters set as a json string in
  addtional Parameters json object
    Send email to client -  true or false
    Send email copy to me -  true or false
    Remind me to do this -  False or the Composed reminder email text

  additionalParam = {
    sendEmail: true/false,
    sendCopy: true/false,
    sendReminder: false/Text
  }
-->
<?php
  require_once 'connect-inc.php';

  // var_dump($_POST);

  $titleOfSchedule = array_shift($_POST);
  // var_dump($titleOfSchedule);

  $arrayCounter = 0;
  $previousIndex = 1;
  // $arrayIndex = 0;
  $insertDataArrayUnformatted = array( array());
  foreach ($_POST as $key => $value) {
    // Gets current key number eg from dateVersion_2 gives 2
    $currentIndex = filter_var($key, FILTER_SANITIZE_NUMBER_INT);
    // Check if the current item belongs to same group or not
    // Done by checking previous and current index equality
    if($previousIndex != $currentIndex) {
      // echo "<p> $currentIndex $previousIndex array(count)->$arrayCounter </p>";
      $previousIndex = $currentIndex;
      $arrayCounter++;
      // $arrayIndex = 0;
      // Create new array counter to add all the group variable in one array
    }
    // Removing number from the key name
    $keyNameClean = str_replace("_$currentIndex", "", $key);
    $insertDataArrayUnformatted[$arrayCounter][$keyNameClean] = $value;
  }
  // var_dump($insertDataArrayUnformatted);

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
    isset($data['reminderText']) ? $extraOptions['reminderText'] = $data['reminderText'] : $extraOptions['reminderText'] = false;
    // Addtional parameters store as json string - { sendEmail: true/false, sendCopy: true/false, sendReminder: false/Text }
    $insertData[$count]['additionalParam'] = json_encode($extraOptions);

    $insertData[$count]['emailMsgId'] = $data['templateId'];      // emailMsgId
    $insertData[$count]['scheduleTitle'] = $titleOfSchedule;     // Every entry has same schedule title

    $count++; // increasing array counter for insertData varible
  }

  var_dump($insertData);
  // var_dump($insertDataArrayUnformatted);
  // Schedule
  //   id
  //   scheduleTitle
  //   emailMsgId
  //   curlString
  //   specifiedDateTime
  //   additionalParam
  //   status - "Active" / "Inactive"
  //   dateCreated
  //   dateUpdated
  // $sql = "INSERT INTO Schedule (emailMsgId, scheduleTitle, curlString, specifiedDateTime, additionalParam) VALUES (?, ?, ?, ?, ?)";
  // $stmt = $pdo->prepare($sql);
  // $stmt->bindValue(1, $_POST['templateId']);
  // $stmt->bindValue(2, $_POST['title']);
  // $stmt->bindValue(3, "$curlScheduleString $curlString $outputFile $errorLogFile");
  // $stmt->bindValue(4, "X");
  // $stmt->execute();
  // echo json_encode(array("SUCCESS", "Email job scheduled"));
  $dataFields =
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
