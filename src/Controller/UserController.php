<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use App\Service\ActivityLogger;

#[Route('/user')]
final class UserController extends AbstractController
{
    #[Route(name: 'app_user_index', methods: ['GET'])]
    public function index(UserRepository $userRepository): Response
    {
        return $this->render('user/index.html.twig', [
            'users' => $userRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_user_new', methods: ['GET', 'POST'])]
    public function new(Request $request, UserPasswordHasherInterface $userPasswordHasher, EntityManagerInterface $entityManager): Response
    {
        $user = new User();
        $form = $this->createForm(UserType::class, $user, ['is_edit' => false,]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($user);

            $plainPassword = $form->get('password')->getData();
            $user->setPassword($userPasswordHasher->hashPassword($user, $plainPassword));
            $entityManager->flush();

            return $this->redirectToRoute('app_user_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('user/new.html.twig', [
            'user' => $user,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_user_show', methods: ['GET'])]
    public function show(User $user): Response
    {
        return $this->render('user/show.html.twig', [
            'user' => $user,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_user_edit', methods: ['GET', 'POST'])]
public function edit(
    Request $request,
    UserPasswordHasherInterface $userPasswordHasher,
    User $user,
    EntityManagerInterface $entityManager
): Response {
    $form = $this->createForm(UserType::class, $user, ['is_edit' => true,]);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {

        // Get plain password field
        $plainPassword = $form->get('password')->getData();

        // If admin typed a new password → hash it
        if ($plainPassword) {
            $hashed = $userPasswordHasher->hashPassword($user, $plainPassword);
            $user->setPassword($hashed);
        }

        // If NOT typed → keep old hashed password (do nothing)

        $entityManager->flush();

        return $this->redirectToRoute('app_user_index');
    }

    return $this->render('user/edit.html.twig', [
        'user' => $user,
        'form' => $form,
    ]);
}

    #[Route('/{id}', name: 'app_user_delete', methods: ['POST'])]
public function delete(Request $request, User $user, EntityManagerInterface $entityManager, ActivityLogger $activityLogger): Response
{
    if ($this->isCsrfTokenValid('delete'.$user->getId(), $request->request->get('_token'))) {
        $roles = implode(', ', $user->getRoles());
        // Log before deletion
        $activityLogger->log(
            "DELETE",
            "User deleted: " . $user->getUsername() . " (ID: " . $user->getId() . ", Roles: " . $roles . ")"
        );

        $entityManager->remove($user);
        $entityManager->flush();
    }

    return $this->redirectToRoute('app_user_index', [], Response::HTTP_SEE_OTHER);
}


}
