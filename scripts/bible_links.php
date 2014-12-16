<?php
class BibleBooks {
	private $sortedBooks = Array();
	private static $singltoneInstance;
	private function __construct($type = SingleNode) {
		foreach($this->GetBibleBooks('all') as $book=>$index) {
			$bookkey = self::getBookKey($book);
			if (array_key_exists($bookkey, $this->sortedBooks)) {
				$this->sortedBooks[$bookkey][$book] = $index;
			} else {
				$this->sortedBooks[$bookkey] = Array($book=>$index);
			}
		}
	}

	public static function getBookKey(&$book, $pos = -1) {
		if ($pos == -1)
			$pos = mb_strlen($book);
		return mb_substr($book, $pos-2, 2); // два символа 
	}

	public static function get() {
		if (null === self::$singltoneInstance)
			self::$singltoneInstance = new BibleBooks();
			return self::$singltoneInstance;
	}

	// Проверка на книгу из одной главы
	public function IsSingleChapterBook($index) {
		$SingleChapterBooks = array('31'=>'31', '63'=>'63', '64'=>'64', '65'=>'65', '57'=>'57');
		return array_key_exists($index, $SingleChapterBooks);
	}

	// Определение номера книги по названию
	public function GetBibleBooks($type, $lastLetter = null) {

		$bBooks = new BibleLinksArraysIn;
		
		if ($lastLetter) {
			if (array_key_exists($lastLetter, $this->sortedBooks))
				return $this->sortedBooks[$lastLetter];	
			else
				return Array();
		}

		switch ($type) {
			case 'full':
				$BookByNameOut = $bBooks->BookByNameFull;
				break;
			case 'short':
				if ($_ENV["isRoman"])
					$BookByNameOut = $bBooks->BookByNameShort + $bBooks->BookByNameShortPoint + $bBooks->BookByNameRoman + $bBooks->BookByNameRomanPoint;
				else
					$BookByNameOut = $bBooks->BookByNameShort + $bBooks->BookByNameShortPoint;
				break;
			case 'shortpoint': // точка после кратного написания
				if ($_ENV["isRoman"]) 
					$BookByNameOut = $bBooks->BookByNameShortPoint + $bBooks->BookByNameRomanPoint;
				else
					$BookByNameOut = $bBooks->BookByNameShortPoint;
				break;			
			case 'all':
				if ($_ENV["isRoman"])
					$BookByNameOut = $bBooks->BookByNameShort + $bBooks->BookByNameShortPoint + $bBooks->BookByNameRoman + $bBooks->BookByNameRomanPoint + $bBooks->BookByNameFull;
				else
					$BookByNameOut = $bBooks->BookByNameShort + $bBooks->BookByNameShortPoint + $bBooks->BookByNameFull;
				break;
			default:
					$BookByNameOut = $bBooks->BookByNameShort + $bBooks->BookByNameShortPoint + $bBooks->BookByNameFull;
				break;
		}
			
		return $BookByNameOut;
	}

	// Исправление названий книг по стандарту (отдельно полных и кратких)
	public function RightBibleBooks($name) {
		
		$bBooks = new BibleLinksArraysOut;
						
		$booksFull = $this->GetBibleBooks($type = 'full');
		$booksShort = $this->GetBibleBooks($type = 'short');
		
		if (array_key_exists($name, $booksFull) && $bookIndex = $booksFull[$name]) {
			$name = $bBooks->BookByNameFullRight[$bookIndex];
		} elseif (array_key_exists($name, $booksShort) && $bookIndex = $booksShort[$name]) {
			$name = $bBooks->BookByNameShortRight[$bookIndex];
		}
		
		$name = str_replace(" ", $_ENV["spaceType"], $name);
	
		return $name;
	}

