<?php
/**
 * Requests comments parser
 * @license GNU GPLv3 http://opensource.org/licenses/gpl-3.0.html
 * @package Kinokpk.com releaser
 * @author ZonD80 <admin@kinokpk.com>
 * @copyright (C) 2008-now, ZonD80, Germany, TorrentsBook.com
 * @link http://dev.kinokpk.com
 */

require_once("include/bittorrent.php");

$action = $_GET["action"];
dbconn();

loggedinorreturn();

if ($action == "add") {
	if ($_SERVER["REQUEST_METHOD"] == "POST") {
		$reqid = (int) $_POST["tid"];
		if (!is_valid_id($reqid))
		stderr($REL_LANG->say_by_key('error'), $REL_LANG->say_by_key('invalid_id'));
		$res = sql_query("SELECT request, userid FROM requests WHERE id = $reqid") or sqlerr(__FILE__,__LINE__);
		$arr = mysql_fetch_array($res);
		if (!$arr)
		stderr($REL_LANG->say_by_key('error'), $REL_LANG->say_by_key('invalid_id'));
		$name = $arr[0];
		$text = trim(((string)$_POST["text"]));
		if (!$text)
		stderr($REL_LANG->say_by_key('error'), $REL_LANG->say_by_key('no_fields_blank'));

		// ANTISPAM AND ANTIFLOOD SYSTEM
		$last_pmres = sql_query("SELECT ".time()."-added AS seconds, text AS msg, id, request AS torrent FROM reqcomments WHERE user=".$CURUSER['id']." ORDER BY added DESC LIMIT 4");
		while ($last_pmresrow = mysql_fetch_array($last_pmres)){
			$last_pmrow[] = $last_pmresrow;
			$msgids[] = $last_pmresrow['id'];
			$torids[] = $last_pmresrow['torrent'];
		}
		//   print_r($last_pmrow);
		if ($last_pmrow[0]){
			if (($REL_CONFIG['as_timeout'] > round($last_pmrow[0]['seconds'])) && $REL_CONFIG['as_timeout']) {
				$seconds =  $REL_CONFIG['as_timeout'] - round($last_pmrow[0]['seconds']);
				stderr($REL_LANG->say_by_key('error'),"�� ����� ����� ����� ������ �� �����, ����������, ��������� ������� ����� $seconds ������. <a href=\"javascript: history.go(-1)\">�����</a>");
			}

			if ($REL_CONFIG['as_check_messages'] && ($last_pmrow[0]['msg'] == $text) && ($last_pmrow[1]['msg'] == $text) && ($last_pmrow[2]['msg'] == $text) && ($last_pmrow[3]['msg'] == $text)) {
				$msgview='';
				foreach ($msgids as $key => $msgid){
					$msgview.= "\n<a href=\"".$REL_SEO->make_link('requests','id',$torids[$key])."#comm$msgid\">����������� ID={$msgid}</a> �� ������������ ".$CURUSER['username'];
				}
				$modcomment = sql_query("SELECT modcomment FROM users WHERE id=".$CURUSER['id']);
				$modcomment = mysql_result($modcomment,0);
				if (strpos($modcomment,"Maybe spammer in requests comments") === false) {
					$arow = sql_query("SELECT id FROM users WHERE class = '".UC_SYSOP."'");

					while (list($admin) = mysql_fetch_array($arow)) {
						sql_query("INSERT INTO messages (poster, sender, receiver, added, msg, subject, location) VALUES(0, 0,
						$admin, '" . time() . "', '������������ <a href=\"".$REL_SEO->make_link('userdetails','id',$CURUSER['id'],'username',translit($CURUSER['username']))."\">".$CURUSER['username']."</a> ����� ���� ��������, �.�. ��� 5 ��������� ��������� ������������ � �������� ��������� ���������.$msgview', '��������� � �����!', 1)") or sqlerr(__FILE__, __LINE__);
					}
					$modcomment .= "\n".time()." - Maybe spammer in requests comments";
					sql_query("UPDATE users SET modcomment = ".sqlesc($modcomment)." WHERE id =".$CURUSER['id']);

				} else {
					sql_query("UPDATE users SET enabled=0, dis_reason='Spam in requests comments' WHERE id=".$CURUSER['id']);

					$arow = sql_query("SELECT id FROM users WHERE class = '".UC_SYSOP."'");

					while (list($admin) = mysql_fetch_array($arow)) {
						sql_query("INSERT INTO messages (poster, sender, receiver, added, msg, subject, location) VALUES(0, 0,
						$admin, '" . time() . "', '������������ <a href=\"".$REL_SEO->make_link('userdetails','id',$CURUSER['id'],'username',translit($CURUSER['username']))."\">".$CURUSER['username']."</a> ������� �������� �� ���� � ������������ � ��������, ��� IP ����� (".$CURUSER['ip'].")', '��������� � ����� [���]!', 1)") or sqlerr(__FILE__, __LINE__);
						stderr("�����������!","�� ������� �������� �������� �� ���� � ������������ � ��������! ���� �� �� �������� � �������� �������, <a href=\"".$REL_SEO->make_link('contact')."\">������� ������ �������</a>.");
					}
				}
				stderr($REL_LANG->say_by_key('error'),"�� ����� ����� ����� ������ �� �����, ���� 5 ��������� ������������ � �������� ���������. � ������� ����������� ��������. <b><u>��������! ���� �� ��� ��� ����������� ��������� ���������� ���������, �� ������ ������������� ������������� ��������!!!</u></b> <a href=\"javascript: history.go(-1)\">�����</a>");

			}
		}

		// ANITSPAM SYSTEM END

		sql_query("INSERT INTO reqcomments (user, request, added, text, ip) VALUES (" .
		$CURUSER["id"] . ",$reqid, ".time().", " . sqlesc($text) .
                "," . sqlesc(getip()) . ")");
		$newid = mysql_insert_id();
		sql_query("UPDATE requests SET comments = comments + 1 WHERE id = $reqid");

		$REL_CACHE->clearGroupCache("block-req");

		send_comment_notifs($reqid,"<a href=\"".$REL_SEO->make_link('requests','id',$reqid)."#comm$newid\">".$name."</a>",'reqcomments');
		/////////////////�������� �� ����������/////////////////
		safe_redirect($REL_SEO->make_link('requests','id',$reqid)."#comm$newid");

		exit();
	}
}
elseif ($action == "quote")
{
	if (!is_valid_id($_GET["cid"]))
	stderr($REL_LANG->say_by_key('error'), $REL_LANG->say_by_key('invalid_id'));
	$commentid = (int) $_GET["cid"];
	$res = sql_query("SELECT c.*, r.request, r.id AS rid, u.username FROM reqcomments AS c JOIN requests AS r ON c.request = r.id JOIN users AS u ON c.user = u.id WHERE c.id=$commentid") or sqlerr(__FILE__,__LINE__);
	$arr = mysql_fetch_array($res);
	if (!$arr)
	stderr($REL_LANG->say_by_key('error'), $REL_LANG->say_by_key('invalid_id'));

	$text = "<blockquote><p>" . format_comment($arr["text"]) . "</p><cite>$arr[username]</cite></blockquote><hr /><br /><br />\n";
	$reqid = $arr["rid"];

	stdhead("���������� ���������� � \"" . $arr["request"] . "\"");

	print("<form name=form method=\"post\" action=\"".$REL_SEO->make_link('reqcomment','action','add')."\">\n");
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
	stderr($REL_LANG->say_by_key('error'), $REL_LANG->say_by_key('invalid_id'));
	$commentid = (int) $_GET["cid"];
	$res = sql_query("SELECT c.*, r.request, r.id AS rid FROM reqcomments AS c JOIN requests AS r ON c.request = r.id WHERE c.id=$commentid") or sqlerr(__FILE__,__LINE__);

	$arr = mysql_fetch_array($res);
	if (!$arr)
	stderr($REL_LANG->say_by_key('error'), $REL_LANG->say_by_key('invalid_id'));

	if ($arr["user"] != $CURUSER["id"] && get_user_class() < UC_MODERATOR)
	stderr($REL_LANG->say_by_key('error'), $REL_LANG->say_by_key('access_denied'));

	if ($_SERVER["REQUEST_METHOD"] == "POST")
	{
		$text = ((string)$_POST["msg"]);
		$returnto = makesafe($_POST["returnto"]);

		if ($text == "")
		stderr($REL_LANG->say_by_key('error'), $REL_LANG->say_by_key('no_fields_blank'));

		$text = sqlesc($text);

		$editedat = sqlesc(time());

		sql_query("UPDATE reqcomments SET text=$text, editedat=$editedat, editedby=$CURUSER[id]  WHERE id=$commentid") or sqlerr(__FILE__, __LINE__);


		$REL_CACHE->clearGroupCache("block-req");

		if ($returnto)
		safe_redirect(" $returnto");
		else
		safe_redirect(" {$REL_CONFIG['defaultbaseurl']}/"); // change later ----------------------

		die;
	}

	stdhead("������������� ����������� � \"" . $arr["request"] . "\"");

	print("<form name=form method=\"post\" action=\"".$REL_SEO->make_link('reqcomment','action','edit','cid',$commentid)."\">\n");
	print("<input type=\"hidden\" name=\"returnto\" value=\"".$REL_SEO->make_link('requests','id',$arr["rid"])."#comm$commentid\" />\n");
	print("<input type=\"hidden\" name=\"cid\" value=\"$commentid\" />\n");
	print("<p align=center><table border=1 cellspacing=1>\n");
	print("<tr><td class=colhead colspan=2>������������� ����������� � \"" . htmlspecialchars($arr["request"]) . "\"</td></tr>\n");
	print("<tr><td align=center>\n");
	print textbbcode("msg",$arr["text"]);
	print("</td></tr>\n");
	print("<tr><td align=center colspan=2><input type=submit value=\"".$REL_LANG->say_by_key('edit')."\"></td></tr></form></table></p>\n");

	stdfoot();

	die;
}
/////////////////�������� �� ����������/////////////////
elseif ($action == "check" || $action == "checkoff")
{
	if (!is_valid_id($_GET["tid"]))
	stderr($REL_LANG->say_by_key('error'), $REL_LANG->say_by_key('invalid_id'));
	$tid = (int) $_GET["tid"];

	$docheck = mysql_fetch_array(sql_query("SELECT SUM(1) FROM notifs WHERE checkid = " . $tid . " AND userid = ". $CURUSER["id"] . " AND type='requests'"));
	if ($docheck[0] > 0 && $action=="check")
	stderr($REL_LANG->say_by_key('error'), "<p>�� ��� ��������� �� ���� ������.</p><a href=\"".$REL_SEO->make_link('requests','id',$tid)."#startcomments\">�����</a>");
	if ($action == "check") {
		sql_query("INSERT INTO notifs (checkid, userid, type) VALUES ($tid, $CURUSER[id], 'requests')") or sqlerr(__FILE__,__LINE__);
		stderr($REL_LANG->say_by_key('success'), "<p>������ �� ������� �� ������������� � ����� �������.</p><a href=\"".$REL_SEO->make_link('requests','id',$tid)."#startcomments\">�����</a>");
	}
	else {
		sql_query("DELETE FROM notifs WHERE checkid = $tid AND userid = $CURUSER[id] AND type = 'requests'") or sqlerr(__FILE__,__LINE__);
		stderr($REL_LANG->say_by_key('success'), "<p>������ �� �� ������� �� ������������� � ����� �������.</p><a href=\"".$REL_SEO->make_link('requests','id',$tid)."#startcomments\">�����</a>");
	}

}
elseif ($action == "delete")
{
	if (get_user_class() < UC_MODERATOR)
	stderr($REL_LANG->say_by_key('error'), $REL_LANG->say_by_key('access_denied'));

	if (!is_array($_GET["cid"])||!$_GET["cid"])
	stderr($REL_LANG->say_by_key('error'), $REL_LANG->say_by_key('invalid_id'));
	$cids = array_map("intval",$_GET["cid"]);
	$redaktor = 'reqcomment';
	foreach ($cids AS $commentid) {


		$res = sql_query("SELECT request AS torrent FROM {$redaktor}s WHERE id=$commentid")  or sqlerr(__FILE__,__LINE__);
		$arr = mysql_fetch_array($res);
		if ($arr)
		$torrentid = $arr["torrent"];
		else
		stderr($REL_LANG->say_by_key('error'), $REL_LANG->say_by_key('invalid_id'));

		sql_query("DELETE FROM {$redaktor}s WHERE id=$commentid") or sqlerr(__FILE__,__LINE__);
		if ($torrentid && mysql_affected_rows() > 0)
		sql_query("UPDATE {$redaktor}s SET comments = comments - 1 WHERE id = $torrentid");
	}
	$clearcache = array('block-news');
	foreach ($clearcache as $cachevalue) $REL_CACHE->clearGroupCache($cachevalue);
	safe_redirect(strip_tags($_SERVER['HTTP_REFERER']),1);
	stderr($REL_LANG->_("Success"),$REL_LANG->_("Comments successfully deleted. Now you will back to previous page."),'success');
}
else
stderr($REL_LANG->say_by_key('error'), $REL_LANG->_("Unknown action"));

?>