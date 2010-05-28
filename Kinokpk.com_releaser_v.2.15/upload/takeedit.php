<?

/*
Project: Kinokpk.com releaser
This file is part of Kinokpk.com releaser.
Kinokpk.com releaser is based on TBDev,
originally by RedBeard of TorrentBits, extensively modified by
Gartenzwerg and Yuna Scatari.
Kinokpk.com releaser is free software;
you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation; either version 2 of the License, or
(at your option) any later version.
Kinokpk.com is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.
You should have received a copy of the GNU General Public License
along with Kinokpk.com releaser; if not, write to the
Free Software Foundation, Inc., 59 Temple Place, Suite 330, Boston,
MA  02111-1307  USA
Do not remove above lines!
*/

require_once("include/bittorrent.php");
require_once("include/benc.php");

function bark($msg) {
	stderr("������", $msg);
}

////////////////////////////////////////////////
function uploadimage($x, $imgname, $tid) {

	$maxfilesize = 512000; // 500kb

	$allowed_types = array(
"image/gif" => "gif",
"image/pjpeg" => "jpg",
"image/jpeg" => "jpg",
"image/jpg" => "jpg",
"image/png" => "png"
	// Add more types here if you like
	);

	if (!($_FILES[image.$x]['name'] == "")) {

		if ($imgname != "") {
			// Deleting images
            $img = "torrents/images/$imgname";
           @unlink($img);
    // Deleting thumbnails
$delimgarray = glob("cache/thumbnail/{$tid}[0-1][a-z]*.*");
           if ($delimgarray)
		       foreach ($delimgarray as $img) {
    @unlink($img);
}
		}

		$y = $x + 1;

		// Is valid filetype?
		if (!array_key_exists($_FILES[image.$x]['type'], $allowed_types))
			bark("Invalid file type! Image $y");

		if (!preg_match('/^(.+)\.(jpg|png|gif)$/si', $_FILES[image.$x]['name']))
        bark("�������� ��� ����� (�� �������� ��� �������� ������).");

		// Is within allowed filesize?
		if ($_FILES[image.$x]['size'] > $maxfilesize)
			bark("Invalid file size! Image $y - Must be less than 500kb");

		// Where to upload?
		// Make sure is same as on takeupload.php
		$uploaddir = ROOT_PATH."torrents/images/";

		// What is the temporary file name?
		$ifile = $_FILES[image.$x]['tmp_name'];

        $size = @GetImageSize($ifile);
          //    var_dump($size);
    if (!$size)
    bark("��� �� ��������, ������ ��������");
    
		// By what filename should the tracker associate the image with?
		$ifilename = $tid . $x . substr($_FILES[image.$x]['name'], strlen($_FILES[image.$x]['name'])-4, 4);
			
		// Upload the file
		$copy = copy($ifile, $uploaddir.$ifilename);

		if (!$copy)
			bark("Error occured uploading image! - Image $y");

         //adds watermark ////////////////////////////////////////////////////
/// ORIGINAL POSTED http://www.codenet.ru/webmast/php/Image-Resize-GD/ /////////////////

$ifn=$uploaddir.$ifilename;


// �������� jpeg �� ���������
if (!isset($q)) $q = 75;

// ������ �������� ����������� �� ������
// ��������� ����� � ����������� ��� �������
if (($_FILES[image.$x]['type'] == "image/pjpeg") || ($_FILES[image.$x]['type'] == "image/jpeg") || ($_FILES[image.$x]['type'] == "image/jpg"))
$src = @imagecreatefromjpeg($ifn);
elseif ($_FILES[image.$x]['type'] == "image/gif")
$src = @imagecreatefromgif($ifn);
elseif ($_FILES[image.$x]['type'] == "image/png")
$src = @imagecreatefrompng($ifn);

$w_dest = $size[0];
$h_dest = $size[1];

       // ������ ������ ��������
       // ����� ������ truecolor!, ����� ����� ����� 8-������ ���������
       $dest = imagecreatetruecolor($w_dest,$h_dest);
       imagecopyresampled($dest, $src, 0, 0, 0, 0, $w_dest, $h_dest, $w_dest, $h_dest);

 // ���������� ���������� ������ ������
        $str = "Kinokpk.com releaser";
        $size = 2; // ������ ������
        $x_text = $w_dest-imagefontwidth($size)*strlen($str)-3;
        $y_text = $h_dest-imagefontheight($size)-3;

        // ���������� ����� ������ �� ����� ���� �������� �����
        $white = imagecolorallocate($dest, 255, 255, 255);
        $black = imagecolorallocate($dest, 0, 0, 0);
        $gray = imagecolorallocate($dest, 127, 127, 127);
        if (imagecolorat($dest,$x_text,$y_text)>$gray) $color = $black;
        if (imagecolorat($dest,$x_text,$y_text)<$gray) $color = $white;

        // ������� �����
        imagestring($dest, $size, $x_text-1, $y_text-1, $str,$white-$color);
        imagestring($dest, $size, $x_text+1, $y_text+1, $str,$white-$color);
        imagestring($dest, $size, $x_text+1, $y_text-1, $str,$white-$color);
        imagestring($dest, $size, $x_text-1, $y_text+1, $str,$white-$color);

        imagestring($dest, $size, $x_text-1, $y_text,   $str,$white-$color);
        imagestring($dest, $size, $x_text+1, $y_text,   $str,$white-$color);
        imagestring($dest, $size, $x_text,   $y_text-1, $str,$white-$color);
        imagestring($dest, $size, $x_text,   $y_text+1, $str,$white-$color);

        imagestring($dest, $size, $x_text,   $y_text,   $str,$color);

        if (($_FILES[image.$x]['type'] == "image/pjpeg") || ($_FILES[image.$x]['type'] == "image/jpeg") || ($_FILES[image.$x]['type'] == "image/jpg"))
        imagejpeg($dest,$ifn,$q);
elseif ($_FILES[image.$x]['type'] == "image/gif")
        imagegif($dest,$ifn,$q);
elseif ($_FILES[image.$x]['type'] == "image/png")
        imagepng($dest,$ifn,9,PNG_ALL_FILTERS);

        imagedestroy($dest);
        imagedestroy($src);

////////////////RESIZING END //////////////////////////////////////

		return $ifilename;

	}

}
////////////////////////////////////////////////

