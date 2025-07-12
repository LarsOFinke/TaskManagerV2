<?php

namespace App\Service;

use App\Entity\Topic;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Error;
use Symfony\Component\HttpFoundation\Request;

class TopicService
{
    public function __construct(private EntityManagerInterface $em) {}

    public function createNewTopic(Request $request, User $user): void
    {
        try {
            $data = json_decode($request->getContent(), true);
            $topic = new Topic();
            $topic->setUserRef($user);
            $topic->setName($data['topicName']);
            $this->em->persist($topic);
            $this->em->flush();
        } catch (Error $e) {
            throw new Error('Neues Topic konnte nicht erstellt werden: ' . $e);
        }
    }

    public function mapTopic(Topic $topic): array
    {
        return [
            'name' => $topic->getName(),
            'id' => $topic->getId(),
        ];
    }
}
