<?php

namespace App\Controller\Api;

use App\Entity\Topic;
use App\Form\TopicType;
use App\Repository\TopicRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/api/topic', name: 'api_topic_')]
final class ApiTopicController extends AbstractController
{
    #[Route('/get-all', name: 'get_all', methods: ['GET'])]
    public function getAll(TopicRepository $topicRepository): JsonResponse
    {
        // findAll() returns an array, so use array_map:
        $topicList = array_map(
            fn(Topic $t): array => [
                'id'   => $t->getId(),
                'name' => $t->getName(),
            ],
            $topicRepository->findAll()
        );

        // return JSON with 200 status:
        return $this->json([
            'topicList' => $topicList,
        ], JsonResponse::HTTP_OK);
    }

    #[Route('/create', name: 'create', methods: ['GET', 'POST'])]
    public function create(Request $request, EntityManagerInterface $entityManager): Response
    {
        $topic = new Topic();
        $form = $this->createForm(TopicType::class, $topic);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($topic);
            $entityManager->flush();

            return $this->redirectToRoute('api_topic_get_all', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('api_topic/new.html.twig', [
            'topic' => $topic,
            'form' => $form,
        ]);
    }

    #[Route('/get/{id}', name: 'get_by_id', methods: ['GET'])]
    public function getById(Topic $topic): Response
    {
        return $this->json([
            'topic' => [
                "id"    => $topic->getId(),
                "name"  => $topic->getName(),
            ],
        ]);
    }

    #[Route('/edit/{id}', name: 'edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Topic $topic, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(TopicType::class, $topic);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('api_topic_get_all', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('api_topic/edit.html.twig', [
            'topic' => $topic,
            'form' => $form,
        ]);
    }

    #[Route('/delete/{id}', name: 'delete', methods: ['POST'])]
    public function delete(Request $request, Topic $topic, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete' . $topic->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($topic);
            $entityManager->flush();
        }

        return $this->redirectToRoute('api_topic_get_all', [], Response::HTTP_SEE_OTHER);
    }
}
