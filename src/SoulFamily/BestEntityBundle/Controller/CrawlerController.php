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
    /**
     * @Route("/crawl", name="crawlForm")
     */
    public function indexAction(Request $request)
    {
        $defaultData = array('message' => 'Crawl data');
        $form = $this->createFormBuilder($defaultData)
            ->add('crawlBooks', SubmitType::class)
         //   ->add('crawlFilms', SubmitType::class)
            ->getForm();

        $form->handleRequest($request);

        if ($form->getClickedButton() && $form->isSubmitted()) {
            return $this->redirectToRoute($form->getClickedButton()->getName());
        }
        return $this->render('SoulFamilyBestEntityBundle:Crawler:index.html.twig',
            ['form' => $form->createView()]
        );
    }


    /**
     * @Route("/crawlBooks", name="crawlBooks")
     */
    public function crawlBooksAction()
    {
        $url = "http://thegreatestbooks.org/";
        $client = new Client();
        $crawler = $client->request('GET', $url);
        $books = [];
        $crawler->filter('html .container .col-sm-7 .list-body h4 a')->each(function ($node) use (&$books) {
            $books[] = ['name' => $node->text(), 'link' => $node->link()->getUri()];
        });

        $em = $this->getDoctrine()->getManager();
        $category =  $em->getRepository(Category::class)->find(Category::BOOKS);

        foreach ($books as $crawlingBook) {
            $book = new EntityDescription($category);
            $book->setName($crawlingBook['name']);
            $book->setLink($crawlingBook['link']);

            $em->persist($book);
        }

        $em->flush();

        return $this->redirectToRoute('crawlForm');

    }

}
