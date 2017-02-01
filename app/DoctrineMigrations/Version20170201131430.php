<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20170201131430 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE jdj_customer_avatar ADD createdAt DATETIME DEFAULT NULL, ADD updatedAt DATETIME DEFAULT NULL');
        $this->addSql('ALTER TABLE jdj_dealer_image ADD createdAt DATETIME DEFAULT NULL, ADD updatedAt DATETIME DEFAULT NULL');
        $this->addSql('ALTER TABLE jdj_game_play_image ADD createdAt DATETIME DEFAULT NULL, ADD updatedAt DATETIME DEFAULT NULL');
        $this->addSql('ALTER TABLE jdj_person_image ADD createdAt DATETIME DEFAULT NULL, ADD updatedAt DATETIME DEFAULT NULL');
        $this->addSql('ALTER TABLE sylius_product_variant_image ADD createdAt DATETIME DEFAULT NULL, ADD updatedAt DATETIME DEFAULT NULL');
        $this->addSql('ALTER TABLE jdj_pub_banner ADD createdAt DATETIME DEFAULT NULL, ADD updatedAt DATETIME DEFAULT NULL');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE jdj_customer_avatar DROP createdAt, DROP updatedAt');
        $this->addSql('ALTER TABLE jdj_dealer_image DROP createdAt, DROP updatedAt');
        $this->addSql('ALTER TABLE jdj_game_play_image DROP createdAt, DROP updatedAt');
        $this->addSql('ALTER TABLE jdj_person_image DROP createdAt, DROP updatedAt');
        $this->addSql('ALTER TABLE jdj_pub_banner DROP createdAt, DROP updatedAt');
        $this->addSql('ALTER TABLE sylius_product_variant_image DROP createdAt, DROP updatedAt');
    }
}