	public function RightChaptersNumber($name) {
		
		$ChaptersNumberInBibleBook = array(	// Кол-во глав в книгах Библии
						'1' => '50',
						'2' => '40',
						'3' => '27',
						'4' => '36',
						'5' => '34',
						'6' => '24',
						'7' => '21',
						'8' => '4',
						'9' => '31',
						'10' => '24',
						'11' => '22',
						'12' => '25',
						'13' => '29',
						'14' => '36',
						'15' => '10',
						'16' => '13',
						'17' => '10',
						'18' => '42',
						'19' => '150',
						'20' => '31',
						'21' => '12',
						'22' => '8',
						'23' => '66',
						'24' => '52',
						'25' => '5',
						'26' => '48',
						'27' => '12',
						'28' => '14',
						'29' => '3',
						'30' => '9',
						'31' => '1',
						'32' => '4',
						'33' => '7',
						'34' => '3',
						'35' => '3',
						'36' => '3',
						'37' => '2',
						'38' => '14',
						'39' => '4',
						'40' => '28',
						'41' => '16',
						'42' => '24',
						'43' => '21',
						'44' => '28',
						'59' => '5',
						'60' => '5',
						'61' => '3',
						'62' => '5',
						'63' => '1',
						'64' => '1',
						'65' => '1',
						'45' => '16',
						'46' => '16',
						'47' => '13',
						'48' => '6',
						'49' => '6',
						'50' => '4',
						'51' => '4',
						'52' => '5',
						'53' => '3',
						'54' => '6',
						'55' => '4',
						'56' => '3',
						'57' => '1',
						'58' => '13',
						'66' => '22',
						);

		$books = $this->GetBibleBooks($type = 'all');
		
		if (array_key_exists($name, $books) && $bookIndex = $books[$name]) 
			$number = $ChaptersNumberInBibleBook[$bookIndex];
			
		return $number;
	}
		
