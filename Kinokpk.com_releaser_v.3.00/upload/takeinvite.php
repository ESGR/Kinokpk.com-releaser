<?

require_once("include/bittorrent.php");

dbconn();
getlang('takeinvite');
loggedinorreturn();

function bark($msg) {
	stdhead();
	stdmsg($tracker_lang['error'], $msg);
	stdfoot();
	die;
}

$id = (int) $_GET["id"];
if (!$id) $id = (int) $_POST['id'];
if (!$id)
stderr($tracker_lang['error'], $tracker_lang['invalid_id']);

if (get_user_class() <= UC_MODERATOR)
$id = $CURUSER["id"];

$hash  = md5(mt_rand(1, 1000000));
if ($CACHEARRAY['use_captcha']){

	require_once('include/recaptchalib.php');
	$resp = recaptcha_check_answer ($CACHEARRAY['re_privatekey'],
	$_SERVER["REMOTE_ADDR"],
	$_POST["recaptcha_challenge_field"],
	$_POST["recaptcha_response_field"]);

	if (!$resp->is_valid) {
		stderr($tracker_lang['error'], "��������� ��� ������������� ��������. <a href=\"javascript:history.go(-1);\">���������� ��� ���</a>");
	}

}
$email =  trim((string)$_POST['email']);
if (!validemail($email)) stderr($tracker_lang['error'],'Email ����� ������ �������');

$res = sql_query("SELECT 1 FROM users WHERE email='$email'");
$check = @mysql_result($res,0);
if ($check) stderr($tracker_lang['error'],'����� email ��� ���������������!');

$subject = "����������� �� {$CACHEARRAY['sitename']}";
$body = "��� ���� ��� ������� � ����� {$CURUSER['username']} ���������� ��� ������������������ �� {$CACHEARRAY['sitename']}<br/>
��� ����������� �������� �� ���� ������:
<a href=\"{$CACHEARRAY['defaultbaseurl']}/signup.php\">{$CACHEARRAY['defaultbaseurl']}/signup.php</a><br/>
����������� ��������� ��� �����������:<b>$hash</b><hr/>
������� �� ��������, � ��������� {$CACHEARRAY['sitename']}";

sql_query("INSERT INTO invites (inviter, invite, time_invited) VALUES (" . implode(", ", array_map("sqlesc", array($id, $hash, time()))) . ")") or sqlerr(__FILE__,__LINE__);

sql_query("INSERT INTO cron_emails (email, subject, body) VALUES (".sqlesc($email).",".sqlesc($subject).",".sqlesc($body).")") or sqlerr(__FILE__,__LINE__);

safe_redirect(" invite.php?id=$id");

?>