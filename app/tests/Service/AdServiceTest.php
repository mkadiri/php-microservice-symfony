<?php

namespace App\Tests\Service;

use App\Exception\UserNotFoundException;
use App\Repository\UserEntityRepository;
use App\Service\AdService;
use App\Service\Publisher\SocialMediaPublisher;
use App\Service\Validator\MoneyValidator;
use App\Service\Validator\TitleValidator;
use PHPUnit\Framework\TestCase;
use Symfony\Bridge\Doctrine\ManagerRegistry;

class AdServiceTest extends TestCase
{
    public function testCreateInvalidUser()
    {
        $managerRegistry = $this->getMockBuilder(ManagerRegistry::class)->disableOriginalConstructor()->getMock();
        $moneyValidator = $this->getMockBuilder(MoneyValidator::class)->getMock();
        $titleValidator = $this->getMockBuilder(TitleValidator::class)->getMock();
        $socialMediaPublisher = $this->getMockBuilder(SocialMediaPublisher::class)->disableOriginalConstructor()->getMock();


        $moneyValidator->expects($this->once())->method('validate')->willReturn(null);
        $titleValidator->expects($this->once())->method('validate')->willReturn(null);

        $repository = $this->getMockBuilder(UserEntityRepository::class)->disableOriginalConstructor()->getMock();
        $repository->expects($this->once())->method('findByAuthToken')->willReturn(null);
        $managerRegistry->expects($this->once())->method('getRepository')->willReturn($repository);


        $this->expectException(UserNotFoundException::class);

        $adService = (new AdService($managerRegistry, $moneyValidator, $titleValidator, $socialMediaPublisher));
        $adService->create('anAuthToken', 'aTitle', 'aDescr', 10);
    }
}