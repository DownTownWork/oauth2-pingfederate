<?php

declare(strict_types=1);

namespace DownTownWork\OAuth2\Client\Provider;

use League\OAuth2\Client\Provider\ResourceOwnerInterface;
use League\OAuth2\Client\Tool\ArrayAccessorTrait;

class PingFederateResourceOwner implements ResourceOwnerInterface
{
    use ArrayAccessorTrait;

    /**
     * Raw response
     *
     * @var array<string, string>
     */
    protected $response;

    /**
     * Creates new resource owner.
     *
     * @param array<string, string> $response
     */
    public function __construct(array $response = [])
    {
        $this->response = $response;
    }

    /**
     * Get resource owner id
     *
     * @return string|null
     */
    public function getId()
    {
        return $this->getValueByKey($this->response, 'sub');
    }

    /**
     * Returns email address of the resource owner
     *
     * @return string|null
     */
    public function getEmail(): ?string
    {
        return $this->getValueByKey($this->response, 'email');
    }

    /**
     * Gets resource owner attribute by key. The key supports dot notation.
     *
     * @return string|null
     */
    public function getAttribute(string $key)
    {
        return $this->getValueByKey($this->response, $key);
    }

    /**
     * Return all of the owner details available as an array.
     *
     * @return array<string, string>
     */
    public function toArray()
    {
        return $this->response;
    }
}
