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

require_once("include/bittorrent.php");

dbconn();
loggedinorreturn();
gzip();

if (get_user_class() < UC_SYSOP) stderr($tracker_lang['error'],$tracker_lang['access_denied']);

httpauth();

define("NO_TINYMCE",true);

$allowed_modules = array(
	"admincp" => "�������",
	"browse" => "�����",
	"staff" => "��������",
	"upload" => "���������",
	"details" => "������",
	"my" => "������ �����.",
	"userdetails" => "�������",
	"viewrequests" => "�������",
	"log" => "������",
	"faq" => "����",
	"rules" => "�������",
	"message" => "�����",
	"recover" => "�������. ������",
	"signup" => "�����������",
	"login" => "����",
	"mybonus" => "��� �����",
	"mywarned" => "��� ��������������",
  "testport" => "��������� NAT/����",
  "users" => "������������",
  "topten" => "��� 10",
  "email-gateway" => "������� �����",
  "peers" => "���������� �����",
  "copyrights" => "���������� � ��������",
  "online" => "�� �� ������",
  "newsoverview" => "����� �������",
  "newsarchive" => "����� ��������",
  "news" => "������� ��������",
  "polloverview" => "����� ������",
  "pollsarchive" => "����� �������",
	"invite" => "�����������",
	"bookmarks" => "��������",
	"pages" => "��������"
);

function BlocksNavi() {
	stdhead("������������� ������� �������");
	echo "<h2>���������� �������</h2><br />"
	."[ <a href=\"blocksadmin.php\">�������</a>"
	." | <a href=\"blocksadmin.php?op=BlocksNew\">�������� ����� ����</a>"
	." ]";
}

