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


function bark($msg) {
	stdhead($tracker_lang['error']);
	stdmsg($tracker_lang['error'], $msg);
	stdfoot();
	exit;
}

dbconn();

if (!is_valid_id($_POST["id"])) 			stderr($tracker_lang['error'], $tracker_lang['invalid_id']);

$id = (int) $_POST["id"];

loggedinorreturn();

$res = sql_query("SELECT name,owner,images FROM torrents WHERE id = $id");
$row = mysql_fetch_array($res);
if (!$row)
stderr($tracker_lang['error'],"������ �������� �� ����������.");

if (get_user_class() < UC_MODERATOR)
bark("�� �� ���������! ��� ����� ����� ���������?\n");

$rt = (int) $_POST["reasontype"];

if ( $rt < 1 || $rt > 5)
bark("�������� ������� $rt.");

$reason = $_POST["reason"];

if ($rt == 1)
$reasonstr = "�������: 0 ���������, 0 �������� = 0 �����";
elseif ($rt == 2)
$reasonstr = "�������" . ($reason[0] ? (": " . trim($reason[0])) : "!");
elseif ($rt == 3)
$reasonstr = "Nuked" . ($reason[1] ? (": " . trim($reason[1])) : "!");
elseif ($rt == 4)
{
	if (!$reason[2])
	bark("�� �� �������� ���� ������, ������� ���� ������� �������.");
	$reasonstr = "��������� ������: " . trim($reason[2]);
}
else
{
	if (!$reason[3])
	bark("�� �� �������� �������, ������ �������� �������.");
	$reasonstr = trim($reason[3]);
}

deletetorrent($id);

$clearcache = array('block-indextorrents','block-comments','browse-normal','browse-cat');

foreach ($clearcache as $cachevalue)
$CACHE->clearGroupCache($cachevalue);
$CACHE->clearCache('system','cat_tags');

$reasonstr = htmlspecialchars($reasonstr);
write_log("������� $id ($row[name]) ��� ������ ������������� $CURUSER[username] ($reasonstr)\n","torrent");

stdhead("������� ������!");

if (isset($_POST["returnto"]))
$ret = "<a href=\"" . htmlspecialchars($_POST["returnto"]) . "\">�����</a>";
else
$ret = "<a href=\"{$CACHEARRAY['defaultbaseurl']}/\">�� �������</a>";

?>
<h2>������� ������!</h2>
<p><?= $ret ?></p>
<?

stdfoot();

?>