<?php

namespace App\Api;

use App\Security\ApiAccessChecker;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/api/todo', name: 'api_todo_')]
final class ApiTodoController extends AbstractController
{
    public function __construct(private ApiAccessChecker $accessChecker) {}

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

        return $this->json(['success' => true], JsonResponse::HTTP_OK);
    }
}
