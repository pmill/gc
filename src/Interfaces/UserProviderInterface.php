<?php
namespace App\Interfaces;

use App\Entities\User;

interface UserProviderInterface
{
    /**
     * @param string $apiKey
     *
     * @return User
     */
    public function getUserByApiKey(string $apiKey);
}
