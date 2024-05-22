<?php

declare(strict_types=1);

namespace DownTownWork\OAuth2\Client\Test\Provider;

use DownTownWork\OAuth2\Client\Provider\PingFederate;
use DownTownWork\OAuth2\Client\Exception\PingFederateIdentityProviderException;
use DownTownWork\OAuth2\Client\Test\Factory\PingFederateFactory;
use GuzzleHttp\ClientInterface;
use GuzzleHttp\Psr7\Response;
use PHPUnit\Framework\TestCase;

class PingFederateIdentityProviderExceptionTest extends TestCase
{
    private const BASE_URL_HOST = "localhost";
    private const BASE_URL_PORT = 9031;

    /** @var PingFederate */
    private $provider;

    /** @var \Exception */
    private $exception;

    /**
     * @return void
     */
    public function testGettingIdentityProviderException()
    {
        $this->givenProvider();
        $this->whenGettingAccessToken();
        $this->thenCheckIdentityProviderException();
    }

    /**
     * @return void
     */
    private function givenProvider()
    {
        $this->provider = PingFederateFactory::createWith(self::BASE_URL_HOST, self::BASE_URL_PORT);

        $response = new Response(
            500,
            ['Content-Type' => 'application/json'],
            '{
                "error": "request_token_expired",
                "error_description": "' . \uniqid() . '"
            }'
        );

        $client = $this->createMock(ClientInterface::class);
        $client->expects($this->once())->method("send")->willReturn($response);

        $this->provider->setHttpClient($client);
    }

    /**
     * @return void
     */
    private function whenGettingAccessToken()
    {
        try {
            $token = $this->provider->getAccessToken('authorization_code', ['code' => 'mock_authorization_code']);
        } catch (\Exception $e) {
            $this->exception = $e;
        }
    }

    /**
     * @return void
     */
    private function thenCheckIdentityProviderException()
    {
        $this->assertInstanceOf(PingFederateIdentityProviderException::class, $this->exception);
        $this->assertEquals("request_token_expired", $this->exception->getMessage());
        $this->assertEquals(500, $this->exception->getCode());
    }
}
