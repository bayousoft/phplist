<?include "pagetop.php"?>
	<title>Upgrade from 1.1.x to 1.2.x</title>
<? 
require_once "accesscheck.php";

require "config.php";
include "header.inc";
include "structure.php";

if (TEST)
  Fatal_Error("You didn't edit config.php did you?");
#if (STRUCTUREVERSION != VERSION)
#  Fatal_Error("Structure is not the right version");

Sql_Verbose_Query("alter table user_attribute change column value value varchar(255)");
Sql_Verbose_Query("alter ignore table user change column email email varchar(255) not null unique");
Sql_Verbose_Query("alter table attribute change column type type varchar(30)");
include "footer.inc";
?>

