<?php

namespace App\MessageHandler;

use App\Service\ExportService;
use App\Message\ExportRequest;
use App\Repository\ExportRepository;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

// Consumes the export request and does the export work
class ExportRequestHandler implements MessageHandlerInterface
{
    private ExportRepository $exportRepository;

    private ExportService $exportService;

    public function __construct(ExportRepository $exportRepository, ExportService $exportService)
    {
        $this->exportRepository = $exportRepository;
        $this->exportService = $exportService;
    }

    public function __invoke(ExportRequest $exportRequest)
    {
        $export = $this->exportRepository->find($exportRequest->getExportId());
        $this->exportService->process($export);
    }
}
