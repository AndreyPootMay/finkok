<?php

declare(strict_types=1);

namespace PhpCfdi\Finkok\Tests\Integration\Services\Registration;

use PhpCfdi\Finkok\Services\Registration\CustomerStatus;
use PhpCfdi\Finkok\Services\Registration\EditCommand;
use PhpCfdi\Finkok\Services\Registration\EditService;

class EditServiceTest extends RegistrationIntegrationTestCase
{
    protected function createService(): EditService
    {
        $editService = new EditService($this->createSettingsFromEnvironment());
        return $editService;
    }

    public function testConsumeEditServiceUsingExistentRfc(): void
    {
        $rfc = 'XDEL000101XX1';
        $service = $this->createService();

        if (! $this->findCustomerOrFail($rfc)->status()->isActive()) {
            $service->edit(new EditCommand($rfc, CustomerStatus::active()));
        }

        $editToSuspended = $service->edit(new EditCommand($rfc, CustomerStatus::suspended()));
        $this->assertTrue($editToSuspended->success());
        $this->assertSame('Account was Suspended successfully', $editToSuspended->message());
        $this->assertTrue(
            $this->findCustomerOrFail($rfc)->status()->isSuspended(),
            "Customer $rfc was not changed to Suspended"
        );

        $editToActive = $service->edit(new EditCommand($rfc, CustomerStatus::active()));
        $this->assertTrue($editToActive->success());
        $this->assertSame('Account was Activated successfully', $editToActive->message());
        $this->assertTrue(
            $this->findCustomerOrFail($rfc)->status()->isActive(),
            "Customer $rfc was not changed to Active"
        );
    }

    public function testConsumeEditServiceDoubleEditWithSameData(): void
    {
        $rfc = 'XDEL000101XX1';
        $service = $this->createService();

        if (! $this->findCustomerOrFail($rfc)->status()->isActive()) {
            $service->edit(new EditCommand($rfc, CustomerStatus::active()));
        }

        $editToActive = $service->edit(new EditCommand($rfc, CustomerStatus::active()));
        $this->assertTrue($editToActive->success());
        $this->assertSame('Account was Activated successfully', $editToActive->message());
        $this->assertTrue(
            $this->findCustomerOrFail($rfc)->status()->isActive(),
            "Customer $rfc was not changed to Active"
        );
    }

    public function testConsumeEditServiceUsingNotRegisteredRfc(): void
    {
        $rfc = 'ABCD010101AAA';
        $this->assertNull($this->findCustomer($rfc), "For this test RFC $rfc must not exists");

        $service = $this->createService();
        $result = $service->edit(new EditCommand($rfc, CustomerStatus::active()));

        $this->assertFalse($result->success());
        // Finkok report this error when an unregistered customer is sent to edit (Ticket #19861)
        $this->assertSame('ERROR: Cer o Key Invalido', $result->message());
    }
}
