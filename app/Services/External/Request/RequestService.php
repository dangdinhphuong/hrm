<?php

namespace App\Services\External\Request;

use App\Services\External\Request\RequestHandler\RequestHandlerFactory;
use App\Services\External\Request\RequestHandler\RequestHandlerInterface;
use Log;

class RequestService
{
    private $requestHandlerFactory;

    public function __construct(RequestHandlerFactory $requestHandlerFactory)
    {
        $this->requestHandlerFactory = $requestHandlerFactory;
    }

    public function create($type, $params)
    {
        /** @var RequestHandlerInterface $requestHandler */
        $requestHandler = $this->requestHandlerFactory->getRequestHandlerByType($type);
        if (!$requestHandler) {
            Log::warning("Invalid type $type");
            return false;
        }

        return $requestHandler->create($params);
    }
}
