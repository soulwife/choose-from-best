<?php

namespace SoulFamily\BestEntityBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use SoulFamily\BestEntityBundle\Entity\Category;
use SoulFamily\BestEntityBundle\Entity\EntityDescription;
use Symfony\Component\HttpFoundation\Response;

class AppControllerTest extends WebTestCase
{
    public function testIndex()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/');

        $this->assertGreaterThanOrEqual(
            1,
            $crawler->filter('body #main .carousel-inner .carousel-item')->count(),
            'The homepage displays all the available categories.'
        );

        $this->assertEquals(Response::HTTP_OK, $client->getResponse()->getStatusCode());

    }

    public function testShowCategory()
    {
        $client = static::createClient();

        $category = $client->getContainer()->get('doctrine')->getRepository(Category::class)->findOneBy(['id' => 2]);
        $crawler = $client->request('GET', '/categories/' . $category->getName());

        $categoryEntities = $client->getContainer()->get('doctrine')->getRepository(EntityDescription::class)->findBy([
            'category' => $category->getId(),
        ]);

        $this->assertEquals(
            count($categoryEntities),
            $crawler->filter('body #main .entities-list')->children()->count(),
            'The category page displays right amount of entities'
        );

        $this->assertEquals(Response::HTTP_OK, $client->getResponse()->getStatusCode());
    }

    /**
     * @group todoit
     */
    public function testShowCategoryForUser()
    {
        $client = static::createClient([], [
            'PHP_AUTH_USER' => 'test',
            'PHP_AUTH_PW' => 'test',
        ]);

        $category = $client->getContainer()->get('doctrine')->getRepository(Category::class)->findOneBy(['id' => 2]);
        $crawler = $client->request('GET', '/categories/' . $category->getName());

        $categoryEntities = $client->getContainer()->get('doctrine')->getRepository(EntityDescription::class)->findBy([
            'category' => $category->getId(),
        ]);

        $this->assertEquals(
            count($categoryEntities),
            $crawler->filter('body #main .entities-list')->children()->count(),
            'The category page displays right amount of entities'
        );

        //TODO tick one entity checkbox, click 'read' button, check if it was saved for user
        //$firstItemCheckbox = $crawler->filter('body #main .entities-list')->first()->filter('.entities-list-checkbox');
        //$firstItemCheckbox->tick();
        // $client->click($crawler->filter('body #main .read-list-btn'));

        $this->assertEquals(Response::HTTP_OK, $client->getResponse()->getStatusCode());
    }

}
