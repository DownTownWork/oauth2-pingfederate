<?php

declare(strict_types=1);

namespace DownTownWork\OAuth2\Client\Test\Provider;

use DownTownWork\OAuth2\Client\Provider\PingFederate;
use DownTownWork\OAuth2\Client\Test\Factory\PingFederateFactory;
use PHPUnit\Framework\TestCase;

final class AuthorizationUrlTest extends TestCase
{
    private const BASE_URL_HOST = "localhost";
    private const BASE_URL_PORT = 9031;
    private const CLIENT_ID = "test";
    private const CLIENT_SECRET = "secret";
    private const REDIRECT_URI = "https://example.com/connect/check";

    /** @var PingFederate */
    private $provider;

    /** @var string */
    private $authorizationUrl;

    /**
     * @return void
     */
    public function testGettingAuthorizationUrl()
    {
        $this->givenProvider();
        $this->whenGettingAuthorizationUrl();
        $this->thenCheckAuthorizationUrlElements();
    }

    /**
     * @return void
     */
    private function givenProvider()
    {
        $this->provider = PingFederateFactory::createWith(
            self::BASE_URL_HOST,
            self::BASE_URL_PORT,
            self::CLIENT_ID,
            self::CLIENT_SECRET,
            self::REDIRECT_URI
        );
    }

    /**
     * @return void
     */
    private function whenGettingAuthorizationUrl()
    {
        $this->authorizationUrl = $this->provider->getAuthorizationUrl();
    }

    /**
     * @return void
     */
    private function thenCheckAuthorizationUrlElements()
    {
        $resource = \parse_url($this->authorizationUrl);

        $this->assertArrayHasKey("query", $resource);

        \parse_str($resource["query"], $query);

        $this->assertArrayHasKey("state", $query);
        $this->assertNotNull($query["state"]);
        $this->assertNotNull($this->provider->getState());

        $this->assertArrayHasKey("scope", $query);
        $this->assertNotNull($query["scope"]);

        $this->assertArrayHasKey("redirect_uri", $query);
        $this->assertEquals(self::REDIRECT_URI, $query["redirect_uri"]);

        $this->assertArrayHasKey("response_type", $query);
        $this->assertNotNull($query["response_type"]);

        $this->assertArrayHasKey("client_id", $query);
        $this->assertEquals($query["client_id"], self::CLIENT_ID);

        $this->assertArrayHasKey("approval_prompt", $query);
        $this->assertNotNull($query["approval_prompt"]);
    }
}
