<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20160421140020 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE jdj_article ADD topic_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE jdj_article ADD CONSTRAINT FK_DBEDE0121F55203D FOREIGN KEY (topic_id) REFERENCES jdj_topic (id) ON DELETE SET NULL');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_DBEDE0121F55203D ON jdj_article (topic_id)');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE jdj_article DROP FOREIGN KEY FK_DBEDE0121F55203D');
        $this->addSql('DROP INDEX UNIQ_DBEDE0121F55203D ON jdj_article');
        $this->addSql('ALTER TABLE jdj_article DROP topic_id');
    }
}
