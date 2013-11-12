<?php

namespace Acme\JobeetBundle\Utils;

class Jobeet
{
	static public function slugify($text)
	{
		// Replace all non letters or digits by '-'
		$text = preg_replace('#[^\\pL\d]+#u', '-', $text);

		// Trim 
		$text = trim($text, '-');

		// transliterate
		if (function_exists('iconv'))
		{
			$text = iconv('utf-8', 'us-ascii//TRANSLIT', $text);
		}

		// lowercase
		$text = strtolower($text);

		// remove unwanted characters
		$text = preg_replace('#[^-\w]+#', '', $text);

		if(empty($text))
			return 'n-a';

		return $text;
	}
}