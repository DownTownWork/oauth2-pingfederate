<?php

declare(strict_types=1);

namespace DownTownWork\OAuth2\Client\Test\Provider;

use DownTownWork\OAuth2\Client\Provider\PingFederate;
use DownTownWork\OAuth2\Client\Test\Factory\PingFederateFactory;
use League\OAuth2\Client\Token\AccessToken;
use PHPUnit\Framework\TestCase;

final class ResourceOwnerDetailsUrlTest extends TestCase
{
    private const BASE_URL_HOST = "localhost";
    private const BASE_URL_PORT = 9031;

    /** @var PingFederate */
    private $provider;

    /** @var AccessToken */
    private $accessToken;

    /** @var string */
    private $resourceOwnerDetailsUrl;

    /**
      * @return void
      */
    public function testGettingResourceOwnerDetailsUrl()
    {
        $this->givenProviderAndAccessToken();
        $this->whenGettingResourceOwnerDetailsUrl();
        $this->thenCheckResourceOwnerDetailsUrlElements();
    }

    /**
     * @return void
     */
    private function givenProviderAndAccessToken()
    {
        $this->provider = PingFederateFactory::createWith(self::BASE_URL_HOST, self::BASE_URL_PORT);
        $this->accessToken = $this->createMock(AccessToken::class);
    }

    /**
     * @return void
     */
    private function whenGettingResourceOwnerDetailsUrl()
    {
        $this->resourceOwnerDetailsUrl = $this->provider->getResourceOwnerDetailsUrl($this->accessToken);
    }

    /**
     * @return void
     */
    private function thenCheckResourceOwnerDetailsUrlElements()
    {
        $resource = \parse_url($this->resourceOwnerDetailsUrl);

        $this->assertArrayHasKey("host", $resource);
        $this->assertEquals(self::BASE_URL_HOST, $resource["host"]);

        $this->assertArrayHasKey("port", $resource);
        $this->assertEquals(self::BASE_URL_PORT, $resource["port"]);

        $this->assertArrayHasKey("path", $resource);
        $this->assertEquals("/idp/userinfo.openid", $resource["path"]);
    }
}
