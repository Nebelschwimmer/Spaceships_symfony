<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;
use App\Entity\User;
use Symfony\Component\Security\Http\Attribute\CurrentUser;



class ApiLoginController extends AbstractController
{
    #[Route('/api/login', name: 'api_login', methods: ['POST']) ]
    public function index(#[CurrentUser] ?User $user): JsonResponse
    {
        if (null === $user) {
            return new JsonResponse([
                'message' => 'missing credentials',
            ], 401);
        }
        
        return $this->json([
            'user'  => $user->getUserIdentifier(),
            'id' => $user->getId(),
            'message' => 'Welcome ' . $user->getUserIdentifier(),
        ]);
    }

}