<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class DefaultController extends AbstractController
{
    /**
     * Controller method *must* return a Response instance.
     */
    #[Route('/', methods: 'GET')]
    public function homepage(): Response
    {
        return new Response('<html><head><title>Accueil</title></head><body>
    <h1>Bienvenue !</h1>
</body></html>');
    }
}
