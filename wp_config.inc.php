<?php
//Настройки скрипта 
$_ENV["isRoman"] 		= get_option('isRoman');		// Номера книг могут быть римскими цифрами
$_ENV["doCorrection"] 	= get_option('doCorrection');	// Исправлять названия книг на стандартные
$_ENV["doBookRepeat"] 	= get_option('doBookRepeat');	// Повторять название книги каждый раз перед главой, если глав несколько
$_ENV["doNotWrap"] 		= get_option('doNotWrap');		// Делать ссылку неразрывной
$_ENV["linkStandart"] 	= get_option('linkStandart');	// Стандарт написания ссылки: восточный (Мф. 3:4–6,8) и западный (Мт. 3,4–6.8)
$_ENV["spaceType"] 		= "&".get_option('spaceType').";";	// Тип пробела: неразрывный (&nbsp;) и половинный (&thinsp;)
$_ENV["popupWindow"] 	= get_option('popupWindow');	// Всплывающие окно
$_ENV["popupSource"] 	= get_option('popupSource');	// Источник текста для всплывающего окна

// Выбор источника онлайн Библии
//$g_BibleSource = AllbibleInfoSource;		// http://allbible.info/ 					(рус., укр. или англ.)
//$g_BibleSource = BibleComUaSource;		// http://bible.com.ua/ 					(рус., укр. и англ. одновременно)
//$g_BibleSource = BiblezoomRuSource;		// http://biblezoom.ru/ 					(греч. с подстрочником)
//$g_BibleSource = BibleonlineRuSource;		// http://bibleonline.ru/ 					(рус., укр., бел. или англ.)
//$g_BibleSource = BibleCenterRuSource;		// http://bible-center.ru/ 					(рус., англ., греч. или лат.)
//$g_BibleSource = BibleserverComSource;	// http://bibleserver.com/ 					(рус., болг., англ., греч., ивр. или лат.)
//$g_BibleSource = BibleComSource;			// http://bible.com/ или http://bible.us/	(рус., укр., болг. или англ.)
//$g_BibleSource = BibleDesktopComSource;	// http://bible-desktop.com/ или http://bibledesktop.ru/ (рус., укр., бел. или англ.)
//$g_BibleSource = BiblegatewayComSource;	// https://biblegateway.com/ 				(рус., укр., болг., англ. или лат.)
//$g_BibleSource = AzbykaRuSource;			// http://azbyka.ru/						(рус., слав., греч., ивр. или лат.)
//$g_BibleSource = BibliaComSource;			// http://biblia.com/						(рус., англ. или лат.)
//$g_BibleSource = BibleOrgSource;			// http://bibles.org/						(рус., болг., англ., греч. или ивр.)

$_ENV["g_BibleSource"] = get_option('g_BibleSource');

// Проверка на правильность установки языка
if (get_option('language') == 'en' || get_option('language') == 'ru' || get_option('language') == 'ua') {
	$_ENV["languageIn"] 	= get_option('language');	// Язык анализируемых ссылок (ru, ua, en)
	$_ENV["languageOut"] 	= get_option('language');	// Язык вывода (ru, ua, en)
} else {
	switch (get_locale()) {
		case "en_US":
            $_ENV["languageIn"] 	= 'en';
            $_ENV["languageOut"] 	= 'en';
			update_option('language', 'en');
            break;
		case "ru_RU":
			$_ENV["languageIn"] 	= 'ru';
			$_ENV["languageOut"] 	= 'ru';
			update_option('language', 'ru');
            break;
		case "uk_UA":
			$_ENV["languageIn"] 	= 'ua';
			$_ENV["languageOut"] 	= 'ua';
			update_option('language', 'ua');
			break;
		default:
			$_ENV["languageIn"] 	= 'ru';
			$_ENV["languageOut"] 	= 'ru';
			update_option('language', 'ru');
    }
}

// Проверка на правильность пробела
if ($_ENV["spaceType"] != '&nbsp;' && $_ENV["spaceType"] != '&thinsp;') {
	$_ENV["spaceType"] = '&nbsp;';
}

