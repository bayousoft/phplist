// Progressbar - Version 2.0
// Author: Brian Gosselin of http://scriptasylum.com
// Featured on Dynamic Drive (http://www.dynamicdrive.com)
// adapted for PHPlist by Michiel Dethmers http://www.phplist.com

var yposition=100;                       //POSITION OF LOAD BAR FROM TOP OF WINDOW, IN PIXELS
var loadedcolor='black' ;                // PROGRESS BAR COLOR
var unloadedcolor='white';               // BGCOLOR OF UNLOADED AREA
var barheight=30;                        // HEIGHT OF PROGRESS BAR IN PIXELS (MIN 25)
var barwidth=500;                        // WIDTH OF THE BAR IN PIXELS  
var bordercolor='grey';                  // COLOR OF THE BORDER
var text = 'Processing, please wait ...';
var total = 100;
var done = false;

//DO NOT EDIT BEYOND THIS POINT 
var NS4 = (navigator.appName.indexOf("Netscape")>=0 && parseFloat(navigator.appVersion) >= 4 && parseFloat(navigator.appVersion) < 5)? true : false;
var IE4 = (document.all)? true : false;
var NS6 = (parseFloat(navigator.appVersion) >= 5 && navigator.appName.indexOf("Netscape")>=0 )? true: false;
var imagesdone=false;
var blocksize=barwidth/total;
barheight=Math.max(barheight,25);
var loaded=0, perouter, perdone, stopped;

var txt=(NS4)?'<layer name="perouter" bgcolor="'+bordercolor+'" visibility="hide">' : '<div id="perouter" style="position:absolute; visibility:hidden; background-color:'+bordercolor+'">';
txt+='<table cellpadding="0" cellspacing="1" border="0"><tr><td width="'+barwidth+'" height="'+barheight+'" valign="center">';
if(NS4)txt+='<ilayer width="100%" height="100%"><layer width="100%" height="100%" bgcolor="'+unloadedcolor+'" top="0" left="0">';
txt+='<table cellpadding="0" cellspacing="0" border="0"><tr><td valign="center" width="'+barwidth+'" height="'+barheight+'" bgcolor="'+unloadedcolor+'"><center><font color="'+loadedcolor+'" size="1" face="sans-serif">'+text+'</font></center></td></tr></table>';
if(NS4) txt+='</layer>';
txt+=(NS4)? '<layer name="perdone" width="100%" height="'+barheight+'" bgcolor="'+loadedcolor+'" top="0" left="0">' : '<div id="perdone" style="position:absolute; top:1px; left:1px; width:'+barwidth+'px; height:'+barheight+'px; background-color:'+loadedcolor+'; z-index:100">';
txt+='<table cellpadding="0" cellspacing="0" border="0"><tr><td valign="center" width="'+barwidth+'" height="'+barheight+'" bgcolor="'+loadedcolor+'"><center><font color="'+unloadedcolor+'" size="1" face="sans-serif">'+text+'</font></center></td></tr></table>';
txt+=(NS4)? '</layer></ilayer>' : '</div>';
txt+='</td></tr></table>';
txt+=(NS4)?'</layer>' : '</div>';


var progressmeter = txt;


function start(){
  if(NS4){
    perouter=document.perouter;
    perdone=document.perouter.document.layers[0].document.perdone;
  }
  if(NS6){
    perouter=document.getElementById('perouter');
    perdone=document.getElementById('perdone');
  } else if(document.all){
    perouter=document.all.perouter;
    perdone=document.all.perdone;
  }
  cliplayer(perdone,0,0,barheight,0);
  window.onresize=setouterpos;
  setouterpos();
  stopped = false;
  increase();
}

function stop() {
  stopped = true;
}

function setouterpos(){
  var ww=(IE4)? document.body.clientWidth : window.innerWidth;
  var x=(ww-barwidth)/2;
  if(NS4){
    perouter.moveTo(x,yposition);
    perouter.visibility="show";
  }
  if(IE4||NS6){
    perouter.style.left=x+'px';
    perouter.style.top=yposition+'px';
    perouter.style.visibility="visible";
  }
}

function increase() {
  loaded++;
  dispbars();
  if (!stopped)
    setTimeout('increase()', 100);
}

function dispbars(){
  cliplayer(perdone, 0, blocksize*loaded, barheight, 0);
  if (loaded > total)
    // setTimeOut("hidebar()",100);
    loaded = 0;
}

function reload() {
 // reload the page without parameters
  document.location = document.location;
}

function finish() {
  hidebar();
}

function hidebar(){
 (NS4)? perouter.visibility="hide" : perouter.style.visibility="hidden";
  done=true;
}

function cliplayer(layer, ct, cr, cb, cl){
  if(NS4){
    layer.clip.left=cl;
    layer.clip.top=ct;
    layer.clip.right=cr;
    layer.clip.bottom=cb;
  }
  if(IE4||NS6)layer.style.clip='rect('+ct+' '+cr+' '+cb+' '+cl+')';
}
