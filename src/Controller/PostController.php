<?php

namespace App\Controller;

use App\Repository\PostRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class PostController extends AbstractController
{
    #[Route('/post', methods: 'GET')]
    public function index(
        PostRepository $postRepository,
    ): Response {
        $posts = $postRepository->findBy([], limit: 50);

        return $this->render('post/index.html.twig', [
            'posts' => $posts,
        ]);
    }

    #[Route('/post/{id}', requirements: ['id' => '\d+'], methods: 'GET')]
    public function show(
        int $id,
        PostRepository $postRepository,
    ): Response {
        $post = $postRepository->findOneBy(['id' => $id]);

        if (!$post) {
            throw $this->createNotFoundException();
        }

        return $this->render('post/show.html.twig', [
            'post' => $post,
        ]);
    }
}
