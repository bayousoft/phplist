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
 * testold.asp: ASP page to test the editor ASP integration with
 *              ASP version 4 and above (old versions).
 *
 * Authors:
 *   Frederico Caldeira Knabben (fckeditor@fredck.com)
-->
<!-- #INCLUDE file="../fckeditorold.asp" -->
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN" >
<HTML>
	<HEAD>
		<TITLE>FCKeditor - ASP Test Page - Old Versions</TITLE>
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
<%
CreateFCKeditor "EditorDefault", "100%", 150, "", "This is same <B>sample text</B>."
%>
			<BR>
			FCKeditor - Accessibility Toolbar Set<BR>
<%
FCKCanUpload = False
FCKCanBrowse = False
CreateFCKeditor "EditorAccessibility", "80%", 120, "Accessibility", "This is another test. <BR><B>The &quot;Second&quot; row.</B>"
%>
			<BR>
			<BR>
			FCKeditor - Basic Toolbar Set<BR>
<%
FCKCanUpload = Null
FCKCanBrowse = Null
CreateFCKeditor "EditorBasic", 300, 80, "Basic", "<P align=""center"">Another test.</P>"
%>
			<BR>
			<BR>
			<INPUT type="submit" value="Submit Data">
			<BR>
		</form>
	</BODY>
</HTML>
