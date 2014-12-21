<?php
abstract class BibleSource {
	protected $m_bookIndex ="";
	
	function __construct($bookIndex) {
		$this->m_bookIndex = $bookIndex;
		
		$this->languageIn = $_ENV["languageIn"];
	}
	
	public static function get($bookIndex) {
		
		if (null != $_ENV["g_BibleSource"]) {
			switch($_ENV["g_BibleSource"]) {
				case 'AllbibleInfoSource': 		return new AllbibleInfo($bookIndex);	break;
				case 'BibleComUaSource': 		return new BibleComUa($bookIndex);		break;
				case 'BiblezoomRuSource':		return new BiblezoomRu($bookIndex);		break;
				case 'BibleonlineRuSource': 	return new BibleonlineRu($bookIndex);	break;
				case 'BibleCenterRuSource': 	return new BibleCenterRu($bookIndex);	break;
				case 'BibleserverComSource': 	return new BibleserverCom($bookIndex);	break;
				case 'BibleComSource': 			return new BibleCom($bookIndex);		break;
				case 'BibleDesktopComSource': 	return new BibleDesktopCom($bookIndex);	break;
				case 'BiblegatewayComSource': 	return new BiblegatewayCom($bookIndex);	break;
				default: 						return new AllbibleInfo($bookIndex);	break;
			}
		}
		return new AllbibleInfo($bookIndex);
	}
	
	abstract public function getLink($translation = "");
	abstract public function getSingleChapterPart($chapter);
	abstract public function getChapterPart($chapter);
	abstract public function getVersePart($verse);
	abstract public function checkForTranslationExist($translation);
}

class AllbibleInfo extends BibleSource {
	function __construct($bookIndex) {
		parent::__construct($bookIndex);
	}
	
	public function getLink($translation = "") {
		return 'http://allbible.info/bible/' . $this->GetTranslationPrefix($translation) . $this->GetIndexName($this->m_bookIndex);
	}

	public function GetTranslationPrefix($translation) {
		switch($translation) {
			case RSTTranslation: 	return 'sinodal/';		break;
			case MDRTranslation: 	return 'modern/';		break;
			case RVTranslation: 	return 'modernrbo/';	break;
			case UBIOTranslation: 	return 'ogienko/';		break;
			case KJVTranslation: 	return 'kingjames/';	break;
			case ASVTranslation: 	return 'standart/';		break;
			default: 
				switch($this->languageIn) {
					case 'ru': return 'sinodal/';	break;
					case 'ua': return 'ogienko/';	break;
					case 'en': return 'kingjames/';	break;
				}
		}
	}
	
	public function getSingleChapterPart($chapter) {
		return '/1#' . $chapter;
	}
	
	public function getChapterPart($chapter) {
		return '/' . $chapter;
	}
	
	public function getVersePart($verse, $translation = "") {
		return '#' . $verse;
	}
	
