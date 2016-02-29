<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20160229081346 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE jdj_post ADD replyTo_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE jdj_post ADD CONSTRAINT FK_3312CC5BFC7F804C FOREIGN KEY (replyTo_id) REFERENCES jdj_post (id)');
        $this->addSql('CREATE INDEX IDX_3312CC5BFC7F804C ON jdj_post (replyTo_id)');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE jdj_post DROP FOREIGN KEY FK_3312CC5BFC7F804C');
        $this->addSql('DROP INDEX IDX_3312CC5BFC7F804C ON jdj_post');
        $this->addSql('ALTER TABLE jdj_post DROP replyTo_id');
    }
}
