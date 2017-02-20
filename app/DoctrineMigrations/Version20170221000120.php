<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20170221000120 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE jdj_post ADD article_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE jdj_post ADD CONSTRAINT FK_3312CC5B7294869C FOREIGN KEY (article_id) REFERENCES jdj_article (id)');
        $this->addSql('CREATE INDEX IDX_3312CC5B7294869C ON jdj_post (article_id)');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE jdj_post DROP FOREIGN KEY FK_3312CC5B7294869C');
        $this->addSql('DROP INDEX IDX_3312CC5B7294869C ON jdj_post');
        $this->addSql('ALTER TABLE jdj_post DROP article_id');
    }
}
