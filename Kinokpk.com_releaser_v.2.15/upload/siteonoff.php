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

if (get_user_class() < UC_SYSOP) {
                stderr($tracker_lang['error'], $tracker_lang['access_denied']);
        }

if ($_SERVER["REQUEST_METHOD"] == "POST")
{ 
    if (!$_POST["reason"]){ 
                stderr($tracker_lang['error'], "�� �� ������� ������� ��������");
    die;
    }

$reason = $_POST['reason'];//������� ����������
$class = $_POST["class"];                //����� ������, ������ �������� - ������ ������
$onoff = $_POST["onoff"];
$cname = $class;                         //����������� ���������� �������� �� ������� �����_������

//���������� ����� �������, ������ �������� - ������ ������
 switch ($cname) {
        case '0':
        $cname = "��� ������������� � ����";
        break;
        case '1':
        $cname = "��� ����������� � ����";
        break;
        case '2':
        $cname = "��� VIP'�� � ����";
        break;
        case '3':
        $cname = "��� ���������� � ����";
        break;
        case '4':
        $cname = "��� ����������� � ����";
        break;
        case '5':
        $cname = "��� ��������������� � ����";
        break;
        case '6':
        $cname = "������ ��� ����������";
        break;
        //��� �� �������?
        default:
           $cname = "��� ����";
        }

$class_name = $cname;          //��� ������, ������ �������� - ������ ������
$onoffarray = array('onoff'=>$onoff,'reason'=>$reason,'class'=>$class,'class_name'=>$class_name);
sql_query("UPDATE cache_stats SET cache_value=".sqlesc(serialize($onoffarray))." WHERE cache_name='siteonline'") or die(mysql_error());//���������� ����� �������� � ����
 
 header("Location: siteonoff.php");

}    

stdhead("��������� / ���������� �����");

$row = unserialize($CACHEARRAY['siteonline']);

if ($row["onoff"] !=1){
$stroka = ("<td colspan='2' class=myhighlight style='padding:4px; background-color: #FF0000; color:#FFFFFF'>&nbsp;
          <b>����&nbsp;������</b>!&nbsp;����� �������:&nbsp;<b>".$row['class']."</b>&nbsp;(������&nbsp;".$row['class_name'].").&nbsp;
          ��� �����:&nbsp;<b>".$CURUSER['class']."</b>.</td>");
}
else {
$stroka = ("<td colspan='2' class=myhighlight style='padding:4px; background-color: #EAFFD5; color:#008000'>&nbsp;
          <b>����&nbsp;������&nbsp;������</b>!&nbsp;������ ����� ��� ������.&nbsp;
          ��� �����:&nbsp;<b>".$CURUSER['class']."</b>.</td>");
}

?>
<form method="POST" action="siteonoff.php">
<table border="1" cellspacing="0" cellpadding="0" style="border-collapse: collapse">
<tr>
<td class=colhead><center><font size='3'>::&nbsp;&nbsp;��������&nbsp;&nbsp;:&nbsp;&nbsp;�������� �����&nbsp;&nbsp;::</font></center></td>
</tr><tr><td><table border="0" cellspacing="1">
<tr><td class=embedded>
<table border="0" cellspacing="2"><tr><?=$stroka?></tr><tr>
<td  class=embedded colspan="2" height="3"></td></tr><tr>
<td class=colhead>&nbsp;C�������� � �������� ����� (�������� HTML):</td>
<td class=colhead>&nbsp;�������� � ��������:</td></tr><tr>
<td class=embedded valign="top">
<textarea rows="9" name="reason" cols="60"><?=$row["reason"]?></textarea></td>
<td class=embedded align="left" valign="top">
<table border="0" cellspacing="1" id="table1" align="left">
<tr><td  class=embedded height="2" colspan="2"></td></tr>
<tr><td class=colhead colspan="2">&nbsp;���� ������:</td></tr><tr>
<td class=myhighlight width="50%"><b><font color=green>&nbsp;&nbsp;���.</font></b><input type="radio" name="onoff" <?=($row["onoff"] == "1" ? "checked" : "")?> value="1"></td>
<td class=myhighlight width="50%"><b><font color=red>&nbsp;&nbsp;����.</font></b><input type="radio" name="onoff" <?=($row["onoff"] == "0" ? "checked" : "")?> value="0"></td>
</tr><tr><td class=embedded height="5" colspan="2"></td></tr><tr>
<td class=colhead colspan="2">&nbsp;����� ������:</td></tr><tr>
<td class=myhighlight colspan="2">
<select size="1" name="class" " style="<?=($row["onoff"] != 1 ? "color: #FFFFFF; background-color: #FF0000;" : "")?>">
<option <?=($row["class"] == "6" ? "selected" : "")?> value="6">������ ����������</option>
<option <?=($row["class"] == "5" ? "selected" : "")?> value="5">�������������� � ����</option>
<option <?=($row["class"] == "4" ? "selected" : "")?> value="4">���������� � ����</option>
<option <?=($row["class"] == "3" ? "selected" : "")?> value="3">��������� � ����</option>
<option <?=($row["class"] == "2" ? "selected" : "")?> value="2">VIP'� � ����</option>
<option <?=($row["class"] == "1" ? "selected" : "")?> value="1">����������� � ����</option>
<option <?=($row["class"] == "0" ? "selected" : "")?> value="0">������������ � ����</option>
</select>
</td></tr><tr><td class=embedded height="5" colspan="2"></td></tr><tr>
<td class=embedded colspan="2">
<p align="center"><input type="submit" value="���������"></p></td></tr></table></td></tr>
</table></td></tr></table></td></tr></table>
</form>

<?
stdfoot();
?>