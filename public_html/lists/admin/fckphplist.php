<?php
require_once "accesscheck.php";

if ($_GET["action"] == "js") {
	ob_end_clean();
	$req = Sql_query("select name from {$tables["attribute"]} where type in ('textline','select') order by listorder");
	$attnames = ';preferences url;unsubscribe url';
	$attcodes = ';[PREFERENCES];[UNSUBSCRIBE]';
	while ($row = Sql_Fetch_Row($req)) {
		$attnames .= ';'.strtolower(substr($row[0],0,15));
		$attcodes .= ';['.strtoupper($row[0]).']';
	}
	
  $imgdir = getenv("DOCUMENT_ROOT").$GLOBALS["pageroot"].'/'.FCKIMAGES_DIR.'/';
  $enable_image_upload = is_dir($imgdir) && is_writeable ($imgdir) ? 'true':'false';

	$smileypath = $_SERVER["DOCUMENT_ROOT"].$GLOBALS["pageroot"].'/images/smiley';
	$smileyextensions = array('gif');
	$smileys = '';
	if ($dir = opendir($smileypath)) {
		while (false !== ($file = readdir($dir)))
		{
			list($fname,$ext) = explode(".",$file);
			if (in_array($ext,$smileyextensions)) {
				$smileys .= '"'.$file.'",';
			}
		}
	} 
	$smileys = substr($smileys,0,-1);

?>
oTB_Items.Attribute			= new TBCombo( "Attributes"			, "doAttribute(this)"		, 'Attribute'		, '<?php echo $attnames?>', '<?=$attcodes?>') ;

function doAttribute(combo)
{
	if (combo.value != null && combo.value != "")
		insertHtml(combo.value);
	SetFocus();
}

config.BasePath = document.location.protocol + '//' + document.location.host + 
	document.location.pathname.substring(0,document.location.pathname.lastIndexOf('/')+1) ;
config.EditorAreaCSS = config.BasePath + 'css/fck_editorarea.css' ;
config.BaseUrl = document.location.protocol + '//' + document.location.host + '/' ;
	config.EnableXHTML = false ;
config.StartupShowBorders = false ;
config.StartupShowDetails = false ;
config.ForcePasteAsPlainText	= false ;
config.AutoDetectPasteFromWord	= true ;
config.UseBROnCarriageReturn	= true ;
config.TabSpaces = 4 ;
config.AutoDetectLanguage = true ;
config.DefaultLanguage    = "en" ;
config.SpellCheckerDownloadUrl = "http://www.rochen.com/ieSpellSetup201325.exe" ;
config.ToolbarImagesPath = config.BasePath + "images/toolbar/" ;
config.ToolbarSets["Default"] = [
	['EditSource','-','Cut','Copy','Paste','PasteText','PasteWord','-','SpellCheck','Find','-','Undo','Redo','-','SelectAll','RemoveFormat','-','Link','RemoveLink','-','Image','Table','Rule','SpecialChar','Smiley','-','About'] ,
	['Bold','Italic','Underline','StrikeThrough','-','Subscript','Superscript','-','JustifyLeft','JustifyCenter','JustifyRight','JustifyFull','-','InsertOrderedList','InsertUnorderedList','-','Outdent','Indent','-','ShowTableBorders','ShowDetails','-','Zoom'] ,
	['Attribute','-','FontFormat','-','Font','-','FontSize','-','TextColor','BGColor']
] ;

//	['FontStyle','-','FontFormat','-','Font','-','Attribute','-','FontSize','-','TextColor','BGColor']
config.StyleNames  = ';Main Header;Blue Title;Centered Title' ;
config.StyleValues = ';MainHeader;BlueTitle;CenteredTitle' ;
config.ToolbarFontNames = ';Arial;Comic Sans MS;Courier New;Tahoma;Times New Roman;Verdana' ;
config.LinkShowTargets = true ;
config.LinkTargets = '_blank;_parent;_self;_top' ;
config.LinkDefaultTarget = '_blank' ;
config.ImageBrowser = <?php echo $enable_image_upload?> ;
config.ImageBrowserURL = config.BasePath + "../?page=fckphplist&action=browseimage" ;
config.ImageBrowserWindowWidth  = 600 ;
config.ImageBrowserWindowHeight = 480 ;

config.ImageUpload = <?php echo $enable_image_upload?> ;
// Page that effectivelly upload the image.
config.ImageUploadURL = config.BasePath + "../?page=fckphplist&action=uploadimage" ;
config.ImageUploadWindowWidth	= 600 ;
config.ImageUploadWindowHeight	= 480 ;
config.ImageUploadAllowedExtensions = ".gif .jpg .jpeg .png" ;

config.LinkBrowser = false ;
config.LinkBrowserURL = config.BasePath + "../?page=fckphplist&action=browsefile" ;
config.LinkBrowserWindowWidth	= 400 ;
config.LinkBrowserWindowHeight	= 250 ;

config.LinkUpload = false ;
config.LinkUploadURL = config.BasePath + "../?page=fckphplist&action=uploadfile" ;

//config.SmileyPath	= config.BasePath + "images/smiley/fun/" ;
config.SmileyPath = document.location.protocol + '//' + document.location.host +'<?php echo $GLOBALS["pageroot"].'/images/smiley/'?>'

config.SmileyImages	= [<?php echo $smileys?>] ;
config.SmileyColumns = 8 ;
config.SmileyWindowWidth	= 800 ;
config.SmileyWindowHeight	= 600 ;

<?php exit;
} elseif ($_GET["action"] == "browseimage") {
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
 * browse.php: Browse function.
 *
 * Authors:
 *   Frederic TYNDIUK (http://www.ftls.org/ - tyndiuk[at]ftls.org)
 */

// Init var :

	$IMAGES_BASE_URL = 'http://'.$_SERVER["SERVER_NAME"].$GLOBALS["pageroot"].'/'.FCKIMAGES_DIR.'/';
	$IMAGES_BASE_DIR = getenv("DOCUMENT_ROOT").$GLOBALS["pageroot"].'/'.FCKIMAGES_DIR.'/';

// End int var

// Thanks : php dot net at phor dot net
function walk_dir($path) {
	if ($dir = opendir($path)) {
		while (false !== ($file = readdir($dir)))
		{
			if ($file[0]==".") continue;
			if (is_dir($path."/".$file))
				$retval = array_merge($retval,walk_dir($path."/".$file));
			else if (is_file($path."/".$file))
				$retval[]=$path."/".$file;
			}
		closedir($dir);
		}
	return $retval;
}

function CheckImgExt($filename) {
	$img_exts = array("gif","jpg", "jpeg","png");
	foreach($img_exts as $this_ext) {
		if (preg_match("/\.$this_ext$/", $filename)) {
			return TRUE;
		}
	}
	return FALSE;
}
$files = array();
foreach (walk_dir($IMAGES_BASE_DIR) as $file) {
	$file = preg_replace("#//+#", '/', $file);
	$IMAGES_BASE_DIR = preg_replace("#//+#", '/', $IMAGES_BASE_DIR);
	$file = preg_replace("#$IMAGES_BASE_DIR#", '', $file);
	if (CheckImgExt($file)) {
		$files[] = $file;	//adding filenames to array
	}
}

sort($files);	//sorting array

// generating $html_img_lst
foreach ($files as $file) {
	$html_img_lst .= "<a href=\"javascript:getImage('$file');\">$file</a><br>\n";
}
ob_end_clean();
?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN" >
<HTML>
	<HEAD>
		<TITLE>Image Browser</TITLE>
		<LINK rel="stylesheet" type="text/css" href="./FCKeditor/css/fck_dialog.css">
		<SCRIPT language="javascript">
var sImagesPath  = "<?php echo $IMAGES_BASE_URL; ?>";
var sActiveImage = "" ;

function getImage(imageName)
{
	sActiveImage = sImagesPath + imageName ;
	imgPreview.src = sActiveImage ;
}

function ok()
{	
	window.setImage(sActiveImage) ;
	window.close() ;
}
		</SCRIPT>
	</HEAD>
	<BODY bottommargin="5" leftmargin="5" topmargin="5" rightmargin="5">
<TABLE cellspacing="1" cellpadding="1" border="0" width="100%" class="dlg" height="100%">
	<TR height="100%">
		<TD>
			<TABLE cellspacing="0" cellpadding="0" width="100%" border="0" height="100%">
				<TR>
					<TD width="45%" valign="top">
						<table cellpadding="0" cellspacing="0" height="100%" width="100%">
							<tr>
								<td width="100%">File : </td>
							</tr>
							<tr height="100%">
								<td>
									<DIV class="ImagePreviewArea"><?php echo $html_img_lst ?></DIV>
								</td>
							</tr>
						</table>
					</TD>
					<TD width="10%" >&nbsp;&nbsp;&nbsp;</TD>
					<TD>
						<table cellpadding="0" cellspacing="0" height="100%" width="100%">
							<tr>
								<td width="100%">Preview : </td>
							</tr>
							<tr>
								<td height="100%" align="center" valign="middle">
									<DIV class="ImagePreviewArea"><IMG id="imgPreview" border=1"></DIV>
								</td>
							</tr>
						</table>
					</TD>
				</TR>
			</TABLE>
		</TD>
	</TR>
	<TR>
		<TD align="center">
			<INPUT style="WIDTH: 80px" type="button" value="OK"     onclick="ok();"> &nbsp;&nbsp;&nbsp;&nbsp;
			<INPUT style="WIDTH: 80px" type="button" value="Cancel" onclick="window.close();"><BR>
		</TD>
	</TR>
</TABLE>
	</BODY>
</HTML>
<?php
exit;
} elseif ($_GET["action"] == "uploadimage") {
//	ob_end_clean();

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
 * upload.php: Basic file upload manager for the editor. You have
 *   to have set a directory called "userimages" in the root folder
 *   of your web site.
 *
 * Authors:
 *   Frederic TYNDIUK (http://www.ftls.org/ - tyndiuk[at]ftls.org)
 */

// Init var :

	$UPLOAD_BASE_URL = 'http://'.$_SERVER["SERVER_NAME"].$GLOBALS["pageroot"].'/'.FCKIMAGES_DIR.'/';
	$UPLOAD_BASE_DIR = getenv("DOCUMENT_ROOT").$GLOBALS["pageroot"].'/'.FCKIMAGES_DIR.'/';


// End int var

?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN" >
<HTML>
	<HEAD>
		<TITLE>File Uploader</TITLE>
		<LINK rel="stylesheet" type="text/css" href="./FCKeditor/css/fck_dialog.css">
	</HEAD>
	<BODY><form>
		<TABLE eight="100%" width="100%">
			<TR>
				<TD align=center valign=middle><B>
					Upload in progress...
<font color='red'><BR><BR>
<?php 

if (file_exists($UPLOAD_BASE_DIR.$HTTP_POST_FILES['FCKeditor_File']['name'])) {
	echo "Error : File ".$HTTP_POST_FILES['FCKeditor_File']['name']." exists, can't overwrite it...";
	echo '<BR><BR><INPUT type="button" value=" Cancel " onclick="window.close()">';
} else {
	if (is_uploaded_file($HTTP_POST_FILES['FCKeditor_File']['tmp_name'])) {
		$savefile = $UPLOAD_BASE_DIR.$HTTP_POST_FILES['FCKeditor_File']['name'];

		if (move_uploaded_file($HTTP_POST_FILES['FCKeditor_File']['tmp_name'], $savefile)) {
			chmod($savefile, 0666);
			?>
		<SCRIPT language=javascript>window.opener.setImage('<?php echo $UPLOAD_BASE_URL.$HTTP_POST_FILES['FCKeditor_File']['name']; ?>') ; window.close();</SCRIPT>";
		<?php
		}
	} else {
		echo "Error : ";
		switch($HTTP_POST_FILES['FCKeditor_File']['error']) {
			case 0: //no error; possible file attack!
				echo "There was a problem with your upload.";
				break;
			case 1: //uploaded file exceeds the upload_max_filesize directive in php.ini
				echo "The file you are trying to upload is too big.";
				break;
			case 2: //uploaded file exceeds the MAX_FILE_SIZE directive that was specified in the html form
				echo "The file you are trying to upload is too big.";
				break;
			case 3: //uploaded file was only partially uploaded
				echo "The file you are trying upload was only partially uploaded.";
				break;
			case 4: //no file was uploaded
				echo "You must select an image for upload.";
				break;
			default: //a default error, just in case!  :)
				echo "There was a problem with your upload.";
				break;
		}
	}
	echo '<BR><BR><INPUT type="button" value=" Cancel " onclick="window.close()">';
} ?>
				</font></B></TD>
			</TR>
		</TABLE>
	</form></BODY>
</HTML>
<?php
//exit;
} elseif ($_GET["action"]) {
	print "Sorry, ".$_GET["action"]." has not been implemented yet";
}
?>
