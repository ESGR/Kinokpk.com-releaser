<?php
/**
 * Admin control panel frontend
 * @license GNU GPLv3 http://opensource.org/licenses/gpl-3.0.html
 * @package Kinokpk.com releaser
 * @author ZonD80 <admin@kinokpk.com>
 * @copyright (C) 2008-now, ZonD80, Germany, TorrentsBook.com
 * @link http://dev.kinokpk.com
 */

require "include/bittorrent.php";
dbconn();
loggedinorreturn();

httpauth();

stdhead($REL_LANG->_('Administrator control panel'));
begin_main_frame();


if (get_user_class() >= UC_SYSOP) {
	begin_frame($REL_LANG->_("Staff functions").' - '.$REL_LANG->_("For owners")); ?>
<table width=100% cellspacing=10 align=center>
	<tr>
		<td><a href="<?=$REL_SEO->make_link('siteonoff');?>"><?=$REL_LANG->_("On/Off the site");?></a></td>
		<td><a href="<?=$REL_SEO->make_link('blocksadmin');?>"><?=$REL_LANG->_("Blocks administration");?></a></td>
		<td><a href="<?=$REL_SEO->make_link('templatesadmin');?>"><?=$REL_LANG->_("Skins administration");?></a></td>
		<td><a href="<?=$REL_SEO->make_link('configadmin');?>"><b><?=$REL_LANG->_("Global settings");?></b></a></td>
	</tr>
	<tr>
		<td><a href="<?=$REL_SEO->make_link('spam');?>"><?=$REL_LANG->_("View private messages");?></a></td>
		<td><a href="<?=$REL_SEO->make_link('category');?>"><?=$REL_LANG->_("Categories");?></a></td>
		<td><a href="<?=$REL_SEO->make_link('stampadmin');?>"><?=$REL_LANG->_("Stamps");?></a></td>
		<td><a href="<?=$REL_SEO->make_link('countryadmin');?>"><?=$REL_LANG->_("Countries and flags");?></a></td>
	</tr>
	<tr>
		<td><a href="<?=$REL_SEO->make_link('retrackeradmin');?>"><?=$REL_LANG->_("Retracker administration");?></a></td>
		<td><a href="<?=$REL_SEO->make_link('cronadmin');?>"><b><?=$REL_LANG->_("Sheduled jobs administration");?></b></a></td>
		<td><a href="<?=$REL_SEO->make_link('langadmin');?>"><?=$REL_LANG->_("Language tools");?></a></td>

		<td><a href="<?=$REL_SEO->make_link('pagescategory');?>"><?=$REL_LANG->_("Categories for pages");?></a></td>
	</tr>
</table>
	<? end_frame();
}

if (get_user_class() >= UC_ADMINISTRATOR) { ?>
<? begin_frame($REL_LANG->_("Staff functions").' - '.$REL_LANG->_("For administrators")); ?>
<table width=100% cellspacing=10 align=center>
	<tr>
		<td><a href="<?=$REL_SEO->make_link('unco');?>"><?=$REL_LANG->_("Unconfirmed users");?></a></td>
		<td><a href="<?=$REL_SEO->make_link('delacctadmin');?>"><?=$REL_LANG->_("Delete user account");?></a></td>
		<td><a href="<?=$REL_SEO->make_link('rgadmin');?>"><?=$REL_LANG->_("Release groups");?></a></td>
		<td><a href="<?=$REL_SEO->make_link('bans');?>"><?=$REL_LANG->_("IP/subnet bans");?></a></td>
	</tr>
	<tr>
		<td><a href="<?=$REL_SEO->make_link('banemailadmin');?>"><?=$REL_LANG->_("E-mail bans");?></a></td>
		<td><a href="<?=$REL_SEO->make_link('email');?>"><?=$REL_LANG->_("Mass e-mail");?></a></td>
		<td><a href="<?=$REL_SEO->make_link('staffmess');?>"><?=$REL_LANG->_("Mass private message");?></a></td>
		<td><a href="<?=$REL_SEO->make_link('pollsadmin');?>"><?=$REL_LANG->_("Polls administration");?></a></td>
	</tr>
	<tr>
		<td><a href="<?=$REL_SEO->make_link('mysqlstats');?>"><?=$REL_LANG->_("MySQL status");?></a></td>
		<td><a href="<?=$REL_SEO->make_link('passwordadmin');?>"><?=$REL_LANG->_("Change user password");?></a></td>
		<td><a href="<?=$REL_SEO->make_link('clearcache');?>"><?=$REL_LANG->_("Clear caches");?></a></td>
		<td><a href="<?=$REL_SEO->make_link('reltemplatesadmin');?>"><?=$REL_LANG->_("Release's templates adminsitration");?></a></td>
	</tr>
	<tr>
		<td colspan="4"><a href="<?=$REL_SEO->make_link('news');?>"><?=$REL_LANG->_("Add a news");?></a> | <a
			href="<?=$REL_SEO->make_link('newsarchive');?>"><?=$REL_LANG->_("View all news");?></a></td>

	</tr>
</table>
<? end_frame();
}

