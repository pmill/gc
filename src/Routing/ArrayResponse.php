<?php
namespace App\Routing;

use App\Interfaces\PresentableInterface;

class ArrayResponse implements PresentableInterface
{
    /**
     * @var array
     */
    protected $responseData;

    /**
     * JsonResponse constructor.
     *
     * @param array $responseData
     */
    public function __construct(array $responseData)
    {
        $this->responseData = $responseData;
    }

    /**
     * @inheritDoc
     */
    public function present(): array
    {
        return $this->responseData;
    }
}