// Разделители между главами и стихами в зависимости от стандарта
switch($_ENV["linkStandart"]) {
	case 'east':
		$_ENV["ChapterSeparatorVerseIn"] 	= ':';
		$_ENV["VerseSeparatorVerseIn"] 		= ',';
		$_ENV["ChapterSeparatorVerseOut"] 	= ':';
		$_ENV["VerseSeparatorVerseOut"] 	= ',';
		break;
	case 'west':
		$_ENV["ChapterSeparatorVerseIn"] 	= ',';
		$_ENV["VerseSeparatorVerseIn"] 		= '.';
		$_ENV["ChapterSeparatorVerseOut"] 	= ',';
		$_ENV["VerseSeparatorVerseOut"] 	= '.';
		break;
	default:
		$_ENV["ChapterSeparatorVerseIn"] 	= ':';
		$_ENV["VerseSeparatorVerseIn"] 		= ',';
		$_ENV["ChapterSeparatorVerseOut"] 	= ':';
		$_ENV["VerseSeparatorVerseOut"] 	= ',';
		break;
}

// Аббревиатуры переводов:

define("RSTTranslation",	$_ENV["spaceType"]."RST");		// Русский синодальный текст (1876, 2010)
define("RVTranslation",		$_ENV["spaceType"]."RV");		// Радостная весть / РБО (рус., только НЗ) [Полная Библия - RBO]
define("MDRTranslation",	$_ENV["spaceType"]."MDR");		// Библия: современный перевод Библии (1993)
define("CASTranslation",	$_ENV["spaceType"]."CAS");		// Перевод еп. Кассиана (рус., только НЗ)
define("NTKulTranslation",	$_ENV["spaceType"]."NTKul");	// Перевод Кулакова (рус., только НЗ)
define("MAKTranslation",	$_ENV["spaceType"]."MAK");		// Перевод архимандрита Макария (рус.)
define("RSZTranslation",	$_ENV["spaceType"]."RSZ");		// НЗ «Слово Жизни» (1993, только НЗ)
define("ISBTranslation",	$_ENV["spaceType"]."ISB");		// Новый русский перевод (ISB)
define("CRSTranslation",	$_ENV["spaceType"]."CRS");		// Священное Писание: "восточный перевод" (рус.)
define("RSPTranslation",	$_ENV["spaceType"]."RSP");		// Новый Завет: совр. перевод (2011, рус., только НЗ)
define("RUVZTranslation",	$_ENV["spaceType"]."RUVZ");		// Новый завет Журомского (рус., только НЗ)
define("UCSTranslation",	$_ENV["spaceType"]."UCS");		// Церковнославянский перевод
define("UBIOTranslation",	$_ENV["spaceType"]."UBIO");		// Біблія / пер. Огієнко (1962)
define("UKRKTranslation",	$_ENV["spaceType"]."UKRK");		// Біблія / пер. Куліша та Пулюя (1905, укр.)
define("UMTTranslation",	$_ENV["spaceType"]."UMT");		// Свята Біблія: сучасною мовою (2007, укр., только НЗ)
define("UKHTranslation",	$_ENV["spaceType"]."UKH");		// Біблія / пер. Хоменка
define("UBTTranslation",	$_ENV["spaceType"]."UBT");		// Біблія / УБО
define("WBTCTranslation",	$_ENV["spaceType"]."UK_WBTC");	// Український Новий Заповіт (укр., только НЗ)
define("BBSTranslation",	$_ENV["spaceType"]."BBS");		// Беларускі пераклад (бел.)
define("BLGTranslation",	$_ENV["spaceType"]."BLG");		// Българската Библия (болг., 1940)
define("KJVTranslation",	$_ENV["spaceType"]."KJV");		// King Jame Version (1611, 1769)
define("ASVTranslation",	$_ENV["spaceType"]."ASV"); 		// American Standard Version (1901)
define("NASBTranslation",	$_ENV["spaceType"]."NASB");		// New American Standard Bible (англ.)
define("NIVTranslation",	$_ENV["spaceType"]."NIV");		// New International Version (англ.)
define("ESVTranslation",	$_ENV["spaceType"]."ESV");		// English Standard Version (англ.)
define("TNIVTranslation",	$_ENV["spaceType"]."TNIV");		// Today's New International Version (англ.)
define("NIRVTranslation",	$_ENV["spaceType"]."NIRV");		// New International Readers Version (англ.)
define("KJVSTranslation",	$_ENV["spaceType"]."KJVS");		// King James Version with Strong's Dictionary (англ.)
define("NVTranslation",		$_ENV["spaceType"]."NV");		// Новая Вульгата (лат.)
define("LXXTranslation",	$_ENV["spaceType"]."LXX");		// Септуагинта (греч.)
define("OTTranslation",		$_ENV["spaceType"]."OT");		// Hebrew OT (ивр.)
define("VULTranslation",	$_ENV["spaceType"]."VUL");		// Vulgata (лат.)

// Увеличение времени выполнения скрипта и просмотр результата
//set_time_limit(60);
//echo ini_get('max_execution_time');
?>