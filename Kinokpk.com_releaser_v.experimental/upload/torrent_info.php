<?php
/**
 * Displays all torrent information
 * @license GNU GPLv3 http://opensource.org/licenses/gpl-3.0.html
 * @package Kinokpk.com releaser
 * @author ZonD80 <admin@kinokpk.com>
 * @copyright (C) 2008-now, ZonD80, Germany, TorrentsBook.com
 * @link http://dev.kinokpk.com
 */

require_once "include/bittorrent.php";
require_once "include/benc.php";

/**
 * Creates nice peers table
 * @param string $name Name of a table
 * @param array $arr Array to be processed
 * @param int $torrent ID of a torrent
 * @return string HTML code of a table
 */
function dltable($name, $arr, $torrent)
{

	global $CURUSER, $REL_LANG, $REL_SEO;
	$s = "<b>" . count($arr) . " $name</b>\n";
	if (!count($arr))
	return $s;
	$s .= "\n";
	$s .= "<table width=100% class=main border=1 cellspacing=0 cellpadding=5>\n";
	$s .= "<tr><td class=colhead>".$REL_LANG->say_by_key('user')."</td>" .
          "<td class=colhead align=right>".$REL_LANG->say_by_key('ratio')."</td>" .
          "<td class=colhead align=right>".$REL_LANG->say_by_key('completed')."</td>".    
          "<td class=colhead align=right>".$REL_LANG->say_by_key('idle')."</td>" .
          "<td class=colhead align=left>".$REL_LANG->say_by_key('client')."</td></tr>\n";
	$moderator = (isset($CURUSER) && get_user_class() >= UC_MODERATOR);
	$mod = get_user_class() >= UC_MODERATOR;
	foreach ($arr as $e) {
		// user/ip/port
		// check if anyone has this ip
		$s .= "<tr>\n";
		if ($e["username"])
		$s .= "<td><a href=\"".$REL_SEO->make_link('userdetails','id',$e['userid'],'username',translit($e["username"]))."\"><b>".get_user_class_color($e["class"], $e["username"])."</b></a>".($mod ? "&nbsp;[<span title=\"{$e["ip"]}\" style=\"cursor: pointer\">IP</span>]" : "")."</td>\n";
		else
		$s .= "<td>" . ($mod ? $e["ip"] : preg_replace('/\.\d+$/', ".xxx", $e["ip"])) . "</td>\n";
		$s .='<td nowrap>'.ratearea($e['ratingsum'],$e['userid'],'users',$CURUSER['id']).'</td>';
		$s .="<td>".($e['seeder']?$REL_LANG->say_by_key('yes'):$REL_LANG->say_by_key('no'))."</td>";
		$s .= "<td align=\"right\">" . get_elapsed_time($e["la"]) . "</td>\n";
		$s .= "<td align=\"left\">" . substr($e["peer_id"],0,7) . "</td>\n";
		$s .= "</tr>\n";
	}
	$s .= "</table>\n";
	return $s;
}

dbconn();



loggedinorreturn();

$id = (int)$_GET["id"];

if (!$id)
stderr($REL_LANG->say_by_key('error'),$REL_LANG->say_by_key('invalid_id'));

list($name,$nofr) = @mysql_fetch_array(sql_query("SELECT name,filename FROM torrents WHERE id=$id"));
if ($nofr == 'nofile') die ("����� �� ���� ��������? ��� �� ������� �����! ������ � �������� ���! <a href='".$REL_SEO->make_link('details','id',$id,'name',translit($name))."'>� �������� ������</a>");
elseif (!$nofr) 	stderr($REL_LANG->say_by_key('error'),$REL_LANG->say_by_key('invalid_id'));

$nof = sql_query("SELECT tracker,lastchecked,state,seeders,leechers FROM trackers WHERE torrent = $id ORDER by lastchecked DESC");
while (list($tracker,$lastchecked,$state,$seeders,$leechers) = mysql_fetch_array($nof)) {
	if ($tracker=='localhost') {
		$data[$i]['tracker'] = $REL_CONFIG['defaultbaseurl'];
		$data[$i]['state'] = 'ok_local';
	}
	else { $data[$i]['tracker']=$tracker;   $data[$i]['state'] = $state; }

	$data[$i]['lastchecked'] = get_elapsed_time($lastchecked)." {$REL_LANG->say_by_key('ago')}";
	$data[$i]['seeders'] = $seeders;
	$data[$i]['leechers'] = $leechers;
	$i++;
}

