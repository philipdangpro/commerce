<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CategoryType extends AbstractType
{

    private $locales;
    private $doctrine;

    public function __construct(array $locales)
    {
            $this->locales = $locales;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {

        //récupération des données du formualaire
        $entity = $builder->getData();

        /*
         * mapped : permet de définir si un champ est relié à une propriété de l'entité; par défaut true
         * data : permet de définir une valeur au champ
         */

        //créer plusieurs champs selon les langues, càd 2 champs en et fr pour nom
        foreach ( $this->locales as $key => $value)
        {
            //champ name
            $builder
                ->add("name_" . $value, TextType::class, [
                    'mapped' => false,
                    'data' => $entity->translate($value)->getName()
                ])
                // champ description
                ->add("description_" . $value, TextType::class, [
                    'mapped' => false,
                    'data' => $entity->translate($value)->getDescription()
                ])
            ;

            //écouteur : récupérer la saisie et de fusionner les traductions
            $builder->addEventListener(FormEvents::PRE_SUBMIT, function(FormEvent $event){
                //on récupère les data saisie du formulaire
                $data = $event->getData();

                //donnéesdu formulaire
                $entity = $event->getForm()->getData();

                foreach ($this->locales as $key => $value){
                    //méthode translate est fournie par doctrine behaviors,
                    //ici c'est un tableau car on utilise des attributs custom qui vont être utilisée que lors de cet événement
                    $entity->translate($value)->setName($data["name_" . $value]);
                    $entity->translate($value)->setDescription($data["description_" . $value]);
                }

                //méthode mergeNewTranslations est fournie par doctrine behaviors
                $entity->mergeNewTranslations();

            });
        }

    }


    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\Category'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'appbundle_category';
    }


}
