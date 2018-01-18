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
            ['/fr/admin/', 'Dashboard'],
            ['/en/admin/', 'Dashboard'],
        ];
    }

    /**
     * @dataProvider listRoutes
     */
    public function testIndex(string $url, string $title)
    {
        //$client vient simuler les actions d'un client, simule un nvaigateur

        $client = static::createClient([], [
            'PHP_AUTH_USER' => 'admin',
            'PHP_AUTH_PW'   => 'passw0rd',
        ]);

        //vient tester le DOM, dessous, on écrit : 'le client clique sur localhost:8000/
        $crawler = $client->request('GET', $url);

        // 1er arg : résultat attendu, contenu à tester
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertTrue($crawler->filter('h1')->count() === 2);

//        $this->assertContains($title, $crawler->filter('.container > h1'));


//        $this->assertFalse($crawler->filter('.container > .row:nth-of-type(2) h1')->count() === 1);

    }

}
