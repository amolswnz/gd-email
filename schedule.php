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
        <form class="form-horizontal" action="php/xschedule-email.php" method="post" id="frm">
          <div class="form-group">
            <label for="title"></label>
            <input type="text" class="form-control" id="title" name="title" placeholder="Schedule title">
          </div>
        <div class="repeatingSection">
          <hr>
          <a href="#" style="display:none" class="btn btn-danger deleteAction">Delete this Action</a>
          <div class="form-group">
            <label for="textVersion_1">Select timeframes</label>
            <input type="text" class="form-control" id="textVersion_1" name="textVersion_1" placeholder="Timeframe to send">
            <p class="help-block">eg. Tomorrow, +2 days, This Sunday, Next Monday, +1 hour</p>
          </div>
          <div class="form-group">
            <label for="dateVersion_1">OR</label>
            <input type="datetime-local" class="form-control" id="dateVersion_1" name="dateVersion_1" placeholder="datetime">
            <p class="help-block">Set custom date and time</p>
          </div>
          <div class="form-group" id="textVersionWrtPrevActionDiv_1">
            <label for="textVersionWrtPrevAction_1">OR</label>
             <input type="text" class="form-control" id="textVersionWrtPrevAction_1" name="textVersionWrtPrevAction_1" placeholder="datetime">
            <p class="help-block">Set time wrt previous action</p>
            <p class="help-block">eg. Tomorrow, +2 days, This Sunday, Next Monday, +1 hour</p>
          </div>

          <div class="form-group">
            <label for="template_1">Select Template</label>
            <select class="form-control" id="template_1" name="templateId_1">
              <?php require_once 'php/getEmailMsg.php';
                foreach ($results as $row) {
                  echo "<option value='{$row['id']}'>{$row['templateName']}</option>";
                }
              ?>
            </select>
          </div>
          <div id="additionalActions_1">
            <div class="form-group">
              <label class="checkbox-inline">
                <input type="checkbox" id="sendEmail_1" name="sendEmail_1" value="sendEmail" checked> Send email to client
              </label>
              <label class="checkbox-inline">
                <input type="checkbox" id="sendCopy_1" name="sendCopy_1" value="sendCopy"> Send email copy to me
              </label>
              <label class="checkbox-inline">
                <input type="checkbox" id="sendReminder_1" name="sendReminder_1" value="sendReminder"> Remind me to do this
              </label>
            </div>
            <div class="form-group" id="reminderTextDiv_1">
              <label for="reminderText_1">Compose your remider email</label>
              <textarea class="form-control" rows="13" id="reminderText_1" name="reminderText_1"></textarea>
            </div>
          </div>
        </div>
        <div class="form-group" id="formActions">
          <label for="actions"></label>
          <a href="#" class="btn btn-info addAction">Add Action</a>
          <button type="submit" class="btn btn-primary" id="finished">Finish</button>
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

    $(document).ready(function() {
      // Hiding with respect to div - for first entry it is Not Applicabale
      // $("#textVersionWrtPrevActionDiv_1").hide();
      // Initially reminder textarea div is hidden unless clicked on remind me checkbox
      // $("#reminderTextDiv_1").hide();
    });

    // Create new set of form inputs, if additional action is added
    $(".addAction").click(function(event) {
      var currentCount =  $('.repeatingSection').length;
      var newCount = currentCount+1;
      var repeatingFormGroup = $('.repeatingSection').last();
      // Create copy of repeatingFormGroup
      var newSection = repeatingFormGroup.clone();
      // Insert this into the DOM
      newSection.insertAfter(repeatingFormGroup).hide().show('slow');

      $("html, body").animate({ scrollTop: $(document).height() }, "slow");

      // Change the names of each input, select and div so as to distinguish the fields
      newSection.find("input, select, textarea").each( function (index, element) {
        element.id = element.id.replace("_" + currentCount, "_" + newCount);
        element.name = element.name.replace("_" + currentCount, "_" + newCount);
        element.value = '';   // Reset the value of the form field - clone function copies values too
      });

      newSection.find("div").each( function (index, element) {
        element.id = element.id.replace("_" + currentCount, "_" + newCount);
      });

      // Show with respect to form field for newCount which is created now
      $("#textVersionWrtPrevActionDiv_" + newCount).show();
      // Show delete button except for the first form group
      $('.deleteAction').slice(1).show();

      return false;
    });

    // $(document).on('click', '*', function(event) {
    //   event.preventDefault();
    //     $('html, body').animate({
    //       scrollTop: $(this).offset().top
    //   }, 2000);
    // });

    // Delete a section
    $(document).on('click','.deleteAction',function() {
        $(this).parent('div').remove();
        return false;
    });

    // Remind me textarea show
    $(document).on('click','[id^=sendReminder]',function() {
      var thisId = $(this).attr('id').match(/\d+$/)[0];
      $("#reminderTextDiv_" +  thisId).slideToggle('slow');
    });
  </script>
</body>
</html>