stdhead("������ � �������");
print("<h1>������ � �������</h1>\n");
print("<div id=\"tabs\"><ul>
	<li class=\"tab2\"><a href=\"".$REL_SEO->make_link('details','id',$id,'name',translit($name))."\"><span>��������</span></a></li>
	<li nowrap=\"\" class=\"tab1\"><a href=\"".$REL_SEO->make_link('torrent_info','id',$id,'name',translit($name))."\"><span>{$REL_LANG->say_by_key('torrent_info')}</span></a></li>
	<li nowrap=\"\" class=\"tab2\"><a href=\"".$REL_SEO->make_link('exportrelease','id',$id,'name',translit($row['name']))."\"><span>{$REL_LANG->say_by_key('exportrelease_mname')}</span></a></li>
	</ul></div>\n <br />");
print('<table width="100%" style="float:left"><tr><td class="colhead">'.$REL_LANG->say_by_key('tracker').'</td><td class="colhead">�����</td><td class="colhead">�����</td><td class="colhead">�����</td><td class="colhead">����� ��������</td><td class="colhead">'.$REL_LANG->say_by_key('status').'</td></tr>');
if ($data)
foreach ($data as $tracker)
print ("<tr><td>".$tracker['tracker']."</td><td>{$tracker['seeders']}</td><td>{$tracker['leechers']}</td><td>".($tracker['seeders']+$tracker['leechers'])."</td><td>{$tracker['lastchecked']}</td><td>".cleanhtml($tracker['state'])."</td></tr>");
//print('</table>');
else print ('<tr><td colspan="6" align="center">������ ����� �������� ������� ��� ������� ��� ��������</td></tr>');
end_frame();


print('<h3><a href="'.$REL_SEO->make_link('torrent_info','id',$id,'name',translit($name),'info','').'">���������� ��������� �������-�����</a> ��� <a href="'.$REL_SEO->make_link('torrent_info','id',$id,'name',translit($name),'dllist','').'">���������� ������ ����� �� �����</a></h3>');
if (isset($_GET['info'])) {
	/**
	 * Prints Nice array
	 * @param array $array Array to be processed
	 * @param string $offset_symbol Offset symbol
	 * @param string $offset Offset
	 * @param string $parent Parent
	 */
	function print_array($array, $offset_symbol = "|--", $offset = "", $parent = "")
	{
		if (!is_array($array))
		{
			echo "[$array] is not an array!<br />";
			return;
		}

		reset($array);


		switch($array['type'])
		{
			case "string":
				printf("<li><div class=string> - <span class=icon>[STRING]</span> <span class=title>[%s]</span> <span class=length>(%d)</span>: <span class=value>%s</span></div></li>",$parent,$array['strlen'],$array['value']);
				break;
			case "integer":
				printf("<li><div class=integer> - <span class=icon>[INT]</span> <span class=title>[%s]</span> <span class=length>(%d)</span>: <span class=value>%s</span></div></li>",$parent,$array['strlen'],$array['value']);
				break;
			case "list":
				printf("<li><div class=list> + <span class=icon>[LIST]</span> <span class=title>[%s]</span> <span class=length>(%d)</span></div>",$parent,$array['strlen']);
				echo "<ul>";
				print_array($array['value'], $offset_symbol, $offset.$offset_symbol);
				echo "</ul></li>";
				break;
			case "dictionary":
				printf("<li><div class=dictionary> + <span class=icon>[DICT]</span> <span class=title>[%s]</span> <span class=length>(%d)</span></div>",$parent,$array['strlen']);
				while (list($key, $val) = each($array))
				{
					if (is_array($val))
					{
						echo "<ul>";
						print_array($val, $offset_symbol, $offset.$offset_symbol,$key);
						echo "</ul>";
					}
				}
				echo "</li>";

				break;
			default:
				while (list($key, $val) = each($array))
				{
					if (is_array($val))
					{
						//echo $offset;
						print_array($val, $offset_symbol, $offset, $key);
					}
				}
				break;

		}

	}

	$fn = "torrents/$id.torrent";

	if (!is_readable($fn)) {
		stdmsg($REL_LANG->say_by_key('error'),'���������� ��������� torrent-����','error');   stdfoot(); die(); }
		?>

<style type="text/css">
<!-- /* list styles */
ul ul {
	margin-left: 15px;
}

ul,li {
	padding: 0px;
	list-style-type: none;
	color: #000;
	font-weight: normal;
}

ul a,li a {
	color: #009;
	text-decoration: none;
	font-weight: normal;
}

li {
	display: inline;
}  /* fix for IE blank line bug */
ul>li {
	display: list-item;
}

li div.string {
	padding: 3px;
}

li div.integer {
	padding: 3px;
}

li div.dictionary {
	padding: 3px;
}

li div.list {
	padding: 3px;
}

li div.string span.icon {
	color: #090;
	padding: 2px;
}

li div.integer span.icon {
	color: #990;
	padding: 2px;
}

li div.dictionary span.icon {
	color: #909;
	padding: 2px;
}

li div.list span.icon {
	color: #009;
	padding: 2px;
}

li span.title {
	font-weight: bold;
}
-->
</style>

		<?php

		begin_main_frame();

		$info = bdec_file($fn, (1024*1024));

		// Start table
		/*print("<table cellspacing=\"0\" cellpadding=\"0\" class=\"tabs\"><tbody><tr>
		 <td class=\"tab0\"> </td><td nowrap=\"\" class=\"tab2\"><a href=\"details.php?id=$id\">��������</a></td>
		 <td class=\"tab\"> </td><td nowrap=\"\" class=\"tab1\"><a href=\"torrent_info.php?id=$id\">������ � ��������</a></td>
		 <td class=\"tab3\"> </td></tr></tbody></table>\n");*/
		print("<table width=100% border=1 cellspacing=0 cellpadding=5>");

		print("<td>");

		$info['value']['pieces']['value'] = "0x".bin2hex(substr($info['value']['pieces']['value'], 0, 25))."...";

		echo "<ul id=colapse>";
		print_array($info,"*", "", "info");
		print $anstring;
		echo "</ul>";

		// End table
		print("</td></table>");

		?>

<script type="text/javascript" language="javascript1.2"><!--
var openLists = [], oIcount = 0;
function compactMenu(oID,oAutoCol,oPlMn,oMinimalLink) {
	if( !document.getElementsByTagName || !document.childNodes || !document.createElement ) { return; }
	var baseElement = document.getElementById( oID ); if( !baseElement ) { return; }
	compactChildren( baseElement, 0, oID, oAutoCol, oPlMn, baseElement.tagName.toUpperCase(), oMinimalLink && oPlMn );
}
function compactChildren( oOb, oLev, oBsID, oCol, oPM, oT, oML ) {
	if( !oLev ) { oBsID = escape(oBsID); if( oCol ) { openLists[oBsID] = []; } }
	for( var x = 0, y = oOb.childNodes; x < y.length; x++ ) { if( y[x].tagName ) {
		//for each immediate LI child
		var theNextUL = y[x].getElementsByTagName( oT )[0];
		if( theNextUL ) {
			//collapse the first UL/OL child
			theNextUL.style.display = 'none';
			//create a link for expanding/collapsing
			var newLink = document.createElement('A');
			newLink.setAttribute( 'href', '#' );
			newLink.onclick = new Function( 'clickSmack(this,' + oLev + ',\'' + oBsID + '\',' + oCol + ',\'' + escape(oT) + '\');return false;' );
			//wrap everything upto the child U/OL in the link
			if( oML ) { var theHTML = ''; } else {
				var theT = y[x].innerHTML.toUpperCase().indexOf('<'+oT);
				var theA = y[x].innerHTML.toUpperCase().indexOf('<A');
				var theHTML = y[x].innerHTML.substr(0, ( theA + 1 && theA < theT ) ? theA : theT );
				while( !y[x].childNodes[0].tagName || ( y[x].childNodes[0].tagName.toUpperCase() != oT && y[x].childNodes[0].tagName.toUpperCase() != 'A' ) ) {
					y[x].removeChild( y[x].childNodes[0] ); }
			}
			y[x].insertBefore(newLink,y[x].childNodes[0]);
			y[x].childNodes[0].innerHTML = oPM + theHTML.replace(/^\s*|\s*$/g,'');
			theNextUL.MWJuniqueID = oIcount++;
			compactChildren( theNextUL, oLev + 1, oBsID, oCol, oPM, oT, oML );
} } } }
function clickSmack( oThisOb, oLevel, oBsID, oCol, oT ) {
	if( oThisOb.blur ) { oThisOb.blur(); }
	oThisOb = oThisOb.parentNode.getElementsByTagName( unescape(oT) )[0];
	if( oCol ) {
		for( var x = openLists[oBsID].length - 1; x >= oLevel; x-=1 ) { if( openLists[oBsID][x] ) {
			openLists[oBsID][x].style.display = 'none'; if( oLevel != x ) { openLists[oBsID][x] = null; }
		} }
		if( oThisOb == openLists[oBsID][oLevel] ) { openLists[oBsID][oLevel] = null; }
		else { oThisOb.style.display = 'block'; openLists[oBsID][oLevel] = oThisOb; }
	} else { oThisOb.style.display = ( oThisOb.style.display == 'block' ) ? 'none' : 'block'; }
}
function stateToFromStr(oID,oFStr) {
	if( !document.getElementsByTagName || !document.childNodes || !document.createElement ) { return ''; }
	var baseElement = document.getElementById( oID ); if( !baseElement ) { return ''; }
	if( !oFStr && typeof(oFStr) != 'undefined' ) { return ''; } if( oFStr ) { oFStr = oFStr.split(':'); }
	for( var oStr = '', l = baseElement.getElementsByTagName(baseElement.tagName), x = 0; l[x]; x++ ) {
		if( oFStr && MWJisInTheArray( l[x].MWJuniqueID, oFStr ) && l[x].style.display == 'none' ) { l[x].parentNode.getElementsByTagName('a')[0].onclick(); }
		else if( l[x].style.display != 'none' ) { oStr += (oStr?':':'') + l[x].MWJuniqueID; }
	}
	return oStr;
}
function MWJisInTheArray(oNeed,oHay) { for( var i = 0; i < oHay.length; i++ ) { if( oNeed == oHay[i] ) { return true; } } return false; }
function selfLink(oRootElement,oClass,oExpand) {
	if(!document.getElementsByTagName||!document.childNodes) { return; }
	oRootElement = document.getElementById(oRootElement);
	for( var x = 0, y = oRootElement.getElementsByTagName('a'); y[x]; x++ ) {
		if( y[x].getAttribute('href') && !y[x].href.match(/#$/) && getRealAddress(y[x]) == getRealAddress(location) ) {
			y[x].className = (y[x].className?(y[x].className+' '):'') + oClass;
			if( oExpand ) {
				oExpand = false;
				for( var oEl = y[x].parentNode, ulStr = ''; oEl != oRootElement && oEl != document.body; oEl = oEl.parentNode ) {
					if( oEl.tagName && oEl.tagName == oRootElement.tagName ) { ulStr = oEl.MWJuniqueID + (ulStr?(':'+ulStr):''); } }
				stateToFromStr(oRootElement.id,ulStr);
} } } }
function getRealAddress(oOb) { return oOb.protocol + ( ( oOb.protocol.indexOf( ':' ) + 1 ) ? '' : ':' ) + oOb.hostname + ( ( typeof(oOb.pathname) == typeof(' ') && oOb.pathname.indexOf('/') != 0 ) ? '/' : '' ) + oOb.pathname + oOb.search; }

compactMenu('colapse',false,'');
//--></script>

		<?
		// Standard html footers
		end_main_frame();
}
elseif (isset($_GET['dllist'])) {
	$downloaders = array();
	$seeders = array();
	$subres = sql_query("SELECT seeder, peers.ip, port, peer_id, peers.last_action AS la, peers.userid, users.username, users.ratingsum, users.class FROM peers INNER JOIN users ON peers.userid = users.id WHERE peers.torrent = $id") or sqlerr(__FILE__, __LINE__);
	while ($subrow = mysql_fetch_array($subres)) {
		if ($subrow["seeder"])
		$seeders[] = $subrow;
		else
		$downloaders[] = $subrow;
	}
	print '<table>';
	tr("<div id=\"seeders\"></div>".$REL_LANG->say_by_key('details_seeding'), dltable($REL_LANG->say_by_key('details_seeding'), $seeders, $row), 1);
	tr("<div id=\"leechers\"></div>".$REL_LANG->say_by_key('details_leeching'), dltable($REL_LANG->say_by_key('details_leeching'), $downloaders, $row), 1);
	print '</table>';
}
stdfoot();

?>