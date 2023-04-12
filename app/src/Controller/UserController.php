<?php

namespace App\Controller;

use App\Service\UserService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api', name: 'api')]
class UserController extends AbstractController
{

    #[Route('/user', name: 'user_index', methods: 'GET')]
    public function index(UserService $userService): JsonResponse
    {
        return $this->json($userService->findUsers());
    }

    #[Route('/user/{id}', name: 'user_detail', methods: 'GET')]
    public function detail($id, UserService $userService): JsonResponse
    {
        return $this->json($userService->findUser($id));
    }

    #[Route('/user', name: 'user_create', methods: 'POST')]
    public function create(UserService $userService): JsonResponse
    {
        $userService->addUser();

        return $this->json([], 202);
    }

    #[Route('/user/{id}/activate', name: 'user_activate', methods: 'POST')]
    public function activate($id, UserService $userService): JsonResponse
    {
        $userService->changeUserStatus($id, true);

        return $this->json([], 202);
    }

    #[Route('/user/{id}/deactivate', name: 'user_deactivate', methods: 'POST')]
    public function deactivate($id, UserService $userService): JsonResponse
    {
        $userService->changeUserStatus($id, false);

        return $this->json([], 202);
    }

}
