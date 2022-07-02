<?php

namespace OrderBundle\Service;

use FidelityProgramBundle\Service\FidelityProgramService;
use OrderBundle\Entity\CreditCard;
use OrderBundle\Entity\Customer;
use OrderBundle\Entity\Item;
use OrderBundle\Exception\BadWordsFoundException;
use OrderBundle\Exception\CustomerNotAllowedException;
use OrderBundle\Exception\ItemNotAvailableException;
use OrderBundle\Repository\OrderRepository;
use PaymentBundle\Entity\PaymentTransaction;
use PaymentBundle\Service\PaymentService;
use PHPUnit\Framework\TestCase;

class OrderServiceTest extends TestCase
{
    private FidelityProgramService $fidelityProgramService;
    private BadWordsValidator $badWordsValidador;
    private OrderRepository $orderRepository;
    private PaymentService $paymentService;

    private CreditCard $creditCard;
    private Customer $customer;
    private Item $item;

    private OrderService $orderService;

    public function setUp(): void
    {
        $this->fidelityProgramService = $this->createMock(FidelityProgramService::class);
        $this->badWordsValidador = $this->createMock(BadWordsValidator::class);
        $this->orderRepository = $this->createMock(OrderRepository::class);
        $this->paymentService = $this->createMock(PaymentService::class);

        $this->creditCard = $this->createMock(CreditCard::class);
        $this->customer = $this->createMock(Customer::class);
        $this->item = $this->createMock(Item::class);
    }

    public function testShouldNotProcessWhenCustomerIsNotAllowed()
    {
        $this->withOrderService()->withCustomerNotAllowed();

        $this->expectException(CustomerNotAllowedException::class);

        $this->orderService->process($this->customer, $this->item, "", $this->creditCard);
    }

    public function testShouldNotProcessWhenItemIsNotAvailable()
    {
        $this->withOrderService()
            ->withCustomerAllowed()
            ->withNotAvailableItem();

        $this->expectException(ItemNotAvailableException::class);

        $this->orderService->process($this->customer, $this->item, "", $this->creditCard);
    }

    public function testShouldNotProcessWhenBadWordsIsFound()
    {
        $this->withOrderService()
            ->withCustomerAllowed()
            ->withAvailableItem()
            ->withBadWordsFound();

        $this->expectException(BadWordsFoundException::class);

        $this->orderService->process($this->customer, $this->item, "", $this->creditCard);
    }

    public function testShouldSucessfullyProcess()
    {
        $this->withOrderService()
            ->withCustomerAllowed()
            ->withAvailableItem()
            ->withBadWordsNotFound();

        $paymentTransection = $this->createMock(PaymentTransaction::class);

        $this->paymentService
            ->method("pay")
            ->willReturn($paymentTransection);

        $this->orderRepository
            ->expects($this->once())
            ->method("save");

        $this->fidelityProgramService
            ->expects($this->once())
            ->method("addPoints");

        $createdOrder = $this->orderService->process($this->customer, $this->item, "", $this->creditCard);

        $this->assertNotEmpty($createdOrder->getPaymentTransaction());
    }

    private function withOrderService(): OrderServiceTest
    {
        $this->orderService = new OrderService(
            $this->badWordsValidador,
            $this->paymentService,
            $this->orderRepository,
            $this->fidelityProgramService
        );

        return $this;
    }

    private function withCustomerNotAllowed(): OrderServiceTest
    {
        $this->customer
            ->method("isAllowedToOrder")
            ->willReturn(false);

        return $this;
    }

    private function withCustomerAllowed(): OrderServiceTest
    {
        $this->customer
            ->method("isAllowedToOrder")
            ->willReturn(true);

        return $this;
    }

    private function withNotAvailableItem(): OrderServiceTest
    {
        $this->item
            ->method("isAvailable")
            ->willReturn(false);

        return $this;
    }

    private function withAvailableItem(): OrderServiceTest
    {
        $this->item
            ->method("isAvailable")
            ->willReturn(true);

        return $this;
    }

    private function withBadWordsFound(): OrderServiceTest
    {
        $this->badWordsValidador
            ->method("hasBadWords")
            ->willReturn(true);

        return $this;
    }

    private function withBadWordsNotFound(): OrderServiceTest
    {
        $this->badWordsValidador
            ->method("hasBadWords")
            ->willReturn(false);

        return $this;
    }
}
