In the message field you can use "variables", which will be replaced by the appropriate value for a user:
<br />The variables need to be in the form <b>[NAME]</b> where NAME can be replaced with the name of one of your attributes.
<br />For example if you have an attribute "First Name" put [FIRST NAME] in the message somewhere to identify the location where the "First Name" value of the recipient needs to be inserted.
</p><p>Currently you have the following attributes defined:
<table border=1><tr><td><b>Attribute</b></td><td><b>Placeholder</b></td></tr>
<?php
$req = Sql_query("select name from {$tables["attribute"]} order by listorder");
while ($row = Sql_Fetch_Row($req))
  if (strlen($row[0]) < 20)
    printf ('<tr><td>%s</td><td>[%s]</td></tr>',$row[0],strtoupper($row[0]));

print '</table>';
if (ENABLE_RSS) {
?>
	<p>You can set up templates for messages that go out with RSS items. In order to do that use the "Prepare Message" page and indicate
  the frequency for the message. The message will then be used to send the list of items to users
  on the lists, who have that frequency set. You need to use the placeholder [RSS] in your message
  to identify where the list needs to go.</p>
<?php }
?>
