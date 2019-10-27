<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190727201213 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE order_source DROP system, CHANGE user_id integration_id INT NOT NULL');
        $this->addSql('ALTER TABLE order_source ADD CONSTRAINT FK_9C056FA69E82DDEA FOREIGN KEY (integration_id) REFERENCES integration_service (id)');
        $this->addSql('CREATE INDEX IDX_9C056FA69E82DDEA ON order_source (integration_id)');

        $this->addSql(
            'INSERT INTO integration_service_type (title, code) VALUES (:title, :code)',
            ['title' => 'Google Analytics', 'code' => 'GOOGLE_ANALYTICSs']
        );
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE `order_source` DROP FOREIGN KEY FK_9C056FA69E82DDEA');
        $this->addSql('DROP INDEX IDX_9C056FA69E82DDEA ON `order_source`');
        $this->addSql('ALTER TABLE `order_source` ADD system VARCHAR(255) NOT NULL COLLATE utf8mb4_unicode_ci, CHANGE integration_id user_id INT NOT NULL');
    }
}
