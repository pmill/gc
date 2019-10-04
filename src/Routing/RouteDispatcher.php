<?php
namespace App\Routing;

use App\Exceptions\DispatchingRouteException;
use DI\Container;
use DI\DependencyException;
use DI\NotFoundException;
use Exception;
use ReflectionClass;
use ReflectionException;

/**
 * This class is responsible for accepting a Route object, executing the method on the given controller, and returning
 * the result
 *
 * Class RouteDispatcher
 * @package App\Routing
 */
class RouteDispatcher
{
    /**
     * @var Container
     */
    protected $serviceContainer;

    /**
     * RouteDispatcher constructor.
     *
     * @param Container $serviceContainer
     */
    public function __construct(Container $serviceContainer)
    {
        $this->serviceContainer = $serviceContainer;
    }

    /**
     * @param ResolvedRoute $resolvedRoute
     *
     * @return mixed
     * @throws DispatchingRouteException
     */
    public function dispatchRoute(ResolvedRoute $resolvedRoute)
    {
        try {
            $controllerClass = $resolvedRoute->getRouteDefinition()->getControllerClass();
            $controller = $this->serviceContainer->make($controllerClass);

            $reflectionController = new ReflectionClass($controllerClass);
            $reflectionMethod = $reflectionController->getMethod($resolvedRoute->getRouteDefinition()->getControllerMethod());
            return $reflectionMethod->invokeArgs($controller, $resolvedRoute->getRouteParameters());
        } catch (DependencyException $e) {
            $this->throwDispatchException($e);
        } catch (NotFoundException $e) {
            $this->throwDispatchException($e);
        } catch (ReflectionException $e) {
            $this->throwDispatchException($e);
        }
    }

    /**
     * @param Exception $e
     *
     * @throws DispatchingRouteException
     */
    protected function throwDispatchException(Exception $e)
    {
        throw new DispatchingRouteException('Failed to dispatch to route', 0, $e);
    }
}
