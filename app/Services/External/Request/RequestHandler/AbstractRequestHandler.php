<?php

namespace App\Services\External\Request\RequestHandler;

abstract class AbstractRequestHandler implements RequestHandlerInterface
{
    public function create($params): Bool
    {
        $this->validate($params);
        return $this->handleCreate($params);
    }

    protected function validate($params)
    {
        return null;
    }

    abstract function handleCreate($params): Bool;
}
