<?php

namespace App\Service;

use App\Entity\Topic;

class TopicService
{
    public function mapTopic(Topic $topic)
    {
        return [
            'name' => $topic->getName(),
            'id' => $topic->getId(),
        ];
    }
}
