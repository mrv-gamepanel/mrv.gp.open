<?php

/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
 *   file                 :  secure.class.php
 * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
 *   author               :  Muhamed Skoko - mskoko.me@gmail.com
 * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */

class Secure {
	
	public function SecureTxt($String)  {
        $String = htmlspecialchars(trim($String), ENT_QUOTES);
    	
    	$String = str_ireplace('&amp;#34;', '"', $String);
        $String = str_ireplace('&amp;#39;', "'", $String);

        return $String;
    }

	public function LimitText($text, $chars_limit) {
		// Check if length is larger than the character limit
		if (strlen($text) > $chars_limit) {
			// If so, cut the string at the character limit
			$new_text = substr($text, 0, $chars_limit);
			// Trim off white space
			$new_text = trim($new_text);
			// Add at end of text ...
			return $new_text . "...";
		} else {
			return $text;
		}
	}

	// Valid email
	public function isValidMail($email) {
	    if(filter_var($email, FILTER_VALIDATE_EMAIL) !== false) {
	        return true;
	    } else {
	        return false;
	    }
	}

	// Random Key ()
	public function RandKey($length = 32, $chars = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz1234567890") {
		$chars_length = strlen( $chars ) - 1;
	    $string = $chars[rand( 0, $chars_length )];
	    $i = 1;
	    while ( $i < $length ) {
	        $r = $chars[rand( 0, $chars_length )];
	        if ( $r != $string[$i - 1] ) {
	            $string .= $r;
	        }
	        $i = strlen( $string );
	    }

	    return $string;
	}

	// Random key Link
	public function randKeyForLink($length=30, $chars = "abcdefghijklmnopqrstuvwxyz1234567890") {
		$chars_length = strlen( $chars ) - 1;
	    $string = $chars[rand( 0, $chars_length )];
	    $i = 1;
	    while ( $i < $length ) {
	        $r = $chars[rand( 0, $chars_length )];
	        if ( $r != $string[$i - 1] ) {
	            $string .= $r;
	        }
	        $i = strlen( $string );
	    }

	    return $string;
	}

	public function timeDiff($firstTime, $lastTime) {
		// convert to unix timestamps
		$firstTime = strtotime($firstTime);
		$lastTime = strtotime($lastTime);

		// perform subtraction to get the difference (in seconds) between times
		$timeDiff = ($lastTime - $firstTime);

		// return the difference
		return $timeDiff;
	}

	/* 24h to Array */
	public function get_hours_range( $start = 0, $end = 86400, $step = 3600, $format = 'H:ia' ) {
		$times = array();
		foreach ( range( $start, $end, $step ) as $timestamp ) {
			$hour_mins = gmdate( 'H:i', $timestamp );
			if ( ! empty( $format ) )
				$times[$hour_mins] = gmdate( $format, $timestamp );
			else $times[$hour_mins] = $hour_mins;
		}
		return $times;
	}

	/**
	 * [GetNexMonth]
	 * @param 	$Day   [1]
	 * @param 	$Month [1]
	 * @return 	11/11/2000 (example)
	 */
	public function GetNexMonth($Day=0, $Month=0) {
		$NextMonth = mktime(date('H'), date('i'), date('s'), date('n') + $Month, date('j') + $Day, date('Y'));

		return date('d/m/Y', $NextMonth);
	}

	public function GetPostoNum($tr_num=0, $max_num=0) {
		if ($tr_num == 0||$max_num == 0) {
			$VratiMi = '0';
		} else {
			$VratiMi = round((($tr_num / $max_num) * 100), 2);
		}

		return $VratiMi;
	}

