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

require "include/bittorrent.php";
dbconn();
loggedinorreturn();
gzip();
if (get_user_class() < UC_SYSOP) stderr($tracker_lang['error'],$tracker_lang['access_denied']);
httpauth();

getlang('cronadmin');
$cronrow = mysql_query("SELECT * FROM cron");

while ($cronres = mysql_fetch_array($cronrow)) $CRON[$cronres['cron_name']] = $cronres['cron_value'];

stdhead('��������� cron-�������');

if (!isset($_POST['save']) && !isset($_POST['reset'])){

	begin_frame("��������� cron-�������");
	print('<table width="100%" border="1">');
	print('<form action="cronadmin.php" method="POST">');

	if ($CRON['in_remotecheck'] && $CRON['remotecheck_disabled']) $remotecheck_state .= '<font color="red">������ �� ��������� �����, �� ������ ��� �����������. ��������� ����������</font>';
	if (!$CRON['in_remotecheck'] && $CRON['remotecheck_disabled']) $remotecheck_state .= '<font color="green">������� �����������</font>';
	if ($CRON['in_remotecheck'] && !$CRON['remotecheck_disabled']) $remotecheck_state .= '<font color="green">������� ��������</font>';
	if (!$CRON['in_remotecheck'] && !$CRON['remotecheck_disabled']) $remotecheck_state .= '<font color="green">������� � ������ ��������</font>';

	print('<tr><td align="center" colspan="2" class="colhead">��������� ��������������� ����� | �� <a href="retrackeradmin.php">� ���������� ����������</a></td></tr>');
	print('<tr><td>��������� ������� ��������� ��������� �����:<br /><small>*��� ��� ��� ������� ����������� � ������� ������, �� �� ���������� ����� ������������� ��������� �����. ����� �� �������� ������� ������� ��������� ������.</small></td><td><select name="remotecheck_disabled"><option value="1" '.($CRON['remotecheck_disabled']==1?"selected":"").'>��</option><option value="0" '.($CRON['remotecheck_disabled']==0?"selected":"").'>���</option></select> '.$remotecheck_state .'</td></tr>');
	print('<tr><td>�������� ����� ��������� � ��������:<br /><small>*������ N ������ ������ ����� ��������� � ������� ������ ����� ���� ��������������� ��������� ��� ��������� ���������� ����� � ����� �������� �� � ����� ����������.<br />
��� ������ �������� - ��� ������ ����������, ��� ������ - ��� ������ �������� �� ������ � �������.</small></td><td><input type="text" name="remotepeers_cleantime" size="3" value="'.$CRON['remotepeers_cleantime'].'"> ������</td></tr>');
	print('<tr><td>������� ��������� ��������� �� ���:<br/><small>*�� ������� ��������, ����� ��� torrentsbook.com, ���������� ���������� ���������� ����������� ���������.</small></td><td><input type="text" name="remote_torrents" size="5" value="'.$CRON['remote_torrents'].'">���������</td></tr>');
	print('<tr><td align="center" colspan="2"><small>����� ����� ���������� ��������� ����� = (�������� ����� ��������� * ���-�� �������� �� ���)</small></td></tr>');

	print('<tr><td align="center" colspan="2" class="colhead">��������� �������</td></tr>');

	print('<tr><td>���������� ����, �� ���������� ������� ��������� ���������������� ��������:</td><td><input type="text" name="signup_timeout" size="2" value="'.$CRON['signup_timeout'].'">����</td></tr>');
	print('<tr><td>����� � ���, ����� ������� ������� ��������� �������:</td><td><input type="text" name="max_dead_torrent_time" size="3" value="'.$CRON['max_dead_torrent_time'].'">������</td></tr>');
	print('<tr><td>����� ������� �� � ��������:</td><td><input type="text" name="autoclean_interval" size="4" value="'.$CRON['autoclean_interval'].'">������</td></tr>');
	print('<tr><td>���������� ���� ��� ������� ������ ��������� �� �������:</td><td><input type="text" name="pm_delete_sys_days" size="2" value="'.$CRON['pm_delete_sys_days'].'">����</td></tr>');
	print('<tr><td>���������� ���� ��� ������� ������ ��������� �� ������������:</td><td><input type="text" name="pm_delete_user_days" size="2" value="'.$CRON['pm_delete_user_days'].'">����</td></tr>');
	print('<tr><td>����� ����� �������� �������� � ����:</td><td><input type="text" name="ttl_days" size="3" value="'.$CRON['ttl_days'].'">����</td></tr>');

	print('<tr><td align="center" colspan="2" class="colhead">������ ���������</td></tr>');
	print('<tr><td>������� ������������� ������ ������� � ��� ��� ����������� 1 ��������:</td><td><input type="text" name="points_per_hour" size="2" value="'.$CRON['points_per_hour'].'">�������</td></tr>');
	print('<tr><td>�������� �������� (���������� ���������� � ��������):</td><td><input type="text" size="5" name="announce_interval" value="'.$CRON['announce_interval'].'">�����</td></tr>');
	print('<tr><td>�������� ������� ������ � ���������� �����/��������:<br /><small>*����� ���������� ������� ������������ ������ �������� ������� ��� ���.<br />*������ �������� �� ����� ���� ������ ��������� �������, ����������, ����� ��� ���� ������ ���.<br />*�������� ���� ������, ��� 0, ���� ������, ����� ������� ����������� ��������</td><td><input type="text" size="3" name="delete_votes" value="'.$CRON['delete_votes'].'">�����</td></tr>');

	print('<tr><td align="center" colspan="2"><input type="submit" name="save" value="��������� ���������"><input type="reset" value="��������"><input type="submit" name="reset" value="�������� ���������� cron"></td></tr>
<tr><td colspan="2"><small>*����� ���������� cron ���������, ���� ������� ������� ���������� ��������� cron-�������, ������� �� ����������� �������� cron ������ ����� <a href="http://httpd.apache.org/docs/2.0/mod/mod_status.html">mod_status</a> ��� apache</small></td></tr></table>');
	end_frame();
}
elseif (isset($_POST['reset'])) {
	sql_query("UPDATE cron SET cron_value=0 WHERE cron_name IN ('last_cleanup','last_remotecheck','in_cleanup','in_remotecheck','num_cleaned','num_checked','remote_lastchecked')");
	stdmsg($tracker_lang['success'],$tracker_lang['cron_state_reseted']);
}
elseif (isset($_POST['save'])) {

	$reqparametres = array('max_dead_torrent_time','signup_timeout','autoclean_interval','pm_delete_sys_days','pm_delete_user_days','ttl_days','points_per_hour','remotecheck_disabled','announce_interval','delete_votes','remote_torrents');

	$multi_param = array('remotepeers_cleantime');

	$updateset = array();

	foreach ($reqparametres as $param) {
		if (!isset($_POST[$param]) && (($param != 'bonus_per_hour') || ($param != 'delete_votes')))  { stdmsg($tracker_lang['error'],"��������� ���� �� ��������� ($param)",'error'); stdfoot(); die; }
		$updateset[] = "UPDATE cron SET cron_value=".sqlesc($_POST[$param])." WHERE cron_name='$param'";
	}

	$multi_param = array('remotepeers_cleantime');
	if ($_POST['remotecheck_disabled'] == 0) {
		foreach ($multi_param as $param) {
			if (!$_POST[$param] || !isset($_POST[$param])) { stdmsg($tracker_lang['error'],"��������� ���� ��� ����������������� �� ���������",'error'); stdfoot(); die; }
			$updateset[] = "UPDATE cron SET cron_value=".sqlesc($_POST[$param])." WHERE cron_name='$param'";
		}
	}

	foreach ($updateset as $query) sql_query($query);

	stdmsg($tracker_lang['success'],$tracker_lang['cron_settings_saved']);
}
begin_frame('������� ��������� cron:');
print ('<table width="100%"><tr><td>');
if (!$CRON['in_cleanup']) print $tracker_lang['cleanup_not_running'].'<br />';
if (!$CRON['in_remotecheck']) print $tracker_lang['remotecheck_not_running'].'<br />';
print sprintf($tracker_lang['num_cleaned'],$CRON['num_cleaned'])."<br />";
print sprintf($tracker_lang['num_checked'],$CRON['num_checked'])."<br />";
print $tracker_lang['last_cleanup'].' '.mkprettytime($CRON['last_cleanup'],true,true)." (".get_elapsed_time($CRON['last_cleanup'])." {$tracker_lang['ago']})<br />";
print $tracker_lang['last_remotecheck'].' '.mkprettytime($CRON['last_remotecheck'],true,true)." (".get_elapsed_time($CRON['last_remotecheck'])." {$tracker_lang['ago']})<br />";
print ('</td></tr></table>');
end_frame();
stdfoot();

?>