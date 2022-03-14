<?php

namespace App\Message;

// For import job dispatching
class ImportRequest implements AsyncMessageInterface
{
    private int $importId;

    public function __construct(int $importId)
    {
        $this->importId = $importId;
    }

    public function getImportId(): int
    {
        return $this->importId;
    }
}
