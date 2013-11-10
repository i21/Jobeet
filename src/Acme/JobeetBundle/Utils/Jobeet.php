<?php

namespace Acme\JobeetBundle\Utils;

class Jobeet
{
	static public function slugify($text)
	{
		// Replace all non letters or digits by '-'
		$text = preg_replace('/\W+/', '-', $text);

		// Trim and lowercase
		$text = strtolower(trim($text, '-'));

		return $text;
	}
}