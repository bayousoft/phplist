<?php

class phplistTest {

  var $name = 'Default test';
  var $purpose = 'Test to be extended to test all kinds of things';
  var $userdata = array();

  function phplistTest() {
    $this->userdata = Sql_Fetch_Assoc_Query(sprintf('select * from %s where email = "%s"',$GLOBALS['tables']['user'],$GLOBALS['developer_email']));
    if (!$this->userdata['id']) {
      print "Bounce user needs to exist: ".$GLOBALS['developer_email'].'<br/>';
      return 0;
    }
    $GLOBALS['message_envelope'] = $GLOBALS['developer_email'];
    return 1;
  }

  function runtest() {
    

    return 1;
  }
   
}
?>