function dict_check($d, $s) {
	if ($d["type"] != "dictionary")
		bark("not a dictionary");
	$a = explode(":", $s);
	$dd = $d["value"];
	$ret = array();
	foreach ($a as $k) {
		unset($t);
		if (preg_match('/^(.*)\((.*)\)$/', $k, $m)) {
			$k = $m[1];
			$t = $m[2];
		}
		if (!isset($dd[$k]))
			bark("dictionary is missing key(s)");
		if (isset($t)) {
			if ($dd[$k]["type"] != $t)
				bark("invalid entry in dictionary");
			$ret[] = $dd[$k]["value"];
		}
		else
			$ret[] = $dd[$k];
	}
	return $ret;
}

function dict_get($d, $k, $t) {
	if ($d["type"] != "dictionary")
		bark("not a dictionary");
	$dd = $d["value"];
	if (!isset($dd[$k]))
		return;
	$v = $dd[$k];
	if ($v["type"] != $t)
		bark("invalid dictionary entry type");
	return $v["value"];
}

if (!is_valid_id($_POST['id'])) stderr($tracker_lang["error"],$tracker_lang["invalid_id"]);
$id = 0 + $_POST['id'];

if (!mkglobal("name:type"))
	bark("missing form data");



dbconn();

loggedinorreturn();

$res = sql_query("SELECT torrents.owner, torrents.filename, torrents.save_as, torrents.image1, torrents.image2, torrents.topic_id, categories.name AS catname FROM torrents LEFT JOIN categories ON torrents.category = categories.id WHERE torrents.id = $id");
$row = mysql_fetch_array($res);
if (!$row)
 stderr($tracker_lang["error"],$tracker_lang["invalid_id"]);
 
