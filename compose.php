<!DOCTYPE html>
<html>
  <head>
    <title>Email Campaign</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Bootstrap -->
    <!-- Latest compiled and minified CSS -->
    <link rel="stylesheet" href="http://netdna.bootstrapcdn.com/bootstrap/3.0.0/css/bootstrap.min.css">
    <!-- Optional theme -->
    <link rel="stylesheet" href="http://netdna.bootstrapcdn.com/bootstrap/3.0.0/css/bootstrap-theme.min.css">
    <!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
      <script src="../../assets/js/html5shiv.js"></script>
      <script src="../../assets/js/respond.min.js"></script>
    <![endif]-->
  </head>
  <body>
  <div class="container">
    <div class="row">
        <div class="col-sm-6">
          <h1>Composer email template</h1>
          <form class="form" action="php/save-template.php" method="post">
            <input type="text" class="form-control" name="templateName" placeholder="Template name">
            <br>
            <input type="text" class="form-control" name="subject" maxlength="40" placeholder="Email subject">
            <br>
            <textarea class="form-control" rows="13" name="msgBody"></textarea>
            <br>
            <button type="submit" class="btn btn-primary">Submit</button>
          </form>
        </div>
    </div>

 </div>

    <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
    <script src="http://code.jquery.com/jquery-latest.min.js" type="text/javascript"></script>
    <!-- Latest compiled and minified JavaScript -->
    <script src="http://netdna.bootstrapcdn.com/bootstrap/3.0.0/js/bootstrap.min.js"></script>
  </body>
</html>
