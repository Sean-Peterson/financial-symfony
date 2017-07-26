<?php

namespace AuthBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;

use AppBundle\Form\AdminLoginType;

use AppBundle\Form\RegisterUserType;
use AppBundle\Entity\User;

/**
 * Security/Auth controller.
 */
class SecurityController extends Controller
{
    /**
     * @Route("/loginz", name="loginz")
     */
    // public function loginAction(Request $request)
    // {
    //     $authenticationUtils = $this->get('security.authentication_utils');

    //     // get the login error if there is one
    //     $error = $authenticationUtils->getLastAuthenticationError();

    //     // last username entered by the user
    //     $lastUsername = $authenticationUtils->getLastUsername();


    //     // replace this example code with whatever you need
    //     return $this->render('security/login.html.twig', [
    //         'last_username' => $lastUsername,
    //         'error'         => $error ? $error->getMessage():null,
    //     ]);
    // }

    /**
     * @Route("/login_admin", name="admin_login")
     */
    public function adminLoginAction(Request $request)
    {
        $form = $this->createForm(AdminLoginType::class);
        $form->handleRequest($request);

        $authenticationUtils = $this->get('security.authentication_utils');
        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        // replace this example code with whatever you need
        return $this->render('admin/login.html.twig', [
            'form' => $form->createView(),
            'last_username' => $lastUsername,
            'error'         => $error ? $error->getMessage():null,
        ]);

    }
    /**
     * @Route("/login_check", name="login_check")
     */
    public function loginCheckAction()
    {
        // will never be executed
    }

    /**
     * @Route("/admin_login_check", name="admin_login_check")
     */
    public function adminLoginCheckAction()
    {
        // will never be executed
    }

    /**
     * @Route("/logout", name="logout")
     */
    public function logoutAction()
    {
        return $this->redirectToRoute('homepage');
    }
    /**
     * @Route("/admin/logout", name="admin_logout")
     */
    public function adminLogoutAction()
    {
        return $this->redirectToRoute('admin_login');
    }
}
