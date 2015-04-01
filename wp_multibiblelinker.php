<?php
/*
	Plugin Name: Multipurpose Bible Linker
	Plugin URI: https://wordpress.org/plugins/multipurpose-bible-linker-russian-and-ukrainian/
	Description: This plugin is designed to help people referring to English, Russian or Ukrainian Bibles. Once activated, it will find all texts that look like references to Biblical texts and replace them with link to actually biblical chapter and verses.
	Version: 1.6.8
	Author: Vitaliy Bilanchuk, Vladimir Sokolov
	Author URI: http://helpforheart.org/stati/printsipyi-redaktirovaniya/

    Copyright 2013-2015 Vitaliy Bilanchuk (email: vitaly.bilanchuk@gmail.com), Vladimir Sokolov (email: gadfly.svy@gmail.com)

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation; either version 2 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

function init_textdomain() {
	if (function_exists('load_plugin_textdomain')) {
		load_plugin_textdomain('wp_multibiblelinker', false, basename( dirname( __FILE__ ) ) . '/lang' );
	}
}
add_action('plugins_loaded', 'init_textdomain');

include 'scripts/multibiblelinker.php';

function multibiblelinker_add_option_pages() {
	if (function_exists('add_options_page')) {
	    add_options_page('Multipurpose Bible Linker', 'Multi Bible Linker', 8, basename(__FILE__), 'get_multibiblelinker_form');
	}		
}

function get_multibiblelinker_form() {

	if (isset($_POST['set_defaults'])) {
		echo '<div id="message" class="updated fade"><p><strong>';

		update_option('g_BibleSource', 	'AllbibleInfoSource');
		update_option('language', 		'ru');
		update_option('isRoman', 		true);
		update_option('linkStandart', 	'east');
		update_option('spaceType', 		'nbsp');
		update_option('doCorrection', 	true);
		update_option('doBookRepeat', 	false);
		update_option('doNotWrap', 		true);

		_e('Loaded with the default options.', 'wp_multibiblelinker');
		echo '</strong></p></div>';

	} else if (isset($_POST['multibiblelinker_update'])) {

		echo '<div id="message" class="updated fade"><p><strong>';

		update_option('g_BibleSource', 	(string)$_POST["g_BibleSource"]);
		update_option('language', 		(string)$_POST["language"]);
		update_option('linkStandart', 	(string)$_POST["linkStandart"]);
		update_option('spaceType', 		(string)$_POST["spaceType"]);
		update_option('isRoman', 		(bool)$_POST["isRoman"]);
		update_option('doCorrection', 	(bool)$_POST["doCorrection"]);
		update_option('doBookRepeat', 	(bool)$_POST["doBookRepeat"]);
		update_option('doNotWrap', 		(bool)$_POST["doNotWrap"]);

		_e('Configuration updated.', 'wp_multibiblelinker');
		echo '</strong></p></div>';

	} ?>

<div class=wrap>
	<h2><?php _e('Multipurpose Bible Linker', 'wp_multibiblelinker'); ?></h2> 
	
	<div style="padding: 0 0 0 30px;">
		<p><?php _e('The plugin is designed to highlight the reference to the place of the Bible in Russian, Ukrainian and English.', 'wp_multibiblelinker'); ?></p>
	</div>
	
	<form method="post" action="<?php echo $_SERVER["REQUEST_URI"]; ?>">
	<input type="hidden" name="multibiblelinker_update" id="multibiblelinker_update" value="true" />

	<h3><?php _e('Translation sources', 'wp_multibiblelinker'); ?></h3>
	
	<div style="padding: 0 0 0 30px;">
		<p><?php _e('The plugin allows you to set the addressing biblical references to various online sources:', 'wp_multibiblelinker'); ?> <a href='http://allbible.info/' target='blank'>allbible.info</a> (<?php _e('by default', 'wp_multibiblelinker'); ?>), <a href='http://bible.com.ua/' target='blank'>bible.com.ua</a>, <a href='http://biblezoom.ru/' target='blank'>biblezoom.ru</a>, <a href='http://bibleonline.ru/' target='blank'>bibleonline.ru</a>, <a href='http://bible-center.ru/' target='blank'>bible-center.ru</a>, <a href='http://bibleserver.com/' target='blank'>bibleserver.com</a>, <a href='http://bible.com/' target='blank'>bible.com</a> (<a href='http://bible.us/' target='blank'>bible.us</a>), <a href='http://bible-desktop.com/' target='blank'>bible-desktop.com</a> (<a href='http://bibledesktop.ru/' target='blank'>bibledesktop.ru</a>), <a href='https://www.biblegateway.com/ ' target='blank'>biblegateway.com</a>.</p>
		
		<select name="g_BibleSource">
			<option <?php if (get_option('g_BibleSource') == "AllbibleInfoSource") 		echo "selected"; ?> value="AllbibleInfoSource">allbible.info (en, ru, ua)</option>
			<option <?php if (get_option('g_BibleSource') == "BibleComUaSource") 		echo "selected"; ?> value="BibleComUaSource">bible.com.ua (en, ru, ua <?php _e('simultaneously', 'wp_multibiblelinker'); ?>)</option>
			<option <?php if (get_option('g_BibleSource') == "BiblezoomRuSource") 		echo "selected"; ?> value="BiblezoomRuSource">biblezoom.ru (el)</option>
			<option <?php if (get_option('g_BibleSource') == "BibleonlineRuSource") 	echo "selected"; ?> value="BibleonlineRuSource">bibleonline.ru (en, ru, ua, be)</option>
			<option <?php if (get_option('g_BibleSource') == "BibleCenterRuSource") 	echo "selected"; ?> value="BibleCenterRuSource">bible-center.ru (en, ru, el, lt)</option>
			<option <?php if (get_option('g_BibleSource') == "BibleserverComSource") 	echo "selected"; ?> value="BibleserverComSource">bibleserver.com (en, ru, bg, el, he, la)</option>
			<option <?php if (get_option('g_BibleSource') == "BibleComSource") 			echo "selected"; ?> value="BibleComSource">bible.com / bible.us (en, ru, ua, bg)</option>
			<option <?php if (get_option('g_BibleSource') == "BibleDesktopComSource") 	echo "selected"; ?> value="BibleDesktopComSource">bible-desktop.com / bibledesktop.ru (en, ru, ua, be)</option>
			<option <?php if (get_option('g_BibleSource') == "BiblegatewayComSource") 	echo "selected"; ?> value="BiblegatewayComSource">biblegateway.com (en, ru, ua, bg)</option>
		</select>
	</div>
	
	<h3><?php _e('Language', 'wp_multibiblelinker'); ?></h3>
	
	<div style="padding: 0 0 0 30px;">
		<p><?php _e('Language incoming text. In this case, the links are searched in the dictionary, and optionally replaced by a standard spelling. By default, the script refers to the standard translation of the Russian language, Ogeenka — for Ukrainian and King James — for English (not all sources of support Ukrainian and English translations, in which case the default link leads to the King James).', 'wp_multibiblelinker'); ?></p>
		
		<select name="language">
			<option <?php if (get_option('language') == "en") echo "selected"; ?> value="en">English</option>
			<option <?php if (get_option('language') == "ru") echo "selected"; ?> value="ru">русский</option>
			<option <?php if (get_option('language') == "ua") echo "selected"; ?> value="ua">українська</option>
		</select>
		
		<p><?php _e('Standard writing links: eastern (Matt. 3:4-6,8) or western (Matt. 3,4-6.8).', 'wp_multibiblelinker'); ?></p>
		
		<select name="linkStandart">
			<option <?php if (get_option('linkStandart') == "east") echo "selected"; ?> value="east"><?php _e('eastern', 'wp_multibiblelinker'); ?></option>
			<option <?php if (get_option('linkStandart') == "west") echo "selected"; ?> value="west"><?php _e('western', 'wp_multibiblelinker'); ?></option>
		</select>
		
		<p><?php _e('Type of whitespace: non-breaking or thin.', 'wp_multibiblelinker'); ?></p>
		
		<select name="spaceType">
			<option <?php if (get_option('spaceType') == "nbsp")   echo "selected"; ?> value="nbsp"><?php _e('non-breaking', 'wp_multibiblelinker'); ?></option>
			<option <?php if (get_option('spaceType') == "thinsp") echo "selected"; ?> value="thinsp"><?php _e('thin', 'wp_multibiblelinker'); ?></option>
		</select>
	</div>

	<h3><?php _e('Processing options', 'wp_multibiblelinker'); ?></h3>

	<div style="padding: 0 0 0 32px">
		<input type="checkbox" name="isRoman" value="checkbox" <?php if (get_option('isRoman')) echo "checked='checked'"; ?>/>&nbsp;&nbsp;
		<?php _e('Numbers of the books may be Roman numerals', 'wp_multibiblelinker'); ?>
		<br />
		<input type="checkbox" name="doCorrection" value="checkbox" <?php if (get_option('doCorrection')) echo "checked='checked'"; ?>/>&nbsp;&nbsp;
		<?php _e('Correct titles on standard', 'wp_multibiblelinker'); ?>
		<br />
		<input type="checkbox" name="doBookRepeat" value="checkbox" <?php if (get_option('doBookRepeat')) echo "checked='checked'"; ?>/>&nbsp;&nbsp;
		<?php _e('Repeat the name of the book before each chapter, if a few heads', 'wp_multibiblelinker'); ?>
		<br />
		<input type="checkbox" name="doNotWrap" value="checkbox" <?php if (get_option('doNotWrap')) echo "checked='checked'"; ?>/>&nbsp;&nbsp;
		<?php _e('Make a link inseparable', 'wp_multibiblelinker'); ?>
	</div>
	
	<div class="submit">
		<input type="submit" name="set_defaults" value="<?php _e('Restore defaults', 'wp_multibiblelinker'); ?> &raquo;" />
		<input type="submit" name="multibiblelinker_update" value="<?php _e('Save', 'wp_multibiblelinker'); ?> &raquo;" />
	</div>

	</form>
</div>
<?php
}

include 'wp_config.inc.php';

$fileLanguageIn  = "local/bible_links_arrays_" . $_ENV["languageIn"]  . "_in.php";
$fileLanguageOut = "local/bible_links_arrays_" . $_ENV["languageOut"] . "_out.php";

is_file(__DIR__ . '/'. $fileLanguageIn)  ? (include $fileLanguageIn)  : (include 'local/bible_links_arrays_ru_in.php');
is_file(__DIR__ . '/'. $fileLanguageOut) ? (include $fileLanguageOut) : (include 'local/bible_links_arrays_ru_out.php');

add_filter('the_content', 'SearchBibleLinks');
add_filter('the_content_rss', 'SearchBibleLinks');
add_filter('comment_text','SearchBibleLinks');
add_action('admin_menu', 'multibiblelinker_add_option_pages');
?>