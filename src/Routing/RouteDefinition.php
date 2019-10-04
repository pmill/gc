<?php
namespace App\Routing;

class RouteDefinition
{
    /**
     * @var string
     */
    protected $controllerClass;

    /**
     * @var string
     */
    protected $controllerMethod;

    /**
     * @var bool
     */
    protected $requiresAuthorisation;

    /**
     * Route constructor.
     *
     * @param string $controllerClass
     * @param string $controllerMethod
     * @param bool $requiresAuthorisation
     */
    public function __construct(
        string $controllerClass,
        string $controllerMethod,
        bool $requiresAuthorisation
    ) {
        $this->controllerClass = $controllerClass;
        $this->controllerMethod = $controllerMethod;
        $this->requiresAuthorisation = $requiresAuthorisation;
    }

    /**
     * @return string
     */
    public function getControllerClass(): string
    {
        return $this->controllerClass;
    }

    /**
     * @return string
     */
    public function getControllerMethod(): string
    {
        return $this->controllerMethod;
    }

    /**
     * @return bool
     */
    public function getRequiresAuthorisation(): bool
    {
        return $this->requiresAuthorisation;
    }
}
