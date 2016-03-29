<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20160329234102 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE jdj_designer_product DROP FOREIGN KEY FK_7A89C4D4A21BD112');
        $this->addSql('DROP INDEX IDX_7A89C4D4A21BD112 ON jdj_designer_product');
        $this->addSql('ALTER TABLE jdj_designer_product DROP PRIMARY KEY');
        $this->addSql('ALTER TABLE jdj_designer_product CHANGE personne_id person_id INT NOT NULL');
        $this->addSql('ALTER TABLE jdj_designer_product ADD CONSTRAINT FK_7A89C4D4217BBB47 FOREIGN KEY (person_id) REFERENCES jdj_person (id) ON DELETE CASCADE');
        $this->addSql('CREATE INDEX IDX_7A89C4D4217BBB47 ON jdj_designer_product (person_id)');
        $this->addSql('ALTER TABLE jdj_designer_product ADD PRIMARY KEY (product_id, person_id)');
        $this->addSql('ALTER TABLE jdj_artist_product DROP FOREIGN KEY FK_30E5810AA21BD112');
        $this->addSql('DROP INDEX IDX_30E5810AA21BD112 ON jdj_artist_product');
        $this->addSql('ALTER TABLE jdj_artist_product DROP PRIMARY KEY');
        $this->addSql('ALTER TABLE jdj_artist_product CHANGE personne_id person_id INT NOT NULL');
        $this->addSql('ALTER TABLE jdj_artist_product ADD CONSTRAINT FK_30E5810A217BBB47 FOREIGN KEY (person_id) REFERENCES jdj_person (id) ON DELETE CASCADE');
        $this->addSql('CREATE INDEX IDX_30E5810A217BBB47 ON jdj_artist_product (person_id)');
        $this->addSql('ALTER TABLE jdj_artist_product ADD PRIMARY KEY (product_id, person_id)');
        $this->addSql('ALTER TABLE jdj_publisher_product DROP FOREIGN KEY FK_603F17AEA21BD112');
        $this->addSql('DROP INDEX IDX_603F17AEA21BD112 ON jdj_publisher_product');
        $this->addSql('ALTER TABLE jdj_publisher_product DROP PRIMARY KEY');
        $this->addSql('ALTER TABLE jdj_publisher_product CHANGE personne_id person_id INT NOT NULL');
        $this->addSql('ALTER TABLE jdj_publisher_product ADD CONSTRAINT FK_603F17AE217BBB47 FOREIGN KEY (person_id) REFERENCES jdj_person (id) ON DELETE CASCADE');
        $this->addSql('CREATE INDEX IDX_603F17AE217BBB47 ON jdj_publisher_product (person_id)');
        $this->addSql('ALTER TABLE jdj_publisher_product ADD PRIMARY KEY (product_id, person_id)');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE jdj_artist_product DROP FOREIGN KEY FK_30E5810A217BBB47');
        $this->addSql('DROP INDEX IDX_30E5810A217BBB47 ON jdj_artist_product');
        $this->addSql('ALTER TABLE jdj_artist_product DROP PRIMARY KEY');
        $this->addSql('ALTER TABLE jdj_artist_product CHANGE person_id personne_id INT NOT NULL');
        $this->addSql('ALTER TABLE jdj_artist_product ADD CONSTRAINT FK_30E5810AA21BD112 FOREIGN KEY (personne_id) REFERENCES jdj_personne (id) ON DELETE CASCADE');
        $this->addSql('CREATE INDEX IDX_30E5810AA21BD112 ON jdj_artist_product (personne_id)');
        $this->addSql('ALTER TABLE jdj_artist_product ADD PRIMARY KEY (product_id, personne_id)');
        $this->addSql('ALTER TABLE jdj_designer_product DROP FOREIGN KEY FK_7A89C4D4217BBB47');
        $this->addSql('DROP INDEX IDX_7A89C4D4217BBB47 ON jdj_designer_product');
        $this->addSql('ALTER TABLE jdj_designer_product DROP PRIMARY KEY');
        $this->addSql('ALTER TABLE jdj_designer_product CHANGE person_id personne_id INT NOT NULL');
        $this->addSql('ALTER TABLE jdj_designer_product ADD CONSTRAINT FK_7A89C4D4A21BD112 FOREIGN KEY (personne_id) REFERENCES jdj_personne (id) ON DELETE CASCADE');
        $this->addSql('CREATE INDEX IDX_7A89C4D4A21BD112 ON jdj_designer_product (personne_id)');
        $this->addSql('ALTER TABLE jdj_designer_product ADD PRIMARY KEY (product_id, personne_id)');
        $this->addSql('ALTER TABLE jdj_publisher_product DROP FOREIGN KEY FK_603F17AE217BBB47');
        $this->addSql('DROP INDEX IDX_603F17AE217BBB47 ON jdj_publisher_product');
        $this->addSql('ALTER TABLE jdj_publisher_product DROP PRIMARY KEY');
        $this->addSql('ALTER TABLE jdj_publisher_product CHANGE person_id personne_id INT NOT NULL');
        $this->addSql('ALTER TABLE jdj_publisher_product ADD CONSTRAINT FK_603F17AEA21BD112 FOREIGN KEY (personne_id) REFERENCES jdj_personne (id) ON DELETE CASCADE');
        $this->addSql('CREATE INDEX IDX_603F17AEA21BD112 ON jdj_publisher_product (personne_id)');
        $this->addSql('ALTER TABLE jdj_publisher_product ADD PRIMARY KEY (product_id, personne_id)');
    }
}
