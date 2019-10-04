<?php
namespace App\Exceptions;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class HttpFileNotFoundException extends HttpException
{
    /**
     * HttpFileNotFoundException constructor.
     *
     * @param Request $request
     */
    public function __construct(Request $request)
    {
        parent::__construct(
            Response::HTTP_NOT_FOUND,
            sprintf(
                'Could not find file at %s',
                $request->getRequestUri()
            )
        );
    }
}
