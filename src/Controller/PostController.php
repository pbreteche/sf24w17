<?php

namespace App\Controller;

use App\Entity\Post;
use App\Repository\PostRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/post')]
class PostController extends AbstractController
{
    #[Route(methods: 'GET')]
    public function index(
        PostRepository $postRepository,
    ): Response {
        $posts = $postRepository->findBy([], limit: 50);

        return $this->render('post/index.html.twig', [
            'posts' => $posts,
        ]);
    }

    #[Route('/{id<\d+>}', methods: 'GET')]
    public function show(
        Post $post,
    ): Response {
        return $this->render('post/show.html.twig', [
            'post' => $post,
        ]);
    }

    #[Route('/search', methods: 'GET')]
    public function search(
        Request $request,
        PostRepository $postRepository,
    ): Response {
        $searchQuery = $request->query->get('q');

        $searchResult = $postRepository->findByTitleLike($searchQuery);

        return $this->render('post/search.html.twig', [
            'posts' => $searchResult,
            'query' => $searchQuery,
        ]);
    }
}
