<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class DefaultController extends AbstractController
{
    /**
     * Controller method *must* return a Response instance.
     * Symfony résout les paramètres demandés par la méthode de contrôleur,
     * en s'appuyant sur le typage et/ou sur le nommage.
     * Si la résolution est correctement effectuée, Symfony passe l'argument.
     * Une erreur est déclenchée sinon.
     * ⇒ S'appuie sur le système d'injection de dépendance.
     */
    #[Route('/', methods: 'GET')]
    public function homepage(Request $request): Response
    {
        // helper from AbstractController
        return $this->render('default/homepage.html.twig', [
            'my_var' => 'Hello',
            'server_name' => $request->server->get('SERVER_NAME'),
        ]);
    }
}
