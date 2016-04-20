<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20160420131617 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE jdj_article ADD author_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE jdj_article ADD CONSTRAINT FK_DBEDE012F675F31B FOREIGN KEY (author_id) REFERENCES sylius_customer (id)');
        $this->addSql('CREATE INDEX IDX_DBEDE012F675F31B ON jdj_article (author_id)');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE jdj_article DROP FOREIGN KEY FK_DBEDE012F675F31B');
        $this->addSql('DROP INDEX IDX_DBEDE012F675F31B ON jdj_article');
        $this->addSql('ALTER TABLE jdj_article DROP author_id');
    }
}
