<?php


namespace App\Services\Settings;


use App\Services\Core\BaseService;
use App\Services\Core\Setting\DeliverySettingService;
use App\Services\Tenant\Setting\SettingService as TenantSettingService;

class SettingService extends BaseService
{
    public function getMailSettings()
    {
        $service = resolve(DeliverySettingService::class);

        $default = $service
            ->getDefaultSettings();

        return $service
            ->getFormattedDeliverySettings([
                optional($default)->value,
                'default_mail_email_name'
            ]);
    }

    public function getCachedMailSettings()
    {
        return cache()->remember('app-delivery-settings', 86400, function () {
            return $this->getMailSettings();
        });
    }

    public function getTenantFormattedSettings()
    {
        return resolve(TenantSettingService::class)
            ->getFormattedTenantSettings('app');
    }

}
