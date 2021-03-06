<?php
/**
 * Cache cleaner
 * @license GNU GPLv3 http://opensource.org/licenses/gpl-3.0.html
 * @package Kinokpk.com releaser
 * @author ZonD80 <admin@kinokpk.com>
 * @copyright (C) 2008-now, ZonD80, Germany, TorrentsBook.com
 * @link http://dev.kinokpk.com
 */

require_once "include/bittorrent.php";
dbconn();

if (get_user_class() < UC_ADMINISTRATOR) stderr($REL_LANG->say_by_key('error'), $REL_LANG->say_by_key('access_denied'));


if ($_SERVER['REQUEST_METHOD'] != 'POST') {

	stdhead($REL_LANG->say_by_key('cleaning_cache'));
	begin_frame($REL_LANG->say_by_key('select_cache'));

	print '<form action="'.$REL_SEO->make_link('clearcache').'" method="POST" id="message"><table width="100%"><tr><td class="colhead">'.$REL_LANG->say_by_key('name_cache').'</td><td class="colhead">'.$REL_LANG->say_by_key('select_all').'<input type="checkbox" title="'.$REL_LANG->say_by_key('mark_all').'" value="'.$REL_LANG->say_by_key('mark_all').'" id="toggle-all"></td></tr>';
	if ($handle = opendir(ROOT_PATH.'cache')) {
		while (false !== ($file = readdir($handle))) {
			if ($file != "." && $file != "..") {
				print "<tr><td>$file</td><td><input type=\"checkbox\" name=\"cache[]\" value=\"$file\"></td></tr>";
			}
		}
		closedir($handle);
	}
	print('<tr><td colspan="2"><input type="submit" value="'.$REL_LANG->say_by_key('clean').'"></td></tr></table></form>');
	end_frame();

	stdfoot();

} else {

	$text = '';

	if (!is_array($_POST['cache'])) stderr($REL_LANG->say_by_key('error'),$REL_LANG->say_by_key('dont_set_cache'));

	foreach ($_POST['cache'] as $clearcache){

		$REL_CACHE->clearGroupCache($clearcache);

		$text .= "<br />".$REL_LANG->say_by_key('cache')." <b>".htmlspecialchars($clearcache)."</b> ".$REL_LANG->say_by_key('succ_purif');

	}

	stderr($REL_LANG->say_by_key('success'),$REL_LANG->say_by_key('cache_cleared') . $text,'success');

}
?>