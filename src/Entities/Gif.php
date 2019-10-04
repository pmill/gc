<?php
namespace App\Entities;

use App\Interfaces\PresentableInterface;

class Gif implements PresentableInterface
{
    /**
     * @var string
     */
    protected $title;

    /**
     * @var string
     */
    protected $url;

    /**
     * Gif constructor.
     *
     * @param string $title
     * @param string $url
     */
    public function __construct(string $title, string $url)
    {
        $this->title = $title;
        $this->url = $url;
    }

    /**
     * @return string
     */
    public function getTitle(): string
    {
        return $this->title;
    }

    /**
     * @return string
     */
    public function getUrl(): string
    {
        return $this->url;
    }

    /**
     * @inheritDoc
     */
    public function present(): array
    {
        return [
            'title' => $this->title,
            'url' => $this->url,
        ];
    }
}
