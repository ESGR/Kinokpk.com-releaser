<?php
require_once("include/bittorrent.php");


if (!isset($_GET['step'])) {
print "��� ������������ ������ ��������� Kinokpk.com releaser 2.15. �������� ����������� � ��� ����� ���������.<hr/>";
print "Greetings for you from Kinokpk.com releaser 2.15 installation script. Follow the instructions and all will be OK.<hr/>";
print '<a href="install.php?step=1">����������/Continue</a>';

}

elseif ($_GET['step'] == 1) {
print "�������� �������� �������/Check server settings:<hr/>";
print "PHP version: ".(((version_compare(PHP_VERSION,"5.2.3",'>'))||(version_compare(PHP_VERSION,"5.2.3") === 1))?"<font color=\"green\">OK</font>":"<font color=\"red\">FAIL</font>")."<br/>";
print "MySQL support: ".(function_exists("mysql_connect")?"<font color=\"green\">OK</font>":"<font color=\"red\">FAIL</font>")."<br/>";
print "GD2 support: ".(function_exists("imagecreatefromjpeg")&&function_exists("imagecreatefrompng")&&function_exists("imagecreatefromgif")?"<font color=\"green\">OK</font>":"<font color=\"red\">FAIL</font>")."<br/>";
print "Zlib support: ".(extension_loaded("zlib")?"<font color=\"green\">OK</font>":"<font color=\"red\">FAIL</font>")."<br/>";
print "Iconv support: ".(function_exists("iconv")?"<font color=\"green\">OK</font>":"<font color=\"red\">FAIL</font>")."<hr/>";
print "���� ���� �� ���� �� �������� ����� ������ <font color=\"red\">FAIL</font>, �� ���������� ��������� �� �������������!<hr/>";
print "If one or more extensions is <font color=\"red\">FAIL</font>, it's not recommended to continue installation!<hr/>";
print '<a href="install.php?step=2">����������/Continue</a>';
}

elseif ($_GET['step'] == 2) {
print "������������ ��<hr/>";
print "Database configuration<hr/>";
print '<form action="install.php?step=3" method="POST">
<table><tr><td>���� / Mysql host</td><td><input type="text" name="mysql_host" value="localhost"></td></tr>
<tr><td>�� / Mysql database</td><td><input type="text" name="mysql_db"></td></tr>
<tr><td>������������ / Mysql user</td><td><input type="text" name="mysql_user"></td></tr>
<tr><td>������ / Mysql password</td><td><input type="password" name="mysql_pass"></td></tr>
<tr><td>����� �������� / Mysql charset</td><td><input type="text" name="mysql_charset" value="CP1251"></td></tr>
<tr><td colspan="2">������������ ���������� (�������� �������, ���� ���) / Use integration (leave fields blank if you prefer to NOT use integration)</tr>
<tr><td>���� / Mysql host</td><td><input type="text" name="fmysql_host" value="localhost"></td></tr>
<tr><td>�� / Mysql database</td><td><input type="text" name="fmysql_db"></td></tr>
<tr><td>������������ / Mysql user</td><td><input type="text" name="fmysql_user"></td></tr>
<tr><td>������ / Mysql password</td><td><input type="password" name="fmysql_pass"></td></tr>
<tr><td>����� �������� / Mysql charset</td><td><input type="text" name="fmysql_charset" value="CP1251"></td></tr>
<tr><td>������ ������ ������ / Table prefix</td><td><input type="text" name="fprefix" value="ibf_"></td></tr>
<tr><td colspan="2"><input type="submit" value="���������� / Continue"></td></tr>';
}

