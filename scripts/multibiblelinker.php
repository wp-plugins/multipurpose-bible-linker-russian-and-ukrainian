<?php
include 'bible_links.php'; 			// массивы библейских ссылок
include 'bible_sources.php'; 		// сайты библейский текстов и переводов

// Например в Мф. 1:2–4,6; 7:8

define("NamedNode", 4);		// самая первая цифра после названия книги (1)
define("SubNode", 	3);		// цифра после двоеточия (2, 8)
define("EndNode", 	1); 	// цифра после тире (4)
define("SingleNode", 0); 	// цифра после запятой (6)
define("RootNode", 	2); 	// цифра после точки с запятой (7)

class CNode {
	
    function __construct($type = SingleNode) {
		$this->SetType($type); 
		$this->SetNumber(0); 
		$this->SetAdditionalSymbol(false);
	}

    public function SetType($type) { $this->m_nodeType = $type; }
    public function GetType() { return $this->m_nodeType; }

    public function SetNumber($n) { $this->m_num = $n; }
    public function GetNumber() { return $this->m_num; }

	public function SetAdditionalSymbol($symbol) { $this->m_additionalSymbol = $symbol; }
    public function GetAdditionalSymbol() { return $this->m_additionalSymbol; }
	
	private $m_nodeType;
    private $m_num;
	private $m_additionalSymbol;
}

class CNodeWrapper {
    private $m_str;
    private $m_name;
	private $m_modifiedName;
	private $m_bookIndex;
	public  $m_pos;
    
    private $bibleBooks;

    public function __construct($name, $str) {
        $this->m_name = $name;
		$this->m_modifiedName = $name;
		$this->defineBookIndex($this->m_modifiedName);
        $this->m_str = $str;
		$this->m_pos = 0;
		$this->bibleBooks = BibleBooks::get();
		$this->SetSpecialTranslation(false);
    }
	
	private $m_specialTranslation;
	private function SetSpecialTranslation($specialTranslation) { $this->m_specialTranslation = $specialTranslation; }
    private function GetSpecialTranslation() { return $this->m_specialTranslation; }
	
    public function Parse(&$isFind) {
		$isFind = false;
	    $pos = 0;
        $node = new CNode();
		$pos = $this->TrimStr($pos, ".");
		$pos = $this->TrimStr($pos, ",");
		$pos = $this->TrimStr($pos);
		$pos = $this->TrimStr($pos, "chapter");
		$pos = $this->TrimStr($pos, ".");
        $pos = $this->TrimStr($pos);
		
		$node->SetType(NamedNode);
        if ($this->CheckForAdditionalPart($pos, $additionalSymbol))
            $node->SetAdditionalSymbol($additionalSymbol);
		if (!$this->FillNode($node, $pos))
			return $this->m_name + " " + $this->m_str;
        $pos += strlen($node->GetAdditionalSymbol());
        if ($this->CheckForSpecialTranslation($pos, $specialTranslation, $specialTranslationText))
            $this->SetSpecialTranslation($specialTranslation);
        $curList = array();
        $curList[] = $node;
 
        $Nodes =  array();
        $Nodes[] = $curList;
        
        $res;
        do {
            $node1= new CNode();
            $res = $this->Parse_($node1, $pos);
			
			if ($res) {
                if ($node1->GetType() == SubNode) {
                    $endList = $Nodes[count($Nodes)-1];
                    if(count($endList) > 1) {
						array_pop($Nodes);
                        $endNode = $endList[count($endList)-1];
						array_pop($endList);
						$endList[] = $endNode;
                        $Nodes[] = $endList;
                    }
                } elseif ($node1->GetType() == EndNode || $node1->GetType() == SingleNode) {
					array_pop($curList);
                } elseif ($node1->GetType() == RootNode) {
					$curList = array();
				}
				$curList[] = $node1;
                $Nodes[] = $curList;
            }

        } while ($res);
        $outPut;
		
        if (count($Nodes) > 0 && $this->WriteStr($Nodes, $outPut, $pos)) {
			$isFind = true;
            return $outPut;
        }
		//return $this->m_name + " " + $this->m_str;
    }
	
