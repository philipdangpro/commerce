<?php

namespace AppBundle\DataFixtures;

use AppBundle\Entity\Category;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class CategoryFixtures extends Fixture
{

    private $locales = ['en' => 'en_US', 'fr' => 'fr_FR'];

    public function load(ObjectManager $manager)
    {
        for($i = 0; $i < 4; $i++){

            // remplir les propriétés non traduites
            $entity = new Category();

            // remplir les propriétés traduites
            foreach($this->locales as $key => $value){
                // faker
                $faker = \Faker\Factory::create($value);

                // créer des valeurs traduites pour les propriétés
                $name = ($key === 'fr') ? 'catégorie' : 'category';
                //$description = ($value === 'fr') ? 'description' : 'description';
                $description = $faker->realText();

                // méthode translate est fournie par doctrine behaviors
                $entity->translate($key)->setName($name . $i);
                $entity->translate($key)->setDescription($description);
            }

            // méthode mergeNewTranslations est fournie par doctrine behaviors
            $entity->mergeNewTranslations();

            // stocker les catégories en mémoire
            $this->addReference("category$i", $entity);

            $manager->persist($entity);
        }

        $manager->flush();
    }

}














