<?php    
    class StringHandler
    {
        function RusStrToTranslitStr($string)
        {
            $converter = array(
            'а' => 'a', 'б' => 'b', 'в' => 'v', 'г' => 'g', 'д' => 'd', 'е' => 'e', 'ё' => 'e',
            'ж' => 'zh', 'з' => 'z', 'и' => 'i', 'й' => 'y', 'к' => 'k', 'л' => 'l', 'м' => 'm',
            'н' => 'n', 'о' => 'o', 'п' => 'p', 'р' => 'r', 'с' => 's', 'т' => 't', 'у' => 'u',
            'ф' => 'f', 'х' => 'h', 'ц' => 'c', 'ч' => 'ch', 'ш' => 'sh', 'щ' => 'sch', 'ь' => "",
            'ы' => 'y', 'ъ' => "", 'э' => 'e', 'ю' => 'yu', 'я' => 'ya',
    
            'А' => 'A', 'Б' => 'B', 'В' => 'V', 'Г' => 'G', 'Д' => 'D', 'Е' => 'E', 'Ё' => 'E',
            'Ж' => 'Zh', 'З' => 'Z', 'И' => 'I', 'Й' => 'Y', 'К' => 'K', 'Л' => 'L', 'М' => 'M',
            'Н' => 'N', 'О' => 'O', 'П' => 'P', 'Р' => 'R', 'С' => 'S', 'Т' => 'T', 'У' => 'U',
            'Ф' => 'F', 'Х' => 'H', 'Ц' => 'C', 'Ч' => 'Ch', 'Ш' => 'Sh', 'Щ' => 'Sch', 'Ь' => "",
            'Ы' => 'Y', 'Ъ' => "", 'Э' => 'E', 'Ю' => 'Yu', 'Я' => 'Ya', ' ' => '_'
            );
            
            return strtr($string, $converter);
        }
        
        // Возвращает название месяца по номеру
        // $inducing = склонение
        function GetNameMonth($inputNumber, $inducing = false)
        {        
            $namedMonths = array(
            "январ",
            "феврал",
            "март",
            "апрел",
            "ма",
            "июн",
            "июл",
            "август",
            "сентябр",
            "октябр",
            "ноябр",
            "декабр"
            );
            
            $nameMonth = $namedMonths[0];
            
            // Если полученный параметр не привышает допустимого, то..
            if ((intval($inputNumber)-1) <= count($namedMonths))
                $nameMonth = $namedMonths[intval($inputNumber)-1];
            
            if ($inducing)
            {
                if ($inputNumber == 3 || $inputNumber == 8)
                    $nameMonth .= "а";
                else
                    $nameMonth .= "я";
            }
            else
            {
                if ($inputNumber != 3 || $inputNumber != 8)
                    $nameMonth .= "ь";
            }
            
            return $nameMonth;
        }
        
        // Проверяет строку на присутствие в ней пустых знаков (пробел)
        function IsEmptyString($string)
        {
            $countSymbolSpace = 0;
            
            for ($i = 0; $i < strlen($string); $i++)
            {
                if ($string[$i] == ' ')
                {
                    $countSymbolSpace++;
                }
            }
            
            if ($countSymbolSpace == strlen($string))
                return true;
            else
                return false;
        }
        
        function numberof($numberof, $value, $suffix = array('', 'а', 'ов'))
        {
            $keys = array(2, 0, 1, 1, 1, 2);
            $mod = $numberof % 100;
            $suffix_key = $mod > 4 && $mod < 20 ? 2 : $keys[min($mod%10, 5)];
            
            return $value . $suffix[$suffix_key];
        }
        
        function PrintDate($date_text, $isTime = true)
        {
            $name_month = $this->GetNameMonth(date("m", $date_text), true);
            $day = date("d", $date_text);
            if (date("Y", $date_text) == date("Y", time()))
                $year = "";
            else
                $year = " ".date("Y", $date_text);

            $time = $isTime ? date("H:i", $date_text) : "";
                
            return $day . " " . $name_month . $year . " ". $time;
        }
        
        function PrintLegendLine($currentPage, $all_count, $maxItemsInPage = DEFAULT_COUNT_ITEMS, $module_name = "news", $other_param = "")
        {                                           
            global $stringHandler;
            
            $pages = ceil(($all_count) / $maxItemsInPage);
            $MAX_ITEM = 15;
            
            if ($currentPage < $MAX_ITEM)
                $blockPages = $MAX_ITEM;
            else
                $blockPages = $currentPage + 5;
                
            if ($blockPages > $MAX_ITEM)
                $i = $currentPage - 5;
            else
                $i = 1;
               
            if ($i < 1)
                $i = 1;
            
            if ($blockPages >= $pages)
                $blockPages = $pages; 
    
            if ($pages > 1) 
            {
                echo "<div class=\"legend_line\">";
                
                if (strlen($other_param) > 0 && !$stringHandler->IsEmptyString($other_param))
                    $other_param = "&" . $other_param;
                    
                if (($currentPage-8) >= 1)
                    $prevMore = $currentPage-8;
                else
                    $prevMore = 1;
                    
                if (($blockPages+8) <= $pages)
                    $nextMore = $currentPage+8;
                else
                    $nextMore = $pages;
                
                if ($currentPage >= $MAX_ITEM)
                {
                    echo "<a class=\"legend\" href=\"?" . NAME_PARAM_PAGENAME . "=" . $module_name . $other_param . "&". NAME_PARAM_NUMPAGE ."=". $prevMore ."\"><назад</a>";
                    echo "<a class=\"legend\" href=\"?" . NAME_PARAM_PAGENAME . "=" . $module_name . $other_param . "&". NAME_PARAM_NUMPAGE ."=1\">1</a>...";
                }
                
                while ($i <= $blockPages)
                {
                    $textPage = $i;
                    
                    if ($stringHandler->IsEmptyString($currentPage) && $i == 1)
                        echo "<a class=\"legend active\">".$textPage."</a>";
                    else if ($i != intval($currentPage))
                        echo "<a class=\"legend\" href=\"?" . NAME_PARAM_PAGENAME . "=" . $module_name . $other_param . "&". NAME_PARAM_NUMPAGE ."=".$i."\">".$textPage."</a>";
                    else if ((isset($currentPage)) && (intval($currentPage) == $i))
                        echo "<a class=\"legend active\">".$textPage."</a>";
                    $i++;
                }
                
                if ($currentPage < $pages && $blockPages < $pages)
                {
                    echo "...<a class=\"legend\" href=\"?" . NAME_PARAM_PAGENAME . "=" . $module_name . $other_param . "&". NAME_PARAM_NUMPAGE ."=".$pages."\">".$pages."</a>";
                    echo "<a class=\"legend\" href=\"?" . NAME_PARAM_PAGENAME . "=" . $module_name . $other_param . "&". NAME_PARAM_NUMPAGE ."=". $nextMore ."\">вперед></a>";
                }
                
                echo "</div>";
            }
        }
        
        function GetStrFileSize($filesize) 
        {
            // Если размер переданного в функцию файла больше 1кб 
            if ($filesize > 1024) 
            { 
                $filesize = ($filesize / 1024); 
                // если размер файла больше одного килобайта 
                // пересчитываем в мегабайтах 
                if ($filesize > 1024) 
                {
                    $filesize = ($filesize / 1024); 
                    // если размер файла больше одного мегабайта 
                    // пересчитываем в гигабайтах 
                    if ($filesize > 1024)
                    {
                        $filesize = ($filesize / 1024); 
                        $filesize = round($filesize, 1); 
                        return $filesize." ГБ";
                    }
                    else 
                    {
                        $filesize = round($filesize, 1); 
                        return $filesize." MБ";    
                    }
                }
                else
                { 
                    $filesize = round($filesize, 1); 
                    return $filesize." Кб";    
                }
            }
            else 
            {
                $filesize = round($filesize, 1); 
                return $filesize." байт";    
            }
        }

        function AgeToStr($Age)
        {
            $str='';
            $num=$Age>100 ? substr($Age, -2) : $Age;
            if($num>=5&&$num<=14) $str = "лет";
            else
            {
                $num=substr($Age, -1);
                if($num==0||($num>=5&&$num<=9)) $str='лет';
                if($num==1) $str='год';
                if($num>=2&&$num<=4) $str='года';
            }
            return $Age.' '.$str;
        }
		
		function UrlParseFilter($text)
		{
			$reg_exUrl = "/(http|https|ftp|ftps)\:\/\/[a-zA-Z0-9\-\.]+\.[a-zA-Z]{2,3}(\/\S*)?/";
			if (preg_match($reg_exUrl, $text, $url))
				echo preg_replace($reg_exUrl, '<a target="_blank" href="'.$url[0].'" rel="nofollow">'.$url[0].'</a>', $text);
			else 
				echo $text;
		}
    }
?>