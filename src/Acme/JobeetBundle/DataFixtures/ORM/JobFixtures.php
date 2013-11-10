<?php
// src/Acme/JobeetBundle/DataFixtures/ORM/JobFixtures.php

namespace Acme\JobeetBudnle\DataFixtures\ORM;

use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Acme\JobeetBundle\Entity\Job;

class JobFixtures extends AbstractFixture implements OrderedFixtureInterface{

	public function load(ObjectManager $om) {
		$job1 = new Job();
		$job1->setCategory($this->getReference('programming'));
		$job1->setType('full-time');
		$job1->setCompany('Sensio Labs');
		$job1->setLogo('sensio-labs.gif');
		$job1->setUrl('http://www.sensiolabs.com');
		$job1->setPosition('Web Developer');
		$job1->setLocation('Paris, France');
		$job1->setDescription("You've already developed websites with symfony
			and you want to work with Open-Source technologies. You have a
			minimum of 3 years experience in web development with PHP or Java
			and you wish to participate to development of Web 2.0 sites using
			the best frameworks available.");
		$job1->setHowToApply("Send your resume to fabien.potencier [AT] sensio [DOT] com");
		$job1->setIsPublic(true);
		$job1->setIsActivated(true);
		$job1->setToken('job_sensio_labs');
		$job1->setEmail('Job@example.com');
		$job1->setExpiresAt(new \DateTime('2008-10-10'));

		$job2 = new Job();
		$job2->setCategory($this->getReference('design'));
		$job2->setType('part-time');
		$job2->setCompany('Extreme Sensio');
		$job2->setLogo('extreme-sensio.gif');
		$job2->setUrl('http://www.extreme-sensio.com');
		$job2->setPosition('Web Developer');
		$job2->setLocation('Paris, France');
		$job2->setDescription("Lorem ipsum dolor sit amet, consectetur
	          adipisicing elit, sed do eiusmod tempor incididunt ut labore 
	          et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud 
	          exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. 
	          Duis aute irure dolor in reprehenderit in.");
		$job2->setHowToApply("Send your resume to fabien.potencier [at] sensio [DOT] com");
		$job2->setIsPublic(true);
		$job2->setIsActivated(true);
		$job2->setToken('job_extreme_sensio');
		$job2->setEmail('ob@example.com');
		$job2->setExpiresAt(new \DateTime('2008-10-10'));

		$om->persist($job1);
		$om->persist($job2);

		$om->flush();
	}

	public function getOrder() {
		return 20;
	}
}