<?php
namespace App\Auth;

use App\Entities\User;
use App\Exceptions\HttpUnauthorisedException;
use App\Interfaces\UserProviderInterface;
use Symfony\Component\HttpFoundation\Request;

class AuthorisationService
{
    /**
     * @var Request
     */
    protected $request;

    /**
     * @var UserProviderInterface
     */
    protected $userProvider;

    /**
     * @var User
     */
    protected $loggedInUser;

    /**
     * AuthorisationService constructor.
     *
     * @param Request $request
     * @param UserProviderInterface $userProvider
     */
    public function __construct(Request $request, UserProviderInterface $userProvider)
    {
        $this->request = $request;
        $this->userProvider = $userProvider;
    }

    /**
     * @throws HttpUnauthorisedException
     */
    public function assertIsAuthorised()
    {
        $apiKey = $this->request->headers->get('API_KEY');

        if (empty($apiKey)) {
            throw new HttpUnauthorisedException();
        }

        $user = $this->userProvider->getUserByApiKey($apiKey);

        if ($user === null) {
            throw new HttpUnauthorisedException();
        }

        $this->loggedInUser = $user;
    }
}
