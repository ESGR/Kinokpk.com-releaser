<?php
/**
 * Staff viewer
 * @license GNU GPLv3 http://opensource.org/licenses/gpl-3.0.html
 * @package Kinokpk.com releaser
 * @author ZonD80 <admin@kinokpk.com>
 * @copyright (C) 2008-now, ZonD80, Germany, TorrentsBook.com
 * @link http://dev.kinokpk.com
 */


require "include/bittorrent.php";
dbconn();
loggedinorreturn();
stdhead("�������������");
begin_main_frame();

// Get current datetime
$dt = time() - 300;

$res = sql_query("SELECT id,username,class, (SELECT SUM(1) FROM sessions WHERE uid=users.id AND time>$dt) AS online FROM users WHERE class>=".UC_UPLOADER." AND confirmed=1 GROUP BY users.id ORDER BY class DESC, username ASC" ) or sqlerr(__FILE__, __LINE__);

while ($arr = mysql_fetch_assoc($res))
{

	$staff_table[$arr['class']]=$staff_table[$arr['class']].
"<td class=embedded><a class=altlink href=\"".$REL_SEO->make_link('userdetails','id',$arr['id'],'username',translit($arr['username']))."\"><b>".
	get_user_class_color($arr['class'],$arr['username'])."</b></a></td><td class=embedded> ".($arr['online']?"<img src=pic/button_online.gif border=0 alt=\"online\">":"<img src=pic/button_offline.gif border=0 alt=\"offline\">" )."</td>".
"<td class=embedded><a href=\"".$REL_SEO->make_link('message','action','sendmessage','receiver',$arr['id'])."\">".
"<img src=pic/button_pm.gif border=0></a></td>".
" ";



	// Show 3 staff per row, separated by an empty column
	++ $col[$arr['class']];
	if ($col[$arr['class']]<=2)
	$staff_table[$arr['class']]=$staff_table[$arr['class']]."<td class=embedded>&nbsp;</td>";
	else
	{
		$staff_table[$arr['class']]=$staff_table[$arr['class']]."</tr><tr height=15>";
		$col[$arr['class']]=0;
	}
}
begin_frame("�������������");
?>
<table width=100% cellspacing=0>
	<tr>
		<tr>
			<td class=embedded colspan=11>�������, �� ������� ���� ������ �
			�������� ��� FAQ, ����� ��������� ��� ��������.</td>
		</tr>
		<!-- Define table column widths -->
		<td class=embedded width="125">&nbsp;</td>
		<td class=embedded width="25">&nbsp;</td>
		<td class=embedded width="35">&nbsp;</td>
		<td class=embedded width="85">&nbsp;</td>
		<td class=embedded width="125">&nbsp;</td>
		<td class=embedded width="25">&nbsp;</td>
		<td class=embedded width="35">&nbsp;</td>
		<td class=embedded width="85">&nbsp;</td>
		<td class=embedded width="125">&nbsp;</td>
		<td class=embedded width="25">&nbsp;</td>
		<td class=embedded width="35">&nbsp;</td>
	</tr>
	<?php
	//var_dump($staff_table);
	foreach ($staff_table as $class => $data) {?>
	<tr>
		<td class=embedded colspan=11><b><?=get_user_class_name($class)?></b></td>
	</tr>
	<tr>
		<td class=embedded colspan=11>
		<hr color="#4040c0" size=1>
		</td>
	</tr>
	<tr height=15>
	<?=$data?>
	</tr>
	<tr>
		<td class=embedded colspan=11>&nbsp;</td>
	</tr>
	<?php } ?>
</table>
	<?
	end_frame();

	// LIST ALL FIRSTLINE SUPPORTERS
	// Search User Database for Firstline Support and display in alphabetical order
	$res = sql_query("SELECT users.id AS ids, users.last_access, users.username, users.supportfor, users.country, countries.name AS name, countries.flagpic AS flagpic FROM users LEFT JOIN countries ON users.country = countries.id WHERE supportfor<>'' AND confirmed=1 ORDER BY username LIMIT 10") or sqlerr(__FILE__, __LINE__);
	while ($arr = mysql_fetch_assoc($res))
	{

		$firstline .= "<tr height=15><td class=embedded><a class=altlink href=\"".$REL_SEO->make_link('userdetails','id',$arr['ids'],'username',translit($arr['username']))."\">".$arr['username']."</a></td>
<td class=embedded> ".($arr['last_access'] > $dt ? "<img src=pic/button_online.gif border=0 alt=\"online\">":"<img src=pic/button_offline.gif border=0 alt=\"offline\">" )."</td>".
		"<td class=embedded><a href=\"".$REL_SEO->make_link('message','action','sendmessage','receiver',$arr['ids'])."\">"."<img src=pic/button_pm.gif border=0></a></td>".
"<td class=embedded><img src=pic/flag/$arr[flagpic] title=$arr[name] border=0 width=19 height=12></td>".
"<td class=embedded>".htmlspecialchars($arr['supportfor'])."</td></tr>\n";
	}
	begin_frame("������ ����� ���������");
	?>

<table width=100% cellspacing=0>
	<tr>
		<td class=embedded colspan=11>����� ������� ����� �������� ����
		�������������. ������ ��� ��� �����������, �������� ���� ����� � ����
		�� ������ ���. ���������� � ��� ���������.<br />
		<br />
		<br />
		</td>
	</tr>
	<!-- Define table column widths -->
	<tr>
		<td class=embedded width="30"><b>������������&nbsp;</b></td>
		<td class=embedded width="5"><b>�������&nbsp;</b></td>
		<td class=embedded width="5"><b>�������&nbsp;</b></td>
		<td class=embedded width="85"><b>����&nbsp;</b></td>
		<td class=embedded width="200"><b>��������� ���&nbsp;</b></td>
	</tr>


	<tr>
		<tr>
			<td class=embedded colspan=11>
			<hr color="#4040c0" size=1>
			</td>
		</tr>

		<?=$firstline?>

	</tr>
</table>
		<?
		end_frame();

		?>
		<?
		end_main_frame();
		stdfoot();
?>