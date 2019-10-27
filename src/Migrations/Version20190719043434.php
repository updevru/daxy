<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190719043434 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE `traffic_source` (id INT AUTO_INCREMENT NOT NULL, title VARCHAR(255) NOT NULL, user_id INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE `traffic_source_label` (id INT AUTO_INCREMENT NOT NULL, traffic_source_id INT DEFAULT NULL, source VARCHAR(255) NOT NULL, medium VARCHAR(255) DEFAULT NULL, campaign VARCHAR(255) DEFAULT NULL, INDEX IDX_3268A17DE0BEA2C6 (traffic_source_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE `advert_system` (id INT AUTO_INCREMENT NOT NULL, title VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE `order` (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, order_id VARCHAR(255) NOT NULL, status VARCHAR(255) NOT NULL, customer_id VARCHAR(255) NOT NULL, date_create DATETIME NOT NULL, summ DOUBLE PRECISION NOT NULL, delivery_summ DOUBLE PRECISION NOT NULL, delivery_cost DOUBLE PRECISION NOT NULL, product_cost DOUBLE PRECISION NOT NULL, profit DOUBLE PRECISION NOT NULL, order_index INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE `user` (id INT AUTO_INCREMENT NOT NULL, username VARCHAR(180) NOT NULL, username_canonical VARCHAR(180) NOT NULL, email VARCHAR(180) NOT NULL, email_canonical VARCHAR(180) NOT NULL, enabled TINYINT(1) NOT NULL, salt VARCHAR(255) DEFAULT NULL, password VARCHAR(255) NOT NULL, last_login DATETIME DEFAULT NULL, confirmation_token VARCHAR(180) DEFAULT NULL, password_requested_at DATETIME DEFAULT NULL, roles LONGTEXT NOT NULL COMMENT \'(DC2Type:array)\', UNIQUE INDEX UNIQ_8D93D64992FC23A8 (username_canonical), UNIQUE INDEX UNIQ_8D93D649A0D96FBF (email_canonical), UNIQUE INDEX UNIQ_8D93D649C05FB297 (confirmation_token), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE `order_source` (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, type VARCHAR(255) NOT NULL, system VARCHAR(255) NOT NULL, date DATETIME NOT NULL, order_id VARCHAR(255) NOT NULL, source VARCHAR(255) DEFAULT NULL, medium VARCHAR(255) DEFAULT NULL, campaign VARCHAR(255) DEFAULT NULL, keyword VARCHAR(255) DEFAULT NULL, amount DOUBLE PRECISION NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE `traffic_cost` (id INT AUTO_INCREMENT NOT NULL, advert_system_id INT NOT NULL, user_id INT NOT NULL, date DATETIME NOT NULL, cost DOUBLE PRECISION NOT NULL, views INT NOT NULL, clicks INT NOT NULL, source VARCHAR(255) NOT NULL, medium VARCHAR(255) NOT NULL, campaign VARCHAR(255) DEFAULT NULL, keyword VARCHAR(255) DEFAULT NULL, INDEX IDX_EE60CEB24AA83EA9 (advert_system_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE `traffic_source_label` ADD CONSTRAINT FK_3268A17DE0BEA2C6 FOREIGN KEY (traffic_source_id) REFERENCES `traffic_source` (id)');
        $this->addSql('ALTER TABLE `traffic_cost` ADD CONSTRAINT FK_EE60CEB24AA83EA9 FOREIGN KEY (advert_system_id) REFERENCES `advert_system` (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE `traffic_source_label` DROP FOREIGN KEY FK_3268A17DE0BEA2C6');
        $this->addSql('ALTER TABLE `traffic_cost` DROP FOREIGN KEY FK_EE60CEB24AA83EA9');
        $this->addSql('DROP TABLE `traffic_source`');
        $this->addSql('DROP TABLE `traffic_source_label`');
        $this->addSql('DROP TABLE `advert_system`');
        $this->addSql('DROP TABLE `order`');
        $this->addSql('DROP TABLE `user`');
        $this->addSql('DROP TABLE `order_source`');
        $this->addSql('DROP TABLE `traffic_cost`');
    }
}
