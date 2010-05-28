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

dbconn();

if ($CACHEARRAY['use_captcha']){

	require_once('include/recaptchalib.php');
	$resp = recaptcha_check_answer ($CACHEARRAY['re_privatekey'],
	$_SERVER["REMOTE_ADDR"],
	$_POST["recaptcha_challenge_field"],
	$_POST["recaptcha_response_field"]);

	if (!$resp->is_valid) {
		stderr($tracker_lang['error'], "�� �� ������ �������� �� ������������, ���������� ��� ���.");
	}

}

if ($CACHEARRAY['deny_signup'] && !$CACHEARRAY['allow_invite_signup'])
stderr($tracker_lang['error'], "��������, �� ����������� ��������� ��������������.");

if ($CURUSER)
stderr($tracker_lang['error'], sprintf($tracker_lang['signup_already_registered'], $CACHEARRAY['sitename']));

$users = get_row_count("users");
if ($users >= $CACHEARRAY['maxusers'])
stderr($tracker_lang['error'], sprintf($tracker_lang['signup_users_limit'], number_format($CACHEARRAY['maxusers'])));

if ($CACHEARRAY['deny_signup'] && $CACHEARRAY['allow_invite_signup']) {
	if (empty($_POST["invite"]))
	stderr($tracker_lang['error'], "��� ����������� ��� ����� ������ ��� �����������!");
}

if (!empty($_POST['invite'])) {
	if (strlen($_POST["invite"]) != 32)
	stderr($tracker_lang['error'], "�� ����� �� ���������� ��� �����������.");
	$invitecheck = sql_query("SELECT invites.inviter, invites.id, users.username, users.class FROM invites LEFT JOIN users ON inviter=users.id WHERE invite = ".sqlesc($_POST["invite"])) or sqlerr(__FILE__,__LINE__);
	list($inviter, $invid, $invname,$invclass) = @mysql_fetch_array($invitecheck);
	if (!$inviter)
	stderr($tracker_lang['error'], "��� ����������� ��������� ���� �� �������."); else
	list($invitedroot) = mysql_fetch_row(sql_query("SELECT invitedroot FROM users WHERE id = $inviter"));
}

function bark($msg) {
	global $tracker_lang;
	stderr($tracker_lang['error'], $msg, 'error');
	exit;
}

function barkdb($msg) {
	global $tracker_lang;
	relconn();
	stderr($tracker_lang['error'], $msg, 'error');
	exit;
}


if (!is_numeric($_POST["icq"]) && (intval($_POST["icq"]) > 999999999))
bark("����, ����� icq ������� ������� , ��� ��� �� ����� (���� - 999999999)");
$icq = (int) $_POST["icq"];

$msn = htmlspecialchars(unesc($_POST["msn"]));
if (strlen($msn) > 30)
bark("����, ��� msn ������� �������  (���� - 30)");

$aim = unesc($_POST["aim"]);
if (strlen($aim) > 30)
bark("����, ��� aim ������� �������  (���� - 30)");

$yahoo = htmlspecialchars(unesc($_POST["yahoo"]));
if (strlen($yahoo) > 30)
bark("����, ��� yahoo ������� �������  (���� - 30)");

$mirc = htmlspecialchars(unesc($_POST["mirc"]));
if (strlen($mirc) > 30)
bark("����, ��� mirc ������� �������  (���� - 30)");


$skype = htmlspecialchars(unesc($_POST["skype"]));
if (strlen($skype) > 20)
bark("����, ��� skype ������� �������  (���� - 20)");

$website = htmlspecialchars(unesc($_POST["website"]));
if (strlen($website) > 50)
bark("����, ����� ������ ����� ������� �������  (���� - 50)");

$wantusername = unesc($_POST["wantusername"]);
$email = unesc($_POST["email"]);

if (empty($wantusername) || empty($_POST['wantpassword']) || empty($email) || !is_valid_id($_POST["gender"]) || !is_valid_id($_POST["country"]))
bark("��� ���� ����������� ��� ����������.");

$gender = unesc($_POST["gender"]);
$country = unesc($_POST["country"]);

if (strlen($wantusername) > 12)
bark("��������, ��� ������������ ������� ������� (�������� 12 ��������)");

if ($_POST['wantpassword'] != $_POST['passagain'])
bark("������ �� ���������! ������ �� ��������. ���������� ���.");

if (strlen($_POST['wantpassword']) < 6)
bark("��������, ������ ������� ������� (������� 6 ��������)");

if (strlen($_POST['wantpassword']) > 40)
bark("��������, ������ ������� ������� (�������� 40 ��������)");

if ($_POST['wantpassword'] == $wantusername)
bark("��������, ������ �� ����� ���� �����-�� ��� ��� ������������.");

if (!validemail($email))
bark("��� �� ������ �� �������� email �����.");

if (!validusername($wantusername))
bark("�������� ��� ������������.");

if (!is_valid_id($_POST['year']) || !is_valid_id($_POST['month']) || !is_valid_id($_POST['day']))
stderr($tracker_lang['error'],"������ �� ������� �������� ���� ��������");

$year = (int)$_POST['year'];
$month = (int)$_POST['month'];
$day = (int)$_POST['day'];
$birthday = @date("$year.$month.$day");

// make sure user agrees to everything...
if (!$_POST["rulesverify"] || !$_POST["faqverify"] || !$_POST["ageverify"])
stderr($tracker_lang['error'], "��������, �� �� ��������� ��� ���� ���-�� ����� ������ ����� �����.");

// check if email addy is already in use
$a = (@mysql_fetch_row(@sql_query("SELECT COUNT(*) FROM users WHERE email=".sqlesc($email)))) or die(mysql_error());
if ($a[0] != 0)
bark("E-mail ����� $email ��� ��������������� � �������.");

