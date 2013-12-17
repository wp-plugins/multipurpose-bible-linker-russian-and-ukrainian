<?php

abstract class BibleSource {
	protected $m_bookIndex ="";
	function __construct($bookIndex) {
		$this->m_bookIndex = $bookIndex;
	}
	
	public static function get($bookIndex) {
		global $g_BibleSource;
		if (null != $g_BibleSource) {
			switch($g_BibleSource) {
				case BibleComUaSource: 		return new BibleComUa($bookIndex);
				case BiblezoomRuSource: 	return new BiblezoomRu($bookIndex);
				case BibleonlineRuSource: 	return new BibleonlineRu($bookIndex);
				case BibleCenterRuSource: 	return new BibleCenterRu($bookIndex);
				case BibleserverComSource: 	return new BibleserverCom($bookIndex);
				case BibleComSource: 		return new BibleCom($bookIndex);
				default: 					return new AllbibleInfo($bookIndex);
			}
		}
		return new AllbibleInfo($bookIndex);
	}
	
	abstract public function getLink($translation = "");
	abstract public function getSingleChapterPart($chapter);
	abstract public function getChapterPart($chapter);
	abstract public function getVersePart($verse);
	abstract public function checkForTranslationExist($translation);
};

class AllbibleInfo extends BibleSource {
	function __construct($bookIndex) {
		parent::__construct($bookIndex);
	}
	
	public function getLink($translation = "") {
		return 'http://allbible.info/bible/' . $this->GetTranslationPrefix($translation) . $this->GetIndexName($this->m_bookIndex);
	}

	public function GetTranslationPrefix($translation) {
		switch($translation) {
			case RSTTranslation: return 'sinodal/';
			case MDRTranslation: return 'modern/';
			case RVTranslation: return 'modernrbo/';
			case UBIOTranslation: return 'ogienko/';
			case KJVTranslation: return 'kingjames/';
			case ASVTranslation: return 'standart/';
			default: return 'sinodal/';
		}
	}
	
	public function getSingleChapterPart($chapter) {
		return '/1#' . $chapter;
	}
	
	public function getChapterPart($chapter) {
		return '/' . $chapter;
	}
	
	public function getVersePart($verse) {
		return '#' . $verse;
	}
	
	public function checkForTranslationExist($translation) {
		$LastBookOfOldTestament = 39;
		switch($translation) {
			case RSTTranslation: return true;
			case MDRTranslation: return true;
			case RVTranslation: return (integer)$this->m_bookIndex > $LastBookOfOldTestament ? true : false; // только НЗ
			case UBIOTranslation: return true;
			case KJVTranslation: return true;
			case ASVTranslation: return true;
		}
		return false;
	}
	
	protected function GetIndexName($bookIndex) {
		$indexes = array(
					'1' => 'ge',
					'2' => 'ex',
					'3' => 'le',
					'4' => 'nu',
					'5' => 'de',
					'6' => 'jos',
					'7' => 'jud',
					'8' => 'ru',
					'9' => '1ki',
					'10' => '2ki',
					'11' => '3ki',
					'12' => '4ki',
					'13' => '1ch',
					'14' => '2ch',
					'15' => 'ezr',
					'16' => 'ne',
					'17' => 'es',
					'18' => 'job',
					'19' => 'ps',
					'20' => 'pr',
					'21' => 'ec',
					'22' => 'so',
					'23' => 'isa',
					'24' => 'jer',
					'25' => 'la',
					'26' => 'eze',
					'27' => 'da',
					'28' => 'ho',
					'29' => 'joe',
					'30' => 'am',
					'31' => 'ob',
					'32' => 'jon',
					'33' => 'mic',
					'34' => 'na',
					'35' => 'hab',
					'36' => 'sof',
					'37' => 'hag',
					'38' => 'zec',
					'39' => 'mal',
					'40' => 'mt',
					'41' => 'mr',
					'42' => 'lu',
					'43' => 'joh',
					'44' => 'ac',
					'59' => 'jas',
					'60' => '1pe',
					'61' => '2pe',
					'62' => '1jo',
					'63' => '2jo',
					'64' => '3jo',
					'65' => 'jude',
					'45' => 'ro',
					'46' => '1co',
					'47' => '2co',
					'48' => 'ga',
					'49' => 'eph',
					'50' => 'php',
					'51' => 'col',
					'52' => '1th',
					'53' => '2th',
					'54' => '1ti',
					'55' => '2ti',
					'56' => 'tit',
					'57' => 'phm',
					'58' => 'heb',
					'66' => 're',
					);
		return $indexes[$bookIndex];
	}
};

