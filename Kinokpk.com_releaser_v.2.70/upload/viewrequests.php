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

gzip();

loggedinorreturn();

if ($_GET["delreq"])
{
	if (get_user_class() >= UC_MODERATOR) {
		if (empty($_GET["delreq"]))
		stderr($tracker_lang['error'],$tracker_lang['no_fileds_blank']);
		sql_query("DELETE FROM requests WHERE id IN (" . implode(", ", array_map("sqlesc", $_GET["delreq"])) . ")");
		sql_query("DELETE FROM addedrequests WHERE requestid IN (" . implode(", ", array_map("sqlesc", $_GET["delreq"])) . ")");
		sql_query("DELETE FROM reqcomments WHERE request IN (" . implode(", ", array_map("sqlesc", $_GET["delreq"])) . ")");
		sql_query("DELETE FROM checkcomm WHERE checkid IN (" . implode(", ", array_map("sqlesc", $_GET["delreq"])) . ") AND req = 1");
		$CACHE->clearGroupCache('block-req');
		stderr($tracker_lang['success'], "������ ������� ������.<br /><a href=viewrequests.php>� ������ ��������</a>");
	}
	else
	stderr($tracker_lang['error'], "� ��� ��� ���� ��� �������� ��������.");
}

if ((!is_valid_id($_GET['category'])) && ($_GET['category']<>0)) stderr ($tracker_lang['error'],$tracker_lang['invalid_id']);
stdhead($tracker_lang['requests_section']);

$categ = (int)$_GET["category"];
$requestorid = (int)$_GET["requestorid"];
$sort = htmlspecialchars((string)$_GET["sort"]);
$search = (string) $_GET["search"];
$filter = htmlspecialchars($_GET["filter"]);

print("<table class=\"embedded\" cellspacing=\"0\" cellpadding=\"5\" width=\"100%\" >");
print("<tr><td class=\"colhead\" align=\"center\" colspan=\"15\">������ ��������</td></tr>");
print("<tr><td class=\"index\" colspan=\"15\">");

//begin_main_frame();

//print("<h1>".$tracker_lang['requests_section']."</h1>\n");
print("<p><a href=requests.php?action=new>".$tracker_lang['make_request']."</a></p>\n");
print("<p><a href=viewrequests.php?requestorid=$CURUSER[id]>".$tracker_lang['show_my_requests']."</a></p>\n");
print("<p><a href=". $_SERVER[PHP_SELF] ."?".(isset($_GET['category'])?"category={$categ}&":"")."sort=" . $sort . "&filter=true>".$tracker_lang['hide_filled']."</a></p>\n");

print("<form method=get action=viewrequests.php>");
$tree= make_tree();
print gen_select_area('category',$tree,$categ);

print("</select>");
print("&nbsp;<input type=\"submit\" align=\"center\" value=\"��������\" style=\"height: 22px\">\n");
print("</form>\n<p />");

print("<form method=\"get\" action=\"viewrequests.php\">");
print("<b>������ �������: </b><input type=\"text\" size=\"40\" name=\"search\">");
print("&nbsp;<input type=\"submit\" align=\"center\" value=\"������\" style=\"height: 22px\">\n");
print("</form><p></p>");

if ($search)
$query[] = "requests.request LIKE '%" . sqlwildcardesc($search) . "%'";

if ($sort == "votes")
$sortsql = "ORDER BY hits DESC";
elseif ($sort == "request")
$sortsql = "ORDER BY request";
elseif ($sort == "added")
$sortsql = "ORDER BY added DESC";
elseif ($sort == "comm")
$sortsql = "ORDER BY comments DESC";
else
$sortsql = "ORDER BY added DESC";

if ($filter == "true")
$query[] = "requests.filledby = 0";
else
$filtersql = "";

if ($requestorid <> NULL) {
	if (($categ <> NULL) && ($categ <> 0))
	$query[] = "requests.cat = " . sqlesc($categ) . " AND requests.userid = " . sqlesc($requestorid);
	else
	$query[] = "requests.userid = " . sqlesc($requestorid);
}

else if ($categ != 0)
$query[] = "requests.cat = " . sqlesc($categ);

if ($query) $query = 'WHERE '.implode(' AND ',$query);

$res = sql_query("SELECT COUNT(requests.id) FROM requests INNER JOIN categories ON requests.cat = categories.id INNER JOIN users ON requests.userid = users.id $query $filtersql") or sqlerr(__FILE__,__LINE__);
list($count) = mysql_fetch_array($res);

