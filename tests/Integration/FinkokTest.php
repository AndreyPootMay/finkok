<?php

declare(strict_types=1);

namespace PhpCfdi\Finkok\Tests\Integration;

use PhpCfdi\Finkok\Finkok;

class FinkokTest extends IntegrationTestCase
{
    public function testCallingDateTime(): void
    {
        $finkok = new Finkok($this->createSettingsFromEnvironment());
        $result = $finkok->datetime();
        $this->assertSame('', $result->error(), 'Is Finkok down? Are you using valid testing credentials?');
        $this->assertStringMatchesFormat('%d-%d-%dT%d:%d:%d', $result->datetime());
    }
}
