
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
   $('#messagestatus'+msgid).load('./?page=ajaxcall&action=msgstatus&id='+msgid,"",function() {
   });
   setTimeout("messageStatusUpdate("+msgid+")",5000);
}

function openDialog(url) {
  $("#dialog").dialog({
    minHeight: 400,
    width: 600,
    modal: true,
    show: 'blind',
    hide: 'explode',
    // this doesn't seem to work
    //buttons: {
      //Ok: function() {
        //$(this).dialog('close');
      //}
    //}
  });
  var destpage = urlParameter('page',url);
  var url = url.replace(/page=/,'origpage=');
  $("#dialog").load(url+'&ajaxed=true&page=pageaction&action='+destpage);
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
  var thispage = urlParameter('page',window.location.href);
  var action = urlParameter('action',url);
  if (action == "") {
    action = thispage;
  }
  parent = $(this).parent();
  parent.load(url+'&ajaxed=true&page=pageaction&action='+action);
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
    collapsible: true,
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
  alert("CLose");
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



		//scrollpane parts
		var scrollPane = $('.scroll-pane');
		var scrollContent = $('.scroll-content');
		
		//build slider
		var scrollbar = $(".scroll-bar").slider({
			slide:function(e, ui){
				if( scrollContent.width() > scrollPane.width() ){ scrollContent.css('margin-left', Math.round( ui.value / 100 * ( scrollPane.width() - scrollContent.width() )) + 'px'); }
				else { scrollContent.css('margin-left', 0); }
			}
		});
		
		//append icon to handle
		var handleHelper = scrollbar.find('.ui-slider-handle')
		.mousedown(function(){
			scrollbar.width( handleHelper.width() );
		})
		.mouseup(function(){
			scrollbar.width( '100%' );
		})
		.append('<span class="ui-icon ui-icon-grip-dotted-vertical"></span>')
		.wrap('<div class="ui-handle-helper-parent"></div>').parent();
		
		//change overflow to hidden now that slider handles the scrolling
		scrollPane.css('overflow','hidden');
		
		//size scrollbar and handle proportionally to scroll distance
		function sizeScrollbar(){
			var remainder = scrollContent.width() - scrollPane.width();
			var proportion = remainder / scrollContent.width();
			var handleSize = scrollPane.width() - (proportion * scrollPane.width());
			scrollbar.find('.ui-slider-handle').css({
				width: handleSize,
				'margin-left': -handleSize/2
			});
			handleHelper.width('').width( scrollbar.width() - handleSize);
		}
		
		//reset slider value based on scroll content position
		function resetValue(){
			var remainder = scrollPane.width() - scrollContent.width();
			var leftVal = scrollContent.css('margin-left') == 'auto' ? 0 : parseInt(scrollContent.css('margin-left'));
			var percentage = Math.round(leftVal / remainder * 100);
			scrollbar.slider("value", percentage);
		}
		//if the slider is 100% and window gets larger, reveal content
		function reflowContent(){
				var showing = scrollContent.width() + parseInt( scrollContent.css('margin-left') );
				var gap = scrollPane.width() - showing;
				if(gap > 0){
					scrollContent.css('margin-left', parseInt( scrollContent.css('margin-left') ) + gap);
				}
		}
		
		//change handle position on window resize
		$(window)
		.resize(function(){
				resetValue();
				sizeScrollbar();
				reflowContent();
		});
		//init scrollbar size
		setTimeout(sizeScrollbar,10);//safari wants a timeout


})
