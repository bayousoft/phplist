
function urlParameter( name, link) {
  name = name.replace(/[\[]/,"\\\[").replace(/[\]]/,"\\\]");
  var regexS = "[\\?&]"+name+"=([^&#]*)";
  var regex = new RegExp( regexS );
  var results = regex.exec( link );
  if( results == null )
    return "";
  else
    return results[1];
}

function messageStatusUpdate(msgid) {
   $('#messagestatus'+msgid).load('./?page=pageaction&ajaxed=true&action=msgstatus&id='+msgid,"",function() {
   });
   setTimeout("messageStatusUpdate("+msgid+")",5000);
}

function openDialog(url) {
  $("#dialog").dialog({
    minHeight: 400,
    width: 600,
    modal: true,
    show: 'blind',
    hide: 'explode'
  });
  var destpage = urlParameter('page',url);
  url = url.replace(/page=/,'origpage=');
  $("#dialog").load(url+'&ajaxed=true&page=pageaction&action='+destpage);
}

function totalSentUpdate(msgid) {
   $('#totalsent'+msgid).load('./?page=pageaction&ajaxed=true&action=msgsent&id='+msgid,"",function() {
   });
   setTimeout("totalSentUpdate("+msgid+")",5000);
}

$(document).ready(function(){

$(".configurelink").click(function() {
 // alert(this.href);
  $("#configurecontent").load('./?page=ajaxcall&action=test');
  $("#configurecontent").show();
  return false;
})

$("a.ajaxable").click(function() {
  var url = this.href;
  var thispage = urlParameter('page',window.location.href);
  var action = urlParameter('action',url);
  if (action == "") {
    url += '&action='+thispage;
  }
  parent = $(this).parent();
  url = url.replace(/page=/,'origpage=');
  //alert(url+'&ajaxed=true&page=pageaction');
  parent.load(url+'&ajaxed=true&page=pageaction');
  return false;
})

$("input:checkbox.checkallcheckboxes").click(function() {
  if (this.checked) {
    $("input[type=checkbox]:not(:checked)").each(function(){
      this.checked = true;
    });
  } else {
    $("input[type=checkbox]:checked").each(function(){
      this.checked = false;
    });
  }
})

  var stop = false;

$(".accordion").accordion({
    autoHeight: false,
    navigation: true,
    collapsible: true
  })

$(".opendialog").click(function() {
  openDialog(this.href);
  return false;
});
$(".helpdialog").click(function() {
  openDialog(this.href);
  return false;
});
$(".closedialog").click(function() {
  $("#dialog").dialog('close');
});


/* hmm, doesn't work yet, but would be nice at some point
$("#emailsearch").autocomplete({
  source: "?page=pageaction&ajaxed=true&action=searchemail",
  minLength: 2,
  select: function(event, ui) {
  log(ui.item ? ("Selected: " + ui.item.value + " aka " + ui.item.id) : "Nothing selected, input was " + this.value);
  }
});
*/

  $("#listinvalid").load("./?page=pageaction&action=listinvalid&ajaxed=true",function() {
   alert("Loaded")
   });

  $(".tabbed").tabs({
    ajaxOptions: {
      error: function(xhr, status, index, anchor) {
        $(anchor.hash).html("Error fetching page");
      }
    }
  });

  $("#remoteurlinput").blur(function() {
    $("#remoteurlstatus").load("./?page=pageaction&action=checkurl&ajaxed=true&url="+this.value);
  })

  $("input:radio[name=sendmethod]").change(function() {
    if (this.value == "remoteurl") {
      $("#remoteurl").show();
      $("#messagecontent").hide();
    } else {
      $("#remoteurl").hide();
      $("#messagecontent").show();
    }
  })

  $("a.savechanges").click(function() {
    if (changed) {
      document.sendmessageform.followupto.value = this.href;
      document.sendmessageform.submit();
      return false;
    }
  });

})
