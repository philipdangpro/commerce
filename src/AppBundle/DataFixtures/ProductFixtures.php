<?php
/**
 * Created by PhpStorm.
 * User: wabap2-13
 * Date: 12/01/18
 * Time: 12:22
 */

namespace  AppBundle\DataFixtures;

use AppBundle\Entity\Product;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

class ProductFixtures extends Fixture implements DependentFixtureInterface
{
    private $locales = ['en' => 'en_US', 'fr' => 'fr_FR'];

    public function load(ObjectManager $manager)
    {
        for($i = 0; $i < 10; $i++) {
            $faker = \Faker\Factory::create();

            // cibler les propriétés non traduites
            $entity = new Product();

            $entity->setPrice($faker->randomFloat(2,1,999.99));
            $entity->setStock($faker->numberBetween(0, 100));
            /*
             * image
             *      cibler la racine du projet
             *      le dossier ciblé doit exister
             */
            $entity->setImage(
                $faker->imageUrl(
                    $faker->image(
                        'web/img/product',
                        '400',
                        '400',
                        'cats',
                        'false'
                    )
                )
            );


            // associer le produit à une catégorie
            $entity->setCategory(
                $this->getReference($faker->numberBetween(37,40))
            );

            foreach($this->locales as $key => $value) {
                // use the factory to create a Faker\Generator instance
                $faker = \Faker\Factory::create($value);

                //créer des valeurs traduits pour les propriétés
                $name = ($key === 'fr') ? 'produit' : 'product';
                //$description = ($value === 'fr') ? 'description' : 'description';

                $description = $faker->realText();

                // méthode translate est fourni par doctrine behaviors
                $entity->translate($key)->setName($name . $i);
                $entity->translate($key)->setDescription($description);
            }

            //méthode mergeNewTranslations est fourni par doctrine behaviors
            $entity->mergeNewTranslations();

            $manager->persist($entity);
        }

        $manager->flush();
    }

    public function getDependencies()
    {
        return array(
            CategoryFixtures::class,
        );
    }
}