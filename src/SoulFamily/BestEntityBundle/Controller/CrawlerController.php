<?php

namespace SoulFamily\BestEntityBundle\Controller;

use SoulFamily\BestEntityBundle\Entity\Category;
use SoulFamily\BestEntityBundle\Entity\EntityDescription;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Goutte\Client;
use Symfony\Component\DomCrawler\Crawler;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;


class CrawlerController extends Controller
{
    const URL_BOOKS = 'http://thegreatestbooks.org/';
    const URL_FILMS = 'http://www.imdb.com/list/ls000049962/';
    const URL_PLACES = 'http://www.absolutevisit.com/top-100-places-in-the-world';

    /**
     * @Route("/crawl", name="crawlForm")
     */
    public function indexAction(Request $request)
    {
        $defaultData = array('message' => 'Crawl data');
        $form = $this->createFormBuilder($defaultData)
            ->add('crawlBooks', SubmitType::class)
            ->add('crawlFilms', SubmitType::class)
            ->add('crawlPlaces', SubmitType::class)
            ->getForm();

        $form->handleRequest($request);

        if ($form->getClickedButton() && $form->isSubmitted()) {
            return $this->redirectToRoute($form->getClickedButton()->getName());
        }
        return $this->render('admin/crawler.html.twig',
            ['form' => $form->createView()]
        );
    }


    /**
     * @Route("/crawlBooks", name="crawlBooks")
     */
    public function crawlBooksAction()
    {
        $books = $this->crawl(self::URL_BOOKS, 'html .container .col-sm-7 .list-body h4 a');
        $this->saveParsedData($books, Category::BOOKS);

        $this->addFlash('success', 'Books have been crawled successfully');
        return $this->redirectToRoute('crawlForm');

    }

    /**
     * @Route("/crawlFilms", name="crawlFilms")
     */
    public function crawlFilmsAction()
    {
        $films = $this->crawl(self::URL_FILMS, 'html .lister-list .lister-item-header a');
        $this->saveParsedData($films, Category::FILMS);

        $this->addFlash('success', 'Films have been crawled successfully');
        return $this->redirectToRoute('crawlForm');
    }

    /**
     * @Route("/crawlPlaces", name="crawlPlaces")
     */
    public function crawlPlacesAction()
    {
        $films = $this->crawl(self::URL_PLACES, 'html #tabarea1 tr a');
        $this->saveParsedData($films, Category::PLACES);

        $this->addFlash('success', 'Places have been crawled successfully');
        return $this->redirectToRoute('crawlForm');
    }

    /**
     * @param string $url
     * @param string $htmlIdentifier
     * @return array
     */
    public function crawl($url, $htmlIdentifier)
    {
        $client = new Client();
        $client->setHeader('Accept-Language', 'en');
        $crawler = $client->request('GET', $url);
        $data = [];
        $crawler->filter($htmlIdentifier)->each(function ($node) use (&$data) {
            $data[] = ['name' => $node->text(), 'link' => $node->link()->getUri()];
        });

        return $data;
    }

    /**
     *
     * @param array $data
     * @param Category $category
     */
    public function saveParsedData($data, $category)
    {
        $em = $this->getDoctrine()->getManager();
        $category = $em->getRepository(Category::class)->find($category);

        foreach ($data as $crawling) {
            $book = new EntityDescription($category);
            $book->setName($crawling['name']);
            $book->setLink($crawling['link']);

            $em->persist($book);
        }

        $em->flush();
    }


}
