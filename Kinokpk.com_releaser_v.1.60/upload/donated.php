<?

require "include/bittorrent.php";
dbconn();
loggedinorreturn();

if (isset($_GET["mode"]))
	$mode = $_GET["mode"];

stdhead("� �����������");

print("<table class=\"embedded\" width=\"400\" border=\"1\" cellspacing=\"0\" cellpadding=\"5\">");
print("<tr><td class=\"colhead\">���</td><td class=\"colhead\">�����</td></tr>");

/*switch ($mode) {
	case "paypal":
		print("<form action=\"donated.php?mode=$mode\" mode=\"post\">");
		print("</form>");
		break;
	case "webmoney":
		print("<form action=\"donated.php?mode=$mode\" mode=\"post\">");
		print("</form>");
		break;
	case "western":
		print("<form action=\"donated.php?mode=$mode\" mode=\"post\">");
		print("</form>");
		break;
	default:
		print("<form action=\"donated.php?mode=$mode\" mode=\"post\">");
		print("<select name=\"mode\"><option value=\"\" selected>�������� �������</option><option value=\"paypal\">PayPal</option><option value=\"webmoney\">WebMoney</option><option value=\"western\">Western Union</option></select>");
		print("<input type=\"submit\" value=\"��������\" />");
		print("</form>");
		break;
}*/

print("<tr><td><img src=\"http://www.kinoclub.org/pic/paypal.gif\" alt=\"PayPal\" title=\"PayPal\" /></td><td><input type=\"hidden\" name=\"mode\" value=\"paypal\" />ID ��������: <input type=\"text\" name=\"transaction_id\" /><br />�����: <input type=\"text\" name=\"payee\" /><br />���� ����� � � - ���������� ��� ����������. ����� - USD.</td></tr>"); // PayPal
print("<tr><td><img src=\"http://uucyc.ru/base/logo-wu.gif\" alt=\"Western Union\" title=\"Western Unions\" /></td><td><input type=\"hidden\" name=\"mode\" value=\"western\" />MTCN ��������: <input type=\"text\" name=\"mtcn\" /><br />��� �����������: <input type=\"text\" name=\"sender_name\" /><br />������� �����������: <input type=\"text\" name=\"sender_surname\" /><br />����� �����������: <input type=\"text\" name=\"sender_address\" /><br />�����: <input type=\"text\" name=\"payee\" /><br /></td></tr>");
print("<tr><td colspan=\"2\"><div align=\"center\"><input type=\"submit\" value=\"�������� � �������������\" /></div></td></tr>");

print("</table>");

stdfoot();

?>