elseif ($_GET['step'] == 3) {
  $reqfields = array("mysql_host","mysql_db","mysql_user","mysql_pass");
  foreach ($reqfields as $field) {
    if (!$_POST[$field]) die('�� ��� ���� ��������� / You have some blank fields');
  }
  $mysql_host=$_POST['mysql_host'];
  $mysql_user=$_POST['mysql_user'];
  $mysql_db=$_POST['mysql_db'];
  $mysql_pass=$_POST['mysql_pass'];
  $mysql_charset=$_POST['mysql_charset'];
  $fmysql_host=$_POST['fmysql_host'];
  $fmysql_user=$_POST['fmysql_user'];
  $fmysql_db=$_POST['fmysql_db'];
  $fmysql_pass=$_POST['fmysql_pass'];
  $fmysql_charset=$_POST['fmysql_charset'];
  $fprefix =$_POST['fprefix'];
  
  $important_files = array(
'./torrents/',
'./avatars/',
'./cache/',
'./Sitemap.xml',
'./torrents/images/',
'./include/secrets.php',
);
print('�������� CHMOD / CHMOD check:<br/>');
  foreach($important_files as $file){

        if(!file_exists($file) || !is_writable($file)){
            print "$file: <font color=red>FAIL</font><br/>";
            die('<hr/>��������� ��� ���, ��������� ����� �����������, ���� ����� �������� CHMOD, ��� ���������� ����� ���� ������������� ��������<hr/>Check your FTP - missing files, or wrong CHMOD, to repeat this step refresh this page');
          }
        elseif(is_writable($file)){
            print "$file:  <font color=green>OK</font><br/>";
                   }
      }
      
        print('<hr/>������ ���� ������ / Importing database: ');
  relconn();
   /*  $a = mysql_get_server_info();
$b = substr($a, 0, strpos($a, "-"));
 if ($b<"5.0.7") die("���� ������ mysql ������� �� ���������� � Kinokpk.com releaser, ���������� 5.0.7+ / Your mysql server version is not allowed to use with Kinokpk.com releaser, it's MUST be 5.0.7+");
  */
   $strings = file("database.sql");
$query = '';
foreach ($strings AS $string)
{
  if (preg_match("/^\s?#/", $string) || !preg_match("/[^\s]/", $string))
     continue;
  else
  {
      $query .= $string;
      if (preg_match("/;\s?$/", $query))
      {
          mysql_query($query) or die('<font color="red">FAIL</font>, ������ MySQL / MySQL error ['.mysql_errno().']: ' . mysql_error());
            $query = '';
      }
  }
}
 print('<font color="green">OK</font><hr/>');
        $dbconfig = <<<HTML
<?

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

if(!defined('IN_TRACKER') && !defined('IN_ANNOUNCE')) die("Direct access to this page not allowed");

\$mysql_host = "{$mysql_host}";
\$mysql_user = "{$mysql_user}";
\$mysql_pass = "{$mysql_pass}";
\$mysql_db = "{$mysql_db}";
\$mysql_charset = "{$mysql_charset}";

\$fmysql_host = "{$fmysql_host}";
\$fmysql_user = "{$fmysql_user}";
\$fmysql_pass = "{$fmysql_pass}";
\$fmysql_db = "{$fmysql_db}";
\$fmysql_charset = "{$fmysql_charset}";
\$fprefix = "{$fprefix}";
?>
HTML;
print('������ ������������ � ���� / Write configuration to file: ');
        $con_file = fopen("include/secrets.php", "w+") or die("<font color=\"red\">FAIL</font><hr/> ��������, �� ���������� ������� ���� <b>include/secrets.php</b>.<br />��������� ������������ �������������� CHMOD! <hr/> Sorry, cant open <b>.include/secrets.php</b>.<br />Check CHMOD!");
fwrite($con_file, $dbconfig);
fclose($con_file);
print ('<font color="green">OK</font><hr/>');

print ('������ ���������, � ��������� ���� �� ��������� � �������� ����������<hr/>');
print ('Passwords were saved, next step will be main configuration<hr/>');
print '<a href="install.php?step=4">����������/Continue</a>';
}

