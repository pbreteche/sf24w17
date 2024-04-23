<?php

namespace App\Controller;

use App\Entity\Post;
use App\Enum\PostState;
use App\Form\PostType;
use App\Repository\PostRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\EnumType;
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

        if ($searchQuery) {
            $searchResult = $postRepository->findByTitleLikeWithQueryBuilder($searchQuery);
        }

        return $this->render('post/search.html.twig', [
            'posts' => $searchResult ?? [],
            'query' => $searchQuery,
        ]);
    }

    #[Route('/new', methods: ['GET', 'POST'])]
    public function new(
        Request $request,
        EntityManagerInterface $manager,
    ): Response {
        $post = new Post();
        $form = $this->createForm(PostType::class, $post, [
            'state_placeholder' => false,
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Marque l'entité comme devant être stockée en base
            $manager->persist($post);
            // Synchronise la base d'après le suivi des modifications sur les entités
            $manager->flush();
            // Les messages Flash Symfony sont enregistrée en session utilisateur.
            // Ils sont supprimés automatiquement au premier accès en lecture.
            $this->addFlash('success', 'La publication a bien été enregistrée.');

            return $this->redirectToRoute('app_post_show', ['id' => $post->getId()], Response::HTTP_SEE_OTHER);
        }

        return $this->render('post/new.html.twig', [
            'form_new' => $form,
        ]);
    }
}
