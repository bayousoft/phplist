<!---
 * FCKeditor - The text editor for internet
 * Copyright (C) 2003 Frederico Caldeira Knabben
 *
 * Licensed under the terms of the GNU Lesser General Public License
 * (http://www.opensource.org/licenses/lgpl-license.php)
 *
 * For further information go to http://www.fredck.com/FCKeditor/ 
 * or contact fckeditor@fredck.com.
 *
 * fckeditor.cfm: ColdFusion integrator. Notes this module is created 
 *                for use with Coldfusion 5 and above.
 *                Syntax: <cfmodule template="fckeditor.cfm" instanceName="" width="" height="" toolbarSetName="" initialValue="" FCKeditorBasePath="">
 *                For usage samples see test.cfm in _test.
 *
 * Authors:
 *   John Watson (john@themedialounge.net)
--->

<!--- this is the default parameter for the editor directory --->
<cfparam name="attributes.FCKeditorBasePath" default="/fckeditor/">

<!--- pull parameters from users module tag --->
<cfparam name="instanceName" default="#attributes.instanceName#">
<cfparam name="width" default="#attributes.width#">
<cfparam name="height" default="#attributes.height#">
<cfparam name="toolbarSetName" default="#attributes.toolbarSetName#">
<cfparam name="initialValue" default="#attributes.initialValue#">
<cfparam name="canUpload" default="#attributes.canUpload#">
<cfparam name="canBrowse" default="#attributes.canBrowse#">

<!--- if the user adds this to the module then it overides the previous cfparam --->
<cfparam name="FCKeditorBasePath" default="#attributes.FCKeditorBasePath#">

<!--- check the user_agent --->
<cfif (findnocase("MSIE", http_user_agent) gt 0) AND (findnocase("Windows", http_user_agent) gt 0) AND (findnocase("Opera", http_user_agent) lte 0)>
		<Cfset iVersion = Mid(http_user_agent, findnocase( "MSIE", http_user_agent) + 5, 1)>
		<Cfset FCK_IsCompatible =(iVersion GTE 5)>
<cfelse>
		<Cfset FCK_IsCompatible =False>
</cfif>

<!--- if FCK_IsCompatible is YES then create editor ---->
<cfoutput>		
	<cfif FCK_IsCompatible>
			<Cfset sLink = "#FCKeditorBasePath#fckeditor.html?FieldName=#instanceName#">
			
			<cfif toolbarSetName neq "">
				<Cfset sLink = "#sLink#&Toolbar=#toolbarSetName#">
			</cfif>
			
			<cfif canUpload neq "">
				<Cfset sLink = "#sLink#&Upload=#canUpload#">
			</cfif>

			<cfif canBrowse neq "">
				<Cfset sLink = "#sLink#&Browse=#canBrowse#">
			</cfif>

			<IFRAME id="iframe_#instanceName#" src="#sLink#" width="#width#" height="#height#" frameborder="no" scrolling="no"></IFRAME>
			<INPUT type="hidden" name="#instanceName#" value="#HTMLEditFormat(initialValue)#">
	<cfelse>
			<TEXTAREA name="#instanceName#" rows="4" cols="40" style="WIDTH: #width#; HEIGHT: #height#" wrap="virtual">#HTMLEditFormat(initialValue)#</TEXTAREA>		
	</cfif>
</cfoutput>

