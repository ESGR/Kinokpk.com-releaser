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
$REL_LANG->load('admincp');
httpauth();

stdhead($REL_LANG->say_by_key('panel_admin'));
begin_main_frame();


if (get_user_class() >= UC_SYSOP) {
	begin_frame($REL_LANG->say_by_key('1')); ?>
<table width=100% cellspacing=10 align=center>
	<tr>
		<td><a href="<?=$REL_SEO->make_link('siteonoff');?>">���������� ����������� / ���������� �����
		� �������� �������</a></td>
		<td><a href="<?=$REL_SEO->make_link('blocksadmin');?>">���������� �������</a></td>
		<td><a href="<?=$REL_SEO->make_link('templatesadmin');?>">��������� ������</a></td>
		<td><a href="<?=$REL_SEO->make_link('configadmin');?>"><b>�������� ���������</b></a></td>
	</tr>
	<tr>
		<td><a href="<?=$REL_SEO->make_link('spam');?>">�� ������������� :)</a></td>
		<td><a href="<?=$REL_SEO->make_link('category');?>">���������</a></td>
		<td><a href="<?=$REL_SEO->make_link('stampadmin');?>">������ � ������</a></td>
		<td><a href="<?=$REL_SEO->make_link('countryadmin');?>">������� ����� � ������</a></td>
	</tr>
	<tr>
		<td><a href="<?=$REL_SEO->make_link('retrackeradmin');?>">���������� ����������</a></td>
		<td><a href="<?=$REL_SEO->make_link('cronadmin');?>"><b>��������� cron-������� � ��������</b></a></td>
		<td colspan="2"><a href="<?=$REL_SEO->make_link('pagescategory');?>">��������� �������</a></td>
	</tr>
</table>
	<? end_frame();
}

if (get_user_class() >= UC_ADMINISTRATOR) { ?>
<? begin_frame($REL_LANG->say_by_key('2')); ?>
<table width=100% cellspacing=10 align=center>
	<tr>
		<td><a href="<?=$REL_SEO->make_link('unco');?>">�������. �����</a></td>
		<td><a href="<?=$REL_SEO->make_link('delacctadmin');?>">������� �����</a></td>
		<td><a href="<?=$REL_SEO->make_link('rgadmin');?>">�����-������</a></td>
		<td><a href="<?=$REL_SEO->make_link('bans');?>">����</a></td>
	</tr>
	<tr>
		<td><a href="<?=$REL_SEO->make_link('banemailadmin');?>">��� �������</a></td>
		<td><a href="<?=$REL_SEO->make_link('email');?>">�������� E-mail</a></td>
		<td><a href="<?=$REL_SEO->make_link('staffmess');?>">������� ��</a></td>
		<td><a href="<?=$REL_SEO->make_link('pollsadmin');?>">������</a></td>
	</tr>
	<tr>
		<td><a href="<?=$REL_SEO->make_link('mysqlstats');?>">���������� MySQL</a></td>
		<td><a href="<?=$REL_SEO->make_link('passwordadmin');?>">������� ������ ������</a></td>
		<td><a href="<?=$REL_SEO->make_link('clearcache');?>">������� �����</a></td>
		<td><a href="<?=$REL_SEO->make_link('reltemplatesadmin');?>">��������� �������� �������</a></td>
	</tr>
	<tr>
		<td colspan="4"><a href="<?=$REL_SEO->make_link('news');?>">�������� �������</a> | <a
			href="<?=$REL_SEO->make_link('newsarchive');?>">��� �������</a></td>

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
		<td><a href="<?=$REL_SEO->make_link('users','act','users');?>">������������ � ��������� ���� 0</a></td>
		<td><a href="<?=$REL_SEO->make_link('users','act','banned');?>">����������� ������������</a></td>
		<td><a href="<?=$REL_SEO->make_link('users','act','last');?>">����� ������������</a></td>
		<td><a href="<?=$REL_SEO->make_link('log');?>">��� �����</a></td>
	</tr>
	<tr>
		<td><a href="<?=$REL_SEO->make_link('warned');?>">�������. �����</a></td>
		<td><a href="<?=$REL_SEO->make_link('adduser');?>">�������� �����</a></td>
		<td><a href="<?=$REL_SEO->make_link('recover');?>">������. �����</a></td>
		<td><a href="<?=$REL_SEO->make_link('uploaders');?>">���������</a></td>
	</tr>
	<tr>
		<td colspan="4"><a href="<?=$REL_SEO->make_link('users');?>">������ ������</a></td>
	</tr>
	<tr>
		<td><a href="<?=$REL_SEO->make_link('stats');?>">����������</a></td>
		<td><a href="<?=$REL_SEO->make_link('testip');?>">�������� IP</a></td>
		<td><a href="<?=$REL_SEO->make_link('reports');?>">������</a></td>
		<td><a href="<?=$REL_SEO->make_link('ipcheck');?>">��������� IP</a></td>
	</tr>
	<tr>
		<td colspan="4" class=embedded>
		<form method=get action="<?=$REL_SEO->make_link('users')?>">�����: <input type=text size=30
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
		<td class=embedded><a href="<?=$REL_SEO->make_link('usersearch');?>">���������������� �����</a></td>
	</tr>
</table>

			<?php
			end_frame();
}
end_main_frame();
stdfoot();
?>