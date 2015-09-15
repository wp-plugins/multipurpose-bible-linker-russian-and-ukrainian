=== Plugin Name ===
Plugin Name: Multipurpose Bible Linker
Plugin URI: https://wordpress.org/plugins/multipurpose-bible-linker-russian-and-ukrainian/
Contributors: Gadfly_svy
Tags: bible, сhristian, reference, widget, scripture, verse, verses, passage, biblia, English, Russian, Ukrainian
Requires at least: 3.5.0
Tested up to: 4.3.1
Stable tag: 1.7.2
Author: Vitaliy Bilanchuk, Vladimir Sokolov
Author URI: http://helpforheart.org/stati/printsipyi-redaktirovaniya/
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Find and replace Bible verses with direct links to the Bible.

== Description ==

This plugin is designed to help people referring to English, Russian or Ukrainian Bibles. Once activated, it will find all texts that look like references to Biblical texts and replace them with a link to the actual Bible chapter and verses.
Supported recognition of long and short titles. It is possible to use one of twelve online Bibles (http://allbible.info/, http://bible.com.ua/, http://biblezoom.ru/, http://bibleonline.ru/, http://bible-center.ru/, http://bibleserver.com/, http://bible.com/ or http://bible.us/, http://bible-desktop.com/ or http://bibledesktop.ru/, https://biblegateway.com/, http://azbyka.ru/, https://biblia.com/, http://bibles.org/). Pop-ups are using four sources with JSON or XML APIs (http://bibleonline.ru/, https://biblia.com/, https://getbible.net/, http://www.4-14.org.uk [http://preachingcentral.com/]).
Plugin is based on Simlpe Bible Linker Russian, but has significantly greater functional.

1. Support different types of separators.<br />
1. Fixes a hyphen &ldquo;-&rdquo;, &ldquo;8208&rdquo;, double &ldquo;--&rdquo; and a non-breaking &ldquo;8209&rdquo;.<br />
1. Numeric dash &ldquo;8210&rdquo;, &ldquo;minus&rdquo; and &ldquo;8722&rdquo;.<br />
1. En dash &ldquo;&ndash;&rdquo;, &ldquo;ndash&rdquo; and &ldquo;8211&rdquo;.<br />
1. Em dash &ldquo;&mdash;&rdquo;, &ldquo;mdash&rdquo; and &ldquo;8212&rdquo;.<br />
1. Various combinations of spaces.<br />
1. Rare types of spaces &ldquo;emsp&rdquo;, &ldquo;ensp&rdquo;, &ldquo;8196&rdquo;, &ldquo;8197&rdquo;, &ldquo;8198&rdquo;, &ldquo;thinsp&rdquo; and &ldquo;8202&rdquo;.<br />
1. Apostrophe as a symbol (Ukrainian only).<br />
1. Extra spaces.<br />
1. Extra comma.<br />
1. Pointer to the chapter &ldquo;ch&rdquo; and &ldquo;ch.&rdquo;.<br />
1. Link to chapter.<br />
1. Two chapters.<br />
1. One chapter books.<br />
1. Additional symbols.<br />
1. Different translations.<br />
1. Checking the absence of a translation (for Old Testament).<br />
1. Short and complete writing books.<br />
1. Roman numbers of books.<br />
1. Roman numbers of chapters and verses.<br />
1. Number of chapters more than it actually is.<br />
1. Correction of related verses and chapters across the dash.<br />

== Installation ==

Installing the Multipurpose Bible Verse plugin is very straight forward:

1. Once you have downloaded and extracted multipurpose-bible-linker-russian-and-ukrainian.zip, upload the entire bibleverse FOLDER to /wp-content/plugins/.
1. You should now have wp-content/plugins/multibiblelinker/ or similar.
1. Activate it from the Plugin Panel in your Admin interface.
1. The results should now be seen in your posts.

Or install from the WordPress repository.

With large text blocks the algorithm works slowly, therefore it is recommended to use the cache (for example, using the plugin WP Super Cache or Hyper Cache).