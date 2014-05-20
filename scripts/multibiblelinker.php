<?php
include 'bible_links.php'; 			// массивы библейских ссылок
include 'bible_sources.php'; 		// сайты библейский текстов и переводов

// Например в Мф. 1:2–4,6; 7:8

define("NamedNode", 4);		// самая первая цифра после названия книги (1)
define("SubNode", 3);		// цифра после двоеточия (2, 8)
define("EndNode", 1); 		// цифра после тире (4)
define("SingleNode", 0); 	// цифра после запятой (6)
define("RootNode", 2); 		// цифра после точки с запятой (7)

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
		$pos = $this->TrimStr($pos, "гл");
		$pos = $this->TrimStr($pos, ".");
        $pos = $this->TrimStr($pos);
        $node->SetType(NamedNode);
        if ($this->CheckForAdditionalPart($pos, $additionalSymbol)) {
            $node->SetAdditionalSymbol($additionalSymbol);
        }
		if(!$this->FillNode($node, $pos)) {
			return $this->m_name + " " + $this->m_str;
        }
        $pos += strlen($node->GetAdditionalSymbol());
        if($this->CheckForSpecialTranslation($pos, $specialTranslation, $specialTranslationText)) {
            $this->SetSpecialTranslation($specialTranslation);
        }
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
		return $this->m_name + " " + $this->m_str;
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
		
		if (array_key_exists($name, $booksShortPoint)) {
			$name .= ".";
		}
		
		$name = str_replace("&nbsp;", " ", $name);
		$name = mb_convert_case($name, MB_CASE_TITLE, "UTF-8");
		$input = array(" ", "i", "v");
		$output = array("&nbsp;", "I","V");
		$name = str_replace($input, $output, $name);

		return $name;
	}
		
	public function constructLink($name, &$nodeList) {

		$bParams = new BibleParams;
		$doCorrection = $bParams->doCorrection;
		$g_BibleSource = $bParams->g_BibleSource;

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
		
		$bibleSource = BibleSource::get($this->m_bookIndex);

		$link = $bibleSource->getLink($this->GetSpecialTranslation());
		
		$nodeArray = $nodeList[0];
		$nodeChapter = $nodeArray[count($nodeArray)-1];
		if (array_key_exists(1, $nodeList))
			$nodeArray = $nodeList[1];
		$node = $nodeArray[count($nodeArray)-1];

		if ($nodeList[0][0]->GetType() == NamedNode) {
			$txtLink = ($doCorrection) ? BibleBooks::get()->RightBibleBooks($name) : $this->particleCorrectedBook($name);
		}
		
		for ($i = 0; $i < count($nodeList); $i++) {
			if (!$this->getNodeText($nodeList[$i], $txtLink)) {
				return "";
            }
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
                $link .= $bibleSource->getSingleChapterPart($nodeChapter->GetNumber());
            } else {
                if ($g_BibleSource == "BibleserverComSource") {
                    $link .= $node->GetNumber(); 			// Тонкость формирования однокнижной ссылки для bibleserver.com
                } else {
                    $link .= $bibleSource->getChapterPart($nodeChapter->GetNumber());
                    if ($node->GetType() == SubNode) {
                        $link .= $bibleSource->getVersePart($node->GetNumber());
                    }
                }
            }
		} else {
			$link .= $bibleSource->getChapterPart($nodeChapter->GetNumber());
			if (count($nodeList) > 1) {
				if ($node->GetType() != 1 && $node->GetType() != 0) { 	// Учитывает интервал глав (Иов. 38–42 и Иов. 38,42)
					$link .= $bibleSource->getVersePart($node->GetNumber());
				}
			}
		}
		
		if ($g_BibleSource == "BibleComSource") {
				$link .= $bibleSource->GetTranslationPrefixLast($translation); 	// Тонкость формирования однокнижной ссылки для bible.com
			}
		
		if ($this->bibleBooks->IsSingleChapterBook($this->m_bookIndex)) { // Формирование ссылки для одноглавных книг
			if ($nodeList[0][0]->GetType() == RootNode) {
				return "; <a href='$link' target='blank'>" . $txtLink . "</a>";
			} else {
				return "<a href='$link' target='blank'>" . $txtLink . "</a>";
			}
		} else if ($nodeList[0][0]->GetType() == RootNode) { // Проверка на существование главы
			if (BibleBooks::get()->RightChaptersNumber($name) < $nodeList[0][0]->GetNumber()){
				return "; " . $txtLink;
			} else {
				return "; <a href='$link' target='blank'>" . $txtLink . "</a>";
			}
		} else if ($nodeList[0][0]->GetType() == NamedNode) { // Проверка на существование главы
			if (BibleBooks::get()->RightChaptersNumber($name) < $nodeList[0][0]->GetNumber()){
				return $txtLink;
			} else {
				return "<a href='$link' target='blank'>" . $txtLink . "</a>";
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
			
			// определение root nodes
			$bnode = $beg[count($beg)-1];
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

        if ($pos < strlen($this->m_str)) {
			$outPut = $outPut . substr($this->m_str, $pos);
        }
		$this->m_pos = $pos;
        return true;
    }
	
    public function getNodeText(&$nodeArray, &$txtLink) {
		
		$bParams = new BibleParams;
		$doCorrection = $bParams->doCorrection;
		$doBookRepeat = $bParams->doBookRepeat;
		$ChapterSeparatorVerseOut = $bParams->ChapterSeparatorVerseOut;
		$VerseSeparatorVerseOut = $bParams->VerseSeparatorVerseOut;	
	
		$node = $nodeArray[count($nodeArray)-1];
		switch ($node->GetType()) {
			case EndNode:
				$txtLink .= "&ndash;";
				break;
			case SubNode:
				$txtLink .= $ChapterSeparatorVerseOut;
				break;
			case RootNode:
				$txtLink .= ($doBookRepeat) ? (($doCorrection) ? (BibleBooks::get()->RightBibleBooks($this->m_modifiedName)) : ($this->particleCorrectedBook($this->m_modifiedName)))."&nbsp;" : "";
				break;
			case SingleNode:
				$txtLink .= $VerseSeparatorVerseOut;
				break;
			case NamedNode:
				$txtLink .= "&nbsp;";
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
		$onesymbollens = strlen('а');
		$additionalSymbol = substr($this->m_str, $intPos, $onesymbollens);
		$symbols = array('а', 'б', 'с', 'н'); // первая и вторая половина стихов, следующие стихи на рус. и укр.
		if(in_array($additionalSymbol, $symbols)) {
			return true;
		}
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
		
		$bParams = new BibleParams;
		$ChapterSeparatorVerseIn = $bParams->ChapterSeparatorVerseIn;
		$VerseSeparatorVerseIn = $bParams->VerseSeparatorVerseIn;
	
		$oldPos = $pos;
        $pos = $this->TrimStr($pos);
		$pos++;

        if (strlen($this->m_str) <= $pos) {
            return false;
        }
        switch ($this->m_str[$pos-1]) {
			case $ChapterSeparatorVerseIn:
                $node->SetType(SubNode);
				if ($this->CheckForAdditionalPart($pos, $additionalSymbol)) {
					$node->SetAdditionalSymbol($additionalSymbol);
				}
                break;
			case '-':
				if ($this->m_str[$pos-1] == '-' && $this->m_str[$pos] == '-' ){ // убирает двойной дефис ("--")
					$pos += 1;
				}
                $node->SetType(EndNode);
                if ($this->CheckForAdditionalPart($pos, $additionalSymbol)) {
					$node->SetAdditionalSymbol($additionalSymbol);
				}
                break;
			case $VerseSeparatorVerseIn:
				$node->SetType(SingleNode);
				if ($this->CheckForAdditionalPart($pos, $additionalSymbol)) {
					$node->SetAdditionalSymbol($additionalSymbol);
                }
               break;
			case ';':
				$node->SetType(RootNode);
				break;
			default:
				// поиск среднего и длинного тире в UTF-8 &ndash; &mdash; &minus; &#8208; &#8209; &#8210; &#8211; &#8212; &#8722;
				if (ord($this->m_str[$pos-1]) == 226 && ord($this->m_str[$pos]) == 128 
					&& (ord($this->m_str[$pos+1]) == 147 || ord($this->m_str[$pos+1]) == 148)) {
					$pos += 2;
					$node->SetType(EndNode);
					break;
				} elseif (
					($this->m_str[$pos-1] == '&' && ($this->m_str[$pos] == 'n' || $this->m_str[$pos] == 'm')
					&& $this->m_str[$pos+1] == 'd' && $this->m_str[$pos+2] == 'a' && $this->m_str[$pos+3] == 's' 
					&& $this->m_str[$pos+4] == 'h' && $this->m_str[$pos+5] == ';')
					||
					($this->m_str[$pos-1] == '&' && $this->m_str[$pos] == 'm' && $this->m_str[$pos+1] == 'i' 
					&& $this->m_str[$pos+2] == 'n' && $this->m_str[$pos+3] == 'u' && $this->m_str[$pos+4] == 's' 
					&& $this->m_str[$pos+5] == ';')
					||
					($this->m_str[$pos-1] == '&' && $this->m_str[$pos] == '#' && $this->m_str[$pos+1] == '8' 
					&& $this->m_str[$pos+2] == '2' && $this->m_str[$pos+3] == '0' 
					&& ($this->m_str[$pos+4] == '8' || $this->m_str[$pos+4] == '9') && $this->m_str[$pos+5] == ';')
					||
					($this->m_str[$pos-1] == '&' && $this->m_str[$pos] == '#' && $this->m_str[$pos+1] == '8' 
					&& $this->m_str[$pos+2] == '2' && $this->m_str[$pos+3] == '1'
					&& ($this->m_str[$pos+4] == '0' || $this->m_str[$pos+4] == '1' || $this->m_str[$pos+4] == '2')
					&& $this->m_str[$pos+5] == ';')
					||
					($this->m_str[$pos-1] == '&' && $this->m_str[$pos] == '#' && $this->m_str[$pos+1] == '8' 
					&& $this->m_str[$pos+2] == '7' && $this->m_str[$pos+3] == '2' && $this->m_str[$pos+4] == '2' 
					&& $this->m_str[$pos+5] == ';')
					) {
					$pos += 6;
					$node->SetType(EndNode);
					break;
				} else {
					$pos = $oldPos;
					return false;
				}
        }
			
		if ($this->FillNode($node, $pos)) {
			if($node->GetAdditionalSymbol()) {
				$pos += strlen($node->GetAdditionalSymbol());
			}
			if($this->CheckForSpecialTranslation($pos, $specialTranslation, $specialTranslationText)) {
				$this->SetSpecialTranslation($specialTranslation);
			}
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
		} elseif ($trimChar == "гл") {
			$str = ltrim($str, "г");
			$str = ltrim($str, "л");
		} else {
			$str = preg_replace('/^(&nbsp;| )+/', '', $str);
		}
		return $pos + ($currentSize - strlen($str));
    }

    public function GetInt(&$pos, &$n) {
		$str = substr($this->m_str, $pos);
		$h = sscanf($str, "%d", $n);
		if ($h != 1) {
			return false;
		}
		$pos = $pos + strlen((string)$n);
		return true;
    }

    // read number from str and set in node
    public function FillNode(&$none, &$pos) {
        $n;
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
				if ($tempBookNameLower == $book and mb_strlen($tempBookNameLower) > mb_strlen($bookname)) {
					$bookname = $tempBookNameLower;
				}
			}
		}
		return $bookname;
	}

	// $contentLower - readonly
	private function GoToNextSymbolBack(&$contentLower, &$currentpos) {
		$currentpos -= 1;
	}
	
	private function FindDigitRight(&$contentLower, &$currentpos) {
		$digitArray = Array('0', '1', '2', '3', '4', '5', '6', '7', '8', '9');
		while($currentpos) {
		    $symbol = mb_substr($contentLower, $currentpos - mb_strlen('1'), mb_strlen('1'));
			if(in_array($symbol, $digitArray)) {
				return true;
			}
			$currentpos -= 1;
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
				$maxSymbolsCount -= 1;
				$bookname = $this->CheckForBook($contentLower, $currentpos); // проверка
				
				if ($bookname) {
                    $previouscharacter_position = $currentpos - mb_strlen($bookname) - 1;
                    $previouscharacter = mb_substr($contentLower, $previouscharacter_position , mb_strlen('1'));
                    if ($previouscharacter_position < 0 || 
                                 ($previouscharacter == '(' || $previouscharacter == '{' || $previouscharacter == '[' ||
								  $previouscharacter == ' ' || $previouscharacter == '>' || $previouscharacter == ';' || 
								  $previouscharacter == chr(10) || $previouscharacter == chr(9))
						) {
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
		$content = str_replace(" ", "&nbsp;", $content); // замена неразрывных пробелов в виде символов
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