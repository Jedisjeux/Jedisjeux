<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20170220142349 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE jdj_post ADD gamePlay_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE jdj_post ADD CONSTRAINT FK_3312CC5B6EEC1E6A FOREIGN KEY (gamePlay_id) REFERENCES jdj_game_play (id)');
        $this->addSql('CREATE INDEX IDX_3312CC5B6EEC1E6A ON jdj_post (gamePlay_id)');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE jdj_post DROP FOREIGN KEY FK_3312CC5B6EEC1E6A');
        $this->addSql('DROP INDEX IDX_3312CC5B6EEC1E6A ON jdj_post');
        $this->addSql('ALTER TABLE jdj_post DROP gamePlay_id');
    }
}
