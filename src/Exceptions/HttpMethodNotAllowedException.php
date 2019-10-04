<?php
namespace App\Exceptions;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class HttpMethodNotAllowedException extends HttpException
{
    /**
     * HttpFileNotFoundException constructor.
     *
     * @param Request $request
     */
    public function __construct(Request $request)
    {
        parent::__construct(
            Response::HTTP_METHOD_NOT_ALLOWED,
            sprintf(
                'The %s method is not allowed for this url',
                $request->getMethod()
            )
        );
    }
}
