<?php

namespace App\Controller\Api;

use App\Entity\Topic;
use App\Form\Topic1Type;
use App\Repository\TopicRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/api/topic', name: 'app_api_topic_')]
final class ApiTopicController extends AbstractController
{
    #[Route('/get-all', name: 'get_all', methods: ['GET'])]
    public function index(TopicRepository $topicRepository): Response
    {
        return $this->render('api_topic/index.html.twig', [
            'topics' => $topicRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $topic = new Topic();
        $form = $this->createForm(Topic1Type::class, $topic);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($topic);
            $entityManager->flush();

            return $this->redirectToRoute('app_api_topic_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('api_topic/new.html.twig', [
            'topic' => $topic,
            'form' => $form,
        ]);
    }

    #[Route('/get/{id}', name: 'get_by_id', methods: ['GET'])]
    public function show(Topic $topic): Response
    {
        return $this->render('api_topic/show.html.twig', [
            'topic' => $topic,
        ]);
    }

    #[Route('/edit/{id}', name: 'edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Topic $topic, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(Topic1Type::class, $topic);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_api_topic_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('api_topic/edit.html.twig', [
            'topic' => $topic,
            'form' => $form,
        ]);
    }

    #[Route('/delete/{id}', name: 'delete', methods: ['POST'])]
    public function delete(Request $request, Topic $topic, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$topic->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($topic);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_api_topic_index', [], Response::HTTP_SEE_OTHER);
    }
}
