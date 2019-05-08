<?php

declare(strict_types=1);

namespace PhpCfdi\Finkok\Services\Registration;

use PhpCfdi\Finkok\Definitions\Services;
use PhpCfdi\Finkok\FinkokSettings;

class ObtainService
{
    /** @var FinkokSettings */
    private $settings;

    public function __construct(FinkokSettings $settings)
    {
        $this->settings = $settings;
    }

    public function settings(): FinkokSettings
    {
        return $this->settings;
    }

    public function obtain(ObtainCommand $command): ObtainResult
    {
        $this->settings()->changeUsernameKey('reseller_username');
        $this->settings()->changeUsernameKey('reseller_password');
        $soapCaller = $this->settings()->createCallerForService(Services::registration());
        $rawResponse = $soapCaller->call('get', array_filter([
            'taxpayer_id' => $command->rfc(),
        ]));
        return new ObtainResult($rawResponse);
    }
}
