<?php

namespace App\Controller\API;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/api')]
class PostController extends AbstractController
{
    #[Route]
    #[IsGranted('IS_AUTHENTICATED')]
    public function demo(): Response
    {
        return $this->json(['status' => 'OK', 'message' => 'Ça marche !']);
    }
}
