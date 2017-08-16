<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20170816110003 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE jdj_post DROP FOREIGN KEY FK_3312CC5B1F55203D');
        $this->addSql('ALTER TABLE jdj_post ADD CONSTRAINT FK_3312CC5B1F55203D FOREIGN KEY (topic_id) REFERENCES jdj_topic (id) ON DELETE CASCADE');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE jdj_post DROP FOREIGN KEY FK_3312CC5B1F55203D');
        $this->addSql('ALTER TABLE jdj_post ADD CONSTRAINT FK_3312CC5B1F55203D FOREIGN KEY (topic_id) REFERENCES jdj_topic (id)');
    }
}
