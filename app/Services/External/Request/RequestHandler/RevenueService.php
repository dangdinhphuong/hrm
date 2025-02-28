<?php

namespace App\Services\External\Request\RequestHandler;

use App\Services\RabbitMq\Crm3RabbitMqService;
use Validator;

class RevenueService extends AbstractRequestHandler
{
    const QUEUE_SUBJECT = 'request.revenue';
    private $crm3RabbitMqService;

    public function __construct(Crm3RabbitMqService $crm3RabbitMqService)
    {
        $this->crm3RabbitMqService = $crm3RabbitMqService;
    }

    protected function validate($params)
    {
        Validator::validate($params, [
            'key' => 'required',
            'phone' => 'required',
            'datetime' => 'required|date_format:d/m/Y H:i:s',
        ]);
    }

    function handleCreate($params): Bool
    {
        $this->crm3RabbitMqService->publishMessageCcm(self::QUEUE_SUBJECT, $params);
        return true;
    }
}
