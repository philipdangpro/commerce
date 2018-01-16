<?php

namespace Tests\AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class HomepageControllerTest extends WebTestCase
{

    /*
        * @dataProvider : fournisseur de données
        * doit retourner un array de données
        * les entrées du tableau deviennent des paramètres dans la fonction callback
     */
    public function listRoutes(){
        return [
            ['/fr/', 'Catégories'],
            ['/en/', 'Category'],
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
        $this->assertContains($title, $crawler->filter('#phpunit')->text());

        //compter le nombre de bouton catégories
        $this->assertGreaterThan(0, $crawler->filter('.btn')->count());

        //compter les 3 produits aléatoires
        $this->assertEquals(3, $crawler->filter('.container > .row .col-sm-4')->count());
        $this->assertFalse($crawler->filter('.container > .row:nth-of-type(3) .col-sm-4')->count() === 4);

    }


}
