<?php
namespace App\Entities;

class User
{
    /**
     * @var string
     */
    protected $apiKey;

    /**
     * @var string
     */
    protected $username;

    /**
     * User constructor.
     *
     * @param string $apiKey
     * @param string $username
     */
    public function __construct(string $apiKey, string $username)
    {
        $this->apiKey = $apiKey;
        $this->username = $username;
    }

    /**
     * @return string
     */
    public function getApiKey(): string
    {
        return $this->apiKey;
    }

    /**
     * @return string
     */
    public function getUsername(): string
    {
        return $this->username;
    }
}
