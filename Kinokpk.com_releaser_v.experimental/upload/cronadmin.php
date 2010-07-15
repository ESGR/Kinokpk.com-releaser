<?php
/**
 * CRONJOB administration
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


$cronrow = sql_query("SELECT * FROM cron");

while ($cronres = mysql_fetch_array($cronrow)) $CRON[$cronres['cron_name']] = $cronres['cron_value'];

stdhead('��������� cron-�������');

if (!isset($_POST['save']) && !isset($_POST['reset']) && !isset($_POST['recount'])){

	begin_frame("��������� cron-�������");
	print('<form action="'.$REL_SEO->make_link('cronadmin').'" method="POST">');
	print('<table width="100%" border="1">');

	if ($CRON['in_remotecheck'] && $CRON['remotecheck_disabled']) $remotecheck_state .= '<font color="red">������ �� ��������� �����, �� ������ ��� �����������. ��������� ����������</font>';
	if (!$CRON['in_remotecheck'] && $CRON['remotecheck_disabled']) $remotecheck_state .= '<font color="green">������� �����������</font>';
	if ($CRON['in_remotecheck'] && !$CRON['remotecheck_disabled']) $remotecheck_state .= '<font color="green">������� ��������</font>';
	if (!$CRON['in_remotecheck'] && !$CRON['remotecheck_disabled']) $remotecheck_state .= '<font color="green">������� � ������ ��������</font>';

	print('<tr><td align="center" colspan="2" class="colhead">��������� ��������������� ����� | �� <a href="'.$REL_SEO->make_link('retrackeradmin').'">� ���������� ����������</a></td></tr>');
	print('<tr><td>��������� ������� ��������� ��������� �����:<br /><small>*��� ��� ��� ������� ����������� � ������� ������, �� �� ���������� ����� ������������� ��������� �����. ����� �� �������� ������� ������� ��������� ������.</small></td><td><select name="remotecheck_disabled"><option value="1" '.($CRON['remotecheck_disabled']==1?"selected":"").'>��</option><option value="0" '.($CRON['remotecheck_disabled']==0?"selected":"").'>���</option></select> '.$remotecheck_state .'</td></tr>');
	print('<tr><td>����� ������������ ��������� �����:<br /><small>*����� N ������ �������� �������� �� �������� ������.</small></td><td><input type="text" name="remotepeers_cleantime" size="3" value="'.$CRON['remotepeers_cleantime'].'"> <b>������</b></td></tr>');
	print('<tr><td>������� �������� ��������� �� ���:<br/><small>*�� ������� ��������, ����� ��� torrentsbook.com, ���������� ���������� ���������� ����������� ��������. ��� <b>����</b> ����� ��������� ��� �������������� �������</small></td><td><input type="text" name="remote_trackers" size="5" value="'.$CRON['remote_trackers'].'">��������</td></tr>');
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
	print('<tr><td>���������� ��������, ���������� � ������������ �� ���������� ������:</td><td><input type="text" size="3" name="rating_perdownload" value="'.$CRON['rating_perdownload'].'"></td></tr>');
	print('<tr><td>����� ������� ���������� ���������:</td><td><input type="text" size="4" name="rating_downlimit" value="'.$CRON['rating_downlimit'].'"></td></tr>');
	print('<tr><td>����� ���������� ��������:</td><td><input type="text" size="4" name="rating_dislimit" value="'.$CRON['rating_dislimit'].'"></td></tr>');
	print('<tr><td>������������ ���������� ��������:</td><td><input type="text" size="4" name="rating_max" value="'.$CRON['rating_max'].'"></td></tr>');
	print('<tr><td>������� ������ �������� ����� 1 ������� ������:</td><td><input type="text" size="2" name="rating_discounttorrent" value="'.$CRON['rating_discounttorrent'].'"></td></tr>');


	print('<tr><td align="center" colspan="2" class="colhead">������ ���������</td></tr>');
	print('<tr><td>�������� �������� (���������� ���������� � ��������):</td><td><input type="text" size="5" name="announce_interval" value="'.$CRON['announce_interval'].'">�����</td></tr>');
	print('<tr><td>�������� ������� ������ � ���������� �����/��������:<br /><small>*����� ���������� ������� ������������ ������ �������� ������� ��� ���.<br />*������ �������� �� ����� ���� ������ ��������� �������, ����������, ����� ��� ���� ������ ���.<br />*�������� ���� ������, ��� 0, ���� ������, ����� ������� ����������� ��������</td><td><input type="text" size="3" name="delete_votes" value="'.$CRON['delete_votes'].'">�����</td></tr>');

	print('<tr><td align="center" colspan="2"><input type="submit" name="save" value="��������� ���������"><input type="reset" value="��������"><input type="submit" name="reset" value="�������� ���������� cron"><input type="submit" name="recount" value="���������������� �������� � ��"></td></tr>
<tr><td colspan="2"><small>*����� ���������� cron ���������, ���� ������� ������� ���������� ��������� cron-�������, ������� �� ����������� �������� cron ������ ����� <a href="http://httpd.apache.org/docs/2.0/mod/mod_status.html">mod_status</a> ��� apache</small></td></tr></table></form>');
	end_frame();
}
elseif (isset($_POST['recount'])) {
	do {

		$res = sql_query("SELECT id, filename FROM torrents") or sqlerr(__FILE__,__LINE__);
		$ar = array();
		while ($row = mysql_fetch_array($res)) {
			$id = $row[0];
			$ar[$id] = 1;
			$far[$id] = $row[1];
		}

		if (!count($ar))
		break;

		$dp = @opendir(ROOT_PATH."torrents");
		if (!$dp)
		break;

		$ar2 = array();
		while (($file = @readdir($dp)) !== false) {
			if (!preg_match('/^(\d+)\.torrent$/', $file, $m))
			continue;
			$id = $m[1];
			$ar2[$id] = 1;
			if (isset($ar[$id]) && $ar[$id])
			continue;
			$ff = ROOT_PATH.'torrents/'.$file;
			@unlink($ff);
		}
		@closedir($dp);

		if (!count($ar2))
		break;

		$delids = array();
		foreach (array_keys($ar) as $k) {
			if (isset($ar2[$k]) && $ar2[$k])
			continue;
			if ($far[$k] != 'nofile')
			$delids[] = $k;
			unset($ar[$k]);
		}
		if ($delids)
		foreach ($delids as $did) deletetorrent($did);
	} while (0);
	safe_redirect($REL_SEO->make_link('cronadmin'),3);
	stdmsg($REL_LANG->say_by_key('success'),sprintf($REL_LANG->say_by_key('torrent_recounted'),count($delids)));
}

elseif (isset($_POST['reset'])) {
	sql_query("UPDATE cron SET cron_value=0 WHERE cron_name IN ('last_cleanup','last_remotecheck','in_cleanup','in_remotecheck','num_cleaned','num_checked','remote_lastchecked')");
	stdmsg($REL_LANG->say_by_key('success'),$REL_LANG->say_by_key('cron_state_reseted'));
}
elseif (isset($_POST['save'])) {

	$reqparametres = array('max_dead_torrent_time','signup_timeout','autoclean_interval','pm_delete_sys_days','pm_delete_user_days','ttl_days','remotecheck_disabled','announce_interval','delete_votes','remote_trackers','rating_enabled','remotecheck_interval');

	$multi_param = array('remotepeers_cleantime');

	$rating_param = array('rating_freetime','rating_perseed','rating_perinvite','rating_perrequest','rating_checktime','rating_perrelease','rating_dislimit','rating_downlimit', 'rating_perleech', 'rating_perdownload', 'rating_discounttorrent','rating_max');
	$updateset = array();

	foreach ($reqparametres as $param) {
		if (!isset($_POST[$param]) && (($param != 'rating_enabled') || ($param != 'delete_votes') || ($param != 'remote_trackers')))  { stdmsg($REL_LANG->say_by_key('error'),"��������� ���� �� ��������� ($param)",'error'); stdfoot(); die; }
		$updateset[] = "UPDATE cron SET cron_value=".sqlesc($_POST[$param])." WHERE cron_name='$param'";
	}

	if ($_POST['remotecheck_disabled'] == 0) {
		foreach ($multi_param as $param) {
			if (!$_POST[$param] || !isset($_POST[$param])) { stdmsg($REL_LANG->say_by_key('error'),"��������� ���� ��� ����������������� �� ���������",'error'); stdfoot(); die; }
			$updateset[] = "UPDATE cron SET cron_value=".sqlesc($_POST[$param])." WHERE cron_name='$param'";
		}
	}

	if ($_POST['rating_enabled']) {
		foreach ($rating_param as $param) {
			if (!$_POST[$param] || !isset($_POST[$param])) { stdmsg($REL_LANG->say_by_key('error'),"��������� ���� ��� ����������� ������� �� ���������",'error'); stdfoot(); die; }
			$updateset[] = "UPDATE cron SET cron_value=".sqlesc($_POST[$param])." WHERE cron_name='$param'";
		}
	}

	foreach ($updateset as $query) sql_query($query);
	safe_redirect($REL_SEO->make_link('cronadmin'),3);
	stdmsg($REL_LANG->say_by_key('success'),$REL_LANG->say_by_key('cron_settings_saved'));
}
begin_frame('������� ��������� cron:');
print ('<table width="100%"><tr><td>');
if (!$CRON['in_cleanup']) print $REL_LANG->say_by_key('cleanup_not_running').'<br />';
if (!$CRON['in_remotecheck']) print $REL_LANG->say_by_key('remotecheck_not_running').'<br />';
print sprintf($REL_LANG->say_by_key('num_cleaned'),$CRON['num_cleaned'])."<br />";
print sprintf($REL_LANG->say_by_key('num_checked'),$CRON['num_checked'])."<br />";
print $REL_LANG->say_by_key('last_cleanup').' '.mkprettytime($CRON['last_cleanup'],true,true)." (".get_elapsed_time($CRON['last_cleanup'])." {$REL_LANG->say_by_key('ago')})<br />";
print $REL_LANG->say_by_key('last_remotecheck').' '.mkprettytime($CRON['last_remotecheck'],true,true)." (".get_elapsed_time($CRON['last_remotecheck'])." {$REL_LANG->say_by_key('ago')})<br />";
print ('</td></tr></table>');
end_frame();
stdfoot();

?>