<?php

namespace AppBundle\DataFixtures;

use AppBundle\Entity\Product;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

class ProductFixtures extends Fixture implements DependentFixtureInterface
{

    private $locales = ['en' => 'en_US', 'fr' => 'fr_FR'];

    public function load(ObjectManager $manager)
    {
        for($i = 0; $i < 50; $i++){

            // faker
            $faker = \Faker\Factory::create();

            // remplir les propriétés non traduites
            $entity = new Product();
            $entity->setPrice($faker->randomFloat(2, 1, 999.99));
            $entity->setStock($faker->numberBetween(0, 100));

            /*
             * image
             *      cible la racine du projet
             *      le dossier ciblé doit exister
             */
            $entity->setImage(
                $faker->image('web/img/product', 400, 400, 'technics', false)
            );

            // associer le produit à une catégorie
            $randomCategory = $faker->numberBetween(0, 3);
            $entity->setCategory(
                $this->getReference("category" . $randomCategory)
            );

            // remplir les propriétés traduites
            foreach($this->locales as $key => $value){
                // faker
                $faker = \Faker\Factory::create($value);

                // créer des valeurs traduites pour les propriétés
                $name = ($key === 'fr') ? 'produit' : 'product';
                $description = $faker->realText();

                // méthode translate est fournie par doctrine behaviors
                $entity->translate($key)->setName($name . $i);
                $entity->translate($key)->setDescription($description);
            }

            // méthode mergeNewTranslations est fournie par doctrine behaviors
            $entity->mergeNewTranslations();

            $manager->persist($entity);
        }

        $manager->flush();
    }

    public function getDependencies()
    {
        return [
            CategoryFixtures::class,
        ];
    }

}