function BlocksAdmin() {
	global $CACHE;
	BlocksNavi();
	echo "<p /><table border=\"0\" cellpadding=\"3\" cellspacing=\"1\" width=\"100%\"><tr align=\"center\">"
	."<td class=\"colhead\">�</td><td class=\"colhead\">���������</td><td class=\"colhead\">�������</td><td colspan=\"2\" class=\"colhead\">���������</td><td class=\"colhead\">���</td><td class=\"colhead\">������</td><td class=\"colhead\">��� �����</td><td class=\"colhead\">�������</td></tr>";

	$result = sql_query("SELECT a.bid, a.bkey, a.title, a.bposition, a.weight, a.active, a.blockfile, a.view, a.expire, a.action, b.bid, b.bposition, b.weight, c.bid, c.bposition, c.weight FROM orbital_blocks AS a LEFT JOIN orbital_blocks AS b ON (b.bposition = a.bposition AND b.weight = a.weight-1) LEFT JOIN orbital_blocks AS c ON (c.bposition = a.bposition AND c.weight = a.weight+1) ORDER BY a.bposition, a.weight") or sqlerr(__FILE__,__LINE__);
	while (list($bid, $bkey, $title, $bposition, $weight, $active, $blockfile, $view, $expire, $action, $con1, $bposition1, $weight1, $con2, $bposition2, $weight2) = mysql_fetch_row($result)) {
		if (($expire && $expire < time()) || (!$active && $expire)) {
			if ($action == "d") {
				sql_query("UPDATE orbital_blocks SET active=0, expire=0 WHERE bid=$bid");

				$CACHE->clearGroupCache('blocks');
			} elseif ($action == "r") {
				sql_query("DELETE FROM orbital_blocks WHERE bid=$bid");

				$CACHE->clearGroupCache('blocks');
			}
		}
		$weight_minus = $weight - 1;
		$weight_plus = $weight + 1;
		echo "<tr><td align=\"center\">$bid</td><td>$title</td>";
		if ($bposition == "l") {
			$bposition = "<img src=\"pic/admin/left.gif\" border=\"0\" alt=\"����� ����\" title=\"����� ����\"> �����";
		} elseif ($bposition == "r") {
			$bposition = "������ <img src=\"pic/admin/right.gif\" border=\"0\" alt=\"������ ����\" title=\"������ ����\">";
		} elseif ($bposition == "c") {
			$bposition = "<img src=\"pic/admin/right.gif\" border=\"0\" alt=\"����������� ����\" title=\"����������� ����\">&nbsp;�� ������ ������&nbsp;<img src=\"pic/admin/left.gif\" border=\"0\" alt=\"����������� ����\" title=\"����������� ����\">";
		} elseif ($bposition == "d") {
			$bposition = "<img src=\"pic/admin/right.gif\" border=\"0\" alt=\"����������� ����\" title=\"����������� ����\">&nbsp;�� ������ �����&nbsp;<img src=\"pic/admin/left.gif\" border=\"0\" alt=\"����������� ����\" title=\"����������� ����\">";
		} elseif ($bposition == "b") {
			$bposition = "<img src=\"pic/admin/up.gif\" border=\"0\" alt=\"������\" title=\"������\">&nbsp;������� ������&nbsp;<img src=\"pic/admin/up.gif\" border=\"0\" alt=\"������\" title=\"������\">";
		} elseif ($bposition == "f") {
			$bposition = "<img src=\"pic/admin/down.gif\" border=\"0\" alt=\"������\" title=\"������\">&nbsp;������ ������&nbsp;<img src=\"pic/admin/down.gif\" border=\"0\" alt=\"������\" title=\"������\">";
		}
		echo "<td align=\"center\"><nobr>$bposition</nobr></td><td align=\"center\">$weight</td><td align=\"center\">";
		if ($con1) echo "<a href=\"blocksadmin.php?op=BlocksOrder&weight=$weight&bidori=$bid&weightrep=$weight_minus&bidrep=$con1\"><img src=\"pic/admin/up.gif\" alt=\"����������� �����\" title=\"����������� �����\" border=\"0\"></a> ";
		if ($con2) echo "<a href=\"blocksadmin.php?op=BlocksOrder&weight=$weight&bidori=$bid&weightrep=$weight_plus&bidrep=$con2\"><img src=\"pic/admin/down.gif\" alt=\"����������� ����\" title=\"����������� ����\" border=\"0\"></a>";
		echo"</td>";
		if ($bkey == "") {
			$type = "HTML";
			if ($blockfile != "") $type = "����";
		} elseif ($bkey != "") {
			$type = "���������";
		}
		echo "<td align=\"center\">$type</td>";
		$block_act = $active;
		if ($active == 1) {
			$active = "<font color=\"#009900\">���.</font>";
			$change = "title=\"����.\"><img src=\"pic/admin/inactive.gif\" border=\"0\" alt=\"����.\"></a>";
		} elseif ($active == 0) {
			$active = "<font color=\"#FF0000\">����.</font>";
			$change = "title=\"���.\"><img src=\"pic/admin/activate.gif\" border=\"0\" alt=\"���.\"></a>";
		}
		echo "<td align=\"center\">$active</td>";
		if ($view == 0) {
			$who_view = "��� ����������";
		} elseif ($view == 1) {
			$who_view = "������ ������������";
		} elseif ($view == 2) {
			$who_view = "������ ��������������";
		} elseif ($view == 3) {
			$who_view = "������ �������";
		}
		echo "<td align=\"center\"><nobr>$who_view</nobr></td>";
		echo "<td align=\"center\"><a href=\"blocksadmin.php?op=BlocksEdit&bid=$bid\" title=\"�������������\"><img src=\"pic/admin/edit.gif\" border=\"0\" alt=\"�������������\"></a> <a href=\"blocksadmin.php?op=BlocksChange&bid=$bid\" $change";
		if ($bkey == "") echo " <a href=\"blocksadmin.php?op=BlocksDelete&bid=$bid\" OnClick=\"return DelCheck(this, '������� &quot;$title&quot;?');\" title=\"�������\"><img src=\"pic/admin/delete.gif\" border=\"0\" alt=\"�������\"></a>";
		if ($block_act == 0) echo " <a href=\"blocksadmin.php?op=BlocksShow&bid=$bid\" title=\"��������\"><img src=\"pic/admin/show.gif\" border=\"0\" alt=\"��������\"></a>";
	}
	if (mysql_num_rows($result) == 0)
	echo "<tr><td colspan=\"9\">��� ������.";
	echo "</td></tr></table>";

}

