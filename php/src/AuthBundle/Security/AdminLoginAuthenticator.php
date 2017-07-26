<?php

namespace AuthBundle\Security;

use Symfony\Component\Security\Core\Exception\CustomUserMessageAuthenticationException;
use Symfony\Component\Security\Guard\Authenticator\AbstractFormLoginAuthenticator;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;

use AppBundle\Entity\User;
use AppBundle\Entity\Admin;

class AdminLoginAuthenticator extends AbstractFormLoginAuthenticator
{
    private $container;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    public function getCredentials(Request $request)
    {
        if ($request->getPathInfo() != '/admin_login_check') {
            return null;
        }

        $username = $request->request->get('_username');
        $request->getSession()->set(Security::LAST_USERNAME, $username);
        $password = $request->request->get('_password');

        if (empty($username) || empty($password)) {
            throw new CustomUserMessageAuthenticationException('Username and password are required');
        }

        $data = array(
            'username' => $username,
            'password' => $password
        );

        return $data;
    }

    public function getUser($credentials, UserProviderInterface $userProvider)
    {
        
        // $user = $userProvider->loadUserByUsername($credentials['username']);
        $repo = $this->container->get('doctrine')->getRepository('AppBundle:Admin');
        $user = $repo->findOneByUsername($credentials["username"]);
        
        if (!$user) {
            // $user = new Admin();
            // $user->setUsername($credentials["username"]);
            throw new CustomUserMessageAuthenticationException('Your username does not have permission to administer this application');
            // } else if ( !$user->getIsActive() ) {
            //     throw new CustomUserMessageAuthenticationException('User account has been disabled');
        }
        
        return $user;
    }

    public function checkCredentials($credentials, UserInterface $user)
    {
        $em = $this->container->get('doctrine')->getEntityManager();   
        $username = $credentials['username'];
        $password = $credentials['password'];

        if ( ( $username == "admin") && $username == $password ) {

        } else {
            $server = "ldap.ohsu.edu";
            $port = 389;
            $uname = "OHSUM01\\" . $username;
            $dn = "dc=ohsum01,dc=ohsu,dc=edu";
            $filter = "(&(ObjectClass=User)(cn=" . $username . "))";
            $attr = array("name", "givenname", "sn", "mail", "displayname", "mail", "title", "telephonenumber", "manager", "directreports");

            $ds = ldap_connect($server, $port);
            $valid = @ldap_bind($ds, $uname, $password);

            if ($valid) {
                //user logged in successfully
                $em->persist($user);
            } else {
                define(LDAP_OPT_DIAGNOSTIC_MESSAGE, 0x0032);
                if (ldap_get_option($ds, LDAP_OPT_DIAGNOSTIC_MESSAGE, $extended_error)) {
                    throw new CustomUserMessageAuthenticationException('Invalid username or password');
                }else{
                    throw new CustomUserMessageAuthenticationException('We are unable to connect to OHSU authentication server to validate login');
                }

            }
        }
        
        return true;
        
    }

    protected function getLoginUrl()
    {
        return $this->container->get('router')->generate('admin_login');
    }

    function onAuthenticationSuccess(Request $request, TokenInterface $token, $providerKey)
    {
        // $token->getUser()->setLastLoginAt(new \DateTime());
        $this->container->get('doctrine')->getEntityManager()->flush();
        // if ( $token->getUser()->profileRequiresUpdate() ) {
        //     return new RedirectResponse($this->container->get('router')->generate('user_profile'));
        // } else {
        //     return new RedirectResponse($this->container->get('router')->generate('homepage'));
        // }
        return new RedirectResponse($this->container->get('router')->generate('manage_groups'));
        
    }
}
