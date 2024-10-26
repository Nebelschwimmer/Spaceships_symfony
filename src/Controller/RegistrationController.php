<?php

namespace App\Controller;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\JsonResponse;

class RegistrationController extends AbstractController
{
    #[Route('/api/register', name: 'api_register', methods: ['POST'])]
    public function register(UserPasswordHasherInterface $passwordHasher, Request $request, EntityManagerInterface $entityManager): JsonResponse
    {
        $email = $request->getPayload()->get('username');
        $displayName = $request->getPayload()->get('displayName');
        $avatarURL = $request->getPayload()->get('avatarURL');
        $plainPassword = $request->getPayload()->get('password');

        $user = new User();
        $user->setEmail($email);
        $user->setRoles(['ROLE_USER']);
        $hashedPassword = $passwordHasher->hashPassword(
            $user,
            $plainPassword
        );
        if ($displayName) {
            $user->setUsername($displayName);
        }
        if ($avatarURL) {
            $user->setAvatar($avatarURL);
        }

        $user->setPassword($hashedPassword);
        $entityManager->persist($user);
        $entityManager->flush();
        return new JsonResponse([
            'message' => 'User successfully registered',
        ], Response::HTTP_CREATED);
    }
}