check_banned_emails($email);

if ($CACHEARRAY['use_integration']) {
	// check IPB email and USER ///////////////////////////////////////////////////////////////////
	////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

	// connecting to IPB DB
	forumconn();
	//connection opened

	$a = (@mysql_fetch_row(@sql_query("SELECT COUNT(*) FROM ".$fprefix."members_converge WHERE converge_email=".sqlesc($email)))) or die(mysql_error());
	if ($a[0] != 0)
	barkdb("E-mail ����� $email ��� ��������������� �� ������ {$CACHEARRAY['forumname']}.");

	$a = (@mysql_fetch_row(@sql_query("SELECT COUNT(*) FROM ".$fprefix."members WHERE name=".sqlesc($wantusername)))) or die(mysql_error());
	if ($a[0] != 0)
	barkdb("������������ � ����� $wantusername ��� ��������������� �� ������ {$CACHEARRAY['forumname']}.");

	// closing IPB DB connection
	relconn();
	// connection closed

	/////////////////////////////////////////////////////////////////
}

$ip = getip();

if (isset($_COOKIE["uid"]) && is_numeric($_COOKIE["uid"]) && $users) {
	$cid = intval($_COOKIE["uid"]);
	$c = sql_query("SELECT enabled FROM users WHERE id = $cid ORDER BY id DESC LIMIT 1");
	$co = @mysql_fetch_row($c);
	if (!$co[0]) {
		sql_query("UPDATE users SET ip = '$ip', last_access = ".time()." WHERE id = $cid");
		bark("�� ��� ��������������� �� {$CACHEARRAY['defaultbaseurl']}");
	} else
	bark("�� ��� ��������������� �� {$CACHEARRAY['defaultbaseurl']}");
}

$secret = mksecret();
$wantpasshash = md5($secret . $_POST['wantpassword'] . $secret);
$editsecret = (!$users?"":mksecret());

if ((!$users) || (!$CACHEARRAY['use_email_act'] == true))
$status = 1;
else
$status = 0;

$ret = sql_query("INSERT INTO users (username, passhash, secret, editsecret, gender, country, icq, msn, aim, yahoo, skype, mirc, website, email, confirmed, ". (!$users?"class, ":"") ."added, birthday, language, invitedby, invitedroot) VALUES (" .
implode(",", array_map("sqlesc", array($wantusername, $wantpasshash, $secret, $editsecret, $gender, $country, $icq, $msn, $aim, $yahoo, $skype, $mirc, $website, strtolower($email), $status))).
		", ". (!$users?UC_SYSOP.", ":"").time().", ".sqlesc($birthday).", '{$CACHEARRAY['default_language']}', ".(int)$inviter.", ".(int)$invitedroot.")");// or sqlerr(__FILE__, __LINE__);

if (!$ret) {
	if (mysql_errno() == 1062)
	bark("������������ $wantusername ��� ��������������� �� {$CACHEARRAY['sitename']}!");
	bark("����������� ������. ����� �� ������� mySQL: ".htmlspecialchars(mysql_error()));
}

$id = mysql_insert_id();

if ($inviter) {
	sql_query("UPDATE invites SET inviteid=$id WHERE inviter=$inviter AND id=$invid") or sqlerr(__FILE__,__LINE__);
	write_sys_msg($id,sprintf($tracker_lang['invite_notice'],"<a href=\"userdetails.php?id=$inviter\">".get_user_class_color($invclass,$invname)."</a>"),$tracker_lang['welcome_back'].strip_tags($wantusername));
	write_sys_msg($inviter,sprintf($tracker_lang['invite_notice_reg'],"<a href=\"userdetails.php?id=$id\">".get_user_class_color(UC_USER,strip_tags($wantusername))."</a>"),$tracker_lang['invite_system']);
}

register_ipb_user($wantusername,$_POST['wantpassword'], $email, $gender, $year, $month, $day, $aim, $icq, $website, $yahoo, $msn);

write_log("��������������� ����� ������������ $wantusername","FFFFFF","tracker");
if ($CACHEARRAY['use_integration']) {
	write_log("��������������� ����� ������������ �� ������ {$CACHEARRAY['forumname']}  $wantusername","9693FF","tracker");
}


$psecret = md5($editsecret);

$body = <<<EOD
�� ������������������ �� {$CACHEARRAY['sitename']} � ������� ���� ����� ��� �������� ($email).

���� ��� ���� �� ��, ��������� �������������� ��� ������. ������� ������� ����� ��� E-Mail ������ ����� IP ����� {$_SERVER["REMOTE_ADDR"]}. ���������, �� ���������.

��� ������������� ����� �����������, ��� ����� ������ �� ��������� ������:

{$CACHEARRAY['defaultbaseurl']}/confirm.php?id=$id&secret=$psecret

����� ���� ��� �� ��� ��������, �� ������� ������������ ��� �������. ���� �� ����� �� ��������,
 ��� ����� ������� ����� ������ ����� ���� ����. �� ����������� ��� ��������� �������
� ���� ������ ��� �� ������� ������������ {$CACHEARRAY['sitename']}.
EOD;

if($CACHEARRAY['use_email_act'] && $users) {
	if (!sent_mail($email,$CACHEARRAY['sitename'],$CACHEARRAY['siteemail'],"������������� ����������� �� {$CACHEARRAY['sitename']}",$body)) {
		stderr($tracker_lang['error'], "���������� ��������� E-Mail. ���������� �����");
	}
} else {
	logincookie($id, $wantpasshash, $CACHEARRAY['default_language']);
}
header("Refresh: 0; url=ok.php?type=". (!$users?"sysop":("signup&email=" . urlencode($email))));

?>