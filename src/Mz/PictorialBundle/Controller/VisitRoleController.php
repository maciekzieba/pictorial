<?php

namespace Mz\PictorialBundle\Controller;

use Mz\PictorialBundle\Entity\VisitRole;
use Mz\PictorialBundle\Form\VisitRoleForm;
use Mz\PictorialBundle\Listing\VisitRoleListing;
use Mz\PictorialBundle\Service\VisitRoleService;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;
use JMS\DiExtraBundle\Annotation as DI;

class VisitRoleController extends Controller
{
    /**
     * @DI\Inject("pictorial.visit_role")
     * @var VisitRoleService
     */
    protected $visitRoleService;

    /**
     * @Route("/admin/visit_role/list", name="visit_role_list")
     * @Template()
     */
    public function listAction(Request $request)
    {
        /** @var Listing $listing */
        $listing = $this->get('listing')->createListing(new VisitRoleListing(), array(
            'template' => 'MzPictorialBundle:VisitRole:list.html.twig',
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
     * @Route("/admin/visit_role/add", name="visit_role_add")
     * @Template()
     */
    public function addAction(Request $request)
    {
        $visitRole = new VisitRole();
        $form = $this->createForm(new VisitRoleForm(), $visitRole);
        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            if ($form->isValid()) {
                $this->visitRoleService->saveVisitRole($visitRole);
                $this->addFlash('success', 'Rola została dodana');
                return $this->redirect($this->generateUrl('visit_role_list', array()));
            } else {

            }
        }

        return array(
            'form' => $form->createView()
        );
    }

    /**
     * @Route("/admin/visit_role/{id}/edit", name="visit_role_edit", requirements={"id": "\d+"})
     * @Template()
     */
    public function editAction(Request $request, $id)
    {
        $visitRole = $this->visitRoleService->demandVisitRole($id);
        $form = $this->createForm(new VisitRoleForm(), $visitRole);
        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            if ($form->isValid()) {
                $this->visitRoleService->saveVisitRole($visitRole);
                $this->addFlash('success', 'Rola została zapisana');
                return $this->redirect($this->generateUrl('visit_role_list', array()));
            } else {

            }
        }

        return array(
            'form' => $form->createView(),
            'visitRole' => $visitRole
        );
    }

    /**
     * @Route("/admin/visit_role/{id}/remove", name="visit_role_remove", requirements={"id": "\d+"})
     * @Template()
     */
    public function removeAction(Request $request, $id)
    {
        try {
            $visitRole = $this->visitRoleService->demandVisitRole($id);
            $this->visitRoleService->removeVisitRole($visitRole);
            $this->addFlash('success', 'Rola została usunięta!');
        } catch (\Exception $e) {
            $this->addFlash('danger', 'Rola nie mógła zostać usunięta (' . $e->getMessage() . ')');
        }

        return $this->redirectToRoute('visit_role_list');
    }
}
