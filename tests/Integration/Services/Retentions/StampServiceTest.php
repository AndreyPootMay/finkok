<?php

declare(strict_types=1);

namespace PhpCfdi\Finkok\Tests\Integration\Services\Retentions;

final class StampServiceTest extends RetentionsTestCase
{
    public function testStampValidPreCfdiRetentions(): void
    {
        $result = $this->currentRetentionsStampResult();
        $this->assertSame('Comprobante timbrado satisfactoriamente', $result->statusCode());
        $this->assertNotEmpty($result->xml());
        $this->assertNotEmpty($result->uuid());
        $this->assertNotEmpty($result->seal());
    }

    public function testStampInvalidXml(): void
    {
        $result = $this->stampRetentionPreCfdi('invalid xml');
        $this->assertEmpty($result->xml());
        $this->assertEmpty($result->uuid());
        $this->assertTrue($result->hasAlerts());
    }
}