class BibleComUa extends AllbibleInfo {
	public function getLink($translation = "") {
		return 'http://bible.com.ua/bible/r/' . $this->m_bookIndex;
	}
};

class BiblezoomRu extends AllbibleInfo {

	public function getLink($translation = "") {
		return 'http://biblezoom.ru/#' . $this->GetIndexName($this->m_bookIndex);
	}

	public function getSingleChapterPart($chapter) {
		return '-1-' . $chapter;
	}
	
	public function getChapterPart($chapter) {
		return '-' . $chapter;
	}
	
	public function getVersePart($verse) {
		return '-' . $verse;
	}
	
	protected function GetIndexName($bookIndex) {
		$indexes = array(
						'1' => '-1',
						'2' => '-2',
						'3' => '-3',
						'4' => '-4',
						'5' => '-5',
						'6' => '-6',
						'7' => '-7',
						'8' => '-8',
						'9' => '-9',
						'10' => '-10',
						'11' => '-11',
						'12' => '-12',
						'13' => '-13',
						'14' => '-14',
						'15' => '-15',
						'16' => '-16',
						'17' => '-17',
						'18' => '-18',
						'19' => '-19',
						'20' => '-20',
						'21' => '-21',
						'22' => '-22',
						'23' => '-23',
						'24' => '-24',
						'25' => '-25',
						'26' => '-26',
						'27' => '-27',
						'28' => '-28',
						'29' => '-29',
						'30' => '-30',
						'31' => '-31',
						'32' => '-32',
						'33' => '-33',
						'34' => '-34',
						'35' => '-35',
						'36' => '-36',
						'37' => '-37',
						'38' => '-38',
						'39' => '-39',
						'40' => '1',
						'41' => '2',
						'42' => '3',
						'43' => '4',
						'44' => '5',
						'59' => '6',
						'60' => '7',
						'61' => '8',
						'62' => '9',
						'63' => '10',
						'64' => '11',
						'65' => '12',
						'45' => '13',
						'46' => '14',
						'47' => '15',
						'48' => '16',
						'49' => '17',
						'50' => '18',
						'51' => '19',
						'52' => '20',
						'53' => '21',
						'54' => '22',
						'55' => '23',
						'56' => '24',
						'57' => '25',
						'58' => '26',
						'66' => '27',
						);
		return $indexes[$bookIndex];
	}
};

class BibleonlineRu extends AllbibleInfo {

	public function getLink($translation = "") {
		return 'http://bibleonline.ru/bible/' . $this->GetTranslationPrefix($translation) . $this->GetIndexName($this->m_bookIndex);
	}

	public function GetTranslationPrefix($translation) {
		switch($translation) {
			case RSTTranslation: return 'rus/';
			case CASTranslation: return 'cas/';
			case RVTranslation: return 'rbo/';
			case UCSTranslation: return 'csl/';
			case UBIOTranslation: return 'ukr/';
			case KJVTranslation: return 'eng/';
			case BBSTranslation: return 'bel/';
			default: return 'rus/'; // Русский синодальный перевод (рус.)
		}
	}
	public function getSingleChapterPart($chapter) {
		return '/1/#' . $chapter;
	}
	
