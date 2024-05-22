<?php

declare(strict_types=1);

namespace DownTownWork\OAuth2\Client\Test\Provider;

use DownTownWork\OAuth2\Client\Provider\PingFederate;
use DownTownWork\OAuth2\Client\Provider\PingFederateResourceOwner;
use DownTownWork\OAuth2\Client\Test\Factory\PingFederateFactory;
use GuzzleHttp\ClientInterface;
use GuzzleHttp\Psr7\Response;
use PHPUnit\Framework\TestCase;

final class UserInfoTest extends TestCase
{
    private const BASE_URL_HOST = "localhost";
    private const BASE_URL_PORT = 9031;

    /** @var PingFederate */
    private $provider;

    /** @var PingFederateResourceOwner */
    private $user;

    /**
     * @return void
     */
    public function testGettingUserInfo()
    {
        $this->givenProvider();
        $this->whenGettingUserInfo();
        $this->thenCheckUserInfoElements();
    }

    /**
     * @return void
     */
    private function givenProvider()
    {
        $this->provider = PingFederateFactory::createWith(self::BASE_URL_HOST, self::BASE_URL_PORT);

        $accessTokenResponse = new Response(
            200,
            ['Content-Type' => 'application/json'],
            '{
                "access_token": "mock_access_token",
                "token_type": "bearer"
            }'
        );

        $userInfoResponse = new Response(
            200,
            ['Content-Type' => 'application/json'],
            '{
                "email": "auser@example.com",
                "phone_number": "(555) 555-5555",
                "phone_number_verified": true,
                "sub": "joe"
            }'
        );

        $client = $this->createMock(ClientInterface::class);
        $client->expects($this->exactly(2))->method("send")->willReturn($accessTokenResponse, $userInfoResponse);

        $this->provider->setHttpClient($client);
    }

    /**
     * @return void
     */
    private function whenGettingUserInfo()
    {
        $token = $this->provider->getAccessToken('authorization_code', ['code' => 'mock_authorization_code']);

        $this->user = $this->provider->getResourceOwner($token);
    }

    /**
     * @return void
     */
    private function thenCheckUserInfoElements()
    {
        $this->assertInstanceOf(PingFederateResourceOwner::class, $this->user);

        $this->assertEquals("joe", $this->user->getId());
        $this->assertEquals("joe", $this->user->toArray()["sub"]);

        $this->assertEquals("auser@example.com", $this->user->getEmail());
        $this->assertEquals("auser@example.com", $this->user->toArray()["email"]);

        $this->assertEquals("(555) 555-5555", $this->user->getAttribute("phone_number"));
        $this->assertEquals("(555) 555-5555", $this->user->toArray()["phone_number"]);

        $this->assertTrue($this->user->getAttribute("phone_number_verified"));
        $this->assertTrue($this->user->toArray()["phone_number_verified"]);
    }
}
