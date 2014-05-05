<?php
/*
	Plugin Name: Multipurpose Bible Linker (Russian and Ukrainian)
	Plugin URI: https://wordpress.org/plugins/multipurpose-bible-linker-russian-and-ukrainian/
	Description: This plugin is designed to help people referring to Russian Bible. Once activated, it will find all texts that look like references to Biblical texts and replace them with link to actually biblical chapter and verses. Russian only.
	Version: 1.5.3
	Author: Vitaliy Bilanchuk, Vladimir Sokolov
	Author URI: http://helpforheart.org/stati/printsipyi-redaktirovaniya/

    Copyright 2013-2014 Vitaliy Bilanchuk (email: vitaly.bilanchuk@gmail.com), Vladimir Sokolov (email: gadfly.svy@gmail.com

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

include 'scripts/multibiblelinker.php';
include 'wp_config.inc.php';

function multibiblelinker_add_option_pages() {
	if (function_exists('add_options_page')) {
	    add_options_page('Multipurpose Bible Linker', 'Bible Linker', 8, basename(__FILE__), 'get_multibiblelinker_form');
	}		
}

function get_multibiblelinker_form() {

	if (isset($_POST['set_defaults'])) {
		echo '<div id="message" class="updated fade"><p><strong>';

		update_option('g_BibleSource', 'AllbibleInfoSource');
		update_option('language', 'ru');
		update_option('isRoman', true);
		update_option('doCorrection', true);
		update_option('doBookRepeat', false);

		echo 'Загружены опции по умолчанию';
		echo '</strong></p></div>';

	} else if (isset($_POST['multibiblelinker_update'])) {

		echo '<div id="message" class="updated fade"><p><strong>';

		update_option('g_BibleSource', (string)$_POST["g_BibleSource"]);
		update_option('language', (string)$_POST["language"]);
		update_option('isRoman', (bool)$_POST["isRoman"]);
		update_option('doCorrection', (bool)$_POST["doCorrection"]);
		update_option('doBookRepeat', (bool)$_POST["doBookRepeat"]);

		echo 'Конфигурация обновлена.';
		echo '</strong></p></div>';

	} ?>

<div class=wrap>
	<h2>Multipurpose Bible Linker (русский и украинский)</h2>
	
	<div style="padding: 0 0 0 30px;">
		<p>Плагин предназначен для подсвечивания ссылок на места из Библии на русском и украинском языках.</p>
	</div>
	
	<form method="post" action="<?php echo $_SERVER["REQUEST_URI"]; ?>">
	<input type="hidden" name="multibiblelinker_update" id="multibiblelinker_update" value="true" />

	<h3>Источники переводов</h3>
	
	<div style="padding: 0 0 0 30px;">
		<p>Плагин позволяет устанавливать адресацию библейских ссылок на разные онлайн источники: <a href='http://allbible.info/' target='blank'>http://allbible.info/</a> (по умолчанию), <a href='http://bible.com.ua/' target='blank'>http://bible.com.ua/</a>, <a href='http://biblezoom.ru/' target='blank'>http://biblezoom.ru/</a>, <a href='http://bibleonline.ru/' target='blank'>http://bibleonline.ru/</a>, <a href='http://bible-center.ru/' target='blank'>http://bible-center.ru/</a>, <a href='http://bibleserver.com/' target='blank'>http://bibleserver.com/</a>, <a href='http://bible.com/' target='blank'>http://bible.com/</a> (<a href='http://bible.us/' target='blank'>http://bible.us/</a>) или <a href='http://bible-desktop.com/' target='blank'>bible-desktop.com</a> (<a href='http://bibledesktop.ru/' target='blank'>bibledesktop.ru</a>).</p>
		
		<select name="g_BibleSource">
			<option <?php if (get_option('g_BibleSource') == "AllbibleInfoSource") echo "selected"; ?> value="AllbibleInfoSource">allbible.info (рус., укр. или англ.)</option>
			<option <?php if (get_option('g_BibleSource') == "BibleComUaSource") echo "selected"; ?> value="BibleComUaSource">bible.com.ua (рус., укр. и англ. одновременно)</option>
			<option <?php if (get_option('g_BibleSource') == "BiblezoomRuSource") echo "selected"; ?> value="BiblezoomRuSource">biblezoom.ru (греч. с подстрочником)</option>
			<option <?php if (get_option('g_BibleSource') == "BibleonlineRuSource") echo "selected"; ?> value="BibleonlineRuSource">bibleonline.ru (рус., укр., бел. или англ.)</option>
			<option <?php if (get_option('g_BibleSource') == "BibleCenterRuSource") echo "selected"; ?> value="BibleCenterRuSource">bible-center.ru (рус., англ., греч. и лат.)</option>
			<option <?php if (get_option('g_BibleSource') == "BibleserverComSource") echo "selected"; ?> value="BibleserverComSource">bibleserver.com (рус., болг., англ., греч., ивр. и лат.)</option>
			<option <?php if (get_option('g_BibleSource') == "BibleComSource") echo "selected"; ?> value="BibleComSource">bible.com или bible.us (рус., укр., болг., англ.)</option>
			<option <?php if (get_option('g_BibleSource') == "BibleDesktopComSource") echo "selected"; ?> value="BibleDesktopComSource">bible-desktop.com или bibledesktop.ru (рус., укр., бел. англ.)</option>
		</select>
	</div>
	
	<h3>Язык</h3>
	
	<div style="padding: 0 0 0 30px;">
		<p>Язык входящего текста. В данном случае ссылки ищутся по словарю и по желанию заменяются на стандартное написание. По умолчанию скрипт ссылается на Синодальный перевод для русского языка и перевод Огеенка для украинского (не все источники поддерживают укрианский перевод, в таком случае ссылка по умолчанию ведет на Синодальный).</p>
		
		<select name="language">
			<option <?php if (get_option('language') == "ru") echo "selected"; ?> value="ru">Русский</option>
			<option <?php if (get_option('language') == "ua") echo "selected"; ?> value="ua">Украинский</option>
		</select>
	</div>

	<h3>Опции обработки</h3>

	<div style="padding: 0 0 0 32px">
		<input type="checkbox" name="isRoman" value="checkbox" <?php if (get_option('isRoman')) echo "checked='checked'"; ?>/>&nbsp;&nbsp;
		<strong>Номера книг могут быть римскими цифрами</strong>
		<br />
		<input type="checkbox" name="doCorrection" value="checkbox" <?php if (get_option('doCorrection')) echo "checked='checked'"; ?>/>&nbsp;&nbsp;
		<strong>Исправлять названия книг на стандартные</strong>
		<br />
		<input type="checkbox" name="doBookRepeat" value="checkbox" <?php if (get_option('doBookRepeat')) echo "checked='checked'"; ?>/>&nbsp;&nbsp;
		<strong>Повторять название книги каждый раз перед главой, если глав несколько</strong>
	</div>
	
	<div class="submit">
		<input type="submit" name="set_defaults" value="<?php _e('Восстановить по умолчанию'); ?> &raquo;" />
		<input type="submit" name="multibiblelinker_update" value="<?php _e('Сохранить'); ?> &raquo;" />
	</div>

	</form>
</div>
<?php
}

add_filter('the_content', 'SearchBibleLinks');
add_action('admin_menu', 'multibiblelinker_add_option_pages');
?>