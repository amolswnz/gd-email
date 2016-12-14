<?php
  require_once 'connect-inc.php';
  // var_dump($_POST);
  echo json_encode(true);



  // save ? db : file

/*

    db
      queries will be required
      -temp session id or unique key will be required
      no need for clearing the entered data
      easy to read

      sessionId can be created in first execution
        ? how to determine the first execution
        A if the field name afterPreviousEvent is not present
          then this will be first Event in all other cases
          it will have zero or null value



    file
      file first time will be empty
      -apeend to file repeat calls will delete the file
      file can be delete on finish
      -read problem


*/
