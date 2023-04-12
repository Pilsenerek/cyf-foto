<?php

namespace App\Controller;

use App\Message\AddProduct;
use App\Message\UserStatus;
use App\Repository\ProductRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use App\Message\AddUser;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use JMS\Serializer\SerializerInterface;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\HttpFoundation\Request;

#[Route('/api', name: 'api')]
class UserController extends AbstractController
{

    #[Route('/user', name: 'user_index', methods: 'GET')]
    public function index(UserRepository $userRepository): JsonResponse
    {
        return $this->json($userRepository->findAll());
    }

    #[Route('/user/{id}', name: 'user_detail', methods: 'GET')]
    public function detail($id, UserRepository $userRepository): JsonResponse
    {
        return $this->json($userRepository->findOneBy(['id' => $id]));
    }

    #[Route('/user', name: 'user_create', methods: 'POST')]
    public function create(Request $request, MessageBusInterface $bus): JsonResponse
    {
        //@todo use normalizer instead
        $bus->dispatch(new AddUser(
            $request->get('firstName'),
            $request->get('lastName'),
            $request->get('email'),
            $request->get('address'),
            $request->get('active', true),
            $request->get('nip')
        ));

        return $this->json([], 202);
    }

    #[Route('/user/{id}/activate', name: 'user_activate', methods: 'POST')]
    public function activate($id, MessageBusInterface $bus): JsonResponse
    {
        $bus->dispatch(new UserStatus($id, true));

        return $this->json([], 202);
    }

    #[Route('/user/{id}/deactivate', name: 'user_deactivate', methods: 'POST')]
    public function deactivate($id, MessageBusInterface $bus): JsonResponse
    {
        $bus->dispatch(new UserStatus($id, false));

        return $this->json([], 202);
    }

}
