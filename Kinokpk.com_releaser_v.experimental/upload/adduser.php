<?php
/**
 * Admin adds user
 * @license GNU GPLv3 http://opensource.org/licenses/gpl-3.0.html
 * @package Kinokpk.com releaser
 * @author ZonD80 <admin@kinokpk.com>
 * @copyright (C) 2008-now, ZonD80, Germany, TorrentsBook.com
 * @link http://dev.kinokpk.com
 */

require "include/bittorrent.php";
dbconn();

loggedinorreturn();
httpauth();

if (get_user_class() < UC_MODERATOR)
stderr($REL_LANG->say_by_key('error'), $REL_LANG->say_by_key('access_denied'));
if ($_SERVER["REQUEST_METHOD"] == "POST")
{
	if ($_POST["username"] == "" || $_POST["password"] == "" || $_POST["email"] == "")
	stderr($REL_LANG->say_by_key('error'), $REL_LANG->say_by_key('missing_form_data'));
	if ($_POST["password"] != $_POST["password2"])
	stderr($REL_LANG->say_by_key('error'), $REL_LANG->say_by_key('password_mismatch'));
	$username = sqlesc(htmlspecialchars((string)$_POST["username"]));
	$password = (string)$_POST["password"];
	$email = sqlesc(htmlspecialchars(trim((string)$_POST["email"])));
	$secret = mksecret();
	$passhash = sqlesc(md5($secret . $password . $secret));
	$secret = sqlesc($secret);

	sql_query("INSERT INTO users (added, last_access, secret, username, passhash, notifs, emailnotifs, confirmed, email) VALUES(".time().", ".time().", $secret, $username, $passhash, '{$REL_CONFIG['default_notifs']}', '{$REL_CONFIG['default_emailnotifs']}', 1, $email)");
	if (mysql_errno()==1062)
	stderr($REL_LANG->say_by_key('error'), $REL_LANG->say_by_key('unable_to_create_account'));
	$id = mysql_insert_id();
	safe_redirect("../".$REL_SEO->make_link('userdetails','id',$id,'username',$username));
	die;
}
stdhead($REL_LANG->say_by_key('add_user'));
?>
<h1><?=$REL_LANG->say_by_key('add_user');?></h1>
<form method=post action="<?=$REL_SEO->make_link('adduser')?>">
<table border=1 cellspacing=0 cellpadding=5>
	<tr>
		<td class=rowhead><?=$REL_LANG->say_by_key('username');?></td>
		<td><input type=text name=username size=40></td>
	</tr>
	<tr>
		<td class=rowhead><?=$REL_LANG->say_by_key('password');?></td>
		<td><input type=password name=password size=40></td>
	</tr>
	<tr>
		<td class=rowhead><?=$REL_LANG->say_by_key('repeat_password');?></td>
		<td><input type=password name=password2 size=40></td>
	</tr>
	<tr>
		<td class=rowhead>E-mail</td>
		<td><input type=text name=email size=40></td>
	</tr>
	<tr>
		<td colspan=2 align=center><input type=submit value="OK" class=btn></td>
	</tr>
</table>
</form>
<? stdfoot(); ?>