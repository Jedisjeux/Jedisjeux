<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20160330003408 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE jdj_designer_product (product_id INT NOT NULL, person_id INT NOT NULL, INDEX IDX_7A89C4D44584665A (product_id), INDEX IDX_7A89C4D4217BBB47 (person_id), PRIMARY KEY(product_id, person_id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE jdj_artist_product (product_id INT NOT NULL, person_id INT NOT NULL, INDEX IDX_30E5810A4584665A (product_id), INDEX IDX_30E5810A217BBB47 (person_id), PRIMARY KEY(product_id, person_id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE jdj_publisher_product (product_id INT NOT NULL, person_id INT NOT NULL, INDEX IDX_603F17AE4584665A (product_id), INDEX IDX_603F17AE217BBB47 (person_id), PRIMARY KEY(product_id, person_id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE jdj_designer_product ADD CONSTRAINT FK_7A89C4D44584665A FOREIGN KEY (product_id) REFERENCES sylius_product (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE jdj_designer_product ADD CONSTRAINT FK_7A89C4D4217BBB47 FOREIGN KEY (person_id) REFERENCES jdj_person (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE jdj_artist_product ADD CONSTRAINT FK_30E5810A4584665A FOREIGN KEY (product_id) REFERENCES sylius_product (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE jdj_artist_product ADD CONSTRAINT FK_30E5810A217BBB47 FOREIGN KEY (person_id) REFERENCES jdj_person (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE jdj_publisher_product ADD CONSTRAINT FK_603F17AE4584665A FOREIGN KEY (product_id) REFERENCES sylius_product (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE jdj_publisher_product ADD CONSTRAINT FK_603F17AE217BBB47 FOREIGN KEY (person_id) REFERENCES jdj_person (id) ON DELETE CASCADE');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE jdj_designer_product');
        $this->addSql('DROP TABLE jdj_artist_product');
        $this->addSql('DROP TABLE jdj_publisher_product');
    }
}
