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
 * fckeditor.asp: ASP pages integration. It works with old versions
 *                of VBScript (4 and bellow).
 *                For newer VBScript versions (5 and above), see fckeditor.asp.
 *
 * Authors:
 *   Frederico Caldeira Knabben (fckeditor@fredck.com)
-->
<%
' The editor base path
' You have to update it with you web site configuration
Dim FCKeditorBasePath
FCKeditorBasePath = "/FCKeditor/"

Dim FCKCanUpload, FCKCanBrowse

Function CreateFCKeditor(instanceName, width, height, toolbarSetName, initialValue)
		If FCK_IsCompatible() Then
			Dim sLink
			sLink = FCKeditorBasePath & "fckeditor.html?FieldName=" & instanceName
			
			If (toolbarSetName & "") <> "" Then
				sLink = sLink & "&Toolbar=" & toolbarSetName
			End If
			
			If FCKCanUpload & "" <> "" Then
				If FCKCanUpload = True Then
					sLink = sLink & "&Upload=true"
				ElseIf FCKCanUpload = False Then
					sLink = sLink & "&Upload=false"
				End If
			End If
			
			If FCKCanBrowse & "" <> "" Then
				If FCKCanBrowse = True Then
					sLink = sLink & "&Browse=true"
				ElseIf FCKCanBrowse = False Then
					sLink = sLink & "&Browse=false"
				End If
			End If

			Response.Write "<IFRAME src=""" & sLink & """ width=""" & width & """ height=""" & height & """ frameborder=""no"" scrolling=""no""></IFRAME>"
			Response.Write "<INPUT type=""hidden"" name=""" & instanceName & """ value=""" & Server.HTMLEncode( initialValue ) & """>"
		Else
			Response.Write "<TEXTAREA name=""" & instanceName & """ rows=""4"" cols=""40"" style=""WIDTH: " & width & "; HEIGHT: " & height & """ wrap=""virtual"">" & Server.HTMLEncode( initialValue ) & "</TEXTAREA>"
		End If
End Function
	
Function FCK_IsCompatible()
		Dim sAgent
		sAgent = Request.ServerVariables("HTTP_USER_AGENT") 

		If InStr(sAgent, "MSIE") > 0 AND InStr(sAgent, "Windows") > 0  AND InStr(sAgent, "Opera") <= 0 Then
			Dim iVersion
			iVersion = CInt(Mid(sAgent, InStr(sAgent, "MSIE") + 5, 1)) 
			FCK_IsCompatible = (iVersion >= 5)
		Else
			FCK_IsCompatible = False
		End If
End Function
%>