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

//if (@strpos($_SERVER['HTTP_REFERER'],"upload.php?type=1") === false) die ("Direct access to this script not allowed.");

require_once "include/bittorrent.php";
dbconn(false);
loggedinorreturn();

if ($CURUSER) {
	$ss_a = @mysql_fetch_array(@sql_query("SELECT uri FROM stylesheets WHERE id=" . $CURUSER["stylesheet"]));
	if ($ss_a) $ss_uri = $ss_a["uri"];
}
if (!$ss_uri) {
	$ss_uri = $CACHEARRAY['default_theme'];
}

function search($source,$text)
{

	$result = false;
	/* ���������� ��������� ��� ��� �������� (����������) */
	$searchfilms = "#<a class=\"all\" href=\"/level/1/film/(.*?)a>#si";

	/*���������� ��������� ��� ���� �������� (...Terminator)*/
	$searchfilms2 = "#<font color=\"\#999999\">(.*?)</font>#si";

	/*���������� ��������� ��� ������� ����*/
	$searchyear = "#\[year\]/(\d{4})/#si";

	/*���������� ��������� ��� ������� ��������� ������ �� ����������
	 ���� ����� ������ ������ ��������� ������ ����, � � ��������� $source
	 ���������� �������� ������ ������ �������� ������ (��������, ���������)*/
	$search_one_id = "#img src=\"/images/film/([0-9]+)\.jpg#si";
	preg_match_all ($searchfilms, $source, $matches);
	preg_match_all($searchfilms2, $source, $matches2);
	preg_match_all($searchyear, $source, $matches_y);


	if (!$matches[1]){
		preg_match_all($search_one_id, $source, $matches_one);
		$parsID = $matches_one[1][0];
		//����� �������������� �� �������� ��������� �� id (��������, ���������)
		//header ("Location: parser.php?id=$parsID"); - ��������������� �������� ������ ���� �������� ������ ��������
		if (!is_numeric($parsID)) die('����� � ����� ������ �� ������, ���������� ������ �� ������������� ��������.');

		print('
    <SCRIPT type="text/javascript">
		<!--
		window.location="parser.php?id='.$parsID.'";
		//-->
	</SCRIPT>'); //���������������, ��������� javascript
	}

	else{
		//���� ��������� ��������� ������, ��� ������� ���������� �����
		$temparray = $matches[1];

		foreach ($temparray as $key2 => $tempresult){

			$result[$key2] = $tempresult;

			$result[$key2] = preg_replace("#(.*?)/.*?\">(.*?)</#is", "<a href=\"?id=$1\">$2</a>", $result[$key2])."   (".$matches_y[1][$key2].")   ".$matches2[1][$key2];
			$result[$key2] .= ($matches2[1][$key2]) ? "   (".$matches_y[1][$key2].")" : "" ;

		}
	}

	return $result;

}


function get_content($text, $option)

{
	global $id;
	if ($option == 'rusname') {
		$search = "#\<h1 style=\"margin: 0; padding: 0\" class=\"moviename-big\"\>(.*?)\</h1\>#si";
	}
	elseif ($option == 'origname') {

		$search = "#\<span style=\"color: \#666; font-size: 13px\"\>(.*?)\</span\>#si";
	}
	elseif ($option == 'country') {
		$search = "#������</td>(.*?)</td>#si";
		$parse = 1;
	}
	elseif ($option == 'year') {
		$search = "#���</td>(.*?)</td>#si";
		$parse = 1;
	}
	elseif ($option == 'director') {
		$search = "#��������</td>(.*?)</td>#si";
		$parse = 1;
	}
	elseif ($option == 'scenario') {
		$search = "#��������</td>(.*?)</td>#si";
		$parse = 1;
	}
	elseif ($option == 'producer') {
		$search = "#��������</td>(.*?)</td>#si";
		$parse = 1;
	}
	elseif ($option == 'operator') {
		$search = "#��������</td>(.*?)</td>#si";
		$parse = 1;
	}
	elseif ($option == 'time') {
		$search = "#�����</td>(.*?)</td>#si";

	}
	elseif ($option == 'mpaa') {
		$search = "#src=\"/images/mpaa/(.*?)\.gif\"#si";
	}
	elseif ($option == 'imdb') {
		$search = "#IMDB: (.*?)</div>#si";
	}
	elseif ($option == 'descr') {
		$search = "#<tr><td colspan=3 style=\"padding:10px;padding-left:20px;\" class=\"news\">(.*?)</td></tr>#si";
	}
	elseif ($option == 'kinopoisk') {
		$search = "#<a href=\"/level/83/film/".$id."/\" class=\"continue\" style=\"background: url\(/images/dot_or.gif\) 0 93% repeat-x; font-weight: normal !important; text-decoration: none\">(.*?)<span#si";
	}
	elseif ($option == 'kinopoisktotal')
	{
		$search = "#<span style=\"font:100 14px tahoma, verdana\">&nbsp;&nbsp;(.*?)</span>#si";
	}

	elseif ($option == 'actors') {
		$search = '#� ������� �����:</span></td>(.*?)(���� �����������:</td>|<!-- /������ ������ -->)#si';
		$parse = 1;
	}

	elseif ($option == 'genre') {
		$search = "#����</td><td>(.*?)</td>#si";
		$parse = 1;
	}

	elseif ($option == 'budget') {
		$search = "#������</td>(.*?)</td>#si";
		$parse = 1;
	}

	elseif ($option == 'cashusa') {
		$search = "#����� � ���</td>(.*?)</td>#si";
		$parse = 1;
	}

	elseif ($option == 'cashworld') {
		$search = "#����� � ����</td>(.*?)</td>#si";
		$parse = 1;
	}

	elseif ($option == 'cashrus') {
		$search = "#����� � ������</td>(.*?)</td>#si";
		$parse = 1;
	}

	elseif ($option == 'dvdusa') {
		$search = "#DVD � ���</td>(.*?)</td>#si";
		$parse = 1;
	}

	elseif ($option == 'dvdru') {
		$search = "#����� �� DVD</td>(.*?)</td>#si";
		$parse = 1;
	}

	elseif ($option == 'premierworld') {
		$search = "#�������� \(���\)</td>(.*?)</td>#si";

		$parse = 1;
	}

	elseif ($option == 'premierrus') {
		$search = "#�������� \(��\)</td>(.*?)</td>#si";
		$parse = 1;
	}

	elseif ($option == 'bluray') {
		$search = "#����� �� Blu-Ray</td>(.*?)</td>#si";
		$parse = 1;
	}

	$result = false;

	preg_match_all ($search, $text, $matches);
	$result = $matches[1][0];

	if ($parse) {
		$result = ParseUrls($result);
	}

	return $result;
}
?>
<html>
<head>
<title><?=$CACHEARRAY['sitename']?> :: ��������� ����� ��� ������</title>
<link rel="stylesheet" href="./themes/<?=$ss_uri."/".$ss_uri?>.css"
	type="text/css" />
</head>
<table width="100%" border="1" cellspacing="2" cellpadding="2">
	<h2>����� ������ �� Kinopoisk.ru</h2>
	<?

	if (!isset($_GET['id']) && !isset($_GET['filmname'])) print('<tr><td>������� �������� ������:</td><td><form method="get"><input type="text" name="filmname"><br />��� ������:<input type="text" name="filmyear" size="5">
<input type="submit" value="����������" />
</form></td></tr>');

	include "classes/parser/Snoopy.class.php";
	$page = new Snoopy;

	if (isset($_GET['filmname'])) {
		$film = RawUrlEncode($_GET['filmname']);
		$filmsafe = htmlspecialchars($_GET['filmname']);
		$filmyear = (int)$_GET['filmyear'];
		if ($filmyear)
		$page->fetch("http://www.kinopoisk.ru/index.php?kp_query={$film}&x=0&y=0");
		else
		$page->fetch("http://www.kinopoisk.ru/index.php?level=7&m_act%5Bwhat%5D=content&m_act%5Bfind%5D={$film}&m_act%5Byear%5D={$filmyear}");
		$source = $page->results;
		if (!$source) die('Nothing found!');

		print("<tr><td align=\"center\">��������� �� ������� \"$filmsafe\" ������</td></tr>");

		$searched = search($source,$film);
		if (!$searched) die('Nothing found!');
		foreach ($searched as $searchedrow) {
			print("<tr><td>".$searchedrow."</td></tr>");
		}
	}
	elseif (isset($_GET['id']) && $_GET['id'] != '') {
		if (!is_numeric($_GET['id'])) die('Wrong ID');
		$id = $_GET['id'];

		$page->fetch("http://www.kinopoisk.ru/level/1/film/$id/");
		$source = $page->results;

		if (!$source) die('Nothing found!');

		function clear($text){
			$text = preg_replace("#\t|\r|\x0B|\n#si","",$text);
			// $text = preg_replace("#\n(.*?)\n#si","\n",$text);
			$text = preg_replace("#\&\#133;|\&\#151;#si","",strip_tags(trim(html_entity_decode($text,ENT_QUOTES,"Windows-1251"))));
			return $text;
		}
		function clearDescr($text){
			$text = preg_replace("#\t|\n|\r|\x0B#si","",$text);
			$text = strip_tags(trim(html_entity_decode($text,ENT_QUOTES,"Windows-1251")), "<br></br>");

			return $text;
		}

		function ParseUrls($text){
			$text = preg_replace("#\t|\r|\x0B#si","",$text);
			$text = strip_tags(trim($text), "<a></a>");
			//$text = preg_replace("#.*?(<a.*</a>).*#is", "$1", $text);
			$text = preg_replace("#<a href=\"(.*?)>(.*?)</a>#is", "\\2", $text);
			$text = trim(str_replace('...', '', $text));

			return $text;
		}

		function format_actors($text){
			$text = preg_replace("#\t|\r|\x0B#si","",$text);
			$text = preg_replace("#\n#si",", ",$text);
			$text = preg_replace("#\&\#133;|\&\#151;#si","",strip_tags(trim(html_entity_decode($text,ENT_QUOTES))));
			$text = str_replace(", , , , ", "", $text);
			return $text;
		}

		$rusname = clear(get_content($source, 'rusname'));
		$origname = clear(get_content($source, 'origname'));
		$country = clear(get_content($source, 'country'));
		$year = clear(get_content($source, 'year'));
		$director = clear(get_content($source, 'director'));
		$genre = clear(get_content($source, 'genre'));

		$mpaarating = clear(get_content($source, 'mpaa'));
		$mpaapic = $mpaarating;
		$imdbrating = clear(get_content($source, 'imdb'));
		$time = clear(get_content($source, 'time'));
		$descr_tab = clearDescr(get_content($source, 'descr'));
		$descr = preg_replace("#<br>|</br>|<br />#si","\n",$descr_tab);
		$actors = format_actors(get_content($source, 'actors'));
		$kinopoiskrating = clear(get_content($source, 'kinopoisk').get_content($source,'kinopoisktotal'))."[url=http://www.kinopoisk.ru/level/1/film/$id/][img]http://www.kinopoisk.ru/rating/$id.gif[/img][/url]";
		switch ($mpaarating){
			case "G": $mpaarating = "[img][siteurl]/pic/mpaa/G.gif[/img] G - ��� ���������� �����������"; break;
			case "PG": $mpaarating ="[img][siteurl]/pic/mpaa/PG.gif[/img] PG - ������������� ����������� ���������"; break;
			case "PG-13": $mpaarating = "[img][siteurl]/pic/mpaa/PG-13.gif[/img] PG-13 - ����� �� 13 ��� �������� �� ���������"; break;
			case "R": $mpaarating = "[img][siteurl]/pic/mpaa/R.gif[/img] R - ����� �� 17 ��� ����������� ����������� ���������"; break;
			case "NC-17": $mpaarating = "[img][siteurl]/pic/mpaa/NC-17.gif[/img] NC-17 - ����� �� 17 ��� �������� ��������"; break;
		}

		print ('<script type="text/javascript" language="javascript">
function fillform(){
  window.opener.document.forms["upload"].elements["name"].value = "'.$rusname.($origname?' / '.$origname:'').'";

  '); //window.opener.document.forms["upload"].elements["val[1]"].value = "'.$origname.'";
		print('window.opener.document.forms["upload"].elements["val[2]"].value = "'.$year.'";
  window.opener.document.forms["upload"].elements["val[3]"].value = "'.$director.'";
  window.opener.document.forms["upload"].elements["val[4]"].value = "'.$actors.'";
  window.opener.document.forms["upload"].elements["val[5]"].value = "'.$country.'";
  window.opener.document.forms["upload"].elements["val[6]"].value = "'.$time.'";
  window.opener.document.forms["upload"].elements["val[9]"].value = "'.$imdbrating.'";
  window.opener.document.forms["upload"].elements["val[10]"].value = "'.$kinopoiskrating.'";
  window.opener.document.forms["upload"].elements["val[11]"].value = "'.$mpaarating.'";

  window.opener.document.forms["upload"].elements["val[18]"].value = "'.$descr.'";
  
    window.opener.document.forms["upload"].elements["val[19]"].value = "'.$origname.'";
  window.opener.document.forms["upload"].elements["val[20]"].value = "'.$year.'";
  window.opener.document.forms["upload"].elements["val[23]"].value = "'.$director.'";
  window.opener.document.forms["upload"].elements["val[24]"].value = "'.$actors.'";
  window.opener.document.forms["upload"].elements["val[25]"].value = "'.$country.'";
  window.opener.document.forms["upload"].elements["val[50]"].value = "'.$time.'";
  window.opener.document.forms["upload"].elements["val[29]"].value = "'.$imdbrating.'";
  window.opener.document.forms["upload"].elements["val[30]"].value = "'.$kinopoiskrating.'";
  window.opener.document.forms["upload"].elements["val[31]"].value = "'.$mpaarating.'";

  window.opener.document.forms["upload"].elements["val[32]"].value = "'.$descr.'";

  }
</script>');
		print ("<table width=\"100%\" border=\"1\"><tr><td>������� ��������:</td><td>$rusname</td></tr>
<tr><td>������������ ��������:</td><td>$origname</td></tr>
<tr><td>� �����:</td><td>$actors</td></tr>
<tr><td>������ ������������:</td><td>$country</td></tr>
<tr><td>��� ������:</td><td>$year</td></tr>
<tr><td>��������:</td><td>$director</td></tr>
<tr><td>������� MPAA:</td><td>".(($mpaapic)?"<img src=\"pic/mpaa/$mpaapic.gif\">":"")."</td></tr>
<tr><td>������� IMDB:</td><td>$imdbrating</td></tr>
<tr><td>������� ����������</td><td>$kinopoiskrating</td></tr>
<tr><td>�����������������:</td><td>$time</td></tr>
<tr><td>��������:</td><td>$descr</td></tr>");
		print ('<tr><td align="center">��� ���������� �����?</td>
<td align="center">[<a href="javascript:fillform();">��, ��������� �����</a>]<br/>[<a href="parser.php">��������� �����</a>]<br/>[<a href="javascript:window.close()">������� ����</a>]</td></tr>
');
}

?>
</table>
</html>
