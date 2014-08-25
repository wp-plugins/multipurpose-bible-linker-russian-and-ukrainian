<?php
//Настройки скрипта 
$_ENV["isRoman"] = 		get_option('isRoman');		// Номера книг могут быть римскими цифрами
$_ENV["doCorrection"] = get_option('doCorrection');	// Исправлять названия книг на стандартные
$_ENV["doBookRepeat"] = get_option('doBookRepeat');	// Повторять название книги каждый раз перед главой, если глав несколько
$_ENV["languageIn"] = 	get_option('language');		// Язык анализируемых ссылок (ru, ua, en)
$_ENV["languageOut"] = 	get_option('language');		// Язык вывода (ru, ua, en)
$_ENV["linkStandart"] = get_option('linkStandart');	// Стандарт написания ссылки: восточный (Мф. 3:4–6,8) и западный (Мт. 3,4–6.8)
		
// Выбор источника онлайн Библии
//$g_BibleSource = AllbibleInfoSource;		// http://allbible.info/ 					(рус., укр. или англ.)
//$g_BibleSource = BibleComUaSource;		// http://bible.com.ua/ 					(рус., укр. и англ. одновременно)
//$g_BibleSource = BiblezoomRuSource;		// http://biblezoom.ru/ 					(греч. с подстрочником)
//$g_BibleSource = BibleonlineRuSource;		// http://bibleonline.ru/ 					(рус., укр., бел. или англ.)
//$g_BibleSource = BibleCenterRuSource;		// http://bible-center.ru/ 					(рус., англ., греч. и лат.)
//$g_BibleSource = BibleserverComSource;	// http://bibleserver.com/ 				(рус., болг., англ., греч., ивр. и лат.)
//$g_BibleSource = BibleComSource;			// http://bible.com/ или http://bible.us/	(рус., укр., болг., англ.)
//$g_BibleSource = BibleDesktopComSource;	// http://bible-desktop.com/ или http://bibledesktop.ru/ (рус., укр., бел. англ.)
			
$_ENV["g_BibleSource"] = get_option('g_BibleSource');
		
// Проверка на правильность установки языка
if ($_ENV["languageIn"] != 'ru' && $_ENV["languageIn"] != 'ua' && $_ENV["languageIn"] != 'en') {
	$_ENV["languageIn"] = 'ru';
}
if ($_ENV["languageOut"] != 'ru' && $_ENV["languageOut"] != 'ua' && $_ENV["languageOut"] != 'en') {
	$_ENV["languageOut"] = 'ru';
}
		
// Разделители между главами и стихами в зависимости от стандарта
switch($_ENV["linkStandart"]) {
	case 'east':
		$_ENV["ChapterSeparatorVerseIn"] = ':';
		$_ENV["VerseSeparatorVerseIn"] = ',';
		$_ENV["ChapterSeparatorVerseOut"] = ':';
		$_ENV["VerseSeparatorVerseOut"] = ',';
		break;
	case 'west':
		$_ENV["ChapterSeparatorVerseIn"] = ',';
		$_ENV["VerseSeparatorVerseIn"] = '.';
		$_ENV["ChapterSeparatorVerseOut"] = ',';
		$_ENV["VerseSeparatorVerseOut"] = '.';
		break;
	default:
		$_ENV["ChapterSeparatorVerseIn"] = ':';
		$_ENV["VerseSeparatorVerseIn"] = ',';
		$_ENV["ChapterSeparatorVerseOut"] = ':';
		$_ENV["VerseSeparatorVerseOut"] = ',';
		break;
}

// Аббревиатуры переводов:

define("RSTTranslation", " RST");		// Русский синодальный текст (1876, 2010)
define("RVTranslation", " RV");			// Радостная весть: совр. перевод НЗ на русский язык / РБО (рус., только НЗ) [Полная Библия - RBO]
define("MDRTranslation", " MDR");		// Библия: современный перевод Библии (1993)
define("CASTranslation", " CAS");		// Перевод еп. Кассиана (рус., только НЗ)
define("NTKulTranslation", " NTKul");	// Перевод Кулакова (рус., только НЗ)
define("RSZTranslation", " RSZ");		// НЗ «Слово Жизни» (1993, только НЗ)
define("ISBTranslation", " ISB");		// Новый русский перевод (ISB)
define("CRSTranslation", " CRS");		// Священное Писание: "восточный перевод" (рус.)
define("RSPTranslation", " RSP");		// Новый Завет: современный перевод (2011, рус., только НЗ)
define("RUVZTranslation", " RUVZ");		// Новый завет Журомского (рус., только НЗ)
define("UCSTranslation", " UCS");		// Церковнославянский перевод
define("UBIOTranslation", " UBIO");		// Біблія / пер. Огієнко (1962)
define("UKRKTranslation", " UKRK");		// Біблія / пер. Куліша та Пулюя (1905, укр.)
define("UMTTranslation", " UMT");		// Свята Біблія: сучасною мовою (2007, укр., только НЗ)
define("UKHTranslation", " UKH");		// Біблія / пер. Хоменка
define("UBTTranslation", " UBT");		// Біблія / УБО
define("WBTCTranslation", " UK_WBTC");	// Український Новий Заповіт (укр., только НЗ)
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