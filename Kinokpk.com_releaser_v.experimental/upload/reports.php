<?php
/**
 * Reports viewer
 * @license GNU GPLv3 http://opensource.org/licenses/gpl-3.0.html
 * @package Kinokpk.com releaser
 * @author ZonD80 <admin@kinokpk.com>
 * @copyright (C) 2008-now, ZonD80, Germany, TorrentsBook.com
 * @link http://dev.kinokpk.com
 */

require_once ("include/bittorrent.php");
dbconn ();
loggedinorreturn ();

if (get_user_class () < UC_MODERATOR) {
	stderr ( $REL_LANG->say_by_key('error'), $REL_LANG->say_by_key('access_denied') );
}

//������� ��� ������
if ($_POST ['deleteall']) {

	sql_query ( "TRUNCATE TABLE reports" ) or sqlerr ( __FILE__, __LINE__ );
}
//


//������� ��������� ������
if ($_POST ['delete'] && $_POST ['reports']) {
	$reports = $_POST ['reports'];

	foreach ( $reports as $id ) {
		sql_query ( "DELETE FROM reports WHERE id=" . sqlesc ( ( int ) $id ) );
	}
}
//


stdhead ( "�������� �����" );

$count = get_row_count ( "reports" );
if (! $count) {
	$empty = 0;
} else {
	$empty = 1;
}

?>
<center>
<h1>����������� ������</h1>
</center>
<div align=center>
<form id="message" action="<?=$REL_SEO->make_link('reports');?>" method="post"><input
	type="hidden" name="deleteall" value="deleteall"> <input type="submit"
	value="������� ��� ������" onClick="return confirm('�� �������?')"></form>
</div>
<br />

<form id="message" action="<?=$REL_SEO->make_link('reports');?>" method="post" name="form1"><input
	type="hidden" value="moveordel" name="action" />
<table border="0" cellspacing="0" width="100%" cellpadding="3">
	<tr>
		<td class=colhead>
		<center>����&nbsp;�����������</center>
		</td>
		<td class=colhead>
		<center>������&nbsp;��</center>
		</td>
		<td class=colhead>
		<center>������&nbsp;��</center>
		</td>
		<td class=colhead>
		<center>�������&nbsp;������</center>
		</td>
		<td class=colhead>
		<center><INPUT id="toggle-all" type="checkbox" title="������� ���"
			value="������� ���" /></center>
		</td>
	</tr>

	<?

	if ($empty) {

		$res = sql_query ( "SELECT reports.*,users.username,users.class FROM reports LEFT JOIN users ON reports.userid=users.id ORDER BY added DESC" ) or sqlerr ( __FILE__, __LINE__ );
			$allowed_types = array ('messages' => $REL_SEO->make_link('message','action','viewmessage','id',' '), 'torrents' => $REL_SEO->make_link('details','id',' '), 'users' => $REL_SEO->make_link('userdetails','id',' '), 'comments' => $REL_SEO->make_link('comment','action','edit','cid',' '), 'pollcomments' => $REL_SEO->make_link('pollcomment','action','edit','cid',' '), 'newscomments' => $REL_SEO->make_link('newscomment','action','edit','cid',' '), 'usercomments' => $REL_SEO->make_link('usercomment','action','edit','cid', ' '), 'reqcomments' => $REL_SEO->make_link('reqcomment','action','edit','cid',' '), 'relgroups' => $REL_SEO->make_link('relgroups','id',' '), 'rgcomments' => $REL_SEO->make_link('rgcomment','action','edit','cid',' '), 'pages' => $REL_SEO->make_link('pagedetails','id',' '), 'pagecomments' => $REL_SEO->make_link('pagecomment','action','edit','cid',' '));
		
		while ( $row = mysql_fetch_array ( $res ) ) {

			$reportid = $row ["id"];
			$toid = $row ["reportid"];
			$userid = $row ["userid"];
			$motive = $row ["motive"];
			$type = $row ['type'];

			$added = mkprettytime ( $row ["added"] ) . ' (' . get_elapsed_time ( $row ['added'], false ) . " {$REL_LANG->say_by_key('ago')})";

			$username = $row ["username"];
			$userclass = $row ["class"];


			//foreach ($allowed_types as $atype)


			print ( "<tr>
        <td align='center'>$added</td>
        <td><b><a target='_blank' href='".$REL_SEO->make_link('userdetails','id',$userid,'username',translit($username))."'>" . get_user_class_color ( $userclass, $username ) . "</a></b></td>
        <td><a href=\"{$allowed_types[$type]}$toid\">$type [$toid]</a></td>
        <td>$motive</td>
        <td align='center'>
        <INPUT type=\"checkbox\" name=\"reports[]\" title=\"�������\" value=\"" . $reportid . "\"></td></tr>" );

		}

	} else {
		print ( "<tr><td align='center' colspan='5'>��� �� ����� ������...</td></tr>" );
	}

	?>

	<tr>
		<td class=colhead colspan="5">
		<div align=right><input type="submit" name="delete"
			value="������� ���������" onClick="return confirm('�� �������?')" /></div>
		</td>
	</tr>
</table>
</form>

	<?
	stdfoot();

	?>
