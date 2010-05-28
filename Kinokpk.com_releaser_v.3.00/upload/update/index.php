<?php
/**
 * Updater from 2.70 to 3.00
 * @license GNU GPLv3 http://opensource.org/licenses/gpl-3.0.html
 * @package Kinokpk.com releaser
 * @author ZonD80 <admin@kinokpk.com>
 * @copyright (C) 2008-now, ZonD80, Germany, TorrentsBook.com
 * @link http://dev.kinokpk.com
 */
define('ROOT_PATH',str_replace('update','',dirname(__FILE__)));


if ($_GET['setlang']) {
	setcookie('lang',(string)$_GET['setlang']);
	print('<html><head><meta http-equiv="Content-Type" content="text/html; charset=windows-1251" /></head><a href="index.php">���������� / Continue</a></html>');
	footers();
	die();
}
if (!$_COOKIE['lang'] || (strlen($_COOKIE['lang'])>2)) {
	print("<html><head><meta http-equiv=\"Content-Type\" content=\"text/html; charset=windows-1251\" /></head><h1>�������� ���� / Choose a language: <a href=\"index.php?setlang=ru\">�������</a>, <a href=\"index.php?setlang=en\">English</a></h1></html>");
	footers();
	die();
} else require_once(ROOT_PATH.'update/lang_'.$_COOKIE['lang'].'.php');
require_once(ROOT_PATH.'include/bittorrent.php');

dbconn(true);
@set_time_limit(0);
@ignore_user_abort(1);
ini_set("error_reporting",'E_ALL & ~E_NOTICE & ~E_WARNING');
$step = (int)$_GET['step'];



