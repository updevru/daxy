<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190727045314 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE `order` CHANGE user_id integration_id INT NOT NULL');
        $this->addSql('ALTER TABLE `order` ADD CONSTRAINT FK_F52993989E82DDEA FOREIGN KEY (integration_id) REFERENCES integration_service (id)');
        $this->addSql('CREATE INDEX IDX_F52993989E82DDEA ON `order` (integration_id)');

        $this->addSql(
            'INSERT INTO integration_service_type (title, code) VALUES (:title, :code)',
            ['title' => 'RetailCRM', 'code' => 'RETAIL_CRM']
        );
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE `order` DROP FOREIGN KEY FK_F52993989E82DDEA');
        $this->addSql('DROP INDEX IDX_F52993989E82DDEA ON `order`');
        $this->addSql('ALTER TABLE `order` CHANGE integration_id user_id INT NOT NULL');
    }
}
