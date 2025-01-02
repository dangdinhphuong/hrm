<?php

namespace App\Services\External\Request\RequestHandler;

class RequestHandlerFactory
{
    const TYPE_REVENUE = 'revenue';

    public function getRequestHandlerByType($type)
    {
        if ($type == self::TYPE_REVENUE) {
            return app(RevenueService::class);
        }

        return null;
    }
}
