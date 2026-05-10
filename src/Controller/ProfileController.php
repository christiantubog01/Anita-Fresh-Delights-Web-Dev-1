<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Service\ActivityLogger;

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
        UserRepository $userRepository,
        ActivityLogger $activityLogger
    ): Response {

        /** @var User $user */
        $user = $this->getUser();

        if (!$user instanceof User) {
            throw $this->createAccessDeniedException();
        }

        if ($request->isMethod('POST')) {

            $changes = [];

            // =========================
            // USERNAME
            // =========================
            $newUsername = $request->request->get('username');

            if ($newUsername && $newUsername !== $user->getUsername()) {

                $existing = $userRepository->findOneBy([
                    'username' => $newUsername
                ]);

                if ($existing) {

                    $this->addFlash('error', 'Username already taken.');

                } else {

                    $changes[] = "Username changed from '{$user->getUsername()}' to '{$newUsername}'";

                    $user->setUsername($newUsername);
                }
            }

            // =========================
            // FIRST NAME
            // =========================
            $firstName = $request->request->get('first_name');

            if ($firstName !== $user->getFirstName()) {

                $changes[] = "First name updated";

                $user->setFirstName($firstName);
            }

            // =========================
            // LAST NAME
            // =========================
            $lastName = $request->request->get('last_name');

            if ($lastName !== $user->getLastName()) {

                $changes[] = "Last name updated";

                $user->setLastName($lastName);
            }

            // =========================
            // BIRTH DATE
            // =========================
            $birthDate = $request->request->get('birth_date');

            if ($birthDate) {

                $newBirthDate = new \DateTime($birthDate);

                if (
                    !$user->getBirthDate() ||
                    $user->getBirthDate()->format('Y-m-d') !== $newBirthDate->format('Y-m-d')
                ) {

                    $changes[] = "Birth date updated";

                    $user->setBirthDate($newBirthDate);
                }
            }

            // =========================
            // PASSWORD
            // =========================
            $newPassword = $request->request->get('new_password');

            if (!empty($newPassword)) {

                $hashed = $passwordHasher->hashPassword($user, $newPassword);

                $user->setPassword($hashed);

                $changes[] = "Password changed";
            }

            // =========================
            // PROFILE PICTURE
            // =========================
            $file = $request->files->get('profile_picture');

            if ($file) {

                $filename = uniqid() . '.' . $file->guessExtension();

                $file->move(
                    $this->getParameter('kernel.project_dir') . '/public/uploads/profile',
                    $filename
                );

                $user->setProfilePicture($filename);

                $changes[] = "Profile picture updated";
            }

            // =========================
            // SAVE
            // =========================
            $em->flush();

            // =========================
            // ACTIVITY LOG
            // =========================
            if (!empty($changes)) {

                $activityLogger->log(
                    "UPDATE",
                    "User profile updated by {$user->getUsername()} | " . implode(', ', $changes)
                );
            }

            $this->addFlash('success', 'Profile updated successfully!');
        }

        return $this->render('profile/index.html.twig', [
            'user' => $user
        ]);
    }
}