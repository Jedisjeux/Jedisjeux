<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20161027155833 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE jdj_notification ADD article_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE jdj_notification ADD CONSTRAINT FK_5D9A5B2D7294869C FOREIGN KEY (article_id) REFERENCES jdj_article (id)');
        $this->addSql('CREATE INDEX IDX_5D9A5B2D7294869C ON jdj_notification (article_id)');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE jdj_notification DROP FOREIGN KEY FK_5D9A5B2D7294869C');
        $this->addSql('DROP INDEX IDX_5D9A5B2D7294869C ON jdj_notification');
        $this->addSql('ALTER TABLE jdj_notification DROP article_id');
    }
}
