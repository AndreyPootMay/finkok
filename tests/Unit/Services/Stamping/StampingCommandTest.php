<?php

declare(strict_types=1);

namespace PhpCfdi\Finkok\Tests\Unit\Services\Stamping;

use PhpCfdi\Finkok\Services\Stamping\StampingCommand;
use PhpCfdi\Finkok\Tests\Factories\RandomPreCfdi;
use PhpCfdi\Finkok\Tests\TestCase;

final class StampingCommandTest extends TestCase
{
    public function testStampingCommandCanReceiveAPrecfdi(): void
    {
        $xml = (new RandomPreCfdi())->createValid();

        $stamping = new StampingCommand($xml);
        $this->assertSame($xml, $stamping->xml());
    }
}