if (($row["filename"] == 'nofile') && (get_user_class() == UC_UPLOADER)) $tedit = 1; else $tedit = 0;

if ($CURUSER["id"] != $row["owner"] && get_user_class() < UC_MODERATOR && !$tedit)
	bark("You're not the owner! How did that happen?\n");

$updateset = array();

if (($_POST['nofile'] == 'yes') && (empty($_POST['nofilesize']))) bark("�� �� ������� ������ �� ������� ������!");

if ($_POST['nofile'] == 'yes') {$fname = 'nofile'; } else {
$fname = $row["filename"];
preg_match('/^(.+)\.torrent$/si', $fname, $matches);
$shortfname = $matches[1];
$dname = $row["save_as"];
}

// picturemod
$img1action = $_POST['img1action'];
if ($img1action == "update")
	$updateset[] = "image1 = " .sqlesc(uploadimage(0, $row['image1'], $id));
if ($img1action == "delete") {
	if ($row['image1']) {
		$del = @unlink("torrents/images/$row[image1]");
		       foreach (glob("cache/thumbnail/{$id}[0-1][a-z]*.*") as $img) {
    @unlink($img);
}
       		$updateset[] = "image1 = ''";
	}
}

$img2action = $_POST['img2action'];
if ($img2action == "update")
	$updateset[] = "image2 = " .sqlesc(uploadimage(1, $row['image2'], $id));
