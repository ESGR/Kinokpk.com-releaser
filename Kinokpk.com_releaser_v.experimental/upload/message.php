<?php
/**
 * Private messages mailbox
 * @license GNU GPLv3 http://opensource.org/licenses/gpl-3.0.html
 * @package Kinokpk.com releaser
 * @author ZonD80 <admin@kinokpk.com>
 * @copyright (C) 2008-now, ZonD80, Germany, TorrentsBook.com
 * @link http://dev.kinokpk.com
 */

require_once ("include/bittorrent.php");

dbconn ();

loggedinorreturn ();

// Define constants
define ( 'PM_DELETED', 0 ); // Message was deleted
define ( 'PM_INBOX', 1 ); // Message located in Inbox for reciever
define ( 'PM_SENTBOX', - 1 ); // GET value for sent box


// Determine action
$action = ( string ) $_GET ['action'];
if (! $action) {
	$action = ( string ) $_POST ['action'];
	if (! $action) {
		$action = 'viewmailbox';
	}
}

// ������ �������� ��������� �����
if ($action == "viewmailbox") {
	// Get Mailbox Number
	$mailbox = ( int ) $_GET ['box'];
	if (! $mailbox) {
		$mailbox = PM_INBOX;
	}
	if ($mailbox == PM_INBOX) {
		$mailbox_name = $REL_LANG->say_by_key('inbox');
	} else {
		$mailbox_name = $REL_LANG->say_by_key('outbox');
	}

	// Start Page


	stdhead ( $mailbox_name );

	?>

<H1><?=$mailbox_name?></H1>

	<?
	#amount of messages
	$inbox_all = count($CURUSER['inbox']);
	$outbox_all = count($CURUSER['outbox']);
	$all_mess = $inbox_all+$outbox_all;
	$all_mess_procent = round(($all_mess/$REL_CONFIG['pm_max'])*100);
	//print($all_mess_procent);
	$inbox_all_procent = round(($inbox_all/$REL_CONFIG['pm_max'])*100);
	$outbox_all_procent = round(($outbox_all/$REL_CONFIG['pm_max'])*100);

	print("<b>��� ���� �������� ��:</b><font color=\"#8da6cf\"> $all_mess</font><font color=\"green\"> ($all_mess_procent%) </font> <small>������������ ���������� ��������� - ". $REL_CONFIG['pm_max'] ."</small><br />");
	#amount end

	print('<form id="message" action="'.$REL_SEO->make_link('message').'" method="POST"><input type="hidden" name="action" value="moveordel">');
	print("<div id=\"tabs\"><ul>
	<li class=\"tab".(($mailbox != PM_SENTBOX)?'1':'2')."\"><a href=\"".$REL_SEO->make_link('message','action','viewmailbox','box',1)."\"><span>�������� <font color=\"#8da6cf\">$inbox_all </font><font color=\"green\">($inbox_all_procent%) </font></span></a></li>
	<li nowrap=\"\" class=\"tab".(($mailbox != PM_SENTBOX)?'2':'1')."\"><a href=\"".$REL_SEO->make_link('message','action','viewmailbox','box',-1)."\"><span>������������ <font color=\"#8da6cf\">$outbox_all </font><font color=\"green\">($outbox_all_procent%) </font></span></a></li>
	</ul></div>");
	?>

<TABLE border="0" cellpadding="4" cellspacing="0" width="100%"
	style="float: left; margin-top: 10px;">
	<TR>
		<TD width="2%" class="colhead">&nbsp;&nbsp;</TD>
		<TD width="41%" class="colhead"><?=$REL_LANG->say_by_key('subject');?></TD>
		<?
		if ($mailbox == PM_INBOX )
		print ("<TD width=\"30%\" class=\"colhead\">".$REL_LANG->say_by_key('sender')."</TD>");
		else
		print ("<TD width=\"30%\" class=\"colhead\">".$REL_LANG->say_by_key('receiver')."</TD>");
		?>
		<TD width="10%" class="colhead"><?=$REL_LANG->say_by_key('date');?></TD>
		<TD width="10%" class="colhead">� ������</TD>
		<TD width="10%" class="colhead">���� ��������</TD>
		<TD width="2%" class="colhead"><input id="toggle-all"
			style="float: right;" type="checkbox"
			title="<?=$REL_LANG->say_by_key('mark_all');?>"
			value="<?=$REL_LANG->say_by_key('mark_all');?>" /></TD>

	</TR>
	<?

	$cronrow = sql_query("SELECT * FROM cron WHERE cron_name IN ('pm_delete_sys_days','pm_delete_user_days')");

	while ($cronres = mysql_fetch_assoc($cronrow)) $CRON[$cronres['cron_name']] = $cronres['cron_value'];
	$secs_system = $CRON['pm_delete_sys_days']*86400; // ���������� ����
	$dt_system = time() - $secs_system; // ������� ����� ���������� ����
	$secs_all = $CRON['pm_delete_user_days']*86400; // ���������� ����
	$dt_all = time() - $secs_all; // ������� ����� ���������� ����

	if ($mailbox != PM_SENTBOX) {
		$res = sql_query ( "SELECT m.*, u.username AS sender_username, friends.id AS fid, friends.confirmed AS fconf FROM messages AS m LEFT JOIN users AS u ON m.sender = u.id LEFT JOIN friends ON (friends.userid=m.sender AND friends.friendid={$CURUSER['id']}) OR (friends.friendid=m.sender AND friends.userid={$CURUSER['id']}) WHERE receiver=" . sqlesc ( $CURUSER ['id'] ) . " AND location=" . sqlesc ( $mailbox ) . " AND IF(m.archived_receiver=1, 1=1, IF(m.sender=0,m.added>$dt_system,m.added>$dt_all)) ORDER BY id DESC" ) or sqlerr ( __FILE__, __LINE__ );
	} else {
		$res = sql_query ( "SELECT m.*, u.username AS receiver_username, friends.id AS fid, friends.confirmed AS fconf FROM messages AS m LEFT JOIN users AS u ON m.receiver = u.id LEFT JOIN friends ON (friends.userid=m.receiver AND friends.friendid={$CURUSER['id']}) OR (friends.friendid=m.receiver AND friends.userid={$CURUSER['id']}) WHERE sender=" . sqlesc ( $CURUSER ['id'] ) . " AND saved=1 AND IF(m.archived_receiver<>1, 1=1, IF(m.sender=0,m.added>$dt_system,m.added>$dt_all)) ORDER BY id DESC" ) or sqlerr ( __FILE__, __LINE__ );
	}

	if (mysql_num_rows ( $res ) == 0) {

		echo ("<TD colspan=\"6\" align=\"center\">" . $REL_LANG->say_by_key('no_messages') . ".</TD>\n");
	} else {
		while ( $row = mysql_fetch_assoc ( $res ) ) {
			if ($row['receiver']==$CURUSER['id']) $row['archived'] = $row['archived_receiver'];

			$friend = $row ['fid'];
			$fconf = $row ['fconf'];
			// Get Sender Username
			if ($row ['sender'] != 0) {
				$username = "<a href=\"".$REL_SEO->make_link('userdetails','id',$row['sender'],'username',translit($row["sender_username"]))."\">" . $row ["sender_username"] . "</a>";
				$id = $row ['sender'];

				if ($friend)
				$username .= "<br /><small>[<a href=\"".$REL_SEO->make_link('friends','action','deny','id',$row['fid'])."\">{$REL_LANG->say_by_key('delete_from_friends')}</a>]" . (! $fconf ? "[<a href=\"".$REL_SEO->make_link('friends','action','confirm','id',$row['fid'])."\">{$REL_LANG->say_by_key('confirm')}</a>]" : '') . "</small>";
				else
				$username .= "<br /><small>[<a href=\"".$REL_SEO->make_link('friends','action','add','id',$id)."\">{$REL_LANG->say_by_key('add_to_friends')}</a>]</small>";
			} else {
				$username = $REL_LANG->say_by_key('from_system');
			}
			// Get Receiver Username
			if ($row ['receiver'] != 0) {
				$receiver = "<a href=\"".$REL_SEO->make_link('userdetails','id',$row ['receiver'],'username',translit($row["receiver_username"]))."\">" . $row ["receiver_username"] . "</a>";
				$id_r = $row ['receiver'];

				if ($friend)
				$receiver .= "<br /><small>[<a href=\"".$REL_SEO->make_link('friends','action','deny','id',$row['fid'])."\">{$REL_LANG->say_by_key('delete_from_friends')}</a>]" . (! $fconf ? $REL_LANG->say_by_key('confirm') : '') . "</small>";
				else
				$receiver .= "<br /><small>[<a href=\"".$REL_SEO->make_link('friends','action','add','id',$id_r)."\">{$REL_LANG->say_by_key('add_to_friends')}</a>]</small>";
			} else {
				$receiver = $REL_LANG->say_by_key('from_system');
			}
			$subject = makesafe ( $row ['subject'] );
			if (strlen ( $subject ) <= 0) {
				$subject = $REL_LANG->say_by_key('no_subject');
			}
			if ($row ['unread'] && $mailbox != PM_SENTBOX) {
				echo ("<TR>\n<TD ><IMG src=\"pic/pn_inboxnew.gif\" alt=\"" . $REL_LANG->say_by_key('mail_unread') . "\"></TD>\n");
			} else {
				echo ("<TR>\n<TD><IMG src=\"pic/pn_inbox.gif\" alt=\"" . $REL_LANG->say_by_key('mail_read') . "\"></TD>\n");
			}
			$msgtext = strip_tags($row['msg']);
			$msgtext = "<small>".(strlen($msgtext)>70?substr($msgtext,0,70).'...':$msgtext)."</small>";
			echo ("<TD><A href=\"".$REL_SEO->make_link('message','action','viewmessage','id',$row ['id'])."\">" . $subject . "</A><br/>$msgtext</TD>\n");
			if ($mailbox != PM_SENTBOX) {
				echo ("<TD>$username</TD>\n");
			} else {
				echo ("<TD>$receiver</TD>\n");
			}
			echo ("<TD>" . mkprettytime ( $row ['added'] ) . "</TD>\n");

			echo ("<TD>" . (($row ['archived']) ? "<font color=\"red\">��</font>" : "���") . "</TD>\n");
			if ($row ['sender'] == 0)
			$pm_del = $CRON ['pm_delete_sys_days'];
			else
			$pm_del = $CRON ['pm_delete_user_days'];

			echo ("<TD>" . (($row ['archived']) ? "N/A" : ($pm_del - round ( (time () - $row ['added']) / 86400 )) . " ���(��)</TD>\n"));
			echo ("<TD><INPUT type=\"checkbox\" name=\"messages[]\" title=\"" . $REL_LANG->say_by_key('mark') . "\" value=\"" . $row ['id'] . "\" id=\"checkbox_tbl_" . $row ['id'] . "\"></TD>\n</TR>\n");
		}
	}
	?>
	<tr class="colhead">
		<td class="colhead">&nbsp;</td>
		<td colspan="6" align="right" width="100%" class="colhead" /><input
			type="hidden" name="box" value="<?=$mailbox?>" /> <input
			type="submit" name="delete"
			title="<?=$REL_LANG->say_by_key('delete_marked_messages');?>"
			value="<?=$REL_LANG->say_by_key('delete');?>"
			onClick="return confirm('<?=$REL_LANG->say_by_key('sure_mark_delete');?>')" />
		<input type="submit" name="markread"
			title="<?=$REL_LANG->say_by_key('mark_as_read');?>"
			value="<?=$REL_LANG->say_by_key('mark_read');?>"
			onClick="return confirm('<?=$REL_LANG->say_by_key('sure_mark_read');?>')" />
		<input type="submit" name="archive" title="������������"
			value="������������"
			onClick="return confirm('������������ ��������� ���������? (��� �� ����� ������� �������� �������������)')" />
		<input type="submit" name="unarchive" title="���������������"
			value="���������������"
			onClick="return confirm('��������������� ��������� ���������? (��� ����� ������� �������� �������������)')" /></td>

	</tr>
</table>
</form>
<div align="left"><img src="pic/pn_inboxnew.gif" alt="�������������" />
	<?=$REL_LANG->say_by_key('mail_unread_desc');?><br />
<img src="pic/pn_inbox.gif" alt="�����������" /> <?=$REL_LANG->say_by_key('mail_read_desc');?></div>
	<?
	stdfoot ();
} // ����� �������� ��������� �����


// ������ �������� ���� ���������
elseif ($action == "viewmessage") {
	if (! is_valid_id ( $_GET ["id"] ))
	stderr ( $REL_LANG->say_by_key('error'), $REL_LANG->say_by_key('invalid_id') );
	$pm_id = $_GET ['id'];

	// Get the message
	if (get_user_class () != UC_SYSOP) {
		$res = sql_query ( 'SELECT * FROM messages WHERE messages.id=' . sqlesc ( $pm_id ) . ' AND (messages.receiver=' . sqlesc ( $CURUSER ['id'] ) . ' OR (messages.sender=' . sqlesc ( $CURUSER ['id'] ) . ' AND messages.saved=1)) LIMIT 1' ) or sqlerr ( __FILE__, __LINE__ );
		if (mysql_num_rows ( $res ) == 0) {
			stderr ( $REL_LANG->say_by_key('error'), "������ ��������� �� ����������." );
		}

	} else {
		$res = sql_query ( 'SELECT * FROM messages WHERE messages.id=' . sqlesc ( $pm_id ) );
		if (mysql_num_rows ( $res ) == 0) {
			stderr ( $REL_LANG->say_by_key('error'), "������ ��������� �� ����������." );
		}
		$adminview = 1;
	}

	// Prepare for displaying message
	$message = mysql_fetch_assoc ( $res );
	if ($message ['sender'] == $CURUSER ['id']) {
		// Display to
		$res2 = sql_query ( "SELECT username FROM users WHERE id=" . sqlesc ( $message ['receiver'] ) ) or sqlerr ( __FILE__, __LINE__ );
		$sender = mysql_fetch_array ( $res2 );
		$sender = "<A href=\"".$REL_SEO->make_link('userdetails','id',$message['receiver'],'username',translit($sender [0]))."\">" . $sender [0] . "</A>";
		$reply = "";
		$from = "����";
	} else {
		$from = "�� ����";
		if ($message ['sender'] == 0) {
			$sender = "���������";
			$reply = "";
		} else {
			$res2 = sql_query ( "SELECT username FROM users WHERE id=" . sqlesc ( $message ['sender'] ) ) or sqlerr ( __FILE__, __LINE__ );
			$sender = mysql_fetch_array ( $res2 );
			$sender = "<A href=\"".$REL_SEO->make_link('userdetails','id',$message['sender'],'username',translit($sender [0]))."\">{$sender [0]}</A>";
			$reply = " [ <A href=\"".$REL_SEO->make_link('message','action','sendmessage','receiver',$message['sender'],'replyto',$pm_id)."\">��������</A> ]";
		}
	}
	$body = format_comment ( $message ['msg'] );
	$added = mkprettytime ( $message ['added'] );
	if (get_user_class () >= UC_MODERATOR && $message ['sender'] == $CURUSER ['id']) {
		$unread = ($message ['unread'] ? "<SPAN style=\"color: #FF0000;\"><b>(�����)</b></A>" : "");
	} else {
		$unread = "";
	}
	$subject = makesafe ( $message ['subject'] );
	if (strlen ( $subject ) <= 0) {
		$subject = "��� ����";
	}
	// Mark message unread
	if ($adminview && ($CURUSER ['id'] != $message ['receiver']) && ($CURUSER ['id'] != $message ['sender'])) {
	} else
	sql_query ( "UPDATE messages SET unread=0 WHERE id=" . sqlesc ( $pm_id ) . " AND receiver=" . sqlesc ( $CURUSER ['id'] ) . " LIMIT 1" );
	// Display message
	stdhead ( "������ ��������� (����: $subject)" );
	?>
<TABLE width="100%" border="0" cellpadding="4" cellspacing="0">

	<TR>
		<TD class="colhead" colspan="2">����: <?=$subject?><span class="higo"><a
			href="javascript:history.go(-1);">�����</a></span></TD>

	</TR>
	<TR>
		<TD width="50%" class="colhead"><?=$from?></TD>
		<TD width="50%" class="colhead">���� ��������</TD>
	</TR>
	<TR>
		<TD><?=$sender?></TD>
		<TD><?=$added?>&nbsp;&nbsp;<?=$unread?></TD>
	</TR>
	<TR>
		<TD colspan="2" style="padding: 20px;"><?=$body?></TD>
	</TR>
	<TR>
		<TD align="right" colspan=2><?php
		if ($adminview && ($CURUSER ['id'] != $message ['receiver']) && ($CURUSER ['id'] != $message ['sender'])) {
			$a_receiver = sql_query ( "SELECT username FROM users WHERE id = " . $message ['receiver'] );
			$a_receiver = mysql_result ( $a_receiver, 0 );

			print ( '<font color="red">�� �������������� ��� ��������� �� ���� ��������������!</font> ����������: <a href="'.$REL_SEO->make_link('userdetails','id',$message['receiver'],'username',translit($a_receiver)).'">' . $a_receiver . '</a><br />' );
		}
		print ( "[ <A onClick=\"return confirm('�� �������?')\" href=\"".$REL_SEO->make_link('message','action','deletemessage','id',$pm_id)."\">�������</A> ]$reply [ <A href=\"".$REL_SEO->make_link('message','action','forward','id',$pm_id)."\">���������</A> ]" . reportarea ( $message ['id'], 'messages' ) );
		?></TD>
	</TR>
</TABLE>
		<?
		set_visited('messages',$pm_id);
		stdfoot ();
} // ����� �������� ���� ���������


// ������ �������� ������� ���������
elseif ($action == "sendmessage") {

	if (! is_valid_id ( $_GET ["receiver"] ))
	stderr ( $REL_LANG->say_by_key('error'), "�������� ID ����������" );
	$receiver = ( int ) $_GET ["receiver"];

	if ($_GET ['replyto'] && ! is_valid_id ( $_GET ["replyto"] ))
	stderr ( $REL_LANG->say_by_key('error'), "�������� ID ���������" );
	$replyto = ( int ) $_GET ["replyto"];

	$res = sql_query ( "SELECT * FROM users WHERE id=$receiver" ) or die ( mysql_error () );
	$user = mysql_fetch_assoc ( $res );
	if (! $user)
	stderr ( $REL_LANG->say_by_key('error'), "������������ � ����� ID �� ����������." );

	if ($replyto) {
		$res = sql_query ( "SELECT * FROM messages WHERE id=$replyto" ) or sqlerr ( __FILE__, __LINE__ );
		$msga = mysql_fetch_assoc ( $res );
		if ($msga ["receiver"] != $CURUSER ["id"])
		stderr ( $REL_LANG->say_by_key('error'), "�� ��������� �������� �� �� ���� ���������!" );

		$res = sql_query ( "SELECT username FROM users WHERE id=" . $msga ["sender"] ) or sqlerr ( __FILE__, __LINE__ );
		$usra = mysql_fetch_assoc ( $res );
		$body .= "<blockquote>" . format_comment ( $msga ['msg'] ) . "</blockquote><cite>$usra[username]</cite><hr /><br /><br />";
		// Change
		if (!preg_match("/^Re\(([0-9]+)\)\:/",$msga ['subject']))

		$subject = "Re(1): ".makesafe($msga ['subject']);
		else $subject = preg_replace("/^Re\(([0-9]+)\)\:/e","'Re('.(\\1+1).'):'",makesafe($msga ['subject']));



		// End of Change
	}

	stdhead ( "������� ���������", false );
	?>
<script language="JavaScript">
<!--

required = new Array("subject");
required_show = new Array("���� ���������");


function SendForm () {
  var i, j;

for(j=0; j<required.length; j++) {
    for (i=0; i<document.message.length; i++) {
        if (document.message.elements[i].name == required[j] &&
  document.forms[0].elements[i].value == "" ) {
            alert('����������, ������� ' + required_show[j]);
            document.message.elements[i].focus();
            return false;
        }
    }
}

  return true;
}
//-->

</script>
<table class=main border=0 cellspacing=0 cellpadding=0>
	<tr>
		<td class=embedded>
		<form name="message" method="post"
			action="<?=$REL_SEO->make_link('message')?>"
			onsubmit="return SendForm();"><input type=hidden name=action
			value=takemessage>
		<table class=message cellspacing=0 cellpadding=5>
			<tr>
				<td colspan=2 class=colhead>��������� ��� <a class=altlink_white
					href="<?=$REL_SEO->make_link('userdetails','id',$receiver,'username',translit($user['username']))?>"><?=$user ["username"]?></a></td>
			</tr>
			<TR>
				<TD colspan="2"><B>����:&nbsp;&nbsp;</B> <INPUT name="subject"
					type="text" size="60" value="<?=$subject?>" maxlength="255"></TD>
			</TR>
			<tr>
				<td <?=$replyto ? " colspan=2" : ""?>><?
				print textbbcode ( "msg", $body );
				?></td>
			</tr>
			<tr>
			<?
			if ($replyto) {
				?>
				<td align=center><input type=checkbox name='delete' value='1'
				<?=$CURUSER ['deletepms'] ? "checked" : ""?> />������� ���������
				����� ������ <input type=hidden name=origmsg value=<?=$replyto?> /></td>
				<?
			}
			?>
				<td align=center><input type=checkbox name='save' value='1'
				<?=$CURUSER ['savepms'] ? "checked" : ""?> />��������� ��������� �
				������������</td>
			</tr>
			<tr>
				<td align="center"><input type="checkbox" name='archive' value='1' />������������
				����� ��������</td>
			</tr>
			<tr>
				<td <?=$replyto ? " colspan=2" : ""?> align=center><input
					type=submit value="�������!" class=btn /></td>
			</tr>
		</table>
		<input type=hidden name=receiver value=<?=$receiver?> /></form>
		</div>
		</td>
	</tr>
</table>
				<?
				stdfoot ();
} // ����� ������� ���������


// ������ ����� ���������� ���������
elseif ($action == 'takemessage') {

	$receiver = ( int ) $_POST ["receiver"];
	$origmsg = ( int ) $_POST ["origmsg"];
	$save = $_POST ["save"];
	$archive = $_POST ["archive"];
	$returnto = urlencode ( $_POST ["returnto"] );
	if (! is_valid_id ( $receiver ) || ($origmsg && ! is_valid_id ( $origmsg )))
	stderr ( $REL_LANG->say_by_key('error'), $REL_LANG->say_by_key('invalid_id') );
	$msg = trim ( $_POST ["msg"] );
	if (! $msg)
	stderr ( $REL_LANG->say_by_key('error'), "���������� ������� ���������!" );
	$subject = trim ( $_POST ['subject'] );
	if (! $subject)
	stderr ( $REL_LANG->say_by_key('error'), "���������� ������� ���� ���������!" );

	// ANTISPAM SYSTEM BEGIN
	$last_pmres = sql_query ( "SELECT " . time () . "-added AS seconds, msg,id FROM messages WHERE sender=" . $CURUSER ['id'] . " OR poster=" . $CURUSER ['id'] . " ORDER BY added DESC LIMIT 4" );
	while ( $last_pmresrow = mysql_fetch_array ( $last_pmres ) ) {
		$last_pmrow [] = $last_pmresrow;
		$msgids [] = $last_pmresrow ['id'];
	}
	//   print_r($last_pmrow);
	if ($last_pmrow [0]) {
		if (($REL_CONFIG ['as_timeout'] > round ( $last_pmrow [0] ['seconds'] )) && $REL_CONFIG ['as_timeout']) {
			$seconds = $REL_CONFIG ['as_timeout'] - round ( $last_pmrow [0] ['seconds'] );
			stderr ( $REL_LANG->say_by_key('error'), "�� ����� ����� ����� ������ �� �����, ����������, ��������� ������� ����� $seconds ������. <a href=\"javascript: history.go(-1)\">�����</a>" );
		}

		if ($REL_CONFIG ['as_check_messages'] && ($last_pmrow [0] ['msg'] == $msg) && ($last_pmrow [1] ['msg'] == $msg) && ($last_pmrow [2] ['msg'] == $msg) && ($last_pmrow [3] ['msg'] == $msg)) {
			$msgview = '';
			foreach ( $msgids as $msgid ) {
				$msgview .= "\n<a href=\"".$REL_SEO->make_link('message','action','viewmessage','id',$msgid)."\">��������� � ID={$msgid}</a> �� ������������ " . $CURUSER ['username'];
			}
			$modcomment = sql_query ( "SELECT modcomment FROM users WHERE id=" . $CURUSER ['id'] );
			$modcomment = mysql_result ( $modcomment, 0 );
			if (strpos ( $modcomment, "Maybe spammer" ) === false) {
				$arow = sql_query ( "SELECT id FROM users WHERE class = '" . UC_SYSOP . "'" );

				while ( list ( $admin ) = mysql_fetch_array ( $arow ) ) {
					sql_query ( "INSERT INTO messages (poster, sender, receiver, added, msg, subject, location) VALUES(0, 0,
					$admin, '" . time () . "', '������������ <a href=\"".$REL_SEO->make_link('userdetails','id',$CURUSER ['id'],'username',translit($CURUSER['username']))."\">" . $CURUSER ['username'] . "</a> ����� ���� ��������, �.�. ��� 5 ��������� ��������� ��������� ��������� ���������.$msgview', '��������� � �����!', 1)" ) or sqlerr ( __FILE__, __LINE__ );
				}
				$modcomment .= "\n" . time () . " - Maybe spammer";
				sql_query ( "UPDATE users SET modcomment = " . sqlesc ( $modcomment ) . " WHERE id =" . $CURUSER ['id'] );
					
			} else {
				sql_query ( "UPDATE users SET enabled=0, dis_reason='Spam' WHERE id=" . $CURUSER ['id'] );

				$arow = sql_query ( "SELECT id FROM users WHERE class = '" . UC_SYSOP . "'" );

				while ( list ( $admin ) = mysql_fetch_array ( $arow ) ) {
					sql_query ( "INSERT INTO messages (poster, sender, receiver, added, msg, subject, location) VALUES(0, 0,
					$admin, '" . time () . "', '������������ <a href=\"".$REL_SEO->make_link('userdetails','id',$CURUSER ['id'],'username',translit($CURUSER['username']))."\">" . $CURUSER ['username'] . "</a> ������� �������� �� ����, ��� IP ����� (" . $CURUSER ['ip'] . ")', '��������� � ����� [���]!', 1)" ) or sqlerr ( __FILE__, __LINE__ );
					stderr ( "�����������!", "�� ������� �������� �������� �� ���� � ������ ����������! ���� �� �� �������� � �������� �������, <a href=\"".$REL_SEO->make_link('contact')."\">������� ������ �������</a>." );
				}
			}
			stderr ( $REL_LANG->say_by_key('error'), "�� ����� ����� ����� ������ �� �����, ���� 5 ��������� ��������� ���������. � ������� ������� ��������� ��������. <b><u>��������! ���� �� ��� ��� ����������� ��������� ���������� ���������, �� ������ ������������� ������������� ��������!!!</u></b> <a href=\"javascript: history.go(-1)\">�����</a>" );

		}
	}
	// ANTISPAM SYSTEM END
	$pms = sql_query ( "SELECT SUM(1) FROM messages WHERE (receiver = $receiver AND location=1) OR (sender = $receiver AND saved = 1)" );
	$pms = (int)mysql_result ( $pms, 0 );
	if ($pms >= $REL_CONFIG ['pm_max'])
	stderr ( $REL_LANG->say_by_key('error'), "���� ������ ��������� ���������� ��������, �� �� ������ ��������� ��� ���������." );

	if ($save) {
		$pms = sql_query ( "SELECT SUM(1) FROM messages WHERE (receiver = " . $CURUSER ['id'] . " AND location=1) OR (sender = " . $CURUSER ['id'] . " AND saved = 1)" );
		$pms = (int)mysql_result ( $pms, 0 );
		if ($pms >= $REL_CONFIG ['pm_max'])
		stderr ( "���������� ��������� ���������", "��� ���� ������ ��������� ��������, ������������ ���-�� {$REL_CONFIG['pm_max']}. �� �� ������ ��������� ���������, ��� ���������� �������� ���� ������ ���������" );
	}

	// Change
	$save = ($save) ? 1 : 0;
	$archive = ($archive) ? 1 : 0;
	// End of Change
	$res = sql_query ( "SELECT email, acceptpms, notifs, last_access AS la FROM users WHERE id=$receiver" ) or sqlerr ( __FILE__, __LINE__ );
	$user = mysql_fetch_assoc ( $res );
	if (! $user)
	stderr ( $REL_LANG->say_by_key('error'), "��� ������������ � ����� ID $receiver." );
	//Make sure recipient wants this message

	if (get_user_class () < UC_MODERATOR) {
		if ($user ["acceptpms"] == "friends") {
			$res2 = sql_query ( "SELECT * FROM friends WHERE userid=$receiver AND friendid=" . $CURUSER ["id"] ) or sqlerr ( __FILE__, __LINE__ );
			if (mysql_num_rows ( $res2 ) != 1)
			stderr ( "���������", "���� ������������ ��������� ��������� ������ �� ������ ����� ������" );
		} elseif ($user ["acceptpms"] == "no")
		stderr ( "���������", "���� ������������ �� ��������� ���������." );
	}
	sql_query ( "INSERT INTO messages (poster, sender, receiver, added, msg, subject, saved, location, archived) VALUES(" . $CURUSER ["id"] . ", " . $CURUSER ["id"] . ",
	$receiver, '" . time () . "', " . sqlesc ( ($msg) ) . ", " . sqlesc ( $subject ) . ", " . sqlesc ( $save ) . ",  1, " . sqlesc ( $archive ) . ")" ) or sqlerr ( __FILE__, __LINE__ );
	$sended_id = mysql_insert_id ();

	$username = $CURUSER ["username"];
	$usremail = $user ["email"];
	$body = "
	$username ������ ��� ������ ���������!

�������� �� ������ ����, ����� ��� ���������.

".$REL_SEO->make_link('message','action','viewmessage','id',$sended_id)."


";

	// email notifs
	send_notifs('unread',$body,$receiver);

	$delete = $_POST ["delete"];
	if ($origmsg) {
		if ($delete) {
			// Make sure receiver of $origmsg is current user
			$res = sql_query ( "SELECT * FROM messages WHERE id=$origmsg" ) or sqlerr ( __FILE__, __LINE__ );
			if (mysql_num_rows ( $res ) == 1) {
				$arr = mysql_fetch_assoc ( $res );
				if ($arr ["receiver"] != $CURUSER ["id"])
				stderr ( $REL_LANG->say_by_key('error'), "�� ��������� ������� �� ���� ���������!" );
				if (! $arr ["saved"])
				sql_query ( "DELETE FROM messages WHERE id=$origmsg" ) or sqlerr ( __FILE__, __LINE__ );
				elseif ($arr ["saved"])
				sql_query ( "UPDATE messages SET location = '0' WHERE id=$origmsg" ) or sqlerr ( __FILE__, __LINE__ );
			}
		}
		if (! $returnto)
		$returnto = $REL_SEO->make_link("message");
	}
	if ($returnto) {
		safe_redirect(" $returnto" );
		die ();
	} else {
		safe_redirect($REL_SEO->make_link('message'),2);
		stderr ( $REL_LANG->say_by_key('success'), "��������� ���� ������� ����������!" );
	}

} // ����� ����� ���������� ���������


//������ �������� ��������
elseif ($action == 'mass_pm') {
	if (get_user_class () < UC_MODERATOR)
	stderr ( $REL_LANG->say_by_key('error'), $REL_LANG->say_by_key('access_denied') );
	if (! is_valid_id ( $_POST ['n_pms'] ))
	stderr ( $REL_LANG->say_by_key('error'), $REL_LANG->say_by_key('invalid_id') );
	$n_pms = ( int ) $_POST ['n_pms'];
	$pmees = htmlspecialchars ( $_POST ['pmees'] );

	stdhead ( "������� ���������", false );
	?>
<table class=main border=0 cellspacing=0 cellpadding=0>
	<tr>
		<td class=embedded>
		<div align=center>
		<form method=post action=<?=$_SERVER ['PHP_SELF']?> name=message><input
			type=hidden name=action value=takemass_pm> <?
			if ($_SERVER ["HTTP_REFERER"]) {
				?> <input type=hidden name=returnto
			value="<?=htmlspecialchars ( $_SERVER ["HTTP_REFERER"] );?>"> <?
			}
			?>
		<table border=1 cellspacing=0 cellpadding=5>
			<tr>
				<td class=colhead colspan=2>�������� �������� ��� <?=$n_pms?>
				����������<?=($n_pms > 1 ? "���" : "��")?></td>
			</tr>
			<TR>
				<TD colspan="2"><B>����:&nbsp;&nbsp;</B> <INPUT name="subject"
					type="text" size="60" maxlength="255"></TD>
			</TR>
			<tr>
				<td colspan="2">
				<div align="center"><?=print textbbcode ( "msg", $body );?></div>
				</td>
			</tr>
			<tr>
				<td colspan="2">
				<div align="center"><b>�����������:&nbsp;&nbsp;</b> <input
					name="comment" type="text" size="70" /></div>
				</td>
			</tr>
			<tr>
				<td>
				<div align="center"><b>��:&nbsp;&nbsp;</b> <?=$CURUSER ['username']?>
				<input name="sender" type="radio" value="self" checked /> &nbsp;
				��������� <input name="sender" type="radio" value="system" /></div>
				</td>
			</tr>
			<tr>
				<td colspan="2" align=center><input type=submit value="�������!"
					class=btn /></td>
			</tr>
		</table>
		<input type=hidden name=pmees value="<?=$pmees?>" /> <input
			type=hidden name=n_pms value=<?=$n_pms?> /></form>
		<br />
		<br />
		</div>
		</td>
	</tr>
</table>
			<?php
			stdfoot ();

} //����� �������� ��������


//������ ����� ��������� �� �������� ��������
elseif ($action == 'takemass_pm') {
	if (get_user_class () < UC_MODERATOR)
	stderr ( $REL_LANG->say_by_key('error'), $REL_LANG->say_by_key('access_denied') );
	$msg = trim ( $_POST ["msg"] );
	if (! $msg)
	stderr ( $REL_LANG->say_by_key('error'), "���������� ������� ���������." );
	$sender_id = ($_POST ['sender'] == 'system' ? 0 : $CURUSER ['id']);
	$n_pms = ( int ) $_POST ['n_pms'];
	$comment = ( string ) $_POST ['comment'];
	$from_is = mysql_real_escape_string ( unesc ( $_POST ['pmees'] ) );
	// Change
	$subject = trim ( $_POST ['subject'] );
	$query = "INSERT INTO messages (sender, receiver, added, msg, subject, location, poster) " . "SELECT $sender_id, u.id, " . time () . ", " . sqlesc (  $msg ) . ", " . sqlesc ( $subject ) . ", 1, $sender_id " . $from_is;
	// End of Change
	sql_query ( $query ) or sqlerr ( __FILE__, __LINE__ );
	$n = mysql_affected_rows ();
	// add a custom text or stats snapshot to comments in profile
	if ($comment) {
		$res = sql_query ( "SELECT u.id, u.modcomment " . $from_is ) or sqlerr ( __FILE__, __LINE__ );
		if (mysql_num_rows ( $res ) > 0) {
			$l = 0;
			while ( $user = mysql_fetch_array ( $res ) ) {
				unset ( $new );
				$old = $user ['modcomment'];
				if ($comment)
				$new = $comment;

				$new .= $old ? ("\n" . $old) : $old;
				sql_query ( "UPDATE users SET modcomment = " . sqlesc ( $new ) . " WHERE id = " . $user ['id'] ) or sqlerr ( __FILE__, __LINE__ );
				if (mysql_affected_rows ())
				$l ++;
			}
		}
	}
	safe_redirect($REL_SEO->make_link('message'),3);
	stderr ( $REL_LANG->say_by_key('success'), (($n_pms > 1) ? "$n ��������� �� $n_pms ����" : "��������� ����") . " ������� ����������!" . ($l ? " $l �����������(��) � ������� " . (($l > 1) ? "����" : " ���") . " ��������!" : "") );
} //����� ����� ��������� �� �������� ��������


//������ �����������, ��������� ��� ������������
elseif ($action == "moveordel") {
	if (isset ( $_POST ["id"] ) && ! is_valid_id ( $_POST ["id"] ))
	stderr ( $REL_LANG->say_by_key('error'), $REL_LANG->say_by_key('invalid_id') );
	$pm_id = $_POST ['id'];

	$pm_box = ( int ) $_POST ['box'];
	if (! is_array ( $_POST ['messages'] ))
	stderr ( $REL_LANG->say_by_key('error'), $REL_LANG->say_by_key('invalid_id') );
	$pm_messages = $_POST ['messages'];
	if ($_POST ['move']) {
		if ($pm_id) {
			// Move a single message
			@sql_query ( "UPDATE messages SET location=" . sqlesc ( $pm_box ) . ", saved = 1 WHERE id=" . sqlesc ( $pm_id ) . " AND receiver=" . $CURUSER ['id'] . " LIMIT 1" );
		} else {
			// Move multiple messages
			@sql_query ( "UPDATE messages SET location=" . sqlesc ( $pm_box ) . ", saved = 1 WHERE id IN (" . implode ( ", ", array_map ( "sqlesc", array_map ( "intval", $pm_messages ) ) ) . ') AND receiver=' . $CURUSER ['id'] );
		}
		// Check if messages were moved
		if (@mysql_affected_rows () == 0) {
			stderr ( $REL_LANG->say_by_key('error'), "�� �������� ����������� ���������!" );
		}
		safe_redirect($REL_SEO->make_link('message','action','viewmailbox','box',$pm_box));
		exit ();
	} elseif ($_POST ['delete']) {
		if ($pm_id) {
			// Delete a single message
			$res = sql_query ( "SELECT * FROM messages WHERE id=" . sqlesc ( $pm_id ) ) or sqlerr ( __FILE__, __LINE__ );
			$message = mysql_fetch_assoc ( $res );
			if ($message ['receiver'] == $CURUSER ['id'] && ! $message ['saved']) {
				sql_query ( "DELETE FROM messages WHERE id=" . sqlesc ( $pm_id ) ) or sqlerr ( __FILE__, __LINE__ );
			} elseif ($message ['sender'] == $CURUSER ['id'] && $message ['location'] == PM_DELETED) {
				sql_query ( "DELETE FROM messages WHERE id=" . sqlesc ( $pm_id ) ) or sqlerr ( __FILE__, __LINE__ );
			} elseif ($message ['receiver'] == $CURUSER ['id'] && $message ['saved']) {
				sql_query ( "UPDATE messages SET location=0 WHERE id=" . sqlesc ( $pm_id ) ) or sqlerr ( __FILE__, __LINE__ );
			} elseif ($message ['sender'] == $CURUSER ['id'] && $message ['location'] != PM_DELETED) {
				sql_query ( "UPDATE messages SET saved=0 WHERE id=" . sqlesc ( $pm_id ) ) or sqlerr ( __FILE__, __LINE__ );
			}
		} else {
			// Delete multiple messages
			if (is_array ( $pm_messages ))
			foreach ( $pm_messages as $id ) {
				$res = sql_query ( "SELECT * FROM messages WHERE id=" . sqlesc ( ( int ) $id ) );
				$message = mysql_fetch_assoc ( $res );
				if ($message ['receiver'] == $CURUSER ['id'] && ! $message ['saved']) {
					sql_query ( "DELETE FROM messages WHERE id=" . sqlesc ( ( int ) $id ) ) or sqlerr ( __FILE__, __LINE__ );
				} elseif ($message ['sender'] == $CURUSER ['id'] && $message ['location'] == PM_DELETED) {
					sql_query ( "DELETE FROM messages WHERE id=" . sqlesc ( ( int ) $id ) ) or sqlerr ( __FILE__, __LINE__ );
				} elseif ($message ['receiver'] == $CURUSER ['id'] && $message ['saved']) {
					sql_query ( "UPDATE messages SET location=0 WHERE id=" . sqlesc ( ( int ) $id ) ) or sqlerr ( __FILE__, __LINE__ );
				} elseif ($message ['sender'] == $CURUSER ['id'] && $message ['location'] != PM_DELETED) {
					sql_query ( "UPDATE messages SET saved=0 WHERE id=" . sqlesc ( ( int ) $id ) ) or sqlerr ( __FILE__, __LINE__ );
				}
			}
		}
		// Check if messages were moved
		if (@mysql_affected_rows () == 0) {
			stderr ( $REL_LANG->say_by_key('error'), "��������� �� ����� ���� �������!" );
		} else {
			safe_redirect($REL_SEO->make_link('message','action','viewmailbox','box',$pm_box));
			exit ();
		}
	} elseif ($_POST ["markread"]) {
		//�������� ���� ���������
		if ($pm_id) {
			sql_query ( "UPDATE messages SET unread=0 WHERE id = " . sqlesc ( $pm_id ) ) or sqlerr ( __FILE__, __LINE__ );
		} //�������� ��������� ���������
		else {
			if (is_array ( $pm_messages ))
			foreach ( $pm_messages as $id ) {
				$res = sql_query ( "SELECT * FROM messages WHERE id=" . sqlesc ( ( int ) $id ) );
				$message = mysql_fetch_assoc ( $res );
				sql_query ( "UPDATE messages SET unread=0 WHERE id = " . sqlesc ( ( int ) $id ) ) or sqlerr ( __FILE__, __LINE__ );
			}
		}
		// ���������, ���� �� �������� ���������

			safe_redirect($REL_SEO->make_link('message','action','viewmailbox','box',$pm_box));
			exit ();

	} elseif ($_POST ["archive"]) {
		//���������� ���� ���������
		if ($pm_id) {
			sql_query ( "UPDATE messages SET archived=IF(sender={$CURUSER['id']},1,archived), archived_receiver=IF(sender={$CURUSER['id']},archived_receiver,1) WHERE id = " . sqlesc ( $pm_id ) ) or sqlerr ( __FILE__, __LINE__ );
		} //���������� ��������� ���������
		else {
			if (is_array ( $pm_messages ))
			foreach ( $pm_messages as $id ) {
				$res = sql_query ( "SELECT * FROM messages WHERE id=" . sqlesc ( ( int ) $id ) );
				$message = mysql_fetch_assoc ( $res );
				sql_query ( "UPDATE messages SET archived=IF(sender={$CURUSER['id']},1,archived), archived_receiver=IF(sender={$CURUSER['id']},archived_receiver,1) WHERE id = " . sqlesc ( ( int ) $id ) ) or sqlerr ( __FILE__, __LINE__ );
			}
		}

		safe_redirect($REL_SEO->make_link('message','action','viewmailbox','box',$pm_box),1 );
		stderr($REL_LANG->say_by_key('success'), "���������(�) ������������(�)!",'success');

	} elseif ($_POST ["unarchive"]) {
		//���������� ���� ���������
		if ($pm_id) {
			sql_query ( "UPDATE messages SET archived=IF(sender={$CURUSER['id']},0,archived), archived_receiver=IF(sender={$CURUSER['id']},archived_receiver,0) AND id = " . sqlesc ( $pm_id ) ) or sqlerr ( __FILE__, __LINE__ );
		} //���������� ��������� ���������
		else {
			if (is_array ( $pm_messages ))
			foreach ( $pm_messages as $id ) {
				$res = sql_query ( "SELECT * FROM messages WHERE id=" . sqlesc ( ( int ) $id ) );
				$message = mysql_fetch_assoc ( $res );
				sql_query ( "UPDATE messages SET archived=IF(sender={$CURUSER['id']},0,archived), archived_receiver=IF(sender={$CURUSER['id']},archived_receiver,0) AND id = " . sqlesc ( ( int ) $id ) ) or sqlerr ( __FILE__, __LINE__ );
			}
		}

		safe_redirect($REL_SEO->make_link('message','action','viewmailbox','box',$pm_box),1 );
		stderr($REL_LANG->say_by_key('success'), "���������(�) ���������������(�)!",'success');
	}

	stderr ( $REL_LANG->say_by_key('error'), "��� ��������." );
} //����� �����������, ��������� ��� ������������


//������ ���������
elseif ($action == "forward") {
	if ($_SERVER ['REQUEST_METHOD'] == 'GET') {
		// Display form
		if (! is_valid_id ( $_GET ["id"] ))
		stderr ( $REL_LANG->say_by_key('error'), $REL_LANG->say_by_key('invalid_id') );
		$pm_id = $_GET ['id'];

		// Get the message
		$res = sql_query ( 'SELECT * FROM messages WHERE id=' . sqlesc ( $pm_id ) . ' AND (receiver=' . sqlesc ( $CURUSER ['id'] ) . ' OR sender=' . sqlesc ( $CURUSER ['id'] ) . ') LIMIT 1' ) or sqlerr ( __FILE__, __LINE__ );

		if (! $res) {
			stderr ( $REL_LANG->say_by_key('error'), "� ��� ��� ���������� ���������� ��� ���������." );
		}
		if (mysql_num_rows ( $res ) == 0) {
			stderr ( $REL_LANG->say_by_key('error'), "� ��� ��� ���������� ���������� ��� ���������." );
		}
		$message = mysql_fetch_assoc ( $res );

		// Prepare variables
		if (!preg_match("/^Fwd\(([0-9]+)\)\:/",$message ['subject']))

		$subject = "Fwd(1): ".makesafe($message ['subject']);
		else $subject = preg_replace("/^Fwd\(([0-9]+)\)\:/e","'Fwd('.(\\1+1).'):'",makesafe($message ['subject']));
		
		$from = $message ['sender'];
		$orig = $message ['receiver'];

		$res = sql_query ( "SELECT username FROM users WHERE id=" . sqlesc ( $orig ) . " OR id=" . sqlesc ( $from ) ) or sqlerr ( __FILE__, __LINE__ );

		$orig2 = mysql_fetch_assoc ( $res );
		$orig_name = "<A href=\"".$REL_SEO->make_link('userdetails','id',$from,'username',translit($orig2['username']))."\">" . $orig2 ['username'] . "</A>";
		if ($from == 0) {
			$from_name = "���������";
			$from2 ['username'] = "���������";
		} else {
			$from2 = mysql_fetch_array ( $res );
			$from_name = "<A href=\"".$REL_SEO->make_link('userdetails','id',$from,'username',translit($from2['username']))."\">" . $from2 ['username'] . "</A>";
		}

		$body = "������������ ���������:<hr /><blockquote>" . format_comment ( $message ['msg'] . "</blockquote><cite>{$from2['username']}</cite><hr /><br /><br />" );

		stdhead ( $subject );
		?>

<FORM action="<?=$REL_SEO->make_link('message')?>" method="post"><INPUT
	type="hidden" name="action" value="forward"> <INPUT type="hidden"
	name="id" value="<?=$pm_id?>">
<TABLE border="0" cellpadding="4" cellspacing="0">
	<TR>
		<TD class="colhead" colspan="2"><?=$subject?></TD>
	</TR>
	<TR>
		<TD>����:</TD>
		<TD><INPUT type="text" name="to" value="������� ���" size="83"></TD>
	</TR>
	<TR>
		<TD>������������<br />
		�����������:</TD>
		<TD><?=$orig_name?></TD>
	</TR>
	<TR>
		<TD>��:</TD>
		<TD><?=$from_name?></TD>
	</TR>
	<TR>
		<TD>����:</TD>
		<TD><INPUT type="text" name="subject" value="<?=$subject?>" size="83"></TD>
	</TR>
	<TR>
		<TD>���������:</TD>
		<TD><?=( textbbcode ( "msg" ) );?></TD>
	</TR>
	<TR>
		<TD colspan="2" align="center">��������� ��������� <INPUT type="checkbox" name="save" value="1" <?=$CURUSER ['savepms'] ? " checked" : ""?>>&nbsp;<INPUT
			type="submit" value="���������"></TD>
	</TR>
</TABLE>
</FORM>
			<?
			stdfoot ();
	}

	else {

		// Forward the message
		if (! is_valid_id ( $_POST ["id"] ))
		stderr ( $REL_LANG->say_by_key('error'), $REL_LANG->say_by_key('invalid_id') );
		$pm_id = $_POST ['id'];

		// Get the message
		$res = sql_query ( 'SELECT * FROM messages WHERE id=' . sqlesc ( $pm_id ) . ' AND (receiver=' . sqlesc ( $CURUSER ['id'] ) . ' OR sender=' . sqlesc ( $CURUSER ['id'] ) . ') LIMIT 1' ) or sqlerr ( __FILE__, __LINE__ );
		if (! $res) {
			stderr ( $REL_LANG->say_by_key('error'), "� ��� ��� ���������� ���������� ��� ���������." );
		}

		if (mysql_num_rows ( $res ) == 0) {
			stderr ( $REL_LANG->say_by_key('error'), "� ��� ��� ���������� ���������� ��� ���������." );
		}

		$message = mysql_fetch_assoc ( $res );
		$subject = ( string ) $_POST ['subject'];
		$username = strip_tags ( $_POST ['to'] );

		// Try finding a user with specified name


		$res = sql_query ( "SELECT id FROM users WHERE LOWER(username)=LOWER(" . sqlesc ( $username ) . ") LIMIT 1" );
		if (! $res) {
			stderr ( $REL_LANG->say_by_key('error'), "������������, � ����� ������ �� ����������." );
		}
		if (mysql_num_rows ( $res ) == 0) {
			stderr ( $REL_LANG->say_by_key('error'), "������������, � ����� ������ �� ����������." );
		}

		$to = mysql_fetch_array ( $res );
		$to = $to [0];

		// Get Orignal sender's username
		if ($message ['sender'] == 0) {
			$from = "���������";
		} else {
			$res = sql_query ( "SELECT * FROM users WHERE id=" . sqlesc ( $message ['sender'] ) ) or sqlerr ( __FILE__, __LINE__ );
			$from = mysql_fetch_assoc ( $res );
			$from = $from ['username'];
		}
		$body = ( string ) $_POST ['msg'];
		$body .= "������������ ���������:<hr /><blockquote>" . $message ['msg'] . "</blockquote><cite>{$from}</cite><hr /><br /><br />";
		$save = ( int ) $_POST ['save'];
		if ($save) {
			$save = 1;
		} else {
			$save = 0;
		}

		//Make sure recipient wants this message
		if (get_user_class () < UC_MODERATOR) {
			if ($from ["acceptpms"] == "friends") {
				$res2 = sql_query ( "SELECT * FROM friends WHERE userid=$to AND friendid=" . $CURUSER ["id"] ) or sqlerr ( __FILE__, __LINE__ );
				if (mysql_num_rows ( $res2 ) != 1)
				stderr ( "���������", "���� ������������ ��������� ��������� ������ �� ������ ����� ������." );
			}

			elseif ($from ["acceptpms"] == "no")
			stderr ( "���������", "���� ������������ �� ��������� ���������." );
		}

		$pms = sql_query ( "SELECT SUM(1) FROM messages WHERE (receiver = " . ($receiver ? $receiver : $to) . " AND location=1) OR (sender = " . ($receiver ? $receiver : $to) . " AND saved = 1) GROUP BY messages.id" );
		$pms = mysql_result ( $pms, 0 );
		if ($pms >= $REL_CONFIG ['pm_max'])
		stderr ( $REL_LANG->say_by_key('error'), "���� ������ ��������� ���������� ��������, �� �� ������ ��������� ��� ���������." );

		sql_query ( "INSERT INTO messages (poster, sender, receiver, added, subject, msg, location, saved) VALUES(" . $CURUSER ["id"] . ", " . $CURUSER ["id"] . ", $to, '" . time () . "', " . sqlesc ( $subject ) . "," . sqlesc ( ($body) ) . ", " . sqlesc ( PM_INBOX ) . ", " . sqlesc ( $save ) . ")" ) or sqlerr ( __FILE__, __LINE__ );
		stdmsg ( "������", "�� ���������." );
	}
} //����� ���������


//������ �������� ���������
elseif ($action == "deletemessage") {
	if (! is_valid_id ( $_GET ["id"] ))
	stderr ( $REL_LANG->say_by_key('error'), $REL_LANG->say_by_key('invalid_id') );
	$pm_id = $_GET ['id'];

	// Delete message
	$res = sql_query ( "SELECT * FROM messages WHERE id=" . sqlesc ( $pm_id ) ) or sqlerr ( __FILE__, __LINE__ );
	if (! $res) {
		stderr ( $REL_LANG->say_by_key('error'), "��������� � ����� ID �� ����������." );
	}
	if (mysql_num_rows ( $res ) == 0) {
		stderr ( $REL_LANG->say_by_key('error'), "��������� � ����� ID �� ����������." );
	}
	$message = mysql_fetch_assoc ( $res );
	if ($message ['receiver'] == $CURUSER ['id'] && ! $message ['saved']) {
		$res2 = sql_query ( "DELETE FROM messages WHERE id=" . sqlesc ( $pm_id ) ) or sqlerr ( __FILE__, __LINE__ );
	} elseif ($message ['sender'] == $CURUSER ['id'] && $message ['location'] == PM_DELETED) {
		$res2 = sql_query ( "DELETE FROM messages WHERE id=" . sqlesc ( $pm_id ) ) or sqlerr ( __FILE__, __LINE__ );
	} elseif ($message ['receiver'] == $CURUSER ['id'] && $message ['saved']) {
		$res2 = sql_query ( "UPDATE messages SET location=0 WHERE id=" . sqlesc ( $pm_id ) ) or sqlerr ( __FILE__, __LINE__ );
	} elseif ($message ['sender'] == $CURUSER ['id'] && $message ['location'] != PM_DELETED) {
		$res2 = sql_query ( "UPDATE messages SET saved=0 WHERE id=" . sqlesc ( $pm_id ) ) or sqlerr ( __FILE__, __LINE__ );
	}
	if (! $res2) {
		stderr ( $REL_LANG->say_by_key('error'), "���������� ������� ���������." );
	}
	if (mysql_affected_rows () == 0) {
		stderr ( $REL_LANG->say_by_key('error'), "���������� ������� ���������." );
	} else {
		safe_redirect($REL_SEO->make_link('message','action','viewmailbox','id',$message['location']));
		exit ();
	}
	//����� �������� ���������
}
//else stderr("Access Denied.","Unknown action");
?>
<script language="JavaScript">
<!--
$(document).ready(function () {
	$('td').children('blockquote').addClass('quote1');
})
//-->
</script>
