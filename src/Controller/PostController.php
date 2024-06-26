<?php

namespace App\Controller;

use App\Entity\Category;
use App\Entity\Post;
use App\Repository\PostRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\Cache;
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
    #[Cache(maxage: 86400, public: true)]
    public function show(
        Request $request,
        Post $post,
    ): Response {
        $response = new Response();
        $response->setPublic();
        $response->headers->addCacheControlDirective('must-revalidate');
        $response->setLastModified($post->getUpdatedAt());
        if ($response->isNotModified($request)) {
            return $response;
        }

        return $response->setContent($this->renderView('post/show.html.twig', [
            'post' => $post,
        ]));
    }

    #[Route('/search', methods: 'GET')]
    public function search(
        Request $request,
        PostRepository $postRepository,
    ): Response {
        $searchQuery = $request->query->get('q');

        if ($searchQuery) {
            $searchResult = $postRepository->findByTitleLikeWithQueryBuilder($searchQuery);
        }

        return $this->render('post/search.html.twig', [
            'posts' => $searchResult ?? [],
            'query' => $searchQuery,
        ]);
    }

    public function indexByCategory(
        Category $category,
        PostRepository $postRepository,
    ): Response {
        return $this->render('post/index_by_category.html.twig', [
            'posts' => $postRepository->findBy(['filedIn' => $category]),
        ]);
    }
}