	public function checkForTranslationExist($translation) {
		$LastBookOfOldTestament = 39;
		switch($translation) {
			case RSTTranslation: 	return true;	break;
			case MDRTranslation: 	return true;	break;
			case RVTranslation: 	return (integer)$this->m_bookIndex > $LastBookOfOldTestament ? true : false; break; // только НЗ
			case UBIOTranslation: 	return true;	break;
			case KJVTranslation: 	return true;	break;
			case ASVTranslation: 	return true;	break;
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
}

class BibleComUa extends AllbibleInfo {
	public function getLink($translation = "") {
		return 'http://bible.com.ua/bible/r/' . $this->m_bookIndex;
	}
}

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
	
	public function getVersePart($verse, $translation = "") {
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
}

class BibleonlineRu extends AllbibleInfo {

	public function getLink($translation = "") {
		return 'http://bibleonline.ru/bible/' . $this->GetTranslationPrefix($translation) . $this->GetIndexName($this->m_bookIndex);
	}

	public function GetTranslationPrefix($translation) {
		switch($translation) {
			case RSTTranslation: 	return 'rus/';	break;
			case CASTranslation: 	return 'cas/';	break;
			case RVTranslation: 	return 'rbo/';	break;
			case UCSTranslation: 	return 'csl/';	break;
			case UBIOTranslation: 	return 'ukr/';	break;
			case KJVTranslation: 	return 'eng/';	break;
			case BBSTranslation: 	return 'bel/';	break;
			default:
				switch($this->languageIn) {
					case 'ru': return 'rus/';	break;
					case 'ua': return 'ukr/';	break;
					case 'en': return 'eng/';	break;
				}
		}
	}
	public function getSingleChapterPart($chapter) {
		return '/1/#' . $chapter;
	}
	
	public function getVersePart($verse, $translation = "") {
		return '/#' . $verse;
	}
	
		public function checkForTranslationExist($translation) {
		$LastBookOfOldTestament = 39;
		switch($translation) {
			case RSTTranslation: 	return true;	break;
			case CASTranslation: 	return (integer)$this->m_bookIndex > $LastBookOfOldTestament ? true : false; break; // только НЗ
			case RVTranslation: 	return (integer)$this->m_bookIndex > $LastBookOfOldTestament ? true : false; break; // только НЗ
			case UCSTranslation: 	return true;	break;
			case UBIOTranslation: 	return true;	break;
			case KJVTranslation: 	return true;	break;
			case BBSTranslation: 	return true;	break;
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
			case RSTTranslation: 	return 'synnew_ru/';		break;
			case CASTranslation: 	return 'kassian_ru/';		break;
			case RVTranslation: 	return 'rv_ru/';			break;
			case NTKulTranslation: 	return 'kulakov_ru/';		break;
			case UCSTranslation: 	return 'cslavonic_rusl/';	break;
			case KJVTranslation: 	return 'kjv_eng/';			break;
			case NASBTranslation: 	return 'nasb_eng/';			break;
			case NIVTranslation: 	return 'niv_eng/';			break;
			case NVTranslation: 	return 'nv_lat/';			break;
			case LXXTranslation: 	return 'sept_gr/';			break;
			default:
				switch($this->languageIn) {
					case 'ru': return 'synnew_ru/';	break;
					case 'ua': return 'synnew_ru/'; break; // Украинский отсутствует
					case 'en': return 'kjv_eng/';	break;
				}
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
	
	public function getVersePart($verse, $translation = "") {
		return '#' . $this->GetIndexName($this->m_bookIndex) . $this->m_chapter . '_' . $verse;
	}
	
	public function checkForTranslationExist($translation) {
		$LastBookOfOldTestament = 39;
		switch($translation) {
			case RSTTranslation: 	return true;	break;
			case CASTranslation: 	return (integer)$this->m_bookIndex > $LastBookOfOldTestament ? true : false; break; // только НЗ			
			case RVTranslation: 	return (integer)$this->m_bookIndex > $LastBookOfOldTestament ? true : false; break; // только НЗ
			case NTKulTranslation: 	return (integer)$this->m_bookIndex > $LastBookOfOldTestament ? true : false; break; // только НЗ
			case UCSTranslation: 	return true;	break;
			case KJVTranslation: 	return true;	break;
			case NASBTranslation: 	return true;	break;
			case NIVTranslation: 	return true;	break;
			case NVTranslation: 	return true;	break;
			case LXXTranslation: 	return true;	break;
		}
		return false;
	}
}

class BibleServerCom extends AllbibleInfo {
	
	public function getLink($translation = "") {
		return 'http://bibleserver.com/text/' . $this->GetTranslationPrefix($translation) . $this->GetIndexName($this->m_bookIndex);
	}

	public function GetTranslationPrefix($translation) {
		switch($translation) {
			case RSZTranslation: 	return 'RSZ/';	break;
			case CRSTranslation: 	return 'CRS/';	break;
			case BLGTranslation: 	return 'BLG/';	break;
			case ESVTranslation: 	return 'ESV/';	break;
			case NIVTranslation: 	return 'NIV/';	break;
			case TNIVTranslation: 	return 'TNIV/';	break;
			case NIRVTranslation: 	return 'NIRV/';	break;
			case KJVTranslation: 	return 'KJV/';	break;
			case KJVSTranslation: 	return 'KJVS/';	break;
			case LXXTranslation: 	return 'LXX/';	break;
			case OTTranslation: 	return 'OT/';	break;
			case VULTranslation: 	return 'VUL/';	break;
			default:
				switch($this->languageIn) {
					case 'ru': return 'RSZ/';	break; // Новый перевод на русский язык (рус.)
					case 'ua': return 'RSZ/'; 	break; // Украинский отсутствует
					case 'en': return 'KJV/';	break;
				}
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
	
	public function getVersePart($verse, $translation = "") {
		return '.' . $verse;
	}
	
	public function checkForTranslationExist($translation) {
		$FirstBookOfNewTestament = 40;
		switch($translation) {
			case RSZTranslation: 	return true;	break;
			case CRSTranslation: 	return true;	break;
			case BLGTranslation: 	return true;	break;
			case ESVTranslation: 	return true;	break;
			case NIVTranslation: 	return true;	break;
			case TNIVTranslation: 	return true;	break;
			case NIRVTranslation: 	return true;	break;
			case KJVTranslation: 	return true;	break;
			case KJVSTranslation: 	return true;	break;
			case LXXTranslation: 	return true;	break;
			case OTTranslation: 	return (integer)$this->m_bookIndex < $FirstBookOfNewTestament ? true : false; break; // только ВЗ
			case VULTranslation: 	return true;	break;
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
}

class BibleCom extends AllbibleInfo {
	function __construct($bookIndex) {
		parent::__construct($bookIndex);
	}
	
	public function getLink($translation = "") {
		return 'http://bible.us/' . $this->GetTranslationPrefixFirst($translation) . $this->GetIndexName($this->m_bookIndex);
	}

	public function GetTranslationPrefixFirst($translation) {
		switch($translation) {
			case RSTTranslation: 	return '400/';	break;
			case CASTranslation: 	return '480/';	break;
			case NTKulTranslation: 	return '313/';	break;
			case CRSTranslation: 	return '385/';	break;
			case UCSTranslation: 	return '45/';	break;
			case RSZTranslation: 	return '143/';	break;
			case RSPTranslation: 	return '201/';	break;
			case RUVZTranslation: 	return '145/';	break;
			case BLGTranslation: 	return '23/';	break;
			case UBIOTranslation: 	return '186/';	break;
			case UKRKTranslation: 	return '188/';	break;
			case UMTTranslation: 	return '204/';	break;
			case KJVTranslation: 	return '1/';	break;
			case ASVTranslation: 	return '12/';	break;
			case NASBTranslation: 	return '100/';	break;
			case NIVTranslation: 	return '111/';	break;
			case ESVTranslation: 	return '59/';	break;
			case NIRVTranslation: 	return '110/';	break;
			default: 
				switch($this->languageIn) {
					case 'ru': return '400/';	break;
					case 'ua': return '186/';	break;
					case 'en': return '1/';		break;
				}
		}
	}
	
		public function GetTranslationPrefixLast($translation) {
		switch($translation) {
			case RSTTranslation: 	return '.syno';		break;
			case CASTranslation: 	return '.cass';		break;
			case NTKulTranslation: 	return '.bti';		break;
			case CRSTranslation: 	return '.cars';		break;
			case UCSTranslation: 	return '.cslav';	break;
			case RSZTranslation: 	return '.rsz';		break;
			case RSPTranslation: 	return '.rsp';		break;
			case RUVZTranslation: 	return '.ruvz';		break;
			case BLGTranslation: 	return '.bg1940';	break;
			case UBIOTranslation: 	return '.ubio';		break;
			case UKRKTranslation: 	return '.ukrk';		break;
			case UMTTranslation: 	return '.umt';		break;
			case KJVTranslation: 	return '.kjv';		break;
			case ASVTranslation: 	return '.asv';		break;
			case NASBTranslation: 	return '.nasb';		break;
			case NIVTranslation: 	return '.niv';		break;
			case ESVTranslation: 	return '.esv';		break;
			case NIRVTranslation: 	return '.nirv';		break;
			default:
				switch($this->languageIn) {
					case 'ru': return '.syno';	break;
					case 'ua': return '.ubio';	break;
					case 'en': return '.kjv';	break;
				}
		}
	}
	
	public function getSingleChapterPart($chapter) {
		return '.1.' . $chapter;
	}
	
	public function getChapterPart($chapter) {
		return '.' . $chapter;
	}
	
	public function getVersePart($verse, $translation = "") {
		return '.' . $verse;// . $this->GetTranslationPrefixLast($translation);
	}
	
	public function checkForTranslationExist($translation) {
		$LastBookOfOldTestament = 39;
		switch($translation) {
			case RSTTranslation: 	return true;	break;
			case CASTranslation: 	return (integer)$this->m_bookIndex > $LastBookOfOldTestament ? true : false; break; // только НЗ
			case NTKulTranslation: 	return (integer)$this->m_bookIndex > $LastBookOfOldTestament ? true : false; break; // только НЗ
			case CRSTranslation: 	return true;	break;
			case UCSTranslation: 	return true;	break;
			case RSZTranslation: 	return (integer)$this->m_bookIndex > $LastBookOfOldTestament ? true : false; break; // только НЗ
			case RSPTranslation: 	return (integer)$this->m_bookIndex > $LastBookOfOldTestament ? true : false; break; // только НЗ
			case RUVZTranslation: 	return (integer)$this->m_bookIndex > $LastBookOfOldTestament ? true : false; break; // только НЗ
			case BLGTranslation: 	return true;	break;
			case UBIOTranslation: 	return true;	break;
			case UKRKTranslation: 	return true;	break;
			case UMTTranslation: 	return (integer)$this->m_bookIndex > $LastBookOfOldTestament ? true : false; break; // только НЗ
			case KJVTranslation: 	return true;	break;
			case ASVTranslation: 	return true;	break;
			case NASBTranslation: 	return true;	break;
			case NIVTranslation: 	return true;	break;
			case ESVTranslation: 	return true;	break;
			case NIRVTranslation: 	return true;	break;
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
}

class BibleDesktopCom extends AllbibleInfo {
	function __construct($bookIndex) {
		parent::__construct($bookIndex);
	}
	
	public function getLink($translation = "") {
		return 'http://bible-desktop.com/bq/' . $this->GetIndexName($this->m_bookIndex);
	}

	public function GetTranslationPrefixLast($translation) {
		switch($translation) {
			case RSTTranslation: 	return '/RST';	break;
			case MDRTranslation: 	return '/MDR';	break; // НЗ
			case CASTranslation: 	return '/CAS';	break; // НЗ
			case ISBTranslation: 	return '/New Russian Translation';	break;
			case UCSTranslation: 	return '/SLR';	break;
			case UBIOTranslation: 	return '/UKR';	break;
			case UKRKTranslation: 	return '/Bible_UA_Kulish';	break;
			case UKHTranslation: 	return '/UKH';	break;
			case UBTTranslation: 	return '/UBT';	break;
			case WBTCTranslation: 	return '/UK_WBTC';	break; // НЗ
			case BBSTranslation: 	return '/BBS';	break;
			case KJVTranslation: 	return '/KJV';	break;
			case VULTranslation: 	return '/VL_78';	break;
			default:
				switch($this->languageIn) {
					case 'ru': return '/RST';	break;
					case 'ua': return '/UKR';	break;
					case 'en': return '/KJV';	break;
				}
		}
	}
	
	public function getSingleChapterPart($chapter) {
		return '/1_' . $chapter;// . $this->GetTranslationPrefixLast($translation);
	}
	
	public function getChapterPart($chapter) {
		return '/' . $chapter;
	}
	
	public function getVersePart($verse) {
		return '_' . $verse;// . $this->GetTranslationPrefixLast($translation);
	}
	
	public function checkForTranslationExist($translation) {
		$LastBookOfOldTestament = 39;
		switch($translation) {
			case RSTTranslation: 	return true;	break;
			case MDRTranslation: 	return true;	break;
			case CASTranslation: 	return (integer)$this->m_bookIndex > $LastBookOfOldTestament ? true : false; break; // только НЗ
			case ISBTranslation: 	return true;	break;
			case UCSTranslation: 	return true;	break;
			case UBIOTranslation: 	return true;	break;
			case UKRKTranslation: 	return true;	break;
			case UKHTranslation: 	return true;	break;
			case UBTTranslation: 	return true;	break;
			case WBTCTranslation: 	return (integer)$this->m_bookIndex > $LastBookOfOldTestament ? true : false; break; // только НЗ
			case BBSTranslation: 	return true;	break;
			case KJVTranslation: 	return true;	break;
			case VULTranslation: 	return true;	break;
		}
		return false;
	}
	
	protected function GetIndexName($bookIndex) {
		$indexes = array(
					'1' => 'genesis',
					'2' => 'exodus',
					'3' => 'leviticus',
					'4' => 'numbers',
					'5' => 'deuteronomy',
					'6' => 'joshua',
					'7' => 'judges',
					'8' => 'ruth',
					'9' => '1samuel',
					'10' => '2samuel',
					'11' => '1kings',
					'12' => '2kings',
					'13' => '1chron',
					'14' => '2chron',
					'15' => 'ezra',
					'16' => 'nehemiah',
					'17' => 'esther',
					'18' => 'job',
					'19' => 'psalms',
					'20' => 'proverbs',
					'21' => 'ecclesia',
					'22' => 'songs',
					'23' => 'isaiah',
					'24' => 'jeremiah',
					'25' => 'lamentations',
					'26' => 'ezekiel',
					'27' => 'daniel',
					'28' => 'hosea',
					'29' => 'joel',
					'30' => 'amos',
					'31' => 'obadiah',
					'32' => 'jonah',
					'33' => 'micah',
					'34' => 'nahum',
					'35' => 'habakkuk',
					'36' => 'zephaniah',
					'37' => 'haggai',
					'38' => 'zechariah',
					'39' => 'malachi',
					'40' => 'matthew',
					'41' => 'mark',
					'42' => 'luke',
					'43' => 'john',
					'44' => 'acts',
					'59' => 'james',
					'60' => '1peter',
					'61' => '2peter',
					'62' => '1john',
					'63' => '2john',
					'64' => '3john',
					'65' => 'jude',
					'45' => 'romans',
					'46' => '1corinthians',
					'47' => '2corinthians',
					'48' => 'galatians',
					'49' => 'ephesians',
					'50' => 'philippians',
					'51' => 'colossians',
					'52' => '1thessalonians',
					'53' => '2thessalonians',
					'54' => '1timothy',
					'55' => '2timothy',
					'56' => 'titus',
					'57' => 'philemon',
					'58' => 'hebrews',
					'66' => 'revelations',
					);
		return $indexes[$bookIndex];
	}
}

class BiblegatewayCom extends AllbibleInfo {
	function __construct($bookIndex) {
		parent::__construct($bookIndex);
	}
	
	public function getLink($translation = "") {
		return 'https://www.biblegateway.com/passage/?version=' . $this->GetTranslationPrefix($translation) . $this->GetIndexName($this->m_bookIndex);
	}

	public function GetTranslationPrefix($translation) {
		switch($translation) {
			case RSTTranslation: 	return 'RUSV&search=';		break;
			case RSZTranslation: 	return 'SZ&search=';		break;
			case CRSTranslation: 	return 'CARS&search=';		break;
			case UBIOTranslation: 	return 'UKR&search=';		break;
			case BLGTranslation: 	return 'BG1940&search=';	break;
			case KJVTranslation: 	return 'KJV&search=';		break;
			case ASVTranslation: 	return 'ASV&search=';		break;
			case NASBTranslation: 	return 'NASB&search=';		break;
			case NIVTranslation: 	return 'NIV&search=';		break;
			case ESVTranslation: 	return 'ESV&search=';		break;
			case NIRVTranslation: 	return 'NIRV&search=';		break;
			case VULTranslation: 	return 'VULGATE&search=';	break;
			default:
				switch($this->languageIn) {
					case 'ru': return 'RUSV&search=';	break;
					case 'ua': return 'UKR&search=';	break;
					case 'en': return 'KJV&search=';	break;
				}
		}
	}
	public function getSingleChapterPart($chapter) {
		return '+1%3A' . $chapter;
	}
	
	public function getChapterPart($chapter) {
		return '+' . $chapter;
	}
	
	public function getVersePart($verse, $translation = "") {
		return '%3A' . $verse . '-';
	}
	
		public function checkForTranslationExist($translation) {
		$LastBookOfOldTestament = 39;
		switch($translation) {
			case RSTTranslation: 	return true;	break;
			case RSZTranslation: 	return true;	break;
			case CRSTranslation: 	return true;	break;
			case UBIOTranslation: 	return true;	break;
			case BLGTranslation: 	return true;	break;
			case KJVTranslation: 	return true;	break;
			case ASVTranslation: 	return true;	break;
			case NASBTranslation: 	return true;	break;
			case NIVTranslation: 	return true;	break;
			case ESVTranslation: 	return true;	break;
			case NIRVTranslation: 	return true;	break;
			case VULTranslation: 	return true;	break;
		}
		return false;
	}
	
	protected function GetIndexName($bookIndex) {
		$indexes = array(
					'1' => 'Genesis',
					'2' => 'Exodus',
					'3' => 'Leviticus',
					'4' => 'Numbers',
					'5' => 'Deuteronomy',
					'6' => 'Joshua',
					'7' => 'Judges',
					'8' => 'Ruth',
					'9' => '1+Samuel',
					'10' => '2+Samuel',
					'11' => '1+Kings',
					'12' => '2+Kings',
					'13' => '1+Chronicles',
					'14' => '2+Chronicles',
					'15' => 'Ezra',
					'16' => 'Nehemiah',
					'17' => 'Esther',
					'18' => 'Job',
					'19' => 'Psalm',
					'20' => 'Proverbs',
					'21' => 'Ecclesiastes',
					'22' => 'Song+of+Songs',
					'23' => 'Isaiah',
					'24' => 'Jeremiah',
					'25' => 'Lamentations',
					'26' => 'Ezekiel',
					'27' => 'Daniel',
					'28' => 'Hosea',
					'29' => 'Joel',
					'30' => 'Amos',
					'31' => 'Obadiah',
					'32' => 'Jonah',
					'33' => 'Micah',
					'34' => 'Nahum',
					'35' => 'Habakkuk',
					'36' => 'Zephaniah',
					'37' => 'Haggai',
					'38' => 'Zechariah',
					'39' => 'Malachi',
					'40' => 'Matthew',
					'41' => 'Mark',
					'42' => 'Luke',
					'43' => 'John',
					'44' => 'Acts',
					'59' => 'James',
					'60' => '1+Peter',
					'61' => '2+Peter',
					'62' => '1+John',
					'63' => '2+John',
					'64' => '3+John',
					'65' => 'Jude',
					'45' => 'Romans',
					'46' => '1+Corinthians',
					'47' => '2+Corinthians',
					'48' => 'Galatians',
					'49' => 'Ephesians',
					'50' => 'Philippians',
					'51' => 'Colossians',
					'52' => '1+Thessalonians',
					'53' => '2+Thessalonians',
					'54' => '1+Timothy',
					'55' => '2+Timothy',
					'56' => 'Titus',
					'57' => 'Philemon',
					'58' => 'Hebrews',
					'66' => 'Revelation',
					);
		return $indexes[$bookIndex];
	}
}
?>
