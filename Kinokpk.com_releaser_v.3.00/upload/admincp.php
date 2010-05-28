<?php
/**
 * Admin control panel frontend
 * @license GNU GPLv3 http://opensource.org/licenses/gpl-3.0.html
 * @package Kinokpk.com releaser
 * @author ZonD80 <admin@kinokpk.com>
 * @copyright (C) 2008-now, ZonD80, Germany, TorrentsBook.com
 * @link http://dev.kinokpk.com
 */

require "include/bittorrent.php";
dbconn();
loggedinorreturn();
getlang('admincp');
httpauth();

stdhead($tracker_lang['panel_admin']);
begin_main_frame();


if (get_user_class() >= UC_SYSOP) {
	begin_frame($tracker_lang['1']); ?>
<table width=100% cellspacing=10 align=center>
	<tr>
		<td><a href="siteonoff.php">���������� ����������� / ���������� �����
		� �������� �������</a></td>
		<td><a href="blocksadmin.php">���������� �������</a></td>
		<td><a href="templatesadmin.php">��������� ������</a></td>
		<td><a href="configadmin.php"><b>�������� ���������</b></a></td>
	</tr>
	<tr>
		<td><a href="spam.php">�� ������������� :)</a></td>
		<td><a href="category.php">���������</a></td>
		<td><a href="stampadmin.php">������ � ������</a></td>
		<td><a href="countryadmin.php">������� ����� � ������</a></td>
	</tr>
	<tr>
		<td><a href="retrackeradmin.php">���������� ����������</a></td>
		<td><a href="cronadmin.php"><b>��������� cron-������� � ��������</b></a></td>
		<td colspan="2"><a href="pagescategory.php">��������� �������</a></td>
	</tr>
</table>
	<? end_frame();
}

if (get_user_class() >= UC_ADMINISTRATOR) { ?>
<? begin_frame($tracker_lang['2']); ?>
<table width=100% cellspacing=10 align=center>
	<tr>
		<td><a href="unco.php">�������. �����</a></td>
		<td><a href="delacctadmin.php">������� �����</a></td>
		<td><a href="rgadmin.php">�����-������</a></td>
		<td><a href="bans.php">����</a></td>
	</tr>
	<tr>
		<td><a href="banemailadmin.php">��� �������</a></td>
		<td><a href="email.php">�������� E-mail</a></td>
		<td><a href="staffmess.php">������� ��</a></td>
		<td><a href="pollsadmin.php">������</a></td>
	</tr>
	<tr>
		<td><a href="mysqlstats.php">���������� MySQL</a></td>
		<td><a href="passwordadmin.php">������� ������ ������</a></td>
		<td><a href="clearcache.php">������� �����</a></td>
		<td><a href="faqadmin.php">��������� FAQ</a></td>
	</tr>
	<tr>
		<td><a href="rulesadmin.php">��������� ������</a></td>
		<td><a href="reltemplatesadmin.php">��������� �������� �������</a></td>
		<td colspan="2"><a href="news.php">�������� �������</a> | <a
			href="newsarchive.php">��� �������</a></td>

	</tr>
</table>
<? end_frame();
}

if (get_user_class() >= UC_MODERATOR) { ?>
<? begin_frame("�������� ��������� - <font color=#004E98>����� �����������.</font>"); ?>


<table width=100% cellspacing=3>
	<tr>
	</tr>
	<tr>
		<td><a href="users.php?act=users">������������ � ��������� ���� 0</a></td>
		<td><a href="users.php?act=banned">����������� ������������</a></td>
		<td><a href="users.php?act=last">����� ������������</a></td>
		<td><a href="log.php">��� �����</a></td>
	</tr>
	<tr>
		<td><a href="warned.php">�������. �����</a></td>
		<td><a href="adduser.php">�������� �����</a></td>
		<td><a href="recover.php">������. �����</a></td>
		<td><a href="uploaders.php">���������</a></td>
	</tr>
	<tr>
		<td colspan="4"><a href="users.php">������ ������</a></td>
	</tr>
	<tr>
		<td><a href="stats.php">����������</a></td>
		<td><a href="testip.php">�������� IP</a></td>
		<td><a href="reports.php">������</a></td>
		<td><a href="ipcheck.php">��������� IP</a></td>
	</tr>
	<tr>
		<td colspan="4" class=embedded>
		<form method=get action="users.php">�����: <input type=text size=30
			name=search> <select name=class>
			<option value='-'>(��������)</option>
			<?php
			for ($i=0;;$i++) {
				if ($s=get_user_class_name($i))
				print("<option value=\"$i\">$s</option>");
				else
				break;
			}
			?>
		</select> <input type=submit value='������'></form>
		</td>
	</tr>
	<tr>
		<td class=embedded><a href="usersearch.php">���������������� �����</a></td>
	</tr>
</table>

			<?php
			end_frame();
}
end_main_frame();
stdfoot();
?>