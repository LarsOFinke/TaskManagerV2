<?php

namespace App\Api;

use Error;
use App\Repository\TaskRepository;
use App\Security\ApiAccessChecker;
use App\Service\TaskService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/api/task', name: 'api_task_')]
final class ApiTaskController extends AbstractController
{
    public function __construct(private ApiAccessChecker $accessChecker, private TaskService $taskService) {}

    #[Route('/get-all', name: 'get_all', methods: ['GET'])]
    public function getAll(TaskRepository $taskRepository): JsonResponse
    {
        return $this->render('api_task/index.html.twig', [
            'tasks' => $taskRepository->findAll(),
        ]);
    }

    #[Route('/create', name: 'create', methods: ['POST'])]
    public function create(Request $request): JsonResponse
    {
        // Security-checks
        $csrfToken = $request?->headers->get('X-CSRF-TOKEN');
        try {
            $this->accessChecker->ensureCsrfValid('task_api', $csrfToken);
        } catch (BadRequestHttpException $e) {
            return $this->json([
                'success' => false,
                'errors'  => $e->getMessage()
            ], JsonResponse::HTTP_BAD_REQUEST);
        }

        try {
            $this->taskService->createNewTask($request, $this->getUser());
        } catch (Error $e) {
            return $this->json([
                'success' => false,
                'errors'  => $e->getMessage()
            ], JsonResponse::HTTP_INTERNAL_SERVER_ERROR);
        }

        return new JsonResponse(['success' => true], JsonResponse::HTTP_CREATED);
    }
}
