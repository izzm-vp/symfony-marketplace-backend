<?php

namespace App\Controller;

use App\Entity\User;
use App\Service\AuthService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Cookie;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Http\Attribute\CurrentUser;
use Symfony\Component\Serializer\SerializerInterface;

class AuthController extends AbstractController
{
    #[Route('/api/register', name: 'register', methods: ['POST'])]
    public function register(Request $request, AuthService $authService): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        if (!isset($data['email']) || !isset($data['password'])) {
            return $this->json(
                ['error' => 'Email and password are required'],
                Response::HTTP_BAD_REQUEST
            );
        }

        // attribuez le role d'utilisateur par défaut
        $role = $data['role'] ?? ['ROLE_USER'];

        if (!is_array($role)) {

            $role = [$role];
        }

        try {
            $user = $authService->register($data['email'], $data['password'], $role);
            return $this->json(
                [
                    'message' => 'Registration successful. Check your email to verify.',
                    'email' => $user->getEmail(),
                    'roles' => $user->getRoles()
                ],
                Response::HTTP_CREATED
            );
        } catch (\RuntimeException $e) {
            return $this->json(
                ['error' => $e->getMessage()],
                Response::HTTP_BAD_REQUEST
            );
        }
    }

    // verifie l'email
    #[Route('/api/verify-email', name: 'verify_email', methods: ['POST'])]
    public function verifyEmail(Request $request, AuthService $authService): Response
    {
        $data = json_decode($request->getContent(), true);

        if (!isset($data['token'])) {
            return $this->json(
                ['error' => 'Token is required'],
                Response::HTTP_BAD_REQUEST
            );
        }

        try {
            $user = $authService->verifyEmail($data['token']);
            $response = new JsonResponse([
                'message' => 'Email verified successfully!',
                'user' => [
                    'email' => $user->getEmail(),
                    'isVerified' => true
                ]
            ]);

            $cookie = $authService->refreshToken($user);
            $response->headers->setCookie($cookie);

            return $response;
        } catch (\RuntimeException $e) {
            return $this->json(
                ['error' => $e->getMessage()],
                Response::HTTP_BAD_REQUEST
            );
        }
    }

    #[Route('/api/login', name: 'login', methods: ['POST'], priority: 1)]
    public function login(
        Request $request,
        AuthService $authService,
        SerializerInterface $serializer

    ): Response {
        $data = json_decode($request->getContent(), true);

        if (!isset($data['email']) || !isset($data['password'])) {
            return $this->json(
                ['error' => 'Email and password are required'],
                Response::HTTP_BAD_REQUEST
            );
        }

        try {

            $result = $authService->login($data['email'], $data['password']);

            $userData = $serializer->serialize($result['user'], 'json', [
                'groups' => ['user:read']
            ]);

            $response = new JsonResponse([
                'message' => 'Login successful',
                'user' => [
                    'email' => $result['user']->getEmail(),
                    'user' => json_decode($userData, true)
                ]
            ]);
            $response->headers->setCookie($result['cookie']);
            return $response;
        } catch (\Exception $e) {
            return $this->json(
                ['error' => 'Invalid credentials'],
                Response::HTTP_UNAUTHORIZED
            );
        }
    }

    #[Route('/api/me', name: 'get_authenticated_user', methods: ['POST'])]
    public function getAuthenticatedUser(
        Request $request,
        AuthService $authService,
        SerializerInterface $serializer
    ): Response {
        try {

            $user = $authService->getUser($request);

            $userData = $serializer->serialize($user, 'json', [
                'groups' => ['user:read']
            ]);

            return new JsonResponse([
                'user' => json_decode($userData, true)
            ]);
        } catch (AuthenticationException $e) {
            return $this->json(
                ['error' => $e->getMessage()],
                Response::HTTP_UNAUTHORIZED
            );
        } catch (\Exception $e) {
            return $this->json(
                ['error' => 'An error occurred while fetching user data'],
                Response::HTTP_INTERNAL_SERVER_ERROR
            );
        }
    }

    #[Route('/api/refresh-token', name: 'refresh_token', methods: ['POST'])]
    public function refreshToken(
        #[CurrentUser] ?User $user,
        AuthService $authService
    ): Response {
        if (!$user) {
            return $this->json(
                ['error' => 'Invalid session'],
                Response::HTTP_UNAUTHORIZED
            );
        }

        $response = new JsonResponse([
            'message' => 'Token refreshed',
            'user' => [
                'email' => $user->getEmail(),
            ]
        ]);
        $response->headers->setCookie($authService->refreshToken($user));
        return $response;
    }

    #[Route('/api/logout', name: 'logout', methods: ['POST'])]
    public function logout(): JsonResponse
    {
        // créer un cookie access_token expiré pour remplacer celui existant
        $expiredCookie = new Cookie(
            'access_token',
            '',
            new \DateTime('@0'),
            '/',
            null,
            false,
            true,
            false,
            'strict'
        );

        $response = new JsonResponse([
            'message' => 'Logged out successfully.'
        ]);

        $response->headers->setCookie($expiredCookie);

        return $response;
    }
}
