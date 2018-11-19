<?php

namespace App\Service\Adaptor;

use App\Entity\AdEntity;
use DateTime;

class AdEntityAdaptor
{
    public function toArray(AdEntity $adEntity): array
    {
        return [
            'id' => $adEntity->getId(),
            'user' => [
                'name' => $adEntity->getUser()->getName(),
                'email' => $adEntity->getUser()->getEmail()
            ],
            'title' => $adEntity->getTitle(),
            'description' => $adEntity->getDescription(),
            'price' => $adEntity->getPrice(),
            'creationDate' => $adEntity->getCreationDate()->format(DateTime::ATOM)
        ];
    }
}