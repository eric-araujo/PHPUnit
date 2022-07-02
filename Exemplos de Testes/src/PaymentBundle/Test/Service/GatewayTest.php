<?php

namespace PaymentBundle\Test\Service;

use DateTime;
use MyFramework\HttpClientInterface;
use MyFramework\LoggerInterface;
use PaymentBundle\Service\Gateway;
use PHPUnit\Framework\TestCase;

class GatewayTest extends TestCase
{
    /**
     * @dataProvider returnProviders
     */
    public function testShouldNotPayWhenAuthenticationFail(array $map, array $authenticate, int $numberOfSendMethodCalls, bool $expectedValue, DateTime $validity)
    {
        $httpClient = $this->createMock(HttpClientInterface::class);
        $user = $authenticate["user"];
        $password = $authenticate["password"];

        $httpClient
            ->expects($this->atLeast($numberOfSendMethodCalls))
            ->method("send")
            ->will($this->returnValueMap($map));

        $logger = $this->createMock(LoggerInterface::class);
        $gateway = new Gateway($httpClient, $logger, $user, $password);
        $creditCardData = $this->returnCreditCardData($validity);

        $paid = $gateway->pay(
            $creditCardData["name"],
            $creditCardData["credit_card_number"],
            $creditCardData["validity"],
            $creditCardData["value"]
        );

        $this->assertEquals($expectedValue, $paid);
    }

    public function returnProviders(): array
    {
        $validity = new DateTime("now");
        $creditCardData = $this->returnCreditCardData($validity);
        return [
            "shouldNotPayWhenAuthenticationFail" => GatewayTestDataProvider::testDataToShouldNotPayWhenAuthenticationFail($validity),
            "shouldNotPayWhenFailOnGateway" => GatewayTestDataProvider::testDataToShouldNotPayWhenFailOnGateway($validity, $creditCardData),
            "shouldSuccessfullyPayWhenGatewayReturnOk" => GatewayTestDataProvider::testDataToShouldSuccessfullyPayWhenGatewayReturnOk($validity, $creditCardData)
        ];
    }

    private function returnCreditCardData(DateTime $validity): array
    {
        return [
            'name' => "Eric Araújo",
            'credit_card_number' => 99999999,
            'validity' => $validity,
            'value' => 100,
            'token' => "meu-token"
        ];
    }
}
