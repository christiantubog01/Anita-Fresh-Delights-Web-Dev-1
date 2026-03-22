<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegistrationFormType;
use App\Security\LoginAuthenticator;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;
use App\Service\ActivityLogger;

use App\Service\EmailVerificationService;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class RegistrationController extends AbstractController
{
    #[Route('/register', name: 'app_register')]
    public function register(Request $request, UserPasswordHasherInterface $userPasswordHasher, EntityManagerInterface $entityManager, EmailVerificationService $emailVerificationService,  ActivityLogger $activityLogger): Response
    {
        if ($this->getUser()) {
        $roles = $this->getUser()->getRoles();

        if (in_array('ROLE_ADMIN', $roles)) {
            return $this->redirectToRoute('app_dashboard'); // replace with your admin route
        }

        if (in_array('ROLE_STAFF', $roles)) {
            return $this->redirectToRoute('app_dashboard'); // replace with your staff route
        }

        // Default: regular user
        return $this->redirectToRoute('app_home'); // replace with your user home
    }
        $user = new User();
        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->handleRequest($request);
    
        if ($form->isSubmitted() && $form->isValid()) {
            /** @var string $plainPassword */
            $plainPassword = $form->get('plainPassword')->getData();

            // encode the plain password
            $user->setPassword($userPasswordHasher->hashPassword($user, $plainPassword));
            $user->setRoles(['ROLE_USER']);

            // Generate verification token
            $verificationToken = $emailVerificationService->generateVerificationToken();
            $user->setVerificationToken($verificationToken);
            $user->setIsVerified(false);



            $entityManager->persist($user);
            $entityManager->flush();



            // do anything else you need here, like send an email
// Generate verification URL
            $verificationUrl = $this->generateUrl(
                'app_verify_email',
                ['token' => $verificationToken],
                UrlGeneratorInterface::ABSOLUTE_URL
            );
            // Send verification email
            $emailVerificationService->sendVerificationEmail($user, $verificationUrl);            

            $this->addFlash('success', 'Registration successful! Please check your email to verify your account.');


            return $this->redirectToRoute('app_login');
        }

        return $this->render('registration/register.html.twig', [
            'registrationForm' => $form,
        ]);
    }
}
