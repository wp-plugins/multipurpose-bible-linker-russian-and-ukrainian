<head>
	<title>Тестовая страница</title>
	<meta http-equiv="content-type" content="text/html; charset=UTF-8">
</head>
<body>

<?php
function get_sec() {
    $mtime = microtime();
    $mtime = explode(" ",$mtime);
    $mtime = $mtime[1] + $mtime[0];
    return $mtime;
}

$start_time = get_sec();

$textFilterFunc;

function add_filter($name, $func) {
	global $textFilterFunc;
	$textFilterFunc = $func;
}

include 'multibiblelinker.php';
include 'config_test.inc.php';

//echo chr(x0aa);

/* Тестовые данные */
$str = '1. Лишние пробелы: Иов.                 4:5,      6,      7; 8:1<br />
2. Ссылка на главу: Иова 4<br />
3. Одноглавные книги: Флм. 5 и Флм. 1:5; Флм. 1,9 и Флм. 2,6<br />
4. Две главы: 1 Кор. 5:3-4:4<br />
5. Спецобозначения: Иова 4:6 а; Иова 4:6а; Иова4:6б; 2 Кор 5:3-4а; 2 Кор 5:3-4:4а; 2 Кор 5:3б-4а; 2 Кор 5:3б-4:4а; Флм. 5а; 1 Кор. 10:31б, Пс. 118:100с и Иов 29:1,2с<br />
6. Дефис: 1 Кор. 5:3-4<br />
7. Среднее тире "–" и "ndash": 1 Кор. 5:3–4 и 1 Кор. 5:3&ndash;4<br />
8. Длинное тире "—" и "mdash": 1 Кор. 5:3—4 и 1 Кор. 5:3&mdash;4<br />
9. Различные написания книг (краткое и полное): Быт 1:2; Быт. 1:2; Бт 1:2 и Книга Бытие 1:2<br />
10. Различные комбинации пробелов: 2 Кор5:3-4; 2 Кор.5:3-4; 2 Кор.  5:3 - 4, 6,&nbsp;7,8,9; 2 Кор.&nbsp;5:3-4; 2 Кор. &nbsp; 5:3-4; 2 Кор.&nbsp; 5:3-4; 2&nbsp;Кор.&nbsp;5:3-4 и 2&nbsp;Кор. 5:3-4<br />
11. Неразрывный пробел в виде символа: 1 Фес. 5:11–14 РБО; ср. Рим. 15:14; Кол. 3:16<br />
12. Римские номера книг: II Кор. 5:3-4; I Цар. 17:34–41; I Паралипоменон 14:9<br />
13. Проверка разделителей: Мф. 3:4–6,8а (рус.) и Мт. 3,4–6.8а (укр.)<br />
14. Проверка переводов: Матфея 3:4 РБО2011, Матфея 3:4&nbsp;РБО2011, Матфея 3:4–4:4 РБО2011, Матфея 3:4  РБО2011, Матфея 3:4 &nbsp;РБО2011, Матфея 3:4&nbsp; РБО2011, Матфея 3:4а РБО2011, Матфея 3:4а  РБО2011, Матфея 3:4а; 5:6б РБО2011 и Флм. 5 РБО2011<br />
15. Проверка на ложные срабатывания: Через старейшин Откровение откровение проблемам прим.<br />
16. Длинный текст: "Исследуйте другие отрывки Священного Писания, которые говорят о страхе. Бог Велик, писание призывает верующих бояться только его (Исх.&nbsp;23:32,33; Числ. 13:26–33; 14:9; Втор.&nbsp;31:12,13; 1 Цар. 17; 4 Цар. 17:34–41; Иов. 38–42; Пс. 22:4; 55:2–4; 61; 90; 118:120; 120; Притч. 10:24; 28:1; 29:25; Ис.&nbsp;8:11&ndash;14; 51:7&ndash;8; 57:11; Иер. 17:5–10; Дан. 1,3,4,6; Мф. 10:28–39; Лк. 12:4,5; 1&nbsp;Петр.&nbsp;3:6,13,14; Рим. 8:15; 2 Тим. 1:7)".<br />
17. Начало абзаца (табуляция):<br />
	Мф. 18:15<br />
