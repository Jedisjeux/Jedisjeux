<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20170307122212 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE jdj_slideshow_block (id INT AUTO_INCREMENT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE jdj_article ADD slideShowBlock_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE jdj_article ADD CONSTRAINT FK_DBEDE012B22630A5 FOREIGN KEY (slideShowBlock_id) REFERENCES jdj_slideshow_block (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_DBEDE012B22630A5 ON jdj_article (slideShowBlock_id)');
        $this->addSql('ALTER TABLE jdj_block ADD slideShowBlock_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE jdj_block ADD CONSTRAINT FK_ECC27863B22630A5 FOREIGN KEY (slideShowBlock_id) REFERENCES jdj_slideshow_block (id)');
        $this->addSql('CREATE INDEX IDX_ECC27863B22630A5 ON jdj_block (slideShowBlock_id)');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE jdj_article DROP FOREIGN KEY FK_DBEDE012B22630A5');
        $this->addSql('ALTER TABLE jdj_block DROP FOREIGN KEY FK_ECC27863B22630A5');
        $this->addSql('DROP TABLE jdj_slideshow_block');
        $this->addSql('DROP INDEX UNIQ_DBEDE012B22630A5 ON jdj_article');
        $this->addSql('ALTER TABLE jdj_article DROP slideShowBlock_id');
        $this->addSql('DROP INDEX IDX_ECC27863B22630A5 ON jdj_block');
        $this->addSql('ALTER TABLE jdj_block DROP slideShowBlock_id');
    }
}
