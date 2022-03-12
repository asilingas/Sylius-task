<?php

namespace App\Tests\Entity;

use App\Entity\Export\Export;
use App\Entity\User\AdminUser;
use PHPUnit\Framework\TestCase;

class ExportTest extends TestCase
{
    private Export $export;

    public function setUp(): void
    {
        $this->export = (new Export())
            ->setUser(new AdminUser())
            ->setType(Export::TYPE_PRODUCT)
            ->setFilename('filename.csv');
    }

    public function testExportIsConstructed(): void
    {
        $this->assertIsObject($this->export);
    }

    public function testExportHasUser(): void
    {
        $this->assertInstanceOf(AdminUser::class, $this->export->getUser());
    }

    public function testExportHasStatus(): void
    {
        $this->assertIsInt(0, $this->export->getStatus());
    }

    public function testExportIsCreated(): void
    {
        $this->assertInstanceOf('datetime', $this->export->getCreated());
    }

    public function testExportIsUpdated(): void
    {
        $this->assertInstanceOf('datetime', $this->export->getUpdated());
    }

    public function testExportHasFilename(): void
    {
        $this->assertEquals('filename.csv', $this->export->getFilename());
    }

    public function testExportHasType(): void
    {
        $this->assertEquals(Export::TYPE_PRODUCT, $this->export->getType());
    }

    public function testExportHasTotalItems(): void
    {
        $this->assertEquals(0, $this->export->getTotalItems());
    }

    public function testExportHasProcessedItems(): void
    {
        $this->assertEquals(0, $this->export->getProcessedItems());
    }
}
