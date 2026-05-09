<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class ProfileController extends AbstractController
{
    #[Route('/profile', name: 'app_profile')]
    public function index(
        Request $request,
        EntityManagerInterface $em,
        UserPasswordHasherInterface $passwordHasher,
        UserRepository $userRepository
    ): Response {

        /** @var User $user */
        $user = $this->getUser();

        if (!$user instanceof User) {
            throw $this->createAccessDeniedException();
        }

        if ($request->isMethod('POST')) {

            // 🔹 USERNAME (NEW)
            $newUsername = $request->request->get('username');

            if ($newUsername && $newUsername !== $user->getUsername()) {

                $existing = $userRepository->findOneBy(['username' => $newUsername]);

                if ($existing) {
                    $this->addFlash('error', 'Username already taken.');
                } else {
                    $user->setUsername($newUsername);
                }
            }

            // 1. FIRST NAME
            $user->setFirstName($request->request->get('first_name'));

            // 2. LAST NAME
            $user->setLastName($request->request->get('last_name'));

            // 3. BIRTH DATE
            $birthDate = $request->request->get('birth_date');
            if ($birthDate) {
                $user->setBirthDate(new \DateTime($birthDate));
            }

            // 4. PASSWORD
            $newPassword = $request->request->get('new_password');
            if (!empty($newPassword)) {
                $hashed = $passwordHasher->hashPassword($user, $newPassword);
                $user->setPassword($hashed);
            }

            // 5. PROFILE PICTURE
            $file = $request->files->get('profile_picture');
            if ($file) {
                $filename = uniqid().'.'.$file->guessExtension();

                $file->move(
                    $this->getParameter('kernel.project_dir') . '/public/uploads/profile',
                    $filename
                );

                $user->setProfilePicture($filename);
            }

            $em->flush();

            $this->addFlash('success', 'Profile updated successfully!');
        }

        return $this->render('profile/index.html.twig', [
            'user' => $user
        ]);
    }
}