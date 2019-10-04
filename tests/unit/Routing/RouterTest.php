<?php
namespace Tests\Unit\Routing;

use App\Exceptions\HttpFileNotFoundException;
use App\Exceptions\HttpMethodNotAllowedException;
use App\Http\Controllers\GifController;
use App\Routing\Router;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Request;

class RouterTest extends TestCase
{
    /**
     * @var Router
     */
    protected $router;

    /**
     * @inheritDoc
     */
    protected function setUp()
    {
        parent::setUp();

        $this->router = new Router();
        $this->router->initialiseFromRouteFiles([
            __DIR__ . '/../../../routes/api.php',
        ]);
    }

    public function testRouteNotFound()
    {
        $this->expectException(HttpFileNotFoundException::class);

        $request = Request::create('http://localhost/404');

        $this->router->findRoute($request);
    }

    public function testMethodNotAllowed()
    {
        $this->expectException(HttpMethodNotAllowedException::class);

        $request = Request::create('http://localhost/v1/gifs/search', 'POST');

        $this->router->findRoute($request);
    }

    public function testRouteResolution()
    {
        $request = Request::create('http://localhost/v1/gifs/search', 'GET');

        $resolvedRoute = $this->router->findRoute($request);
        $resolvedRouteDefinition = $resolvedRoute->getRouteDefinition();

        $this->assertEquals(GifController::class, $resolvedRouteDefinition->getControllerClass());
        $this->assertEquals('search', $resolvedRouteDefinition->getControllerMethod());
    }
}
