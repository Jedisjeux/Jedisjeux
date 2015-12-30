<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20151230151707 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE jdj_personne DROP FOREIGN KEY FK_587FAB24A6E44244');
        $this->addSql('DROP INDEX IDX_587FAB24A6E44244 ON jdj_personne');
        $this->addSql('ALTER TABLE jdj_personne CHANGE pays_id country_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE jdj_personne ADD CONSTRAINT FK_587FAB24F92F3E70 FOREIGN KEY (country_id) REFERENCES jdj_country (id)');
        $this->addSql('CREATE INDEX IDX_587FAB24F92F3E70 ON jdj_personne (country_id)');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE jdj_personne DROP FOREIGN KEY FK_587FAB24F92F3E70');
        $this->addSql('DROP INDEX IDX_587FAB24F92F3E70 ON jdj_personne');
        $this->addSql('ALTER TABLE jdj_personne CHANGE country_id pays_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE jdj_personne ADD CONSTRAINT FK_587FAB24A6E44244 FOREIGN KEY (pays_id) REFERENCES jdj_pays (id)');
        $this->addSql('CREATE INDEX IDX_587FAB24A6E44244 ON jdj_personne (pays_id)');
    }
}