	public function defineBookIndex(&$name) {
		$books = BibleBooks::get()->GetBibleBooks($type = 'all');
		foreach ($books as $book => $index) {
			//if (mb_strtolower($name, 'UTF-8') == mb_strtolower($book, 'UTF-8')) { 
			if (mb_strtolower($name) == mb_strtolower($book)) { 
				$this->m_bookIndex = $index;
				$name = $book;
				break;
			}
		}
	}
	
	public function particleCorrectedBook($name) { // корректное отображение названия книг без изменения вида их написания
		$booksShortPoint = BibleBooks::get()->GetBibleBooks($type = 'shortpoint');
		
		if (array_key_exists($name, $booksShortPoint))
			$name .= ".";
			
		$name = str_replace("&nbsp;", " ", $name);
		$name = mb_convert_case($name, MB_CASE_TITLE, "UTF-8");
		$input = array("iv ", "iii ", "ii ", "i ");
		$output = array("IV".$_ENV["spaceType"], "III".$_ENV["spaceType"], "II".$_ENV["spaceType"], "I".$_ENV["spaceType"]);
		$name = str_ireplace($input, $output, $name);
		$name = str_replace(" ", $_ENV["spaceType"], $name); 
		return $name;
	}
		
	public function constructLink($name, &$nodeList) {

		// Формируем ссылку в зависимости от сайта
		// Адрес									перевод		книга				глава	стих	перевод
		// http://allbible.info/bible/				sinodal/	phm					/1#		5
		// http://bible.com.ua/bible/r/							57					/1#		5
		// http://biblezoom.ru/#								25					-1-		5
		// http://bibleonline.ru/bible/				rus/		64					/1/#	5
		// http://bible-center.ru/bibletext/		synnew_ru/	phm					/1#		phm1_5
		// http://bibleserver.com/text/				RSZ/		Послание Филимону			5
		// http://bibleserver.com/text/				RSZ/		Послание Римлянам 	1.		31
		// https://bible.com/ru/bible/				400/		phm					.1.		5		.syno - вариант не используется
		// http://bible.us/							400/		phm					.1.		5		.syno
		// http://bible-desktop.com/bq/							philemon			/1_		5		/RST
		// http://b-bq.eu/										philemon			/1_		5		/RST
// https://www.biblegateway.com/passage/?version=	RUSV&search=Philemon			+1%3A	5-
		// http://azbyka.ru/biblia/?							Phlm				.1:		5		&r			&rus&num­­c
		// нет https://ru.wikisource.org/wiki/					Послание_к_Филимону	#1:		5
		// http://biblia.com/books/					rst/		Phm					1.		5
		// http://bibles.org/						rus-SYNOD/	Phlm				/1/		5
		
		// data-bsource='bibla'									data-bverse='phm	1.		5		' data-bversion='RST'
		// data-bsource='bibleonline'							data-bverse='phm	+1.		5		' data-bversion='rus'
		// data-bsource='getbible'								data-bverse='Phm	1:		5		' data-bversion='synodal'
		// data-bsource='preachingcentral'						data-bverse='Phlm	1:		5		' data-bversion='synodal'
		
		$bibleSource = BibleSource::get($this->m_bookIndex);
		$link = $bibleSource->getLink($this->GetSpecialTranslation());
		
		if ($_ENV["popupWindow"]) {
            $javaScriptData = BibleSource::getJavaScript($this->m_bookIndex);;
			$bverse = $javaScriptData->getLink($this->GetSpecialTranslation());
			$bversion = $javaScriptData->GetTranslationPrefix($this->GetSpecialTranslation());
        }
		
		$nodeArray = $nodeList[0];
		$nodeChapter = $nodeArray[count($nodeArray)-1];
		if (array_key_exists(1, $nodeList))
			$nodeArray = $nodeList[1];
		$node = $nodeArray[count($nodeArray)-1];

		if ($nodeList[0][0]->GetType() == NamedNode) 
			$txtLink = ($_ENV["doCorrection"]) ? BibleBooks::get()->RightBibleBooks($name) : $this->particleCorrectedBook($name);
		
		for ($i = 0; $i < count($nodeList); $i++) {
			if (!$this->getNodeText($nodeList[$i], $txtLink))
				return "";
		}
		
		if ($this->GetSpecialTranslation())
			$txtLink .= $this->GetSpecialTranslation();

		if ($this->bibleBooks->IsSingleChapterBook($this->m_bookIndex) ) { // Учитывает одноглавные книги (Флм. 6 и Флм. 1:6)
            $isSubNodeExist = false;
            for ($i = 0; $i < count($nodeArray); $i++) {
                $tmp_node = $nodeArray[$i];
                if ($tmp_node->GetType() == SubNode) {
                    $isSubNodeExist = true;
                    break;
                }
            }
            if (!$isSubNodeExist) {
                $link 	.= $bibleSource->getSingleChapterPart($nodeChapter->GetNumber());
                if ($_ENV["popupWindow"]) $bverse .= $javaScriptData->getSingleChapterPart($nodeChapter->GetNumber());
            } else {
                if ($_ENV["g_BibleSource"] == "BibleserverComSource") {
                    $link .= $node->GetNumber(); 	// Тонкость формирования однокнижной ссылки для bibleserver.com
                } else {
                    $link 	.= $bibleSource->getChapterPart($nodeChapter->GetNumber());
                    if ($_ENV["popupWindow"]) $bverse .= $javaScriptData->getChapterPart($nodeChapter->GetNumber());
                    if ($node->GetType() == SubNode) {
                        $link 	.= $bibleSource->getVersePart($node->GetNumber());
                        if ($_ENV["popupWindow"]) $bverse .= $javaScriptData->getVersePart($node->GetNumber());
                    }
                }
            }
		} else {
			$link 	.= $bibleSource->getChapterPart($nodeChapter->GetNumber());
			if ($_ENV["popupWindow"]) $bverse .= $javaScriptData->getChapterPart($nodeChapter->GetNumber());
			if (count($nodeList) > 1) {
				if ($node->GetType() != 1 && $node->GetType() != 0) {		// Учитывает интервал глав (Иов. 38–42 и Иов. 38,42)
					$link .= $bibleSource->getVersePart($node->GetNumber());
					if ($_ENV["popupWindow"]) $bverse .= $javaScriptData->getVersePart($node->GetNumber());
				} else {
					// Выводит в всплывающем окне только первый стих главы
					if ($_ENV["popupWindow"] && ($_ENV["popupSource"] == "getbible" || $_ENV["popupSource"] == "preachingcentral")) {
						$bverse .= ":1"; // особенность для getbible.net и preachingcentral.com
					} else {
						$bverse .= ".1";
					}
				}
			} else {
				// Выводит в всплывающем окне только первый стих главы
				if ($_ENV["popupWindow"] && ($_ENV["popupSource"] == "getbible" || $_ENV["popupSource"] == "preachingcentral")) {
					$bverse .= ":1"; // особенность для getbible.net и preachingcentral.com
				} else {
					$bverse .= ".1";
				}
			}
		}
		
		if ($_ENV["g_BibleSource"] == "BibleComSource" || $_ENV["g_BibleSource"] == "AzbykaRuSource") {
				$link .= $bibleSource->GetTranslationPrefixLast($translation); 	// Тонкость формирования однокнижной ссылки для bible.com и azbyka.ru
				
		}
		
		($_ENV["doNotWrap"]) ? $spaceType = "style='white-space: pre; word-wrap: normal;' " : $spaceType = "";
		
		($_ENV["popupWindow"]) ? $datas = "title='' class='blink' data-bsource='" . $_ENV["popupSource"] . "' data-bverse='$bverse' data-bversion='$bversion' " : $datas = "";
		
		if ($this->bibleBooks->IsSingleChapterBook($this->m_bookIndex)) { // Формирование ссылки для одноглавных книг
			if ($nodeList[0][0]->GetType() == RootNode) {
				return "; <a href='$link' " . $datas . $spaceType . "target='blank'>" . $txtLink . "</a>";
			} else {
				return "<a href='$link' " . $datas . $spaceType . "target='blank'>" . $txtLink . "</a>";
			}
		} else if ($nodeList[0][0]->GetType() == RootNode) { // Проверка на существование главы
			if (BibleBooks::get()->RightChaptersNumber($name) < $nodeList[0][0]->GetNumber()) {
				return "; " . $txtLink;
			} else {
				return "; <a href='$link' " . $datas . $spaceType . "target='blank'>" . $txtLink . "</a>";
			}
		} else if ($nodeList[0][0]->GetType() == NamedNode) { // Проверка на существование главы
			if (BibleBooks::get()->RightChaptersNumber($name) < $nodeList[0][0]->GetNumber()) {
				return $txtLink;
			} else {
				return "<a href='$link' " . $datas . $spaceType . "target='blank'>" . $txtLink . "</a>";
			}
		} else {
			return $txtLink;
		}
	}
	
