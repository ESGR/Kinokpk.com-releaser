<?php
/**
 * Configuration script (main)
 * @license GNU GPLv3 http://opensource.org/licenses/gpl-3.0.html
 * @package Kinokpk.com releaser
 * @author ZonD80 <admin@kinokpk.com>
 * @copyright (C) 2008-now, ZonD80, Germany, TorrentsBook.com
 * @link http://dev.kinokpk.com
 */

require "include/bittorrent.php";
dbconn();
loggedinorreturn();
if (get_user_class() < UC_SYSOP) stderr($REL_LANG->say_by_key('error'),$REL_LANG->say_by_key('access_denied'));
httpauth();

if (!isset($_GET['action'])){
	stdhead("�������� ���������");

	begin_frame("�������� ��������� Kinokpk.com releaser ".RELVERSION);
	print('<form action="'.$REL_SEO->make_link('configadmin','action','save').'" method="POST">');
	print('<table width="100%" border="1">');

	print('<tr><td align="center" colspan="2" class="colhead">�������� ���������</td></tr>');

	print('<tr><td>����� ����� (��� /):</td><td><input type="text" name="defaultbaseurl" size="30" value="'.$REL_CONFIG['defaultbaseurl'].'"> <br/>��������, "http://www.kinokpk.com"</td></tr>');
	print('<tr><td>�������� ����� (title):</td><td><input type="text" name="sitename" size="80" value="'.$REL_CONFIG['sitename'].'"> <br/>��������, "������� �������� �����������"</td></tr>');
	print('<tr><td>�������� ����� (meta description):</td><td><input type="text" name="description" size="80" value="'.$REL_CONFIG['description'].'"> <br/>��������, "����� ������� ���������� ������� ����"</td></tr>');
	print('<tr><td>�������� ����� (meta keywords):</td><td><input type="text" name="keywords" size="80" value="'.$REL_CONFIG['keywords'].'"> <br/>��������, "�������, ����������, ������, �������"</td></tr>');
	print('<tr><td>�����, � �������� ����� ������������ ��������� �����:</td><td><input type="text" name="siteemail" size="30" value="'.$REL_CONFIG['siteemail'].'"> <br/>��������, "bot@kinokpk.com"</td></tr>');
	print('<tr><td>����� ��� ����� � ���������������:</td><td><input type="text" name="adminemail" size="30" value="'.$REL_CONFIG['adminemail'].'"> <br/>��������, "admin@windows.lox"</td></tr>');
	print('<tr><td>���� �������� �� ��������� (��� lang_%����%):</td><td><input type="text" name="default_language" size="2" value="'.$REL_CONFIG['default_language'].'"></td></tr>');
	print('<tr><td>������������ ������� �������������� (��������� �� �������������):</td><td><select name="use_lang"><option value="1" '.($REL_CONFIG['use_lang']==1?"selected":"").'>��</option><option value="0" '.($REL_CONFIG['use_lang']==0?"selected":"").'>���</option> ����������� ������ ������ 2 ����� ����� (ru,en)</select></td></tr>');
	print('<tr><td>����������� ���� ��� ������ � ���������������� (themes/%����%):</td><td><input type="text" name="default_theme" size="10" value="'.$REL_CONFIG['default_theme'].'"> �� ��������� "kinokpk"</td></tr>');
	print('<tr><td>��� �������� ��� ����������� ����� ��������:<br /><small>*�� ������ ������������ ������ <b>{datenow}</b> ��� ������ �������� ����</small></td><td><input type="text" name="yourcopy" size="60" value="'.$REL_CONFIG['yourcopy'].'"> <br/>��������, "&copy; 2008-{datenow} ��� ����"</td></tr>');
	print('<tr><td>������������ ������� ������ (��������� �� �������������):</td><td><select name="use_blocks"><option value="1" '.($REL_CONFIG['use_blocks']==1?"selected":"").'>��</option><option value="0" '.($REL_CONFIG['use_blocks']==0?"selected":"").'>���</option></select></td></tr>');
	print('<tr><td>������������ gzip ������ ��� �������:</td><td><select name="use_gzip"><option value="1" '.($REL_CONFIG['use_gzip']==1?"selected":"").'>��</option><option value="0" '.($REL_CONFIG['use_gzip']==0?"selected":"").'>���</option></select></td></tr>');
	print('<tr><td>������������ ������� ����� �� IP/��������:</td><td><select name="use_ipbans"><option value="1" '.($REL_CONFIG['use_ipbans']==1?"selected":"").'>��</option><option value="0" '.($REL_CONFIG['use_ipbans']==0?"selected":"").'>���</option></select></td></tr>');
	print('<tr><td>��� SMTP:</td><td><input type="text" name="smtptype" size="10" value="'.$REL_CONFIG['smtptype'].'"></td></tr>');
	print('<tr><td>�������� ������ ����� � ��������:</td><td><select name="announce_packed"><option value="1" '.($REL_CONFIG['announce_packed']==1?"selected":"").'>��</option><option value="0" '.($REL_CONFIG['announce_packed']==0?"selected":"").'>���</option></select> �� ���������, ��</td></tr>');

	print('<tr><td align="center" colspan="2" class="colhead">��������� �����������</td></tr>');

	print('<tr><td>��������� �����������:</td><td><select name="deny_signup"><option value="1" '.($REL_CONFIG['deny_signup']==1?"selected":"").'>��</option><option value="0" '.($REL_CONFIG['deny_signup']==0?"selected":"").'>���</option></select></td></tr>');
	print('<tr><td>��������� ����������� �� ������������:</td><td><select name="allow_invite_signup"><option value="1" '.($REL_CONFIG['allow_invite_signup']==1?"selected":"").'>��</option><option value="0" '.($REL_CONFIG['allow_invite_signup']==0?"selected":"").'>���</option></select></td></tr>');
	print('<tr><td>��������� ���� ��� �����������:</td><td>'.list_timezones('register_timezone',$REL_CONFIG['register_timezone']).'</td></tr>');
	print('<tr><td>������������ ��������� ��������� �� e-mail:</td><td><select name="use_email_act"><option value="1" '.($REL_CONFIG['use_email_act']==1?"selected":"").'>��</option><option value="0" '.($REL_CONFIG['use_email_act']==0?"selected":"").'>���</option></select></td></tr>');
	print('<tr><td>������������ �����:<br /><small>*�� ������ ������������������ �� <a target="_blank" href="http://recaptcha.net">ReCaptcha.net</a> � �������� ��������� � ��������� ����� ��� ������������� ���� �����</small></td><td><select name="use_captcha"><option  value="1" '.($REL_CONFIG['use_captcha']==1?"selected":"").'>��</option><option value="0" '.($REL_CONFIG['use_captcha']==0?"selected":"").'>���</option></select></td></tr>');
	print('<tr><td>��������� ���� �����:</td><td><input type="text" name="re_publickey" size="80" value="'.$REL_CONFIG['re_publickey'].'"></td></tr>');
	print('<tr><td>��������� ���� �����:</td><td><input type="text" name="re_privatekey" size="80" value="'.$REL_CONFIG['re_privatekey'].'"></td></tr>');
	print('<tr><td>����������� ����������� (����.���� �/��� ��):</td><td><input type="text" name="default_notifs" size="120" value="'.$REL_CONFIG['default_notifs'].'"></td></tr>');
	print('<tr><td>����������� ����������� � Email:</td><td><input type="text" name="default_emailnotifs" size="120" value="'.$REL_CONFIG['default_emailnotifs'].'"></td></tr>');
	print('<tr><td colspan="2"><small>*��� ���� ����������� � Kinokpk.com releaser '.RELVERSION.':<br/>unread,torrents,comments,pollcomments,newscomments,usercomments,reqcomments,rgcomments,pages,pagecomments,friends,users,reports,unchecked ; ��������� - <a target="_blank" href="'.$REL_SEO->make_link('mynotifs','settings','').'">��������� ���� �����������</a></small></td></tr>');


	print('<tr><td align="center" colspan="2" class="colhead">��������� �����������</td></tr>');

	print('<tr><td>'.$REL_LANG->_("Comment hide rating").':</td><td><input type="text" name="low_comment_hide" size="4" value="'.$REL_CONFIG['low_comment_hide'].'">'.$REL_LANG->_('Points, after which post text will be replaced by "This post too bad"').'</td></tr>');	
	print('<tr><td>'.$REL_LANG->_("Maximal users signatures length").':</td><td><input type="text" name="sign_length" size="4" value="'.$REL_CONFIG['sign_length'].'">'.$REL_LANG->_("Characters").'</td></tr>');
	print('<tr><td>������������ ���������� �������������:</td><td><input type="text" name="maxusers" size="6" value="'.$REL_CONFIG['maxusers'].'">�������������, ������� 0 ��� ���������� ������</td></tr>');
	print('<tr><td>������������ ���������� ��������� � ������ �����:</td><td><input type="text" name="pm_max" size="4" value="'.$REL_CONFIG['pm_max'].'">���������</td></tr>');
	print('<tr><td>������������ ������ ������:</td><td><input type="text" name="avatar_max_width" size="3" value="'.$REL_CONFIG['avatar_max_width'].'">��������</td></tr>');
	print('<tr><td>������������ ������ ������:</td><td><input type="text" name="avatar_max_height" size="3" value="'.$REL_CONFIG['avatar_max_height'].'">��������</td></tr>');
	print('<tr><td>��������� ������������� DC-������:</td><td><select name="use_dc"><option value="1" '.($REL_CONFIG['use_dc']?"selected":"").'>��</option><option value="0" '.(!$REL_CONFIG['use_dc']?"selected":"").'>���</option></select></td></tr>');
	print('<tr><td>������������ ������ �������-����� � ������:</td><td><input type="text" name="max_torrent_size" size="10" value="'.$REL_CONFIG['max_torrent_size'].'">����</td></tr>');
	print('<tr><td>������������ ���������� �������� ��� ������:</td><td><input type="text" name="max_images" size="2" value="'.$REL_CONFIG['max_images'].'"><br/>��������, "2"</td></tr>');
	print('<tr><td>��������� adult �������:<br /><small>*����� �� ��������� ���������� ��������� "XXX �����", ������������ ����� �������� ����������� ���� ��������� � �������<br /><b>���� ��������� ������, ��� ����, ���������� �� ����� ������� <u>��� ��������</u></b></small></td><td><input type="text" name="pron_cats" size="60" value="'.$REL_CONFIG['pron_cats'].'"><br/>��������, "13,14"</td></tr>');

	print('<tr><td align="center" colspan="2" class="colhead">��������� ������������</td></tr>');

	print('<tr><td>����-�������� � ��������:</td><td><input type="text" name="as_timeout" size="10" value="'.$REL_CONFIG['as_timeout'].'">������</td></tr>');
	print('<tr><td>������������ �������� ��������� 5 ������������ (��������):</td><td><select name="as_check_messages"><option value="1" '.($REL_CONFIG['as_check_messages']==1?"selected":"").'>��</option><option value="0" '.($REL_CONFIG['as_check_messages']==0?"selected":"").'>���</option></select></td></tr>');
	print('<tr><td>�����-�����:</td><td><select name="debug_mode"><option value="1" '.($REL_CONFIG['debug_mode']==1?"selected":"").'>��</option><option value="0" '.($REL_CONFIG['debug_mode']==0?"selected":"").'>���</option></select></td></tr>');
	print('<tr><td>'.$REL_LANG->_("Language debug").':</td><td><select name="debug_language"><option value="1" '.($REL_CONFIG['debug_language']==1?"selected":"").'>'.$REL_LANG->_("Yes").'</option><option value="0" '.($REL_CONFIG['debug_language']==0?"selected":"").'>'.$REL_LANG->_("No").'</option></select> <a href="'.$REL_SEO->make_link('langadmin').'">'.$REL_LANG->_("Language administration tools").'</a></td></tr>');
	
	print('<tr><td align="center" colspan="2" class="colhead">������</td></tr>');

	print('<tr><td>����������� ������������� �������� ������� ������ � ���������.��:<br/><small>*�������� ������, ���� � �������� ������ ���� ������ ���� http://www.kinopoisk.ru/level/1/film/ID_������</small></td><td><select name="use_ttl"><option value="1" '.($REL_CONFIG['use_kinopoisk_trailers']==1?"selected":"").'>��</option><option value="0" '.($REL_CONFIG['use_kinopoisk_trailers']==0?"selected":"").'>���</option></select></td></tr>');
	print('<tr><td>���������� ������� � ������ ������� �� ��������:<br /><small>*��� ��������� ����� ��������� ���������� �������� ��� browse</small></td><td><input type="text" name="torrentsperpage" size="3" value="'.$REL_CONFIG['torrentsperpage'].'">�������</td></tr>');
	print('<tr><td>������������ TTL (���� �������� ������� ���������):</td><td><select name="use_ttl"><option value="1" '.($REL_CONFIG['use_ttl']==1?"selected":"").'>��</option><option value="0" '.($REL_CONFIG['use_ttl']==0?"selected":"").'>���</option></select></td></tr>');
	print('<tr><td>������������ ������� ����������� ������� �� �������:</td><td><select name="use_wait"><option value="1" '.($REL_CONFIG['use_wait']==1?"selected":"").'>��</option><option value="0" '.($REL_CONFIG['use_wait']==0?"selected":"").'>���</option></select></td></tr>');

	print('<tr><td align="center" colspan="2"><input type="submit" value="��������� ���������"><input type="reset" value="��������"></td></tr></table></form>');
	end_frame();
	stdfoot();

}

