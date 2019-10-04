<?php
namespace App;

use App\Auth\ArrayUserProvider;
use App\Auth\AuthorisationService;
use App\Exceptions\HttpException;
use App\Http\Presenters\JsonPresenter;
use App\Interfaces\GifServiceInterface;
use App\Interfaces\UserProviderInterface;
use App\Routing\ArrayResponse;
use App\Routing\RouteDispatcher;
use App\Routing\Router;
use App\Services\GiphyService;
use DI\Container;
use Dotenv\Dotenv;
use Exception;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class Application
{
    /**
     * @var Container
     */
    protected $serviceContainer;

    /**
     * Application constructor.
     */
    public function __construct()
    {
        $dotenv = Dotenv::create(__DIR__ . '/../');
        $dotenv->load();

        $this->setupDependencyInjection();
    }

    /**
     * Sets up dependencies for anything that can't be setup with autowiring
     */
    protected function setupDependencyInjection()
    {
        $this->serviceContainer = new Container();

        $this->serviceContainer->set(Request::class, Request::createFromGlobals());

        $this->serviceContainer->set(GifServiceInterface::class, function () {
            $giphyApiKey = getenv('GIPHY_API_KEY');

            return new GiphyService($giphyApiKey);
        });

        $this->serviceContainer->set(UserProviderInterface::class, function () {
            $usersData = require __DIR__ . '/../config/users.php';

            return new ArrayUserProvider($usersData);
        });

        $this->serviceContainer->set(Router::class, function () {
            $router = new Router();

            $router->initialiseFromRouteFiles([
                __DIR__ . '/../routes/api.php',
            ]);

            return $router;
        });

        $this->serviceContainer->set(RouteDispatcher::class, function ($serviceContainer) {
            return new RouteDispatcher($serviceContainer);
        });
    }

    /**
     * Runs the application:
     *
     * 1. Resolve the route from the request
     * 2. Check authorisation
     * 3. Dispatch the route and get the result from the controller
     * 4. Pass the result to the presenter to send a response to the browser
     */
    public function run()
    {
        $presenter = new JsonPresenter();

        try {
            /** @var Router $router */
            $router = $this->serviceContainer->get(Router::class);
            /** @var Request $request */
            $request = $this->serviceContainer->get(Request::class);

            $resolvedRoute = $router->findRoute($request);

            if ($resolvedRoute->getRouteDefinition()->getRequiresAuthorisation()) {
                $authorisationService = $this->serviceContainer->get(AuthorisationService::class);
                $authorisationService->assertIsAuthorised();
            }

            /** @var RouteDispatcher $routeDispatcher */
            $routeDispatcher = $this->serviceContainer->get(RouteDispatcher::class);
            $routeResult = $routeDispatcher->dispatchRoute($resolvedRoute);

            $presenter->present($routeResult);
        } catch (HttpException $e) {
            $presenter->present($e, $e->getHttpStatusCode());
        } catch (Exception $e) {
            // TODO log unhandled exception somewhere

            $presenter->present(
                new ArrayResponse(
                    [
                        'error' => "An error has occurred",
                    ]
                ),
                Response::HTTP_INTERNAL_SERVER_ERROR
            );
        }
    }
}