    public function WriteStr(&$nodes, &$outPut, $pos) {
		$linkNodes = array();
		
        for ($i = 0; $i < count($nodes); $i++) {
            $beg = $nodes[$i];
            $txtLink = "";
			
			$bnode = $beg[count($beg)-1];
			
			// Исправление смежных стихов и глав через тире
			if ($bnode->GetType() == EndNode) {
				$begprev = $nodes[$i-1];
				$bnodeprev = $begprev[count($begprev)-1];
				if (isset($nodes[$i+1])) {
					$begnext = $nodes[$i+1];
					$bnodenext = $begnext[count($begnext)-1];
					if (($bnodeprev->GetType() == SubNode || $bnodeprev->GetType() == SingleNode || $bnodeprev->GetType() == NamedNode)
						&& $bnodenext->GetType() != SubNode
						&& ($bnodeprev->GetNumber() + 1) == $bnode->GetNumber() )
						$bnode->SetType(SingleNode);
				} else {
					if (($bnodeprev->GetType() == SubNode || $bnodeprev->GetType() == SingleNode || $bnodeprev->GetType() == NamedNode)
						&& ($bnodeprev->GetNumber() + 1) == $bnode->GetNumber() )
						$bnode->SetType(SingleNode);
				}
			}
			
			// определение root nodes
			if ($bnode->GetType() == RootNode) {
				if (count($linkNodes)) {
					$linkstr = $this->constructLink($this->m_modifiedName, $linkNodes);
					$outPut = $outPut . $linkstr;
				}
				$linkNodes = array();
			}
			$linkNodes[] = $beg;
        }
		
		if (count($linkNodes)) {
			$linkstr = $this->constructLink($this->m_modifiedName, $linkNodes);
			$outPut = $outPut . $linkstr;
		}

        if ($pos < strlen($this->m_str))
			$outPut = $outPut . substr($this->m_str, $pos);
			
		$this->m_pos = $pos;
        return true;
    }
	
