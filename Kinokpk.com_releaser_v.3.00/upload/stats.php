<?php
/**
 * Activity statistics
 * @license GNU GPLv3 http://opensource.org/licenses/gpl-3.0.html
 * @package Kinokpk.com releaser
 * @author ZonD80 <admin@kinokpk.com>
 * @copyright (C) 2008-now, ZonD80, Germany, TorrentsBook.com
 * @link http://dev.kinokpk.com
 */

require "include/bittorrent.php";
dbconn();
loggedinorreturn();

if (get_user_class() < UC_MODERATOR)
stderr($tracker_lang['error'], $tracker_lang['access_denied']);

stdhead("����������");
?>

<STYLE TYPE="text/css" MEDIA=screen>
<!--
a.colheadlink:link,a.colheadlink:visited {
	font-weight: bold;
	color: #FFFFFF;
	text-decoration: none;
}

a.colheadlink:hover {
	text-decoration: underline;
}
-->
</STYLE>

<?php
begin_main_frame();

$res = sql_query("SELECT SUM(1) FROM torrents") or sqlerr(__FILE__, __LINE__);
$n = mysql_fetch_row($res);
$n_tor = $n[0];

$res = sql_query("SELECT SUM(1) FROM peers") or sqlerr(__FILE__, __LINE__);
$n = mysql_fetch_row($res);
$n_peers = $n[0];

$uporder = urlencode($_GET['uporder']);
$catorder = urlencode($_GET["catorder"]);

if ($uporder == "lastul")
$orderby = "last DESC, name";
elseif ($uporder == "torrents")
$orderby = "n_t DESC, name";
elseif ($uporder == "peers")
$orderby = "n_p DESC, name";
else
$orderby = "name";

$query = "SELECT u.id, u.username AS name, MAX(t.added) AS last, COUNT(DISTINCT t.id) AS n_t, COUNT(p.id) as n_p
	FROM users as u LEFT JOIN torrents as t ON u.id = t.owner LEFT JOIN peers as p ON t.id = p.torrent WHERE u.class = 3
	GROUP BY u.id UNION SELECT u.id, u.username AS name, MAX(t.added) AS last, COUNT(DISTINCT t.id) AS n_t, COUNT(p.id) as n_p
	FROM users as u LEFT JOIN torrents as t ON u.id = t.owner LEFT JOIN peers as p ON t.id = p.torrent WHERE u.class > 3
	GROUP BY u.id ORDER BY $orderby";

$res = sql_query($query) or sqlerr(__FILE__, __LINE__);

if (mysql_num_rows($res) == 0)
stdmsg("��������", "��� ����������.");
else
{
	begin_frame("���������� ����������", True);
	print("<table width=\"100%\"><tr>\n
	<td class=colhead><a href=\"stats.php?uporder=uploader&amp;catorder=$catorder\" class=colheadlink>����������</a></td>\n
	<td class=colhead><a href=\"stats.php?uporder=lastul&amp;catorder=$catorder\" class=colheadlink>��������� �������</a></td>\n
	<td class=colhead><a href=\"stats.php?uporder=torrents&amp;catorder=$catorder\" class=colheadlink>���������</a></td>\n
	<td class=colhead>���������</td>\n
	<td class=colhead><a href=\"stats.php?uporder=peers&amp;catorder=$catorder\" class=colheadlink>�����</a></td>\n
	<td class=colhead>���������</td>\n
	</tr>\n");
	while ($uper = mysql_fetch_array($res))
	{
		print("<tr><td><a href=userdetails.php?id=".$uper['id']."><b>".$uper['name']."</b></a></td>\n");
		print("<td " . ($uper['last']?(">".mkprettytime($uper['last'])." (".get_elapsed_time($uper['last'])." �����)"):"align=center>---") . "</td>\n");
		print("<td align=right>" . $uper['n_t'] . "</td>\n");
		print("<td align=right>" . ($n_tor > 0?number_format(100 * $uper['n_t']/$n_tor,1)."%":"---") . "</td>\n");
		print("<td align=right>" . $uper['n_p']."</td>\n");
		print("<td align=right>" . ($n_peers > 0?number_format(100 * $uper['n_p']/$n_peers,1)."%":"---") . "</td></tr>\n");
	}
	print('</table>');
	end_frame();
}

if ($n_tor == 0)
stdmsg("��������", "������ �� ���������� �����������!");
else
{
	if ($catorder == "lastul")
	$orderby = "last DESC, c.name";
	elseif ($catorder == "torrents")
	$orderby = "n_t DESC, c.name";
	elseif ($catorder == "peers")
	$orderby = "n_p DESC, c.name";
	else
	$orderby = "c.name";
	$tree = make_tree();
	$res = sql_query("SELECT c.id as catid, MAX(t.added) AS last, COUNT(DISTINCT t.id) AS n_t, COUNT(p.id) AS n_p
	FROM categories as c LEFT JOIN torrents as t ON t.category = c.id LEFT JOIN peers as p
	ON t.id = p.torrent GROUP BY c.id ORDER BY $orderby") or sqlerr(__FILE__, __LINE__);

	begin_frame("���������� ���������", True);
	print("<table width=\"100%\" border=\"1\"><tr><td class=colhead><a href=\"stats.php?uporder=$uporder&amp;catorder=category\" class=colheadlink>���������</a></td>
	<td class=colhead><a href=\"stats.php?uporder=$uporder&amp;catorder=lastul\" class=colheadlink>��������� �������</a></td>
	<td class=colhead><a href=\"stats.php?uporder=$uporder&amp;catorder=torrents\" class=colheadlink>���������</a></td>
	<td class=colhead>���������</td>
	<td class=colhead><a href=\"stats.php?uporder=$uporder&amp;catorder=peers\" class=colheadlink>�����</a></td>
	<td class=colhead>���������</td></tr>\n");
	while ($cat = mysql_fetch_array($res))
	{
		print("<tr><td class=rowhead>" . get_cur_position_str($tree,$cat['catid']) . "</b></a></td>");
		print("<td " . ($cat['last']?(">".mkprettytime($cat['last'])." (".get_elapsed_time($cat['last'])." �����)"):"align = center>---") ."</td>");
		print("<td align=right>" . $cat['n_t'] . "</td>");
		print("<td align=right>" . number_format(100 * $cat['n_t']/$n_tor,1) . "%</td>");
		print("<td align=right>" . $cat['n_p'] . "</td>");
		print("<td align=right>" . ($n_peers > 0?number_format(100 * $cat['n_p']/$n_peers,1)."%":"---") . "</td>\n");
	}
	print ('</table>');
	end_frame();
}

end_main_frame();
stdfoot();
die;
?>