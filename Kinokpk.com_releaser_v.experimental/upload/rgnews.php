<?php
/**
 * Relgroups news
 * @license GNU GPLv3 http://opensource.org/licenses/gpl-3.0.html
 * @package Kinokpk.com releaser
 * @author ZonD80 <admin@kinokpk.com>
 * @copyright (C) 2008-now, ZonD80, Germany, TorrentsBook.com
 * @link http://dev.kinokpk.com
 */

require "include/bittorrent.php";

dbconn();
loggedinorreturn();


if (get_user_class() < UC_UPLOADER)
stderr($REL_LANG->say_by_key('error'), $REL_LANG->say_by_key('access_denied'));

$action = $_GET["action"];

$id = (int) $_GET['id'];

if (!is_valid_id($id)) stderr($REL_LANG->say_by_key('error'),$REL_LANG->say_by_key('invalid_id'));
$relgroup = sql_query("SELECT name,owners FROM relgroups WHERE id=$id") or sqlerr(__FILE__,__LINE__);
list($rgname,$owners) = mysql_fetch_array($relgroup);

$to_group = " <a href=\"".$REL_SEO->make_link('relgroups','id',$id,'name',translit($rgname))."\">� ����� ������</a>";
//if (!$owners && get_user_class()<UC_ADMINISTRATOR) stderr($REL_LANG->say_by_key('error'),$REL_LANG->say_by_key('error_no_onwers'));

if ($owners) {
	$owners = explode(',',$owners);

if (!@in_array($CURUSER['id'],$owners) && (get_user_class()<UC_ADMINISTRATOR)) stderr($REL_LANG->say_by_key('error'),$REL_LANG->say_by_key('no_relgroup_owner'));
}
//   Delete rgnews Item    //////////////////////////////////////////////////////

if ($action == 'delete')
{
	$rgnewsid = (int)$_GET["newsid"];
	if (!is_valid_id($rgnewsid))
	stderr($REL_LANG->say_by_key('error'),$REL_LANG->say_by_key('invalid_id'));

	$returnto = strip_tags($_SERVER['HTTP_REFERER']);

	sql_query("DELETE FROM rgnews WHERE id=$rgnewsid") or sqlerr(__FILE__, __LINE__);
	sql_query("DELETE FROM rgnewscomments WHERE rgnews=$rgnewsid") or sqlerr(__FILE__, __LINE__);
	sql_query("DELETE FROM notifs WHERE type='rgnewscomments' AND checkid=$rgnewsid") or sqlerr(__FILE__, __LINE__);

	$REL_CACHE->clearCache('relgroups-'.$id, 'newsquery');
	if ($returnto != "")
	safe_redirect(" $returnto");
	else
	$warning = "������� <b>�������</b> �������$to_group";
}

elseif ($action == 'add')
{

	$subject = htmlspecialchars($_POST["subject"]);
	if (!$subject)
	stderr($REL_LANG->say_by_key('error'),"���� ������� �� ����� ���� ������!");

	$body = ((string)$_POST["body"]);
	if (!$body)
	stderr($REL_LANG->say_by_key('error'),"���� ������� �� ����� ���� ������!");

	$added = $_POST["added"];
	if (!$added)
	$added = sqlesc(time());

	sql_query("INSERT INTO rgnews (relgroup, added, body, subject) VALUES (".
	$id . ", $added, " . sqlesc($body) . ", " . sqlesc($subject) . ")") or sqlerr(__FILE__, __LINE__);

	$REL_CACHE->clearCache('relgroups-'.$id, 'newsquery');
	$warning = "������� <b>������� ���������</b>$to_group";

}

//   Edit rgnews Item    ////////////////////////////////////////////////////////

elseif ($action == 'edit')
{

	$rgnewsid = (int)$_GET["newsid"];

	if (!is_valid_id($rgnewsid))
	stderr($REL_LANG->say_by_key('error'),$REL_LANG->say_by_key('invalid_id'));

	$res = sql_query("SELECT * FROM rgnews WHERE id=$rgnewsid") or sqlerr(__FILE__, __LINE__);
	$arr = mysql_fetch_array($res);
	if (!$arr) 	  stderr($REL_LANG->say_by_key('error'),$REL_LANG->say_by_key('invalid_id'));

	if ($_SERVER['REQUEST_METHOD'] == 'POST')
	{
		$body = (string)$_POST['body'];
		$subject = htmlspecialchars($_POST['subject']);

		if ($subject == "")
		stderr($REL_LANG->say_by_key('error'),"���� ������� �� ����� ���� ������!");

		if ($body == "")
		stderr($REL_LANG->say_by_key('error'), "���� ������� �� ����� ���� ������!");

		$body = sqlesc(($body));

		$subject = sqlesc($subject);

		$editedat = time();

		sql_query("UPDATE rgnews SET body=$body, subject=$subject WHERE id=$rgnewsid") or sqlerr(__FILE__, __LINE__);

		$REL_CACHE->clearCache('relgroups-'.$id, 'newsquery');

		$returnto = strip_tags($_GET['returnto']);

		if ($returnto != "")
		safe_redirect(" $returnto");
		else
		$warning = "������� <b>�������</b> ���������������$to_group";
	}
	else
	{
		$returnto = strip_tags($_SERVER['HTTP_REFERER']);
		stdhead("�������������� ������� $rgname");
		print("<form method=post name=rgnews action=\"".$REL_SEO->make_link('rgnews','action','edit','newsid',$rgnewsid,'id',$id)."\">\n");
		print("<table border=1 cellspacing=0 cellpadding=5>\n");
		print("<tr><td class=colhead>�������������� ������� $rgname</td></tr>\n");
		print("<tr><td>����: <input type=text name=subject maxlength=70 size=50 value=\"" . makesafe($arr["subject"]) . "\"/></td></tr>");
		print("<tr><td style='padding: 0px'>");
		print textbbcode("body",$arr["body"]);
		//<textarea name=body cols=145 rows=5 style='border: 0px'>" . htmlspecialchars($arr["body"]) .
		print("</textarea></td></tr>\n");
		print("<tr><td align=center><input type=submit value='���������������'></td></tr>\n");
		print("</table>\n");
		print("<input type=\"hidden\" name=\"returnto\" value=\"returnto\"/></form>\n");
		stdfoot();
		die;
	}
}

//   Other Actions and followup    ////////////////////////////////////////////

stdhead("������� $rgname");
if ($warning)
print("<p>($warning)</p>");
print("<form method=post name=rgnews action=\"".$REL_SEO->make_link('rgnews','action','add','id',$id)."\">\n");
print("<table border=1 cellspacing=0 cellpadding=5>\n");
print("<tr><td class=colhead>�������� ������� $rgname</td></tr>\n");
print("<tr><td>����: <input type=text name=subject maxlength=40 size=50 value=\"" . makesafe($arr["subject"]) . "\"/></td></tr>");
print("<tr><td style='padding: 0px'>");
print textbbcode("body",$arr["body"]);
//<textarea name=body cols=145 rows=5 style='border: 0px'>
print("</textarea></td></tr>\n");
print("<tr><td align=center><input type=submit value='{$REL_LANG->say_by_key('go')}' class=btn></td></tr>\n");
print("</table></form><br /><br />\n");

stdfoot();
die;
?>