<?php
$content .= "<table  width=100%><tr><td  valign=top align=center >";

$content .= "<b>��������� �������</b><small><br><a href=viewrequests.php>[���]</a><a href=requests.php?action=new>[������� ������]<a></small><hr><table border=1 class=bottom>";
if (!defined('BLOCK_FILE')) {
header("Location: ../index.php");
exit;
}


$req=sql_query("SELECT requests.* FROM requests INNER JOIN categories ON requests.cat = categories.id WHERE requests.filled = '' ORDER BY added DESC LIMIT 3;");

for ($i=0; $i<mysql_num_rows($req); $i++) {
$requests=mysql_fetch_array($req);

if ($requests[filledby]!=0) {
$done = "<a href=".addslashes($requests[filled])."><img border=\"0\" src=\"pic/chk.gif\" alt=\"��������\"/></a>";
}
else {
$done = "";
}

$content .= "<tr><td class='req'><b><a id='reqq' href=requests.php?id=$requests[id]>$requests[request]</b></a>&nbsp;&nbsp;&nbsp;$done<br> [����:  $requests[comments], �����������: $requests[hits]]<br><small><a href=requests.php?action=vote&voteid=$requests[id]>�������������� � �������</a></small></td></tr>";
}

$content .= "</table>";

$content .= "</td></tr><tr><td valign=top align=center>";

$content .= "<br><br><b>��������� �����������</b></font><small><br><a href=viewoffers.php>[���]</a> <a href=offers.php?action=new>[������� �����������]<a></small><hr><table border=0 class=bottom><tr>";


$off=sql_query("SELECT offers.* FROM offers INNER JOIN categories ON offers.category = categories.id order by added desc LIMIT 3;");

for ($i=0; $i<mysql_num_rows($off); $i++) {
$offers=mysql_fetch_array($off);


$content .= "<tr><td class='req'><b><a id='reqq' href=offers.php?id=$offers[id]>$offers[name]</a></b><br>[����:  $offers[comments], �����������: $offers[votes]]<br><small><a href=offers.php?action=vote&voteid=$offers[id]>������������� �� �����������</a></small></td></tr>";
}

$content .= "</table>";

$content .= "</td></tr></tr></table>";


$blocktitle = "<font color=\"red\">���� �������</font>";
?>