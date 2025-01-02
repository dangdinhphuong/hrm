<?php

namespace App\Http\Controllers\External;

use App\Http\Controllers\Controller;
use App\Services\MauticService;
use Illuminate\Http\Request;

class ContactController extends Controller
{
    private $mauticService;

    public function __construct(MauticService $mauticService)
    {
        $this->mauticService = $mauticService;
    }

    public function create(Request $request)
    {
        $this->validate($request, ['phone' => 'required']);

        $result = $this->mauticService->createContact($request->only([
            'phone',
            'firstname',
            'campaign_name',
            'partner_id',
            'agent_code',
            'product_package',
            'title',
            'notes'
        ]));

        if ($result) {
            return responder()->respond(message: 'Tạo lead thành công');
        }
        return responder()->fail('Lỗi vui lòng liên hệ admin');
    }
}
