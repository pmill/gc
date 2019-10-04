<?php
namespace App\Interfaces;

use Symfony\Component\HttpFoundation\Response;

interface PresenterInterface
{
    /**
     * @param PresentableInterface|PresentableInterface[] $presentee
     * @param int $statusCode
     */
    public function present($presentee, int $statusCode = Response::HTTP_OK);
}
