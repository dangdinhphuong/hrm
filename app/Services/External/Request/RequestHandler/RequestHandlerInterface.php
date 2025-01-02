<?php

namespace App\Services\External\Request\RequestHandler;

interface RequestHandlerInterface
{
    public function create(array $params): Bool;
}
