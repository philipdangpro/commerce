<?php

namespace AppBundle\Form;

use AppBundle\Entity\Category;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Image;
use Symfony\Component\Validator\Constraints\Type;

class ProductType extends AbstractType
{
    private $locales;
    private $requestStack;

    public function __construct(RequestStack $requestStack, array $locales)
    {
        $this->locales = $locales;
        $this->requestStack = $requestStack;
    }

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $entity = $builder->getData();
        dump($entity);
//        dump()

        dump($this->requestStack);
//        exit;

        //propriétés non localisées
        $builder
            ->add('price', MoneyType::class, [
                'constraints' => [
                    new NotBlank([
                        'message' => 'veuillez renseigner le prix'
                    ]),
//                    new Type([
//                        'type' => 'float',
//                        'message' => 'veuillez renseigner un décimal'
//                    ]),
//                    new RegexType

                ],
//                'invalid_message' => 'toto'
            ])
            ->add('stock', NumberType::class)
//            ->add("category", TextType::class, [])
            ->add('category', EntityType::class, [
                'class' => Category::class,
                'choice_label' => 'translations['. $this->requestStack->getMasterRequest()->getLocale() . '].name',
                'placeholder' => 'veuillez sélectionner une catégorie'
            ])
        ;

        //definition des contraintes pour image
        $imageConstraints = [
            new Image([
                'mimeTypes' => ['image/jpeg', 'image/png'],
                'mimeTypesMessage' => ["sefserser erreor imlage"]
            ])
        ];

        if(!$entity->getId()){
            $imageConstraints[] = new NotBlank([
                'message' => 'veuillez choisir un fichier'
            ]);
        }

        $builder->add('image', FileType::class, [
            'data_class' => null,
            'constraints' => $imageConstraints
        ]);

        //propriétés localisées
        foreach ( $this->locales as $key => $value)
        {
            $builder
                ->add("name_" . $value, TextType::class, [
                    'mapped' => false,
                    'data' => $entity->translate($value)->getName()
                ])
                ->add("description_" . $value, TextType::class, [
                    'mapped' => false,
                    'data' => $entity->translate($value)->getDescription()
                ])
            ;

            $builder->addEventListener(FormEvents::PRE_SUBMIT, function(FormEvent $event){
                $data = $event->getData();
                $entity = $event->getForm()->getData();

                foreach ($this->locales as $key => $value){
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
            'data_class' => 'AppBundle\Entity\Product'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'appbundle_product';
    }


}
