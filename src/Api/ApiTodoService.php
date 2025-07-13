<?php

namespace App\Api;

use App\Security\ApiAccessChecker;
use App\Service\TodoService;
use Error;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/api/todo', name: 'api_todo_')]
final class ApiTodoService extends AbstractController
{
    public function __construct(private ApiAccessChecker $accessChecker, private TodoService $todoService) {}

    #[Route('/close', name: 'close', methods: ['POST'])]
    public function closeTodo(Request $request): JsonResponse
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
            $data = json_decode($request->getContent(), true);
            $this->todoService->closeTodo($data['todoId']);
        } catch (Error $e) {
            return $this->json(['success' => false], JsonResponse::HTTP_INTERNAL_SERVER_ERROR);
        }

        return $this->json(['success' => true], JsonResponse::HTTP_OK);
    }

    #[Route('/open', name: 'open', methods: ['POST'])]
    public function openTodo(Request $request): JsonResponse
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
            $data = json_decode($request->getContent(), true);
            $this->todoService->openTodo($data['todoId']);
        } catch (Error $e) {
            return $this->json(['success' => false], JsonResponse::HTTP_INTERNAL_SERVER_ERROR);
        }

        return $this->json(['success' => true], JsonResponse::HTTP_OK);
    }
}
