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

require_once "include/bittorrent.php";

dbconn();
getlang('invite');
gzip();

loggedinorreturn();

if (isset($_GET['id']) && !is_valid_id($_GET['id'])) stderr($tracker_lang['error'],$tracker_lang['invalid_id']);

$id = (int) $_GET["id"];
$type = unesc($_GET["type"]);
$invite = $_GET["invite"];

stdhead("�����������");

function bark($msg) {
	stdmsg("������", $msg);
	stdfoot();
}

if ($id == 0) {
	$id = $CURUSER["id"];
}

$res = sql_query("SELECT invites FROM users WHERE id = $id") or sqlerr(__FILE__,__LINE__);

$inv = mysql_fetch_assoc($res);

if ($inv["invites"] != 1) {
	$_s = "���";
} else {
	$_s = "��";
}

if ($type == 'new') {
	print("<form method=get action=takeinvite.php>".
	"<input type=hidden name=id value=$id />".
	"<table border=1 width=100% cellspacing=0 cellpadding=5>".
	"<tr class=tabletitle><td colspan=2><b>������� ��������������� ��� (�������� $inv[invites] ��������$_s)</b></td></tr>".
	"<tr class=tableb><td align=center colspan=2>{$tracker_lang['invite_notice']}</td></tr>".
	"<tr class=tableb><td align=center colspan=2><input type=submit value=\"�������\"></td></tr>".
	"</form></table>");
} elseif ($type == 'del') {
	$ret = sql_query("SELECT * FROM invites WHERE invite = ".sqlesc($invite)) or sqlerr(__FILE__,__LINE__);
	$num = mysql_fetch_assoc($ret);
	if ($num[inviter]==$id) {
		sql_query("DELETE FROM invites WHERE invite = ".sqlesc($invite)) or sqlerr(__FILE__,__LINE__);
		sql_query("UPDATE users SET invites = invites + 1 WHERE id = $CURUSER[id]") or sqlerr(__FILE__,__LINE__);
		stdmsg("�������", "����������� �������.");
	} else
	stdmsg("������", "��� �� ��������� ������� �����������.");
} else {
	if (get_user_class() <= UC_UPLOADER && !($id == $CURUSER["id"])) {
		bark("� ��� ��� ����� ������ ����������� ����� ������������.");
	}

	$rel = sql_query("SELECT SUM(1) FROM users WHERE invitedby = $id") or sqlerr(__FILE__,__LINE__);
	$arro = mysql_fetch_row($rel);
	$number = $arro[0];

	$ret = sql_query("SELECT u.id, u.username, u.class, u.email, u.uploaded, u.downloaded, u.warned, u.enabled, u.donor, u.email, invites.confirmed FROM invites LEFT JOIN users AS u ON u.id=invites.inviteid WHERE invitedby = $id") or sqlerr(__FILE__,__LINE__);
	$num = mysql_num_rows($ret);

	print("<form method=post action=takeconfirm.php?id=$id><table border=1 width=100% cellspacing=0 cellpadding=5>".
	"<tr class=tabletitle><td colspan=7><b>������ ������������ ����</b> ($number)</td></tr>");

	if(!$num) {
		print("<tr class=tableb><td colspan=7>��� ����� ���� �� ���������.</tr>");
	} else {
		print("<tr class=tableb><td><b>������������</b></td><td><b>Email</b></td><td><b>������</b></td><td><b>������</b></td><td><b>�������</b></td><td><b>������</b></td>");
		if ($CURUSER[id] == $id || get_user_class() >= UC_MODERATOR)
		print("<td align=center><b>�����������</b></td>");
		print("</tr>");
		for ($i = 0; $i < $num; ++$i) {
			$arr = mysql_fetch_assoc($ret);
			if (!$arr[confirmed])
			$user = "<td align=left>$arr[username]</td>";
			else
			$user = "<td align=left><a href=userdetails.php?id=$arr[id]>" . get_user_class_color($arr["class"], "$arr[username]") . "</a>" . ($arr["warned"] ? "&nbsp;<img src=pic/warned.gif border=0 alt='Warned'>" : "") . (!$arr["enabled"] ? "&nbsp;<img src=pic/disabled.gif border=0 alt='Disabled'>" : "") . ($arr["donor"] ? "&nbsp;<img src=pic/star.gif border=0 alt='Donor'>" : "")."</td>";
			if ($arr["downloaded"] > 0) {
				$ratio = number_format($arr["uploaded"] / $arr["downloaded"], 3);
				$ratio = "<font color=" . get_ratio_color($ratio) . ">$ratio</font>";
			} else {
				if ($arr["uploaded"] > 0) {
					$ratio = "Inf.";
				} else {
					$ratio = "---";
				}
			}
			if ($arr["confirmed"])
			$status = "<a href=userdetails.php?id=$arr[id]><font color=green>�����������</font></a>";
			else
			$status = "<font color=red>�� �����������</font>";

			print("<tr class=tableb>$user<td>$arr[email]</td><td>" . mksize($arr[uploaded]) . "</td><td>" . mksize($arr[downloaded]) . "</td><td>$ratio</td><td>$status</td>");

			if ($CURUSER[id] == $id || get_user_class() >= UC_SYSOP) {
				print("<td align=center>");
				if (!$arr[confirmed])
				print("<input type=\"checkbox\" name=\"conusr[]\" value=\"" . $arr[id] . "\" />");
				print("</td>");
			}
			print("</tr>");
		}
	}
	if ($CURUSER[id] == $id || get_user_class() >= UC_SYSOP) {
		print("<input type=hidden name=email value=$arr[email]>");
		print("<tr class=tableb><td colspan=7 align=right><input type=submit value=\"����������� �������������\"></form></td></tr>");
	}
	print("</table><br />");

	$rul = sql_query("SELECT COUNT(*) FROM invites WHERE inviter = $id") or sqlerr(__FILE__,__LINE__);
	$arre = mysql_fetch_row($rul);
	$number1 = $arre[0];
	$rer = sql_query("SELECT invite, time_invited FROM invites WHERE inviter = $id AND confirmed=0") or sqlerr(__FILE__,__LINE__);
	$num1 = mysql_num_rows($rer);

	print("<table border=1 width=100% cellspacing=0 cellpadding=5>".
	"<tr class=tabletitle><td colspan=6><b>������ �������� �����������</b> ($number1)</td></tr>");

	if(!$num1) {
		print("<tr class=tableb><td colspan=6>�� ������ ������ ���� �� ������� �������� �����������.</tr>");
	} else {
		print("<tr class=tableb><td><b>��� �����������</b></td><td><b>���� ��������</b></td><td>�������</td></tr>");
		for ($i = 0; $i < $num1; ++$i) {
			$arr1 = mysql_fetch_assoc($rer);
			print("<tr class=tableb><td>$arr1[invite]</td><td>".mkprettytime($arr1['time_invited'])."</td>");
			print ("<td><a href=\"invite.php?invite=$arr1[invite]&type=del\" onClick=\"return confirm('�� �������?')\">������� �����������</a></td></tr>");
		}
	}

	print("<tr class=tableb><td colspan=7 align=center><form method=get action=invite.php?id=$id&type=new><input type='hidden' name='id' value='$id' /><input type='hidden' name='type' value='new' /><input type=submit value=\"������� �����������\"></form></td></tr>");
	print("</table>");
}
stdfoot();

?>