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
stdhead("������� ����� {$CACHEARRAY['sitename']}");
begin_frame("������� ����� {$CACHEARRAY['sitename']}");
end_frame();

$res = sql_query("SELECT `id`, `rule`, `flag` FROM `rules` WHERE `type`='categ' ORDER BY `order` ASC");
while ($arr = mysql_fetch_array($res, MYSQL_BOTH)) {
	$rules_categ[$arr[id]][title] = $arr[rule];
	$rules_categ[$arr[id]][flag] = $arr[flag];
}

/*---------------------------------------------------------------------*/
$res = sql_query("SELECT `id`, `flag`, `rule`, `categ` FROM `rules` WHERE `type`='item' ORDER BY `order` ASC");
while ($arr = mysql_fetch_array($res, MYSQL_BOTH)) {
	$rules_categ[$arr[categ]][items][$arr[id]][rule] = $arr[rule];
	$rules_categ[$arr[categ]][items][$arr[id]][flag] = $arr[flag];
}
/*---------------------------------------------------------------------*/

if (isset($rules_categ)) {
	// gather orphaned items
	foreach ($rules_categ as $id => $temp) {
		if (!array_key_exists("title", $rules_categ[$id])) {
			foreach ($rules_categ[$id][items] as $id2 => $temp) {
				$rules_orphaned[$id2][rule] = $rules_categ[$id][items][$id2][rule];
				$rules_orphaned[$id2][flag] = $rules_categ[$id][items][$id2][flag];
				unset($rules_categ[$id]);
			}
		}
	}
	begin_frame("<a id=\"top\"/>����������");
	print("<ul>");
	foreach ($rules_categ as $id => $temp) {
		if ($rules_categ[$id][flag] == "1") {
			print("<li><a href=\"rules.php#n". $id ."\"><b>". $rules_categ[$id][title] ."</b></a></li>\n");
			/*---------------------------------------------------------------------
			 if (array_key_exists("items", $rules_categ[$id])) {
			 foreach ($rules_categ[$id][items] as $id2 => $temp) {
			 if ($rules_categ[$id][items][$id2][flag] == "1")
			 print("<li><a href=\"rules.php#". $id2 ."\" class=\"altlink\">". $rules_categ[$id][items][$id2][rule] ."</a></li>\n");
			 elseif ($rules_categ[$id][items][$id2][flag] == "2")
			 print("<li><a href=\"rules.php#". $id2 ."\" class=\"altlink\">". $rules_categ[$id][items][$id2][rule] ."</a> <img src=\"pic/updated.png\" alt=\"���������\" title=\"���������\" align=\"absbottom\"></li>\n");
			 elseif ($rules_categ[$id][items][$id2][flag] == "3")
			 print("<li><a href=\"rules.php#". $id2 ."\" class=\"altlink\">". $rules_categ[$id][items][$id2][rule] ."</a> <img src=\"pic/new.png\" alt=\"�����\" title=\"�����\" align=\"absbottom\"></li>\n");
			 }
			 }
			 ---------------------------------------------------------------------*/
		}
	}
	print("</ul>\n");
	end_frame();
	foreach ($rules_categ as $id => $temp) {
		if ($rules_categ[$id][flag] == "1") {
			$frame = $rules_categ[$id][title] ." - <a href=\"rules.php#top\">������</a>";
			begin_frame($frame);
			print("<a id=\"n". $id ."\"/>\n<ul>\n");
			if (array_key_exists("items", $rules_categ[$id])) {
				foreach ($rules_categ[$id][items] as $id2 => $temp) {
					if ($rules_categ[$id][items][$id2][flag] != "0") {
						print("<li><b>". $rules_categ[$id][items][$id2][rule] ."</b><a id=\"n". $id2 ."\"/></li>\n");
					}
				}
			}
		 print("</ul>");
		 end_frame();
		}
	}
}

stdfoot();
?>