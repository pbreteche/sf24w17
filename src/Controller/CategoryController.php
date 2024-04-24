<?php

namespace App\Controller;

use App\Entity\Category;
use App\Repository\CategoryRepository;
use App\Repository\PostRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/category')]
class CategoryController extends AbstractController
{
    #[Route(methods: 'GET')]
    public function index(
        CategoryRepository $repository,
    ): Response {
        $categories = $repository->findBy([], limit: 50);

        return $this->render('category/index.html.twig', [
            'categories' => $categories,
        ]);
    }

    #[Route('/{id<\d+>}', methods: 'GET')]
    public function show(
        Category $category,
        PostRepository $postRepository,
    ): Response {
        return $this->render('category/show.html.twig', [
            'category' => $category,
            'posts' => $postRepository->findBy(['filedIn' => $category]),
        ]);
    }
}
