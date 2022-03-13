<?php

declare(strict_types=1);

namespace App\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220313122528 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE export (id INT AUTO_INCREMENT NOT NULL, user INT DEFAULT NULL, created DATETIME NOT NULL, updated DATETIME NOT NULL, guid CHAR(36) NOT NULL COMMENT \'(DC2Type:guid)\', type VARCHAR(255) NOT NULL, fileName VARCHAR(255) NOT NULL, total_items INT NOT NULL, processed_items INT NOT NULL, status SMALLINT NOT NULL, UNIQUE INDEX UNIQ_428C16942B6FCFB2 (guid), INDEX IDX_428C16948D93D649 (user), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE export ADD CONSTRAINT FK_428C16948D93D649 FOREIGN KEY (user) REFERENCES sylius_admin_user (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE export');
    }
}
