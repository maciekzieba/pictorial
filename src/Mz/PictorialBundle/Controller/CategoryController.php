<?php

namespace Mz\PictorialBundle\Controller;

use Mz\PictorialBundle\Entity\Category;
use Mz\PictorialBundle\Form\CategoryForm;
use Mz\PictorialBundle\Listing\CategoryListing;
use Mz\PictorialBundle\Service\CategoryService;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;
use JMS\DiExtraBundle\Annotation as DI;

class CategoryController extends Controller
{
    /**
     * @DI\Inject("pictorial.category")
     * @var CategoryService
     */
    protected $categoryService;

    /**
     * @Route("/admin/category/list", name="category_list")
     * @Template()
     */
    public function listAction(Request $request)
    {
        /** @var Listing $listing */
        $listing = $this->get('listing')->createListing(new CategoryListing(), array(
            'template' => 'MzPictorialBundle:Category:list.html.twig',
            'request' => $request
        ));


        if ($request->isXmlHttpRequest()) {
            return $listing->createResponse();
        }

        return array(
            'listing' => $listing->createView()
        );
    }

    /**
     * @Route("/admin/category/add", name="category_add")
     * @Template()
     */
    public function addAction(Request $request)
    {
        $category = new Category();
        $form = $this->createForm(new CategoryForm(), $category);
        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            if ($form->isValid()) {
                $this->categoryService->saveCategory($category);
                $this->addFlash('success', 'Kategoria została dodana');
                return $this->redirect($this->generateUrl('category_list', array()));
            } else {

            }
        }

        return array(
            'form' => $form->createView()
        );
    }

    /**
     * @Route("/admin/category/{id}/edit", name="category_edit", requirements={"id": "\d+"})
     * @Template()
     */
    public function editAction(Request $request, $id)
    {
        $category = $this->categoryService->demandCategory($id);
        $form = $this->createForm(new CategoryForm(), $category);
        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            if ($form->isValid()) {
                $this->categoryService->saveCategory($category);
                $this->addFlash('success', 'Kategoria została zapisana');
                return $this->redirect($this->generateUrl('category_list', array()));
            } else {

            }
        }

        return array(
            'form' => $form->createView(),
            'category' => $category
        );
    }

    /**
     * @Route("/admin/category/{id}/remove", name="category_remove", requirements={"id": "\d+"})
     * @Template()
     */
    public function removeAction(Request $request, $id)
    {
        try {
            $category = $this->categoryService->demandCategory($id);
            $this->categoryService->removeCategory($category);
            $this->addFlash('success', 'Kategoria została usunięta!');
        } catch (\Exception $e) {
            $this->addFlash('danger', 'Kategoria nie mógła zostać usunięta (' . $e->getMessage() . ')');
        }

        return $this->redirectToRoute('category_list');
    }
}
