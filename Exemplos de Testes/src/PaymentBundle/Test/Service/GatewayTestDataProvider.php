<?php

namespace PaymentBundle\Test\Service;

use DateTime;
use PaymentBundle\Service\Gateway;

class GatewayTestDataProvider
{
    private static string $method = "POST";
    private static string $baseRoute = Gateway::BASE_URL;

    public static function testDataToShouldNotPayWhenAuthenticationFail(DateTime $validity): array
    {
        return [
            "map" => [
                [
                    self::$method,
                    self::$baseRoute . "/authenticate",
                    [
                        "user" => "test",
                        "password" => "invalid-test"
                    ],
                    null
                ]
            ],
            "authenticate" => [
                "user" => "test",
                "password" => "invalid-test"
            ],
            "numberOfSendMethodCalls" => 1,
            "expectedValue" => false,
            "validity" => $validity
        ];
    }

    public static function testDataToShouldNotPayWhenFailOnGateway(DateTime $validity, array $creditCardData): array
    {
        $user = "test";
        $password = "valid-test";
        return [
            "map" => [
                [
                    self::$method,
                    self::$baseRoute . "/authenticate",
                    [
                        "user" => $user,
                        "password" => $password
                    ],
                    "meu-token"
                ],
                [
                    self::$method,
                    self::$baseRoute . "/pay",
                    $creditCardData,
                    ["paid" => false]
                ]
            ],
            "authenticate" => [
                "user" => $user,
                "password" => $password
            ],
            "numberOfSendMethodCalls" => 2,
            "expectedValue" => false,
            "validity" => $validity,
        ];
    }

    public static function testDataToShouldSuccessfullyPayWhenGatewayReturnOk(DateTime $validity, array $creditCardData): array
    {
        $user = "test";
        $password = "valid-test";
        return [
            "map" => [
                [
                    self::$method,
                    self::$baseRoute . "/authenticate",
                    [
                        "user" => $user,
                        "password" => $password
                    ],
                    "meu-token"
                ],
                [
                    self::$method,
                    self::$baseRoute . "/pay",
                    $creditCardData,
                    ["paid" => true]
                ]
            ],
            "authenticate" => [
                "user" => $user,
                "password" => $password
            ],
            "numberOfSendMethodCalls" => 2,
            "expectedValue" => true,
            "validity" => $validity,
        ];
    }
}
