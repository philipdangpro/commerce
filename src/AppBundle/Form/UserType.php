<?php

namespace AppBundle\Form;

use AppBundle\EventSubscriber\UserTypeSubscriber;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Form\Extension\Core\Type\CountryType;
use Symfony\Component\Form\FormEvent;

class UserType extends AbstractType
{
    //injecter la Request Stack
    private $requestStack;
    private $request;

    // masterRequest : cibler la requête principale
    public function __construct(RequestStack $requestStack)
    {
        $this->requestStack = $requestStack;
        $this->request = $requestStack->getMasterRequest();

    }

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('username', TextType::class, [
                'constraints' => [
                    new NotBlank([
                        'message' => 'username'
                    ])
                ]
            ])
            ->add('password', PasswordType::class, [
                'constraints' => [
                    new NotBlank([
                        'message' => 'password'
                    ])
                ]
            ])
            ->add('email', EmailType::class, [
                'constraints' =>[
                    new NotBlank([
                        'message' => 'email.notblank'
                    ]),
                    new Email([
                        'message' => 'email.incorrect'
                    ])
                ]
            ])
            ->add('address', TextType::class, [
                'constraints' => [
                    new NotBlank([
                        'message' => 'Adresse vide'
                    ])
                ]
            ])
            ->add('zipcode', TextType::class, [
                'constraints' => [
                    new NotBlank([
                        'message' => 'code postal vide'
                    ])
                ]
            ])
            ->add('city', TextType::class, [
                'constraints' => [
                    new NotBlank([
                        'message' => 'ville vide'
                    ])
                ]
            ])
            ->add('country', CountryType::class, [
                'placeholder' => '',
                'constraints' => [
                    new NotBlank([
                        'message' => 'pays vide'
                    ])
                ]
            ])
        ;

        /*
         * listener : écouter un seul événement
         * subscriber :écouter plusieurs événéments
         */

        //subscriber
        $subscriber = new UserTypeSubscriber($this->requestStack);
        $builder->addEventSubscriber($subscriber);


        // écouteur
        $builder->addEventListener(FormEvents::POST_SET_DATA, function (FormEvent $event){
            //récupérer la route
            $route = $this->request->get('_route');

            //tester la route
            //création de compte
            /*
             * l'événement renvoie
             *      $event->getData() qui contient la saisie du formulaire,
             *      $event->getForm() : $builder du formulaire
             *      $event->getForm()->getData() : données du formulaire (entité, modèle, ...)
             * */

            if($route === 'account.register'){
                //récupération de la saisie
                $data = $event->getData();
                //formulaire
                $form = $event->getForm();
                //données du formulaire
                $entity = $form->getData();

                //supprimer les champs du formulaire
                $form->remove('address');
                $form->remove('zipcode');
                $form->remove('city');
                $form->remove('country');
            }

        });

    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\User'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'appbundle_user';
    }


}
