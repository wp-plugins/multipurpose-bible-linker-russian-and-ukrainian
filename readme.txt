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

This plugin is designed to help people referring to English, Russian or Ukrainian Bibles. Once activated, it will find all texts that look like references to Biblical texts and replace them with a link to the actual Bible chapter and verses.
Supported recognition of long and short titles. It is possible to use one of seven online Bibles (http://allbible.info/, http://bible.com.ua/, http://biblezoom.ru/, http://bibleonline.ru/, http://bible-center.ru/, http://bibleserver.com/, http://bible.com/ or http://bible.us/, http://bible-desktop.com/ or http://bibledesktop.ru/).
Plugin is based on Simlpe Bible Linker Russian, but has significantly greater functional.

1. Support different types of separators: Matt. 3,4&ndash;6.8a (west) and Matt. 3:4&ndash;6,8a (east)<br />
1. Fixes a hyphen &ldquo;-&rdquo;, &ldquo;8208&rdquo;, double &ldquo;--&rdquo; and a non-breaking &ldquo;8209&rdquo;: 1 Cor. 5:3-5a, 1 Cor. 5:3&#8208;5a, 1 Cor. 5:3--5a and 1 Cor. 5:3&#8209;5a<br />
1. Numeric dash &ldquo;8210&rdquo;, &ldquo;minus&rdquo; and &ldquo;8722&rdquo;: 1 Cor. 5:3&#8210;5a, 1 Cor. 5:3&minus;5a and 1 Cor. 5:3&#8722;5a<br />
1. En dash &ldquo;&ndash;&rdquo;, &ldquo;ndash&rdquo; and &ldquo;8211&rdquo;: 1 Cor. 5:3&ndash;5a, 1 Cor. 5:3&Ndash;5a and 1 Cor. 5:3&#8211;5a<br />
1. Em dash &ldquo;&mdash;&rdquo;, &ldquo;mdash&rdquo; and &ldquo;8212&rdquo;: 1 Cor. 5:3&mdash;5a, 1 Cor. 5:3&mdash;5a and 1 Cor. 5:3&#8212;5a<br />
1. Various combinations of spaces: 2 Cor 5:3-5; 2 Cor.5:3-5; 2 Cor.  5:3 - 5, 6,&nbsp;7,8,9; 2 Cor.&nbsp;5:3-5; 2 Cor. &nbsp; 5:3-5; 2 Cor.&nbsp; 5:3-5; 2&nbsp;Cor.&nbsp;5:3-5 and 2&nbsp;Cor. 5:3-5<br />
1. Non-breaking space in the form of a symbol: 1 Thess. 5:11&ndash;14 ASV; Rom. 15:14; Col. 3:16<br />
1. Apostrophe as a symbol: &#1060;&#1080;&#1083;&#1080;&#1087;&#039;&#1103;&#1085;&#1072;&#1084; 1,2 (Ukrainian only)<br />
1. Extra spaces: Job.                 4:5,      6,      7; 8:1<br />
1. Extra comma: Job, 4:5; Job., 4:5<br />
1. Pointer to the chapter &ldquo;ch&rdquo; and &ldquo;ch.&rdquo;: Job ch 4; Job &nbsp;ch. 4,5; Job ch.&nbsp;4-6; Job ch.&nbsp;4<br />
1. Link to chapter: Job 4; Job 3, 5; Job 4-6<br />
1. Two chapters: 1 Cor. 5:3-4:4<br />
1. One chapter books: Phlm. 5 and Phlm. 1:5; Phlm. 1,9 and Phlm. 2,6<br />
1. Additional symbols: Job 4:6 a; Job 4:6a; Job4:6b; 2 Cor 5:3-4a; 2 Cor 5:3-4:4a; 2 Cor 5:3b-4a; 2 Cor 5:3b-4:4a; Phlm. 5a; 1 Cor. 10:31b, Ps. 118:100n and Job 29:1,2n<br />
1. Different translations: Matt 3:4 ASV, Matt 3:4&nbsp;KJV<br />
1. Checking the absence of a translation (for Old Testament): Gen. 3:4 ASV and Matt. 3:4 ASV<br />
1. Short and complete writing books: Gen 1:2; Gn. 1:2 and Genesis 1:2<br />
1. Roman numbers of the books: II Cor. 5:3-4; I Sam. 17:34&ndash;41; I Chronicles 14:9<br />
1. Number of chapters more than it actually is: Ps. 151:2; Matt. 16:1; 28:45; 29:5<br />
1. Correction of related verses and chapters across the dash: Isa. 1-2; Isa. 1:2-3; Isa. 1:2-3:4; 1 Cor. 2:3a-4:5; Matt. 1:1,2&ndash;4,6-7,14<br />

== Installation ==

Installing the Multipurpose Bible Verse plugin is very straight forward:

1. Once you have downloaded and extracted multipurpose-bible-linker-russian-and-ukrainian.zip, upload the entire bibleverse FOLDER to /wp-content/plugins/.
1. You should now have wp-content/plugins/multibiblelinker/ or similar.
1. Activate it from the Plugin Panel in your Admin interface.
1. The results should now be seen in your posts.

Or install from the WordPress repository.

With large text blocks the algorithm works slowly, therefore it is recommended to use the cache (for example, using the plugin WP Super Cache).