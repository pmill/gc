<?php
namespace App\Interfaces;

use App\Entities\Gif;
use App\Exceptions\GifServiceException;

interface GifServiceInterface
{
    /**
     * @param string $searchTerm
     * @param int $page
     * @param int $pageSize
     *
     * @return Gif[]
     * @throws GifServiceException
     */
    public function search(string $searchTerm, int $page, int $pageSize): array;

    /**
     * @return Gif
     * @throws GifServiceException
     */
    public function random(): Gif;
}
