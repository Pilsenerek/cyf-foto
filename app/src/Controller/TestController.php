<?php

namespace App\Controller;

use App\Repository\TestRepository;
use App\Service\Test;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

class TestController extends AbstractController
{
    #[Route('/test', name: 'app_test')]
    public function index(Test $test, TestRepository $testRepository): JsonResponse
    {
        $tests = $testRepository->findAll();
        //thanks to symfony/serializer-pack we can serialize data automatically
        return $this->json($tests);
    }
}
