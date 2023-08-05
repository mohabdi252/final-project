<?php

namespace App\Http\Controllers\Pos\Api\Settings;

use App\Http\Controllers\Controller;
use App\Http\Requests\Tenant\Setting\InvoiceSettingRequest;
use App\Models\Tenant\Order\Order;
use App\Models\Tenant\Order\ReturnOrder;
use App\Repositories\Core\Setting\SettingRepository;
use App\Services\Core\Setting\SettingService;

class InvoiceSettingController extends Controller
{
    public function __construct(SettingService $service)
    {
        $this->service = $service;
    }

    public function getInvoiceSetting()
    {
        return resolve(SettingRepository::class)->getFormattedSettings('app');
    }

    public function store(InvoiceSettingRequest $request)
    {
        if ($request->type === 'sales'){
            $checkIfAlreadyGenerated = Order::count();
            if ($checkIfAlreadyGenerated > 0) {
                return response()->json([
                    'status' => false,
                    'message' => __t('sales_invoice_already_configured')
                ]);
            }
        }else{
            $checkIfAlreadyGenerated = ReturnOrder::count();
            if ($checkIfAlreadyGenerated > 0) {
                return response()->json([
                    'status' => false,
                    'message' => __t('return_invoice_already_configured')
                ]);
            }
        }

        $this->service->update();

        return updated_responses('invoice_setting');

    }
}
