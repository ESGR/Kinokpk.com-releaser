<?php

$content .= "<table border=\"1\" width=\"100%\"><tr><td align=\"center\"><a href=\"viewcensoredtorrents.php\">[���������� ���]</a></div><hr><table border=\"1\" class=\"main\" width=\"100%\">";
if (!defined('BLOCK_FILE')) {
header("Location: ../index.php");
exit;
}


$ctorrents=sql_query("SELECT * FROM censoredtorrents ORDER BY id DESC LIMIT 3");

while ($ct=mysql_fetch_array($ctorrents)) {

if (strlen($ct['reason']) > 500) $reason = format_comment(substr($ct['reason'],0,500)."..."); else $reason = format_comment($ct['reason']);

$content .= "<tr><td><b>".$ct['name']."</b><br>".$reason."</tr>";
}

$content .= "</table>";
$content .= "<td width=\"200\">�� ���������� ������ ������� ��� ������� ����� ���������� ������������ ��� �����-���� ��������������. ���������� ���� ������� ���� ���������� ��������� ����������������, ���� ��������� �� ����� ������ �������, �� ��������� �� ���.<br/>������ ��������� �� ��� ���, ���� ����� ��������� � <a href=\"viewcensoredtorrents.php\">������ ����������� �������</a>.</td></tr></table>";


$blocktitle = "<font color=\"red\">����������� ������</font>";
?>