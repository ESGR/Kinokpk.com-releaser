<?php

// paying system via sms
require_once("include/bittorrent.php");
define("IN_CONTACT",true);
dbconn();
$amount = (int)$_GET['amount'];
if (!$amount) $amount = (int)$_POST['LMI_PAYMENT_AMOUNT'];

switch ($amount) {
	case 1: { $project_id = 28832; $discount=10;  break;}
	case 3: { $project_id = 28833; $discount=30; break;}
	case 10: {$project_id = 28834; break;}
}

$mode = trim((string)$_GET['mode']);
if (!$mode) $mode = trim((string)$_POST['mode']);
if ($mode=='wm') {
		if (isset($_GET['okay'])) {
			safe_redirect($REL_SEO->make_link('myrating'),1);
			stderr($REL_LANG->say_by_key('success'),"������ ������� ��������",'success');
		}
		if (isset($_GET['failed'])) {
						safe_redirect($REL_SEO->make_link('donate'),3);
			stderr($REL_LANG->say_by_key('failed'),'��� ���������� ������� �������� ������. <a href="'.$REL_SEO->make_link('donate').'">���������� ��� ���</a>.');
		}

		if (isset($_GET['process']))
		IF($_POST['LMI_PREREQUEST']==1) {
			if (($amount<>1) && ($amount<>3) && ($amount<>10)) die('������: �������� ����� ��������');

			// 3) ���������, �� ��������� �� ������� ��������.
			// C��������� ��� ��������� ������� � ��� ���������, ������� ������� ��� ���������.
			// ���� �������� �� ���������, �� ������� ������ � ��������� ������ �������.
			if(trim($_POST['LMI_PAYEE_PURSE'])!="Z113282224168") {
				die('������: �������� ������� ����������');
			}
			die('YES');
		}
	 else {
		if ($discount) {
			sql_query("UPDATE users SET discount=discount+$discount, modcomment=CONCAT(".sqlesc(date("Y-m-d") . "����� $discount ������\n").",modcomment) WHERE id=".(int)$_POST['id']);

		} else {
			sql_query("UPDATE users SET class=".UC_VIP.", modcomment=CONCAT(".sqlesc(date("Y-m-d") . "����� VIP\n").",modcomment) WHERE class<".UC_VIP." AND id=".(int)$_POST['id']);
			sql_query("UPDATE users SET dis_reason='', enabled=1 WHERE id = ".(int)$_POST['id']);
				
		}
		die('YES');
	}
loggedinorreturn();

stdhead('������������� ����� Webmoney merchant');
		begin_frame('������������� ����� WebMoney')
		?>
<form id="pay" name="pay" method="POST"
	action="https://merchant.webmoney.ru/lmi/payment.asp">
<p>��������� ���������� �� TorrentsBook.com</p>
<p>��� ���������� ��������� <?=$amount;?> WMZ...</p>
<p><input type="hidden" name="LMI_PAYMENT_AMOUNT" value="<?=$amount;?>">
<input type="hidden" name="LMI_PAYMENT_DESC"
	value="���������� �� TorrentsBook.com"> <input type="hidden"
	name="amount" value="<?=$amount;?>"> <input type="hidden" name="mode"
	value="wm"> <input type="hidden" name="id" value="<?=$CURUSER['id']?>"> <input type="hidden" name="result" value=""> <input
	type="hidden" name="LMI_PAYEE_PURSE" value="Z113282224168"> <input
	type="hidden" name="LMI_SIM_MODE" value="0"></p>
<p><input type="submit" value="���������� ������"></p>
</form>

		<?php

		stdfoot();
		die();
	}
	if (!$amount) {
		stdhead('������� ������ TorrentsBook.com');
		print('<table width="100%"><tr><td colspan="3">�� ������ �� �������� ���� ������� ��� ���������� �����������, ������� ������� "������" ��� <strong>������ VIP</strong> �� SMS, WebMoney ��� PayPal. ��� ��������� �������� ���� �� �������� ������ �����. ������� �������.</td></tr>
  	<tr><td align="center"><h1>SMS</h1></td><td align="center"><h1>WebMoney</h1></td><td align="center"><h1>PayPal</h1></td></tr>
	<tr><td align="center"><form action="'.$REL_SEO->make_link('donate').'"><input type="hidden" name="amount" value="10"><input type="submit" value="������������ 10$ � �������� VIP ������"></form></td><td align="center"><form action="'.$REL_SEO->make_link('donate').'"><input type="hidden" name="mode" value="wm"><input type="hidden" name="amount" value="10"><input type="submit" value="������������ 10$ � �������� VIP ������"></form></td><td align="center">
	');
		?>
		<form action="https://www.paypal.com/cgi-bin/webscr" method="post">
<input type="hidden" name="cmd" value="_s-xclick">
<table>
<tr><td><input type="hidden" name="on0" value="Enter your nickname or ID">VIP account, <b>$20</b></td></tr><tr><td><input type="hidden" name="os0" value="userid:<?=$CURUSER['id'];?>"></td></tr>
</table>
<input type="hidden" name="encrypted" value="-----BEGIN PKCS7-----MIIHTwYJKoZIhvcNAQcEoIIHQDCCBzwCAQExggEwMIIBLAIBADCBlDCBjjELMAkGA1UEBhMCVVMxCzAJBgNVBAgTAkNBMRYwFAYDVQQHEw1Nb3VudGFpbiBWaWV3MRQwEgYDVQQKEwtQYXlQYWwgSW5jLjETMBEGA1UECxQKbGl2ZV9jZXJ0czERMA8GA1UEAxQIbGl2ZV9hcGkxHDAaBgkqhkiG9w0BCQEWDXJlQHBheXBhbC5jb20CAQAwDQYJKoZIhvcNAQEBBQAEgYARnrVf+GdJvXr6bZ63bq7sG2GZZpy+lQ+dnD8A/fVDRD1Ub9ZIgdzIzvxsagiQRYclkeBvPbJ4RBOPiZnda3UR3if6fXlS39O5UHJHrGUk8EFxFmGIMBX6Dhv/5so9U4f1d+S6StVA3XSjbssvN3gD7CYHfF5MQSnOioG134dIojELMAkGBSsOAwIaBQAwgcwGCSqGSIb3DQEHATAUBggqhkiG9w0DBwQIaUc7wGP6ia2AgaiNL5YcL9RYkXnUF4K+7nEp1eGkqbodyZ8wgLwpvVquhlb6elA5bmqXwaq3Csgozed/jvZfVLgLkZ1yFmPsxjS9Xpdp5FRXbC8umYN+H6nlnT/+i+6tAVoJjMrye0yAKgtdM53pTcUino4LxB29M+if3oMTK0nnyjwjBRERILnsxD7oej4EGaTUQSIXo5bj6zkdi/QcqCpmVkUs72t+qwtsCvbhqI1Uqh+gggOHMIIDgzCCAuygAwIBAgIBADANBgkqhkiG9w0BAQUFADCBjjELMAkGA1UEBhMCVVMxCzAJBgNVBAgTAkNBMRYwFAYDVQQHEw1Nb3VudGFpbiBWaWV3MRQwEgYDVQQKEwtQYXlQYWwgSW5jLjETMBEGA1UECxQKbGl2ZV9jZXJ0czERMA8GA1UEAxQIbGl2ZV9hcGkxHDAaBgkqhkiG9w0BCQEWDXJlQHBheXBhbC5jb20wHhcNMDQwMjEzMTAxMzE1WhcNMzUwMjEzMTAxMzE1WjCBjjELMAkGA1UEBhMCVVMxCzAJBgNVBAgTAkNBMRYwFAYDVQQHEw1Nb3VudGFpbiBWaWV3MRQwEgYDVQQKEwtQYXlQYWwgSW5jLjETMBEGA1UECxQKbGl2ZV9jZXJ0czERMA8GA1UEAxQIbGl2ZV9hcGkxHDAaBgkqhkiG9w0BCQEWDXJlQHBheXBhbC5jb20wgZ8wDQYJKoZIhvcNAQEBBQADgY0AMIGJAoGBAMFHTt38RMxLXJyO2SmS+Ndl72T7oKJ4u4uw+6awntALWh03PewmIJuzbALScsTS4sZoS1fKciBGoh11gIfHzylvkdNe/hJl66/RGqrj5rFb08sAABNTzDTiqqNpJeBsYs/c2aiGozptX2RlnBktH+SUNpAajW724Nv2Wvhif6sFAgMBAAGjge4wgeswHQYDVR0OBBYEFJaffLvGbxe9WT9S1wob7BDWZJRrMIG7BgNVHSMEgbMwgbCAFJaffLvGbxe9WT9S1wob7BDWZJRroYGUpIGRMIGOMQswCQYDVQQGEwJVUzELMAkGA1UECBMCQ0ExFjAUBgNVBAcTDU1vdW50YWluIFZpZXcxFDASBgNVBAoTC1BheVBhbCBJbmMuMRMwEQYDVQQLFApsaXZlX2NlcnRzMREwDwYDVQQDFAhsaXZlX2FwaTEcMBoGCSqGSIb3DQEJARYNcmVAcGF5cGFsLmNvbYIBADAMBgNVHRMEBTADAQH/MA0GCSqGSIb3DQEBBQUAA4GBAIFfOlaagFrl71+jq6OKidbWFSE+Q4FqROvdgIONth+8kSK//Y/4ihuE4Ymvzn5ceE3S/iBSQQMjyvb+s2TWbQYDwcp129OPIbD9epdr4tJOUNiSojw7BHwYRiPh58S1xGlFgHFXwrEBb3dgNbMUa+u4qectsMAXpVHnD9wIyfmHMYIBmjCCAZYCAQEwgZQwgY4xCzAJBgNVBAYTAlVTMQswCQYDVQQIEwJDQTEWMBQGA1UEBxMNTW91bnRhaW4gVmlldzEUMBIGA1UEChMLUGF5UGFsIEluYy4xEzARBgNVBAsUCmxpdmVfY2VydHMxETAPBgNVBAMUCGxpdmVfYXBpMRwwGgYJKoZIhvcNAQkBFg1yZUBwYXlwYWwuY29tAgEAMAkGBSsOAwIaBQCgXTAYBgkqhkiG9w0BCQMxCwYJKoZIhvcNAQcBMBwGCSqGSIb3DQEJBTEPFw0xMDA0MTQxNTU3MjVaMCMGCSqGSIb3DQEJBDEWBBQhLyj6rCzOyqyxEd0IG7t9K0gCaTANBgkqhkiG9w0BAQEFAASBgCWPm0O7x9Xw9eJnscnMXjxQpIXxVsHU/b/oGKUtnUkNKlH5qbH9r2ELFp0BMIrff9TRRTjQX8zb07q0OGQb2YU+DUvKQ9JKvaaszWpXGqKhh696Enfhq3k8sP2uVyPq2rwxizfv2f8Z9GLTYLX8Eno9AG7Sho1p94cryLcdS5ys-----END PKCS7-----
">
<input type="image" src="https://www.paypal.com/en_US/i/btn/btn_buynow_SM.gif" border="0" name="submit" alt="PayPal - The safer, easier way to pay online!">
<img alt="" border="0" src="https://www.paypal.com/en_US/i/scr/pixel.gif" width="1" height="1">
</form>
		<?php
		print ('
	</td></tr>
  <tr><td align="center"><form action="'.$REL_SEO->make_link('donate').'"><input type="hidden" name="amount" value="3"><input type="submit" value="������������ 3$ � �������� 30 ������ ������"></form></td><td align="center"><form action="'.$REL_SEO->make_link('donate').'"><input type="hidden" name="mode" value="wm"><input type="hidden" name="amount" value="3"><input type="submit" value="������������ 3$ � �������� 30 ������ ������"></form></td><td align="center">');
		?>
		<form action="https://www.paypal.com/cgi-bin/webscr" method="post">
<input type="hidden" name="cmd" value="_s-xclick">
<table>
<tr><td><input type="hidden" name="on0" value="Enter your nickname or ID">30 points of discount, <b>$7</b></td></tr><tr><td><input type="hidden" name="os0" value="userid:<?=$CURUSER['id'];?>"></td></tr>
</table>
<input type="hidden" name="encrypted" value="-----BEGIN PKCS7-----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-----END PKCS7-----
">
<input type="image" src="https://www.paypal.com/en_US/i/btn/btn_buynow_SM.gif" border="0" name="submit" alt="PayPal - The safer, easier way to pay online!">
<img alt="" border="0" src="https://www.paypal.com/en_US/i/scr/pixel.gif" width="1" height="1">
</form>
		
		<?php print ('</td></tr>
  <tr><td align="center"><form action="'.$REL_SEO->make_link('donate').'"><input type="hidden" name="amount" value="1"><input type="submit" value="������������ 1$ � �������� 10 ������ ������"></form></td><td align="center"><form action="'.$REL_SEO->make_link('donate').'"><input type="hidden" name="mode" value="wm"><input type="hidden" name="amount" value="1"><input type="submit" value="������������ 1$ � �������� 10 ������ ������"></form></td><td align="center">');
		?>
		<form action="https://www.paypal.com/cgi-bin/webscr" method="post">
<input type="hidden" name="cmd" value="_s-xclick">
<table>
<tr><td><input type="hidden" name="on0" value="Enter your nickname or ID">10 points of discount, <b>$5</b></td></tr><tr><td><input type="hidden" name="os0" value="userid:<?=$CURUSER['id'];?>"></td></tr>
</table>
<input type="hidden" name="encrypted" value="-----BEGIN PKCS7-----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-----END PKCS7-----
">
<input type="image" src="https://www.paypal.com/en_US/i/btn/btn_buynow_SM.gif" border="0" name="submit" alt="PayPal - The safer, easier way to pay online!">
<img alt="" border="0" src="https://www.paypal.com/en_US/i/scr/pixel.gif" width="1" height="1">
</form>
		
		<?php print ('</td></tr>
  <tr><td colspan="3">* ��������: ���� ������� ��� <img src="pic/flag/russia.gif" title="���������� ���������"> ���������� ���������. ���� �� ���������� ��� �������� ��, �� ������ � ����� ������ ����� ���� ����������, ���� �������� ���� SMS ��������� ����� �������� �� ��������� ������ � ����� ������.</td></tr>
  <tr><td colspan="3">* Attention: Prices for PayPal are a little bigger than another due high tax rates. Also, we are checking paypal payments manually, so you will receive your privelege in ONE day.</td></tr>
  </table>');
		?>
<div
	align="center"><!-- begin WebMoney Transfer : accept label --> <a
	href="http://www.megastock.ru/"><img
	src="http://www.megastock.ru/Doc/88x31_accept/blue_rus.gif"
	alt="www.megastock.ru" border="0" /></a><br />
<!-- end WebMoney Transfer : accept label --> <!-- begin WebMoney Transfer : attestation label -->
<a
	href="https://passport.webmoney.ru/asp/certview.asp?wmid=627388830309"><img
	src="http://www.megastock.ru/doc/75x75_user/blue_rus.gif"
	alt="WMID:627388830309" border="0"><br />
<span style="font-size: 0, 7em;">��������� ��������</span></a> <!-- end WebMoney Transfer : attestation label --><br />
������� ������� � ���������� ���������� � ������� webmoney transfer: <b>support{�����}torrentsbook.com</b>,
��� WMID:627388830309, ���� �� ��������: 7-916-053-58-25</div>
<div class="sp-wrap">
<div class="sp-head folded clickable"><font color="red">��������� �
�������� ������ ����� ������� Webmoney Transfer</font></div>
<div class="sp-body">1. ������ ���������� ����� ������� WebMoney
Merchant � �������������� ������<br />
2. ����� ���������� ������� �� ����� �� ��������� ������, �� �������
�������<br />
3. � ������ ������������� ��������� ������� ����������� � <a
	href="<?=$REL_SEO->make_link('staff')?>">������������� �����</a> ��� ��
���������, ��������� ����<br />
4. � ������, ���� �� ����� ���� �������� �� ��������� ���� ������� ���
������� �������� ������ ������ (������ ������ - ������ ��� ��������), ��
���������� ��������� � ���� �� ��������� ���� ���������� � ������� ��
������� 3-� ����� ����� ���������� �������, � ������ ����� ���������� �
������ ������� �� ������� 5%. ��� ������������ ��������� ���� ������
������ (�� ������ �������).</div>
</div>
		<?php
		stdfoot();
		die();
	}
	elseif (($amount<>1) && ($amount<>3) && ($amount<>10)) stderr($REL_LANG->say_by_key('error'),'�������� ����� �������������');
	// ��������������: cp1251, koi, utf8
	$encoding = 'cp1251';
	// ��������� sms ���� � URL
	$url_restrict = false;
	// ������ ��������� ��� �����
	$limit = 1;
	// ���������� ������ ����� ������
	$lang_switcher = true;
	// ���� �� ���������
	$default_lang = 'ru';
	// ����� �������
	// see up
	//var_dump($project_id);
	//var_dump($amount);

	if ($lang_switcher) {
		$language = isset($_GET['lng']) ? $_GET['lng'] : (isset($_COOKIE['z_lng']) ? $_COOKIE['z_lng'] : $default_lang);
	} else {
		$language = $default_lang;
	}
	$result_code = false;
	$result_message = "closed";
	if (isset($_POST['code']) && preg_match('/^[a-z0-9]{4}-[a-z0-9]{4}$/', $_POST['code'])) {
		$check_url = 'http://check.smszamok.ru/check/?p='.$_POST['code'].'&id='.$project_id;
		if ($url_restrict) {
			$check_url .= "&url_restricted=".$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
		}
		if ($limit > 0) {
			$check_url .= "&limit=".$limit;
		}
		$handle = fopen($check_url, "r");
		if ($handle !== FALSE) {
			$result_message = fgets($handle, 255);
			$result_code = ($result_message == "true");
			fclose($handle);
		} else {
			$result_message = "server_busy";
		}
	}
	if (!$result_code) {
		readfile(($result_message == "server_busy") ?
	'http://iface.smszamok.ru/client/sorry.php?lng='.$language.'&enc='.$encoding :
	'http://iface.smszamok.ru/client/'.$language.'.iface.'.$encoding.'.php?pid='.$project_id.'&message='.$result_message.'&ls='.($lang_switcher?'1':'0'));
		// ������ ����
		die();
	}


	if ($discount) {
		sql_query("UPDATE users SET discount=discount+$discount, modcomment=".sqlesc(date("Y-m-d") . "����� $discount ������\n").$CURUSER['modcomment']." WHERE id={$CURUSER['id']}");

		safe_redirect($REL_SEO->make_link('myrating'),1);
		stderr($REL_LANG->say_by_key('success'),'�������, ��� ������� ���������� '.$discount.' ������ ������, ������ �� ��������� � �������� "��� �������"','success');
	} else {
		sql_query("UPDATE users SET class=".UC_VIP.", modcomment=".sqlesc(date("Y-m-d") . "����� VIP\n").$CURUSER['modcomment']." WHERE class<".UC_VIP." AND id={$CURUSER['id']}");
		if ($CURUSER['dis_reason'] == 'Your rating was too low.') sql_query("UPDATE users SET dis_reason='', enabled=1 WHERE id = {$CURUSER['id']}");

		safe_redirect($REL_SEO->make_link('myrating'),1);
		stderr($REL_LANG->say_by_key('success'),'�������, ��� ������� ��������� VIP ������, ������ �� ��������� � �������� "��� �������"','success');
}
?>