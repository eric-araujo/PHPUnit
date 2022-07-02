<?php

namespace OrderBundle\Test\Service;

use OrderBundle\Repository\BadWordsRepository;
use OrderBundle\Service\BadWordsValidator;
use PHPUnit\Framework\TestCase;

class BadWordsValidatorTest extends TestCase
{
    public function testHasBadWords()
    {
        $badWordsRepository = $this->createMock(BadWordsRepository::class);
        $badWordsRepository->method("findAllAsArray")
        ->willReturn(["feio", "bobo", "ruim"]);

        $badWordsValidator = new BadWordsValidator($badWordsRepository);

        $hasBadWords = $badWordsValidator->hasBadWords("Seu programa é feio");

        $this->assertEquals(true, $hasBadWords);
    }
}