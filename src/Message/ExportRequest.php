<?php

namespace App\Message;

// For export job dispatching
class ExportRequest implements AsyncMessageInterface
{
    private string $exportId;

    public function __construct(int $exportId)
    {
        $this->exportId = $exportId;
    }

    public function getExportId(): int
    {
        return $this->exportId;
    }
}
