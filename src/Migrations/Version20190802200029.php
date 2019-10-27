<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190802200029 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE `cost` (id INT AUTO_INCREMENT NOT NULL, integration_id INT NOT NULL, date DATETIME NOT NULL, cost DOUBLE PRECISION NOT NULL, views INT NOT NULL, clicks INT NOT NULL, source VARCHAR(255) NOT NULL, medium VARCHAR(255) NOT NULL, campaign VARCHAR(255) DEFAULT NULL, keyword VARCHAR(255) DEFAULT NULL, INDEX IDX_182694FC9E82DDEA (integration_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE `cost` ADD CONSTRAINT FK_182694FC9E82DDEA FOREIGN KEY (integration_id) REFERENCES integration_service (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE `cost`');
    }
}
