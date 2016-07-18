<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20160719005944 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE app_article_review ADD author_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE app_article_review ADD CONSTRAINT FK_CC7E84B8F675F31B FOREIGN KEY (author_id) REFERENCES sylius_customer (id)');
        $this->addSql('CREATE INDEX IDX_CC7E84B8F675F31B ON app_article_review (author_id)');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE app_article_review DROP FOREIGN KEY FK_CC7E84B8F675F31B');
        $this->addSql('DROP INDEX IDX_CC7E84B8F675F31B ON app_article_review');
        $this->addSql('ALTER TABLE app_article_review DROP author_id');
    }
}
