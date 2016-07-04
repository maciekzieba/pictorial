<?php

namespace Mz\PictorialBundle\Controller;

use FOS\UserBundle\Model\UserManager;
use Mz\PictorialBundle\Entity\Publication;
use Mz\PictorialBundle\Entity\User;
use Mz\PictorialBundle\Entity\Visit;
use Mz\PictorialBundle\Form\PublicationForm;
use Mz\PictorialBundle\Form\UserForm;
use Mz\PictorialBundle\Form\VisitForm;
use Mz\PictorialBundle\Listing\PackageListing;
use Mz\PictorialBundle\Listing\UserListing;
use Mz\PictorialBundle\Listing\VisitListing;
use Mz\PictorialBundle\Service\PackageService;
use Mz\PictorialBundle\Service\PublicationService;
use Mz\PictorialBundle\Service\VisitService;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;
use JMS\DiExtraBundle\Annotation as DI;

class PublicationController extends Controller
{
    /**
     * @DI\Inject("pictorial.publication")
     * @var PublicationService
     */
    protected $publicationService;

    /**
     * @DI\Inject("pictorial.visit")
     * @var VisitService
     */
    protected $visitService;

    /**
     * @Route("/publication/{visitId}/add", name="publication_add", requirements={"visitId": "\d+"})
     * @Template()
     */
    public function addAction(Request $request, $visitId)
    {
        $visit = $this->visitService->demandVisit($visitId);
        $publication = new Publication();
        $publication->setVisit($visit);
        $form = $this->createForm(new PublicationForm($this->publicationService), $publication);
        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            if ($form->isValid()) {
                $this->publicationService->savePublication($publication);
                $this->addFlash('success', 'Publikacja została dodana');
                return $this->redirect($this->generateUrl('visit_show', array('id' => $visit->getId())));
            } else {

            }
        }

        return array(
            'form' => $form->createView(),
            'visit' => $visit
        );
    }

    /**
     * @Route("/publication/{visitId}/{id}/edit", name="publication_edit", requirements={"visitId": "\d+", "id": "\d+"})
     * @Template()
     */
    public function editAction(Request $request, $visitId, $id)
    {
        $visit = $this->visitService->demandVisit($visitId);
        $publication = $this->publicationService->demandPublication($id);
        $form = $this->createForm(new PublicationForm($this->publicationService), $publication);
        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            if ($form->isValid()) {
                $this->publicationService->savePublication($publication);
                $this->addFlash('success', 'Publikacja została zapisana');
                return $this->redirect($this->generateUrl('visit_show', array('id' => $visit->getId())));
            } else {

            }
        }

        return array(
            'form' => $form->createView(),
            'visit' => $visit,
            'publication' => $publication
        );
    }

    /**
     * @Route("/publication/{visitId}/{id}/remove", name="publication_remove", requirements={"visitId": "\d+", "id": "\d+"})
     * @Template()
     */
    public function removeAction(Request $request, $visitId, $id)
    {
        $visit = $this->visitService->demandVisit($visitId);
        $publication = $this->publicationService->demandPublication($id);

        try {
            $this->publicationService->removePublication($publication);
            $this->addFlash('success', 'Publikacja została usunięta!');
        } catch (\Exception $e) {
            $this->addFlash('danger', 'Publikacja nie mógła zostać usunięta (' . $e->getMessage() . ')');
        }

        return $this->redirect($this->generateUrl('visit_show', array('id' => $visit->getId())));
    }
}