	public function changeTxt($oText) {
		global $Secure;
		global $User;

		//$oText = $Secure->SecureTxt($oText);

		//$oText = nl2br($oText);
	

		// $oText = str_ireplace('[*test*]', 'test', $oText);

		/* Mail template */
		$oText = str_ireplace('[*br*]', '<br>', $oText);

		/* General */
		$oText = str_ireplace('&amp;#34;', '"', $oText);
        $oText = str_ireplace('&amp;#39;', "'", $oText);


		// Latin
	    $oText = str_replace('À', 'A', $oText);
	    $oText = str_replace('Á', 'A', $oText);
	    $oText = str_replace('Â', 'A', $oText);
	    $oText = str_replace('Ã', 'A', $oText);
	    $oText = str_replace('Ä', 'A', $oText);
	    $oText = str_replace('Å', 'A', $oText);
	    $oText = str_replace('Æ', 'AE', $oText);
	    $oText = str_replace('Ç', 'C', $oText);
	    $oText = str_replace('È', 'E', $oText);
	    $oText = str_replace('É', 'E', $oText);
	    $oText = str_replace('Ê', 'E', $oText);
	    $oText = str_replace('Ë', 'E', $oText);
	    $oText = str_replace('Ì', 'I', $oText);
	    $oText = str_replace('Í', 'I', $oText);
	    $oText = str_replace('Î', 'I', $oText);
	    $oText = str_replace('Ï', 'I', $oText);
	    $oText = str_replace('Ð', 'D', $oText);
	    $oText = str_replace('Ñ', 'N', $oText);
	    $oText = str_replace('Ò', 'O', $oText);
	    $oText = str_replace('Ó', 'O', $oText);
	    $oText = str_replace('Ô', 'O', $oText);
	    $oText = str_replace('Õ', 'O', $oText);
	    $oText = str_replace('Ö', 'O', $oText);
	    $oText = str_replace('Ő', 'O', $oText);
	    $oText = str_replace('Ø', 'O', $oText);
	    $oText = str_replace('Ù', 'U', $oText);
	    $oText = str_replace('Ú', 'U', $oText);
	    $oText = str_replace('Û', 'U', $oText);
	    $oText = str_replace('Ü', 'U', $oText);
	    $oText = str_replace('Ű', 'U', $oText);
	    $oText = str_replace('Ý', 'Y', $oText);
	    $oText = str_replace('Þ', 'TH', $oText);
	    $oText = str_replace('ß', 'ss', $oText);
	    $oText = str_replace('à', 'a', $oText);
	    $oText = str_replace('á', 'a', $oText);
	    $oText = str_replace('â', 'a', $oText);
	    $oText = str_replace('ã', 'a', $oText);
	    $oText = str_replace('ä', 'a', $oText);
	    $oText = str_replace('å', 'a', $oText);
	    $oText = str_replace('æ', 'ae', $oText);
	    $oText = str_replace('ç', 'c', $oText);
	    $oText = str_replace('è', 'e', $oText);
	    $oText = str_replace('é', 'e', $oText);
	    $oText = str_replace('ê', 'e', $oText);
	    $oText = str_replace('ë', 'e', $oText);
	    $oText = str_replace('ì', 'i', $oText);
	    $oText = str_replace('í', 'i', $oText);
	    $oText = str_replace('î', 'i', $oText);
	    $oText = str_replace('ï', 'i', $oText);
	    $oText = str_replace('ð', 'd', $oText);
	    $oText = str_replace('ñ', 'n', $oText);
	    $oText = str_replace('ò', 'o', $oText);
	    $oText = str_replace('ó', 'o', $oText);
	    $oText = str_replace('ô', 'o', $oText);
	    $oText = str_replace('õ', 'o', $oText);
	    $oText = str_replace('ö', 'o', $oText);
	    $oText = str_replace('ő', 'o', $oText);
	    $oText = str_replace('ø', 'o', $oText);
	    $oText = str_replace('ù', 'u', $oText);
	    $oText = str_replace('ú', 'u', $oText);
	    $oText = str_replace('û', 'u', $oText);
	    $oText = str_replace('ü', 'u', $oText);
	    $oText = str_replace('ű', 'u', $oText);
	    $oText = str_replace('ý', 'y', $oText);
	    $oText = str_replace('þ', 'th', $oText);
	    $oText = str_replace('ÿ', 'y', $oText);
	    
	    // Symbols
	    $oText = str_replace('&copy;', '(c)', $oText);
	    $oText = str_replace('©', '(c)', $oText);
	    
	    // Greek
	    $oText = str_replace('Α', 'A', $oText);
	    $oText = str_replace('Β', 'B', $oText);
	    $oText = str_replace('Γ', 'G', $oText);
	    $oText = str_replace('Δ', 'D', $oText);
	    $oText = str_replace('Ε', 'E', $oText);
	    $oText = str_replace('Ζ', 'Z', $oText);
	    $oText = str_replace('Η', 'H', $oText);
	    $oText = str_replace('Θ', '8', $oText);
	    $oText = str_replace('Ι', 'I', $oText);
	    $oText = str_replace('Κ', 'K', $oText);
	    $oText = str_replace('Λ', 'L', $oText);
	    $oText = str_replace('Μ', 'M', $oText);
	    $oText = str_replace('Ν', 'N', $oText);
	    $oText = str_replace('Ξ', '3', $oText);
	    $oText = str_replace('Ο', 'O', $oText);
	    $oText = str_replace('Π', 'P', $oText);
	    $oText = str_replace('Ρ', 'R', $oText);
	    $oText = str_replace('Σ', 'S', $oText);
	    $oText = str_replace('Τ', 'T', $oText);
	    $oText = str_replace('Υ', 'Y', $oText);
	    $oText = str_replace('Φ', 'F', $oText);
	    $oText = str_replace('Χ', 'X', $oText);
	    $oText = str_replace('Ψ', 'PS', $oText);
	    $oText = str_replace('Ω', 'W', $oText);
	    $oText = str_replace('Ά', 'A', $oText);
	    $oText = str_replace('Έ', 'E', $oText);
	    $oText = str_replace('Ί', 'I', $oText);
	    $oText = str_replace('Ό', 'O', $oText);
	    $oText = str_replace('Ύ', 'Y', $oText);
	    $oText = str_replace('Ή', 'H', $oText);
	    $oText = str_replace('Ώ', 'W', $oText);
	    $oText = str_replace('Ϊ', 'I', $oText);
	    $oText = str_replace('Ϋ', 'Y', $oText);
	    $oText = str_replace('α', 'a', $oText);
	    $oText = str_replace('β', 'b', $oText);
	    $oText = str_replace('γ', 'g', $oText);
	    $oText = str_replace('δ', 'd', $oText);
	    $oText = str_replace('ε', 'e', $oText);
	    $oText = str_replace('ζ', 'z', $oText);
	    $oText = str_replace('η', 'h', $oText);
	    $oText = str_replace('θ', '8', $oText);
	    $oText = str_replace('ι', 'i', $oText);
	    $oText = str_replace('κ', 'k', $oText);
	    $oText = str_replace('λ', 'l', $oText);
	    $oText = str_replace('μ', 'm', $oText);
	    $oText = str_replace('ν', 'n', $oText);
	    $oText = str_replace('ξ', '3', $oText);
	    $oText = str_replace('ο', 'o', $oText);
	    $oText = str_replace('π', 'p', $oText);
	    $oText = str_replace('ρ', 'r', $oText);
	    $oText = str_replace('σ', 's', $oText);
	    $oText = str_replace('τ', 't', $oText);
	    $oText = str_replace('υ', 'y', $oText);
	    $oText = str_replace('φ', 'f', $oText);
	    $oText = str_replace('χ', 'x', $oText);
	    $oText = str_replace('ψ', 'ps', $oText);
	    $oText = str_replace('ω', 'w', $oText);
	    $oText = str_replace('ά', 'a', $oText);
	    $oText = str_replace('έ', 'e', $oText);
	    $oText = str_replace('ί', 'i', $oText);
	    $oText = str_replace('ό', 'o', $oText);
	    $oText = str_replace('ύ', 'y', $oText);
	    $oText = str_replace('ή', 'h', $oText);
	    $oText = str_replace('ώ', 'w', $oText);
	    $oText = str_replace('ς', 's', $oText);
	    $oText = str_replace('ϊ', 'i', $oText);
	    $oText = str_replace('ΰ', 'y', $oText);
	    $oText = str_replace('ϋ', 'y', $oText);
	    $oText = str_replace('ΐ', 'i', $oText);
	    
	    // Turkish
	    $oText = str_replace('Ş', 'S', $oText);
	    $oText = str_replace('İ', 'I', $oText);
	    $oText = str_replace('Ç', 'C', $oText);
	    $oText = str_replace('Ü', 'U', $oText);
	    $oText = str_replace('Ö', 'O', $oText);
	    $oText = str_replace('Ğ', 'G', $oText);
	    $oText = str_replace('ş', 's', $oText);
	    $oText = str_replace('ı', 'i', $oText);
	    $oText = str_replace('ç', 'c', $oText);
	    $oText = str_replace('ü', 'u', $oText);
	    $oText = str_replace('ö', 'o', $oText);
	    $oText = str_replace('ğ', 'g', $oText);
	    
	    // Russian
	    $oText = str_replace('А', 'A', $oText);
	    $oText = str_replace('Б', 'B', $oText);
	    $oText = str_replace('В', 'V', $oText);
	    $oText = str_replace('Г', 'G', $oText);
	    $oText = str_replace('Д', 'D', $oText);
	    $oText = str_replace('Е', 'E', $oText);
	    $oText = str_replace('Ё', 'Yo', $oText);
	    $oText = str_replace('Ж', 'Zh', $oText);
	    $oText = str_replace('З', 'Z', $oText);
	    $oText = str_replace('И', 'I', $oText);
	    $oText = str_replace('Й', 'J', $oText);
	    $oText = str_replace('К', 'K', $oText);
	    $oText = str_replace('Л', 'L', $oText);
	    $oText = str_replace('М', 'M', $oText);
	    $oText = str_replace('Н', 'N', $oText);
	    $oText = str_replace('О', 'O', $oText);
	    $oText = str_replace('П', 'P', $oText);
	    $oText = str_replace('Р', 'R', $oText);
	    $oText = str_replace('С', 'S', $oText);
	    $oText = str_replace('Т', 'T', $oText);
	    $oText = str_replace('У', 'U', $oText);
	    $oText = str_replace('Ф', 'F', $oText);
	    $oText = str_replace('Х', 'H', $oText);
	    $oText = str_replace('Ц', 'C', $oText);
	    $oText = str_replace('Ч', 'Ch', $oText);
	    $oText = str_replace('Ш', 'Sh', $oText);
	    $oText = str_replace('Щ', 'Sh', $oText);
	    $oText = str_replace('Ъ', '', $oText);
	    $oText = str_replace('Ы', 'Y', $oText);
	    $oText = str_replace('Ь', '', $oText);
	    $oText = str_replace('Э', 'E', $oText);
	    $oText = str_replace('Ю', 'Yu', $oText);
	    $oText = str_replace('Я', 'Ya', $oText);
	    $oText = str_replace('а', 'a', $oText);
	    $oText = str_replace('б', 'b', $oText);
	    $oText = str_replace('в', 'v', $oText);
	    $oText = str_replace('г', 'g', $oText);
	    $oText = str_replace('д', 'd', $oText);
	    $oText = str_replace('е', 'e', $oText);
	    $oText = str_replace('ё', 'yo', $oText);
	    $oText = str_replace('ж', 'zh', $oText);
	    $oText = str_replace('з', 'z', $oText);
	    $oText = str_replace('и', 'i', $oText);
	    $oText = str_replace('й', 'j', $oText);
	    $oText = str_replace('к', 'k', $oText);
	    $oText = str_replace('л', 'l', $oText);
	    $oText = str_replace('м', 'm', $oText);
	    $oText = str_replace('н', 'n', $oText);
	    $oText = str_replace('о', 'o', $oText);
	    $oText = str_replace('п', 'p', $oText);
	    $oText = str_replace('р', 'r', $oText);
	    $oText = str_replace('с', 's', $oText);
	    $oText = str_replace('т', 't', $oText);
	    $oText = str_replace('у', 'u', $oText);
	    $oText = str_replace('ф', 'f', $oText);
	    $oText = str_replace('х', 'h', $oText);
	    $oText = str_replace('ц', 'c', $oText);
	    $oText = str_replace('ч', 'ch', $oText);
	    $oText = str_replace('ш', 'sh', $oText);
	    $oText = str_replace('щ', 'sh', $oText);
	    $oText = str_replace('ъ', '', $oText);
	    $oText = str_replace('ы', 'y', $oText);
	    $oText = str_replace('ь', '', $oText);
	    $oText = str_replace('э', 'e', $oText);
	    $oText = str_replace('ю', 'yu', $oText);
	    $oText = str_replace('я', 'ya', $oText);
	    
	    // Ukrainian
	    $oText = str_replace('Є', 'Ye', $oText);
	    $oText = str_replace('І', 'I', $oText);
	    $oText = str_replace('Ї', 'Yi', $oText);
	    $oText = str_replace('Ґ', 'G', $oText);
	    $oText = str_replace('є', 'ye', $oText);
	    $oText = str_replace('і', 'i', $oText);
	    $oText = str_replace('ї', 'yi', $oText);
	    $oText = str_replace('ґ', 'g', $oText);
	    
	    // Czech
	    $oText = str_replace('Č', 'C', $oText);
	    $oText = str_replace('Ď', 'D', $oText);
	    $oText = str_replace('Ě', 'E', $oText);
	    $oText = str_replace('Ň', 'N', $oText);
	    $oText = str_replace('Ř', 'R', $oText);
	    $oText = str_replace('Š', 'S', $oText);
	    $oText = str_replace('Ť', 'T', $oText);
	    $oText = str_replace('Ů', 'U', $oText);
	    $oText = str_replace('Ž', 'Z', $oText);
	    $oText = str_replace('č', 'c', $oText);
	    $oText = str_replace('ď', 'd', $oText);
	    $oText = str_replace('ě', 'e', $oText);
	    $oText = str_replace('ň', 'n', $oText);
	    $oText = str_replace('ř', 'r', $oText);
	    $oText = str_replace('š', 's', $oText);
	    $oText = str_replace('ť', 't', $oText);
	    $oText = str_replace('ů', 'u', $oText);
	    $oText = str_replace('ž', 'z', $oText);
	    
	    // Polish
	    $oText = str_replace('Ą', 'A', $oText);
	    $oText = str_replace('Ć', 'C', $oText);
	    $oText = str_replace('Ę', 'e', $oText);
	    $oText = str_replace('Ł', 'L', $oText);
	    $oText = str_replace('Ń', 'N', $oText);
	    $oText = str_replace('Ó', 'o', $oText);
	    $oText = str_replace('Ś', 'S', $oText);
	    $oText = str_replace('Ź', 'Z', $oText);
	    $oText = str_replace('Ż', 'Z', $oText);
	    $oText = str_replace('ą', 'a', $oText);
	    $oText = str_replace('ć', 'c', $oText);
	    $oText = str_replace('ę', 'e', $oText);
	    $oText = str_replace('ł', 'l', $oText);
	    $oText = str_replace('ń', 'n', $oText);
	    $oText = str_replace('ó', 'o', $oText);
	    $oText = str_replace('ś', 's', $oText);
	    $oText = str_replace('ź', 'z', $oText);
	    $oText = str_replace('ż', 'z', $oText);
	    
	    // Latvian
	    $oText = str_replace('Ā', 'A', $oText);
	    $oText = str_replace('Č', 'C', $oText);
	    $oText = str_replace('Ē', 'E', $oText);
	    $oText = str_replace('Ģ', 'G', $oText);
	    $oText = str_replace('Ī', 'i', $oText);
	    $oText = str_replace('Ķ', 'k', $oText);
	    $oText = str_replace('Ļ', 'L', $oText);
	    $oText = str_replace('Ņ', 'N', $oText);
	    $oText = str_replace('Š', 'S', $oText);
	    $oText = str_replace('Ū', 'u', $oText);
	    $oText = str_replace('Ž', 'Z', $oText);
	    $oText = str_replace('ā', 'a', $oText);
	    $oText = str_replace('č', 'c', $oText);
	    $oText = str_replace('ē', 'e', $oText);
	    $oText = str_replace('ģ', 'g', $oText);
	    $oText = str_replace('ī', 'i', $oText);
	    $oText = str_replace('ķ', 'k', $oText);
	    $oText = str_replace('ļ', 'l', $oText);
	    $oText = str_replace('ņ', 'n', $oText);
	    $oText = str_replace('š', 's', $oText);
	    $oText = str_replace('ū', 'u', $oText);
	    $oText = str_replace('ž', 'z', $oText);

	    $oText = htmlspecialchars_decode($oText);

	    return $oText; 
	}

}