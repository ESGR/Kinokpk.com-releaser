<?php
/**
 * Just a redirect script
 * @license GNU GPLv3 http://opensource.org/licenses/gpl-3.0.html
 * @package Kinokpk.com releaser
 * @author ZonD80 <admin@kinokpk.com>
 * @copyright (C) 2008-now, ZonD80, Germany, TorrentsBook.com
 * @link http://dev.kinokpk.com
 */

require_once ("include/bittorrent.php");
dbconn(false);

$url = strip_tags(trim(((string)preg_replace("#/away.php\?url=#i", "", getenv("REQUEST_URI")))));
print('<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="ru" lang="ru">
<head>
<meta http-equiv="Content-Type" content="text/html; charset='.$tracker_lang['language_charset'].'" />
<meta name="Description" content="'.$CACHEARRAY['description'].'" />
<meta name="Keywords" content="'.$CACHEARRAY['keywords'].'" />
<title>'.$CACHEARRAY['sitename'].' - ������� �� ������� ������</title>
</head>
<body  style="padding:20px 180px; font-size:12px; font-family:Tahoma; line-height:200%">
<h2>'.$CACHEARRAY['sitename'].' - ������� �� ������� ������</h2>

�� ��������� ���� '.$CACHEARRAY['sitename'].' �� ������� ������ <b>'.$url.'</b>, ��������������� ����� �� ����������. <br/>
<a href="'.$CACHEARRAY['defaultbaseurl'].'/staff.php">�������������</a> '.$CACHEARRAY['sitename'].' �� ����� ��������������� �� ���������� ����� <b>'.$url.'</b> � ������������ ����������� <b>�� ���������</b> ������� ����� ������, ������� ��������� � '.$CACHEARRAY['sitename'].' (�������� <b>e-mail</b>, <b>������</b> � <b>cookies</b>), �� ��������� ������. <br/><br/>
����� ����, ���� <b>'.$url.'</b> ����� ��������� ������, ������ � ������ ����������� ���������, ������� ��� ������ ����������. <br/>
���� � ��� ��� ��������� ��������� �������� ����� �����, ����� ����� �� ���� �� ����������, ���� ���� �� ����� �������� ��� ������ �� ������ �� ����� <a href="'.$CACHEARRAY['defaultbaseurl'].'/friends.php">������.</a> <br/><br/>
���� �� ��� �� ����������, ������� �� <a href="'.$url.'" id="to_go">'.$url.'</a>. <br/>
���� �� �� ������ ��������� ������������� ������ �������� � ����������, ������� <a href="javascript:history.go(-1);">������</a>.
</body>
</html>
');
?>