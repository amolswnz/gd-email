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
    <form class="form-horizontal" action="php/schedule-email.php" method="post">
      <div class="col-md-12">
        <h1>Schedule email timeframe</h1>
        <div class="form-group">
          <label for="title"></label>
          <input type="text" class="form-control" id="title" name="title" placeholder="Schedule title">
        </div>
      </div>
      <div class="col-sm-6">
        <legend>Schedule 1:</legend>
          <fieldset>
          <div class="form-group">
            <label for="textVersion"></label>
            <input type="text" class="form-control" id="textVersion" name="textVersion" placeholder="Timeframe to send">
            <p class="help-block">eg. Tomorrow, +2 days, This Sunday, Next Monday</p>
          </div>
          OR
          <div class="form-group">
            <label for="dateVersion"></label>
            <input type="datetime-local" class="form-control" id="dateVersion" name="dateVersion" placeholder="datetime">
            <p class="help-block">Set custom date and time</p>
          </div>
          <div class="form-group">
            <label for="template">Select Template</label>
            <select class="form-control" id="template" name="templateId">
              <?php require_once 'php/getEmailMsg.php';
                foreach ($results as $row) {
                    echo "<option value='{$row['id']}'>{$row['templateName']}</option>";
                }
              ?>
            </select>
          </div>
          <div class="form-group">
            <label for=""></label>
            <button type="button" class="btn btn-success" id="action">Add action</button>
            <button type="submit" class="btn btn-primary" id="">Finished</button>
          </div>
        </fieldset>
      </div>
      <div class="col-sm-6">
        <legend>Previous action</legend>
      </div>
    </form>
  </div>
</div>
<!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
<script src="http://code.jquery.com/jquery-latest.min.js"></script>
<!-- Latest compiled and minified JavaScript -->
<script src="http://netdna.bootstrapcdn.com/bootstrap/3.0.0/js/bootstrap.min.js"></script>
<script type="text/javascript">
  $("#action").click(function(event) {
    event.preventDefault();
    console.log("Event");
  });
</script>
</body>
</html>
