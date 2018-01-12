<?php

namespace AppBundle\DataFixtures;

use AppBundle\Entity\Category;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
//use Doctrine\ORM\EntityManager;

class CategoryFixtures extends Fixture
{
    private $locales = ['en', 'fr'];

    public function load(ObjectManager $manager)
    {
        foreach ($this->locales as $key => $value){
            for ($i = 0; $i < 4; $i++){
                $entity = new Category();
                $name = ($value === 'fr') ? 'catégorie' : 'category';
                $description = ($value === 'fr') ? 'descriptionenglish' : 'description';

                $entity->translate($value)->setName($name . $i);
                $entity->translate($value)->setDescription($description. $i);

                // In order to persist new translations, call mergeNewTranslations method, before flush
                //méthode translate est fournie par doctrine behaviours
                $entity->mergeNewTranslations();

                $manager->persist($entity);

            }
        }

        $manager->flush();
    }



}