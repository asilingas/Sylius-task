<?php

namespace App\Service;

use App\Entity\Export\Export;
use App\Repository\ProductRepository;
use Doctrine\ORM\EntityManagerInterface;
use Ramsey\Uuid\Uuid;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Security\Core\User\UserInterface;

class ExportService
{
    use HelperTrait;

    const BATCH_SIZE = 10;

    protected ProductRepository $productRepository;

    protected Filesystem $filesystem;

    public function __construct(
        EntityManagerInterface $em,
        ProductRepository $productRepository,
        Filesystem $filesystem
    ) {
        $this->em = $em;
        $this->productRepository = $productRepository;
        $this->filesystem = $filesystem;
    }

    // Export entity creation
    public function create(UserInterface $user, string $type): Export
    {
        $export = (new Export())
            ->setUser($user)
            ->setFilename($this->generateFilename($type))
            ->setType($type);

        $this->update($export);

        return $export;
    }

    // Process export job
    // Could be improved by using Gauffrete with different filesystem adapters
    public function process(Export $export): void
    {
        $totalItems = $this->productRepository->count([]);
        $this->updateExportStart($export, $totalItems);

        try {
            $file = fopen('public/export/'.$export->getFilename(), 'w');
            $i = 0;
            while ($i < $totalItems) {
                if ($i % self::BATCH_SIZE === 0) {
                    $i += $this->exportItems($i, $file);
                    $this->updateExport($export, $i);
                }
            }
            fclose($file);
            $this->updateExportFinish($export);
        } catch (\Exception $exception) {
            $this->updateExportFailure($export);
        }
    }

    // Unique filename
    private function generateFilename(string $type): string
    {
        return sprintf('%s_%s_%s.csv', $type, (new \DateTime())->format('Ymd_his'), Uuid::uuid4());
    }

    // Writes a batch of rows to file
    private function exportItems(int $i, $file)
    {
        $queryResult = $this->productRepository->findForExport(self::BATCH_SIZE, $i);
        foreach ($queryResult as $item) {
            fputcsv($file, $item);
        }

        return count($queryResult);
    }

    // Updates Export entity's state
    private function updateExportStart(Export $export, int $totalItems): void
    {
        $export
            ->setTotalItems($totalItems)
            ->setStatus(Export::STATUS_IN_PROGRESS);
        $this->update($export);
    }

    // Updates Export entity's state
    private function updateExport(Export $export, int $processedItems): void
    {
        $export->setProcessedItems($processedItems);
        $this->update($export);
    }

    // Updates Export entity's state
    private function updateExportFinish(Export $export): void
    {
        $export->setStatus(Export::STATUS_DONE);
        $this->update($export);
    }

    // Updates Export entity when something fails during process
    private function updateExportFailure(Export $export): void
    {
        $export->setStatus(Export::STATUS_FAILED);
        $this->update($export);
    }
}
