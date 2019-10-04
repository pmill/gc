<?php
namespace App\Auth;

use App\Entities\User;
use App\Interfaces\UserProviderInterface;

class ArrayUserProvider implements UserProviderInterface
{
    /**
     * @var User[]
     */
    protected $users;

    /**
     * ArrayUserProvider constructor.
     *
     * @param array $userData
     */
    public function __construct(array $userData)
    {
        $this->users = [];

        foreach ($userData as $userDatum) {
            $this->users[] = new User(
                $userDatum['apiKey'],
                $userDatum['username']
            );
        }
    }

    /**
     * @param string $apiKey
     *
     * @return User
     */
    public function getUserByApiKey(string $apiKey)
    {
        foreach ($this->users as $user) {
            if ($user->getApiKey() === $apiKey) {
                return $user;
            }
        }

        return null;
    }
}
