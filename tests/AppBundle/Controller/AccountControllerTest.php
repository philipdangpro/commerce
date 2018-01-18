<?php

namespace Tests\AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class AccountControllerTest extends WebTestCase
{

    /*
        * @dataProvider : fournisseur de données
        * doit retourner un array de données
        * les entrées du tableau deviennent des paramètres dans la fonction callback
     */
    public function listRoutes(){
        return [
            ['/fr/register', "S'enregistrer", 'FR'],
            ['/en/register', "Sign up", 'EN'],
        ];
    }


    /**
     * @dataProvider listRoutes
     */
    public function testRoutes(string $url, string $title, string $locale)
    {
        //$client vient simuler les actions d'un client, simule un nvaigateur
        $client = static::createClient();

        //suivre les redirections
        $client->followRedirects();

        //vient tester le DOM, dessous, on écrit : 'le client clique sur localhost:8000/
        $crawler = $client->request('GET', $url);

        //assert : tests
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertFalse($crawler->filter('.container > .row:nth-of-type(2) h1')->count() === 4);
        /*
         * données du formulaire
         *      array associatif
         *         clé : name du champ de saisie
         *         valeur : valeur saisie
         */

        $formData = [
            'appbundle_user[username]' => 'user' . $locale . time(),
            'appbundle_user[password]' => 'user',
            'appbundle_user[email]' => 'user' . time() . '@user.com',
        ];

        // sélectionner le formulaire par le bouton submit
        $form = $crawler
            ->selectButton('Valider')
            ->form($formData)
        ;

        //soumission du formulaire
        $crawler = $client->submit($form);
//        dump($crawler);

        $this->assertEquals(1, $crawler->filter('.alert.alert-success')->count());


    }



}
