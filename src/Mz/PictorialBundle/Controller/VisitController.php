<?php

namespace Mz\PictorialBundle\Controller;

use FOS\UserBundle\Model\UserManager;
use Mz\PictorialBundle\Entity\User;
use Mz\PictorialBundle\Entity\Visit;
use Mz\PictorialBundle\Form\UserForm;
use Mz\PictorialBundle\Form\VisitForm;
use Mz\PictorialBundle\Listing\PackageListing;
use Mz\PictorialBundle\Listing\UserListing;
use Mz\PictorialBundle\Listing\VisitListing;
use Mz\PictorialBundle\Service\PackageService;
use Mz\PictorialBundle\Service\VisitService;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;
use JMS\DiExtraBundle\Annotation as DI;

class VisitController extends Controller
{
    /**
     * @DI\Inject("pictorial.visit")
     * @var VisitService
     */
    protected $visitService;

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
                $visit->setScountingShare($this->visitService->getDefaultScountingShare());
                $visit->setEditingShare($this->visitService->getDefaultEditingShare());
                $visit->setInterviewShare($this->visitService->getDefaultInterviewShare());
                $visit->setPhotoShare($this->visitService->getDefaultPhotoShare());
                $visit->setPostproductionShare($this->visitService->getDefaultPostproductionShare());
                $visit->setProvisionShare($this->visitService->getDefaultProvisionShare());

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
