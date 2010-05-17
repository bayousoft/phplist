
function pageParameter( name ) {
  name = name.replace(/[\[]/,"\\\[").replace(/[\]]/,"\\\]");
  var regexS = "[\\?&]"+name+"=([^&#]*)";
  var regex = new RegExp( regexS );
  var results = regex.exec( window.location.href );
  if( results == null )
    return "";
  else
    return results[1];
}

function messageStatusUpdate(msgid) {
   $('#messagestatus'+msgid).load('./?page=ajaxcall&action=msgstatus&id='+msgid,"",function() {
   });
   setTimeout("messageStatusUpdate("+msgid+")",5000);
}

function totalSentUpdate(msgid) {
   $('#totalsent'+msgid).load('./?page=ajaxcall&action=msgsent&id='+msgid,"",function() {
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
  var thispage = pageParameter('page');
  parent = $(this).parent();
  parent.load(url+'&ajaxed=true&page=pageaction&action='+thispage);
  return false;
})

$(".configcontent").blur(function() {
  alert("Save"+this.id+this.innerHTML);
})

})
