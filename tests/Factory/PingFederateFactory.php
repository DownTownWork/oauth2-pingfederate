<?php

declare(strict_types=1);

namespace DownTownWork\OAuth2\Client\Test\Factory;

use DownTownWork\OAuth2\Client\Provider\PingFederate;

class PingFederateFactory
{
    /**
     * @param string $baseUrlHost
     * @param int $baseUrlPort
     * @param string $clientId
     * @param string $clientSecret
     * @param string $redirectUri
     *
     * @return PingFederate
     */
    public static function createWith(
        $baseUrlHost,
        $baseUrlPort,
        $clientId = "",
        $clientSecret = "",
        $redirectUri = ""
    ): PingFederate {
        return new PingFederate([
            "serverUrl" => \sprintf("http://%s:%s", $baseUrlHost, $baseUrlPort),
            "clientId" => $clientId,
            "clientSecret" => $clientSecret,
            "redirectUri" => $redirectUri,
        ]);
    }
}