if ($img2action == "delete") {
	if ($row['image2']) {
		$del = @unlink("torrents/images/$row[image2]");
		       foreach (glob("cache/thumbnail/{$id}[0-1][a-z]*.*") as $img) {
    @unlink($img);
}
         		$updateset[] = "image2 = ''";
	}
}
// picturemod
	if ($_POST['nofile'] == 'yes') {} else {
if (isset($_FILES["tfile"]) && !empty($_FILES["tfile"]["name"]))
	$update_torrent = true;

// check tags
if (!$_POST['tags']) stderr($tracker_lang['error'],"�� �� ������� �� ������ ����");

if ($update_torrent) {

	$f = $_FILES["tfile"];
	$fname = unesc($f["name"]);

	if (empty($fname))
		bark("���� �� ��������. ������ ��� �����!");
	if (!validfilename($fname))
		bark("�������� ��� �����!");
	if (!preg_match('/^(.+)\.torrent$/si', $fname, $matches))
		bark("�������� ��� ����� (�� .torrent).");
	$tmpname = $f["tmp_name"];
	if (!is_uploaded_file($tmpname))
		bark("eek");
	if (!filesize($tmpname))
		bark("������ ����!");
	$dict = bdec_file($tmpname, $CACHEARRAY['max_torrent_size']);
	if (!isset($dict))
		bark("��� �� ����� �� ����������? ��� �� �������-����������� ����!");
	list($info) = dict_check($dict, "info");
	list($dname, $plen, $pieces) = dict_check($info, "name(string):piece length(integer):pieces(string)");
	if (strlen($pieces) % 20 != 0)
		bark("invalid pieces");

	$filelist = array();
	$totallen = dict_get($info, "length", "integer");
	if (isset($totallen)) {
		$filelist[] = array($dname, $totallen);
		$torrent_type = "single";
	} else {
		$flist = dict_get($info, "files", "list");
		if (!isset($flist))
			bark("missing both length and files");
		if (!count($flist))
			bark("no files");
		$totallen = 0;
		foreach ($flist as $fn) {
			list($ll, $ff) = dict_check($fn, "length(integer):path(list)");
			$totallen += $ll;
			$ffa = array();
			foreach ($ff as $ffe) {
				if ($ffe["type"] != "string")
					bark("filename error");
				$ffa[] = $ffe["value"];
			}
			if (!count($ffa))
				bark("filename error");
			$ffe = implode("/", $ffa);
			$filelist[] = array($ffe, $ll);
		if ($ffe == 'Thumbs.db')
	        {
	            stderr("������", "� ��������� ��������� ������� ����� Thumbs.db!");
	            die;
	        }
		}
		$torrent_type = "multi";
	}

  $dict['value']['announce']=bdec(benc_str(''));  // change announce url to local
//	$dict['value']['info']['value']['private']=bdec('i1e');  // add private tracker flag
	$dict['value']['info']['value']['source']=bdec(benc_str( "[{$CACHEARRAY['defaultbaseurl']}] {$CACHEARRAY['sitename']}")); // add link for bitcomet users
	unset($dict['value']['announce-list']); // remove multi-tracker capability
	unset($dict['value']['nodes']); // remove cached peers (Bitcomet & Azareus)
	unset($dict['value']['info']['value']['crc32']); // remove crc32
	unset($dict['value']['info']['value']['ed2k']); // remove ed2k
	unset($dict['value']['info']['value']['md5sum']); // remove md5sum
	unset($dict['value']['info']['value']['sha1']); // remove sha1
	unset($dict['value']['info']['value']['tiger']); // remove tiger
	unset($dict['value']['azureus_properties']); // remove azureus properties
	$dict=bdec(benc($dict)); // double up on the becoding solves the occassional misgenerated infohash
	$dict['value']['comment']=bdec(benc_str( "{$CACHEARRAY['defaultbaseurl']}/details.php?id=$id&hit=1")); // change torrent comment
	$dict['value']['created by']=bdec(benc_str( "$CURUSER[username]")); // change created by
	$dict['value']['publisher']=bdec(benc_str( "$CURUSER[username]")); // change publisher
	$dict['value']['publisher.utf-8']=bdec(benc_str( "$CURUSER[username]")); // change publisher.utf-8
	$dict['value']['publisher-url']=bdec(benc_str( "{$CACHEARRAY['defaultbaseurl']}/userdetails.php?id=$CURUSER[id]")); // change publisher-url
	$dict['value']['publisher-url.utf-8']=bdec(benc_str( "{$CACHEARRAY['defaultbaseurl']}/userdetails.php?id=$CURUSER[id]")); // change publisher-url.utf-8
	list($info) = dict_check($dict, "info");

	$infohash = sha1($info["string"]);
	move_uploaded_file($tmpname, "torrents/$id.torrent");

	$fp = fopen("torrents/$id.torrent", "w");
	if ($fp) {
	    @fwrite($fp, benc($dict), strlen(benc($dict)));
	    fclose($fp);
	    @chmod($fp, 0644);
	}

	$updateset[] = "info_hash = " . sqlesc($infohash);
	$updateset[] = "filename = " . sqlesc($fname);
	$updatesetp[] = "save_as = " . sqlesc($dname);
	@sql_query("DELETE FROM files WHERE torrent = $id");
	$nf = count($filelist);

		sql_query("INSERT INTO files (torrent, filename, size) VALUES ($id, ".sqlesc($dname).",".$totallen.")");
		sql_query("UPDATE torrents SET size = ".$totallen." WHERE id = ".$id);
		sql_query("UPDATE torrents SET numfiles = ".$nf." WHERE id = ".$id);
		sql_query("UPDATE torrents SET type = '".$torrent_type."' WHERE id = ".$id);
		if ($_POST['nofile'] == 'yes') $dname = 'nofile';
		sql_query("UPDATE torrents SET save_as = '".$dname."' WHERE id =".$id);

}
// ����� �� ��������
}
$name = htmlspecialchars($name);

ksort($_POST['tags']);
reset($_POST['tags']);

$tags = $_POST["tags"];
$oldtags = $_POST['oldtags'];
$un = @array_diff($tags, explode(",", $oldtags));
$un2 = @array_diff(explode(",", $oldtags),$tags);

if ($un)
foreach ($un as $tag) {
		@sql_query("UPDATE tags SET howmuch=howmuch+1 WHERE name LIKE ".sqlesc($tag)) or sqlerr(__FILE__, __LINE__);
	}
