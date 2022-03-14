<?php

namespace App\MessageHandler;

use App\Message\ImportRequest;
use App\Repository\ImportRepository;
use App\Service\ImportService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

// Consumes the import request and does the import work
class ImportRequestHandler implements MessageHandlerInterface
{
    private ImportRepository $importRepository;

    private ImportService $importService;

    public function __construct(ImportRepository $importRepository, ImportService $importService)
    {
        $this->importRepository = $importRepository;
        $this->importService = $importService;
    }

    public function __invoke(ImportRequest $importRequest)
    {
        $import = $this->importRepository->find($importRequest->getImportId());
        $this->importService->process($import);
    }
}
