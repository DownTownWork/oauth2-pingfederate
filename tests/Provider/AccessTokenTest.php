<?php

declare(strict_types=1);

namespace DownTownWork\OAuth2\Client\Test\Provider;

use DownTownWork\OAuth2\Client\Provider\PingFederate;
use DownTownWork\OAuth2\Client\Test\Factory\PingFederateFactory;
use GuzzleHttp\ClientInterface;
use GuzzleHttp\Psr7\Response;
use League\OAuth2\Client\Token\AccessToken;
use PHPUnit\Framework\TestCase;

class AccessTokenTest extends TestCase
{
    private const BASE_URL_HOST = "localhost";
    private const BASE_URL_PORT = 9031;

    /** @var PingFederate */
    private $provider;

    /** @var AccessToken */
    private $token;

    /**
     * @return void
     */
    public function testGettingAccessToken()
    {
        $this->givenProvider();
        $this->whenGettingAccessToken();
        $this->thenCheckAccessToken();
    }

    /**
     * @return void
     */
    private function givenProvider()
    {
        $this->provider = PingFederateFactory::createWith(self::BASE_URL_HOST, self::BASE_URL_PORT);

        $response = new Response(
            200,
            ['Content-Type' => 'application/json'],
            '{
                "access_token": "mock_access_token",
                "token_type": "bearer"
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
        $this->token = $this->provider->getAccessToken('authorization_code', ['code' => 'mock_authorization_code']);
    }

    /**
     * @return void
     */
    private function thenCheckAccessToken()
    {
        $this->assertInstanceOf(AccessToken::class, $this->token);

        $this->assertEquals("mock_access_token", $this->token->getToken());

        $this->assertNull($this->token->getExpires());
        $this->assertNull($this->token->getRefreshToken());
        $this->assertNull($this->token->getResourceOwnerId());
    }
}
