<?php
/*
 * FCKeditor - The text editor for internet
 * Copyright (C) 2003 Frederico Caldeira Knabben
 *
 * Licensed under the terms of the GNU Lesser General Public License
 * (http://www.opensource.org/licenses/lgpl-license.php)
 *
 * For further information go to http://www.fredck.com/FCKeditor/ 
 * or contact fckeditor@fredck.com.
 *
 * test.php: PHP page to test the editor PHP integration.
 *
 * Authors:
 *   Frederico Caldeira Knabben (fckeditor@fredck.com)
 */
include("../fckeditor.php") ;
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN" >
<HTML>
	<HEAD>
		<TITLE>FCKeditor - PHP Test Page</TITLE>
	</HEAD>
	<BODY>
		<form action="testsubmit.php" target="_blank" method="post" language="javascript">
			Normal Text Field<BR>
			<INPUT type="text" name="NormalInput" value="My 'normal' &quot;field&quot; value"><BR>
			<BR>
			Normal Textarea<BR>
			<TEXTAREA name="NormalTextArea" rows="2" cols="20"></TEXTAREA>
			<BR>
			<BR>
			FCKeditor - Default Toolbar Set<BR>
<?php
$oFCKeditor = new FCKeditor ;
$oFCKeditor->Value = 'This is same <B>sample text</B>.' ;
$oFCKeditor->CreateFCKeditor( 'EditorDefault', '100%', 150 ) ;
?>
			<BR>
			FCKeditor - Accessibility Toolbar Set<BR>
<?php
$oFCKeditor = new FCKeditor ;
$oFCKeditor->ToolbarSet = 'Accessibility' ;
$oFCKeditor->Value = 'This is another test. <BR><B>The &quot;Second&quot; row.</B>' ;
$oFCKeditor->CanUpload = false ;	// Overrides fck_config.js default configuration
$oFCKeditor->CanBrowse = false ;	// Overrides fck_config.js default configuration
$oFCKeditor->CreateFCKeditor( 'EditorAccessibility', '80%', 120 ) ;
?>
			<BR>
			<BR>
			FCKeditor - Basic Toolbar Set<BR>
<?php
$oFCKeditor = new FCKeditor ;
$oFCKeditor->ToolbarSet = 'Basic' ;
$oFCKeditor->Value = '<P align="center">Another test.</P>' ;
$oFCKeditor->CreateFCKeditor( 'EditorBasic', 300, 80 ) ;
?>
			<BR>
			<BR>
			<INPUT type="submit" value="Submit Data">
			<BR>
		</form>
	</BODY>
</HTML>
