<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20160408001523 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE jdj_player ADD gamePlay_id INT NOT NULL');
        $this->addSql('ALTER TABLE jdj_player ADD CONSTRAINT FK_99ADD28C6EEC1E6A FOREIGN KEY (gamePlay_id) REFERENCES jdj_game_play (id)');
        $this->addSql('CREATE INDEX IDX_99ADD28C6EEC1E6A ON jdj_player (gamePlay_id)');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE jdj_player DROP FOREIGN KEY FK_99ADD28C6EEC1E6A');
        $this->addSql('DROP INDEX IDX_99ADD28C6EEC1E6A ON jdj_player');
        $this->addSql('ALTER TABLE jdj_player DROP gamePlay_id');
    }
}