    public function getNodeText(&$nodeArray, &$txtLink) {
	
		$node = $nodeArray[count($nodeArray)-1];
		
		switch ($node->GetType()) {
			case EndNode:
				$txtLink .= "&ndash;";
				break;
			case SubNode:
				$txtLink .= $_ENV["ChapterSeparatorVerseOut"];
				break;
			case RootNode:
				$txtLink .= ($_ENV["doBookRepeat"]) ? (($_ENV["doCorrection"]) ? (BibleBooks::get()->RightBibleBooks($this->m_modifiedName)) : ($this->particleCorrectedBook($this->m_modifiedName))).$_ENV["spaceType"] : "";
				break;
			case SingleNode:
				$txtLink .= $_ENV["VerseSeparatorVerseOut"];
				break;
			case NamedNode:
				$txtLink .= $_ENV["spaceType"];
				break;
		}
		$lastNode = $nodeArray[count($nodeArray)-1];
		$txtLink .= $lastNode->GetNumber();

		if ($lastNode->GetAdditionalSymbol()) {
			$txtLink .= $lastNode->GetAdditionalSymbol();
		}

        return true;
    }

	private function CheckForAdditionalPart($intPos, &$additionalSymbol) {
        $this->GetInt($intPos, $n); // get position after number
		$onesymbollens = ($_ENV["languageIn"] == 'en') ? strlen('a') : strlen('а'); // латинская и кириллическая "а"
		//$onesymbollens = strlen('а');
		$additionalSymbol = substr($this->m_str, $intPos, $onesymbollens);
		$symbols = array('а', 'б', 'с', 'н', 'a', 'b', 'n'); // первая и вторая половина стихов, следующие стихи на рус., укр., англ.
		if (in_array($additionalSymbol, $symbols))
			return true;
		return false;
	}
	
