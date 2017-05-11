<?php
/**
 * Created by PhpStorm.
 * User: ubuntu
 * Date: 24/04/17
 * Time: 13:20
 */

namespace GescomBundle\DataFixtures\ORM;


use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Faker\Factory;
use GescomBundle\Entity\Category;

class LoadCategoryData extends AbstractFixture implements OrderedFixtureInterface
{

    /**
     * Load data fixtures with the passed EntityManager
     *
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        $faker = Factory::create('fr_FR');
        $faker->seed(1234);

        $categoriesName = [
            "Ordinateur PC",
            "Ordinateur PC portable",
            "Tablette",
            "Smartphone",
            "Imprimante",
            "Moniteur",
            "Consommables",
            "Réseau",
            "Connectique",
        ];

        foreach ($categoriesName as $key => $categoryName){
            $category = new Category();
            $category->setName($categoryName);
            $category->setDescription($faker->sentences(3, true));
            $manager->persist($category);
            $this->setReference("categories_" . $key, $category);
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
        return 1;
    }
}