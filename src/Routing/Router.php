<?php
namespace App\Routing;

use App\Exceptions\HttpFileNotFoundException;
use App\Exceptions\HttpMethodNotAllowedException;
use FastRoute\Dispatcher;
use FastRoute\RouteCollector;
use Symfony\Component\HttpFoundation\Request;
use function FastRoute\simpleDispatcher as createSimpleDispatcher;

/**
 * The router is responsible for accepting a Request object and returning the matching Route, if any
 *
 * Class Router
 * @package App\Routing
 */
class Router
{
    /**
     * @var Dispatcher
     */
    protected $dispatcher;

    /**
     * @param callable $routeDefinitionCallback
     */
    public function initialise(callable $routeDefinitionCallback)
    {
        $this->dispatcher = createSimpleDispatcher($routeDefinitionCallback);
    }

    /**
     * @param string[] $routeFilenames
     */
    public function initialiseFromRouteFiles(array $routeFilenames)
    {
        $this->initialise(
            function (RouteCollector $routeCollector) use ($routeFilenames) {
                foreach ($routeFilenames as $filename) {
                    require($filename);
                }
            }
        );
    }

    /**
     * Finds a matching route from the given request
     *
     * @param Request $request
     *
     * @return ResolvedRoute
     * @throws HttpFileNotFoundException
     * @throws HttpMethodNotAllowedException
     */
    public function findRoute(Request $request)
    {
        $routeInfo = $this->dispatcher->dispatch($request->getMethod(), $request->getPathInfo());
        $dispatchResult = $routeInfo[0];

        if ($dispatchResult === Dispatcher::NOT_FOUND) {
            throw new HttpFileNotFoundException($request);
        }

        if ($dispatchResult === Dispatcher::METHOD_NOT_ALLOWED) {
            throw new HttpMethodNotAllowedException($request);
        }

        /** @var RouteDefinition $routeDefinition */
        $routeDefinition = $routeInfo[1];
        $routeParams = $routeInfo[2];

        return new ResolvedRoute(
            $routeDefinition,
            $routeParams
        );
    }
}
