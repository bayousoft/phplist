<?php

$id = sprintf('%d',$_GET['id']);
if (!$id) {
  return '';
}
$message = Sql_Fetch_Assoc_Query(sprintf('select *,embargo - now() as timetowait from %s where id = %d',$GLOBALS['tables']['message'],$id));
$messagedata = loadMessageData($id);

$totalsent = $messagedata['astext'] + 
  $messagedata['ashtml'] + 
  $messagedata['astextandhtml'] + 
  $messagedata['aspdf'] + 
  $messagedata['astextandpdf'];
  
if (isset($messagedata['to process'])) {
 $num_users = $messagedata['to process'];
}

$sent = $totaltime = $sampletime = $samplesent = 0;
if (!isset($messagedata['sampletime'])) { ## take a "sample" of the send speed, to calculate msg/hr
  $sampletime = time();
  $samplesent = $totalsent;
  setMessageData($id,'sampletime',$sampletime);
  setMessageData($id,'samplesent',$samplesent);
} else {
  $totaltime = time() - $messagedata['sampletime'];
  $sent = $totalsent - $messagedata['samplesent'];
  if ($totaltime > 600) { ## refresh speed sampling every 5 minutes
    setMessageData($id,'sampletime',time());
    setMessageData($id,'samplesent',$totalsent);
  }
}

if ($sent > 0 && $totaltime > 0) {
  $msgperhour = (3600/$totaltime) * $sent;
  $secpermsg = $totaltime / $sent;
  $timeleft = ($num_users - $sent) * $secpermsg;
  $eta = date('D j M H:i',time()+$timeleft);
} else {
  $msgperhour = 0;
  $secpermsg = 0;
  $timeleft = 0;
  $eta = $GLOBALS['I18N']->get('unknown');
}
setMessageData($id,'ETA',$eta);
setMessageData($id,'msg/hr',$msgperhour);

if ($message['status'] != 'inprocess') {
  $html = $GLOBALS['I18N']->get($message['status']);
  
  if ($message['timetowait'] > 0) {
    $timetowait = secs2time($message['timetowait']);
    $html .= '<br/>'.sprintf($GLOBALS['I18N']->get('%s left before embargo'),$timetowait);
  }
  
  if ($message['status'] != 'submitted') {
    $html .= '<br/>'.PageLink2("messages",$GLOBALS['I18N']->get("requeue"),"resend=".$message["id"]);
  }
  if (!empty($messagedata['to process'])) {
    $html .= '<br/>'.$messagedata['to process'].' '.$GLOBALS['I18N']->get('still to process').'<br/>'.
    $GLOBALS['I18N']->get('sent').': '.$totalsent;
  }
} else {
  if (empty($messagedata['last msg sent'])) $messagedata['last msg sent'] = 0;
  if (empty($messagedata['to process'])) $messagedata['to process'] = $GLOBALS['I18N']->get('Unknown');
  
  $active = time() - $messagedata['last msg sent'];
  $html = $GLOBALS['I18N']->get($message['status']).'<br/>'.
  $messagedata['to process'].' '.$GLOBALS['I18N']->get('still to process').'<br/>';
  ## not sure this calculation is accurate
#  $html .= $GLOBALS['I18N']->get('sent').': '.$totalsent.'<br/>';
  if ($active > 120) {
    $html .= $GLOBALS['I18N']->get('Stalled');
  } else {
    $html .= 
    $GLOBALS['I18N']->get('ETA').': '.$eta.'<br/>'.
    $GLOBALS['I18N']->get('Processing').' '.sprintf('%d',$msgperhour).' '.$GLOBALS['I18N']->get('msgs/hr');
  }
}

if (!empty($GLOBALS['developer_email'])) {
  if (isset($messagedata['sampletime'])) $html .= '<br/>ST: '.$messagedata['sampletime'];
  if (isset($messagedata['samplesent'])) $html .= '<br/>SS: '.$messagedata['samplesent'];
  if (isset($totaltime)) $html .= '<br/>TT: '.$totaltime;
  if (isset($sent)) $html .= '<br/>TS: '.$sent;
}

$status = $html;
#exit;
?>
