<?php

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

$action = $_GET["action"];

dbconn();

loggedinorreturn();
parked();

if ($action == "add")
{
	if ($_SERVER["REQUEST_METHOD"] == "POST")
	{
		if(!is_valid_id($_POST["nid"])) stderr($tracker_lang["error"],$tracker_lang["invalid_id"]);

		$nid = (int) $_POST["nid"];
		$text = trim(($_POST["text"]));
		if (!$text)
		stderr($tracker_lang['error'], $tracker_lang['comment_cant_be_empty']);

		// ANTISPAM AND ANTIFLOOD SYSTEM
		$last_pmres = sql_query("SELECT ".time()."-added AS seconds, text AS msg, id, news AS torrent FROM newscomments WHERE user=".$CURUSER['id']." ORDER BY added DESC LIMIT 4");
		while ($last_pmresrow = mysql_fetch_array($last_pmres)){
			$last_pmrow[] = $last_pmresrow;
			$msgids[] = $last_pmresrow['id'];
			$torids[] = $last_pmresrow['torrent'];
		}
		//   print_r($last_pmrow);
		if ($last_pmrow[0]){
			if (($CACHEARRAY['as_timeout'] > round($last_pmrow[0]['seconds'])) && $CACHEARRAY['as_timeout']) {
				$seconds =  $CACHEARRAY['as_timeout'] - round($last_pmrow[0]['seconds']);
				stderr($tracker_lang['error'],"�� ����� ����� ����� ������ �� �����, ����������, ��������� ������� ����� $seconds ������. <a href=\"javascript: history.go(-1)\">�����</a>");
			}

			if ($CACHEARRAY['as_check_messages'] && ($last_pmrow[0]['msg'] == $text) && ($last_pmrow[1]['msg'] == $text) && ($last_pmrow[2]['msg'] == $text) && ($last_pmrow[3]['msg'] == $text)) {
				$msgview='';
				foreach ($msgids as $key => $msgid){
					$msgview.= "\n<a href=newsoverview.php?id={$torids[$key]}#comm$msgid>����������� ID={$msgid}</a> �� ������������ ".$CURUSER['username'];
				}
				$modcomment = sql_query("SELECT modcomment FROM users WHERE id=".$CURUSER['id']);
				$modcomment = mysql_result($modcomment,0);
				if (strpos($modcomment,"Maybe spammer in news") === false) {
					$arow = sql_query("SELECT id FROM users WHERE class = '".UC_SYSOP."'");

					while (list($admin) = mysql_fetch_array($arow)) {
						sql_query("INSERT INTO messages (poster, sender, receiver, added, msg, subject, location) VALUES(0, 0,
						$admin, '" . time() . "', '������������ <a href=userdetails.php?id=".$CURUSER['id'].">".$CURUSER['username']."</a> ����� ���� ��������, �.�. ��� 5 ��������� ��������� ������������ � �������� ��������� ���������.$msgview', '��������� � �����!', 1)") or sqlerr(__FILE__, __LINE__);
					}
					$modcomment .= "\n".time()." - Maybe spammer in news";
					sql_query("UPDATE users SET modcomment = ".sqlesc($modcomment)." WHERE id =".$CURUSER['id']);

				} else {
					sql_query("UPDATE users SET enabled=0, dis_reason='Spam in news' WHERE id=".$CURUSER['id']);

					$arow = sql_query("SELECT id FROM users WHERE class = '".UC_SYSOP."'");

					while (list($admin) = mysql_fetch_array($arow)) {
						sql_query("INSERT INTO messages (poster, sender, receiver, added, msg, subject, location) VALUES(0, 0,
						$admin, '" . time() . "', '������������ <a href=userdetails.php?id=".$CURUSER['id'].">".$CURUSER['username']."</a> ������� �������� �� ���� � ������������ � ��������, ��� IP ����� (".$CURUSER['ip'].")', '��������� � ����� [���]!', 1)") or sqlerr(__FILE__, __LINE__);
						stderr("�����������!","�� ������� �������� �������� �� ���� � ������������ � ��������! ���� �� �� �������� � �������� �������, <a href=\"contact.php\">������� ������ �������</a>.");
					}
				}
				stderr($tracker_lang['error'],"�� ����� ����� ����� ������ �� �����, ���� 5 ��������� ������������ � �������� ���������. � ������� ����������� ��������. <b><u>��������! ���� �� ��� ��� ����������� ��������� ���������� ���������, �� ������ ������������� ������������� ��������!!!</u></b> <a href=\"javascript: history.go(-1)\">�����</a>");

			}
		}

		// ANITSPAM SYSTEM END

		sql_query("INSERT INTO newscomments (user, news, added, text, ip) VALUES (" .
		$CURUSER["id"] . ",$nid, '" . time() . "', " . sqlesc($text) .
	       "," . sqlesc(getip()) . ")") or die(mysql_error());

		$newid = mysql_insert_id();

		$CACHE->clearGroupCache("block-news");
		//  send_comment_notifs($nid,"<a href=newsoverview.php?id=$nid#comm$newid>{$tracker_lang['news']}</a>",'newscomments');
		header("Location: newsoverview.php?id=$nid#comm$newid");
		die;
	}

	if (!is_valid_id($_GET["nid"]))
	stderr($tracker_lang['error'], $tracker_lang['invalid_id']);
	$nid = (int) $_GET["nid"];

	stdhead("�������� ����������� � �������");

	print("<p><form name=\"comment\" method=\"post\" action=\"newscomment.php?action=add\">\n");
	print("<input type=\"hidden\" name=\"nid\" value=\"$nid\"/>\n");
	?>
<table class="main" border="0" cellspacing="0" cellpadding="3">
	<tr>
		<td class="colhead"><?
		print("".$tracker_lang['add_comment']." � �������");
		?></td>
	</tr>
	<tr>
		<td><?
		print textbbcode("text");
		?></td>
	</tr>
</table>
		<?
		//print("<textarea name=\"text\" rows=\"10\" cols=\"60\"></textarea></p>\n");
		print("<p><input type=\"submit\" value=\"��������\" /></p></form>\n");

		$res = sql_query("SELECT newscomments.id, text, newscomments.ip, newscomments.ratingsum, newscomments.added, username, title, class, users.id as user, users.avatar, users.donor, users.enabled, users.warned, users.parked FROM newscomments LEFT JOIN users ON newscomments.user = users.id WHERE news = $nid ORDER BY comments.id DESC");

		$allrows = array();
		while ($row = mysql_fetch_array($res))
		$allrows[] = $row;

		if (count($allrows)) {
	  print("<h2>��������� �����������, � �������� �������</h2>\n");
	  commenttable($allrows);
		}

		stdfoot();
		die;
}
elseif ($action == "quote")
{
	if (!is_valid_id($_GET["cid"]))
	stderr($tracker_lang['error'], $tracker_lang['invalid_id']);
	$commentid = (int) $_GET["cid"];
	$res = sql_query("SELECT nc.*, n.id AS nid, u.username FROM newscomments AS nc LEFT JOIN news AS n ON nc.news = n.id JOIN users AS u ON nc.user = u.id WHERE nc.id=$commentid") or sqlerr(__FILE__,__LINE__);
	$arr = mysql_fetch_array($res);
	if (!$arr)
	stderr($tracker_lang['error'], $tracker_lang['invalid_id']);

	stdhead("���������� ����������� � �������");

	$text = "{$arr[username]}:<br /><blockquote><cite title=\"$arr[username]\">" . $arr["text"] . "</cite></blockquote><hr />\n";

	print("<form method=\"post\" name=\"comment\" action=\"newscomment.php?action=add\">\n");
	print("<input type=\"hidden\" name=\"nid\" value=\"$arr[nid]\" />\n");
	?>

<table class="main" border="0" cellspacing="0" cellpadding="3">
	<tr>
		<td class="colhead"><?
		print("���������� ����������� � ������");
		?></td>
	</tr>
	<tr>
		<td><?
		print textbbcode("text",$text);
		?></td>
	</tr>
</table>

		<?

		print("<p><input type=\"submit\" value=\"��������\" /></p></form>\n");

		stdfoot();

}
elseif ($action == "edit")
{
	if (!is_valid_id($_GET["cid"]))
	stderr($tracker_lang['error'], $tracker_lang['invalid_id']);
	$commentid = (int) $_GET["cid"];
	$res = sql_query("SELECT nc.*, n.id AS nid FROM newscomments AS nc LEFT JOIN news AS n ON nc.news = n.id WHERE nc.id=$commentid") or sqlerr(__FILE__,__LINE__);
	$arr = mysql_fetch_array($res);
	if (!$arr)
	stderr($tracker_lang['error'], $tracker_lang['invalid_id']);

	if ($arr["user"] != $CURUSER["id"] && get_user_class() < UC_MODERATOR)
	stderr($tracker_lang['error'], $tracker_lang['access_denied']);

	if ($_SERVER["REQUEST_METHOD"] == "POST")
	{
		$text = ((string)$_POST["text"]);
		$returnto = strip_tags($_POST["returnto"]);

		if ($text == "")
		stderr($tracker_lang['error'], $tracker_lang['comment_cant_be_empty']);

		$text = sqlesc($text);

		$editedat = sqlesc(time());

		sql_query("UPDATE newscomments SET text=$text, editedat=$editedat, editedby=$CURUSER[id] WHERE id=$commentid") or sqlerr(__FILE__, __LINE__);

		$CACHE->clearGroupCache("block-news");

		if ($returnto)
		header("Location: $returnto");
		else
		header("Location: {$CACHEARRAY['defaultbaseurl']}/");      // change later ----------------------
		die;
	}

	stdhead("�������������� ����������� � �������");

	print("<form method=\"post\" name=\"comment\" action=\"newscomment.php?action=edit&amp;cid=$commentid\">\n");
	print("<input type=\"hidden\" name=\"returnto\" value=\"newsoverview.php?id={$arr["nid"]}#comm$commentid\" />\n");
	print("<input type=\"hidden\" name=\"cid\" value=\"$commentid\" />\n");
	?>

<table class="main" border="0" cellspacing="0" cellpadding="3">
	<tr>
		<td class="colhead"><?
		print("�������������� ����������� � �������");
		?></td>
	</tr>
	<tr>
		<td><?
		print textbbcode("text",$arr["text"]);
		?></td>
	</tr>
</table>

		<?

		print("<p><input type=\"submit\" value=\"���������������\" /></p></form>\n");

		stdfoot();
		die;
}

