<?php

namespace App\Controller\Admin;

use App\Entity\Post;
use App\Form\DeleteType;
use App\Form\PostType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\ExpressionLanguage\Expression;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Component\Validator\Constraints\EqualTo;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Validator\ValidatorInterface;

#[Route('/admin/post')]
class PostController extends AbstractController
{
    #[Route('/new', methods: ['GET', 'POST'])]
    public function new(
        Request $request,
        EntityManagerInterface $manager,
    ): Response {
        $post = new Post();
        $form = $this->createForm(PostType::class, $post, [
            'validation_groups' => ['Default', 'create']
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

        return $this->render('admin/post/new.html.twig', [
            'form_new' => $form,
        ]);
    }
    #[Route('/edit/{id<\d+>}', methods: ['GET', 'POST'])]
    // Un seul IsGranted à la fois nécessaire
    #[IsGranted(new Expression('user === object.getAuthoredBy()'), 'post')]
    #[IsGranted('ENTITY_AUTHOR', 'post')]
    #[isGranted(new Expression('is_granted("ENTITY_AUTHOR", object) or is_granted("ROLE_ADMIN")'), 'post')]
    public function edit(
        Post $post,
        Request $request,
        EntityManagerInterface $manager,
    ): Response {
        $form = $this->createForm(PostType::class, $post, [
            'state_placeholder' => false,
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $manager->flush();
            $this->addFlash('success', 'La publication a bien été modifiée.');

            return $this->redirectToRoute('app_post_show', ['id' => $post->getId()], Response::HTTP_SEE_OTHER);
        }

        return $this->render('admin/post/edit.html.twig', [
            'form_edit' => $form,
            'post' => $post,
        ]);
    }

    #[Route('/delete/{id<\d+>}', methods: ['GET', 'POST'])]
    #[IsGranted('ROLE_SUPER_ADMIN')]
    public function delete(
        Post $post,
        Request $request,
        EntityManagerInterface $manager,
        ValidatorInterface $validator,
    ): Response {
        $confirmationString = $post->getTitle();

        if ($this->isGranted('ROLE_SUPER_ADMIN')) {
            throw $this->createAccessDeniedException();
        }
        $this->denyAccessUnlessGranted('ROLE_SUPER_ADMIN');

        if (
            Request::METHOD_POST === $request->getMethod()
            && $this->isCsrfTokenValid('delete_post', $request->request->get('token'))
        ) {
            $userInput = $request->request->get('confirmation');
            $violations = $validator->validate($userInput, [
                new NotBlank(),
                new EqualTo($confirmationString),
            ]);
            if (0 === $violations->count()) {
                $manager->remove($post);
                $manager->flush();
                $this->addFlash('success', 'La publication a été définitivement supprimée.');

                return $this->redirectToRoute('app_post_index');
            }
        }

        return new Response($this->renderView('admin/post/delete.html.twig', [
            'post' => $post,
            'confirmation_string' => $confirmationString,
            'violations' => $violations ?? [],
        ]), isset($violations) ? Response::HTTP_UNPROCESSABLE_ENTITY : Response::HTTP_OK);
    }

    #[Route('/delete2/{id<\d+>}', methods: ['GET', 'POST'])]
    public function delete2(
        Post $post,
        Request $request,
        EntityManagerInterface $manager,
    ): Response {
        $confirmationString = $post->getTitle();
        $form = $this->createForm(DeleteType::class, options: [
            'help' => 'La suppression d\'une publication est irréversible. Tapez %s',
            'confirmation_message' => $confirmationString,
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $manager->remove($post);
            $manager->flush();
            $this->addFlash('success', 'La publication a été définitivement supprimée.');

            return $this->redirectToRoute('app_post_index');
        }

        return $this->render('admin/post/delete2.html.twig', [
            'post' => $post,
            'delete_form' => $form,
        ]);
    }
}