	private function CheckForSpecialTranslation(&$pos, &$specialTranslation, &$specialTranslationText) {
		$translations = BibleBooks::get()->GetSpecialTranslationArray();
        
        $strAfterDigits = substr($this->m_str, $pos, (strlen($this->m_str) > 20) ? 20 : strlen($this->m_str)); // MAX SPECIAL TRANSLATION SIZE
        $strAfterDigitsTrimed = preg_replace('/^(&nbsp;| )+/', '', $strAfterDigits);
        $trimSize = strlen($strAfterDigits) - strlen($strAfterDigitsTrimed);
        
		foreach($translations as $key=>$value) { 
            if ($key == substr( $strAfterDigitsTrimed, 0, strlen($key) )) {
				if (BibleBooks::get()->CheckForTranslationExist($this->m_bookIndex, $value)) {
                    $pos += $trimSize + strlen($key);
					$specialTranslationText = $key;
					$specialTranslation = $value;
					return true;
				} else {
					return false;
				}
			}
		}
		return false;
	}
	
    public function Parse_(&$node, &$pos) {
	
		$oldPos = $pos;
        $pos = $this->TrimStr($pos);
		$pos++;

        if (strlen($this->m_str) <= $pos)
            return false;
		
		// поиск среднего и длинного тире в UTF-8 &ndash; &mdash;
		if (ord($this->m_str[$pos-1]) == 226 && ord($this->m_str[$pos]) == 128 
			&& (ord($this->m_str[$pos+1]) == 147 || ord($this->m_str[$pos+1]) == 148)) {
			$pos += 2;
			$this->m_str[$pos-1] = '-';
		} elseif (
			($this->m_str[$pos-1] == '&' && (mb_strtolower($this->m_str[$pos]) == 'n' || mb_strtolower($this->m_str[$pos]) == 'm')
			&& mb_strtolower($this->m_str[$pos+1]) == 'd' && mb_strtolower($this->m_str[$pos+2]) == 'a' && mb_strtolower($this->m_str[$pos+3]) == 's' 
			&& mb_strtolower($this->m_str[$pos+4]) == 'h' && $this->m_str[$pos+5] == ';')
			) {
			$pos += 6;
			$this->m_str[$pos-1] = '-';
		}
		
        switch ($this->m_str[$pos-1]) {
			case $_ENV["ChapterSeparatorVerseIn"]:
                $node->SetType(SubNode);
				if ($this->CheckForAdditionalPart($pos, $additionalSymbol))
					$node->SetAdditionalSymbol($additionalSymbol);
                break;
			case '-':
				if ($this->m_str[$pos-1] == '-' && $this->m_str[$pos] == '-' ) // убирает двойной дефис ("--")
					$pos++;
                $node->SetType(EndNode);
                if ($this->CheckForAdditionalPart($pos, $additionalSymbol))
					$node->SetAdditionalSymbol($additionalSymbol);
                break;
			case $_ENV["VerseSeparatorVerseIn"]:
				$node->SetType(SingleNode);
				if ($this->CheckForAdditionalPart($pos, $additionalSymbol))
					$node->SetAdditionalSymbol($additionalSymbol);
				break;
			case ';':
				$node->SetType(RootNode);
				break;
			default:
				$pos = $oldPos;
				return false;
        }
		
		if ($this->FillNode($node, $pos)) {
			if($node->GetAdditionalSymbol())
				$pos += strlen($node->GetAdditionalSymbol());
			if($this->CheckForSpecialTranslation($pos, $specialTranslation, $specialTranslationText))
				$this->SetSpecialTranslation($specialTranslation);
			return true;
		}
		$pos = $oldPos;
        return false;
    }

