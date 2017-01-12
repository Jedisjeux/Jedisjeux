<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20161027130156 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE jdj_notification (id INT AUTO_INCREMENT NOT NULL, topic_id INT DEFAULT NULL, recipient_id INT NOT NULL, product_id INT DEFAULT NULL, is_read TINYINT(1) NOT NULL, message LONGTEXT NOT NULL, target VARCHAR(255) NOT NULL, createdAt DATETIME DEFAULT NULL, updatedAt DATETIME DEFAULT NULL, INDEX IDX_5D9A5B2D1F55203D (topic_id), INDEX IDX_5D9A5B2DE92F8F78 (recipient_id), INDEX IDX_5D9A5B2D4584665A (product_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE jdj_notification ADD CONSTRAINT FK_5D9A5B2D1F55203D FOREIGN KEY (topic_id) REFERENCES jdj_topic (id)');
        $this->addSql('ALTER TABLE jdj_notification ADD CONSTRAINT FK_5D9A5B2DE92F8F78 FOREIGN KEY (recipient_id) REFERENCES sylius_customer (id)');
        $this->addSql('ALTER TABLE jdj_notification ADD CONSTRAINT FK_5D9A5B2D4584665A FOREIGN KEY (product_id) REFERENCES sylius_product (id)');
        $this->addSql('DROP TABLE Notification');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE Notification (id INT AUTO_INCREMENT NOT NULL, product_id INT DEFAULT NULL, topic_id INT DEFAULT NULL, recipient_id INT NOT NULL, is_read TINYINT(1) NOT NULL, message LONGTEXT NOT NULL COLLATE utf8_unicode_ci, target VARCHAR(255) NOT NULL COLLATE utf8_unicode_ci, createdAt DATETIME DEFAULT NULL, updatedAt DATETIME DEFAULT NULL, INDEX IDX_A765AD321F55203D (topic_id), INDEX IDX_A765AD32E92F8F78 (recipient_id), INDEX IDX_A765AD324584665A (product_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE Notification ADD CONSTRAINT FK_A765AD324584665A FOREIGN KEY (product_id) REFERENCES sylius_product (id)');
        $this->addSql('ALTER TABLE Notification ADD CONSTRAINT FK_A765AD321F55203D FOREIGN KEY (topic_id) REFERENCES jdj_topic (id)');
        $this->addSql('ALTER TABLE Notification ADD CONSTRAINT FK_A765AD32E92F8F78 FOREIGN KEY (recipient_id) REFERENCES sylius_customer (id)');
        $this->addSql('DROP TABLE jdj_notification');
    }
}