	public function getVersePart($verse) {
		return '/#' . $verse;
	}
	
		public function checkForTranslationExist($translation) {
		$LastBookOfOldTestament = 39;
		switch($translation) {
			case RSTTranslation: return true;
			case CASTranslation: return (integer)$this->m_bookIndex > $LastBookOfOldTestament ? true : false; // только НЗ
			case RVTranslation: return (integer)$this->m_bookIndex > $LastBookOfOldTestament ? true : false; // только НЗ
			case UCSTranslation: return true;
			case UBIOTranslation: return true;
			case KJVTranslation: return true;
			case BBSTranslation: return true;
		}
		return false;
	}
	
	protected function GetIndexName($bookIndex) {
		$indexes = array(
						'1' => '1',
						'2' => '2',
						'3' => '3',
						'4' => '4',
						'5' => '5',
						'6' => '6',
						'7' => '7',
						'8' => '8',
						'9' => '9',
						'10' => '10',
						'11' => '11',
						'12' => '12',
						'13' => '13',
						'14' => '14',
						'15' => '15',
						'16' => '16',
						'17' => '17',
						'18' => '18',
						'19' => '19',
						'20' => '20',
						'21' => '21',
						'22' => '22',
						'23' => '23',
						'24' => '24',
						'25' => '25',
						'26' => '26',
						'27' => '27',
						'28' => '28',
						'29' => '29',
						'30' => '30',
						'31' => '31',
						'32' => '32',
						'33' => '33',
						'34' => '34',
						'35' => '35',
						'36' => '36',
						'37' => '37',
						'38' => '38',
						'39' => '39',
						'40' => '40',
						'41' => '41',
						'42' => '42',
						'43' => '43',
						'44' => '44',
						'59' => '45',
						'60' => '46',
						'61' => '47',
						'62' => '48',
						'63' => '49',
						'64' => '50',
						'65' => '51',
						'45' => '52',
						'46' => '53',
						'47' => '54',
						'48' => '55',
						'49' => '56',
						'50' => '57',
						'51' => '58',
						'52' => '59',
						'53' => '60',
						'54' => '61',
						'55' => '62',
						'56' => '63',
						'57' => '64',
						'58' => '65',
						'66' => '66',
						);
		return $indexes[$bookIndex];
	}
};
class BibleCenterRu extends AllbibleInfo {
	
	public function getLink($translation = "") {
		return 'http://bible-center.ru/bibletext/' . $this->GetTranslationPrefix($translation) . $this->GetIndexName($this->m_bookIndex);
	}

	public function GetTranslationPrefix($translation) {
		switch($translation) {
			case RSTTranslation: return 'synnew_ru/';
			case CASTranslation: return 'kassian_ru/';
			case RVTranslation: return 'rv_ru/';
			case NTKulTranslation: return 'kulakov_ru/';
			case UCSTranslation: return 'cslavonic_rusl/';
			case KJVTranslation: return 'kjv_eng/';
			case NASBTranslation: return 'nasb_eng/';
			case NIVTranslation: return 'niv_eng/';
			case NVTranslation: return 'nv_lat/';
			case LXXTranslation: return 'sept_gr/';
			default: return 'synnew_ru/'; // Русский синодальный перевод (рус.)
		}
	}
	
	private $m_chapter;
	
	public function getSingleChapterPart($chapter) {
		return '/1#' . $this->GetIndexName($this->m_bookIndex) . '1_' . $chapter;
	}
	
	public function getChapterPart($chapter) {
		$this->m_chapter = $chapter;
		return '/' . $chapter ;
	}
	
	public function getVersePart($verse) {
		return '#' . $this->GetIndexName($this->m_bookIndex) . $this->m_chapter . '_' . $verse;
	}
	