elseif ($action == "delete")
{
	if (get_user_class() < UC_MODERATOR)
	stderr($tracker_lang['error'], $tracker_lang['access_denied']);

	if (!is_valid_id($_GET["cid"]))
	stderr($tracker_lang['error'], $tracker_lang['invalid_id']);
	$commentid = (int) $_GET["cid"];


	$res = sql_query("SELECT news FROM newscomments WHERE id=$commentid")  or sqlerr(__FILE__,__LINE__);
	$arr = mysql_fetch_array($res);
	if ($arr)
	$nid = $arr["news"];
	else
	stderr($tracker_lang['error'], $tracker_lang['invalid_id']);

	sql_query("DELETE FROM newscomments WHERE id=$commentid") or sqlerr(__FILE__,__LINE__);

	$CACHE->clearGroupCache("block-news");

	list($commentid) = mysql_fetch_row(sql_query("SELECT id FROM newscomments WHERE news = $nid ORDER BY added DESC LIMIT 1"));

	$returnto = "newsoverview.php?id=$nid#comm$commentid";

	if ($returnto)
	header("Location: $returnto");
	else
	header("Location: {$CACHEARRAY['defaultbaseurl']}/");      // change later ----------------------
	die;
}
else
stderr($tracker_lang['error'], "Unknown action");

die;
?>