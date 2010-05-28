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
loggedinorreturn();

if (get_user_class() < UC_MODERATOR){
	stderr($tracker_lang['error'],$tracker_lang['access_denied']);
}


//������� ��� ������
if ($_POST['deleteall']) {

	sql_query("TRUNCATE TABLE report") or sqlerr(__FILE__,__LINE__);
}
//


//������� ��������� ������
if ($_POST['delete'] && $_POST['reports']) {
	$reports = $_POST['reports'];

	foreach ($reports as $id) {
		sql_query("DELETE FROM report WHERE id=" . sqlesc((int) $id)) or sqlerr(__FILE__,__LINE__);
	}
}
//

stdhead("�������� ����� �� �������");

$count = get_row_count("report");
if (!$count) {
	$empty = 0;
} else {
	$empty = 1;
}

?>
<script language="Javascript" type="text/javascript">
        <!-- Begin
        var checkflag = "false";
        var marked_row = new Array;
        function check(field) {
                if (checkflag == "false") {
                        for (i = 0; i < field.length; i++) {
                                field[i].checked = true;}
                                checkflag = "true";
                        }
                else {
                        for (i = 0; i < field.length; i++) {
                                field[i].checked = false; }
                                checkflag = "false";
                        }
                }
                //  End -->
        </script>

<center>
<h1>����������� ������ �� �������</h1>
</center>
<div align=center>
<form action="reports.php" method="post"><input type="hidden"
	name="deleteall" value="deleteall"> <input type="submit"
	value="������� ��� ������" onClick="return confirm('�� �������?')"></form>
</div>
<br />

<form action="reports.php" method="post" name="form1">
<table border="0" cellspacing="0" width="100%" cellpadding="3">
	<tr>
		<td class=colhead>
		<center>����&nbsp;�����������</center>
		</td>
		<td class=colhead>
		<center>������&nbsp;��</center>
		</td>
		<td class=colhead>
		<center>������&nbsp;��&nbsp;�������</center>
		</td>
		<td class=colhead>
		<center>�������&nbsp;������</center>
		</td>
		<td class=colhead>
		<center><INPUT type="checkbox" title="������� ���" value="������� ���"
			onClick="this.value=check(document.form1.elements);"></center>
		</td>
	</tr>

	<?

	if ($empty){

		$res = sql_query("SELECT * FROM report ORDER BY added DESC") or sqlerr(__FILE__, __LINE__);
		while ($row = mysql_fetch_array($res)) {

			$reportid = $row["id"];
			$torrentid = $row["torrentid"];
			$userid = $row["userid"];
			$motive = $row["motive"];
			$added = mkprettytime($row["added"]).' ('.get_elapsed_time($row['added'],false)." {$tracker_lang['ago']})";

			$res1 = sql_query("SELECT username, class FROM users WHERE id = $userid") or sqlerr(__FILE__, __LINE__);
			$row1 = mysql_fetch_array($res1);

			$username = $row1["username"];
			$userclass = $row1["class"];

			if ($username == ""){
				$username = "<b><font color='red'>������<font></b>";
			}

			$res2 = sql_query("SELECT id, name FROM torrents WHERE id = $torrentid") or sqlerr(__FILE__, __LINE__);
			$row2 = mysql_fetch_array($res2);

			if ($row2["id"]){
				$torrentname = $row2["name"];
				$torrenturl = "<a target='_blank' href='details.php?id=$torrentid'>$torrentname</a>";
			} else {
				$torrenturl = "<b><font color='red'>������� ������<font></b>";
			}


			print ("<tr>
        <td align='center'>$added</td>
        <td><b><a target='_blank' href='userdetails.php?id=$userid'>".get_user_class_color($userclass, $username)."</a></b></td>
        <td>$torrenturl</td>
        <td>$motive</td>
        <td align='center'>
        <INPUT type=\"checkbox\" name=\"reports[]\" title=\"�������\" value=\"".$reportid."\" id=\"checkbox_tbl_".$reportid."\"></td></tr>");

		}

	}
	else
	{
		print("<tr><td align='center' colspan='5'>��� �� ����� ������ �� �������...</td></tr>");
	}

	?>

	<tr>
		<td class=colhead colspan="5">
		<div align=right><input type="submit" name="delete"
			value="������� ���������" onClick="return confirm('�� �������?')"></div>
		</td>
	</tr>
</table>
</form>

	<?
	stdfoot();

	?>