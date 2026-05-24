<?php

namespace App\Controller;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Component\Routing\Attribute\Route;

class GoogleLoginController extends AbstractController
{
    #[Route('/api/google-login', name: 'api_google_login', methods: ['POST'])]
    public function googleLogin(
        Request $request,
        EntityManagerInterface $em,
        JWTTokenManagerInterface $jwtManager
    ): JsonResponse {

        try {

            $data = json_decode($request->getContent(), true);
            $idToken = $data['idToken'] ?? null;

            if (!$idToken) {
                return $this->json([
                    'message' => 'Missing Google token'
                ], 400);
            }

            $client = HttpClient::create();

            $response = $client->request(
                'GET',
                'https://oauth2.googleapis.com/tokeninfo',
                [
                    'query' => [
                        'id_token' => $idToken,
                    ],
                ]
            );

            if ($response->getStatusCode() !== 200) {
                return $this->json([
                    'message' => 'Invalid Google token'
                ], 401);
            }

            $googleUser = $response->toArray();

            $email = $googleUser['email'] ?? null;
            $firstName = $googleUser['given_name'] ?? '';
            $lastName = $googleUser['family_name'] ?? '';

            if (!$email) {
                return $this->json([
                    'message' => 'Google email not found'
                ], 400);
            }

            $user = $em->getRepository(User::class)
                ->findOneBy(['email' => $email]);

            // CREATE USER IF NOT EXISTS
            if (!$user) {
                $user = new User();

                $user->setEmail($email);
                $user->setFirstName($firstName);
                $user->setLastName($lastName);

                // IMPORTANT: still needed for your current schema
                $user->setUsername($email);

                $user->setRoles(['ROLE_USER']);
                $user->setIsVerified(true);

                // required fields
                $user->setPassword('');
                $user->setBirthDate(new \DateTime('2000-01-01'));

                $em->persist($user);
                $em->flush();
            }

            // 🔥 SAFETY: ensure user is fully managed by Doctrine
            $em->refresh($user);

            return $this->json([
                'user' => $user->getUserIdentifier(),
                'roles' => $user->getRoles(),
                'token' => $jwtManager->create($user),
            ]);

        } catch (\Throwable $e) {

            return $this->json([
                'message' => 'Server error',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}