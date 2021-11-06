<?php

namespace App\Controller;

use KnpU\OAuth2ClientBundle\Client\ClientRegistry;
use League\OAuth2\Client\Provider\Exception\IdentityProviderException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class SpotifyController extends AbstractController
{

    /**
     * @Route("/login", name="login")
     */
    public function login(){
        if ($this->getUser()) {
            return $this->redirectToRoute('home');
        }
        return $this->render('security/login.html.twig');
    }
    /**
     * Link to this controller to start the "connect" process
     *
     * @Route("/connect/spotify", name="connect_spotify_start")
     */
    public function connectAction(ClientRegistry $clientRegistry)
    {
        // will redirect to Facebook!
        return $clientRegistry
            ->getClient('spotify') // key used in config/packages/knpu_oauth2_client.yaml
            ->redirect(['user-top-read', 'user-read-email'], []);
    }

    /**
     * After going to Facebook, you're redirected back here
     * because this is the "redirect_route" you configured
     * in config/packages/knpu_oauth2_client.yaml
     *
     * @Route("/connect/spotify/check", name="connect_spotify_check")
     */
    public function connectCheckAction(Request $request, ClientRegistry $clientRegistry)
    {

    }

    /**
     * @Route("/logout", name="logout")
     */
    public function logout(){

    }
}
