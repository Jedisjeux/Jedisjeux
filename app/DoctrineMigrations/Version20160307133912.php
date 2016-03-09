<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20160307133912 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE jdj_deisgner_product (product_id INT NOT NULL, personne_id INT NOT NULL, INDEX IDX_4054E2DF4584665A (product_id), INDEX IDX_4054E2DFA21BD112 (personne_id), PRIMARY KEY(product_id, personne_id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE jdj_artist_product (product_id INT NOT NULL, personne_id INT NOT NULL, INDEX IDX_30E5810A4584665A (product_id), INDEX IDX_30E5810AA21BD112 (personne_id), PRIMARY KEY(product_id, personne_id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE jdj_publisher_product (product_id INT NOT NULL, personne_id INT NOT NULL, INDEX IDX_603F17AE4584665A (product_id), INDEX IDX_603F17AEA21BD112 (personne_id), PRIMARY KEY(product_id, personne_id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE sylius_product_taxon (product_id INT NOT NULL, taxoninterface_id INT NOT NULL, INDEX IDX_169C6CD94584665A (product_id), INDEX IDX_169C6CD97E2DD0E0 (taxoninterface_id), PRIMARY KEY(product_id, taxoninterface_id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE jdj_deisgner_product ADD CONSTRAINT FK_4054E2DF4584665A FOREIGN KEY (product_id) REFERENCES sylius_product (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE jdj_deisgner_product ADD CONSTRAINT FK_4054E2DFA21BD112 FOREIGN KEY (personne_id) REFERENCES jdj_personne (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE jdj_artist_product ADD CONSTRAINT FK_30E5810A4584665A FOREIGN KEY (product_id) REFERENCES sylius_product (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE jdj_artist_product ADD CONSTRAINT FK_30E5810AA21BD112 FOREIGN KEY (personne_id) REFERENCES jdj_personne (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE jdj_publisher_product ADD CONSTRAINT FK_603F17AE4584665A FOREIGN KEY (product_id) REFERENCES sylius_product (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE jdj_publisher_product ADD CONSTRAINT FK_603F17AEA21BD112 FOREIGN KEY (personne_id) REFERENCES jdj_personne (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE sylius_product_taxon ADD CONSTRAINT FK_169C6CD94584665A FOREIGN KEY (product_id) REFERENCES sylius_product (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE sylius_product_taxon ADD CONSTRAINT FK_169C6CD97E2DD0E0 FOREIGN KEY (taxoninterface_id) REFERENCES Taxon (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE sylius_product ADD ageMin INT UNSIGNED DEFAULT NULL, ADD joueurMin INT UNSIGNED DEFAULT NULL, ADD joueurMax INT UNSIGNED DEFAULT NULL, ADD durationMin INT UNSIGNED DEFAULT NULL, ADD durationMax INT UNSIGNED DEFAULT NULL, ADD durationByPlayer TINYINT(1) NOT NULL, ADD intro LONGTEXT DEFAULT NULL, ADD materiel LONGTEXT DEFAULT NULL, ADD but LONGTEXT DEFAULT NULL, ADD mainTaxon_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE sylius_product ADD CONSTRAINT FK_677B9B74A766DEB2 FOREIGN KEY (mainTaxon_id) REFERENCES Taxon (id)');
        $this->addSql('CREATE INDEX IDX_677B9B74A766DEB2 ON sylius_product (mainTaxon_id)');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE jdj_deisgner_product');
        $this->addSql('DROP TABLE jdj_artist_product');
        $this->addSql('DROP TABLE jdj_publisher_product');
        $this->addSql('DROP TABLE sylius_product_taxon');
        $this->addSql('ALTER TABLE sylius_product DROP FOREIGN KEY FK_677B9B74A766DEB2');
        $this->addSql('DROP INDEX IDX_677B9B74A766DEB2 ON sylius_product');
        $this->addSql('ALTER TABLE sylius_product DROP ageMin, DROP joueurMin, DROP joueurMax, DROP durationMin, DROP durationMax, DROP durationByPlayer, DROP intro, DROP materiel, DROP but, DROP mainTaxon_id');
    }
}
