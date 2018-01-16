<?php

namespace Tests\AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class CategoryControllerTest extends WebTestCase
{

    /*
        * @dataProvider : fournisseur de données
        * doit retourner un array de données
        * les entrées du tableau deviennent des paramètres dans la fonction callback
     */
    public function listRoutes(){
        return [
            ['/fr/category/categorie1'],
            ['/en/category/categorie1'],
        ];
    }

    /**
     * @dataProvider listRoutes
     */
    public function testIndex(string $url)
    {
        //$client vient simuler les actions d'un client, simule un nvaigateur
        $client = static::createClient();

        //vient tester le DOM, dessous, on écrit : 'le client clique sur localhost:8000/
        $crawler = $client->request('GET', $url);

        // 1er arg : résultat attendu, contenu à tester
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
//        $this->assertContains($title, $crawler->filter('#phpunit')->text());



    }


}