function BlocksNew() {

	BlocksNavi();
	echo "<h2>�������� ����� ����</h2>"
	."<form action=\"blocksadmin.php\" method=\"post\">"
	."<table border=\"0\" align=\"center\">"
	."<tr><td>���������:</td><td><input type=\"text\" name=\"title\" size=\"65\" style=\"width:400px\" maxlength=\"60\"></td></tr>"
	."<tr><td>��� �����:</td><td>"
	."<select name=\"blockfile\" style=\"width:400px\">"
	."<option name=\"blockfile\" value=\"\" selected>���</option>";
	$handle = opendir("blocks");
	while ($file = readdir($handle)) {
		if (preg_match("/^block\-(.+)\.php/", $file, $matches)) {
			$found = str_replace("_", " ", $matches[1]);
			print "<option value=\"$file\">$found</option>\n";
		}
	}
	closedir($handle);
	echo "</select></td></tr>"
	."<tr><td>����������:<br/><a href=\"javascript:mcejs();\">�������� TinyMCE</a></td><td>".textbbcode("content")."</td></tr>"
	."<tr><td>�������:</td><td><select name=\"bposition\" style=\"width:400px\">"
	."<option name=\"bposition\" value=\"l\">�����</option>"
	."<option name=\"bposition\" value=\"c\">�� ������ ������</option>"
	."<option name=\"bposition\" value=\"d\">�� ������ �����</option>"
	."<option name=\"bposition\" value=\"r\">������</option>"
	."<option name=\"bposition\" value=\"b\">������� ������</option>"
	."<option name=\"bposition\" value=\"f\">������ ������</option>"
	."</select></td></tr>";
	echo "<tr><td>���������� ���� � �������:</td><td align=\"center\"><table border=\"0\" cellpadding=\"3\" cellspacing=\"0\" align=\"center\" style=\"width:400px\"><tr>";
	echo "<td><input type=\"checkbox\" name=\"blockwhere[]\" value=\"ihome\"></td><td>�������</td>";
	global $allowed_modules;
	$a = 1;
	foreach ($allowed_modules as $name => $title) {
		$i++;
		$title = str_replace("_", " ", $title);
		echo "<td><input type=\"checkbox\" name=\"blockwhere[]\" value=\"".$name."\"></td><td>$title</td>";
		if ($a == 2) {
			echo "</tr><tr>";
			$a = 0;
		} else {
			$a++;
		}
	}
	echo "</tr><tr><td><input type=\"checkbox\" name=\"blockwhere[]\" value=\"all\"></td><td><b>�� ���� �������</b></td><td><input type=\"checkbox\" name=\"blockwhere[]\" value=\"ihome\"></td><td><b>������ �� �������</b></td><td><input type=\"checkbox\" name=\"blockwhere[]\" value=\"infly\"></td><td><b>��������� ����</b></td></tr></table></td></tr>";
	echo "<tr><td>��������?</td><td><input type=\"radio\" name=\"active\" value=\"1\" checked>�� &nbsp;&nbsp; <input type=\"radio\" name=\"active\" value=\"0\">���</td></tr>"
	."<tr><td>����� ������, � ����:</td><td><input type=\"text\" name=\"expire\" maxlength=\"3\" value=\"0\" size=\"65\" style=\"width:400px\"></td></tr>"
	."<tr><td>����� ���������:</td><td><select name=\"action\" style=\"width:400px\">"
	."<option name=\"action\" value=\"d\">����.</option>"
	."<option name=\"action\" value=\"r\">�������</option></select></td></tr>"
	."<tr><td>��� ��� ����� ������?</td><td><select name=\"view\" style=\"width:400px\">"
	."<option value=\"0\" >��� ����������</option>"
	."<option value=\"1\" >������ ������������</option>"
	."<option value=\"2\" >������ ��������������</option>"
	."<option value=\"3\" >������ �������</option>"
	."</select></td></tr>"
	."<tr><td colspan=\"2\" align=\"center\"><br /><input type=\"hidden\" name=\"op\" value=\"BlocksAdd\"><input type=\"submit\" value=\"������� ����\"></td></tr></table></form>";
}

function BlocksOrder($weightrep,$weight,$bidrep,$bidori) {
	global $CACHE;
	$result = sql_query("UPDATE orbital_blocks SET weight='$weight' WHERE bid='$bidrep'");
	$result2 = sql_query("UPDATE orbital_blocks SET weight='$weightrep' WHERE bid='$bidori'");

	$CACHE->clearGroupCache('blocks');
	Header("Location: blocksadmin.php");
}

