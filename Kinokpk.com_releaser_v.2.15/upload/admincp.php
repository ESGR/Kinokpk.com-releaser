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
stdhead("������ ��������������");
begin_main_frame();


 if (get_user_class() >= UC_SYSOP) {
begin_frame("����������� ���������<font color=#FF0000> - ����� ���. ���������������.</font>"); ?>
<table width=100% cellspacing=10 align=center>
<tr>
<td><a href="siteonoff.php">���������� ����������� / ���������� ����� � �������� �������</a></td>
<td><a href="blocksadmin.php">���������� �������</a></td>
<td><a href="templatesadmin.php">��������� ������</a></td>
<td><a href="configadmin.php">�������� �������� (config.php)</a></td>
</tr>
<tr>
<td><a href="spam.php">�� ������������� :)</a></td>
<td><a href="category.php">��������� / ����</a></td>
<td><a href="stampadmin.php">������ � ������</a></td>
<td><a href="descrtypesadmin.php">������� �������</a></td>
</tr>
</table>
<? end_frame();
}

if (get_user_class() >= UC_ADMINISTRATOR) { ?>
<? begin_frame("����������� ���������<font color=#009900> - ����� ���������������.</font>"); ?>
<table width=100% cellspacing=10 align=center>
<tr>
<td><a href="unco.php">�������. �����</a></td>
<td><a href="delacctadmin.php">������� �����</a></td>
<td><a href="javascript://" onClick="alert('Coming soon')">��� ��������</a></td>
<td><a href="bans.php">����</a></td>
</tr>
<tr>
<td><a href="topten.php">Top 10</a></td>
<td><a href="findnotconnectable.php">����� �� NAT</a></td>
<td><a href="email.php">�������� E-mail</a></td>
<td><a href="staffmess.php">������� ��</a></td>
</tr>
<tr>
<td><a href="pollsadmin.php">������</a></td>
<td><a href="faqadmin.php">�������� ����</a></td>
<td><a href="mysqlstats.php">���������� MySQL</a></td>
<td><a href="passwordadmin.php">������� ������ ������</a></td>
</tr>
<tr>
<td colspan="4"><a href="banemailadmin.php">��� �������</a></td>
</tr>
</table>
<? end_frame();
}

