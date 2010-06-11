<?php

## check url
$request_parameters = array(
  'timeout' => 10,
  'allowRedirects' => 1,
  'method' => 'HEAD',
);
$headreq = new HTTP_Request($_GET['url'],$request_parameters);
$headreq->addHeader('User-Agent', 'phplist v'.VERSION.' (http://www.phplist.com)');
if (!PEAR::isError($headreq->sendRequest(false))) {
  $code = $headreq->getResponseCode();
  if ($code != 200) {
    $status = $GLOBALS['I18N']->get('Error fetching URL');
    return;
  }
} else {
  $status = $GLOBALS['I18N']->get('Error fetching URL');
  return;
}

$status = '<span class="pass">'.$GLOBALS['I18N']->get('URL is valid').'</span>';
