<?php
namespace App\Http\Controllers;

use App\Entities\Gif;
use App\Exceptions\GifServiceException;
use App\Exceptions\HttpInvalidArgumentException;
use App\Interfaces\GifServiceInterface;
use Symfony\Component\HttpFoundation\Request;

class GifController
{
    /**
     * @var GifServiceInterface
     */
    protected $gifService;

    /**
     * @var Request
     */
    protected $request;

    /**
     * GifController constructor.
     *
     * @param GifServiceInterface $gifService
     * @param Request $request
     */
    public function __construct(GifServiceInterface $gifService, Request $request)
    {
        $this->gifService = $gifService;
        $this->request = $request;
    }

    /**
     * @return Gif[]
     * @throws GifServiceException
     * @throws HttpInvalidArgumentException
     */
    public function search()
    {
        $searchTerm = $this->getQueryParameter('q');
        if (empty($searchTerm)) {
            throw new HttpInvalidArgumentException('q', $searchTerm);
        }

        $page = $this->getQueryParameter('page', 1);
        $pageSize = $this->getQueryParameter('pageSize', 10);

        return $this->gifService->search($searchTerm, $page, $pageSize);
    }

    /**
     * @return Gif
     * @throws GifServiceException
     */
    public function fetchRandom()
    {
        return $this->gifService->random();
    }

    /**
     * @param string $parameterName
     * @param mixed $defaultValue
     *
     * @return string
     */
    protected function getQueryParameter($parameterName, $defaultValue = null)
    {
        $querystring = $this->request->getQueryString();

        $queryParameters = [];
        parse_str($querystring, $queryParameters);

        Return isset($queryParameters[$parameterName]) ? $queryParameters[$parameterName] : $defaultValue;
    }
}
