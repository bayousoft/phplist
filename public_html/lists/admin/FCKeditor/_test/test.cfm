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
 * test.cfm: ColdFusion page to test the editor CondFusion integration.
 *
 * Authors:
 *   Frederico Caldeira Knabben (fckeditor@fredck.com)
-->
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN" >
<HTML>
	<HEAD>
		<TITLE>FCKeditor - ColdFusion Test Page</TITLE>
	</HEAD>
	<BODY>
		<form action="TestSubmit.asp" target="_blank" method="post" language="javascript">
			Normal Text Field<BR>
			<INPUT type="text" name="NormalInput" value="My 'normal' &quot;field&quot; value"><BR>
			<BR>
			Normal Textarea<BR>
			<TEXTAREA name="NormalTextArea" rows="2" cols="20"></TEXTAREA>
			<BR>
			<BR>
			FCKeditor - Default Toolbar Set<BR>
<cfmodule template="../fckeditor.cfm" instanceName="EditorDefault" width="100%" height="150" toolbarSetName="" canUpload="" canBrowse="" initialValue="This is same <B>sample text</B>.">
			<BR>
			FCKeditor - Accessibility Toolbar Set<BR>
<cfmodule template="../fckeditor.cfm" instanceName="EditorAccessibility" width="80%" height="120" toolbarSetName="Accessibility" canUpload="false" canBrowse="false" initialValue="This is another test. <BR><B>The &quot;Second&quot; row.</B>">
			<BR>
			<BR>
			FCKeditor - Basic Toolbar Set<BR>
<cfmodule template="../fckeditor.cfm" instanceName="EditorBasic" width="300" height="80" toolbarSetName="Basic" canUpload="" canBrowse="" initialValue="<P align=""center"">Another test.</P>">
			<BR>
			<BR>
			<INPUT type="submit" value="Submit Data">
			<BR>
		</form>
	</BODY>
</HTML>
