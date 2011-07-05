
var waitImg1 = 'busy/busy-phplist_alpha50.gif';
var waitImg2 = 'busy/busy-phplist_black.gif';
var waitImg3 = 'busy/busy-phplist_mix.gif';

$(document).ready(function() {
  var waitimg = new Image();
  waitimg.src = waitImage;
  $("#phplistsubscribeform").submit(function() {
    var emailaddress = $("#emailaddress").val();
    var emailReg = /^([\w-\.]+@([\w-]+\.)+[\w-]{2,4})?$/;
    var subscribeaddress = this.action;
    ajaxaddress = subscribeaddress.replace(/subscribe/,'asubscribe');
    $('#phplistsubscriberesult').html('<img src="'+waitimg.src+'" width="'+waitimg.width+'" height="'+waitimg.height+'" border="0" alt="Please wait" title="powered by phpList, www.phplist.com" />');

    if(emailReg.test(emailaddress)) {
      var jqxhr = $.ajax({
        type: 'POST',
        url: ajaxaddress,
        crossDomain: true,
        data: "email="+emailaddress,
        success: function(data, textStatus, jqXHR ) {
          if (data.search(/FAIL/) >= 0) {
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
        }
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

// cross domain fix for IE
// http://forum.jquery.com/topic/cross-domain-ajax-and-ie
$.ajaxTransport("+*", function( options, originalOptions, jqXHR ) {
  if(jQuery.browser.msie && window.XDomainRequest) {
    var xdr;
    return {
        send: function( headers, completeCallback ) {
            // Use Microsoft XDR
            xdr = new XDomainRequest();
            // would be nicer to keep it post
            xdr.open("get", options.url+"&"+options.data);
            xdr.onload = function() {
                if(this.contentType.match(/\/xml/)){
                    var dom = new ActiveXObject("Microsoft.XMLDOM");
                    dom.async = false;
                    dom.loadXML(this.responseText);
                    completeCallback(200, "success", [dom]);
                } else {
                    completeCallback(200, "success", [this.responseText]);
                }
            };
            xdr.ontimeout = function(){
                completeCallback(408, "error", ["The request timed out."]);
            };
           
            xdr.onerror = function(){
                completeCallback(404, "error", ["The requested resource could not be found."]);
            };
            xdr.send();
      },
      abort: function() {
          if(xdr)xdr.abort();
      }
    };
  }
});

if (pleaseEnter == undefined) {
  var pleaseEnter = "Please enter your email";
}
if (waitImage == undefined) {
  // trick to find the location of ourselves, but doesn't work in Chrome
  // can't find any more where I found this trick 
  var me = (new Error).fileName; 
  if (me) {
    var dir = me.substring(0,me.lastIndexOf('/'));
    dir = dir+'/../images/';
  } else {
    var dir = 'lists/images';
  }
  var waitImage = dir+waitImg1;
}  

