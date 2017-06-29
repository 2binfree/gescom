<?php
/**
 * Created by PhpStorm.
 * User: ubuntu
 * Date: 24/04/17
 * Time: 13:22
 */

namespace GescomBundle\DataFixtures\ORM;


use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Faker\Factory;
use GescomBundle\Entity\Product;
use GescomBundle\Entity\ProductSupplier;

class LoadProductData extends AbstractFixture implements OrderedFixtureInterface
{

    const MAX_PRODUCT = 500;
    /**
     * Load data fixtures with the passed EntityManager
     *
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        $faker = Factory::create('fr_FR');
        $faker->seed(1234);
        for ($i=0; $i<self::MAX_PRODUCT; $i++){
            $product = new Product();
            $product->setName($faker->unique()->words(2, true));
            $product->setDescription($faker->sentences(3, true));
            $product->setCategory($this->getReference("categories_" . rand(0, 27)));
            $suppliersTotal = rand(1, 3);
            $supplierStartNumber = rand(0, LoadSupplierData::MAX_SUPPLIERS - $suppliersTotal);
            for ($j=1; $j<=$suppliersTotal; $j++){
                $productSupplier = new ProductSupplier();
                $productSupplier->setProduct($product);
                $productSupplier->setSupplier($this->getReference("suppliers_" . $supplierStartNumber));
                $supplierStartNumber++;
                $manager->persist($productSupplier);
            }
            $manager->persist($product);
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
        return 3;
    }
}