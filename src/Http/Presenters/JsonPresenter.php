<?php
namespace App\Http\Presenters;

use App\Interfaces\PresenterInterface;
use App\Interfaces\PresentableInterface;
use Symfony\Component\HttpFoundation\Response;

class JsonPresenter implements PresenterInterface
{
    /**
     * @inheritDoc
     */
    public function present($presentee, int $statusCode = Response::HTTP_OK)
    {
        if (is_array($presentee)) {
            $this->presentCollection($presentee, $statusCode);
        } elseif ($presentee instanceof PresentableInterface) {
            $this->presentItem($presentee, $statusCode);
        } else {
            $this->sendResponse($presentee, $statusCode);
        }
    }

    /**
     * @param PresentableInterface $item
     * @param int $statusCode
     */
    protected function presentItem(PresentableInterface $item, int $statusCode = Response::HTTP_OK)
    {
        $body = json_encode([
            'data' => $item->present(),
        ]);

        $this->sendResponse($body, $statusCode);
    }

    /**
     * @param PresentableInterface[] $items
     * @param int $statusCode
     */
    protected function presentCollection(array $items, int $statusCode = Response::HTTP_OK)
    {
        $transformedItems = array_map(function (PresentableInterface $item) {
            return $item->present();
        }, $items);

        $body = json_encode([
            'data' => $transformedItems,
        ]);

        $this->sendResponse($body, $statusCode);
    }

    /**
     * @param string $responseBody
     * @param int $statusCode
     */
    protected function sendResponse(string $responseBody, int $statusCode = Response::HTTP_OK)
    {
        $response = new Response($responseBody, $statusCode);
        $response->headers->set('Content-Type', 'application/json');
        $response->send();
    }
}