function BlocksAdd($title, $content, $bposition, $active, $blockfile, $view, $expire, $action) {
	global $CACHE;
	list($weight) = mysql_fetch_row(sql_query("SELECT weight FROM orbital_blocks WHERE bposition=".sqlesc($bposition)." ORDER BY weight DESC"));
	$weight++;
	$bkey = "";
	$btime = "";
	if ($blockfile != "") {
		if ($title == "") {
			$title = str_replace("block-", "", $blockfile);
			$title = str_replace(".php", "", $title);
			$title = str_replace("_", " ", $title);
		}
	}

	if (($content == "") && ($blockfile == "")) {
		stdmsg("������", "���� �� ����� ���� ������!", 'error');
	} else {
		if ($expire == "" || $expire == 0) {
			$expire = 0;
		} else {
			$expire = time() + ($expire * 86400);
		}
		if (isset($_POST['blockwhere'])) {
			$blockwhere = $_POST['blockwhere'];
			$which = "";
			$which = (in_array("all", $blockwhere)) ? "all" : $which;
			$which = (in_array("home", $blockwhere)) ? "home" : $which;
			if ($which == "") {
				while(list($key, $val) = each($blockwhere)) {
					$which .= "{$val},";
				}
			}
		}
		sql_query("INSERT INTO orbital_blocks VALUES (NULL, ".implode(", ", array_map("sqlesc", array($bkey, $title, $content, $bposition, $weight, $active, $btime, $blockfile, $view, $expire, $action, $which))).")") or sqlerr(__FILE__,__LINE__);

		$CACHE->clearGroupCache('blocks');
		Header("Location: blocksadmin.php");
	}
}

