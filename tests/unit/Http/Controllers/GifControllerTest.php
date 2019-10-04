<?php
namespace Tests\Unit\Http\Controllers;

use App\Entities\Gif;
use App\Exceptions\HttpInvalidArgumentException;
use App\Http\Controllers\GifController;
use App\Interfaces\GifServiceInterface;
use Faker\Factory as FakerFactory;
use Mockery;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Request;

class GifControllerTest extends TestCase
{
    public function testSearchingForGifs()
    {
        $faker = FakerFactory::create();

        $bananaGifs = [];
        for ($i = 0; $i < 10; $i++) {
            $bananaGifs[] = new Gif(
                $faker->name,
                $faker->imageUrl()
            );
        }

        $gifServiceMock = Mockery::mock(GifServiceInterface::class);
        $gifServiceMock->shouldReceive('search')
            ->withArgs(['bananas', 1, 10])
            ->andReturn($bananaGifs);

        $request = Request::create('http://localhost/v1/gif/search?q=bananas', 'GET');

        $controller = new GifController($gifServiceMock, $request);
        $foundGifs = $controller->search();

        $this->assertEquals($bananaGifs, $foundGifs);
    }

    public function testSearchingWithNoQueryErrors()
    {
        $this->expectException(HttpInvalidArgumentException::class);

        $gifServiceMock = Mockery::mock(GifServiceInterface::class);

        $request = Request::create('http://localhost/v1/gif/search', 'GET');

        $controller = new GifController($gifServiceMock, $request);
        $controller->search();
    }

    public function testFetchingRandomGifs()
    {
        $faker = FakerFactory::create();

        $randomGif = new Gif(
            $faker->name,
            $faker->imageUrl()
        );

        $gifServiceMock = Mockery::mock(GifServiceInterface::class);
        $gifServiceMock->shouldReceive('random')
            ->andReturn($randomGif);

        $request = Request::create('http://localhost/v1/gif/random', 'GET');

        $controller = new GifController($gifServiceMock, $request);
        $foundGif = $controller->fetchRandom();

        $this->assertEquals($randomGif, $foundGif);
    }

    public function testSearchingWithInvalidPageParameter()
    {
        $this->expectException(HttpInvalidArgumentException::class);

        $gifServiceMock = Mockery::mock(GifServiceInterface::class);

        $request = Request::create('http://localhost/v1/gif/search?page=0', 'GET');

        $controller = new GifController($gifServiceMock, $request);
        $controller->search();
    }

    public function testSearchingWithInvalidPageSizeParameter()
    {
        $this->expectException(HttpInvalidArgumentException::class);

        $gifServiceMock = Mockery::mock(GifServiceInterface::class);

        $request = Request::create('http://localhost/v1/gif/search?pageSize=101', 'GET');

        $controller = new GifController($gifServiceMock, $request);
        $controller->search();
    }
}
