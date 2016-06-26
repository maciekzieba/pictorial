<?php

namespace Mz\PictorialBundle\Controller;

use Edge\XNewsBundle\Service\UserService;
use FOS\UserBundle\Model\UserManager;
use Mz\PictorialBundle\Entity\Package;
use Mz\PictorialBundle\Entity\User;
use Mz\PictorialBundle\Form\PackageForm;
use Mz\PictorialBundle\Form\UserForm;
use Mz\PictorialBundle\Listing\PackageListing;
use Mz\PictorialBundle\Listing\UserListing;
use Mz\PictorialBundle\Service\PackageService;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;
use JMS\DiExtraBundle\Annotation as DI;

class PackageController extends Controller
{
    /**
     * @DI\Inject("pictorial.package")
     * @var PackageService
     */
    protected $packageService;

    /**
     * @Route("/admin/package/list", name="package_list")
     * @Template()
     */
    public function listAction(Request $request)
    {
        /** @var Listing $listing */
        $listing = $this->get('listing')->createListing(new PackageListing($this->packageService), array(
            'template' => 'MzPictorialBundle:Package:list.html.twig',
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
     * @Route("/admin/package/add", name="package_add")
     * @Template()
     */
    public function addAction(Request $request)
    {
        $package = new Package();
        $form = $this->createForm(new PackageForm($this->packageService), $package);
        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            if ($form->isValid()) {
                $this->packageService->savePackage($package);
                $this->addFlash('success', 'Pakiet został dodanay');
                return $this->redirect($this->generateUrl('package_list', array()));
            } else {
                $this->addFlash('danger', 'Uzupełnij wymagane pola!');
            }
        }

        return array(
            'form' => $form->createView()
        );
    }

    /**
     * @Route("/admin/package/{id}/edit", name="package_edit", requirements={"id": "\d+"})
     * @Template()
     */
    public function editAction(Request $request, $id)
    {
        $package = $this->packageService->demandPackage($id);
        $form = $this->createForm(new PackageForm($this->packageService), $package);
        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            if ($form->isValid()) {
                $this->packageService->savePackage($package);
                $this->addFlash('success', 'Pakiet został zapisany');
                return $this->redirect($this->generateUrl('package_list', array()));
            } else {
                $this->addFlash('danger', 'Uzupełnij wymagane pola!');
            }
        }

        return array(
            'form' => $form->createView(),
            'package' => $package
        );
    }

    /**
     * @Route("/admin/package/{id}/remove", name="package_remove", requirements={"id": "\d+"})
     * @Template()
     */
    public function removeAction(Request $request, $id)
    {
        try {
            $package = $this->packageService->demandPackage($id);
            $this->packageService->removePackage($package);
            $this->addFlash('success', 'Pakiet został usunięty!');
        } catch (\Exception $e) {
            $this->addFlash('danger', 'Pakiet nie mógł zostać usunięty (' . $e->getMessage() . ')');
        }

        return $this->redirectToRoute('package_list');
    }
}
