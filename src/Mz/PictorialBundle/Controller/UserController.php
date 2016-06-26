<?php

namespace Mz\PictorialBundle\Controller;

use FOS\UserBundle\Model\UserManager;
use Mz\PictorialBundle\Entity\User;
use Mz\PictorialBundle\Form\UserForm;
use Mz\PictorialBundle\Listing\UserListing;
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

}
