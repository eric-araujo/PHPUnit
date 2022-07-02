<?php

namespace PaymentBundle\Test\Service;

use PaymentBundle\Repository\PaymentTransactionRepository;
use PaymentBundle\Service\PaymentService;
use PaymentBundle\Service\Gateway;
use OrderBundle\Entity\CreditCard;
use OrderBundle\Entity\Customer;
use PHPUnit\Framework\TestCase;
use OrderBundle\Entity\Item;
use PaymentBundle\Exception\PaymentErrorException;

class PaymentServiceTest extends TestCase
{
    private PaymentTransactionRepository $paymentTransactionRepository;
    private PaymentService $paymentService;
    private Gateway $gateway;

    private CreditCard $creditCard;
    private Customer $customer;
    private Item $item;


    //CHAMADO NO INICIO DE CADA TESTE
    public function setUp(): void
    {
        $this->gateway = $this->createMock(Gateway::class);
        $this->paymentTransactionRepository = $this->createMock(PaymentTransactionRepository::class);

        $this->paymentService = new PaymentService($this->gateway, $this->paymentTransactionRepository);

        $this->customer = $this->createMock(Customer::class);
        $this->item = $this->createMock(Item::class);
        $this->creditCard = $this->createMock(CreditCard::class);
    }

    public function testShouldSaveWhenGatewayReturnOkWithRetries(): void
    {
        $numberOfPaymentAttempts = 3;
        $this->gateway
        ->expects($this->atLeast($numberOfPaymentAttempts))
        ->method("pay")
        ->will($this->onConsecutiveCalls(
            false, false, true
        ));

        $this->paymentTransactionRepository
        ->expects($this->once())
        ->method("save");

        $this->paymentService->pay($this->customer, $this->item, $this->creditCard);
    }

    public function testShouldThrowExceptionWhenGatewayFails(): void
    {
        $numberOfPaymentAttempts = 3;
        $this->gateway
        ->expects($this->atLeast($numberOfPaymentAttempts))
        ->method("pay")
        ->will($this->onConsecutiveCalls(
            false, false, false
        ));

        $this->paymentTransactionRepository
        ->expects($this->never())
        ->method("save");

        $this->expectException(PaymentErrorException::class);

        $this->paymentService->pay($this->customer, $this->item, $this->creditCard);
    }

    //CHAMADO NO FINAL DE CADA TESTE
    public function tearDown(): void
    {
        unset($this->paymentTransactionRepository);
        unset($this->paymentService);
        unset($this->gateway);

        unset($this->creditCard);
        unset($this->customer);
        unset($this->item);
    }
}
