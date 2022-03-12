<?php

namespace App\Service;

use App\Entity\Export\Export;
use Doctrine\ORM\EntityManagerInterface;
use Ramsey\Uuid\Uuid;
use Sylius\Bundle\CoreBundle\Doctrine\ORM\ProductRepository;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Security\Core\User\UserInterface;

class ExportService
{
    use HelperTrait;

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

    // Updating entity data when export job is starting
    public function startProcess(Export $export)
    {
        $totalItems = $this->productRepository->count([]);
        $export
            ->setTotalItems($totalItems)
            ->setStatus(Export::STATUS_IN_PROGRESS);
        $this->update($export);

        $file = fopen('export/'.$export->getFilename(), 'w');
        fputcsv($file, []);
        fclose($file);
    }

    // Unique filename
    private function generateFilename(string $type): string
    {
        return sprintf('%s_%s_%s.csv', $type, (new \DateTime())->format('ymd_his'), Uuid::uuid4());
    }
}
