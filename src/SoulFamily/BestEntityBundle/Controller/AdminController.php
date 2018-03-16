<?php

namespace SoulFamily\BestEntityBundle\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\Request;
use SoulFamily\BestEntityBundle\Entity\Category;
use SoulFamily\BestEntityBundle\Entity\EntityDescription;
use SoulFamily\BestEntityBundle\Form\CategoryType;
use SoulFamily\BestEntityBundle\Service\UploadExternalFile;



class AdminController extends Controller
{
    /**
     * Lists all categories.
     *
     * @Route("/admin", name="admin_index")
     * @Security("is_granted('ROLE_ADMIN')")
     * @Method("GET")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();
        $categories = $em->getRepository(Category::class)->findAll();

        return $this->render('admin/index.html.twig', ['categories' => $categories]);
    }

    /**
     * Displays a form to edit an existing Category entity.
     *
     * @Route("admin/category/{id}/edit", requirements={"id": "\d+"}, name="admin_category_edit")
     * @Security("is_granted('ROLE_ADMIN')")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, Category $category)
    {
        $form = $this->createForm(CategoryType::class, $category);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            //delete old category image
            $this->get('_soulfamily.upload_external_file')->copyExternalFile($category->getImgUrl(), $category->getName());

            $this->getDoctrine()->getManager()->flush();

            $this->addFlash('success', 'Category ' . $category->getName() . ' updated_successfully');

            return $this->redirectToRoute('admin_index', ['id' => $category->getId()]);
        }

        return $this->render('admin/category_edit.html.twig', [
            'category' => $category,
            'form' => $form->createView(),
        ]);
    }

    /**
     * Deletes a Category entity.
     *
     * @Route("admin/category/{id}/delete", name="admin_category_delete")
     * @Method("POST")
     * @Security("is_granted('ROLE_ADMIN')")
     *
     */
    public function deleteAction(Request $request, Category $category)
    {
        if (!$this->isCsrfTokenValid('delete', $request->request->get('token'))) {
            return $this->redirectToRoute('admin_index');
        }

        $em = $this->getDoctrine()->getManager();

        unlink($this->get('_soulfamily.upload_external_file')->getFilePath($category->getName()));

        $em->remove($category);
        $em->flush();

        $this->addFlash('success', 'Category deleted successfully');

        return $this->redirectToRoute('admin_index');
    }

    /**
     * Creates a new Category entity.
     *
     * @Route("admin/category/new", name="admin_category_new")
     * @Method({"GET", "POST"})
     * @Security("is_granted('ROLE_ADMIN')")
     *
     */
    public function newAction(Request $request)
    {
        $category = new Category();

        $form = $this->createForm(CategoryType::class, $category);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();

            $this->get('_soulfamily.upload_external_file')->copyExternalFile($category->getImgUrl(), $category->getName());

            $em->persist($category);
            $em->flush();

            $this->addFlash('success', 'Category created successfully');

            return $this->redirectToRoute('admin_index');
        }

        return $this->render('admin/category_new.html.twig', [
            'category' => $category,
            'form' => $form->createView(),
        ]);
    }

    /**
     * Finds and displays a Category entity.
     *
     * @Route("admin/category/{id}", requirements={"id": "\d+"}, name="admin_category_show")
     * @Security("is_granted('ROLE_ADMIN')")
     * @Method({"GET", "POST"})
     */
    public function showAction(Request $request, Category $category)
    {
        $form = $this->createFormBuilder($category)
            ->add('Crawl', SubmitType::class, [
            'attr' => ['class' => 'btn-info']
            ])
            ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            $this->crawlAction($category);
        }

        return $this->render('admin/category_show.html.twig', [
            'category' => $category,
            'form' => $form->createView(),
        ]);
    }

    /**
     * Crawl entities in specific category
     *
     * @param Category $category
     */
    public function crawlAction(Category $category)
    {
        $crawler = $this->get('_soulfamily.crawler');
        $books = $crawler->crawl($category->getUrl(), $category->getHtmlCrawlPath());

        if (empty($books)) {
            $this->addFlash('error', 'There is something wrong. Items hadn not been crawled');
        } else {

            $em = $this->getDoctrine()->getManager();

            $em->getRepository(EntityDescription::class)->deleteByCategory($category);

            foreach ($books as $crawling) {
                $book = new EntityDescription($category);
                $book->setName($crawling['name']);
                $book->setLink($crawling['link']);

                $em->persist($book);
            }

            $em->flush();
            $this->addFlash('success', ucfirst($category->getName()) . ' have been crawled successfully');
        }
    }



}