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

require "include/bittorrent.php";
dbconn();
loggedinorreturn();
    $max_image_width = $avatar_max_width;
    $max_image_height = $avatar_max_height;
    $maxfilesize = 60 * 1024;

$allowed_types = array(
"image/gif" => "gif",
"image/jpeg" => "jpg",
"image/jpg" => "jpg",
"image/png" => "png"
// Add more types here if you like
);
    
if(empty($_FILES['avatar']['tmp_name'])) {
stdhead("�������� �������");
print '<table width="100%" border="0" cellpadding="0" cellspacing="0"><tr><td class="block" width="100%" align="center" valign="middle" ><strong>�������� �������</strong></td></tr></table>';
print "<br><form method=post enctype=multipart/form-data><div class=\"form-row\"><div class=\"field-widget\"><label for=\"avatar\">�������� �������</label> : <input type=file name=avatar title=\"�������� ��������\"></div></div>
<input type=submit value=��������� ></form><br><br><center><font color=green>���������: ������� ������ ���� �������� �� ������ ".round($maxfilesize/1024,2)." ��������<br>� p������� �� ������ ".$max_image_width."�".$max_image_height." ��������</font></center> ";
stdfoot();
}
else {
    $size = @GetImageSize($_FILES['avatar']['tmp_name']);
//    var_dump($size);
if (!$size)
stderr($tracker_lang["error"],"��� �� ��������, ������ ��������");
    // Is valid filetype?
elseif (!array_key_exists($_FILES['avatar']['type'], $allowed_types))
        stderr($tracker_lang["error"],"��������! ����������� ������� ��������: JPG,PNG,GIF.");

elseif (!preg_match('/^(.+)\.(jpg|png|gif)$/si', $_FILES['avatar']['name']))
        stderr($tracker_lang["error"],"�������� ��� ����� (�� �������� ��� �������� ������).");

elseif (($size[0] > $max_image_width ) || ($size[1] > $max_image_height))
stderr ($tracker_lang["error"],"<br>������ ������ ������� ".$size[0]."�".$size[1]." ��������� ������ �� ����� ".$max_image_width."�".$max_image_height."  ��������</font></b>");
elseif ($_FILES['avatar']['size'] > $maxfilesize) {
stderr($tracker_lang["error"],"<br>������ ����� ������� ��������� ".round($maxfilesize/1024,2)." ��������!</font></b>");
}else
{
@unlink("./".$CURUSER['avatar']);
copy($_FILES['avatar']['tmp_name'],"./avatars/".$CURUSER["id"].substr($_FILES['avatar']['name'], strripos($_FILES['avatar']['name'], '.')));
$pathav = "avatars/".$CURUSER["id"].substr($_FILES['avatar']['name'], strripos($_FILES['avatar']['name'], '.'));
sql_query("UPDATE users SET avatar = '".$pathav."' WHERE id = " . $CURUSER["id"])or sqlerr(__FILE__,__LINE__);
/*sql_query("UPDATE ipb_member_extra SET avatar_type = 'url' WHERE id = " . $CURUSER["id"])or sqlerr(__FILE__,__LINE__);
sql_query("UPDATE ipb_member_extra SET avatar_size = '".$size[0]."x".$size[1]."' WHERE id = " . $CURUSER["id"])or sqlerr(__FILE__,__LINE__);
sql_query("UPDATE ipb_member_extra SET avatar_location = '".$pathav."' WHERE id = " . $CURUSER["id"])or sqlerr(__FILE__,__LINE__);
*/
stdmsg("Okay!","<b>���� ������� ���� ������� �������� �� ������!</b><hr>�������� �����: <b>".$CURUSER["id"].substr($_FILES['avatar']['name'], strripos($_FILES['avatar']['name'], '.'))."</b><br>������ �����: <b>".round($_FILES['avatar']['size']/1024,2)." ��.</b><hr><center>������ ������������� �������� � ������� ������������</b></center>");// ��� �� ��������, ��� � �� <a href=\"".$DEFAULTBASEURL."/forums/\">������</a></b></center> ";

}
}
?>