<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=windows-1251" />
<?php
$path = str_replace("js/tiny_mce/plugins/kinopoisk",'',dirname(__FILE__));
require_once ($path."include/bittorrent.php");
dbconn();
?>
<base href="<?=$CACHEARRAY['defaultbaseurl'];?>" />
<title>{#kinopoisk_dlg.title}</title>
<script type="text/javascript" src="js/tiny_mce/tiny_mce_popup.js"></script>
<script type="text/javascript"
	src="js/tiny_mce/plugins/kinopoisk/js/kinopoisk.js"></script>
</head>
<body style="display: none">
<div align="center">
<div class="title">{#kinopoisk_dlg.title}:<br />
<br />
</div>

<?php
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
		window.location="js/tiny_mce/plugins/kinopoisk/kinopoisk.php?id='.$parsID.'";
		//-->
	</SCRIPT>'); //���������������, ��������� javascript
	}

	else{
		//���� ��������� ��������� ������, ��� ������� ���������� �����
		$temparray = $matches[1];

		foreach ($temparray as $key2 => $tempresult){

			$result[$key2] = $tempresult;

			$result[$key2] = preg_replace("#(.*?)/.*?\">(.*?)</#is", "<a href=\"js/tiny_mce/plugins/kinopoisk/kinopoisk.php?id=$1\">$2</a>", $result[$key2])."   (".$matches_y[1][$key2].")   ".$matches2[1][$key2];
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

if (!$CURUSER) die('Only users enabled');

if (!isset($_GET['id']) && !isset($_GET['filmname'])) print('<table><tr><td>������� �������� ������:</td><td><form method="get"><input type="text" name="filmname"><br />��� ������ (�����������):<input type="text" name="filmyear" size="5">
<input type="submit" value="����������" />
</form></td></tr></table>');

require_once(ROOT_PATH."classes/parser/Snoopy.class.php");
$page = new Snoopy;

if (isset($_GET['filmname'])) {
	$film = RawUrlEncode($_GET['filmname']);
	$filmsafe = htmlspecialchars($_GET['filmname']);
	$filmyear = (int)$_GET['filmyear'];
	if (!$filmyear)
	$page->fetch("http://www.kinopoisk.ru/index.php?kp_query={$film}&x=0&y=0");
	else
	$page->fetch("http://www.kinopoisk.ru/index.php?level=7&m_act%5Bwhat%5D=content&m_act%5Bfind%5D={$film}&m_act%5Byear%5D={$filmyear}");
	$source = $page->results;
	if (!$source) die('Nothing found!');

	print("<table><tr><td align=\"center\">��������� �� ������� \"$filmsafe\" ������</td></tr>");

	$searched = search($source,$film);
	if (!$searched) die('Nothing found!');
	foreach ($searched as $searchedrow) {
		print("<tr><td>".$searchedrow."</td></tr>");
	}
	print ('</table>');
}
elseif (isset($_GET['id']) && $_GET['id'] != '') {
	if (!is_valid_id($_GET['id'])) die('Wrong ID');
	$id = (int)$_GET['id'];

	$page->fetch("http://www.kinopoisk.ru/level/1/film/$id/");
	$source = $page->results;

	if (!$source) die('Nothing found!');

	function clear($text){
		$text = preg_replace("#\t|\r|\x0B|\n#si","",$text);
		// $text = preg_replace("#\n(.*?)\n#si","\n",$text);
		$text = preg_replace("#\&\#133;|\&\#151;#si","",strip_tags(trim(html_entity_decode($text,ENT_QUOTES,"windows-1251"))));
		return $text;
	}
	function clearDescr($text){
		$text = preg_replace("#\t|\n|\r|\x0B#si","",$text);
		$text = strip_tags(trim(html_entity_decode($text,ENT_QUOTES,"windows-1251")), "<br></br>");

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
	$descr = clearDescr(get_content($source, 'descr'));
	$actors = format_actors(get_content($source, 'actors'));

	$kinopoiskrating = "<a href=\"http://www.kinopoisk.ru/level/1/film/$id/\"><img style=\"border: none;\" src=\"http://www.kinopoisk.ru/rating/$id.gif\" title=\"������� ����������\"/></a>";

	switch ($mpaarating){
		case "G": $mpaarating = "<img src=\"pic/mpaa/G.gif\" title=\"G - ��� ���������� �����������\"/> G - ��� ���������� �����������"; break;
		case "PG": $mpaarating ="<img src=\"pic/mpaa/PG.gif\" title=\"PG - ������������� ����������� ���������\"/> PG - ������������� ����������� ���������"; break;
		case "PG-13": $mpaarating = "<img src=\"pic/mpaa/PG-13.gif\" title=\"PG-13 - ����� �� 13 ��� �������� �� ���������\"/> PG-13 - ����� �� 13 ��� �������� �� ���������"; break;
		case "R": $mpaarating = "<img src=\"pic/mpaa/R.gif\" title=\"R - ����� �� 17 ��� ����������� ����������� ���������\"/> R - ����� �� 17 ��� ����������� ����������� ���������"; break;
		case "NC-17": $mpaarating = "<img src=\"pic/mpaa/NC-17.gif\" title=\"NC-17 - ����� �� 17 ��� �������� ��������\"/> NC-17 - ����� �� 17 ��� �������� ��������"; break;
	}

	print ('<script type="text/javascript" language="javascript">
function fillform(){
  //window.opener.document.forms["upload"].elements["name"].value = "'.$rusname.'/ '.$origname.'";

  var content = \'<i>���������� � ������:</i><br/><b>����:</b> '.$genre.'<br/><br/><b>������������ ��������:</b> '.$origname.'<br/><b>��� ������:</b> '.$year.'<br/><b>��������:</b> '.$director.'<br/><b>� �����:</b> '.$actors.'<br/><b>��������:</b> '.$country.'<br/><b>�����������������:</b> '.$time.'<br/><b>������� IMDB:</b> '.$imdbrating.'<br/><b>������� ����������:</b> '.$kinopoiskrating.'<br/><b>������� MPAA:</b> '.$mpaarating.'<br/><b>� ������:</b><br/>'.$descr.'\';

  KinopoiskDialog.insert(content);
  }
</script>');
	print ("<table width=\"100%\" border=\"1\"><tr>
<td>����:</td><td>$genre</td></tr>
<td>������� ��������:</td><td>$rusname</td></tr>
<tr><td>������������ ��������:</td><td>$origname</td></tr>
<tr><td>� �����:</td><td>$actors</td></tr>
<tr><td>������ ������������:</td><td>$country</td></tr>
<tr><td>��� ������:</td><td>$year</td></tr>
<tr><td>��������:</td><td>$director</td></tr>
<tr><td>������� MPAA:</td><td>".(($mpaapic)?"<img src=\"pic/mpaa/$mpaapic.gif\"/>":"")."</td></tr>
<tr><td>������� IMDB:</td><td>$imdbrating</td></tr>
<tr><td>������� ����������</td><td>$kinopoiskrating</td></tr>
<tr><td>�����������������:</td><td>$time</td></tr>
<tr><td>��������:</td><td>$descr</td></tr>");
	print ('<tr><td align="center">��� ���������� �����?</td>
<td align="center">[<a href="javascript:fillform();">��, ��������� �����</a>]<br/>[<a href="js/tiny_mce/plugins/kinopoisk/kinopoisk.php">��������� �����</a>]<br/>[<a href="javascript:window.close()">������� ����</a>]</td></tr>
</table>');
}

//$kinopoiskarray = sql_query("SELECT image FROM kinopoisk WHERE class <= ".get_user_class()." ORDER BY sort ASC");
//while ((list($img) = mysql_fetch_array($kinopoiskarray))) print('<tr><td><a href="javascript:KinopoiskDialog.insert(\''.$img.'\',\'\');"><img src="'.$CACHEARRAY['defaultbaseurl'].'/pic/kinopoisk/'.$img.'" border="0" alt="" title="" /></a></td></tr>');

?></div>
</body>
</html>
