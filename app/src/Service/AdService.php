<?php

namespace App\Service;

use App\Entity\AdEntity;
use App\Entity\UserEntity;
use App\Exception\AdNotFoundException;
use App\Exception\PermissionDeniedException;
use App\Exception\TechnicalErrorException;
use App\Exception\UserNotFoundException;
use App\Repository\AdEntityRepository;
use App\Service\Publisher\SocialMediaPublisher;
use App\Service\Validator\MoneyValidator;
use App\Service\Validator\TitleValidator;
use Doctrine\Common\Persistence\ManagerRegistry;
use Exception;
use Psr\Http\Message\ResponseInterface;

class AdService
{
    /**
     * @var ManagerRegistry
     */
    private $managerRegistry;

    /**
     * @var MoneyValidator
     */
    private $moneyValidator;

    /**
     * @var TitleValidator
     */
    private $titleValidator;

    /**
     * @var SocialMediaPublisher
     */
    private $socialMediaPublisher;

    public function __construct(ManagerRegistry $managerRegistry, MoneyValidator $moneyValidator,
        TitleValidator $titleValidator, SocialMediaPublisher $socialMediaPublisher)
    {
        $this->managerRegistry = $managerRegistry;
        $this->moneyValidator = $moneyValidator;
        $this->titleValidator = $titleValidator;
        $this->socialMediaPublisher = $socialMediaPublisher;
    }

    /**
     * @return AdEntity[]
     */
    public function getAll()
    {
        /** @var AdEntityRepository $repository */
        $repository = $this->managerRegistry->getRepository(AdEntity::class);
        return $repository->findAllOrderByLatest();
    }

    /**
     * @param $userAuthToken
     * @param $title
     * @param $description
     * @param $price
     * @return AdEntity
     * @throws TechnicalErrorException
     * @throws UserNotFoundException
     * @throws Exception
     */
    public function create($userAuthToken, $title, $description, $price)
    {
        $this->moneyValidator->validate($price);
        $this->titleValidator->validate($title);

        $user = $this->managerRegistry->getRepository(UserEntity::class)->findByAuthToken($userAuthToken);

        if ($user == null) {
            throw new UserNotFoundException();
        }

        $adEntity = (new AdEntity())
            ->setUser($user)
            ->setTitle($title)
            ->setDescription($description)
            ->setPrice($price)
            ->setCreationDate(new \DateTime());

        $promise = $this->socialMediaPublisher->publish($adEntity);
        $published = false;

        $promise->then(function (ResponseInterface $response) use (&$published) {
            $published = $this->socialMediaPublisher->hasPublished($response);
        })->wait();

        if (!$published) {
            throw new Exception("Error publishing to social media");
        }

        $entityManager = $this->managerRegistry->getManager();
        $entityManager->persist($adEntity);
        $entityManager->flush();

        /** @var AdEntity $savedAdEntity */
        $savedAdEntity = $this->managerRegistry->getRepository(AdEntity::class)->find($adEntity->getId());

        if ($savedAdEntity == null) {
            throw new TechnicalErrorException();
        }

        return $savedAdEntity;
    }

    /**
     * @param $userAuthToken
     * @param $id
     * @param $title
     * @param $description
     * @param $price
     * @throws UserNotFoundException
     * @return AdEntity
     * @throws AdNotFoundException
     * @throws PermissionDeniedException
     * @throws TechnicalErrorException
     * @throws Exception
     */
    public function update($userAuthToken, $id, $title, $description, $price)
    {
        $this->moneyValidator->validate($price);
        $this->titleValidator->validate($title);

        /** @var UserEntity $user */
        $user = $this->managerRegistry->getRepository(UserEntity::class)->findByAuthToken($userAuthToken);

        if ($user == null) {
            throw new UserNotFoundException();
        }

        /** @var AdEntity $adEntity */
        $adEntity = $this->managerRegistry->getRepository(AdEntity::class)->find($id);

        if ($adEntity == null) {
            throw new AdNotFoundException();
        }

        if ($user->getId() !== $adEntity->getUser()->getId()) {
            throw new PermissionDeniedException();
        }

        $adEntity
            ->setTitle($title)
            ->setDescription($description)
            ->setPrice($price);

        $promise = $this->socialMediaPublisher->publish($adEntity);
        $published = false;

        $promise->then(function (ResponseInterface $response) use (&$published) {
            $published = $this->socialMediaPublisher->hasPublished($response);
        })->wait();

        if (!$published) {
            throw new Exception("Error publishing to social media");
        }

        $entityManager = $this->managerRegistry->getManager();
        $entityManager->persist($adEntity);
        $entityManager->flush();

        /** @var AdEntity $savedAdEntity */
        $savedAdEntity = $this->managerRegistry->getRepository(AdEntity::class)->find($adEntity->getId());

        if ($savedAdEntity == null) {
            throw new TechnicalErrorException();
        }

        return $savedAdEntity;
    }
}