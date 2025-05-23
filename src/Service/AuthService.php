<?php

namespace App\Service;

use App\Entity\User;
use App\Repository\UserRepository;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use Symfony\Component\HttpFoundation\Cookie;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\HttpFoundation\Request;

class AuthService
{
    public function __construct(
        private UserRepository $userRepository,
        private UserPasswordHasherInterface $passwordHasher,
        private JWTTokenManagerInterface $jwtManager,
        private MailerInterface $mailer,
        private string $appFrontendUrl,
    ) {}

    public function register(string $email, string $plainPassword, array $role = ["ROLE_USER"]): User
    {
        if ($this->userRepository->findByEmail($email)) {
            throw new \RuntimeException('Email already exists');
        }

        $user = new User();
        $user->setEmail($email)
            ->setPassword($this->passwordHasher->hashPassword($user, $plainPassword))
            ->setIsVerified(false)
            ->setRoles($role);



        $verificationToken = $this->jwtManager->create($user);
        $this->sendVerificationEmail($user, $verificationToken);

        $this->userRepository->save($user);
        return $user;
    }

    public function verifyEmail(string $token): User
    {
        try {
            $payload = $this->jwtManager->parse($token);

            if (!isset($payload['username'])) {
                throw new \RuntimeException('Invalid token format');
            }

            if (isset($payload['exp']) && $payload['exp'] < time()) {
                throw new \RuntimeException('Verification link has expired');
            }

            $user = $this->userRepository->findByEmail($payload['username']);
            if (!$user) {
                throw new \RuntimeException('User not found');
            }

            if ($user->isVerified()) {
                throw new \RuntimeException('Email already verified');
            }

            $user->setIsVerified(true);
            $this->userRepository->save($user);

            return $user;
        } catch (\Exception $e) {
            throw new \RuntimeException('Verification failed: ' . $e->getMessage());
        }
    }

    public function login(string $email, string $password): array
    {
        $user = $this->userRepository->findByEmail($email);

        if (!$user->isVerified()) {
            throw new AuthenticationException('Not Verified');
        }

        if (!$user || !$this->passwordHasher->isPasswordValid($user, $password)) {
            throw new AuthenticationException('Invalid credentials');
        }

        return [
            'user' => $user,
            'cookie' => $this->createAuthCookie($user)
        ];
    }

    // renvoie l'utilisateur authentifié à partir du cookie JWT access_token
    public function getUser(Request $request): User
    {
        
        $accessToken = $request->cookies->get('access_token');

        if (!$accessToken) {
            throw new AuthenticationException('No access token token found');
        }

        try {

            $payload = $this->jwtManager->parse($accessToken);

            if (!isset($payload['username'])) {
                throw new AuthenticationException('Invalid token format');
            }

            if (isset($payload['exp']) && $payload['exp'] < time()) {
                throw new AuthenticationException('Token has expired');
            }

            $user = $this->userRepository->findByEmail($payload['username']);

            if (!$user) {
                throw new AuthenticationException('User not found');
            }

            if (!$user->isVerified()) {
                throw new AuthenticationException('User not verified');
            }

            return $user;
        } catch (\Exception $e) {
            throw new AuthenticationException('Authentication failed: ' . $e->getMessage());
        }
    }


    // actualise access_token lorsqu'il expire
    public function refreshToken(User $user): Cookie
    {
        return $this->createAuthCookie($user);
    }

    private function createAuthCookie(User $user): Cookie
    {
        return new Cookie(
            'access_token',
            $this->jwtManager->create($user),
            new \DateTime('+1 hour'),
            '/',
            null,
            false, // uniquement phase de dev
            true,  // HTTP-only pas accessible par JS
            false,
            'strict'
        );
    }

    private function sendVerificationEmail(User $user, string $token): void
    {
        $verificationUrl = $this->appFrontendUrl . '/verify-email?token=' . urlencode($token);

        $email = (new Email())
            ->from($_ENV['MAILER_FROM'] ?? 'noreply@example.com')
            ->to($user->getEmail())
            ->subject('Verify Your Email Address')
            ->html(sprintf(
                '<h2>Email Verification</h2>
                <p>Click the button below to verify your email address:</p>
                <a href="%s" style="%s">Verify Email</a>',
                htmlspecialchars($verificationUrl, ENT_QUOTES, 'UTF-8'),
                'display: inline-block; padding: 10px 20px; background: #0066cc; color: white; text-decoration: none; border-radius: 4px;'
            ));

        try {
            $this->mailer->send($email);
        } catch (TransportExceptionInterface $e) {
            throw new \RuntimeException('Failed to send verification email.');;
        }
    }
}