    public function  TrimStr($pos, $trimChar = "") {
		$str = substr($this->m_str, $pos);
		$currentSize = strlen($str);
		if ($trimChar == ".") {
			$str = ltrim($str, $trimChar);
		} elseif ($trimChar == ",") {
			$str = ltrim($str, $trimChar);
		} elseif ($trimChar == "chapter") {
			if ($_ENV["languageIn"] == 'en') {
				$str = ltrim($str, "c");
				$str = ltrim($str, "h");
			} else {
				$str = ltrim($str, "г");
				$str = ltrim($str, "л");
			}
		} else {
			$str = preg_replace('/^(&nbsp;| )+/', '', $str);
		}
		return $pos + ($currentSize - strlen($str));
    }

    public function GetInt(&$pos, &$n) {
		$str = substr($this->m_str, $pos);
		
		$h = sscanf($str, "%d", $n);
		if (!$n) { // Для римских цифр
			$h_rom = sscanf($str, "%s", $n_rom);
			$pattern = "/\bC{0,1}(XC|XL|L?X{0,3})(IX|IV|V?I{0,3})\b/";
			preg_match($pattern, $n_rom, $matches);
			if ($matches and $matches[0]) {
				$n = $this->roman2dec($matches[0]);

				if ($h_rom != 1)
					return false;
				$pos += strlen((string)$matches[0]);
				return true;
			}
		}

		if ($h != 1)
			return false;
		$pos += strlen((string)$n);
		return true;
    }

	private function roman2dec($roman) { // конвертор из римских в арабские цифры
		$roman = strtoupper($roman);
		$roman = preg_replace("[^IVXLC]", "", $roman);
		$romanLettersToNumbers = array("C" => 100, "L" => 50, "X" => 10, "V" => 5, "I" => 1);

		$oldChunk = 101;
		$calculation = '';

		for($start = 0; $start < strlen($roman); $start++) {
			$chunk = substr($roman, $start, 1);
			
			$chunk = $romanLettersToNumbers[$chunk];
			
			if ($chunk <= $oldChunk) {
				$calculation .= " + $chunk";
			} else {
				$calculation .= " + " . ($chunk - (2 * $oldChunk));
			}
			$oldChunk = $chunk;
		}
		eval ("\$calculation = $calculation;");
		return $calculation;
	}
	
    // read number from str and set in node
    public function FillNode(&$none, &$pos) {
        $pos = $this->TrimStr($pos);
       
		if ($this->GetInt($pos, $n)) {
            $none->SetNumber($n);
        } else {
			return false;
        }
        return true;
    }
}

class CLinkCreator {
	
	function __construct() {
		mb_internal_encoding('UTF-8');
	}
	
	public function CheckForBook(&$contentLower, $currentpos) {
		$bookkey = BibleBooks::getBookKey($contentLower, $currentpos);
		$books = BibleBooks::get()->GetBibleBooks($type = 'all', $bookkey);
		$bookname = false;

		foreach ($books as $book => $index) {
			if ($currentpos >= mb_strlen($book)) {
				$tempBookNameLower = mb_substr($contentLower, $currentpos - mb_strlen($book), mb_strlen($book));
				if ($tempBookNameLower == $book and mb_strlen($tempBookNameLower) > mb_strlen($bookname))
					$bookname = $tempBookNameLower;
			}
		}
		return $bookname;
	}

	// $contentLower - readonly
	private function GoToNextSymbolBack(&$contentLower, &$currentpos) {
		$currentpos--;
	}
	