	public function checkForTranslationExist($translation) {
		$LastBookOfOldTestament = 39;
		switch($translation) {
			case RSTTranslation: return true;
			case CASTranslation: return (integer)$this->m_bookIndex > $LastBookOfOldTestament ? true : false; // только НЗ			
			case RVTranslation: return (integer)$this->m_bookIndex > $LastBookOfOldTestament ? true : false; // только НЗ
			case NTKulTranslation: return (integer)$this->m_bookIndex > $LastBookOfOldTestament ? true : false; // только НЗ
			case UCSTranslation: return true;
			case KJVTranslation: return true;
			case NASBTranslation: return true;
			case NIVTranslation: return true;
			case NVTranslation: return true;
			case LXXTranslation: return true;
		}
		return false;
	}
};

class BibleServerCom extends AllbibleInfo {
	
	public function getLink($translation = "") {
		return 'http://bibleserver.com/text/' . $this->GetTranslationPrefix($translation) . $this->GetIndexName($this->m_bookIndex);
	}

	public function GetTranslationPrefix($translation) {
		switch($translation) {
			case RSZTranslation: return 'RSZ/';
			case CRSTranslation: return 'CRS/';
			case BLGTranslation: return 'BLG/';
			case ESVTranslation: return 'ESV/';
			case NIVTranslation: return 'NIV/';
			case TNIVTranslation: return 'TNIV/';
			case NIRVTranslation: return 'NIRV/';
			case KJVTranslation: return 'KJV/';
			case KJVSTranslation: return 'KJVS/';
			case LXXTranslation: return 'LXX/';
			case OTTranslation: return 'OT/';
			case VULTranslation: return 'VUL/';
			default: return 'RSZ/'; // Новый перевод на русский язык (рус.)
		}
	}
	
		// http://bibleserver.com/text/				RSZ/		Послание Филимону			5
		// http://bibleserver.com/text/				RSZ/		Послание Римлянам 	1.		31
	
	public function getSingleChapterPart($chapter) {
		return $chapter;
	}
	
	public function getChapterPart($chapter) {
		return $chapter;
	}
	
	public function getVersePart($verse) {
		return '.' . $verse;
	}
	
	public function checkForTranslationExist($translation) {
		$FirstBookOfNewTestament = 40;
		switch($translation) {
			case RSZTranslation: return true;
			case CRSTranslation: return true;
			case BLGTranslation: return true;
			case ESVTranslation: return true;
			case NIVTranslation: return true;
			case TNIVTranslation: return true;
			case NIRVTranslation: return true;
			case KJVTranslation: return true;
			case KJVSTranslation: return true;
			case LXXTranslation: return true;
			case OTTranslation: return (integer)$this->m_bookIndex < $FirstBookOfNewTestament ? true : false; // только ВЗ
			case VULTranslation: return true;
		}
		return false;
	}
	
