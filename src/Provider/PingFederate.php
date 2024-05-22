<?php

declare(strict_types=1);

namespace DownTownWork\OAuth2\Client\Provider;

use DownTownWork\OAuth2\Client\Exception\PingFederateIdentityProviderException;
use League\OAuth2\Client\Provider\AbstractProvider;
use League\OAuth2\Client\Token\AccessToken;
use League\OAuth2\Client\Tool\BearerAuthorizationTrait;
use Psr\Http\Message\ResponseInterface;

/**
  * @inheritDoc
  */
class PingFederate extends AbstractProvider
{
    use BearerAuthorizationTrait;

    private const AUTHORIZATION_URI = "/as/authorization.oauth2";
    private const TOKEN_URI = "/as/token.oauth2";
    private const USERINFO_URI = "/idp/userinfo.openid";

    /**
     * PingFederate URL, eg. http://localhost:9031.
     *
     * @var string
     */
    protected $serverUrl;

    /**
     * @return string
     */
    public function getServerUrl()
    {
        return $this->serverUrl;
    }

    /**
     * @return string
     */
    public function getBaseAuthorizationUrl()
    {
        return $this->getServerUrl() . self::AUTHORIZATION_URI;
    }

    /**
     * @param array<string> $params
     *
     * @return string
     */
    public function getBaseAccessTokenUrl(array $params)
    {
        return $this->getServerUrl() . self::TOKEN_URI;
    }

    /**
     * @return string
     */
    public function getResourceOwnerDetailsUrl(AccessToken $token)
    {
        return $this->getServerUrl() . self::USERINFO_URI;
    }

    /**
     * Get the default scopes used by this provider.
     *
     * This should not be a complete list of all scopes, but the minimum
     * required for the provider user interface!
     *
     * @return array<string>
     */
    protected function getDefaultScopes()
    {
        return ['openid', 'email', 'profile'];
    }

    /**
     * Returns the string that should be used to separate scopes when building
     * the URL for requesting an access token.
     *
     * @return string Scope separator, defaults to ','
     */
    protected function getScopeSeparator()
    {
        return ' ';
    }

    /**
     * Check a provider response for errors.
     *
     * @param array<string> $data
     *
     * @return void
     */
    protected function checkResponse(ResponseInterface $response, $data)
    {
        if ($response->getStatusCode() >= 400) {
            throw new PingFederateIdentityProviderException(
                $data['error'] ?: $response->getReasonPhrase(),
                $response->getStatusCode(),
                $response
            );
        }
    }

    /**
     * Generate a user object from a successful user details request.
     *
     * @param array<string, string> $response
     *
     * @return PingFederateResourceOwner
     */
    protected function createResourceOwner(array $response, AccessToken $token)
    {
        return new PingFederateResourceOwner($response);
    }
}
