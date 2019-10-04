<?php
namespace App\Routing;

class ResolvedRoute
{
    /**
     * @var RouteDefinition
     */
    protected $routeDefinition;

    /**
     * @var array
     */
    protected $routeParameters;

    /**
     * ResolvedRoute constructor.
     *
     * @param RouteDefinition $routeDefinition
     * @param array $routeParameters
     */
    public function __construct(RouteDefinition $routeDefinition, array $routeParameters)
    {
        $this->routeDefinition = $routeDefinition;
        $this->routeParameters = $routeParameters;
    }

    /**
     * @return RouteDefinition
     */
    public function getRouteDefinition(): RouteDefinition
    {
        return $this->routeDefinition;
    }

    /**
     * @return array
     */
    public function getRouteParameters(): array
    {
        return $this->routeParameters;
    }
}
