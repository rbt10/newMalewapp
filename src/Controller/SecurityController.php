<?php

namespace App\Controller;

use App\Repository\UserRepository;
use App\Services\NavProvinces;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends AbstractController
{
    private NavProvinces $navProvinces;
    public function __construct( NavProvinces $navProvinces)
    {
        $this->navProvinces = $navProvinces;
    }
    #[Route(path: '/profile', name: 'app_profile')]
    public function index()
    {
        return $this->render('profile/index.html.twig', [
            'provinces' => $this->navProvinces->provinces()
        ]);
    }
    #[Route(path: '/login', name: 'app_login')]
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();

        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('security/login.html.twig', [
            'last_username' => $lastUsername,
            'error' => $error,
            'provinces' => $this->navProvinces->provinces()
        ]);
    }

    #[Route(path: '/logout', name: 'app_logout')]
    public function logout(): void
    {
        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }

}