if (!$count) {
	print("<tr><td class=\"colhead\" align=\"center\" colSpan=\"15\" >��� ��������</td></tr>");
	print("<tr><td class=\"index\" colspan=\"15\">");
	print("<p>��� ��������. ������� <a href=\"requests.php?action=new\">��������</a>?</p>");
	print("</td></tr>");
} else {

	$perpage = 50;

	list($pagertop, $pagerbottom, $limit) = pager($perpage, $count, $_SERVER["PHP_SELF"] ."?" .(isset($_GET['category'])?"category={$categ}&":"")."sort=" . $sort . "&" );

	print("<tr><td class=\"index\" colspan=\"15\">");
	print($pagertop);
	print("</td></tr>");

	$res = sql_query("SELECT users.class, users.downloaded, users.uploaded, users.username, requests.filled, requests.filledby, requests.id, requests.userid, requests.request, requests.added, requests.hits, requests.comments, categories.id AS cat_id FROM requests INNER JOIN categories ON requests.cat = categories.id INNER JOIN users ON requests.userid = users.id $query $filtersql $limit") or sqlerr(__FILE__, __LINE__);
	$num = mysql_num_rows($res);

	print("<form method=get OnSubmit=\"return confirm('�� �������?')\" action=$SERVER[PHP_SELF]>\n");
	print("<tr><td class=\"colhead\" align=\"center\">".$tracker_lang['type']."</td><td class=colhead align=left><a href=". $_SERVER[PHP_SELF] ."?category=" . $categ . "&filter=" . $filter . "&sort=request class=altlink_white>".$tracker_lang['request']."</a></td><td class=colhead align=center width=150><a href=" . $_SERVER[PHP_SELF] ."?category=" . $categ . "&filter=" . $filter . "&sort=added class=altlink_white>".$tracker_lang['added']."</a></td><td class=colhead align=center>".$tracker_lang['requester']."</td><td class=colhead align=center>".$tracker_lang['filled']."</td><td class=colhead align=center>".$tracker_lang['filled_by']."</td><td class=colhead align=center><a href=" . $_SERVER[PHP_SELF] . "?category=" . $categ . "&filter=" . $filter . "&sort=votes class=altlink_white>".$tracker_lang['votes']."</a></td><td class=colhead align=center><a href=" . $_SERVER[PHP_SELF] . "?category=" . $categ . "&filter=" . $filter . "&sort=comm class=altlink_white>".$tracker_lang['comments']."</a></td>" . (get_user_class() >= UC_MODERATOR?"<td class=colhead align=center>".$tracker_lang['delete']."</td>": "") . "</tr>\n");
	for ($i = 0; $i < $num; ++$i)
	{

		$arr = mysql_fetch_assoc($res);

		if ($arr["downloaded"] > 0)
		{
			$ratio = number_format($arr["uploaded"] / $arr["downloaded"], 2);
			$ratio = "<font color=" . get_ratio_color($ratio) . "><b>$ratio</b></font>";
		}
		else if ($arr["uploaded"] > 0)
		$ratio = "Inf.";
		else
		$ratio = "---";

		$res2 = sql_query("SELECT username, class FROM users WHERE id = " . $arr[filledby]);
		$arr2 = mysql_fetch_assoc($res2);
		if ($arr2[username])
		$filledby = $arr2[username];
		else
		$filledby = " ";
		$addedby = "<td style='padding: 0px' align=center><a href=userdetails.php?id=$arr[userid]><b>".get_user_class_color($arr["class"], $arr["username"])." (<font color=\"".get_ratio_color($ratio)."\">$ratio</font>)</b></a></td>\n";
		$filled = $arr[filled];
		if ($filled != '')
		$filled = "<a href=$arr[filled]><font color=green><b>".$tracker_lang['yes']."</b></font></a>\n";
		else
		$filled = "<a href=requests.php?id=$arr[id]><font color=red><b>".$tracker_lang['no']."</b></font></a>\n";

		if ($arr[comments] == 0)
		$comment = "0";
		else
		$comment = "<a href=requests.php?id=$arr[id]#startcomments><b>$arr[comments]</b></a>";

		print("<tr><td style='padding: 0px'><small>".get_cur_position_str($tree,$arr['cat_id'],'viewrequests')."</small></td>\n<td align=left><a href=requests.php?id=$arr[id]><b>$arr[request]</b></a>".(get_user_class() >= UC_MODERATOR ? "<a href=\"requests.php?action=edit&amp;id=$arr[id]\" class=\"sublink\"><img border=\"0\" src=\"pic/pen.gif\" alt=\"".$tracker_lang['edit']."\" title=\"".$tracker_lang['edit']."\" /></a>" : "")."</td>\n<td align=center>".mkprettytime($arr[added])."</td>$addedby<td align=center>$filled</td>\n<td align=center><a href=userdetails.php?id=$arr[filledby]><b>".get_user_class_color($arr2["class"],$arr2["username"])."</b></a></td>\n<td align=center><a href=votesview.php?requestid=$arr[id]><b>$arr[hits]</b></a></td>\n<td align=center>$comment</td>" .  (get_user_class() >= UC_MODERATOR?"<td align=center><input type=\"checkbox\" name=\"delreq[]\" value=\"" . $arr[id] . "\" /></td>": "") . "</tr>\n");
	}

	if (get_user_class() >= UC_MODERATOR) {
		print("<tr><td class=\"index\" align=\"right\" colspan=\"15\">");
		print("<input type=submit value=\"".$tracker_lang['delete']."\">");
		print("</form>");
		print("</td></tr>");
	}

	print("<tr><td class=\"index\" colspan=\"12\">");
	print($pagerbottom);
	print("</td></tr>");

}

//end_main_frame();
print("</table>");
stdfoot();
//die;

?>