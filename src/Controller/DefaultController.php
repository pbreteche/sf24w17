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
        // helper from AbstractController
        return $this->render('default/homepage.html.twig', [
            'my_var' => 'Hello',
        ]);
    }
}
