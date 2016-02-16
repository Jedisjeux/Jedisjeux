<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20160215235507 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE jdj_game_review (id INT AUTO_INCREMENT NOT NULL, rate_id INT DEFAULT NULL, UNIQUE INDEX UNIQ_7221DFC7BC999F9F (rate_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE jdj_game_rate (id INT AUTO_INCREMENT NOT NULL, game_id INT DEFAULT NULL, value DOUBLE PRECISION NOT NULL, createdBy_id INT NOT NULL, updatedBy_id INT DEFAULT NULL, INDEX IDX_3818F94FE48FD905 (game_id), INDEX IDX_3818F94F3174800F (createdBy_id), INDEX IDX_3818F94F65FF1AEC (updatedBy_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE jdj_game_review ADD CONSTRAINT FK_7221DFC7BC999F9F FOREIGN KEY (rate_id) REFERENCES jdj_game_rate (id)');
        $this->addSql('ALTER TABLE jdj_game_rate ADD CONSTRAINT FK_3818F94FE48FD905 FOREIGN KEY (game_id) REFERENCES jdj_jeu (id)');
        $this->addSql('ALTER TABLE jdj_game_rate ADD CONSTRAINT FK_3818F94F3174800F FOREIGN KEY (createdBy_id) REFERENCES fos_user (id)');
        $this->addSql('ALTER TABLE jdj_game_rate ADD CONSTRAINT FK_3818F94F65FF1AEC FOREIGN KEY (updatedBy_id) REFERENCES fos_user (id)');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE jdj_game_review DROP FOREIGN KEY FK_7221DFC7BC999F9F');
        $this->addSql('DROP TABLE jdj_game_review');
        $this->addSql('DROP TABLE jdj_game_rate');
    }
}
