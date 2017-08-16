<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20170816213343 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE jdj_post DROP FOREIGN KEY FK_3312CC5B7294869C');
        $this->addSql('ALTER TABLE jdj_post DROP FOREIGN KEY FK_3312CC5BB0951292');
        $this->addSql('ALTER TABLE jdj_post ADD CONSTRAINT FK_3312CC5B7294869C FOREIGN KEY (article_id) REFERENCES jdj_article (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE jdj_post ADD CONSTRAINT FK_3312CC5BB0951292 FOREIGN KEY (game_play_id) REFERENCES jdj_game_play (id) ON DELETE CASCADE');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE jdj_post DROP FOREIGN KEY FK_3312CC5BB0951292');
        $this->addSql('ALTER TABLE jdj_post DROP FOREIGN KEY FK_3312CC5B7294869C');
        $this->addSql('ALTER TABLE jdj_post ADD CONSTRAINT FK_3312CC5BB0951292 FOREIGN KEY (game_play_id) REFERENCES jdj_game_play (id)');
        $this->addSql('ALTER TABLE jdj_post ADD CONSTRAINT FK_3312CC5B7294869C FOREIGN KEY (article_id) REFERENCES jdj_article (id)');
    }
}
