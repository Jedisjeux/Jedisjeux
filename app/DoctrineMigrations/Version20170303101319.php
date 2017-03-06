<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20170303101319 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE jdj_article_image (id INT AUTO_INCREMENT NOT NULL, path VARCHAR(255) DEFAULT NULL, createdAt DATETIME DEFAULT NULL, updatedAt DATETIME DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE jdj_article ADD mainImage_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE jdj_article ADD CONSTRAINT FK_DBEDE01244D00FAF FOREIGN KEY (mainImage_id) REFERENCES jdj_article_image (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_DBEDE01244D00FAF ON jdj_article (mainImage_id)');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE jdj_article DROP FOREIGN KEY FK_DBEDE01244D00FAF');
        $this->addSql('DROP TABLE jdj_article_image');
        $this->addSql('DROP INDEX UNIQ_DBEDE01244D00FAF ON jdj_article');
        $this->addSql('ALTER TABLE jdj_article DROP mainImage_id');
    }
}
