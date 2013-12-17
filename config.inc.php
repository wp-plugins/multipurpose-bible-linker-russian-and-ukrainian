<?php

/* Настройки скрипта */
$isRoman = true;			// Номера книг могут быть римскими цифрами
$doCorrection = true;		// Исправлять названия книг на стандартные
$languageIn = 'ru';			// Язык анализируемых ссылок (пока не работает)
$languageOut = 'ru';		// Язык вывода (ru, ua)

// Проверка на правильность установки языка
if ($languageIn != 'ru' || $languageIn != 'ua') {
	$languageIn = 'ru';
}
if ($languageOut != 'ru' || $languageOut != 'ua') {
	$languageOut = 'ru';
}

// Разделители между главами и стихами (рус. Мф. 3:4–6,8 и укр. Мт. 3,4–6.8)
if ($languageIn == 'ua') {
	$СhapterSeparatorVerseIn = ',';
	$VerseSeparatorVerseIn = '.';
} else {
	$СhapterSeparatorVerseIn = ':';
	$VerseSeparatorVerseIn = ',';
}
if ($languageOut == 'ua') {
	$СhapterSeparatorVerseOut = ',';
	$VerseSeparatorVerseOut = '.';
} else {
	$СhapterSeparatorVerseOut = ':';
	$VerseSeparatorVerseOut = ',';
}

// Выбор источника онлайн Библии:

define("AllbibleInfoSource", 0);
define("BibleComUaSource", 1);
define("BiblezoomRuSource", 2);
define("BibleonlineRuSource", 3);
define("BibleCenterRuSource", 4);
define("BibleserverComSource", 5);
define("BibleComSource", 6);

$g_BibleSource = AllbibleInfoSource;		// http://allbible.info/ 					(рус., укр. или англ.)
//$g_BibleSource = BibleComUaSource;		// http://bible.com.ua/ 					(рус., укр. и англ. одновременно)
//$g_BibleSource = BiblezoomRuSource;		// http://biblezoom.ru/ 					(греч. с подстрочником)
//$g_BibleSource = BibleonlineRuSource;		// http://bibleonline.ru/ 					(рус., укр., бел. или англ.)
//$g_BibleSource = BibleCenterRuSource;		// http://bible-center.ru/ 					(рус., англ., греч. и лат.)
//$g_BibleSource = BibleserverComSource;	// http://bibleserver.com/ 					(рус., болг., англ., греч., ивр. и лат.)
//$g_BibleSource = BibleComSource;			// http://bible.com/ или http://bible.us/	(рус., укр., болг., англ.)

if (!$g_BibleSource) $g_BibleSource = AllbibleInfoSource;

// Аббревиатуры переводов:

define("RSTTranslation", " RST");		// Русский синодальный текст (1876, 2010)
define("RVTranslation", " RV");			// Радостная весть: совр. перевод НЗ на русский язык / РБО (рус., только НЗ) [Полная Библия - RBO]
define("MDRTranslation", " MDR");		// Библия: современный перевод Библии (1993)
define("CASTranslation", " CAS");		// Перевод еп. Кассиана (рус., только НЗ)
define("NTKulTranslation", " NTKul");	// Перевод Кулакова (рус., только НЗ)
define("RSZTranslation", " RSZ");		// НЗ «Слово Жизни» (1993, только НЗ)
define("CRSTranslation", " CRS");		// Священное Писание: "восточный перевод" (рус.)
define("RSPTranslation", " RSP");		// Новый Завет: современный перевод (2011, рус., только НЗ)
define("RUVZTranslation", " RUVZ");		// Новый завет Журомского (рус., только НЗ)
define("UCSTranslation", " UCS");		// Церковнославянский перевод
define("UBIOTranslation", " UBIO");		// Біблія / пер. Огієнко (1962)
define("UKRKTranslation", " UKRK");		// Біблія / пер. Куліша та Пулюя (1905, укр.)
define("UMTTranslation", " UMT");		// Свята Біблія: сучасною мовою (2007, укр., только НЗ)
define("BBSTranslation", " BBS");		// Беларускі пераклад (бел.)
define("BLGTranslation", " BLG");		// Българската Библия (болг., 1940)
define("KJVTranslation", " KJV");		// King Jame Version (1611, 1769)
define("ASVTranslation", " ASV"); 		// American Standard Version (1901)
define("NASBTranslation", " NASB");		// New American Standard Bible (англ.)
define("NIVTranslation", " NIV");		// New International Version (англ.)
define("ESVTranslation", " ESV");		// English Standard Version (англ.)
define("TNIVTranslation", " TNIV");		// Today's New International Version (англ.)
define("NIRVTranslation", " NIRV");		// New International Readers Version (англ.)
define("KJVSTranslation", " KJVS");		// King James Version with Strong's Dictionary (англ.)
define("NVTranslation", " NV");			// Новая Вульгата (лат.)
define("LXXTranslation", " LXX");		// Септуагинта (греч.)
define("OTTranslation", " OT");			// Hebrew OT (ивр.)
define("VULTranslation", " VUL");		// Vulgata (лат.)
?>