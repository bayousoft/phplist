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
 * FredCK.FCKeditorDesigner.cs: FCKeditor control designer class.
 *
 * Authors:
 *   Frederico Caldeira Knabben (fckeditor@fredck.com)
 */

using System;

namespace FredCK
{
	public class FCKeditorDesigner : System.Web.UI.Design.ControlDesigner
	{
		public FCKeditorDesigner()
		{
		}

		public override string GetDesignTimeHtml() 
		{
			FCKeditor oControl = (FCKeditor)Component ;
			return String.Format(
				"<TABLE width=\"{0}\" height=\"{1}\" bgcolor=\"#f5f5f5\" bordercolor=\"#c7c7c7\" cellpadding=\"0\" cellspacing=\"0\" border=\"1\"><TR><TD valign=\"middle\" align=\"center\">FCKeditor - <B>{2}</B></TD></TR></TABLE>",
				oControl.Width,
				oControl.Height,
				oControl.ID ) ;
		}
	}
}
