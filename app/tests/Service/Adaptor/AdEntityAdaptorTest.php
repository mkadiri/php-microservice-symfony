<?php

namespace App\tests\Service\Adaptor;

use App\Entity\AdEntity;
use App\Entity\UserEntity;
use App\Service\Adaptor\AdEntityAdaptor;
use DateTime;
use PHPUnit\Framework\TestCase;

class AdEntityAdaptorTest extends TestCase
{
    public function testValidToArray()
    {
        $id = 1;
        $userName = 'john smith';
        $userEmail = 'test@email.com';
        $title = 'ad 1';
        $description = 'my first ad';
        $price = 99.99;
        $creationDate = new \DateTime();

        $adEntity = $this->getMockBuilder(AdEntity::class)->getMock();
        $adEntity->expects($this->once())->method('getId')->will($this->returnValue($id));

        $userEntity = $this->getMockBuilder(UserEntity::class)->getMock();
        $userEntity->expects($this->once())->method('getName')->will($this->returnValue($userName));
        $userEntity->expects($this->once())->method('getEmail')->will($this->returnValue($userEmail));

        $adEntity->expects($this->exactly(2))->method('getUser')->will($this->returnValue($userEntity));

        $adEntity->expects($this->once())->method('getTitle')->will($this->returnValue($title));
        $adEntity->expects($this->once())->method('getDescription')->will($this->returnValue($description));
        $adEntity->expects($this->once())->method('getPrice')->will($this->returnValue($price));
        $adEntity->expects($this->once())->method('getCreationDate')->will($this->returnValue($creationDate));


        $adEntityArray = (new AdEntityAdaptor())->toArray($adEntity);

        $expected = [
            'id' => $id,
            'user' => [
                'name' => $userName,
                'email' => $userEmail
            ],
            'title' => $title,
            'description' => $description,
            'price' => $price,
            'creationDate' => $creationDate->format(DateTime::ATOM)

        ];

        $this->assertEquals($expected, $adEntityArray);
    }
}