	protected function GetIndexName($bookIndex) {
		$indexes = array(
						'1' => 'Бытие',
						'2' => 'Исход',
						'3' => 'Левит',
						'4' => 'Числа',
						'5' => 'Второзаконие',
						'6' => 'Книга Иисуса Навина',
						'7' => 'Книга Судей Израилевых',
						'8' => 'Книга Руфь',
						'9' => 'Первая книга Царств',
						'10' => 'Вторая книга Царств',
						'11' => 'Третья книга Царств',
						'12' => 'Четвертая книга Царств',
						'13' => 'Первая книга Паралипоменон',
						'14' => 'Вторая книга Паралипоменон',
						'15' => 'Книга Ездры',
						'16' => 'Книга Неемии',
						'17' => 'Книга Есфирь',
						'18' => 'Книга Иова',
						'19' => 'Псалтирь',
						'20' => 'Книга Притчей Соломоновых',
						'21' => 'Книга Екклесиаста',
						'22' => 'Книга Песни Песней Соломона',
						'23' => 'Книга пророка Исаии',
						'24' => 'Книга пророка Иеремии',
						'25' => 'Книга Плач Иеремии',
						'26' => 'Книга пророка Иезекииля',
						'27' => 'Книга пророка Даниила',
						'28' => 'Книга пророка Осии',
						'29' => 'Книга пророка Иоиля',
						'30' => 'Книга пророка Амоса',
						'31' => 'Книга пророка Авдия',
						'32' => 'Книга пророка Ионы',
						'33' => 'Книга пророка Михея',
						'34' => 'Книга пророка Наума',
						'35' => 'Книга пророка Аввакума',
						'36' => 'Книга пророка Софонии',
						'37' => 'Книга пророка Аггея',
						'38' => 'Книга пророка Захарии',
						'39' => 'Книга пророка Малахии',
						'40' => 'Евангелие Матфея',
						'41' => 'Евангелие Марка',
						'42' => 'Евангелие Луки',
						'43' => 'Евангелие Иоанна',
						'44' => 'Деятельность апостолов',
						'59' => 'Письмо Иакова',
						'60' => 'Первое Письмо Петра',
						'61' => 'Второе Письмо Петра',
						'62' => 'Первое Письмо Иоанна',
						'63' => 'Второе Письмо Иоанна',
						'64' => 'Третье Письмо Иоанна',
						'65' => 'Письмо Иуды',
						'45' => 'Письмо Римлянам',
						'46' => 'Первое Письмо Коринфянам',
						'47' => 'Второе Письмо Коринфянам',
						'48' => 'Письмо Галатам',
						'49' => 'Письмо Ефесянам',
						'50' => 'Письмо Филиппийцам',
						'51' => 'Письмо Колоссянам',
						'52' => 'Первое Письмо Фессалоникийцам',
						'53' => 'Второе Письмо Фессалоникийцам',
						'54' => 'Первое Письмо Тимофею',
						'55' => 'Второе Письмо Тимофею',
						'56' => 'Письмо Титу',
						'57' => 'Письмо Филимону',
						'58' => 'Письмо Евреям',
						'66' => 'Откровение Иоанна',
						);
		return $indexes[$bookIndex];
	}
};

class BibleCom extends AllbibleInfo {
	function __construct($bookIndex) {
		parent::__construct($bookIndex);
	}
	
	public function getLink($translation = "") {
		return 'http://bible.us/' . $this->GetTranslationPrefixFirst($translation) . $this->GetIndexName($this->m_bookIndex);
	}

	public function GetTranslationPrefixFirst($translation) {
		switch($translation) {
			case RSTTranslation: return '400/';
			case CASTranslation: return '480/';
			case NTKulTranslation: return '313/';
			case CRSTranslation: return '385/';
			case UCSTranslation: return '45/';
			case RSZTranslation: return '143/';
			case RSPTranslation: return '201/';
			case RUVZTranslation: return '145/';
			case BLGTranslation: return '23/';
			case UBIOTranslation: return '186/';
			case UKRKTranslation: return '188/';
			case UMTTranslation: return '204/';
			case KJVTranslation: return '1/';
			case ASVTranslation: return '12/';
			case NASBTranslation: return '100/';
			case NIVTranslation: return '111/';
			case ESVTranslation: return '59/';
			case NIRVTranslation: return '110/'; 
			default: return '400/';
		}
	}
	
		public function GetTranslationPrefixLast($translation) {
		switch($translation) {
			case RSTTranslation: return '.syno';
			case CASTranslation: return '.cass';
			case NTKulTranslation: return '.bti';
			case CRSTranslation: return '.cars';
			case UCSTranslation: return '.cslav';
			case RSZTranslation: return '.rsz';
			case RSPTranslation: return '.rsp';
			case RUVZTranslation: return '.ruvz';
			case BLGTranslation: return '.bg1940';
			case UBIOTranslation: return '.ubio';
			case UKRKTranslation: return '.ukrk';
			case UMTTranslation: return '.umt';
			case KJVTranslation: return '.kjv';
			case ASVTranslation: return '.asv';
			case NASBTranslation: return '.nasb';
			case NIVTranslation: return '.niv';
			case ESVTranslation: return '.esv';
			case NIRVTranslation: return '.nirv'; 
			default: return '.syno';
		}
	}
	
