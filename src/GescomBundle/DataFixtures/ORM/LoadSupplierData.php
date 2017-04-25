<?php
/**
 * Created by PhpStorm.
 * User: ubuntu
 * Date: 24/04/17
 * Time: 13:21
 */

namespace GescomBundle\DataFixtures\ORM;


use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Faker\Factory;
use GescomBundle\Entity\Supplier;

class LoadSupplierData extends AbstractFixture implements OrderedFixtureInterface
{

    const MAX_SUPPLIERS = 100;
    /**
     * Load data fixtures with the passed EntityManager
     *
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        $faker = Factory::create('fr_FR');
        $faker->seed(12345);
        for ($i=0; $i<self::MAX_SUPPLIERS; $i++){
            $supplier = new Supplier();
            $supplier->setName($faker->unique()->company);
            $supplier->setAddress($faker->address);
            $supplier->setMail($faker->unique()->companyEmail);
            $supplier->setPostalCode(rand(10000, 99999));
            $supplier->setTown($faker->city);
            $supplier->setDeliveryTime(rand(1, 30));
            $supplier->setScore(rand(1, 5));
            $supplier->setWeb($faker->unique()->url);
            $supplier->setSiret($this->getRandomSiret());
            $manager->persist($supplier);
            $this->setReference("suppliers_" . $i, $supplier);
        }
        $manager->flush();
    }

    /**
     * Get the order of this fixture
     *
     * @return integer
     */
    public function getOrder()
    {
        return 2;
    }

    private function getRandomSiret()
    {
        return  rand(100, 999) . " " .
                rand(100, 999) . " " .
                rand(100, 999) . " " .
                rand(10000, 99999);
    }
}