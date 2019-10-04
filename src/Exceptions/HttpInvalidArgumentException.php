<?php
namespace App\Exceptions;

use Symfony\Component\HttpFoundation\Response;

class HttpInvalidArgumentException extends HttpException
{
    /**
     * HttpInvalidArgumentException constructor.
     *
     * @param string $argumentName
     * @param string $argumentValue
     */
    public function __construct(string $argumentName, string $argumentValue = null)
    {
        parent::__construct(
            Response::HTTP_BAD_REQUEST,
            sprintf(
                "Invalid value '%s' supplied for argument %s",
                $argumentValue,
                $argumentName
            )
        );
    }
}