	public function GetSpecialTranslationArray() {
		// Синтаксис => правильное название
		return array(
					"RV"			=> RVTranslation,
					",RV"			=> RVTranslation,
					"(RV)"			=> RVTranslation,
					"RV-С"			=> RVTranslation,
					",RV-С"			=> RVTranslation,
					"(RV-С)"		=> RVTranslation,
					"RBO2011"		=> RVTranslation,
					",RBO2011"		=> RVTranslation,
					"(RBO2011)"		=> RVTranslation,
					"RBO"			=> RVTranslation,
					",RBO"			=> RVTranslation,
					"(RBO)"			=> RVTranslation,
					"РБО2011"		=> RVTranslation,
					",РБО2011"		=> RVTranslation,
					"(РБО2011)"		=> RVTranslation,
					"РБО"			=> RVTranslation,
					",РБО"			=> RVTranslation,
					"(РБО)"			=> RVTranslation,
					"MDR"			=> MDRTranslation,
					",MDR"			=> MDRTranslation,
					"(MDR)"			=> MDRTranslation,
					"UBIO"			=> UBIOTranslation,
					",UBIO"			=> UBIOTranslation,
					"(UBIO)"		=> UBIOTranslation,	
					"KJVS"			=> KJVSTranslation,
					",KJVS"			=> KJVSTranslation,
					"(KJVS)"		=> KJVSTranslation,
					"KJV"			=> KJVTranslation,
					",KJV"			=> KJVTranslation,
					"(KJV)"			=> KJVTranslation,
					"KJ"			=> KJVTranslation,
					",KJ"			=> KJVTranslation,
					"(KJ)"			=> KJVTranslation,
					"ASV"			=> ASVTranslation,
					",ASV"			=> ASVTranslation,
					"(ASV)"			=> ASVTranslation,
					"CAS"			=> CASTranslation,
					",CAS"			=> CASTranslation,
					"(CAS)"			=> CASTranslation,
					"KAS"			=> CASTranslation,
					",KAS"			=> CASTranslation,
					"(KAS)"			=> CASTranslation,
					"UCS"			=> UCSTranslation,
					",UCS"			=> UCSTranslation,
					"(UCS)"			=> UCSTranslation,
					"BBS"			=> BBSTranslation,
					",BBS"			=> BBSTranslation,
					"(BBS)"			=> BBSTranslation,
					"NTKul"			=> NTKulTranslation,
					",NTKul"		=> NTKulTranslation,
					"(NTKul)"		=> NTKulTranslation,
					"NASB"			=> NASBTranslation,
					",NASB"			=> NASBTranslation,
					"(NASB)"		=> NASBTranslation,
					"NIV"			=> NIVTranslation,
					",NIV"			=> NIVTranslation,
					"(NIV)"			=> NIVTranslation,
					"NV"			=> NVTranslation,
					",NV"			=> NVTranslation,
					"(NV)"			=> NVTranslation,
					"LXX"			=> LXXTranslation,
					",LXX"			=> LXXTranslation,
					"(LXX)"			=> LXXTranslation,
					"RSZ"			=> RSZTranslation,
					",RSZ"			=> RSZTranslation,
					"(RSZ)"			=> RSZTranslation,
					"NT-SL"			=> RSZTranslation,
					",NT-SL"		=> RSZTranslation,
					"(NT-SL)"		=> RSZTranslation,
					"ESV"			=> ESVTranslation,
					",ESV"			=> ESVTranslation,
					"(ESV)"			=> ESVTranslation,
					"CRS"			=> CRSTranslation,
					",CRS"			=> CRSTranslation,
					"(CRS)"			=> CRSTranslation,
					"BLG"			=> BLGTranslation,
					",BLG"			=> BLGTranslation,
					"(BLG)"			=> BLGTranslation,
					"TNIV"			=> TNIVTranslation,
					",TNIV"			=> TNIVTranslation,
					"(TNIV)"		=> TNIVTranslation,
					"NIRV"			=> NIRVTranslation,
					",NIRV"			=> NIRVTranslation,
					"(NIRV)"		=> NIRVTranslation,
					"OT"			=> OTTranslation,
					",OT"			=> OTTranslation,
					"(OT)"			=> OTTranslation,
					"VUL"			=> VULTranslation,
					",VUL"			=> VULTranslation,
					"(VUL)"			=> VULTranslation,
					"VL"			=> VULTranslation,
					",VL"			=> VULTranslation,
					"(VL)"			=> VULTranslation,
					"RST"			=> RSTTranslation,
					",RST"			=> RSTTranslation,
					"(RST)"			=> RSTTranslation,
					"RUS"			=> RSTTranslation,
					",RUS"			=> RSTTranslation,
					"(RUS)"			=> RSTTranslation,
					"RSP"			=> RSPTranslation,
					",RSP"			=> RSPTranslation,
					"(RSP)"			=> RSPTranslation,
					"RUVZ"			=> RUVZTranslation,
					",RUVZ"			=> RUVZTranslation,
					"(RUVZ)"		=> RUVZTranslation,
					"UKRK"			=> UKRKTranslation,
					",UKRK"			=> UKRKTranslation,
					"(UKRK)"		=> UKRKTranslation,
					"UMT"			=> UMTTranslation,
					",UMT"			=> UMTTranslation,
					"(UMT)"			=> UMTTranslation,
					"ISB"			=> ISBTranslation,
					",ISB"			=> ISBTranslation,
					"(ISB)"			=> ISBTranslation,
					"UKH"			=> UKHTranslation,
					",UKH"			=> UKHTranslation,
					"(UKH)"			=> UKHTranslation,
					"UBT"			=> UBTTranslation,
					",UBT"			=> UBTTranslation,
					"(UBT)"			=> UBTTranslation,
					"WBTC"			=> WBTCTranslation,
					",WBTC"			=> WBTCTranslation,
					"(WBTC)"		=> WBTCTranslation,
					);
	}
	
	public function CheckForTranslationExist($bookIndex, $translation) {
		return BibleSource::get($bookIndex)->checkForTranslationExist($translation);
	}
}
?>
