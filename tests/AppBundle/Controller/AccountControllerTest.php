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
            ['/fr/register', "S'enregistrer"],
            ['/en/register', "Sign up"],
        ];
    }


    /**
     * @dataProvider listRoutes
     */
    public function testIndex(string $url, string $title)
    {
        //$client vient simuler les actions d'un client, simule un nvaigateur
        $client = static::createClient();

        //vient tester le DOM, dessous, on écrit : 'le client clique sur localhost:8000/
        $crawler = $client->request('GET', $url);

        // 1er arg : résultat attendu, contenu à tester
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertContains($title, $crawler->filter('.container > .row:nth-of-type(2) h1')->text());

    }


}
