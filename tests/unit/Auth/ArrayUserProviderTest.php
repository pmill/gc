<?php
namespace Tests\Unit\Auth;

use App\Auth\ArrayUserProvider;
use PHPUnit\Framework\TestCase;

class ArrayUserProviderTest extends TestCase
{
    public function testGetUserByApiKey()
    {
        $users = [
            [
                'apiKey' => 'f8eeee38-5772-4eb1-8d86-d3d3247f5b52',
                'username' => 'peter',
            ],
        ];

        $arrayUserProvider = new ArrayUserProvider($users);
        $user = $arrayUserProvider->getUserByApiKey('f8eeee38-5772-4eb1-8d86-d3d3247f5b52');

        $this->assertEquals('peter', $user->getUsername());
    }

    public function testUnknownUser()
    {
        $users = [
            [
                'apiKey' => 'f8eeee38-5772-4eb1-8d86-d3d3247f5b52',
                'username' => 'peter',
            ],
        ];

        $arrayUserProvider = new ArrayUserProvider($users);
        $user = $arrayUserProvider->getUserByApiKey('b2e60e07-71a7-42ed-bab3-c0f0de3fd5d8');

        $this->assertNull($user);
    }
}
