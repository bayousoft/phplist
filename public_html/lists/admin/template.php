<script language="Javascript" src="js/jslib.js" type="text/javascript"></script>
<?php
require_once "accesscheck.php";

function getTemplateImages($content) {
  $html_images = array();
	$image_types = array(
									'gif'	=> 'image/gif',
									'jpg'	=> 'image/jpeg',
									'jpeg'	=> 'image/jpeg',
									'jpe'	=> 'image/jpeg',
									'bmp'	=> 'image/bmp',
									'png'	=> 'image/png',
									'tif'	=> 'image/tiff',
									'tiff'	=> 'image/tiff',
									'swf'	=> 'application/x-shockwave-flash'
								  );
  // Build the list of image extensions
	while(list($key,) = each($image_types))
		$extensions[] = $key;
  preg_match_all('/"([^"]+\.('.implode('|', $extensions).'))"/Ui', stripslashes($content), $images);
  while (list($key,$val) = each ($images[1]))
    $html_images[$val]++;
  return $html_images;
}

function getTemplateLinks($content) {
  preg_match_all('/href="([^"]+)"/Ui', stripslashes($content), $links);
  return $links[1];
}

if ($action == "addimages") {
  if (!$id)
    $msg = "No such template";
  else {
    $content_req = Sql_Fetch_Row_Query("select template from {$tables["template"]} where id = $id");
    $images = getTemplateImages($content_req[0]);
    if (sizeof($images)) {
      include "class.image.inc";
      $image = new imageUpload();
      while (list($key,$val) = each ($images)) {
       # printf('Image name: <b>%s</b> (%d times used)<br />',$key,$val);
        $image->uploadImage($key,$id);
      }
		  $msg = 'Images stored';
    } else
    	$msg = 'No images found';
  }
  print '<p class="error">'.$msg.'</p>';
  return;
} elseif ($save) {
	$templateok = 1;
  if ($title && ereg("\[CONTENT\]",$content)) {
    $images = getTemplateImages($content);
  	if (($checkfullimages || $checkimagesexist) && sizeof($images)) {
    	foreach ($images as $key => $val) {
      	if (!preg_match("#^https?://#i",$key)) {
        	if ($checkfullimages) {
		      	print "Image $key => not full URL<br/>\n";
	          $templateok = 0;
         	}
        } else {
          if ($checkimagesexist) {
            $fp = @fopen($key,"r");
            if (!$fp) {
              print "Image $key => does not exist<br/>\n";
              $templateok = 0;
            }
            @fclose($fp);
          }
				}
     	}
   	}
    if ($checkfulllinks) {
    	$links = getTemplateLinks($content);
      foreach ($links as $key => $val) {
      	if (!preg_match("#^https?://#i",$val)) {
        	print "Not a full URL: $val<br/>\n";
         	$templateok = 0;
       	}
      }
    }
  } else {
	  if (!$title) print "No Title<br/>";
    else print "Template does not contain the [CONTENT] placeholder<br/>";
  	$templateok = 0;
  }
  if ($templateok) {
    if (!$id) {
      Sql_Query("insert into {$tables["template"]} (title) values(\"Untitled Template\")");
      $id = Sql_Insert_id();
    }
    Sql_Query(sprintf('update %s set title = "%s",template = "%s" where id = %d',
       $tables["template"],$title,addslashes($content),$id));
    Sql_Query(sprintf('select * from %s where filename = "%s" and template = %d',
      $tables["templateimage"],"powerphplist.png",$id));
    if (!Sql_Affected_Rows())
      Sql_Query(sprintf('insert into %s (template,mimetype,filename,data,width,height)
    	values(%d,"%s","%s","%s",%d,%d)',
      $tables["templateimage"],$id,"image/png","powerphplist.png",
      $newpoweredimage,
      70,30));
    print '<p class="error">Template saved</p>';

    if (sizeof($images)) {
      include $GLOBALS["coderoot"] . "class.image.inc";
      $image = new imageUpload();
      print "<h3>Images</h3><p>Below is the list of images used in your template. If an image is currently unavailable, please upload it to the database.</p>";
      print "<p>This includes all images, also fully referenced ones, so you may choose not to upload some. If you upload images, they will be included in the emails that use this template.</p>";
      print formStart('enctype="multipart/form-data"');
      print '<input type=hidden name="id" value="'.$id.'">';
      ksort($images);
      reset($images);
      while (list($key,$val) = each ($images)) {
        printf('Image name: <b>%s</b> (%d times used)<br/>',$key,$val);
        print $image->showInput($key,$value,$id);
      }
      print '<input type=hidden name="id" value="'.$id.'"><input type=hidden name="action" value="addimages"><input type=submit name="addimages" value="Save Images"></form>';
      return;
    } else {
      print "<p>Template does not contain local images</p>";
      return;
    }
  } else {
    print '<p class="error">Some errors were found, template NOT saved!</p>';
    $data["title"] = $title;
    $data["template"] = $content;
  }
} else {
  if ($id) {
    $req = Sql_Query("select * from {$tables["template"]} where id = $id");
    $data = Sql_Fetch_Array($req);
  }
}
?>

<p class="error"><?php echo $msg?></p>
<?php echo PageLink2("templates","List of Templates");?>
<p>
<?php echo formStart()?>
<input type=hidden name="id" value="<?php echo $id?>">
<table>
<tr>
  <td>Title of this template</td>
  <td><input type=text name="title" value="<?php echo stripslashes(htmlspecialchars($data["title"]))?>" size=30></td>
</tr>
<tr>
  <td colspan=2>Content of the template.<br />The content should at least have <b>[CONTENT]</b> somewhere.</td>
</tr>
<tr>
  <td colspan=2><textarea name="content" cols="70" rows="40" wrap="virtual"><?php echo stripslashes(htmlspecialchars($data["template"]))?></textarea></td>
</tr>
<!--tr>
	<td>Make sure all images<br/>start with this URL (optional)</td>
  <td><input type=text name="baseurl" size=40 value="<?php echo htmlspecialchars($baseurl)?>"></td>
</tr-->
<tr>
	<td>Check that all links have a full URL</td>
  <td><input type=checkbox name="checkfulllinks" <?php echo $checkfulllinks?"checked":""?>></td>
</tr>
<tr>
	<td>Check that all images have a full URL</td>
  <td><input type=checkbox name="checkfullimages" <?php echo $checkfullimages?"checked":""?>></td>
</tr>
<tr>
	<td>Check that all external images exist</td>
  <td><input type=checkbox name="checkimagesexist" <?php echo $checkimagesexist?"checked":""?>></td>
</tr>

<tr>
  <td colspan=2><input type=submit name="save" value="Save Changes"></td>
</tr>
</table>
</form>

