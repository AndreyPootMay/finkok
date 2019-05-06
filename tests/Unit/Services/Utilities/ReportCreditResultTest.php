<?php

declare(strict_types=1);

namespace PhpCfdi\Finkok\Tests\Unit\Services\Utilities;

use PhpCfdi\Finkok\Services\Utilities\ReportCreditResult;
use PhpCfdi\Finkok\Tests\TestCase;

class ReportCreditResultTest extends TestCase
{
    public function testResultUsingPredefinedResponse(): void
    {
        $data = json_decode($this->fileContentPath('utilities-report-credit-response.json'));
        $result = new ReportCreditResult($data);

        $this->assertCount(2, $result->items());
        $obtained = [];
        foreach ($result->items() as $item) {
            $obtained[] = [
                'credit' => $item['credit'],
                'date' => $item['date'],
            ];
        }

        $expected = [
            ['credit' => '100', 'date' => '2019-01-13T14:15:16.171819'],
            ['credit' => '200', 'date' => '2019-01-15T16:17:18.192021'],
        ];

        $this->assertSame($expected, $obtained);
    }
}
