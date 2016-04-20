<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20160419105202 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE jdj_personne_image DROP FOREIGN KEY FK_853CCEA6A21BD112');
        $this->addSql('DROP TABLE jdj_personne');
        $this->addSql('DROP TABLE jdj_personne_image');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE jdj_personne (id INT AUTO_INCREMENT NOT NULL, image_id INT DEFAULT NULL, country_id INT DEFAULT NULL, nom VARCHAR(50) NOT NULL COLLATE utf8_unicode_ci, prenom VARCHAR(50) NOT NULL COLLATE utf8_unicode_ci, siteWeb VARCHAR(200) DEFAULT NULL COLLATE utf8_unicode_ci, description LONGTEXT DEFAULT NULL COLLATE utf8_unicode_ci, slug VARCHAR(128) NOT NULL COLLATE utf8_unicode_ci, INDEX IDX_587FAB243DA5256D (image_id), INDEX search_idx (slug), INDEX IDX_587FAB24F92F3E70 (country_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE jdj_personne_image (personne_id INT NOT NULL, image_id INT NOT NULL, INDEX IDX_853CCEA6A21BD112 (personne_id), INDEX IDX_853CCEA63DA5256D (image_id), PRIMARY KEY(personne_id, image_id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE jdj_personne ADD CONSTRAINT FK_587FAB243DA5256D FOREIGN KEY (image_id) REFERENCES jdj_image (id)');
        $this->addSql('ALTER TABLE jdj_personne ADD CONSTRAINT FK_587FAB24F92F3E70 FOREIGN KEY (country_id) REFERENCES jdj_country (id)');
        $this->addSql('ALTER TABLE jdj_personne_image ADD CONSTRAINT FK_853CCEA63DA5256D FOREIGN KEY (image_id) REFERENCES jdj_image (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE jdj_personne_image ADD CONSTRAINT FK_853CCEA6A21BD112 FOREIGN KEY (personne_id) REFERENCES jdj_personne (id) ON DELETE CASCADE');
    }
}
