<?php
// src/Acme/JobeetBundle/DataFixtures/ORM/CategoryFixtures.php

namespace Acme\JobeetBundle\DataFixtures\ORM;

use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Acme\JobeetBundle\Entity\Category;

// Si no quisieramos indicar el orden de carga de los fixtures con poner:
// class CategoriaFixtures implements FixtureInterface {}
// seria suficiente

class CategoryFixtures extends AbstractFixture implements OrderedFixtureInterface {

	public function load(ObjectManager $om) {
		$design = new Category();
		$design->setName('Design');

		$programming = new Category();
		$programming->setName('Programming');

		$manager = new Category();
		$manager->setName('Manager');

		$administrator = new Category();
		$administrator->setName('Administrator');

		$om->persist($design);
		$om->persist($programming);
		$om->persist($manager);
		$om->persist($administrator);

		$om->flush();

		// Nos permitimos tener una referencia para poder usarlo en otros fixtures
		$this->addReference('design', $design);
		$this->addReference('programming', $programming);
		$this->addReference('manager', $manager);
		$this->addReference('administrator',$administrator);

	}

	public function getOrder() {
		// Indicamos el orden de carga, para tener margen para posteriores fixtures
		// En vez de poner del 1 al 3 podemos poner 10, 20, 30... para tener suficiente hueco
		// para poder añadir más fixtures en un futuro
		return 10;
	}
}