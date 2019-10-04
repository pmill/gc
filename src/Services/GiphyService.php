<?php
namespace App\Services;

use App\Entities\Gif;
use App\Exceptions\GifServiceException;
use App\Interfaces\GifServiceInterface;
use Exception;
use GPH\Api\DefaultApi;
use GPH\Model\Gif as GiphyGif;

class GiphyService implements GifServiceInterface
{
    /**
     * @var DefaultApi
     */
    protected $client;

    /**
     * @var string
     */
    protected $apiKey;

    /**
     * GiphyService constructor.
     *
     * @param string $apiKey
     */
    public function __construct(string $apiKey)
    {
        $this->apiKey = $apiKey;
        $this->client = new DefaultApi();
    }

    /**
     * @inheritDoc
     */
    public function search(string $searchTerm, int $page, int $pageSize): array
    {
        try {
            $result = $this->client->gifsSearchGet(
                $this->apiKey,
                $searchTerm,
                $page,
                $pageSize
            );

            $foundGifs = $result->getData();

            return $this->processGiphyGifs($foundGifs);
        } catch (Exception $e) {
            throw new GifServiceException($e->getMessage(), $e->getCode(), $e);
        }
    }

    /**
     * @inheritDoc
     */
    public function random(): Gif
    {
        try {
            $result = $this->client->gifsRandomGet($this->apiKey);

            $foundGif = $result->getData();

            return new Gif(
                $foundGif->getId(),
                $foundGif->getUrl()
            );
        } catch (Exception $e) {
            throw new GifServiceException($e->getMessage(), $e->getCode(), $e);
        }
    }

    /**
     * @param GiphyGif[] $giphyGifs
     *
     * @return Gif[]
     */
    protected function processGiphyGifs(array $giphyGifs)
    {
        return array_map(function (GiphyGif $giphyGif) {
            return new Gif(
                $giphyGif->getId(),
                $giphyGif->getUrl()
            );
        }, $giphyGifs);
    }
}
