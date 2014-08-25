=== Plugin Name ===
Plugin Name: Multipurpose Bible Linker
Plugin URI: https://wordpress.org/plugins/multipurpose-bible-linker-russian-and-ukrainian/
Contributors: Gadfly_svy
Tags: bible, christian, widget, scripture, verse, passage, biblia, English, Russian, Ukrainian
Requires at least: 3.5.0
Tested up to: 3.9.2
Stable tag: 1.6.3
Author: Vitaliy Bilanchuk, Vladimir Sokolov
Author URI: http://helpforheart.org/stati/printsipyi-redaktirovaniya/
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Find and replace Bible verses with direct links to the Bible.

== Description ==

This plugin is designed to help people referring to English, Russian or Ukrainian Bibles. Once activated, it will find all texts that look like references to Biblical texts and replace them with link to actual biblical chapter and verses.
Supported recognition of long and short titles. It is possible to use one of seven online Bibles (http://allbible.info/, http://bible.com.ua/, http://biblezoom.ru/, http://bibleonline.ru/, http://bible-center.ru/, http://bibleserver.com/, http://bible.com/ or http://bible.us/, http://bible-desktop.com/ or http://bibledesktop.ru/).
Plugin is based on Simlpe Bible Linker Russian, but has significantly greater functional.

1. Support different types of separators: Matt. 3,4&#8210;6.8a (west) and Matt. 3:4&#8210;6,8a (east)<br />
1. Fixes a hyphen &ldquo;- &rdquo;, &ldquo;8208&rdquo;, double &ldquo;-- &rdquo; and a non-breaking &ldquo;8209&rdquo;: 1 Cor. 5:3-5a, 1 Cor. 5:3&#8208;5a, 1 Cor. 5:3--5a and 1 Cor. 5:3&#8209;5a<br />
1. Numeric dash &ldquo;8210&rdquo;, &ldquo;minus&rdquo; and &ldquo;8722&rdquo;: 1 Cor. 5:3&#8210;5a, 1 Cor. 5:3&minus;5a and 1 Cor. 5:3&#8722;5a<br />


== Installation ==

Installing the Bible Verse plugin is very straight forward:

1. Once you have downloaded and extracted multibiblelinker_ru.zip, upload the entire bibleverse FOLDER to /wp-content/plugins/.
1. You should now have wp-content/plugins/multibiblelinker_ru/multibiblelinker_ru.php.
1. Activate it from the Plugin Panel in your Admin interface.
1. The results should now be seen in your posts.

The algorithm can be work slow with large text blocks, therefore it is recommended to use the cache (for example, using the plugin WP Super Cache).