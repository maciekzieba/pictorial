<?php

namespace Mz\PictorialBundle\Controller;

use Mz\PictorialBundle\Entity\Visit;
use Mz\PictorialBundle\Form\VisitCostForm;
use Mz\PictorialBundle\Form\VisitForm;
use Mz\PictorialBundle\Listing\VisitListing;
use Mz\PictorialBundle\Service\UserService;
use Mz\PictorialBundle\Service\VisitService;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;
use JMS\DiExtraBundle\Annotation as DI;
use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

class VisitController extends Controller
{
    /**
     * @DI\Inject("pictorial.visit")
     * @var VisitService
     */
    protected $visitService;

    /**
     * @DI\Inject("pictorial.user")
     * @var UserService
     */
    protected $userService;

    /**
     * @Route("/visit/{id}/show", name="visit_show", requirements={"id": "\d+"})
     * @Template()
     */
    public function showAction(Request $request, $id)
    {
        $visit = $this->visitService->demandVisit($id);
        return array(
            'visit' => $visit
        );
    }

    /**
     * @Route("/admin/visit/{visitId}/costs", name="visit_costs", requirements={"visitId": "\d+"})
     * @Template()
     * @Security("has_role('ROLE_SUPER_ADMIN')")
     */
    public function visitCostsAction(Request $request, $visitId)
    {
        /** @var Visit $visit */
        $visit = $this->visitService->demandVisit($visitId);

        $form = $this->createForm(new VisitCostForm($this->userService), $visit);
        $form->handleRequest($request);


        if ($form->isSubmitted()) {
            if ($form->isValid()) {

                $this->visitService->saveVisit($visit);
                $this->addFlash('success', 'Koszty wizyty zostały zapisane');
                //return $this->redirect($this->generateUrl('visit_costs', array('visitId' => $visit->getId())));
            } else {

            }
        }

        return array(
            'form' => $form->createView(),
            'visit' => $visit
        );
    }


    /**
     * @Route("/visit/update-field", name="visit_update_field", options={"expose"=true})
     */
    public function updateFieldAction(Request $request)
    {
        $id = $request->request->getInt('id');
        $value = $request->request->get('value', null);
        $field = $request->request->get('field', null);
        if ($id > 0 && $value !== null && $field !== null) {
            try {
                $visit = $this->visitService->demandVisit($id);
                $newValue = $this->visitService->updateVisitField($visit, $field, $value);
                return new Response($newValue);
            } catch (\Exception $e) {
                return new Response($e->getMessage(), 500);
            }

        }
        return new Response("Wrong input data.", 500);
    }

    /**
     * @Route("/admin/visit/list", name="visit_list")
     * @Template()
     */
    public function listAction(Request $request)
    {
        /** @var Listing $listing */
        $listing = $this->get('listing')->createListing(new VisitListing($this->visitService), array(
            'template' => 'MzPictorialBundle:Visit:list.html.twig',
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
     * @Route("/admin/visit/add", name="visit_add")
     * @Template()
     */
    public function addAction(Request $request)
    {
        $visit = new Visit();
        $form = $this->createForm(new VisitForm($this->visitService), $visit);
        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            if ($form->isValid()) {

                $this->visitService->saveVisit($visit);
                $this->addFlash('success', 'Wizyta została dodana');
                return $this->redirect($this->generateUrl('visit_edit', array('id' => $visit->getId())));
            } else {

            }
        }

        return array(
            'form' => $form->createView()
        );
    }

    /**
     * @Route("/admin/visit/{id}/edit", name="visit_edit", requirements={"id": "\d+"})
     * @Template()
     */
    public function editAction(Request $request, $id)
    {
        $visit = $this->visitService->demandVisit($id);
        $form = $this->createForm(new VisitForm($this->visitService), $visit);
        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            if ($form->isValid()) {
                $this->visitService->saveVisit($visit);
                $this->addFlash('success', 'Wizyta została zapisana');
                return $this->redirect($this->generateUrl('visit_edit', array('id' => $visit->getId())));
            } else {

            }
        }

        return array(
            'form' => $form->createView(),
            'visit' => $visit
        );
    }

    /**
     * @Route("/admin/visit/{id}/remove", name="visit_remove", requirements={"id": "\d+"})
     * @Template()
     */
    public function removeAction(Request $request, $id)
    {
        try {
            $visit = $this->visitService->demandVisit($id);
            $this->visitService->removePackage($visit);
            $this->addFlash('success', 'Wizyta została usunięta!');
        } catch (\Exception $e) {
            $this->addFlash('danger', 'Wizyta nie mógła zostać usunięta (' . $e->getMessage() . ')');
        }

        return $this->redirectToRoute('visit_list');
    }
}