elseif ($_GET['step'] == 4) {
  dbconn();
if (!isset($_GET['action'])){


print("�������� ��������� Kinokpk.com releaser ".RELVERSION."<hr/>");
print('<table width="100%" border="1">');
print('<form action="install.php?action=save&step=4" method="POST">');
print('<tr><td>������������ ���������� �������������:</td><td><input type="text" name="maxusers" size="6" value="'.$CACHEARRAY['maxusers'].'">�������������</td></tr>');
print('<tr><td>����� � ���, ����� ������� ������� ��������� �������:</td><td><input type="text" name="max_dead_torrent_time" size="3" value="'.$CACHEARRAY['max_dead_torrent_time'].'">������</td></tr>');
print('<tr><td>����������� ���������� ������� ��� ����������� �������� ��������:</td><td><input type="text" name="minvotes" size="2" value="'.$CACHEARRAY['minvotes'].'">�������</td></tr>');
print('<tr><td>���������� ����, �� ���������� ������� ��������� ���������������� ��������:</td><td><input type="text" name="signup_timeout" size="2" value="'.$CACHEARRAY['signup_timeout'].'">����</td></tr>');
print('<tr><td>�������� �������� (���������� ���������� � ��������) � �������:</td><td><input type="text" name="announce_interval" size="2" value="'.$CACHEARRAY['announce_interval'].'">�����</td></tr>');
print('<tr><td>������������ ������ ������-����� � ������:</td><td><input type="text" name="max_torrent_size" size="10" value="'.$CACHEARRAY['max_torrent_size'].'">����</td></tr>');
print('<tr><td>����� ����� (��� /):</td><td><input type="text" name="defaultbaseurl" size="30" value="'.$CACHEARRAY['defaultbaseurl'].'"> ��������, "http://www.kinokpk.com"</td></tr>');
print('<tr><td>�����, � �������� ����� ������������ ��������� �����:</td><td><input type="text" name="siteemail" size="30" value="'.$CACHEARRAY['siteemail'].'"> ��������, bot@kinokpk.com</td></tr>');
print('<tr><td>����� ��� ����� � ���������������:</td><td><input type="text" name="adminemail" size="30" value="'.$CACHEARRAY['adminemail'].'"> ��������, "admin@windows.lox"</td></tr>');
print('<tr><td>�������� ����� (title):</td><td><input type="text" name="sitename" size="80" value="'.$CACHEARRAY['sitename'].'"> ��������, "������� �������� �����������"</td></tr>');
print('<tr><td>�������� ����� (meta description):</td><td><input type="text" name="description" size="80" value="'.$CACHEARRAY['description'].'"> ��������, "����� ������� ���������� ������� ����"</td></tr>');
print('<tr><td>�������� ����� (meta keywords):</td><td><input type="text" name="keywords" size="80" value="'.$CACHEARRAY['keywords'].'"> ��������, "�������, ����������, ������, �������"</td></tr>');
print('<tr><td>����� ������� � ��������:</td><td><input type="text" name="autoclean_interval" size="4" value="'.$CACHEARRAY['autoclean_interval'].'">������</td></tr>');
print('<tr><td>��� �������� ��� ����������� ����� ��������:<br/><small>*�� ������ ������������ ������ <b>{datenow}</b> ��� ������ �������� ����</small></td><td><input type="text" name="yourcopy" size="60" value="'.$CACHEARRAY['yourcopy'].'"> ��������, "&copy; 2008-{datenow} ��� ����"</td></tr>');
print('<tr><td>���������� ���� ��� ������� ������ ��������� �� �������:</td><td><input type="text" name="pm_delete_sys_days" size="2" value="'.$CACHEARRAY['pm_delete_sys_days'].'">����</td></tr>');
print('<tr><td>���������� ���� ��� ������� ������ ��������� �� ������������:</td><td><input type="text" name="pm_delete_user_days" size="2" value="'.$CACHEARRAY['pm_delete_user_days'].'">����</td></tr>');
print('<tr><td>������������ ���������� ��������� � ������ �����:</td><td><input type="text" name="pm_max" size="4" value="'.$CACHEARRAY['pm_max'].'">���������</td></tr>');
print('<tr><td>����� ����� �������� �������� � ����:</td><td><input type="text" name="ttl_days" size="3" value="'.$CACHEARRAY['ttl_days'].'">����</td></tr>');
print('<tr><td>���� �������� �� ��������� (��� lang_%����%):</td><td><input type="text" name="default_language" size="10" value="'.$CACHEARRAY['default_language'].'"></td></tr>');
print('<tr><td>������������ ������ ������:</td><td><input type="text" name="avatar_max_width" size="3" value="'.$CACHEARRAY['avatar_max_width'].'">��������</td></tr>');
print('<tr><td>������������ ������ ������:</td><td><input type="text" name="avatar_max_height" size="3" value="'.$CACHEARRAY['avatar_max_height'].'">��������</td></tr>');
print('<tr><td>������� ������������� ������ �������� � ��� ��� ����������� 1 ��������:</td><td><input type="text" name="points_per_hour" size="2" value="'.$CACHEARRAY['points_per_hour'].'">�������</td></tr>');
print('<tr><td>����������� ���� ��� ������ � ���������������� (themes/%����%):</td><td><input type="text" name="default_theme" size="10" value="'.$CACHEARRAY['default_theme'].'"> �� ��������� "kinokpk"</td></tr>');
print('<tr><td>��������� ����������� � ��������� �������:</td><td><select name="nc"><option value="yes" '.($CACHEARRAY['nc']=="yes"?"selected":"").'>��</option><option value="no" '.($CACHEARRAY['nc']=="no"?"selected":"").'>���</option></select></td></tr>');
print('<tr><td>��������� �����������:</td><td><select name="deny_signup"><option value="1" '.($CACHEARRAY['deny_signup']==1?"selected":"").'>��</option><option value="0" '.($CACHEARRAY['deny_signup']==0?"selected":"").'>���</option></select></td></tr>');
print('<tr><td>��������� ����������� �� ������������:</td><td><select name="allow_invite_signup"><option value="1" '.($CACHEARRAY['allow_invite_signup']==1?"selected":"").'>��</option><option value="0" '.($CACHEARRAY['allow_invite_signup']==0?"selected":"").'>���</option></select></td></tr>');
print('<tr><td>������������ TTL (���� �������� ������� ���������):</td><td><select name="use_ttl"><option value="1" '.($CACHEARRAY['use_ttl']==1?"selected":"").'>��</option><option value="0" '.($CACHEARRAY['use_ttl']==0?"selected":"").'>���</option></select></td></tr>');
print('<tr><td>������������ ��������� ��������� �� e-mail:</td><td><select name="use_email_act"><option value="1" '.($CACHEARRAY['use_email_act']==1?"selected":"").'>��</option><option value="0" '.($CACHEARRAY['use_email_act']==0?"selected":"").'>���</option></select></td></tr>');
print('<tr><td>������������ ������� ����������� ������� �� �������:</td><td><select name="use_wait"><option value="1" '.($CACHEARRAY['use_wait']==1?"selected":"").'>��</option><option value="0" '.($CACHEARRAY['use_wait']==0?"selected":"").'>���</option></select></td></tr>');
print('<tr><td>������������ ������� �������������� (��������� �� �������������):</td><td><select name="use_lang"><option value="1" '.($CACHEARRAY['use_lang']==1?"selected":"").'>��</option><option value="0" '.($CACHEARRAY['use_lang']==0?"selected":"").'>���</option></select></td></tr>');
print('<tr><td>������������ �����:<br/><small>*�� ������ ������������������ �� <a target="_blank" href="http://recaptcha.net">ReCaptcha.net</a> � �������� ��������� � ��������� ����� ��� ������������� ���� �����</small></td><td><select name="use_captcha"><option  value="1" '.($CACHEARRAY['use_captcha']==1?"selected":"").'>��</option><option value="0" '.($CACHEARRAY['use_captcha']==0?"selected":"").'>���</option></select></td></tr>');
print('<tr><td>������������ ������� ������ (��������� �� �������������):</td><td><select name="use_blocks"><option value="1" '.($CACHEARRAY['use_blocks']==1?"selected":"").'>��</option><option value="0" '.($CACHEARRAY['use_blocks']==0?"selected":"").'>���</option></select></td></tr>');
print('<tr><td>������������ gzip ������ ��� �������:</td><td><select name="use_gzip"><option value="1" '.($CACHEARRAY['use_gzip']==1?"selected":"").'>��</option><option value="0" '.($CACHEARRAY['use_gzip']==0?"selected":"").'>���</option></select></td></tr>');
print('<tr><td>������������ ������� ����� �� IP/��������:</td><td><select name="use_ipbans"><option value="1" '.($CACHEARRAY['use_ipbans']==1?"selected":"").'>��</option><option value="0" '.($CACHEARRAY['use_ipbans']==0?"selected":"").'>���</option></select></td></tr>');
print('<tr><td>������������ ������:</td><td><select name="use_sessions"><option value="1" '.($CACHEARRAY['use_sessions']==1?"selected":"").'>��</option><option value="0" '.($CACHEARRAY['use_sessions']==0?"selected":"").'>���</option></select></td></tr>');
print('<tr><td>��� SMTP:</td><td><input type="text" name="smtptype" size="10" value="'.$CACHEARRAY['smtptype'].'"></td></tr>');
print('<tr><td>����-�������� � ��������:</td><td><input type="text" name="as_timeout" size="10" value="'.$CACHEARRAY['as_timeout'].'">������</td></tr>');
print('<tr><td>������������ �������� ��������� 5 ������������ (��������):</td><td><select name="as_check_messages"><option value="1" '.($CACHEARRAY['as_check_messages']==1?"selected":"").'>��</option><option value="0" '.($CACHEARRAY['as_check_messages']==0?"selected":"").'>���</option></select></td></tr>');
print('<tr><td>������������ ���������� � ������� IPB:</td><td><select name="use_integration"><option value="1" '.($CACHEARRAY['use_integration']==1?"selected":"").'>��</option><option value="0" '.($CACHEARRAY['use_integration']==0?"selected":"").'>���</option></select></td></tr>');
print('<tr><td>��� �������� ������� �� �����:</td><td><select name="exporttype"><option value="wiki" '.($CACHEARRAY['exporttype']=="wiki"?"selected":"").'>� ����-������</option><option value="post" '.($CACHEARRAY['exporttype']=="post"?"selected":"").'>��������������� � ����</option></select></td></tr>');
print('<tr><td>����� ������ (��� /):</td><td><input type="text" name="forumurl" size="60" value="'.$CACHEARRAY['forumurl'].'"> ��������, "http://forum.pdaprime.ru"</td></tr>');
print('<tr><td>������� ������:</td><td><input type="text" name="forumname" size="60" value="'.$CACHEARRAY['forumname'].'"> ��������, "pdaPRIME.ru"</td></tr>');
print('<tr><td>ID ������-�������:</td><td><input type="text" name="forum_bin_id" size="3" value="'.$CACHEARRAY['forum_bin_id'].'"></td></tr>');
print('<tr><td>����� ������������� ����� �������� �� �����:</td><td><input type="text" name="defuserclass" size="1" value="'.$CACHEARRAY['defuserclass'].'"> �� ��������� IPB, "3"</td></tr>');
print('<tr><td>ID ������ ��� �������� ������ �������<br/><small>*��� ������, ����� ������� �� ��������� � ��������� �����, ���� �������� ������ ��� ����������� ������</small>:</td><td><input type="text" name="not_found_export_id" size="3" value="'.$CACHEARRAY['not_found_export_id'].'"></td></tr>');
print('<tr><td>����� �� �������� ������ (��� /):</td><td><input type="text" name="emo_dir" size="10" value="'.$CACHEARRAY['emo_dir'].'"> �� ��������� IPB, "default"</td></tr>');
print('<tr><td>��������� ���� �����:</td><td><input type="text" name="re_publickey" size="80" value="'.$CACHEARRAY['re_publickey'].'"></td></tr>');
print('<tr><td>��������� ���� �����:</td><td><input type="text" name="re_privatekey" size="80" value="'.$CACHEARRAY['re_privatekey'].'"></td></tr>');
print('<tr><td>�����-�����:</td><td><select name="debug_mode"><option value="1" '.($CACHEARRAY['debug_mode']==1?"selected":"").'>��</option><option value="0" '.($CACHEARRAY['debug_mode']==0?"selected":"").'>���</option></select></td></tr>');

print('<tr><td colspan="2"><input type="submit" value="��������� ���������"><input type="reset" value="��������"></td></tr></table>');

}

elseif ($_GET['action'] == 'save'){
       $reqparametres = array('maxusers','max_dead_torrent_time','minvotes','signup_timeout','announce_interval','max_torrent_size','defaultbaseurl','siteemail','adminemail','sitename','description','keywords','autoclean_interval','yourcopy','pm_delete_sys_days','pm_delete_user_days','pm_max','ttl_days','default_language','avatar_max_width','avatar_max_height','points_per_hour','default_theme','nc','deny_signup','allow_invite_signup','use_ttl','use_email_act','use_wait','use_lang','use_captcha','use_blocks','use_gzip','use_ipbans','use_sessions','smtptype','as_timeout','as_check_messages','use_integration','debug_mode');
       $int_param = array('exporttype','forumurl','forumname','forum_bin_id','defuserclass','not_found_export_id','emo_dir');
       $captcha_param = array('re_publickey','re_privatekey');

       $updateset = array();

       foreach ($reqparametres as $param) {
         if (!isset($_POST[$param])) die("��������� ���� �� ��������� ($param)");
       $updateset[] = "UPDATE cache_stats SET cache_value=".sqlesc($_POST[$param])." WHERE cache_name='$param'";
       }

       if ($_POST['use_integration'] == 1) {
         foreach ($int_param as $param) {
         if (!$_POST[$param] || !isset($_POST[$param])) die("��������� ���� ��� ���������� � ������� �� ���������");
       $updateset[] = "UPDATE cache_stats SET cache_value=".sqlesc($_POST[$param])." WHERE cache_name='$param'";
       }
     }

            if ($_POST['use_captcha'] == 1) {
         foreach ($captcha_param as $param) {
         if (!$_POST[$param] || !isset($_POST[$param])) die("��������� ��� ��������� ����� ����� �� ����������");
       $updateset[] = "UPDATE cache_stats SET cache_value=".sqlesc($_POST[$param])." WHERE cache_name='$param'";
       }
     }

     foreach ($updateset as $query) sql_query($query);

     header("Location: install.php?step=5");

}
}

elseif ($_GET['step'] == 5) {
  print "��������� Kinokpk.com releaser 2.15 ������� ���������! �� ��������� ������� ���� ���� � database.sql � ������ �������, � ����� ��������� CHMOD 644 �� ���� include/secrets.php<hr/>";
print "You have successfully installed Kinokpk.com releaser 2.15! DO NOT FORGET TO DELETE THIS FILE AND database.sql FROM YOUR SERVER, ALSO SET CHMOD TO 644 ON include/sectets.php<hr/>";
print '<a href="javascript: self.close()" >������� ����/Close this window</a><hr/>';
print '<script language="javascript">alert(\'������� �� ����� Kinokpk.com releaser 2.15/Thank you for choosing Kinokpk.com releaser 2.15\');</script>';

}
