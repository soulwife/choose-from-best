<?php

namespace SoulFamily\BestEntityBundle\Controller;

use SoulFamily\BestEntityBundle\Entity\Category;
use SoulFamily\BestEntityBundle\Entity\EntityDescription;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

class AppController extends Controller
{
    /**
     * @Route("/", name="homepage")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();
        $user = $this->getUser();
        $categories = $em->getRepository(Category::class)->findAll();
        $bestRandomChoices = [];

        array_walk($categories, function($category) use ($em, &$bestRandomChoices) {
            $categoryEntities = $em->getRepository(EntityDescription::class)->findBy(['category' => $category]);
            $bestRandomChoices[$category->getId()] = $categoryEntities ? $categoryEntities[array_rand($categoryEntities)] : null;
        });

        return $this->render('app/index.html.twig', ['bestRandomChoices' => $bestRandomChoices]);
    }
}
