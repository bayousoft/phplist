<!--
 * FCKeditor - The text editor for internet
 * Copyright (C) 2003 Frederico Caldeira Knabben
 *
 * Licensed under the terms of the GNU Lesser General Public License
 * (http://www.opensource.org/licenses/lgpl-license.php)
 *
 * For further information go to http://www.fredck.com/FCKeditor/ 
 * or contact fckeditor@fredck.com.
 *
 * testsubmit.asp: Lists the submited data from a form POST.
 *
 * Authors:
 *   Jim Michaels (jmichae3@yahoo.com)
-->
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN" >
<HTML>
	<HEAD>
		<TITLE>FCKeditor - Posted Data</TITLE>
		<STYLE TYPE="text/css">
			body, td 
			{
				FONT-SIZE: 12px; 
				FONT-FAMILY: Arial, Verdana, Sans-Serif;
			}
		</STYLE>
	</HEAD>
	<BODY>
		<table width="100%" border="1" cellspacing="0" bordercolor="#999999">
			<tr style="FONT-WEIGHT: bold; COLOR: #dddddd; BACKGROUND-COLOR: #999999">
				<td>Field</td>
				<td>Value</td>
			</tr>
			<?php
			foreach($HTTP_POST_VARS as $sForm => $value) {
			?>
			<tr>
				<td valign="top" nowrap><b><?=$sForm?></b></td>
				<td width="100%"><?=htmlspecialchars($HTTP_POST_VARS[$sForm])?></td>
			</tr>
			<?php
			}
			?>
		</table>
	</BODY>
</HTML>
