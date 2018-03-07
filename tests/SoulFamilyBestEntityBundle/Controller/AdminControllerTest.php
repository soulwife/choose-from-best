<?php

namespace SoulFamily\BestEntityBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use SoulFamily\BestEntityBundle\Entity\Category;
use Symfony\Component\HttpFoundation\Response;

class AdminControllerTest extends WebTestCase
{

    /**
     * @dataProvider getUrlsForRegularUsers
     */
    public function testAccessDeniedForRegularUsers($httpMethod, $url)
    {
        $client = static::createClient([], [
            'PHP_AUTH_USER' => 'test',
            'PHP_AUTH_PW' => 'test',
        ]);

        $client->request($httpMethod, $url);
        $this->assertSame(Response::HTTP_FORBIDDEN, $client->getResponse()->getStatusCode());
    }

    public function getUrlsForRegularUsers()
    {
        yield ['GET', '/admin'];
        yield ['GET', '/admin/category/1'];
        yield ['GET', '/admin/category/1/edit'];
        yield ['GET', '/admin/category/new'];
        yield ['POST', '/admin/category/1/delete'];
    }


    public function testIndex()
    {
        $client = static::createClient([], [
            'PHP_AUTH_USER' => 'adminuser',
            'PHP_AUTH_PW' => '123456',
        ]);

        $crawler = $client->request('GET', '/admin');

        $this->assertEquals(Response::HTTP_OK, $client->getResponse()->getStatusCode());

        $this->assertGreaterThanOrEqual(
            1,
            $crawler->filter('body #main .card')->count(),
            'The backend homepage displays all the available categories.'
        );

    }

    /**
     * This test changes the database contents by creating a new blog post. However,
     * thanks to the DAMADoctrineTestBundle and its PHPUnit listener, all changes
     * to the database are rolled back when this test completes. This means that
     * all the application tests begin with the same database contents.
     */
    public function testNewCategory()
    {
        $categoryName = 'CategoryTest1';
        $categoryUrl = 'test url';
        $categoryImgUrl= 'http://www.soulwish.info/images/blog-cover.jpg';
        $categoryHtmlPath= 'test path';

        $client = static::createClient([], [
            'PHP_AUTH_USER' => 'adminuser',
            'PHP_AUTH_PW' => '123456',
        ]);
        $crawler = $client->request('GET', '/admin/category/new');
        $form = $crawler->selectButton('Submit')->form([
            'category[name]' => $categoryName,
            'category[url]' => $categoryUrl,
            'category[imgUrl]' => $categoryImgUrl,
            'category[htmlCrawlPath]' => $categoryHtmlPath,
        ]);
        $client->submit($form);

        $this->assertSame(Response::HTTP_FOUND, $client->getResponse()->getStatusCode());

        $category = $client->getContainer()->get('doctrine')->getRepository(Category::class)->findOneBy([
            'name' => $categoryName,
        ]);
        $this->assertNotNull($category);
        $this->assertSame($categoryUrl, $category->getUrl());
        $this->assertSame($categoryImgUrl, $category->getImgUrl());
        $this->assertSame($categoryHtmlPath, $category->getHtmlCrawlPath());
        unlink($client->getContainer()->get('_soulfamily.upload_external_file')->getFilePath($category->getName()));
    }

    public function testShowCategory()
    {
        $client = static::createClient([], [
            'PHP_AUTH_USER' => 'adminuser',
            'PHP_AUTH_PW' => '123456',
        ]);

        $client->request('GET', '/admin/category/1');

        $this->assertEquals(Response::HTTP_OK, $client->getResponse()->getStatusCode());
    }

    public function testEditCategory()
    {
        $newCategoryName = 'CategoryTest'.mt_rand();

        $client = static::createClient([], [
            'PHP_AUTH_USER' => 'adminuser',
            'PHP_AUTH_PW' => '123456',
        ]);

        $crawler = $client->request('GET', '/admin/category/1/edit');
        $form = $crawler->selectButton('Submit')->form([
            'category[name]' => $newCategoryName,
        ]);
        $client->submit($form);

        $this->assertSame(Response::HTTP_FOUND, $client->getResponse()->getStatusCode());

        /** @var Category $category */
        $category = $client->getContainer()->get('doctrine')->getRepository(Category::class)->find(1);
        $this->assertSame($newCategoryName, $category->getName());
        unlink($client->getContainer()->get('_soulfamily.upload_external_file')->getFilePath($category->getName()));
    }

    public function testAdminDeleteCategory()
    {
        $client = static::createClient([], [
            'PHP_AUTH_USER' => 'adminuser',
            'PHP_AUTH_PW' => '123456',
        ]);

        $crawler = $client->request('GET', '/admin');
        $container = $client->getContainer();

        //create img file for successfull unlink
        $category = $container->get('doctrine')->getRepository(Category::class)->find(1);
        $container->get('_soulfamily.upload_external_file')->copyExternalFile('http://www.soulwish.info/images/blog-cover.jpg', $category->getName());

        $client->submit($crawler->filter('#delete-form-1')->form());

        $this->assertSame(Response::HTTP_FOUND, $client->getResponse()->getStatusCode());

        $category = $container->get('doctrine')->getRepository(Category::class)->find(1);
        $this->assertNull($category);
    }
}
