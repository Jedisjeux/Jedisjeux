<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20160725151917 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE Notification ADD topic_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE Notification ADD CONSTRAINT FK_A765AD321F55203D FOREIGN KEY (topic_id) REFERENCES jdj_topic (id)');
        $this->addSql('CREATE INDEX IDX_A765AD321F55203D ON Notification (topic_id)');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE Notification DROP FOREIGN KEY FK_A765AD321F55203D');
        $this->addSql('DROP INDEX IDX_A765AD321F55203D ON Notification');
        $this->addSql('ALTER TABLE Notification DROP topic_id');
    }
}
