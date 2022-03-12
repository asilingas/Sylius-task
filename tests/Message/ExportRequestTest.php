<?php

namespace App\Tests\Message;

use App\Message\ExportRequest;
use PHPUnit\Framework\TestCase;

class ExportRequestTest extends TestCase
{
    private ExportRequest $exportRequest;

    public function setUp(): void
    {
        $this->exportRequest = new ExportRequest(1);
    }

    public function testExportRequestIsContructed(): void
    {
        $this->assertIsObject($this->exportRequest);
    }

    public function testExportRequestHasExportId(): void
    {
        $this->assertEquals(1, $this->exportRequest->getExportId());
    }
}
