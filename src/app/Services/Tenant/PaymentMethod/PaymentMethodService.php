<?php

namespace App\Services\Tenant\PaymentMethod;

use App\Models\Core\Status;
use App\Models\Tenant\PaymentMethod\PaymentMethod;
use App\Services\Tenant\TenantService;

class PaymentMethodService extends TenantService
{
    public function __construct(PaymentMethod $paymentMethod)
    {
        $this->model = $paymentMethod;
    }

    public function getPaymentMethodStatus()
    {
        return Status::where('type', 'payment_method')->get();
    }

    public function saveSettings($type): void
    {
        $settings = array_map(function ($name) use ($type) {
            if ($this->getAttr($name)) {
                return [
                    'settingable_id' => $this->model->id,
                    'settingable_type' => PaymentMethod::class,
                    'name' => $name,
                    'value' => $name != 'mode' ? encrypt($this->getAttr($name)) : $this->getAttr($name),
                    'context' => $type,
                    'created_at' => now(),
                    'updated_at' => now()
                ];
            }
        }, array_keys($this->getAttrs('public_key', 'client_id', 'secret_key', 'mode', 'type')));
        $settings = array_filter($settings);
        $this->model
            ->settings()
            ->insert($settings);
    }

    public function updateSettings()
    {
        $settings = $this->getAttrs('public_key', 'client_id', 'secret_key', 'mode');

        foreach ($settings as $name => $value) {
            if (!$value) {
                $this->model->settings()
                    ->where('name', $name)
                    ->delete();
            }
            if ($value) {
                $this->model->settings()
                    ->updateOrCreate(
                        ['name' => $name],
                        ['value' => $name != 'mode' ? encrypt($this->getAttr($name)) : $this->getAttr($name)]
                    );
            }
        }
    }

    public function getDataWithFormattedSetting($paymentMethod)
    {
        $paymentMethod->settings->map(function ($setting){
            if ($setting->name != 'mode'){
                $setting->value = decrypt($setting->value);
            }
            return $setting;
        });
        return $paymentMethod;
    }

}