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
 * FredCK.FCKeditor.cs: FCKeditor control class.
 *
 * Authors:
 *   Frederico Caldeira Knabben (fckeditor@fredck.com)
 */

using System;
using System.Web.UI;
using System.Web.UI.WebControls;
using System.ComponentModel;

namespace FredCK
{
	public enum EnablePropertyValues
	{
		Default,
		True,
		False
	}

	[
	DefaultProperty("Value"),
	ValidationProperty("Value"),
	ToolboxData("<{0}:FCKeditor runat=server></{0}:FCKeditor>"),
	ParseChildren(false),
	Designer("FredCK.FCKeditorDesigner")
	]
	public class FCKeditor : System.Web.UI.Control, IPostBackDataHandler
	{
		private static readonly object ValueChangedEvent = new object ();

		[Bindable(true),DefaultValue("")]
		public string Value
		{
			get 
			{
				object o = ViewState["Value"];
				return (o == null) ? String.Empty : (string)o;
			}
			set { ViewState["Value"] = value ; }
		}

		[Bindable(true),DefaultValue("/FCKeditor/")]
		public string BasePath
		{
			get 
			{
				object o = ViewState["BasePath"];
				return (o == null) ? "/FCKeditor/" : (string)o;
			}
			set { ViewState["BasePath"] = value ; }
		}

		[Bindable(true),Category("Appearence"),DefaultValue("Default")]
		public string ToolbarSet
		{
			get 
			{
				object o = ViewState["ToolbarSet"];
				return (o == null) ? "Default" : (string)o;
			}
			set { ViewState["ToolbarSet"] = value ; }
		}

		[Bindable(true),Category("Appearence"),DefaultValue("100%")]
		public Unit Width
		{
			get 
			{
				object o = ViewState["Width"];
				return (o == null) ? Unit.Parse("100%") : (Unit)o ;
			}
			set { ViewState["Width"] = value ; }
		}

		[Bindable(true),Category("Appearence"),DefaultValue("200px")]
		public Unit Height
		{
			get 
			{
				object o = ViewState["Height"];
				return (o == null) ? Unit.Parse("200px") : (Unit)o ;
			}
			set { ViewState["Height"] = value ; }
		}

		[Bindable(true),DefaultValue(EnablePropertyValues.Default)]
		public EnablePropertyValues CanUpload
		{
			get 
			{
				object o = ViewState["CanUpload"];
				return (o == null) ? EnablePropertyValues.Default : (EnablePropertyValues)o ;
			}
			set { ViewState["CanUpload"] = value ; }
		}

		[Bindable(true),DefaultValue(EnablePropertyValues.Default)]
		public EnablePropertyValues CanBrowse
		{
			get 
			{
				object o = ViewState["CanBrowse"];
				return (o == null) ? EnablePropertyValues.Default : (EnablePropertyValues)o ;
			}
			set { ViewState["CanBrowse"] = value ; }
		}

		public event EventHandler ValueChanged
		{
			add    { Events.AddHandler(ValueChangedEvent, value) ; }
			remove { Events.RemoveHandler(ValueChangedEvent, value) ; }
		}

		protected virtual void OnValueChanged(EventArgs e)
		{
			if(Events != null)
			{
				EventHandler oEventHandler = (EventHandler)Events[ValueChangedEvent] ;
				if (oEventHandler != null) oEventHandler(this, e);
			}
		}

		protected override void Render(HtmlTextWriter output)
		{
			System.Web.HttpBrowserCapabilities oBrowser = Page.Request.Browser ;
			// The Editor should work over Internet Explorer 5 and above, under Windows.
			if (oBrowser.Browser == "IE" && oBrowser.MajorVersion >= 5 && oBrowser.Win32)
			{
				string sLink = BasePath + "fckeditor.html?FieldName=" + UniqueID ;

				if (ToolbarSet != "Default" && ToolbarSet != "")
					sLink += "&Toolbar=" + ToolbarSet ;

				if (this.CanUpload != EnablePropertyValues.Default) 
					sLink += "&Upload=" + this.CanUpload.ToString().ToLower() ;

				if (this.CanBrowse != EnablePropertyValues.Default) 
					sLink += "&Browse=" + this.CanBrowse.ToString().ToLower() ;

				output.Write(
					"<IFRAME src=\"{0}\" width=\"{1}\" height=\"{2}\" frameborder=\"no\" scrolling=\"no\"></IFRAME>",
						sLink, 
						Width, 
						Height ) ;

				output.Write(
					"<INPUT type=\"hidden\" name=\"{0}\" value=\"{1}\">",
						UniqueID, 
						System.Web.HttpUtility.HtmlEncode(Value) ) ;
			}
			else
			{
				output.Write(
					"<TEXTAREA name=\"{0}\" rows=\"4\" cols=\"40\" style=\"WIDTH: {1}; HEIGHT: {2}\" wrap=\"virtual\">{3}</TEXTAREA>",
						UniqueID,
						Width,
						Height,
						System.Web.HttpUtility.HtmlEncode(Value) ) ;
			}
		}

		bool IPostBackDataHandler.LoadPostData(string postDataKey, System.Collections.Specialized.NameValueCollection postCollection)
		{
			if (postCollection[postDataKey] != Value)
			{
				Value = postCollection[postDataKey];
				return true;
			}
			return false;
		}

		void IPostBackDataHandler.RaisePostDataChangedEvent()
		{
			OnValueChanged(EventArgs.Empty);
		}
	}
}