18. Конец абзаца:<br />
 Мф. 18:15–17; 5:23,24).\r\n\r\n5<br />
<br />';/**/

function ParseText($str) {
	global $textFilterFunc;
	if (is_callable($textFilterFunc)) {
		return call_user_func($textFilterFunc, $str);
	}
	return "error";
}
/**/
echo '<b>Настройки</b><br />Номера книг могут быть римскими цифрами: ';
echo $isRoman ? '<b>да</b><br />' : '<b>нет</b><br />';
echo 'Исправлять названия книг на стандартные: ';
echo $doCorrection ? '<b>да</b><br />' : '<b>нет</b><br />';
echo 'Повторять название книги каждый раз перед главой, если глав несколько: ';
echo $doBookRepeat ? '<b>да</b><br />' : '<b>нет</b><br />';
echo 'Язык ввода: ';
echo ($languageIn == 'ua') ? '<b>украинский</b><br />' : '<b>русский</b><br />';
echo 'Язык вывода: ';
echo ($languageOut == 'ua') ? '<b>украинский</b><br />' : '<b>русский</b><br />';
/**/
echo '<br />'.ParseText($str);

echo 'Переводы источника ';
/**/
switch ($g_BibleSource) {
    case 0:
        echo ParseText('allbible.info:<br />Мф. 3:4, Мф. 3:4 RST, Мф. 3:4 RV, Мф. 3:4 MDR, Мф. 3:4 UBIO, Мф. 3:4 KJV, Мф. 3:4 ASV'.'<br />');
        break;
    case 1:
        echo ParseText('bible.com.ua:<br />Мф. 3:4, Мф. 3:4 RST, Мф. 3:4 RV, Мф. 3:4 MDR, Мф. 3:4 UBIO, Мф. 3:4 KJV, Мф. 3:4 ASV<br />');
        break;
    case 2:
        echo ParseText('biblezoom.ru:<br />Мф. 3:4<br />');
        break;
	case 3:
        echo ParseText('bibleonline.ru:<br />Мф. 3:4, Мф. 3:4 RST, Мф. 3:4 CAS, Числ. 13:26 CAS, Мф. 3:4 RV, Мф. 3:4 UCS, Мф. 3:4 UBIO, Мф. 3:4 KJV, Мф. 3:4 BBS<br />');
        break;
	case 4:
        echo ParseText('bible-center.ru:<br />Мф. 3:4, Мф. 3:4 RST, Мф. 3:4 CAS, Мф. 3:4 RV, Мф. 3:4 NTKul, Мф. 3:4 UCS, Мф. 3:4 KJV, Мф. 3:4 NASB, Мф. 3:4 NIV, Мф. 3:4 NV, Мф. 3:4 LXX <br />');
        break;
	case 5:
        echo ParseText('bibleserver.com:<br />Мф. 3:4, Мф. 3:4 RST, Мф. 3:4 RSZ, Мф. 3:4 CRS, Мф. 3:4 BLG, Мф. 3:4 ESV, Мф. 3:4 NIV, Мф. 3:4 TNIV, Мф. 3:4 NIRV, Мф. 3:4 KJV, Мф. 3:4 KJVS, Мф. 3:4 LXX, Мф. 3:4 OT, Числ. 13:26 OT, Мф. 3:4 VUL<br />');
        break;
	case 6:
        echo ParseText('bible.com:<br />Мф. 3:4, Мф. 3:4 RST, Мф. 3:4 CAS, Мф. 3:4 NTKul, Мф. 3:4 CRS, Мф. 3:4 UCS, Мф. 3:4 RSZ, Мф. 3:4 RSP, Мф. 3:4 RUVZ, Мф. 3:4 BLG, Мф. 3:4 UBIO, Мф. 3:4 UKRK, Мф. 3:4 UMT, Мф. 3:4 KJV, Мф. 3:4 ASV, Мф. 3:4 NASB, Мф. 3:4 NIV, Мф. 3:4 ESV, Мф. 3:4 NIRV<br />');
        break;
}
echo '<br />'.ParseText($strSource);
/**/
$exec_time = get_sec() - $start_time;
echo 'Время выполнения: '.$exec_time.' сек.';
?>

</body>