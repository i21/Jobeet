<?php 
namespace Acme\JobeetBundle\Tests\Utils;

use Acme\JobeetBundle\Utils\Jobeet;

class JobeetTest extends \PHPUnit_Framework_TestCase
{
	public function testSlugify()
	{
		if (function_exists('iconv')) 
		{
			$this->assertEquals('developpeur-web', Jobeet::slugify('Développeur Web'));
		}
	}
}