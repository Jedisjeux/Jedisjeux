<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20160323082647 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE jdj_game_play DROP FOREIGN KEY FK_B97D18CC1F55203D');
        $this->addSql('ALTER TABLE jdj_game_play ADD CONSTRAINT FK_B97D18CC1F55203D FOREIGN KEY (topic_id) REFERENCES jdj_topic (id) ON DELETE SET NULL');
        $this->addSql('ALTER TABLE jdj_topic DROP FOREIGN KEY FK_F299315AE21B6525');
        $this->addSql('ALTER TABLE jdj_topic ADD CONSTRAINT FK_F299315AE21B6525 FOREIGN KEY (mainPost_id) REFERENCES jdj_post (id) ON DELETE SET NULL');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE jdj_game_play DROP FOREIGN KEY FK_B97D18CC1F55203D');
        $this->addSql('ALTER TABLE jdj_game_play ADD CONSTRAINT FK_B97D18CC1F55203D FOREIGN KEY (topic_id) REFERENCES jdj_topic (id)');
        $this->addSql('ALTER TABLE jdj_topic DROP FOREIGN KEY FK_F299315AE21B6525');
        $this->addSql('ALTER TABLE jdj_topic ADD CONSTRAINT FK_F299315AE21B6525 FOREIGN KEY (mainPost_id) REFERENCES jdj_post (id)');
    }
}
