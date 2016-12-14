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
        <h1>Schedule email timeframe</h1>
        <form class="form-horizontal" action="php/schedule-email.php" method="post" id="frm">
          <div class="form-group">
            <label for="title"></label>
            <input type="text" class="form-control" id="title" name="title" placeholder="Schedule title">
          </div>
          <div class="form-group">
            <label for="textVersion"></label>
            <input type="text" class="form-control" id="textVersion" name="textVersion" placeholder="Timeframe to send">
            <p class="help-block">eg. Tomorrow, +2 days, This Sunday, Next Monday, +1 hour</p>
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
          <div class="form-group" id="formActions">
            <label for=""></label>
            <button type="button" class="btn btn-info" id="addAction">Add Action</button>
            <button type="submit" class="btn btn-primary" id="finished">Finish</button>
          </div>
          <div id="additionalActions">
            x
          </div>
        </form>
      </div>
    </div>
 </div>
  <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
  <script src="http://code.jquery.com/jquery-latest.min.js" type="text/javascript"></script>
  <!-- Latest compiled and minified JavaScript -->
  <script src="http://netdna.bootstrapcdn.com/bootstrap/3.0.0/js/bootstrap.min.js"></script>
  <script type="text/javascript">
    $("#addAction").click(function(event) {
      console.log("Event");
      $.ajax({
        url: 'php/tempSession-ajax.php',
        type: 'POST',
        dataType: 'json',
        data: $("#frm").serializeArray()
      })
      .done(function() {
        var templateData;
        console.log("success");
        $.ajax({
          url: 'xform-fields.html',
          type: 'GET',
          dataType: 'html'
        })
        .done(function(data) {
          console.log("success", data);
          templateData = data;
        })
        .fail(function() {
          console.log("error");
        })
        .always(function() {
          console.log("complete");
        });
        $("#formActions").remove();   // Delete the form actions Finish and Add Action
        $("#additionalActions").append("templateData");

        $.get("form-fields.html", function (data) {
            $("#additionalActions").append(data);
        });


        // location.reload();
      })
      .fail(function() {
        console.log("error");
      })
      .always(function() {
        console.log("complete");
      });
    });
  </script>
</body>
</html>
