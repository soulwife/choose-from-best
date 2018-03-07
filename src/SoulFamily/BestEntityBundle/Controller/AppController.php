<?php

namespace SoulFamily\BestEntityBundle\Controller;

use SoulFamily\BestEntityBundle\Entity\Category;
use SoulFamily\BestEntityBundle\Entity\EntityDescription;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

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
        $bestRandomChoices = $userEntities = [];
        if ($user) {
            $userEntities = $user->getBestEntities()->toArray();
        }

        array_walk($categories, function($category) use ($em, &$bestRandomChoices, $userEntities) {
            $categoryEntities = $em->getRepository(EntityDescription::class)->findBy(['category' => $category]);
            if ($categoryEntities) {
                $categoryEntitiesWithoutUserEntities = array_diff($categoryEntities, $userEntities);
                $bestRandomChoices[$category->getId()] = $categoryEntitiesWithoutUserEntities[array_rand($categoryEntitiesWithoutUserEntities)];
            }
        });

        return $this->render('app/index.html.twig', ['bestRandomChoices' => $bestRandomChoices]);
    }

    /**
     * @Route("/categories/{name}", name="category_entities")
     * @Method("GET")
     */
    public function showEntitiesAction(Category $category)
    {
        $user = $this->getUser();
        $userEntitiesIds = [];
        if ($user) {
            $userEntitiesIds = $user->getBestEntities()->map(function($entity)  {
                return $entity->getId();
            })->toArray();

        }
        return $this->render('app/category_entities.html.twig', ['category' => $category, 'userEntitiesIds' => $userEntitiesIds]);
    }

    /**
     * @Route("/saveUserEntities", name="save_user_entities")
     * @Method("POST")
     * @Security("is_granted('IS_AUTHENTICATED_REMEMBERED')")
     * @param Request $request
     * @return Response;
     */
    public function saveUserEntitiesAction(Request $request)
    {
        $user = $this->getUser();
        if ($request->isXMLHttpRequest()) {
            $content = $request->getContent();
            if (!empty($content)) {
                $em = $this->getDoctrine()->getManager();
                $entitiesIds = explode(',', json_decode($content, true));
                $entities = $em->getRepository(EntityDescription::class)->findBy(['id' => $entitiesIds]);
                $user->setBestEntities($entities);
                $em->persist($user);
                $em->flush();
            }

            return new JsonResponse(array('data' => ''));
        }
        return new Response('Error!', 400);
    }
}