function format_spoiler($text) {
	$a[] = "<div style=\"position: static;\" class=\"news-wrap\"><div class=\"news-head folded clickable\">";
	$b[] = "[spoiler]";
	$a[] = "</td></tr></table></div><div class=\"news-body\">";
	$b[] = "";
	$a[] = "</div></div>";
	$b[] = "[/spoiler]";
	return str_replace($a,$b,$text);
}
function headers2() {
	global $step;
	header("X-Powered-By: Kinokpk.com releaser ".RELVERSION);
	header("Cache-Control: no-cache, must-revalidate, max-age=0");
	//header("Expires:" . gmdate("D, d M Y H:i:s") . " GMT");
	header("Expires: 0");
	header("Pragma: no-cache");
	print('<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="ru" lang="ru">
<head>
<title>Kinokpk.com releaser 2.70 to 3.00 updater, step: '.$step.'</title>
<meta http-equiv="Content-Type" content="text/html; charset=windows-1251" /></head><body>');

	if (ini_get("register_globals")) die('<font color="red" size="20">������� register_globals, �������! / Turn off register_globals, noob!</font>');

}

function footers() {
	print('<hr /><div align="right">Kinokpk.com releaser 2.70 to 3.00 updater</div></body></html>');
}

function cont($step) {
	global $lang;
	print '<a href="index.php?step='.$step.'">'.$lang['continue'].'</a>';

}

headers2();

if (!$step) {
	print $lang['hello'];
	print $lang['agree'];
	print('<iframe width="100%" height="300px" src="gnu.html">GNU</iframe>');
	print $lang['agree_continue'];
	print $lang['next_step_update_db'];
}

elseif ($step==1) {
	$strings = file(ROOT_PATH."update/update.sql");
	$query = '';
	foreach ($strings AS $string)
	{
		if (preg_match("/^\s?#/", $string) || !preg_match("/[^\s]/", $string))
		continue;
		else
		{
			$query .= $string;
			if (preg_match("/;\s?$/", $query))
			{
				mysql_query($query) or die($lang['mysql_error'].'['.mysql_errno().']: ' . mysql_error(). 'QUERY: '.$query);
				$query = '';
			}
		}
	}

	print($lang['ok'].'<hr/>');
	print $lang['next_step_update_db'];
	print $lang['step2_descr'];

	cont(2);
}

elseif ($step==2) {
	print 'Converting announces<br/>';

	$res = sql_query("SELECT id,seeders,leechers,announce_urls FROM torrents ORDER BY id ASC");
	while ($row = mysql_fetch_assoc($res)) {
		sql_query("INSERT INTO trackers (torrent,seeders,leechers,tracker) VALUES ({$row['id']},{$row['seeders']},{$row['leechers']},'localhost')");
		if ($row['announce_urls']) {
			$a = explode(',',$row['announce_urls']);
			foreach ($a as $uri)
			sql_query("INSERT INTO trackers (torrent,seeders,leechers,tracker) VALUES ({$row['id']},0,0,'$uri')");

		}
		if (!mysql_errno()) print "torrent {$row['id']} okay<br/>"; else print "torrent {$row['id']} <font color=\"red\">fail</font><br/>";
		flush();
	}
	sql_query("ALTER TABLE `torrents`  DROP `leechers`,  DROP `seeders`, DROP `remote_seeders`,DROP `remote_leechers`, DROP `announce_urls`");
	print '(remote)seeders, (remote)leechers, announce_urls from torrents dropped. <font color="green">done!</font><br/>';


	print $lang['next_step_update_db'];
	print $lang['step3_descr'];
	cont(3);
}

elseif($step==3) {
	// SPOILER REBUILD here
	$res = sql_query("SELECT id,descr FROM torrents");
	print 'Updating torrents...<br/>';
	while ($row = mysql_fetch_assoc($res)) {
		sql_query("UPDATE torrents SET descr=".sqlesc(format_spoiler($row['descr']))." WHERE id={$row['id']}");
		print "torrent {$row['id']} done<br/>";
		flush();
	}
	$res = sql_query("SELECT id,text AS descr FROM comments");
	print '<hr/>Updating comments...<br/>';
	while ($row = mysql_fetch_assoc($res)) {
		sql_query("UPDATE comments SET text=".sqlesc(format_spoiler($row['descr']))." WHERE id={$row['id']}");
		print "comment {$row['id']} done<br/>";
		flush();
	}
	$res = sql_query("SELECT id,msg AS descr FROM messages");
	print '<hr/>Updating messages...<br/>';
	while ($row = mysql_fetch_assoc($res)) {
		sql_query("UPDATE messages SET msg=".sqlesc(format_spoiler($row['descr']))." WHERE id={$row['id']}");
		print "message {$row['id']} done<br/>";
		flush();
	}
	$res = sql_query("SELECT id,body AS descr FROM news");
	print '<hr/>Updating news...<br/>';
	while ($row = mysql_fetch_assoc($res)) {
		sql_query("UPDATE news SET body=".sqlesc(format_spoiler($row['descr']))." WHERE id={$row['id']}");
		print "news {$row['id']} done<br/>";
		flush();
	}
	$res = sql_query("SELECT id,text AS descr FROM newscomments");
	print '<hr/>Updating newscomments...<br/>';
	while ($row = mysql_fetch_assoc($res)) {
		sql_query("UPDATE newscomments SET text=".sqlesc(format_spoiler($row['descr']))." WHERE id={$row['id']}");
		print "newscomment {$row['id']} done<br/>";
		flush();
	}
	$res = sql_query("SELECT id,content AS descr FROM pages");
	print '<hr/>Updating pages...<br/>';
	while ($row = mysql_fetch_assoc($res)) {
		sql_query("UPDATE pages SET content=".sqlesc(format_spoiler($row['descr']))." WHERE id={$row['id']}");
		print "page {$row['id']} done<br/>";
		flush();
	}
	$res = sql_query("SELECT id,text AS descr FROM pollcomments");
	print '<hr/>Updating pollcomments...<br/>';
	while ($row = mysql_fetch_assoc($res)) {
		sql_query("UPDATE pollcomments SET text=".sqlesc(format_spoiler($row['descr']))." WHERE id={$row['id']}");
		print "pollcomment {$row['id']} done<br/>";
		flush();
	}
	$res = sql_query("SELECT id,text AS descr FROM reqcomments");
	print '<hr/>Updating reqcomments...<br/>';
	while ($row = mysql_fetch_assoc($res)) {
		sql_query("UPDATE reqcomments SET text=".sqlesc(format_spoiler($row['descr']))." WHERE id={$row['id']}");
		print "reqcomment {$row['id']} done<br/>";
		flush();
	}
	$res = sql_query("SELECT id, descr FROM requests");
	print '<hr/>Updating requests...<br/>';
	while ($row = mysql_fetch_assoc($res)) {
		sql_query("UPDATE requests SET descr=".sqlesc(format_spoiler($row['descr']))." WHERE id={$row['id']}");
		print "request {$row['id']} done<br/>";
		flush();
	}
	$res = sql_query("SELECT id,text AS descr FROM usercomments");
	print '<hr/>Updating usercomments...<br/>';
	while ($row = mysql_fetch_assoc($res)) {
		sql_query("UPDATE usercomments SET text=".sqlesc(format_spoiler($row['descr']))." WHERE id={$row['id']}");
		print "usercomment {$row['id']} done<br/>";
		flush();
	}
	$res = sql_query("SELECT id,info AS descr FROM users");
	print '<hr/>Updating user info...<br/>';
	while ($row = mysql_fetch_assoc($res)) {
		sql_query("UPDATE users SET info=".sqlesc(format_spoiler($row['descr']))." WHERE id={$row['id']}");
		print "user id {$row['id']} info done<br/>";
		flush();
	}
	print($lang['ok'].'<hr/>');
	print $lang['step4_descr'];
	cont(4);
}
elseif($step==4) {
	if (!isset($_GET['action'])){

		print("�������� ��������� Kinokpk.com releaser ".RELVERSION.'<hr/>');
		print('<form action="index.php?step=4&action=save" method="POST">');
		print('<table width="100%" border="1">');

		print('<tr><td align="center" colspan="2" class="colhead">�������� ���������</td></tr>');

		print('<tr><td>����� ����� (��� /):</td><td><input type="text" name="defaultbaseurl" size="30" value="'.$CACHEARRAY['defaultbaseurl'].'"> <br/>��������, "http://www.kinokpk.com"</td></tr>');
		print('<tr><td>�������� ����� (title):</td><td><input type="text" name="sitename" size="80" value="'.$CACHEARRAY['sitename'].'"> <br/>��������, "������� �������� �����������"</td></tr>');
		print('<tr><td>�������� ����� (meta description):</td><td><input type="text" name="description" size="80" value="'.$CACHEARRAY['description'].'"> <br/>��������, "����� ������� ���������� ������� ����"</td></tr>');
		print('<tr><td>�������� ����� (meta keywords):</td><td><input type="text" name="keywords" size="80" value="'.$CACHEARRAY['keywords'].'"> <br/>��������, "�������, ����������, ������, �������"</td></tr>');
		print('<tr><td>�����, � �������� ����� ������������ ��������� �����:</td><td><input type="text" name="siteemail" size="30" value="'.$CACHEARRAY['siteemail'].'"> <br/>��������, "bot@kinokpk.com"</td></tr>');
		print('<tr><td>����� ��� ����� � ���������������:</td><td><input type="text" name="adminemail" size="30" value="'.$CACHEARRAY['adminemail'].'"> <br/>��������, "admin@windows.lox"</td></tr>');
		print('<tr><td>���� �������� �� ��������� (��� lang_%����%):</td><td><input type="text" name="default_language" size="2" value="ru"></td></tr>');
		print('<tr><td>������������ ������� �������������� (��������� �� �������������):</td><td><select name="use_lang"><option value="1" '.($CACHEARRAY['use_lang']==1?"selected":"").'>��</option><option value="0" '.($CACHEARRAY['use_lang']==0?"selected":"").'>���</option> ����������� ������ ������ 2 ����� ����� (ru,en)</select></td></tr>');
		print('<tr><td>����������� ���� ��� ������ � ���������������� (themes/%����%):</td><td><input type="text" name="default_theme" size="10" value="'.$CACHEARRAY['default_theme'].'"> �� ��������� "kinokpk"</td></tr>');
		print('<tr><td>��� �������� ��� ����������� ����� ��������:<br /><small>*�� ������ ������������ ������ <b>{datenow}</b> ��� ������ �������� ����</small></td><td><input type="text" name="yourcopy" size="60" value="'.$CACHEARRAY['yourcopy'].'"> <br/>��������, "&copy; 2008-{datenow} ��� ����"</td></tr>');
		print('<tr><td>������������ ������� ������ (��������� �� �������������):</td><td><select name="use_blocks"><option value="1" '.($CACHEARRAY['use_blocks']==1?"selected":"").'>��</option><option value="0" '.($CACHEARRAY['use_blocks']==0?"selected":"").'>���</option></select></td></tr>');
		print('<tr><td>������������ gzip ������ ��� �������:</td><td><select name="use_gzip"><option value="1" '.($CACHEARRAY['use_gzip']==1?"selected":"").'>��</option><option value="0" '.($CACHEARRAY['use_gzip']==0?"selected":"").'>���</option></select></td></tr>');
		print('<tr><td>������������ ������� ����� �� IP/��������:</td><td><select name="use_ipbans"><option value="1" '.($CACHEARRAY['use_ipbans']==1?"selected":"").'>��</option><option value="0" '.($CACHEARRAY['use_ipbans']==0?"selected":"").'>���</option></select></td></tr>');
		print('<tr><td>��� SMTP:</td><td><input type="text" name="smtptype" size="10" value="'.$CACHEARRAY['smtptype'].'"></td></tr>');
		print('<tr><td>�������� ������ ����� � ��������:</td><td><select name="announce_packed"><option value="1" '.($CACHEARRAY['announce_packed']==1?"selected":"").'>��</option><option value="0" '.($CACHEARRAY['announce_packed']==0?"selected":"").'>���</option></select> �� ���������, ��</td></tr>');

		print('<tr><td align="center" colspan="2" class="colhead">��������� ���������� � ������� IPB</td></tr>');

		print('<tr><td>������������ ���������� � ������� IPB:</td><td><select name="use_integration"><option value="1" '.($CACHEARRAY['use_integration']==1?"selected":"").'>��</option><option value="0" '.($CACHEARRAY['use_integration']==0?"selected":"").'>���</option></select></td></tr>');
		print('<tr><td>������ �� ������ ������ ������ �� ��������:<br /><small>� ���� ������ ��� ������������ ������� ��� ����� �� ������� ������������ ����� ������ ������, ����� ������� ������������� ������ ������ �� ������</small></td><td><select name="ipb_password_priority"><option value="1" '.($CACHEARRAY['ipb_password_priority']==1?"selected":"").'>��</option><option value="0" '.($CACHEARRAY['ipb_password_priority']==0?"selected":"").'>���</option></select></td></tr>');
		print('<tr><td>��� �������� ������� �� �����:<br /><small>*��� ������������� ������� �������� � ����-������<br />���������� ���������� IPB � wikimedia � <a target="_blank" href="http://www.ipbwiki.com/">http://www.ipbwiki.com/</a></small></td><td><select name="exporttype"><option value="wiki" '.($CACHEARRAY['exporttype']=="wiki"?"selected":"").'>� ����-������</option><option value="post" '.($CACHEARRAY['exporttype']=="post"?"selected":"").'>��������������� � ����</option></select></td></tr>');
		print('<tr><td>����� ������ (��� /):</td><td><input type="text" name="forumurl" size="60" value="'.$CACHEARRAY['forumurl'].'"> <br/>��������, "http://forum.pdaprime.ru"</td></tr>');
		print('<tr><td>�������� ������:</td><td><input type="text" name="forumname" size="60" value="'.$CACHEARRAY['forumname'].'"> <br/>��������, "pdaPRIME.ru"</td></tr>');
		print('<tr><td>������� �������� cookie:</td><td><input type="text" name="ipb_cookie_prefix" size="4" value="'.$CACHEARRAY['ipb_cookie_prefix'].'"> �� ��������� IPB, �����</td></tr>');
		print('<tr><td>ID ������-�������:</td><td><input type="text" name="forum_bin_id" size="3" value="'.$CACHEARRAY['forum_bin_id'].'"></td></tr>');
		print('<tr><td>����� ������������� ����� �������� �� �����:</td><td><input type="text" name="defuserclass" size="1" value="'.$CACHEARRAY['defuserclass'].'"> �� ��������� IPB, "3"</td></tr>');
		print('<tr><td>ID ������ ��� �������� ������ �������:<br /><small>*��� ������, ����� ������� �� ��������� � ��������� �����, ���� �������� ������ ��� ����������� ������</small></td><td><input type="text" name="not_found_export_id" size="3" value="'.$CACHEARRAY['not_found_export_id'].'"></td></tr>');
		print('<tr><td>����� �� �������� ������ (��� /):</td><td><input type="text" name="emo_dir" size="10" value="'.$CACHEARRAY['emo_dir'].'"> �� ��������� IPB, "default"</td></tr>');


		print('<tr><td align="center" colspan="2" class="colhead">��������� �����������</td></tr>');

		print('<tr><td>��������� �����������:</td><td><select name="deny_signup"><option value="1" '.($CACHEARRAY['deny_signup']==1?"selected":"").'>��</option><option value="0" '.($CACHEARRAY['deny_signup']==0?"selected":"").'>���</option></select></td></tr>');
		print('<tr><td>��������� ����������� �� ������������:</td><td><select name="allow_invite_signup"><option value="1" '.($CACHEARRAY['allow_invite_signup']==1?"selected":"").'>��</option><option value="0" '.($CACHEARRAY['allow_invite_signup']==0?"selected":"").'>���</option></select></td></tr>');
		print('<tr><td>��������� ���� ��� �����������:</td><td>'.list_timezones('register_timezone',$CACHEARRAY['register_timezone']).'</td></tr>');
		print('<tr><td>������������ ��������� ��������� �� e-mail:</td><td><select name="use_email_act"><option value="1" '.($CACHEARRAY['use_email_act']==1?"selected":"").'>��</option><option value="0" '.($CACHEARRAY['use_email_act']==0?"selected":"").'>���</option></select></td></tr>');
		print('<tr><td>������������ �����:<br /><small>*�� ������ ������������������ �� <a target="_blank" href="http://recaptcha.net">ReCaptcha.net</a> � �������� ��������� � ��������� ����� ��� ������������� ���� �����</small></td><td><select name="use_captcha"><option  value="1" '.($CACHEARRAY['use_captcha']==1?"selected":"").'>��</option><option value="0" '.($CACHEARRAY['use_captcha']==0?"selected":"").'>���</option></select></td></tr>');
		print('<tr><td>��������� ���� �����:</td><td><input type="text" name="re_publickey" size="80" value="'.$CACHEARRAY['re_publickey'].'"></td></tr>');
		print('<tr><td>��������� ���� �����:</td><td><input type="text" name="re_privatekey" size="80" value="'.$CACHEARRAY['re_privatekey'].'"></td></tr>');
		print('<tr><td>����������� ����������� (����.���� �/��� ��):</td><td><input type="text" name="default_notifs" size="120" value="'.$CACHEARRAY['default_notifs'].'"></td></tr>');
		print('<tr><td>����������� ����������� � Email:</td><td><input type="text" name="default_emailnotifs" size="120" value="'.$CACHEARRAY['default_emailnotifs'].'"></td></tr>');
		print('<tr><td colspan="2"><small>*��� ���� ����������� � Kinokpk.com releaser '.RELVERSION.':<br/>unread,torrents,comments,pollcomments,newscomments,usercomments,reqcomments,rgcomments,pages,pagecomments,friends,users,reports,unchecked ; ��������� - <a target="_blank" href="mynotifs.php?settings">��������� ���� �����������</a></small></td></tr>');


		print('<tr><td align="center" colspan="2" class="colhead">��������� �����������</td></tr>');

		print('<tr><td>������������ ���������� �������������:</td><td><input type="text" name="maxusers" size="6" value="'.$CACHEARRAY['maxusers'].'">�������������, ������� 0 ��� ���������� ������</td></tr>');
		print('<tr><td>������������ ���������� ��������� � ������ �����:</td><td><input type="text" name="pm_max" size="4" value="'.$CACHEARRAY['pm_max'].'">���������</td></tr>');
		print('<tr><td>������������ ������ ������:</td><td><input type="text" name="avatar_max_width" size="3" value="'.$CACHEARRAY['avatar_max_width'].'">��������</td></tr>');
		print('<tr><td>������������ ������ ������:</td><td><input type="text" name="avatar_max_height" size="3" value="'.$CACHEARRAY['avatar_max_height'].'">��������</td></tr>');
		print('<tr><td>��������� ����������� � ��������� �������:</td><td><select name="nc"><option value=1 '.($CACHEARRAY['nc']?"selected":"").'>��</option><option value="0" '.(!$CACHEARRAY['nc']?"selected":"").'>���</option></select></td></tr>');
		print('<tr><td>������������ ������ �������-����� � ������:</td><td><input type="text" name="max_torrent_size" size="10" value="'.$CACHEARRAY['max_torrent_size'].'">����</td></tr>');
		print('<tr><td>������������ ���������� �������� ��� ������:</td><td><input type="text" name="max_images" size="2" value="'.$CACHEARRAY['max_images'].'"><br/>��������, "2"</td></tr>');
		print('<tr><td>��������� adult �������:<br /><small>*����� �� ��������� ���������� ��������� "XXX �����", ������������ ����� �������� ����������� ���� ��������� � �������<br /><b>���� ��������� ������, ��� ����, ���������� �� ����� ������� <u>��� ��������</u></b></small></td><td><input type="text" name="pron_cats" size="60" value="'.$CACHEARRAY['pron_cats'].'"><br/>��������, "13,14"</td></tr>');

		print('<tr><td align="center" colspan="2" class="colhead">��������� ������������</td></tr>');

		print('<tr><td>����-�������� � ��������:</td><td><input type="text" name="as_timeout" size="10" value="'.$CACHEARRAY['as_timeout'].'">������</td></tr>');
		print('<tr><td>������������ �������� ��������� 5 ������������ (��������):</td><td><select name="as_check_messages"><option value="1" '.($CACHEARRAY['as_check_messages']==1?"selected":"").'>��</option><option value="0" '.($CACHEARRAY['as_check_messages']==0?"selected":"").'>���</option></select></td></tr>');
		print('<tr><td>�����-�����:</td><td><select name="debug_mode"><option value="1" '.($CACHEARRAY['debug_mode']==1?"selected":"").'>��</option><option value="0" '.($CACHEARRAY['debug_mode']==0?"selected":"").'>���</option></select></td></tr>');

		print('<tr><td align="center" colspan="2" class="colhead">������</td></tr>');

		print('<tr><td>����������� ������������� �������� ������� ������ � ���������.��:<br/><small>*�������� ������, ���� � �������� ������ ���� ������ ���� http://www.kinopoisk.ru/level/1/film/ID_������</small></td><td><select name="use_ttl"><option value="1" '.($CACHEARRAY['use_kinopoisk_trailers']==1?"selected":"").'>��</option><option value="0" '.($CACHEARRAY['use_kinopoisk_trailers']==0?"selected":"").'>���</option></select></td></tr>');
		print('<tr><td>���������� ������� � ������ ������� �� ��������:<br /><small>*��� ��������� ����� ��������� ���������� �������� ��� browse</small></td><td><input type="text" name="torrentsperpage" size="3" value="'.$CACHEARRAY['torrentsperpage'].'">�������</td></tr>');
		print('<tr><td>������������ TTL (���� �������� ������� ���������):</td><td><select name="use_ttl"><option value="1" '.($CACHEARRAY['use_ttl']==1?"selected":"").'>��</option><option value="0" '.($CACHEARRAY['use_ttl']==0?"selected":"").'>���</option></select></td></tr>');
		print('<tr><td>������������ ������� ����������� ������� �� �������:</td><td><select name="use_wait"><option value="1" '.($CACHEARRAY['use_wait']==1?"selected":"").'>��</option><option value="0" '.($CACHEARRAY['use_wait']==0?"selected":"").'>���</option></select></td></tr>');

		print('<tr><td align="center" colspan="2"><input type="submit" value="��������� ���������"><input type="reset" value="��������"></td></tr></table></form>');


	}

	elseif ($_GET['action'] == 'save'){
		$reqparametres = array('torrentsperpage','maxusers','max_torrent_size','max_images','defaultbaseurl','siteemail','adminemail','sitename','description','keywords',
'forumname','yourcopy','pm_max','default_language',
'avatar_max_width','avatar_max_height','default_theme','nc','deny_signup','allow_invite_signup',
'use_ttl','use_email_act','use_wait','use_lang','use_captcha','use_blocks','use_gzip','use_ipbans','smtptype',
'as_timeout','as_check_messages','use_integration','debug_mode','ipb_cookie_prefix','announce_packed','pron_cats','register_timezone');
		$int_param = array('exporttype','forumurl','forum_bin_id','defuserclass','not_found_export_id','emo_dir','ipb_password_priority');
		$captcha_param = array('re_publickey','re_privatekey');

		$updateset = array();

		foreach ($reqparametres as $param) {
			if (!isset($_POST[$param]) && ($param != 'forumname') && ($param != 'ipb_cookie_prefix') && ($param != 'pron_cats')) stderr($tracker_lang['error'],"��������� ���� �� ��������� ($param)");
			$updateset[] = "UPDATE cache_stats SET cache_value=".sqlesc($_POST[$param])." WHERE cache_name='$param'";
		}

		if ($_POST['use_integration'] == 1) {
			foreach ($int_param as $param) {
				if (!isset($_POST[$param])) stderr($tracker_lang['error'],"��������� ���� ��� ���������� � ������� �� ���������");
				$updateset[] = "UPDATE cache_stats SET cache_value=".sqlesc($_POST[$param])." WHERE cache_name='$param'";
			}
		}
		if ($_POST['use_captcha'] == 1) {
			foreach ($captcha_param as $param) {
				if (!$_POST[$param] || !isset($_POST[$param])) stderr($tracker_lang['error'],"��������� ��� ��������� ����� ����� �� ����������");
				$updateset[] = "UPDATE cache_stats SET cache_value=".sqlesc($_POST[$param])." WHERE cache_name='$param'";
			}
		}

		foreach ($updateset as $query) sql_query($query);

		$CACHE->clearCache('system','config');
		print($lang['ok'].'<hr/>');

		print $lang['step5_descr'];

		cont(5);
	}
}
elseif ($step==5) {
	$cronrow = sql_query("SELECT * FROM cron");

	while ($cronres = mysql_fetch_array($cronrow)) $CRON[$cronres['cron_name']] = $cronres['cron_value'];

	if (!isset($_POST['save']) && !isset($_POST['reset']) && !isset($_POST['recount'])){

		print("��������� cron-�������<hr/>");
		print('<form action="index.php?step=5" method="POST">');
		print('<table width="100%" border="1">');

		if ($CRON['in_remotecheck'] && $CRON['remotecheck_disabled']) $remotecheck_state .= '<font color="red">������ �� ��������� �����, �� ������ ��� �����������. ��������� ����������</font>';
		if (!$CRON['in_remotecheck'] && $CRON['remotecheck_disabled']) $remotecheck_state .= '<font color="green">������� �����������</font>';
		if ($CRON['in_remotecheck'] && !$CRON['remotecheck_disabled']) $remotecheck_state .= '<font color="green">������� ��������</font>';
		if (!$CRON['in_remotecheck'] && !$CRON['remotecheck_disabled']) $remotecheck_state .= '<font color="green">������� � ������ ��������</font>';

		print('<tr><td align="center" colspan="2" class="colhead">��������� ��������������� ����� | �� <a href="retrackeradmin.php">� ���������� ����������</a></td></tr>');
		print('<tr><td>��������� ������� ��������� ��������� �����:<br /><small>*��� ��� ��� ������� ����������� � ������� ������, �� �� ���������� ����� ������������� ��������� �����. ����� �� �������� ������� ������� ��������� ������.</small></td><td><select name="remotecheck_disabled"><option value="1" '.($CRON['remotecheck_disabled']==1?"selected":"").'>��</option><option value="0" '.($CRON['remotecheck_disabled']==0?"selected":"").'>���</option></select> '.$remotecheck_state .'</td></tr>');
		print('<tr><td>����� ������������ ��������� �����:<br /><small>*����� N ������ �������� �������� �� �������� ������.</small></td><td><input type="text" name="remotepeers_cleantime" size="3" value="'.$CRON['remotepeers_cleantime'].'"> <b>������</b></td></tr>');
		print('<tr><td>������� ��������� ��������� �� ���:<br/><small>*�� ������� ��������, ����� ��� torrentsbook.com, ���������� ���������� ���������� ����������� ���������. ��� <b>����</b> ����� ��������� ��� �������������� ��������</small></td><td><input type="text" name="remote_torrents" size="5" value="'.$CRON['remote_torrents'].'">���������</td></tr>');
		print('<tr><td>�������� ����� ����������:<br/><small>*��� ������� �������� ���������� ��������� ���� ��������. ��� <b>����</b> ������ ����� ����������� ���������</small></td><td><input type="text" name="remotecheck_interval" size="3" value="'.$CRON['remotecheck_interval'].'">������</td></tr>');


		print('<tr><td align="center" colspan="2" class="colhead">��������� �������</td></tr>');

		print('<tr><td>���������� ����, �� ���������� ������� ��������� ���������������� ��������:</td><td><input type="text" name="signup_timeout" size="2" value="'.$CRON['signup_timeout'].'">����</td></tr>');
		print('<tr><td>����� � ���, ����� ������� ������� ��������� �������:</td><td><input type="text" name="max_dead_torrent_time" size="3" value="'.$CRON['max_dead_torrent_time'].'">������</td></tr>');
		print('<tr><td>����� ������� �� � ��������:</td><td><input type="text" name="autoclean_interval" size="4" value="'.$CRON['autoclean_interval'].'">������</td></tr>');
		print('<tr><td>���������� ���� ��� ������� ������ ��������� �� �������:</td><td><input type="text" name="pm_delete_sys_days" size="2" value="'.$CRON['pm_delete_sys_days'].'">����</td></tr>');
		print('<tr><td>���������� ���� ��� ������� ������ ��������� �� ������������:</td><td><input type="text" name="pm_delete_user_days" size="2" value="'.$CRON['pm_delete_user_days'].'">����</td></tr>');
		print('<tr><td>����� ����� �������� �������� � ����:</td><td><input type="text" name="ttl_days" size="3" value="'.$CRON['ttl_days'].'">����</td></tr>');


		print('<tr><td align="center" colspan="2" class="colhead">��������� �������������� ����������� �������</td></tr>');
		print('<tr><td>����������� ������� ��������:<br /><small>*��� ����� �������� ������ <b>��������������</b> ��������� �������� �������� � �����������, ��������� � ���. ������������ � ����� ������ ������ ��������� �������� ���� �����, �� ��� ������ �� ����� ������ �� �� ���.</small></td><td><select name="rating_enabled"><option value="1" '.($CRON['rating_enabled']==1?"selected":"").'>��</option><option value="0" '.($CRON['rating_enabled']==0?"selected":"").'>���</option></select></td></tr>');
		print('<tr><td>�����, � ������� �������� ������������ ��������� �������� (����������� ������� �� ���� �� ���������):</td><td><input type="text" name="rating_freetime" size="2" value="'.$CRON['rating_freetime'].'">����</td></tr>');
		print('<tr><td>�������� ����� ���������� �������� ��� �������������:</td><td><input type="text" name="rating_checktime" size="4" value="'.$CRON['rating_checktime'].'">�����</td></tr>');
		print('<tr><td>���������� ��������, �������� ������������ �� ������� ������:</td><td><input type="text" size="3" name="rating_perrelease" value="'.$CRON['rating_perrelease'].'"></td></tr>');
		print('<tr><td>���������� ��������, �������� ������������ �� ����������� ������������� ������������:</td><td><input type="text" size="3" name="rating_perinvite" value="'.$CRON['rating_perinvite'].'"></td></tr>');
		print('<tr><td>���������� ��������, �������� ������������ �� ���������� �������:</td><td><input type="text" size="3" name="rating_perrequest" value="'.$CRON['rating_perrequest'].'"></td></tr>');
		print('<tr><td>���������� ��������, �������� ������������ �� �����������:<br /><small>*������ ������� ��� ����������� ������������ ������� � myrating.php</small></td><td><input type="text" size="3" name="rating_perseed" value="'.$CRON['rating_perseed'].'"></td></tr>');
		print('<tr><td>���������� ��������, ���������� � ������������ �� ��������� ������:</td><td><input type="text" size="3" name="rating_perleech" value="'.$CRON['rating_perleech'].'"></td></tr>');
		print('<tr><td>����� ������� ���������� ���������:</td><td><input type="text" size="4" name="rating_downlimit" value="'.$CRON['rating_downlimit'].'"></td></tr>');
		print('<tr><td>����� ���������� ��������:</td><td><input type="text" size="4" name="rating_dislimit" value="'.$CRON['rating_dislimit'].'"></td></tr>');
		print('<tr><td>������������ ���������� ��������:</td><td><input type="text" size="4" name="rating_max" value="'.$CRON['rating_max'].'"></td></tr>');
		print('<tr><td>������� ������ �������� ����� 1 ������� ������:</td><td><input type="text" size="2" name="rating_discounttorrent" value="'.$CRON['rating_discounttorrent'].'"></td></tr>');


		print('<tr><td align="center" colspan="2" class="colhead">������ ���������</td></tr>');
		print('<tr><td>�������� �������� (���������� ���������� � ��������):</td><td><input type="text" size="5" name="announce_interval" value="'.$CRON['announce_interval'].'">�����</td></tr>');
		print('<tr><td>�������� ������� ������ � ���������� �����/��������:<br /><small>*����� ���������� ������� ������������ ������ �������� ������� ��� ���.<br />*������ �������� �� ����� ���� ������ ��������� �������, ����������, ����� ��� ���� ������ ���.<br />*�������� ���� ������, ��� 0, ���� ������, ����� ������� ����������� ��������</td><td><input type="text" size="3" name="delete_votes" value="'.$CRON['delete_votes'].'">�����</td></tr>');

		print('<tr><td align="center" colspan="2"><input type="submit" name="save" value="��������� ���������"><input type="reset" value="��������"><input type="submit" name="reset" value="�������� ���������� cron" disabled><input type="submit" name="recount" value="���������������� �������� � ��" disabled></td></tr>
<tr><td colspan="2"><small>*����� ���������� cron ���������, ���� ������� ������� ���������� ��������� cron-�������, ������� �� ����������� �������� cron ������ ����� <a href="http://httpd.apache.org/docs/2.0/mod/mod_status.html">mod_status</a> ��� apache</small></td></tr></table></form>');

	}
	elseif (isset($_POST['save'])) {

		$reqparametres = array('max_dead_torrent_time','signup_timeout','autoclean_interval','pm_delete_sys_days','pm_delete_user_days','ttl_days','remotecheck_disabled','announce_interval','delete_votes','remote_torrents','rating_enabled','remotecheck_interval');

		$multi_param = array('remotepeers_cleantime');

		$rating_param = array('rating_freetime','rating_perseed','rating_perinvite','rating_perrequest','rating_checktime','rating_perrelease','rating_dislimit','rating_downlimit','rating_perleech','rating_discounttorrent','rating_max');

		$updateset = array();

		foreach ($reqparametres as $param) {
			if (!isset($_POST[$param]) && (($param != 'rating_enabled') || ($param != 'delete_votes') || ($param != 'remote_torrents')))  { stdmsg($tracker_lang['error'],"��������� ���� �� ��������� ($param)",'error'); stdfoot(); die; }
			$updateset[] = "UPDATE cron SET cron_value=".sqlesc($_POST[$param])." WHERE cron_name='$param'";
		}

		if ($_POST['remotecheck_disabled'] == 0) {
			foreach ($multi_param as $param) {
				if (!$_POST[$param] || !isset($_POST[$param])) { stdmsg($tracker_lang['error'],"��������� ���� ��� ����������������� �� ���������",'error'); stdfoot(); die; }
				$updateset[] = "UPDATE cron SET cron_value=".sqlesc($_POST[$param])." WHERE cron_name='$param'";
			}
		}

		if ($_POST['rating_enabled']) {
			foreach ($rating_param as $param) {
				if (!$_POST[$param] || !isset($_POST[$param])) { stdmsg($tracker_lang['error'],"��������� ���� ��� ����������� ������� �� ���������",'error'); stdfoot(); die; }
				$updateset[] = "UPDATE cron SET cron_value=".sqlesc($_POST[$param])." WHERE cron_name='$param'";
			}
		}

		foreach ($updateset as $query) sql_query($query);
		print($lang['ok'].'<hr/>');

		print $lang['step6_descr'];

		cont(6);
	}
}

elseif ($step==6){

	if (!is_writable(ROOT_PATH.'include/secrets.php')) die($lang['wrong_chmod']);
	$secret = trim((string)$_POST['secret']);
	if (!$secret) {
		print '<form action="index.php?step=6" method="post"><input type="text" name="secret"><input type="submit">'.$lang['secret_notice'];
	}
	else {
		$pattern = <<<HTML
<?php
/**
 * Passwords. Just for fun
 * @license GNU GPLv3 http://opensource.org/licenses/gpl-3.0.html
 * @package Kinokpk.com releaser
 * @author ZonD80 <admin@kinokpk.com>
 * @copyright (C) 2008-now, ZonD80, Germany, TorrentsBook.com
 * @link http://dev.kinokpk.com
 */

if(!defined('IN_TRACKER') && !defined('IN_ANNOUNCE')) die("Direct access to this page not allowed");

\$mysql_host = '$mysql_host';
\$mysql_user = '$mysql_user';
\$mysql_pass = '$mysql_pass';
\$mysql_db = '$mysql_db';
\$mysql_charset = '$mysql_charset';

\$fmysql_host = '$fmysql_host';
\$fmysql_user = '$fmysql_user';
\$fmysql_pass = '$fmysql_pass';
\$fmysql_db = '$fmysql_db';
\$fmysql_charset = '$fmysql_charset';
\$fprefix = '$fprefix';

define("COOKIE_SECRET",'$secret');
?>
HTML;
		if (!file_put_contents(ROOT_PATH.'include/secrets.php',$pattern)) die($lang['write_config_error']);
		print($lang['ok'].'<hr/>');


		cont(7);
	}
}
elseif ($step==7) {
	$CACHE->ClearAllCache();
	print $lang['install_complete'];
	print $lang['install_notice'];
	print $lang['donate'];
}


footers();
?>
