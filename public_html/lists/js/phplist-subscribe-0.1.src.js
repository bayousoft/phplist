
$(document).ready(function(){
  $("#phplistsubscribeform").submit(function() {
    var emailaddress = $("#emailaddress").val();
    var emailReg = /^([\w-\.]+@([\w-]+\.)+[\w-]{2,4})?$/;
    var subscribeaddress = this.action;
    subscribeaddress = subscribeaddress.replace(/asubscribe/,'subscribe');
    if(emailReg.test(emailaddress)) {
      var jqxhr = $.ajax({
        type: 'POST',
        url: this.action,
        data: "email="+emailaddress,
        success: function(data, textStatus, jqXHR ) {
          if (data == 'FAIL') {
            document.location = subscribeaddress+"&email="+emailaddress;
          } else {
            $('#phplistsubscriberesult').html("<div id='subscribemessage'></div>");
            $('#subscribemessage').html(data)
            .hide()
            .fadeIn(1500);
            $("#phplistsubscribeform").hide();
          }
        },
        error: function(jqXHR, textStatus, errorThrown) {
          document.location = subscribeaddress+"&email="+emailaddress;
        },
      });
    } else {
      document.location = subscribeaddress+"&email="+emailaddress;
    }
    return false;
  });

  $("#emailaddress").val(pleaseEnter);
  $("#emailaddress").focus(function() {
    var v = $("#emailaddress").val();
    if (v == pleaseEnter) {
      $("#emailaddress").val("")
    }
  });
  $("#emailaddress").blur(function() {
    var v = $("#emailaddress").val();
    if (v == "") {
      $("#emailaddress").val(pleaseEnter)
    }
  });
});

if (pleaseEnter == undefined) {
  var pleaseEnter = "Please enter your email";
}


