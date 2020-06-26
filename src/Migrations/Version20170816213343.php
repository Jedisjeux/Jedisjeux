<?php

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20170816213343 extends AbstractMigration
{
    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf('mysql' !== $this->connection->getDatabasePlatform()->getName(), 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE jdj_post DROP FOREIGN KEY FK_3312CC5B7294869C');
        $this->addSql('ALTER TABLE jdj_post DROP FOREIGN KEY FK_3312CC5BB0951292');
        $this->addSql('ALTER TABLE jdj_post ADD CONSTRAINT FK_3312CC5B7294869C FOREIGN KEY (article_id) REFERENCES jdj_article (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE jdj_post ADD CONSTRAINT FK_3312CC5BB0951292 FOREIGN KEY (game_play_id) REFERENCES jdj_game_play (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf('mysql' !== $this->connection->getDatabasePlatform()->getName(), 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE jdj_post DROP FOREIGN KEY FK_3312CC5BB0951292');
        $this->addSql('ALTER TABLE jdj_post DROP FOREIGN KEY FK_3312CC5B7294869C');
        $this->addSql('ALTER TABLE jdj_post ADD CONSTRAINT FK_3312CC5BB0951292 FOREIGN KEY (game_play_id) REFERENCES jdj_game_play (id)');
        $this->addSql('ALTER TABLE jdj_post ADD CONSTRAINT FK_3312CC5B7294869C FOREIGN KEY (article_id) REFERENCES jdj_article (id)');
    }
}
