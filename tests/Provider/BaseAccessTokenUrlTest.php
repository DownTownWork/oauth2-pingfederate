<?php

declare(strict_types=1);

namespace DownTownWork\OAuth2\Client\Test\Provider;

use DownTownWork\OAuth2\Client\Provider\PingFederate;
use DownTownWork\OAuth2\Client\Test\Factory\PingFederateFactory;
use PHPUnit\Framework\TestCase;

final class BaseAccessTokenUrlTest extends TestCase
{
    private const BASE_URL_HOST = "localhost";
    private const BASE_URL_PORT = 9031;

    /** @var PingFederate */
    private $provider;

    /** @var string */
    private $baseAccessTokenUrl;

    /**
     * @return void
     */
    public function testGettingBaseAccessTokenUrl(): void
    {
        $this->givenProvider();
        $this->whenGettingBaseAccessTokenUrl();
        $this->thenCheckBaseAccessTokenUrlElements();
    }

    /**
     * @return void
     */
    private function givenProvider()
    {
        $this->provider = PingFederateFactory::createWith(self::BASE_URL_HOST, self::BASE_URL_PORT);
    }

    /**
     * @return void
     */
    private function whenGettingBaseAccessTokenUrl()
    {
        $this->baseAccessTokenUrl = $this->provider->getBaseAccessTokenUrl([]);
    }

    /**
     * @return void
     */
    private function thenCheckBaseAccessTokenUrlElements()
    {
        $resource = \parse_url($this->baseAccessTokenUrl);

        $this->assertArrayHasKey("host", $resource);
        $this->assertEquals(self::BASE_URL_HOST, $resource["host"]);

        $this->assertArrayHasKey("port", $resource);
        $this->assertEquals(self::BASE_URL_PORT, $resource["port"]);

        $this->assertArrayHasKey("path", $resource);
        $this->assertEquals("/as/token.oauth2", $resource["path"]);
    }
}
