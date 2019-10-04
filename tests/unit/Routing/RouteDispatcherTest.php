<?php
namespace Tests\Unit\Routing;

use App\Exceptions\DispatchingRouteException;
use App\Http\Controllers\GifController;
use App\Interfaces\GifServiceInterface;
use App\Routing\ResolvedRoute;
use App\Routing\RouteDefinition;
use App\Routing\RouteDispatcher;
use DI\Container;
use Mockery;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Request;

class RouteDispatcherTest extends TestCase
{
    public function testUnknownController()
    {
        $this->expectException(DispatchingRouteException::class);

        $container = new Container();

        $resolvedRoute = new ResolvedRoute(
            new RouteDefinition(
                'UnknownController',
                'method',
                true
            ),
            []
        );

        $routeDispatcher = new RouteDispatcher($container);
        $routeDispatcher->dispatchRoute($resolvedRoute);
    }

    public function testUnknownControllerMethod()
    {
        $this->expectException(DispatchingRouteException::class);

        $mockGifService = Mockery::mock(GifServiceInterface::class);

        $container = new Container();
        $container->set(GifServiceInterface::class, $mockGifService);

        $resolvedRoute = new ResolvedRoute(
            new RouteDefinition(
                GifController::class,
                'unknownMethod',
                true
            ),
            []
        );

        $routeDispatcher = new RouteDispatcher($container);
        $routeDispatcher->dispatchRoute($resolvedRoute);
    }

    public function testControllerCalled()
    {
        $mockGifService = Mockery::mock(GifServiceInterface::class);
        $mockGifService
            ->shouldReceive('search')
            ->andReturn([]);

        $request = Request::create('http://localhost/v1/gif/search?q=bananas', 'GET');

        $container = new Container();
        $container->set(GifServiceInterface::class, $mockGifService);
        $container->set(Request::class, $request);

        $resolvedRoute = new ResolvedRoute(
            new RouteDefinition(
                GifController::class,
                'search',
                true
            ),
            []
        );

        $routeDispatcher = new RouteDispatcher($container);
        $result = $routeDispatcher->dispatchRoute($resolvedRoute);

        $this->assertNotNull($result);
    }
}
