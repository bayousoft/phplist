<%@ Register TagPrefix="FredCK" Namespace="FredCK" Assembly="FredCK.FCKeditor" %>
<%@ Page language="c#" AutoEventWireup="false" %>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN" >
<HTML>
  <HEAD>
		<title>FCKeditor - ASP.NET Test</title>
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
 * test.aspx: ASPX page to test the editor ASP.NET integration.
 *   This page uses the ASP.NET web user control 
 *   "FredCK.FCKeditor.dll". You can found it at
 *   http://www.fredck.com/FCKeditor/ 
 *
 * Authors:
 *   Frederico Caldeira Knabben (fckeditor@fredck.com)
--> 
		<meta name="CODE_LANGUAGE" Content="C#">
		<LINK href="../css/fck_editorarea.css" type="text/css" rel="stylesheet">
  </HEAD>
	<body>
		<form id="Test" method="post" runat="server">
			<FredCK:FCKeditor id="FCKeditor1" height="300" runat="server" Value='<P align="center">This is just a <B>test</B>.</P>' BasePath="../"></FredCK:FCKeditor><BR>
			<BR>
			<INPUT type="submit" value="Submit Data">
			<BR>
			<BR>
			<HR width="100%" SIZE="1">
			<%=FCKeditor1.Value%>
		</form>
	</body>
</HTML>