function BlocksEdit($bid) {

	BlocksNavi();
	$bid = intval($bid);
	list($bkey, $title, $content, $bposition, $weight, $active, $blockfile, $view, $expire, $action, $which) = mysql_fetch_row(sql_query("SELECT bkey, title, content, bposition, weight, active, blockfile, view, expire, action, which FROM orbital_blocks WHERE bid='$bid'"));
	if ($blockfile != "") {
		$type = "(�������� ����)";
	} else {
		$type = "(HTML ����)";
	}
	echo "<h2>����: $title $type</h2>"
	."<form action=\"blocksadmin.php\" method=\"post\">"
	."<table border=\"0\" align=\"center\">"
	."<tr><td>���������:</td><td><input type=\"text\" name=\"title\" maxlength=\"50\" size=\"65\" style=\"width:400px\" value=\"$title\"></td></tr>";

	echo "<tr><td>��� �����:</td><td><select name=\"blockfile\" style=\"width:400px\"><option name=\"blockfile\" value=\"\" selected>���</option>";
	$dir = opendir("blocks");
	while ($file = readdir($dir)) {
		if (preg_match("/^block\-(.+)\.php/", $file, $matches)) {
			$found = str_replace("_", " ", $matches[1]);
			$selected = ($blockfile == $file) ? "selected" : "";
			echo "<option value=\"$file\" $selected>".$found."</option>";
		}
	}
	closedir($dir);

	echo "<tr><td>����������:<br/><a href=\"javascript:mcejs();\">�������� TinyMCE</a></td><td>".textbbcode("content",$content)."</td></tr>";

	$oldposition = $bposition;
	echo "<input type=\"hidden\" name=\"oldposition\" value=\"$oldposition\">";
	$sel1 = ($bposition == "l") ? "selected" : "";
	$sel2 = ($bposition == "c") ? "selected" : "";
	$sel3 = ($bposition == "r") ? "selected" : "";
	$sel4 = ($bposition == "d") ? "selected" : "";
	$sel5 = ($bposition == "b") ? "selected" : "";
	$sel6 = ($bposition == "f") ? "selected" : "";
	echo "<tr><td>�������:</td><td><select name=\"bposition\" style=\"width:400px\">"
	."<option name=\"bposition\" value=\"l\" $sel1>�����</option>"
	."<option name=\"bposition\" value=\"c\" $sel2>�� ������ ������</option>"
	."<option name=\"bposition\" value=\"d\" $sel4>�� ������ �����</option>"
	."<option name=\"bposition\" value=\"r\" $sel3>������</option>"
	."<option name=\"bposition\" value=\"b\" $sel5>������� ������</option>"
	."<option name=\"bposition\" value=\"f\" $sel6>������ ������</option>"
	."</select></td></tr>";
	echo "<tr><td>���������� ���� � �������:</td><td align=\"center\"><table border=\"0\" cellpadding=\"3\" cellspacing=\"0\" align=\"center\" style=\"width:400px\"><tr>";
	$where_mas = explode(",", $which);
	$cel = ($where_mas[0] == "ihome") ? " checked" : "";
	echo "<td><input type=\"checkbox\" name=\"blockwhere[]\" value=\"ihome\"$cel></td><td>�������</td>";
	global $allowed_modules;
	$a = 1;
	foreach ($allowed_modules as $name => $title) {
		$i++;
		$cel = "";
		foreach ($where_mas as $key => $val) {
			if ($val == $name) $cel = " checked";
		}
		$title = str_replace("_", " ", $title);
		echo "<td><input type=\"checkbox\" name=\"blockwhere[]\" value=\"".$name."\"$cel></td><td>$title</td>";
		if ($a == 2) {
			echo "</tr><tr>";
			$a = 0;
		} else {
			$a++;
		}
	}
	$where_mas = explode(",", $which);
	$cel = "";
	$hel = "";
	switch ($where_mas[0]) {
		case "all":
			$cel = " checked";
			break;
		case "home":
			$hel = " checked";
			break;
		case "infly":
			$fel = " checked";
			break;
	}
	echo "</tr><tr><td><input type=\"checkbox\" name=\"blockwhere[]\" value=\"all\"$cel></td><td><b>�� ���� �������</b></td><td><input type=\"checkbox\" name=\"blockwhere[]\" value=\"home\"$hel></td><td><b>������ �� �������</b></td><td><input type=\"checkbox\" name=\"blockwhere[]\" value=\"infly\"$fel></td><td><b>��������� ����</b></td></tr></table></td></tr>";
	$sel1 = ($active == 1) ? "checked" : "";
	$sel2 = ($active == 0) ? "checked" : "";
	if ($expire != 0) {
		$newexpire = 0;
		$oldexpire = $expire;
		$expire = intval(($expire - time()) / 3600);
		$exp_day = $expire / 24;
		$expire_text = "<input type=\"hidden\" name=\"expire\" value=\"$oldexpire\">��������: $expire ���� (".substr($exp_day,0,5)." ����)";
	} else {
		$newexpire = 1;
		$expire_text = "<input type=\"text\" name=\"expire\" value=\"0\" maxlength=\"3\" size=\"65\" style=\"width:400px\">";
	}
	$selact1 = ($action == "d") ? "selected" : "";
	$selact2 = ($action == "r") ? "selected" : "";
	echo "<tr><td>��������?</td><td><input type=\"radio\" name=\"active\" value=\"1\" $sel1>�� &nbsp;&nbsp;"
	."<input type=\"radio\" name=\"active\" value=\"0\" $sel2>���</td></tr>"
	."<tr><td>����� ������, � ����:</td><td>$expire_text</td></tr>"
	."<tr><td>����� ���������:</td><td><select name=\"action\" style=\"width:400px\">"
	."<option name=\"action\" value=\"d\" $selact1>����.</option>"
	."<option name=\"action\" value=\"r\" $selact2>�������</option></select></td></tr>";
	$sel1 = ($view == 0) ? "selected" : "";
	$sel2 = ($view == 1) ? "selected" : "";
	$sel3 = ($view == 2) ? "selected" : "";
	$sel4 = ($view == 3) ? "selected" : "";
	echo "</td></tr><tr><td>��� ��� ����� ������?</td><td><select name=\"view\" style=\"width:400px\">"
	."<option value=\"0\" $sel1>��� ����������</option>"
	."<option value=\"1\" $sel2>������ ������������</option>"
	."<option value=\"2\" $sel3>������ ��������������</option>"
	."<option value=\"3\" $sel4>������ �������</option>"
	."</select></td></tr></table><br />"
	."<center><input type=\"hidden\" name=\"bid\" value=\"$bid\">"
	."<input type=\"hidden\" name=\"newexpire\" value=\"$newexpire\">"
	."<input type=\"hidden\" name=\"bkey\" value=\"$bkey\">"
	."<input type=\"hidden\" name=\"weight\" value=\"$weight\">"
	."<input type=\"hidden\" name=\"op\" value=\"BlocksEditSave\">"
	."<input type=\"submit\" value=\"���������\"></form></center>";
}

