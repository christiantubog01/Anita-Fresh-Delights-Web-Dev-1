<?php

namespace App\Security;

use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use KnpU\OAuth2ClientBundle\Client\ClientRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Http\Authenticator\AbstractAuthenticator;
use Symfony\Component\Security\Http\Authenticator\Passport\Passport;
use Symfony\Component\Security\Http\Authenticator\Passport\SelfValidatingPassport;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\UserBadge;
use League\OAuth2\Client\Provider\GoogleUser;

class GoogleAuthenticator extends AbstractAuthenticator
{
    public function __construct(
        private ClientRegistry $clientRegistry,
        private UserRepository $userRepository,
        private EntityManagerInterface $em
    ) {}

    public function supports(Request $request): ?bool
    {
        // Trigger only on Google callback
        return $request->attributes->get('_route') === 'connect_google_check';
    }

    public function authenticate(Request $request): Passport
    {
        $client = $this->clientRegistry->getClient('google');

        /** @var GoogleUser $googleUser */
        $googleUser = $client->fetchUser();

        $email = $googleUser->getEmail();
        $fullName = $googleUser->getName() ?? explode('@', $email)[0];
        $nameParts = explode(' ', $fullName, 2);
        $firstName = $nameParts[0];
        $lastName = $nameParts[1] ?? '';

        return new SelfValidatingPassport(
            new UserBadge($email, function () use ($email, $firstName, $lastName) {

                // Check if user already exists
                $user = $this->userRepository->findOneBy(['email' => $email]);

                if (!$user) {
                    // Create new user
                    $user = new User();
                    $user->setEmail($email);

                    // Generate unique username
                    $username = strtolower($firstName);
                    $existing = $this->userRepository->findOneBy(['username' => $username]);
                    if ($existing) {
                        $username .= rand(1000, 9999);
                    }
                    $user->setUsername($username);

                    $user->setRoles(['ROLE_USER']);
                    $user->setPassword(''); // no password for OAuth
                    $user->setIsVerified(true);

                    // Required fields
                    $user->setFirstName($firstName);
                    $user->setLastName($lastName);
                    $user->setBirthDate(new \DateTime('2000-01-01')); // default/fallback
                    $user->setDateTimeCreated(new \DateTime());

                    $this->em->persist($user);
                    $this->em->flush();
                }

                return $user;
            })
        );
    }

    public function onAuthenticationSuccess(Request $request, $token, string $firewallName): ?RedirectResponse
    {
        // Redirect after successful login
        return new RedirectResponse('/home'); // change to your home route
    }

    public function onAuthenticationFailure(Request $request, AuthenticationException $exception): ?RedirectResponse
    {
        // Redirect on failure
        return new RedirectResponse('/login');
    }
}