<?php

namespace App\Service;

use App\Entity\Custom\Import;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Ramsey\Uuid\Uuid;
use Sylius\Component\Core\Model\ProductInterface;
use Sylius\Component\Mailer\Sender\SenderInterface;
use Symfony\Component\Security\Core\User\UserInterface;

class ImportService
{
    use HelperTrait;

    const BATCH_SIZE = 10;

    private ManagerRegistry $registry;

    private SenderInterface $sender;

    public function __construct(
        EntityManagerInterface $em,
        ManagerRegistry $registry,
        SenderInterface $sender
    ) {
        $this->em = $em;
        $this->registry = $registry;
        $this->sender = $sender;
    }

    public function createUniqueFilename(string $filename): string
    {
        return sprintf('%s_%s', Uuid::uuid4(), $filename);
    }

    // Import entity creation
    public function create(UserInterface $user, string $filename, string $type): Import
    {
        $import = (new Import())
            ->setUser($user)
            ->setFilename($filename)
            ->setType($type);

        $this->update($import);

        return $import;
    }

    // Process import job
    public function process(Import $import)
    {
        $filePath = 'public/import/' . $import->getFilename();

        $totalRows = count(file($filePath, FILE_SKIP_EMPTY_LINES));
        $this->updateImportStart($import, $totalRows);

        $delimeter = self::detectCSVFileSeparator($filePath);

        $handle = fopen($filePath, 'r');

        $i = 0;
        // Iterate over every row of the file
        while (($raw_string = fgets($handle)) !== false) {
            // Parse the raw csv string
            $row = str_getcsv($raw_string, $delimeter);
            // Inserting row to database
            try {
                $this->importItem($row);
            // Reset Entity manager on exception and continue to next row
            } catch (\Exception $exception) {
                $this->registry->resetManager();
                $import = $this->em->getReference(Import::class, $import->getId());
                continue;
            }
            ++$i;
            // Progress update
            if ($i % self::BATCH_SIZE === 0) {
                $this->updateImport($import, $i);
            }
        }
        fclose($handle);

        // If nothing was imported mark Import as failed
        if (0 === $i) {
            $this->updateImportFailure($import);

            return false;
        }

        $this->updateImportFinish($import, $i);

        // Sending email
        $this->sender->send('import_done', [$import->getUser()->getEmail()], ['import' => $import]);

        return true;
    }

    // Writes a row to database
    private function importItem(array $row): void
    {
        $nowString = (new \DateTime())->format('Y-m-d H:i:s');
        $params = [
            'code' => $row[0],
            'enabled' => $row[1],
            'created_at' => $nowString,
            'updated_at' => $nowString,
            'variant_selection_method' => ProductInterface::VARIANT_SELECTION_MATCH,
        ];

        $this->em->getConnection()->insert('sylius_product', $params);
    }

    // Updates Import entity's state
    private function updateImportStart(Import $import, int $totalItems): void
    {
        $import
            ->setTotalItemCount($totalItems)
            ->setStatus(Import::STATUS_IN_PROGRESS);
        $this->update($import);
    }

    // Updates Import entity's state
    private function updateImport(Import $import, int $processedItems): void
    {
        $import->setProcessedItemCount($processedItems);
        $this->update($import);
    }

    // Updates Import entity's state
    private function updateImportFinish(Import $import, int $processedItems): void
    {
        $import
            ->setStatus(Import::STATUS_DONE)
            ->setProcessedItemCount($processedItems);
        $this->update($import);
    }

    // Updates Import entity when nothing was imported
    private function updateImportFailure(Import $import): void
    {
        $import->setStatus(Import::STATUS_FAILED);
        $this->update($import);
    }

    private static function detectCSVFileSeparator(string $csvFile)
    {
        $delimiters = [',' => 0, ';' => 0, "\t" => 0, '|' => 0];
        $firstLine = '';
        $handle = fopen($csvFile, 'r');
        if ($handle) {
            $firstLine = fgets($handle);
            fclose($handle);
        }
        if ($firstLine) {
            foreach ($delimiters as $delimiter => &$count) {
                $count = count(str_getcsv($firstLine, $delimiter));
            }
            return array_search(max($delimiters), $delimiters);
        } else {
            return key($delimiters); }
    }

}