	private function FindDigitRight(&$contentLower, &$currentpos) {
		$digitArray = Array('0', '1', '2', '3', '4', '5', '6', '7', '8', '9');
		while($currentpos) {
		    $symbol = mb_substr($contentLower, $currentpos - mb_strlen('1'), mb_strlen('1'));
			if(in_array($symbol, $digitArray))
				return true;
			$currentpos--;
		}
		return false;
	}
	
	// $contentLower - readonly
	public function FindBook(&$contentLower, &$posofbook, &$bookname) {
		$currentpos = mb_strlen($contentLower);
		while ($this->FindDigitRight($contentLower, $currentpos)) {
			$maxSymbolsInBookName = 30; // Максимальная длина имени книги
			$maxSymbolsCount = $maxSymbolsInBookName;
			while ($currentpos && $maxSymbolsCount) {
				$maxSymbolsCount--;
				$bookname = $this->CheckForBook($contentLower, $currentpos); // проверка
				
				if ($bookname) {
                    $previouscharacter_position = $currentpos - mb_strlen($bookname) - 1;
                    $previouscharacter = mb_substr($contentLower, $previouscharacter_position , mb_strlen('1'));
                    if ($previouscharacter_position < 0 || 
                        $previouscharacter == '(' || $previouscharacter == '{' || $previouscharacter == '[' ||
						$previouscharacter == ' ' || $previouscharacter == '>' || $previouscharacter == ';' || 
						$previouscharacter == chr(10) || $previouscharacter == chr(9)) {
                        $posofbook = $currentpos - mb_strlen($bookname);
                        return true;
                    }
				}
				$this->GoToNextSymbolBack($contentLower, $currentpos);
			}
			if(!$currentpos)
			    break;
			
			$currentpos += $maxSymbolsInBookName - 1;
		}
		return false;
	}
	
	public function SearchBibleLinks($content) {
		$content = str_replace("&amp;", "&", $content);
		$inputSymbols = array(
			" ", "&emsp;", " ", "&ensp;", " ", "&#8196;", " ", "&#8197;", " ", "&#8198;", " ", "&thinsp;", " ", "&#8202;", " ",
			"’", "&#039;", "'",
			"&#8211;", "–", "&#8208;", "‐", "&#8209;", "‑", "&#8210;", "‒", "&minus;", "&#8722;", "−", 
			"&#8212;", "—");
		$outputSymbols = array(
			" ", " ", " ", " ", " ", " ", " ", " ", " ", " ", " ", " ", " ", " ", " ",
			"&rsquo;", "&rsquo;", "&rsquo;", 
			"&ndash;", "&ndash;", "-","-", "-", "-", "&ndash;", "&ndash;","&ndash;", "&ndash;", "&ndash;", 
			"&mdash;", "&mdash;");
		$content = str_replace($inputSymbols, $outputSymbols, $content); // замена неразрывных пробелов и апострофов в виде символов
		$contentLower = mb_strtolower($content);
		$output = "";
		while ($this->FindBook($contentLower, $posofbook, $bookname)) {
			$w = new CNodeWrapper($bookname, mb_substr($content, $posofbook + mb_strlen($bookname)));
			$t = $w->Parse($isfind);

			if ($isfind) {
				$endOfLink = $posofbook + mb_strlen($bookname) + $w->m_pos; // $w->m_pos - длина текстовой ссылки
				$contentlen = mb_strlen($content) - $endOfLink; 
				$linklen = mb_strlen($t) - $contentlen;
			
				$output = mb_substr($t, 0, $linklen) . mb_substr($content, $endOfLink) . $output;
				$content = mb_substr($content, 0, $posofbook);
				$contentLower = mb_substr($contentLower, 0, $posofbook);
			} else {
				$output = mb_substr($content, $posofbook) . $output;
				$content = mb_substr($content, 0, $posofbook);
				$contentLower = mb_substr($contentLower, 0, $posofbook);
			}
		}
		$output = $content . $output;
		return $output;
	}
}

function SearchBibleLinks($str) {
	$lc = new CLinkCreator();
	return $lc->SearchBibleLinks($str);
}
?>