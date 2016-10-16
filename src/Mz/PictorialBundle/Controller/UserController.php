<?php

namespace Mz\PictorialBundle\Controller;

use FOS\UserBundle\Model\UserManager;
use Mz\PictorialBundle\Entity\Pricelist;
use Mz\PictorialBundle\Entity\User;
use Mz\PictorialBundle\Form\PasswordForm;
use Mz\PictorialBundle\Form\UserEditForm;
use Mz\PictorialBundle\Form\UserForm;
use Mz\PictorialBundle\Form\UserPricelistForm;
use Mz\PictorialBundle\Listing\UserListing;
use Mz\PictorialBundle\Listing\UserPricelistListing;
use Mz\PictorialBundle\Service\UserService;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;
use JMS\DiExtraBundle\Annotation as DI;

class UserController extends Controller
{
    /**
     * @DI\Inject("pictorial.user")
     * @var UserService
     */
    protected $userService;

    /**
     * @DI\Inject("fos_user.user_manager")
     * @var UserManager $userManager
     */
    protected $userManager;

    /**
     * @Route("/admin/user/list", name="user_list")
     * @Template()
     */
    public function listAction(Request $request)
    {
        /** @var Listing $listing */
        $listing = $this->get('listing')->createListing(new UserListing(), array(
            'template' => 'MzPictorialBundle:User:list.html.twig',
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
     * @Route("/admin/user/add", name="user_add")
     * @Template()
     */
    public function addAction(Request $request)
    {
        $user = new User();
        $user->setEnabled(true);
        $form = $this->createForm(new UserForm($this->userService->getRolesList()), $user);
        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            if ($form->isValid()) {
                $user->setEnabled(true);
                $user->setLocked(false);
                $this->userManager->updateUser($user);
                $this->addFlash('success', 'Użytkownik został dodanay');
                return $this->redirect($this->generateUrl('user_list', array()));
            } else {

            }
        }

        return array(
            'form' => $form->createView()
        );
    }

    /**
     * @Route("/admin/user/{id}/edit", name="user_edit", requirements={"id": "\d+"})
     * @Template()
     */
    public function editAction(Request $request, $id)
    {
        $user = $this->userService->demandUser($id);
        $form = $this->createForm(new UserEditForm($this->userService->getRolesList()), $user);
        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            if ($form->isValid()) {
                $this->userManager->updateUser($user);
                $this->addFlash('success', 'Użytkownik został zapisany');
                return $this->redirect($this->generateUrl('user_list', array()));
            } else {

            }
        }

        return array(
            'form' => $form->createView(),
            'user' => $user
        );
    }

    /**
     * @Route("/admin/user/{userId}/pricelist", name="user_pricelist", requirements={"userId": "\d+"})
     * @Template()
     */
    public function pricelistAction(Request $request, $userId)
    {
        $user = $this->userService->demandUser($userId);

        /** @var Listing $listing */
        $listing = $this->get('listing')->createListing(new UserPricelistListing($user), array(
            'template' => 'MzPictorialBundle:User:pricelist.html.twig',
            'request' => $request
        ));


        if ($request->isXmlHttpRequest()) {
            return $listing->createResponse();
        }

        return array(
            'listing' => $listing->createView(),
            'user' => $user
        );
    }

    /**
     * @Route("/admin/user/{userId}/pricelist/add", name="user_pricelist_add", requirements={"userId": "\d+"})
     * @Template()
     */
    public function pricelistAddAction(Request $request, $userId)
    {
        $user = $this->userService->demandUser($userId);

        $pricelist = new Pricelist();
        $pricelist->setUser($user);

        $form = $this->createForm(new UserPricelistForm(), $pricelist);
        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            if ($form->isValid()) {
                $this->userService->savePricelist($pricelist);
                $this->addFlash('success', 'Cennik został dodany');
                return $this->redirect($this->generateUrl('user_pricelist', array('userId' => $user->getId())));
            } else {

            }
        }

        return array(
            'form' => $form->createView(),
            'user' => $user
        );
    }

    /**
     * @Route("/admin/user/{userId}/pricelist/edit/{id}", name="user_pricelist_edit", requirements={"userId": "\d+", "id": "\d+"})
     * @Template()
     */
    public function pricelistEditAction(Request $request, $userId, $id)
    {
        $user = $this->userService->demandUser($userId);
        $pricelist = $this->userService->demandPricelist($id);

        $form = $this->createForm(new UserPricelistForm(), $pricelist);
        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            if ($form->isValid()) {
                $this->userService->savePricelist($pricelist);
                $this->addFlash('success', 'Cennik został zapisany');
                return $this->redirect($this->generateUrl('user_pricelist', array('userId' => $user->getId())));
            } else {

            }
        }

        return array(
            'form' => $form->createView(),
            'user' => $user,
            'pricelist' => $pricelist
        );
    }

    /**
     * @Route("/admin/user/{userId}/pricelist/remove/{id}", name="user_pricelist_remove", requirements={"userId": "\d+", "id": "\d+"})
     * @Template()
     */
    public function removeAction(Request $request, $userId, $id)
    {
        try {
            $user = $this->userService->demandUser($userId);
            $pricelist = $this->userService->demandPricelist($id);

            $this->userService->removePricelist($pricelist);
            $this->addFlash('success', 'Cennik został usunięty!');
        } catch (\Exception $e) {
            $this->addFlash('danger', 'Cennik nie mógł zostać usunięty (' . $e->getMessage() . ')');
        }

        return $this->redirectToRoute('user_pricelist', array('userId' => $user->getId()));
    }

    /**
     * @Route("/password", name="password")
     * @Template()
     */
    public function passwordAction(Request $request)
    {
        $user = $this->getUser();
        $form = $this->createForm(new PasswordForm(), $user);
        $form->handleRequest($request);
        if ($form->isSubmitted()) {
            if ($form->isValid()) {
                $this->userManager->updateUser($user);
                $this->addFlash('success', 'Hasło zostało zmienione');
                return $this->redirect($this->generateUrl('dashboard', array()));
            } else {

            }
        }

        return array(
            'form' => $form->createView()
        );
    }

}