if ($un2)
foreach ($un2 as $tag) {
		@sql_query("UPDATE tags SET howmuch=howmuch-1 WHERE name LIKE ".sqlesc($tag)) or sqlerr(__FILE__, __LINE__);
	}

$updateset[] = "name = " . sqlesc($name);
if ($tags)
$updateset[] = "tags = " . sqlesc(implode(",",$tags));
$updateset[] = "search_text = " . sqlesc(htmlspecialchars("$shortfname $dname $torrent"));
if (!is_valid_id($_POST['type'])) stderr($tracker_lang["error"],$tracker_lang["invalid_id"]);
$updateset[] = "category = " . (0 + $_POST['type']);

if ($_POST['nofile'] == 'yes') {

 $wastor = sql_query("SELECT filename FROM torrents WHERE id =".$id);
 $wastor = mysql_result($wastor,0);

 if ($wastor != 'nofile') {
   sql_query("DELETE FROM files WHERE torrent = ".$id);
   sql_query("DELETE FROM peers WHERE torrent = ".$id);
   sql_query("DELETE FROM snatched WHERE torrent = ".$id);
   sql_query("UPDATE torrents SET leechers = 0, seeders = 0");
   $updateset[] = "filename = 'nofile'";
   $updateset[] = "save_as = 'nofile'";
   			$ff = "torrents/" . strval($id).".torrent";
			@unlink($ff);
 }

  $nfz = $_POST['nofilesize'];
  $nofilesize = 0+($nfz*1024*1024);
  $updateset[] = "size = " . $nofilesize;
}

if (get_user_class() >= UC_ADMINISTRATOR) {
	if ($_POST["banned"]) {
		$updateset[] = "banned = 'yes'";
		$_POST["visible"] = 0;
	} else
		$updateset[] = "banned = 'no'";
	if ($_POST["sticky"] == "yes")
	        $updateset[] = "sticky = 'yes'";
	    else
	        $updateset[] = "sticky = 'no'";
}
if(get_user_class() >= UC_ADMINISTRATOR)
       $updateset[] = "free = '".($_POST["free"]==1 ? 'yes' : 'no')."'";

$updateset[] = "visible = '" . ($_POST["visible"] ? "yes" : "no") . "'";

$updateset[] = "moderated = 'yes'";
$updateset[] = "moderatedby = ".sqlesc($CURUSER["id"]);