elseif ($_GET['action'] == 'save'){
	$reqparametres = array('torrentsperpage','maxusers','max_torrent_size','max_images','defaultbaseurl','siteemail','adminemail','sitename','description','keywords',
'yourcopy','pm_max','default_language',
'avatar_max_width','avatar_max_height','default_theme','use_dc','deny_signup','allow_invite_signup',
'use_ttl','use_email_act','use_wait','use_lang','use_captcha','use_blocks','use_gzip','use_ipbans','smtptype',
'as_timeout','as_check_messages','debug_mode','debug_language','announce_packed','pron_cats','register_timezone','low_comment_hide','sign_length');
	$captcha_param = array('re_publickey','re_privatekey');

	$updateset = array();

	foreach ($reqparametres as $param) {
		if (!isset($_POST[$param]) && ($param != 'pron_cats')) stderr($REL_LANG->say_by_key('error'),"��������� ���� �� ��������� ($param)");
		$updateset[] = "UPDATE cache_stats SET cache_value=".sqlesc($_POST[$param])." WHERE cache_name='$param'";
	}

	if ($_POST['use_captcha'] == 1) {
		foreach ($captcha_param as $param) {
			if (!$_POST[$param] || !isset($_POST[$param])) stderr($REL_LANG->say_by_key('error'),"��������� ��� ��������� ����� ����� �� ����������");
			$updateset[] = "UPDATE cache_stats SET cache_value=".sqlesc($_POST[$param])." WHERE cache_name='$param'";
		}
	}

	foreach ($updateset as $query) sql_query($query);

	$REL_CACHE->clearCache('system','config');

	safe_redirect($REL_SEO->make_link('configadmin'));

}

else stderr($REL_LANG->say_by_key('error'),"Unknown action");

?>