<?php

global $CACHE;

if (!defined('BLOCK_FILE')) {
	header("Location: ../index.php");
	exit;
}

$content .= "<table  width=\"100%\"><tr><td  valign=\"top\" align=\"center\">";

$content .= "<small>[<a href=\"viewrequests.php\">��</a>] [<a href=\"requests.php?action=new\">��������</a>]</small><hr /><table border=\"1\"><tr><td align=\"center\">";

$reqarray = $CACHE->get('block-req', 'query');

if ($reqarray===false) {

	$reqarray = array();
	$req=sql_query("SELECT requests.* FROM requests INNER JOIN categories ON requests.cat = categories.id WHERE requests.filled = '' ORDER BY added DESC LIMIT 3");

	while ($reqres = @mysql_fetch_array($req))
	$reqarray[]=$reqres;

	$CACHE->set('block-req', 'query', $reqarray);
}

if (!$reqarray) {$content .= '<b>��� ��������</b>'; } else
foreach ($reqarray as $requests) {
	if ($requests[filledby]!=0) {
		$done = "<a href=".addslashes($requests[filled])."><img border=\"0\" src=\"pic/chk.gif\" alt=\"��������\"/></a>";
	}
	else {
		$done = "";
	}

	$content .= "<a href=\"requests.php?id=$requests[id]\"><b>$requests[request]</b></a>&nbsp;&nbsp;&nbsp;$done<br /><small> [����:  $requests[comments], �����������: $requests[hits]]<br /><a href=\"requests.php?action=vote&amp;voteid=$requests[id]\">�������������� � �������</a></small>";

}

$content .= "</td></tr></table></td></tr></table>";

$blocktitle = "<font color=\"red\">���� �������</font>";
?>