if (get_user_class() >= UC_MODERATOR) { ?>
<? begin_frame($REL_LANG->_("Staff functions").' - '.$REL_LANG->_("For moderators")); ?>


<table width=100% cellspacing=3>
	<tr>
	</tr>
	<tr>
		<td><a href="<?=$REL_SEO->make_link('users','act','users');?>"><?=$REL_LANG->_("View users with rating below 0");?></a></td>
		<td><a href="<?=$REL_SEO->make_link('users','act','banned');?>"><?=$REL_LANG->_("View disabled users");?></a></td>
		<td><a href="<?=$REL_SEO->make_link('users','act','last');?>"><?=$REL_LANG->_("View new users");?></a></td>
		<td><a href="<?=$REL_SEO->make_link('log');?>"><?=$REL_LANG->_("View site log");?></a></td>
	</tr>
	<tr>
		<td><a href="<?=$REL_SEO->make_link('warned');?>"><?=$REL_LANG->_("View warned users");?></a></td>
		<td><a href="<?=$REL_SEO->make_link('adduser');?>"><?=$REL_LANG->_("Add a new user");?></a></td>
		<td><a href="<?=$REL_SEO->make_link('recover');?>"><?=$REL_LANG->_("Restore user access");?></a></td>
		<td><a href="<?=$REL_SEO->make_link('uploaders');?>"><?=$REL_LANG->_("View uploaders & stats");?></a></td>
	</tr>
	<tr>
		<td colspan="4"><a href="<?=$REL_SEO->make_link('users');?>"><?=$REL_LANG->_("View list of users");?></a></td>
	</tr>
	<tr>
		<td><a href="<?=$REL_SEO->make_link('stats');?>"><?=$REL_LANG->_("View statistics");?></a></td>
		<td><a href="<?=$REL_SEO->make_link('testip');?>"><?=$REL_LANG->_("Test that IP was banned");?></a></td>
		<td><a href="<?=$REL_SEO->make_link('reports');?>"><?=$REL_LANG->_("View reports");?></a></td>
		<td><a href="<?=$REL_SEO->make_link('ipcheck');?>"><?=$REL_LANG->_("Search for double IP");?></a></td>
	</tr>
	<tr>
		<td colspan="4" class=embedded>
		<form method=get action="<?=$REL_SEO->make_link('users')?>"><?=$REL_LANG->_("Search");?>: <input type=text size=30
			name=search> <select name=class>
			<option value='-'><?=$REL_LANG->_("Select");?></option>
			<?php
			for ($i=0;;$i++) {
				if ($s=get_user_class_name($i))
				print("<option value=\"$i\">$s</option>");
				else
				break;
			}
			?>
		</select> <input type=submit value='<?=$REL_LANG->_("Search");?>'></form>
		</td>
	</tr>
	<tr>
		<td class=embedded><a href="<?=$REL_SEO->make_link('usersearch');?>"><?=$REL_LANG->_("Administrative search");?></a></td>
	</tr>
</table>

			<?php
			end_frame();
}
end_main_frame();
stdfoot();
?>