function BlocksEditSave($newexpire, $bid, $bkey, $title, $content, $oldposition, $bposition, $active, $weight, $blockfile, $view, $expire, $action) {
	global $CACHE;
	if (isset($_POST['blockwhere'])) {
		$blockwhere = $_POST['blockwhere'];
		$which = "";
		$which = (in_array("all", $blockwhere)) ? "all" : $which;
		$which = (in_array("home", $blockwhere)) ? "home" : $which;
		if ($which == "") {
			print $which;
			while(list($key, $val) = each($blockwhere)) {
				$which .= "{$val},";
			}
		}
		sql_query("UPDATE orbital_blocks SET which=".sqlesc($which)." WHERE bid=".sqlesc($bid));
	} else {
		sql_query("UPDATE orbital_blocks SET which='' WHERE bid=".sqlesc($bid));
	}
	if ($oldposition != $bposition) {
		$result5 = sql_query("SELECT bid FROM orbital_blocks WHERE weight>=".sqlesc($weight)." AND bposition=".sqlesc($bposition));
		$fweight = $weight;
		$oweight = $weight;
		while (list($nbid) = mysql_fetch_row($result5)) {
			$weight++;
			sql_query("UPDATE orbital_blocks SET weight=".sqlesc($weight)." WHERE bid=".sqlesc($nbid)) or sqlerr(__FILE__,__LINE__);
		}
		$result6 = sql_query("SELECT bid FROM orbital_blocks WHERE weight>".sqlesc($oweight)." AND bposition=".sqlesc($oldposition)) or sqlerr(__FILE__,__LINE__);
		while (list($obid) = mysql_fetch_row($result6)) {
			sql_query("UPDATE orbital_blocks SET weight=".sqlesc($oweight)." WHERE bid=".sqlesc($obid));
			$oweight++;
		}
		list($lastw) = mysql_fetch_row(sql_query("SELECT weight FROM orbital_blocks WHERE bposition=".sqlesc($bposition)." ORDER BY weight DESC LIMIT 0,1"));
		if ($lastw <= $fweight) {
			$lastw++;
			sql_query("UPDATE orbital_blocks SET title=".sqlesc($title).", content=".sqlesc($content).", bposition=".sqlesc($bposition).", weight=".sqlesc($lastw).", active=".sqlesc($active).", blockfile=".sqlesc($blockfile).", view=".sqlesc($view)." WHERE bid=".sqlesc($bid)) or sqlerr(__FILE__,__LINE__);
		} else {
			sql_query("UPDATE orbital_blocks SET title=".sqlesc($title).", content=".sqlesc($content).", bposition=".sqlesc($bposition).", weight=".sqlesc($fweight).", active=".sqlesc($active).", blockfile=".sqlesc($blockfile).", view=".sqlesc($view)." WHERE bid=".sqlesc($bid)) or sqlerr(__FILE__,__LINE__);
		}
	} else {
		if ($expire == "") $expire = 0;
		if ($newexpire == 1 && $expire != 0) $expire = time() + ($expire * 86400);
		$result8 = sql_query("UPDATE orbital_blocks SET bkey=".sqlesc($bkey).", title=".sqlesc($title).", content=".sqlesc($content).", bposition=".sqlesc($bposition).", weight=".sqlesc($weight).", active=".sqlesc($active).", blockfile=".sqlesc($blockfile).", view=".sqlesc($view).", expire=".sqlesc($expire).", action=".sqlesc($action)." WHERE bid=".sqlesc($bid)) or sqlerr(__FILE__,__LINE__);
	}

	$CACHE->clearGroupCache('blocks');
	Header("Location: blocksadmin.php");
}

