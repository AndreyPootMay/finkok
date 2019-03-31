<?php

declare(strict_types=1);

namespace PhpCfdi\Finkok;

use PhpCfdi\Finkok\Definitions\Services;
use PhpCfdi\Finkok\Exceptions\InvalidArgumentException;

/**
 * @todo Renombrar a FinkokContainer
 */
class FinkokSettings
{
    /** @var string */
    private $username;

    /** @var string */
    private $password;

    /** @var FinkokEnvironment */
    private $environment;

    /** @var SoapFactory */
    private $soapFactory;

    public function __construct(string $username, string $password, FinkokEnvironment $environment = null)
    {
        if ('' === $username) {
            throw new InvalidArgumentException('Invalid username');
        }
        if ('' === $password) {
            throw new InvalidArgumentException('Invalid password');
        }
        $this->username = $username;
        $this->password = $password;
        $this->environment = $environment ?? FinkokEnvironment::makeDevelopment();
        $this->soapFactory = new SoapFactory();
    }

    public function username(): string
    {
        return $this->username;
    }

    public function password(): string
    {
        return $this->password;
    }

    public function environment(): FinkokEnvironment
    {
        return $this->environment;
    }

    public function soapFactory(): SoapFactory
    {
        return $this->soapFactory;
    }

    public function soapCaller(Services $service): SoapCaller
    {
        $soapFactory = $this->soapFactory();
        $endpoint = $this->environment()->endpoint($service);
        $soapClient = $soapFactory->createSoapClient($endpoint);
        $defaultOptions = [
            'username' => $this->username(),
            'password' => $this->password(),
        ];
        return $soapFactory->createSoapCaller($soapClient, $defaultOptions);
    }
}
