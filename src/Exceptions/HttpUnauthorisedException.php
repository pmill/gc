<?php
namespace App\Exceptions;

use Symfony\Component\HttpFoundation\Response;

class HttpUnauthorisedException extends HttpException
{
    /**
     * HttpFileNotFoundException constructor.
     */
    public function __construct()
    {
        parent::__construct(
            Response::HTTP_UNAUTHORIZED,
            'Forbidden'
        );
    }
}
