

      console.log("Event");
      $.ajax({
        url: 'php/tempSession-ajax.php',
        type: 'POST',
        dataType: 'json',
        data: $("#frm").serializeArray()
      })
      .done(function() {
        console.log("success");
        var lastRepeatingGroup = $('.repeatingSection').last();
        var newSection = lastRepeatingGroup.clone();
        newSection.insertAfter(lastRepeatingGroup);
        newSection.find("input").each(function (index, input) {
            input.id = input.id.replace("_" + currentCount, "_" + newCount);
            input.name = input.name.replace("_" + currentCount, "_" + newCount);
        });
        newSection.find("label").each(function (index, label) {
            var l = $(label);
            l.attr('for', l.attr('for').replace("_" + currentCount, "_" + newCount));
        });
        return false;

        // location.reload();
      })
      .fail(function() {
        console.log("error");
      })
      .always(function() {
        console.log("complete");
      });
