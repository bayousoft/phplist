<%
'###
 ' FCKeditor - The text editor for internet
 ' Copyright (C) 2003 Frederico Caldeira Knabben
 '
 ' Licensed under the terms of the GNU Lesser General Public License
 ' (http://www.opensource.org/licenses/lgpl-license.php)
 '
 ' For further information go to http://www.fredck.com/FCKeditor/ 
 ' or contact fckeditor@fredck.com.
 '
 ' fckeditor.asp: ASP pages integration. It works with VBScript 5 (IIS5).
 '                For old VBScript versions, see fckeditorold.asp.
 '
 ' Authors:
 '   Frederico Caldeira Knabben (fckeditor@fredck.com)
'###

' The editor base path
' You have to update it with you web site configuration
Dim FCKeditorBasePath
FCKeditorBasePath = "/FCKeditor/"

Class FCKeditor
	Private sToolbarSetName
	Private sInitialValue
	Private bCanUpload
	Private bCanBrowse
	
	Public Property Let ToolbarSet(toolbarSetName)
		sToolbarSetName = toolbarSetName
	End Property

	Public Property Let Value(initialValue)
		sInitialValue = initialValue
	End Property
	
	Public Property Let CanUpload(canUploadValue)
		bCanUpload = canUploadValue
	End Property
	
	Public Property Let CanBrowse(canBrowseValue)
		bCanBrowse = canBrowseValue
	End Property
	
	Public Function CreateFCKeditor(instanceName, width, height)
		If IsCompatible() Then
			Dim sLink
			sLink = FCKeditorBasePath & "fckeditor.html?FieldName=" & instanceName
			
			If (sToolbarSetName & "") <> "" Then
				sLink = sLink & "&Toolbar=" & sToolbarSetName
			End If

			If bCanUpload & "" <> "" Then
				If bCanUpload = True Then
					sLink = sLink & "&Upload=true"
				ElseIf bCanUpload = False Then
					sLink = sLink & "&Upload=false"
				End If
			End If
			
			If bCanBrowse & "" <> "" Then
				If bCanBrowse = True Then
					sLink = sLink & "&Browse=true"
				ElseIf bCanBrowse = False Then
					sLink = sLink & "&Browse=false"
				End If
			End If
			
			Response.Write "<IFRAME src=""" & sLink & """ width=""" & width & """ height=""" & height & """ frameborder=""no"" scrolling=""no""></IFRAME>"
			Response.Write "<INPUT type=""hidden"" name=""" & instanceName & """ value=""" & Server.HTMLEncode( sInitialValue ) & """>"
		Else
			Response.Write "<TEXTAREA name=""" & instanceName & """ rows=""4"" cols=""40"" style=""WIDTH: " & width & "; HEIGHT: " & height & """ wrap=""virtual"">" & Server.HTMLEncode( sInitialValue ) & "</TEXTAREA>"
		End If
	End Function
	
	Private Function IsCompatible()
		Dim sAgent
		sAgent = Request.ServerVariables("HTTP_USER_AGENT") 

		If InStr(sAgent, "MSIE") > 0 AND InStr(sAgent, "Windows") > 0  AND InStr(sAgent, "Opera") <= 0 Then
			Dim iVersion
			iVersion = CInt(Mid(sAgent, InStr(sAgent, "MSIE") + 5, 1)) 
			IsCompatible = (iVersion >= 5)
		Else
			IsCompatible = False
		End If
	End Function
End Class
%>