sql_query("UPDATE torrents SET " . join(",", $updateset) . " WHERE id = $id");
 if ($_POST['upd'] == 'yes') sql_query("UPDATE torrents SET added = '" . get_date_time() . "' WHERE id = $id");

   if (!defined("CACHE_REQUIRED")){
 	require_once(ROOT_PATH . 'classes/cache/cache.class.php');
	require_once(ROOT_PATH .  'classes/cache/fileCacheDriver.class.php');
	define("CACHE_REQUIRED",1);
  }
  		$cache=new Cache();
		$cache->addDriver('file', new FileCacheDriver());

  $cache->clearGroupCache("block-indextorrents");

 foreach($_POST['val'] as $descrid => $content) {
     sql_query("UPDATE descr_torrents SET value = ".sqlesc($content)." WHERE id =".$descrid);
 }

			if ($CACHEARRAY['use_integration']) {
/// IPB INTEGRATION ///// EDIT WIKI CONTAINER ////////////
$image = $row['image1'];

if ($image <> '') $image = "<div align=\"center\"><a href=\"{$CACHEARRAY['defaultbaseurl']}/viewimage.php?image=".$image."\"><img alt=\"������ ��� ������ (�������� ��� ��������� ������� �����������)\" src=\"{$CACHEARRAY['defaultbaseurl']}/thumbnail.php?image=".$image."&for=forum\" border=\"0\" class=\"linked-image\" /></a></div><br />"; else
$image = "<div align=\"center\"><img src=\"{$CACHEARRAY['defaultbaseurl']}/pic/noimage.gif\" border=\"0\" class=\"linked-image\" /></div><br />";

  if (!empty($_POST['topic'])) {
    if (is_valid_id($_POST['topic'])) {
      $topicid =  $_POST['topic'];
      $topicid = 0 + $topicid;
      sql_query("UPDATE torrents SET topic_id =".$topicid." WHERE id =".$id);
      $topicedit = 1;
    } else stderr($tracker_lang["error"],$tracker_lang["invalid_id"]);
    }  else {
$topicid = $row['topic_id'];
}


if ($topicid <> 0) {

$cid = 0 + $_POST['type'];

$forumdesc = $image;
$forumdesc .= "<table width=\"100%\" border=\"1\"><tr><td valign=\"top\"><b>��� (����):</b></td><td>".implode(", ",$_POST['tags'])."</td></tr><tr><td><b>��������:</b></td><td>$name</td></tr>";
$detid = sql_query("SELECT descr_torrents.value, descr_details.name, descr_details.input FROM descr_torrents LEFT JOIN descr_details ON descr_details.id = descr_torrents.typeid WHERE descr_torrents.torrent = ".$id." AND descr_torrents.value <> '' ORDER BY descr_details.sort ASC");
while ($did = mysql_fetch_array($detid))  {
  if ($did['input'] == 'bbcode')
  $forumdesc .= "<tr><td valign=\"top\"><b>".$did['name'].":</b></td><td>".format_comment($did['value'])."</td></tr>";
  else
  $forumdesc .= "<tr><td valign=\"top\"><b>".$did['name'].":</b></td><td>".format_comment($did['value'])."</td></tr>";
}

$isnofilesize = sql_query("SELECT filename,size FROM torrents WHERE id = $id");
$isnofilesize = mysql_fetch_array($isnofilesize);
$topicfooter = "<tr><td valign=\"top\"><b>������ �����:</b></td><td>".round($isnofilesize['size']/1024/1024)." ��</td></tr>";

  $topicfooter .= "<tr><td valign=\"top\"><b>".(($isnofilesize['filename'] != 'nofile')?"������� {$CACHEARRAY['defaultbaseurl']}:":"����� {$CACHEARRAY['defaultbaseurl']}:")."</b></td><td><div align=\"center\">[<span style=\"color:#FF0000\"><a href=\"{$CACHEARRAY['defaultbaseurl']}/details.php?id=".$id."&hit=1\">���������� ���� ����� �� {$CACHEARRAY['defaultbaseurl']}</a></span>]</div></td></tr></table>";

$forumdesc .= $topicfooter;

// connecting to IPB DB
forumconn();
//connection opened

$postid = sql_query("SELECT topic_firstpost FROM ".$fprefix."topics WHERE tid=".$topicid);
$postid = mysql_result($postid,0);


if ($CACHEARRAY['exporttype'] == "wiki")
sql_query("UPDATE ".$fprefix."posts SET wiki = ".sqlesc($forumdesc).", post = '---' WHERE pid=".$postid);
else
sql_query("UPDATE ".$fprefix."posts SET post = ".sqlesc($forumdesc)." WHERE pid=".$postid);

if ($topicedit) {
  $cutplus = strpos($name,"+");
  if ($cutplus === false)
  $topicname = $name;
  else $topicname = substr($name,0,$cutplus);
  if (!empty($_POST['source'])) $dsql = ", description = ".sqlesc($_POST['source']); else $dsql = '';
  $topic = sql_query("UPDATE ".$fprefix."topics SET title = ".sqlesc($topicname).$dsql." WHERE tid =".$topicid);

}


 // closing IPB DB connection
relconn();
 // connection closed

}
//////////////////////END/////////////////////////////////////
}

write_log("������� '$name' ��� �������������� ������������� $CURUSER[username]\n","F25B61","torrent");

$returl = "details.php?id=$id";
if (isset($_POST["returnto"]))
	$returl .= "&returnto=" . urlencode($_POST["returnto"]);

header("Refresh: 0; url=$returl");

?>