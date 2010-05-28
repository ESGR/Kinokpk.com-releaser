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

if ($action == "add") {
	if ($_SERVER["REQUEST_METHOD"] == "POST") {
		$reqid = (int) $_POST["tid"];
		if (!is_valid_id($reqid))
		stderr($tracker_lang['error'], $tracker_lang['invalid_id']);
		$res = sql_query("SELECT request, userid FROM requests WHERE id = $reqid") or sqlerr(__FILE__,__LINE__);
		$arr = mysql_fetch_array($res);
		if (!$arr)
		stderr($tracker_lang['error'], $tracker_lang['invalid_id']);
		$name = $arr[0];
		$text = trim(((string)$_POST["text"]));
		if (!$text)
		stderr($tracker_lang['error'], $tracker_lang['no_fields_blank']);

		// ANTISPAM AND ANTIFLOOD SYSTEM
		$last_pmres = sql_query("SELECT ".time()."-added AS seconds, text AS msg, id, request AS torrent FROM reqcomments WHERE user=".$CURUSER['id']." ORDER BY added DESC LIMIT 4");
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
					$msgview.= "\n<a href=requests.php?id={$torids[$key]}#comm$msgid>����������� ID={$msgid}</a> �� ������������ ".$CURUSER['username'];
				}
				$modcomment = sql_query("SELECT modcomment FROM users WHERE id=".$CURUSER['id']);
				$modcomment = mysql_result($modcomment,0);
				if (strpos($modcomment,"Maybe spammer in requests comments") === false) {
					$arow = sql_query("SELECT id FROM users WHERE class = '".UC_SYSOP."'");

					while (list($admin) = mysql_fetch_array($arow)) {
						sql_query("INSERT INTO messages (poster, sender, receiver, added, msg, subject, location) VALUES(0, 0,
						$admin, '" . time() . "', '������������ <a href=userdetails.php?id=".$CURUSER['id'].">".$CURUSER['username']."</a> ����� ���� ��������, �.�. ��� 5 ��������� ��������� ������������ � �������� ��������� ���������.$msgview', '��������� � �����!', 1)") or sqlerr(__FILE__, __LINE__);
					}
					$modcomment .= "\n".time()." - Maybe spammer in requests comments";
					sql_query("UPDATE users SET modcomment = ".sqlesc($modcomment)." WHERE id =".$CURUSER['id']);

				} else {
					sql_query("UPDATE users SET enabled=0, dis_reason='Spam in requests comments' WHERE id=".$CURUSER['id']);

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

		sql_query("INSERT INTO reqcomments (user, request, added, text, ip) VALUES (" .
		$CURUSER["id"] . ",$reqid, '" . date("Y-m-d H:i:s", time()) . "', " . sqlesc($text) .
                "," . sqlesc(getip()) . ")");
		$newid = mysql_insert_id();
		sql_query("UPDATE requests SET comments = comments + 1 WHERE id = $reqid");

		$CACHE->clearGroupCache("block-req");

		//     send_comment_notifs($reqid,"<a href=requests.php?id=$reqid#comm$newid>".$name."</a>",'reqcomments');
		/////////////////�������� �� ����������/////////////////
		header("Location: requests.php?id=$reqid#comm$newid");

		exit();
	}

	if (!is_valid_id($_GET["tid"]))
	stderr($tracker_lang['error'], $tracker_lang['invalid_id']);
	$reqid = (int) $_GET["tid"];

	$res = sql_query("SELECT request FROM requests WHERE id = $reqid") or sqlerr(__FILE__,__LINE__);
	$arr = mysql_fetch_array($res);
	if (!$arr)
	stderr($tracker_lang['error'], $tracker_lang['invalid_id']);

	stdhead("���������������� \"" . $arr["request"] . "\"");

	print("<form name=\"Form\" method=\"post\" action=\"reqcomment.php?action=add\">\n");
	print("<input type=\"hidden\" name=\"tid\" value=\"$reqid\"/>\n");
	print("<p align=center><table border=1 cellspacing=1>\n");
	print("<tr><td class=colhead colspan=2>���������������� \"" . $arr["request"] . "\"</td></tr>\n");
	print("<tr><td align=center>\n");
	print textbbcode("msg",$arr["texxt"]);
	print("</td></tr>\n");
	print("<tr><td align=center colspan=2><input type=submit value=\"��������\" class=btn></td></tr></form></table></p>\n");

	$res = sql_query("SELECT reqcomments.id, text, reqcomments.added, reqcomments.ratingsum, username, users.id as user, users.avatar, users.downloaded, users.uploaded, users.class, users.enabled, users.parked, users.warned, users.donor FROM reqcomments LEFT JOIN users ON reqcomments.user = users.id WHERE request = $reqid ORDER BY reqcomments.id DESC LIMIT 5");

	$allrows = array();
	while ($row = mysql_fetch_array($res))
	$allrows[] = $row;

	if (count($allrows)) {
		print("<h2>��������� ����������� � �������� �������.</h2>\n");
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
	$res = sql_query("SELECT c.*, r.request, r.id AS rid, u.username FROM reqcomments AS c JOIN requests AS r ON c.request = r.id JOIN users AS u ON c.user = u.id WHERE c.id=$commentid") or sqlerr(__FILE__,__LINE__);
	$arr = mysql_fetch_array($res);
	if (!$arr)
	stderr($tracker_lang['error'], $tracker_lang['invalid_id']);

	$text = "{$arr[username]}:<br /><blockquote><cite title=\"$arr[username]\">" . $arr["text"] . "</cite></blockquote><hr />\n";
	$reqid = $arr["rid"];

	stdhead("���������� ���������� � \"" . $arr["request"] . "\"");

	print("<form name=form method=\"post\" action=\"reqcomment.php?action=add\">\n");
	print("<input type=\"hidden\" name=\"tid\" value=\"$reqid\"/>\n");
	print("<p align=center><table border=1 cellspacing=1>\n");
	print("<tr><td class=colhead colspan=2>�������� ����������� � \"" . htmlspecialchars($arr["request"]) . "\"</td></tr>\n");
	print("<tr><td align=center>\n");
	print textbbcode("text",$text);
	print("</td></tr>\n");
	print("<tr><td align=center colspan=2><input type=submit value=\"��������\"></td></tr></form></table>\n");

	stdfoot();

}
elseif ($action == "edit")
{
	if (!is_valid_id($_GET["cid"]))
	stderr($tracker_lang['error'], $tracker_lang['invalid_id']);
	$commentid = (int) $_GET["cid"];
	$res = sql_query("SELECT c.*, r.request, r.id AS rid FROM reqcomments AS c JOIN requests AS r ON c.request = r.id WHERE c.id=$commentid") or sqlerr(__FILE__,__LINE__);

	$arr = mysql_fetch_array($res);
	if (!$arr)
	stderr($tracker_lang['error'], $tracker_lang['invalid_id']);

	if ($arr["user"] != $CURUSER["id"] && get_user_class() < UC_MODERATOR)
	stderr($tracker_lang['error'], $tracker_lang['access_denied']);

	if ($_SERVER["REQUEST_METHOD"] == "POST")
	{
		$text = ((string)$_POST["msg"]);
		$returnto = makesafe($_POST["returnto"]);

		if ($text == "")
		stderr($tracker_lang['error'], $tracker_lang['no_fields_blank']);

		$text = sqlesc($text);

		$editedat = sqlesc(time());

		sql_query("UPDATE reqcomments SET text=$text, editedat=$editedat, editedby=$CURUSER[id]  WHERE id=$commentid") or sqlerr(__FILE__, __LINE__);


		$CACHE->clearGroupCache("block-req");

		if ($returnto)
		header("Location: $returnto");
		else
		header("Location: {$CACHEARRAY['defaultbaseurl']}/"); // change later ----------------------

		die;
	}

	stdhead("������������� ����������� � \"" . $arr["request"] . "\"");

	print("<form name=form method=\"post\" action=\"reqcomment.php?action=edit&amp;cid=$commentid\">\n");
	print("<input type=\"hidden\" name=\"returnto\" value=\"requests.php?id={$arr["rid"]}#comm$commentid\" />\n");
	print("<input type=\"hidden\" name=\"cid\" value=\"$commentid\" />\n");
	print("<p align=center><table border=1 cellspacing=1>\n");
	print("<tr><td class=colhead colspan=2>������������� ����������� � \"" . htmlspecialchars($arr["request"]) . "\"</td></tr>\n");
	print("<tr><td align=center>\n");
	print textbbcode("msg",$arr["text"]);
	print("</td></tr>\n");
	print("<tr><td align=center colspan=2><input type=submit value=\"".$tracker_lang['edit']."\"></td></tr></form></table></p>\n");

	stdfoot();

	die;
}
/////////////////�������� �� ����������/////////////////
elseif ($action == "check" || $action == "checkoff")
{
	if (!is_valid_id($_GET["tid"]))
	stderr($tracker_lang['error'], $tracker_lang['invalid_id']);
	$tid = (int) $_GET["tid"];

	$docheck = mysql_fetch_array(sql_query("SELECT COUNT(*) FROM checkcomm WHERE checkid = " . $tid . " AND userid = ". $CURUSER["id"] . " AND req = 1"));
	if ($docheck[0] > 0 && $action=="check")
	stderr($tracker_lang['error'], "<p>�� ��� ��������� �� ���� ������.</p><a href=requests.php?id=$tid#startcomments>�����</a>");
	if ($action == "check") {
		sql_query("INSERT INTO checkcomm (checkid, userid, req) VALUES ($tid, $CURUSER[id], 1)") or sqlerr(__FILE__,__LINE__);
		stderr($tracker_lang['success'], "<p>������ �� ������� �� ������������� � ����� �������.</p><a href=requests.php?id=$tid#startcomments>�����</a>");
	}
	else {
		sql_query("DELETE FROM checkcomm WHERE checkid = $tid AND userid = $CURUSER[id] AND req = 1") or sqlerr(__FILE__,__LINE__);
		stderr($tracker_lang['success'], "<p>������ �� �� ������� �� ������������� � ����� �������.</p><a href=requests.php?id=$tid#startcomments>�����</a>");
	}

}
/////////////////�������� �� ����������/////////////////
elseif ($action == "delete")
{
	if (get_user_class() < UC_MODERATOR)
	stderr($tracker_lang['error'], $tracker_lang['access_denied']);


	if (!is_valid_id($_GET["cid"]))
	stderr($tracker_lang['error'], $tracker_lang['invalid_id']);

	$commentid = (int) $_GET["cid"];


	$res = sql_query("SELECT request FROM reqcomments WHERE id=$commentid") or sqlerr(__FILE__,__LINE__);
	$arr = mysql_fetch_array($res);
	if ($arr)
	$reqid = $arr["request"];
	else
	stderr($tracker_lang['error'], $tracker_lang['invalid_id']);

	sql_query("DELETE FROM reqcomments WHERE id=$commentid") or sqlerr(__FILE__,__LINE__);
	sql_query("UPDATE requests SET comments = comments - 1 WHERE id = $reqid");


	$CACHE->clearGroupCache("block-req");

	$returnto = urlencode($_GET["returnto"]);

	if ($returnto)
	header("Location: $returnto");
	else
	header("Location: {$CACHEARRAY['defaultbaseurl']}/"); // change later ----------------------


	die;
}

else
stderr($tracker_lang['error'], "Unknown action $action");

die;
?>