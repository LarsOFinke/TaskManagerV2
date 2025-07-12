<?php

namespace App\Api;

use App\Entity\Topic;
use App\Repository\TopicRepository;
use App\Security\ApiAccessChecker;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/api/topic', name: 'api_topic_')]
final class ApiTopicService extends AbstractController
{
    public function __construct(private ApiAccessChecker $accessChecker) {}

    #[Route('/get-all', name: 'get_all', methods: ['GET'])]
    public function getAll(TopicRepository $topicRepository): JsonResponse
    {
        $topicList = array_map(
            fn(Topic $t): array => [
                'id'   => $t->getId(),
                'name' => $t->getName(),
            ],
            $topicRepository->findAll()
        );

        return $this->json([
            'topicList' => $topicList,
        ], JsonResponse::HTTP_OK);
    }

    #[Route('/create', name: 'create', methods: ['GET', 'POST'])]
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

        return $this->json(['success' => true], JsonResponse::HTTP_OK);
    }
}
