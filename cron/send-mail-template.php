<?php
  require_once 'vendor/autoload.php';
  require_once '../php/connect-inc.php';

  // Registering a function to capture fatal errors generated from the program
  register_shutdown_function('shutDownFunction');

  // Log error in php_error.log file /Applications/MAMP/logs/php_error.log file
  if(! isset($_GET['id']))
    logErrorInDB("Template parameter NOT set ID:00x00",
        "The variable _GET[id] is not set. There is error in the parameter id");

  $sql = "SELECT * FROM EmailMsg WHERE id = ? AND status = ?";
  $stmt = $pdo->prepare($sql);
  $stmt->bindValue(1, $_GET['id']);
  $stmt->bindValue(2, "Active");
  $stmt->execute();
  $result = $stmt->fetch();

  if(! $result)
    logErrorInDB("Email Template data is not available RS:00x$_GET[id]",
        "The results set retured empty row reason data deleted. Another reason could be the temaplte id is not Active(ie Deleted).");

  // Get the current SMTP settings
  var_dump(ini_get("SMTP"));

  // Set character encoding method for Swift_Mailer
  if (function_exists('mb_internal_encoding') && ((int) ini_get('mbstring.func_overload')) & 2) {
    $mbEncoding = mb_internal_encoding();
    mb_internal_encoding('ASCII');
  }
  if (isset($mbEncoding)) {
    mb_internal_encoding($mbEncoding);
  }

  // Create the message
  $message = Swift_Message::newInstance()
                ->setSubject('Your subject')
                ->setFrom(array('john@doe.com' => 'John Doe'))
                ->setTo(array('receiver@domain.org', 'other@domain.org' => 'A name'))
                ->setBody('Here is the <strong>message</strong> itself')
                ->addPart('<q>Here is the message itself</q>', 'text/html')
                ->attach(Swift_Attachment::fromPath('composer.json'));

  $transport = Swift_SmtpTransport::newInstance('localhost', 25);
  //
  // $transport = Swift_SmtpTransport::newInstance('smtp', 25)
  //               ->setUsername('root')
  //               ->setPassword('root');

  $mailer = Swift_Mailer::newInstance($transport);

  $result = $mailer->send($message);


    // var_dump($message);
    //    http://www.blog.tripleroi.com/2012/05/solvedenabling-sendmail-on-localhost.html

// Custom function to log any error occured during the program execution
function shutDownFunction() {
    $error = error_get_last();
    // fatal error, E_ERROR === 1
    if ($error['type'] === E_ERROR) {
        // Logging error to database for further debugging
        logErrorInDB("Fatal Error caught by FE:00x$_GET[id]", implode("<br>", $error));
    }
}

// Helper function to log errors generated to db
function logErrorInDB($errorText, $error)
{
  // ErrorLog -> id, readableMessage, errorMessage, dateGenerated
  $pdo = $GLOBALS['pdo'];
  $sql = "INSERT INTO ErrorLog (readableMessage, errorMessage) VALUES (?, ?)";
  $stmt = $pdo->prepare($sql);
  $stmt->bindValue(1, $errorText);
  $stmt->bindValue(2, $error);
  $stmt->execute();
}