if (get_user_class() >= UC_MODERATOR) { ?>
<? begin_frame("�������� ��������� - <font color=#004E98>����� �����������.</font>"); ?>


<table width=100% cellspacing=3>
<tr>
<? if (get_user_class() >= UC_MODERATOR) { ?>
</tr>
<tr>
<td><a href="users.php?act=users">������������ � ��������� ���� 0.20</a></td>
<td><a href="users.php?act=banned">����������� ������������</a></td>
<td><a href="users.php?act=last">����� ������������</a></td>
<td><a href="log.php">��� �����</a></td>
</tr>
</table>

<? end_frame(); ?>
<br />
<? begin_frame("���������� � �������� - <font color=#004E98>����� �����������.</font>"); ?>

<br />
<table width=100% cellspacing=3>

</table>
<table width=100% cellspacing=10 align=center>
<tr>
<td><a href="warned.php">�������. �����</a></td>
<td><a href="adduser.php">�������� �����</a></td>
<td><a href="recover.php">������. �����</a></td>
<td><a href="uploaders.php">���������</a></td>
</tr>
<tr>
<td><a href="users.php">������ ������</a></td>
<td><a href="tags.php">����</a></td>
<td><a href="smilies.php">������</a></td>
</tr>
<tr>
<td><a href="stats.php">����������</a></td>
<td><a href="testip.php">�������� IP</a></td>
<td><a href="reports.php">������</a></td>
<td><a href="ipcheck.php">��������� IP</a></td>
</tr>
</table>
<br />

<? end_frame(); ?>

<? begin_frame("������ ������������ - <font color=#004E98>����� �����������.</font>"); ?>


<table width=100% cellspacing=3>
<tr>
<td class=embedded>
<form method=get action="users.php">
�����: <input type=text size=30 name=search>
<select name=class>
<option value='-'>(��������)</option>
<option value=0>������������</option>
<option value=1>������� ������������</option>
<option value=2>VIP</option>
<option value=3>����������</option>
<option value=4>���������</option>
<option value=5>�������������</option>
<option value=6>��������</option>
</select>
<input type=submit value='������'>
</form>
</td>
</tr>
<tr><td class=embedded><li><a href="usersearch.php">���������������� �����</li></a></td></tr>
</table>

<? end_frame(); ?>
<br />
<? if ($act == "users") {
begin_frame("������������ � ��������� ���� 0.20");

echo '<table width="100%" border="0" align="center" cellpadding="2" cellspacing="0">';
echo "<tr><td class=colhead align=left>������������</td><td class=colhead>�������</td><td class=colhead>IP</td><td class=colhead>���������������</td><td class=colhead>��������� ��� ��� �� �������</td><td class=colhead>������</td><td class=colhead>������</td></tr>";


$result = sql_query ("SELECT * FROM users WHERE uploaded / downloaded <= 0.20 AND enabled = 'yes' ORDER BY downloaded DESC ");
if ($row = mysql_fetch_array($result)) {
do {
if ($row["uploaded"] == "0") { $ratio = "inf"; }
elseif ($row["downloaded"] == "0") { $ratio = "inf"; }
$ratio = "<font color=" . get_ratio_color($ratio) . ">$ratio</font>";
echo "<tr><td><a href=userdetails.php?id=".$row["id"]."><b>".$row["username"]."</b></a></td><td><strong>".$ratio."</strong></td><td>".$row["ip"]."</td><td>".$row["added"]."</td><td>".$row["last_access"]."</td><td>".mksize($row["downloaded"])."</td><td>".mksize($row["uploaded"])."</td></tr>";


} while($row = mysql_fetch_array($result));
} else {print "<tr><td colspan=7>��������, ������� �� ����������!</td></tr>";}
echo "</table>";
end_frame(); }?>

<? if ($act == "last") {
begin_frame("��������� ������������");

echo '<table width="100%" border="0" align="center" cellpadding="2" cellspacing="0">';
echo "<tr><td class=colhead align=left>������������</td><td class=colhead>�������</td><td class=colhead>IP</td><td class=colhead>���������������</td><td class=colhead>���������&nbsp;���&nbsp;���&nbsp;��&nbsp;�������</td><td class=colhead>������</td><td class=colhead>������</td></tr>";

$result = sql_query ("SELECT * FROM users WHERE enabled = 'yes' AND status = 'confirmed' ORDER BY added DESC limit 100");
if ($row = mysql_fetch_array($result)) {
do {
if ($row["uploaded"] == "0") { $ratio = "inf"; }
elseif ($row["downloaded"] == "0") { $ratio = "inf"; }
else {
$ratio = number_format($row["uploaded"] / $row["downloaded"], 3);
$ratio = "<font color=" . get_ratio_color($ratio) . ">$ratio</font>";
}
echo "<tr><td><a href=userdetails.php?id=".$row["id"]."><b>".$row["username"]."</b></a></td><td><strong>".$ratio."</strong></td><td>".$row["ip"]."</td><td>".$row["added"]."</td><td>".$row["last_access"]."</td><td>".mksize($row["downloaded"])."</td><td>".mksize($row["uploaded"])."</td></tr>";


} while($row = mysql_fetch_array($result));
} else {print "<tr><td>Sorry, no records were found!</td></tr>";}
echo "</table>";
end_frame(); }?>


<? if ($act == "banned") {
begin_frame("��������� ������������");

echo '<table width="100%" border="0" align="center" cellpadding="2" cellspacing="0">';
echo "<tr><td class=colhead align=left>������������</td><td class=colhead>�������</td><td class=colhead>IP</td><td class=colhead>���������������</td><td class=colhead>��������� ��� ���</td><td class=colhead>������</td><td class=colhead>������</td></tr>";
$result = sql_query ("SELECT * FROM users WHERE enabled = 'no' ORDER BY last_access DESC ");
if ($row = mysql_fetch_array($result)) {
do {
if ($row["uploaded"] == "0") { $ratio = "inf"; }
elseif ($row["downloaded"] == "0") { $ratio = "inf"; }
else {
$ratio = number_format($row["uploaded"] / $row["downloaded"], 3);
$ratio = "<font color=" . get_ratio_color($ratio) . ">$ratio</font>";
}
echo "<tr><td><a href=userdetails.php?id=".$row["id"]."><b>".$row["username"]."</b></a></td><td><strong>".$ratio."</strong></td><td>".$row["ip"]."</td><td>".$row["added"]."</td><td>".$row["last_access"]."</td><td>".mksize($row["downloaded"])."</td><td>".mksize($row["uploaded"])."</td></tr>";


} while($row = mysql_fetch_array($result));
} else {print "<tr><td colspan=7>��������, ������� �� ����������!</td></tr>";}
echo "</table>";
end_frame(); } }

end_main_frame();
stdfoot();
}
?>