<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190725050052 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE integration_service_type (id INT AUTO_INCREMENT NOT NULL, title VARCHAR(255) NOT NULL, code VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE integration_service ADD type_id INT NOT NULL, DROP type');
        $this->addSql('ALTER TABLE integration_service ADD CONSTRAINT FK_8CB1208BC54C8C93 FOREIGN KEY (type_id) REFERENCES integration_service_type (id)');
        $this->addSql('CREATE INDEX IDX_8CB1208BC54C8C93 ON integration_service (type_id)');

        $this->addSql(
            'INSERT INTO integration_service_type (title, code) VALUES (:title, :code)',
            ['title' => 'BigQuery', 'code' => 'BIG_QUERY']
        );
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE integration_service DROP FOREIGN KEY FK_8CB1208BC54C8C93');
        $this->addSql('DROP TABLE integration_service_type');
        $this->addSql('DROP INDEX IDX_8CB1208BC54C8C93 ON integration_service');
        $this->addSql('ALTER TABLE integration_service ADD type VARCHAR(255) NOT NULL COLLATE utf8mb4_unicode_ci, DROP type_id');
    }
}
