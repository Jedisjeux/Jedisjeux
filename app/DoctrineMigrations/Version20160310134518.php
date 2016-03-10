<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20160310134518 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE jdj_designer_product (product_id INT NOT NULL, personne_id INT NOT NULL, INDEX IDX_7A89C4D44584665A (product_id), INDEX IDX_7A89C4D4A21BD112 (personne_id), PRIMARY KEY(product_id, personne_id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE jdj_designer_product ADD CONSTRAINT FK_7A89C4D44584665A FOREIGN KEY (product_id) REFERENCES sylius_product (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE jdj_designer_product ADD CONSTRAINT FK_7A89C4D4A21BD112 FOREIGN KEY (personne_id) REFERENCES jdj_personne (id) ON DELETE CASCADE');
        $this->addSql('DROP TABLE jdj_deisgner_product');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE jdj_deisgner_product (product_id INT NOT NULL, personne_id INT NOT NULL, INDEX IDX_4054E2DF4584665A (product_id), INDEX IDX_4054E2DFA21BD112 (personne_id), PRIMARY KEY(product_id, personne_id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE jdj_deisgner_product ADD CONSTRAINT FK_4054E2DF4584665A FOREIGN KEY (product_id) REFERENCES sylius_product (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE jdj_deisgner_product ADD CONSTRAINT FK_4054E2DFA21BD112 FOREIGN KEY (personne_id) REFERENCES jdj_personne (id) ON DELETE CASCADE');
        $this->addSql('DROP TABLE jdj_designer_product');
    }
}