	public function getSingleChapterPart($chapter) {
		return '.1.' . $chapter;
	}
	
	public function getChapterPart($chapter) {
		return '.' . $chapter;
	}
	
	public function getVersePart($verse) {
		return '.' . $verse;// . $this->GetTranslationPrefixLast($translation);
	}
	
	public function checkForTranslationExist($translation) {
		$LastBookOfOldTestament = 39;
		switch($translation) {
			case RSTTranslation: return true;
			case CASTranslation: return (integer)$this->m_bookIndex > $LastBookOfOldTestament ? true : false; // только НЗ
			case NTKulTranslation: return (integer)$this->m_bookIndex > $LastBookOfOldTestament ? true : false; // только НЗ
			case CRSTranslation: return true;
			case UCSTranslation: return true;
			case RSZTranslation: return (integer)$this->m_bookIndex > $LastBookOfOldTestament ? true : false; // только НЗ
			case RSPTranslation: return (integer)$this->m_bookIndex > $LastBookOfOldTestament ? true : false; // только НЗ
			case RUVZTranslation: return (integer)$this->m_bookIndex > $LastBookOfOldTestament ? true : false; // только НЗ
			case BLGTranslation: return true;
			case UBIOTranslation: return true;
			case UKRKTranslation: return true;
			case UMTTranslation: return (integer)$this->m_bookIndex > $LastBookOfOldTestament ? true : false; // только НЗ
			case KJVTranslation: return true;
			case ASVTranslation: return true;
			case NASBTranslation: return true;
			case NIVTranslation: return true;
			case ESVTranslation: return true;
			case NIRVTranslation: return true;
		}
		return false;
	}
	
	protected function GetIndexName($bookIndex) {
		$indexes = array(
					'1' => 'gen',
					'2' => 'exo',
					'3' => 'lev',
					'4' => 'num',
					'5' => 'deu',
					'6' => 'jos',
					'7' => 'jdg',
					'8' => 'rut',
					'9' => '1sa',
					'10' => '2sa',
					'11' => '1ki',
					'12' => '2ki',
					'13' => '1ch',
					'14' => '2ch',
					'15' => 'ezr',
					'16' => 'neh',
					'17' => 'est',
					'18' => 'job',
					'19' => 'psa',
					'20' => 'pro',
					'21' => 'ecc',
					'22' => 'sng',
					'23' => 'isa',
					'24' => 'jer',
					'25' => 'lam',
					'26' => 'ezk',
					'27' => 'dan',
					'28' => 'hos',
					'29' => 'jol',
					'30' => 'amo',
					'31' => 'oba',
					'32' => 'jon',
					'33' => 'mic',
					'34' => 'nam',
					'35' => 'hab',
					'36' => 'zep',
					'37' => 'hag',
					'38' => 'zec',
					'39' => 'mal',
					'40' => 'mat',
					'41' => 'mrk',
					'42' => 'luk',
					'43' => 'jhn',
					'44' => 'act',
					'59' => 'jas',
					'60' => '1pe',
					'61' => '2pe',
					'62' => '1jn',
					'63' => '2jn',
					'64' => '3jn',
					'65' => 'jud',
					'45' => 'rom',
					'46' => '1co',
					'47' => '2co',
					'48' => 'gal',
					'49' => 'eph',
					'50' => 'php',
					'51' => 'col',
					'52' => '1th',
					'53' => '2th',
					'54' => '1ti',
					'55' => '2ti',
					'56' => 'tit',
					'57' => 'phm',
					'58' => 'heb',
					'66' => 'rev',
					);
		return $indexes[$bookIndex];
	}
};
?>