<?php
namespace Tests\Unit\Auth;

use App\Auth\AuthorisationService;
use App\Entities\User;
use App\Exceptions\HttpUnauthorisedException;
use App\Interfaces\UserProviderInterface;
use Mockery;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Request;

class AuthorisationServiceTest extends TestCase
{
    public function testMissingApiKey()
    {
        $this->expectException(HttpUnauthorisedException::class);

        $userProviderMock = Mockery::mock(UserProviderInterface::class);
        $request = Request::create('http://localhost');

        $authorisationService = new AuthorisationService($request, $userProviderMock);
        $authorisationService->assertIsAuthorised();
    }

    public function testUnknownUser()
    {
        $this->expectException(HttpUnauthorisedException::class);

        $userProviderMock = Mockery::mock(UserProviderInterface::class);
        $userProviderMock
            ->shouldReceive('getUserByApiKey')
            ->andReturnNull();

        $request = Request::create('http://localhost');
        $request->headers->set('API_KEY', 'f8eeee38-5772-4eb1-8d86-d3d3247f5b52');

        $authorisationService = new AuthorisationService($request, $userProviderMock);
        $authorisationService->assertIsAuthorised();
    }

    public function testValidUser()
    {
        $user = new User('f8eeee38-5772-4eb1-8d86-d3d3247f5b52', 'Peter');

        $userProviderMock = Mockery::mock(UserProviderInterface::class);
        $userProviderMock
            ->shouldReceive('getUserByApiKey')
            ->andReturn($user);

        $request = Request::create('http://localhost');
        $request->headers->set('API_KEY', 'f8eeee38-5772-4eb1-8d86-d3d3247f5b52');

        $authorisationService = new AuthorisationService($request, $userProviderMock);
        $authorisationService->assertIsAuthorised();

        // if we get to this point it means the test has passed, there's nothing to assert against so manually fire
        // an assertion
        $this->assertTrue(true);
    }
}
