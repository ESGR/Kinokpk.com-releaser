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

require "include/bittorrent.php";

gzip();

dbconn();

//loggedinorreturn();

stdhead("IRC ��� (��� ������������)");

begin_main_frame();

?>

<? begin_frame("��� IRC |<font color=#004E98> ���������</font>"); ?>
<ul><center><h3>
Server:</h3> <h1>dalnetru.ircd.com.ru</h1>
<h3>Port:</h3> <h1>6667</h1>
<h3>Channel:</h3> <h1>#kinokpk</h1></center>
<? end_frame(); ?>
<? begin_frame("IRC ��� |<font color=#004E98> ��� � ���� ������������?</font>"); ?>
<li>��� ������ - ������� IRC ������. ����������� MIRC - �� �� ����, ����� � �.�.<br><br><center><img src="files/irc/mirc.jpg"></center><br><br>������ ��� �������� (� ������ ��� ������ + ������):<br><a href="files/irc/mirc621.zip">������� (������ 6.21)</a></li>
<li>������������ � ������ IRC ������. ��� ����� ������� ���:<br><br>
<center><b>������������ �������� <font color="red">�������</font>, �������������� - <font color="green">�������</font></b></center><br><br>
��������� MIRC:<br><br>
<center><img src="files/irc/irc1.jpg"></center><br><br>
�������� <i>File</i> -> <i>Select Server</i>
<center><img src="files/irc/irc2.jpg">  <img src="files/irc/irc3.jpg"></center><br><br>
� ����������� ������ �������� <i>Add.</i><br><br>
<center><img src="files/irc/irc4.jpg"></center><br><br>
� ����������� ������ ������ �������� ������� (��� �� ����� ������������ � ���������)<br>
����� ������� - <b>dalnetru.ircd.com.ru</b><br>
���� - <b>6667</b><br>
� �������� �� <i>Add.</i><br><br>
<center><img src="files/irc/irc5.jpg"></center><br><br>
�����, �� �������� ��� ����� ��� ������:<br><br>
<center><img src="files/irc/irc6.jpg"></center><br><br>
� ��� ��������� �� <i>Select.</i> � ���������� � ���� ������������ �����:<br><br>
<center><img src="files/irc/irc7.jpg"></center><br><br>
������ ��� - ���� ��� ��� ����������� �� IRC ������� (���� ����������� �������������, � ��� ��� �����������)<br>
E-mail ����� - ��� �-���� ��� ����������� �� IRC ������� (���� ����������� �������������, � ��� ����� ����������)<br>
��� ��� - ���������� ��� ���<br>
�������������� ��� - ����� ����� ��� ���, ���� ������ ��������� ��� �����<br>
������ ������� �� <i>��.</i><br>
������ �������� ��� ������:<img src="files/irc/irc8.jpg"><br><br>
<center>�� ������������ � �������! ������ ���������� ����� �� ��� �����:<br><br>
<img src="files/irc/irc9.jpg"></center><br><br>
������� ��� ������ - <b>#kinokpk</b><br>
� ��������� <i>Join.</i><br>
����� �� ������� ������ �������� ��� ����� � ������ (����� �� ������� ������ ���), ����� <i>Add.</i><br><br>
<center><b>� ����� �� �������� ���:</b><br><br><img src="files/irc/irc10.jpg"><br><br><font color="red">�����������! �� �� ����� ������ IRC � ������ �������� ��������!!!</font></center>


</li>
<? end_frame();

end_main_frame();
stdfoot(); ?>