<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20160302083311 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE jdj_game_taxon (jeu_id INT NOT NULL, taxoninterface_id INT NOT NULL, INDEX IDX_E2E603648C9E392E (jeu_id), INDEX IDX_E2E603647E2DD0E0 (taxoninterface_id), PRIMARY KEY(jeu_id, taxoninterface_id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE jdj_game_taxon ADD CONSTRAINT FK_E2E603648C9E392E FOREIGN KEY (jeu_id) REFERENCES jdj_jeu (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE jdj_game_taxon ADD CONSTRAINT FK_E2E603647E2DD0E0 FOREIGN KEY (taxoninterface_id) REFERENCES Taxon (id) ON DELETE CASCADE');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE jdj_game_taxon');
    }
}