function BlocksShow($bid) {

	BlocksNavi();
	list($bid, $bkey, $title, $content, $bposition, $blockfile) = mysql_fetch_row(sql_query("SELECT bid, bkey, title, content, bposition, blockfile FROM orbital_blocks WHERE bid='$bid'"));
	$bid = intval($bid);
	echo "<p />";
	render_blocks($bposition, $blockfile, $title, $content, $bid, 'c');
	echo "<h4>[ <a href=\"blocksadmin.php?op=BlocksChange&bid=$bid\">��������</a> | <a href=\"blocksadmin.php?op=BlocksEdit&bid=$bid\">�������������</a>";
	if ($bkey == "") echo " | <a href=\"blocksadmin.php?op=BlocksDelete&bid=$bid\" OnClick=\"return DelCheck(this, '������� &quot;$title&quot;?');\">�������</a>";
	echo " | <a href=\"blocksadmin.php\">�������</a> ]</h4>";
}

function BlocksChange($bid, $ok=0) {
	global $CACHE;
	$bid = intval($bid);
	$row = mysql_fetch_array(sql_query("SELECT active FROM orbital_blocks WHERE bid='$bid'"));
	$active = intval($row['active']);
	if (($ok) || ($active == 0)) {
		if ($active == 0) {
			$active = 1;
		} elseif ($active == 1) {
			$active = 0;
		}
		$result = sql_query("UPDATE orbital_blocks SET active='$active' WHERE bid='$bid'");

		$CACHE->clearGroupCache('blocks');
		Header("Location: blocksadmin.php");
	} else {
		list($title, $content, $active) = mysql_fetch_row(sql_query("SELECT title, content, active FROM orbital_blocks WHERE bid='$bid'"));
		if ($active != 0) stderr("<center>�������������� ���� \"$title\"?<br /><br />","[ <a href=\"blocksadmin.php?op=BlocksChange&bid=$bid&ok=1\">��</a> | <a href=\"blocksadmin.php\">���</a> ]</center>",'success');
	}
}


if (isset($_GET['op'])) $op=$_GET['op']; else $op=$_POST['op'];

if (!$op) BlocksAdmin();
switch($op) {

	case "BlocksNew":
		BlocksNew();
		break;


	case "BlocksAdd":
		BlocksAdd($_POST['title'], $_POST['content'], $_POST['bposition'], $_POST['active'], $_POST['blockfile'], $_POST['view'], $_POST['expire'], $_POST['action']);
		break;

	case "BlocksEdit":
		BlocksEdit((int)$_GET['bid']);
		break;

	case "BlocksEditSave":
		BlocksEditSave($_POST['newexpire'], $_POST['bid'], $_POST['bkey'], $_POST['title'], $_POST['content'], $_POST['oldposition'], $_POST['bposition'], $_POST['active'], $_POST['weight'], $_POST['blockfile'], $_POST['view'], $_POST['expire'], $_POST['action']);
		break;

	case "BlocksChange":
		BlocksChange((int)$_GET['bid'], $_GET['ok'], $_GET['de']);
		break;

	case "BlocksDelete":
		if (!is_valid_id($_GET['bid'])) stderr($tracker_lang['error'],$tracker_lang['invalid_id']);

		$bid = (int) $_GET['bid'];
		list($bposition, $weight) = mysql_fetch_row(sql_query("SELECT bposition, weight FROM orbital_blocks WHERE bid='$bid'"));
		$result = sql_query("SELECT bid FROM orbital_blocks WHERE weight>'$weight' AND bposition='$bposition'");
		while (list($nbid) = mysql_fetch_row($result)) {
			sql_query("UPDATE orbital_blocks SET weight='$weight' WHERE bid='$nbid'");
			$weight++;
		}
		sql_query("DELETE FROM orbital_blocks WHERE bid='$bid'");

		$CACHE->clearGroupCache('blocks');
		header("Location: blocksadmin.php");
		break;

	case "BlocksOrder":
		BlocksOrder($_GET['weightrep'], $_GET['weight'], $_GET['bidrep'], $_GET['bidori']);
		break;

	case "BlocksShow":
		BlocksShow($_GET['bid']);
		break;

}

stdfoot();
?>