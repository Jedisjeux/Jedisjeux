<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20160314140838 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE sylius_customer ADD code VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE jdj_post DROP FOREIGN KEY FK_3312CC5B3174800F');
        $this->addSql('ALTER TABLE jdj_post DROP FOREIGN KEY FK_3312CC5B63D8C20E');
        $this->addSql('ALTER TABLE jdj_post DROP FOREIGN KEY FK_3312CC5B65FF1AEC');
        $this->addSql('ALTER TABLE jdj_post ADD CONSTRAINT FK_3312CC5B3174800F FOREIGN KEY (createdBy_id) REFERENCES sylius_customer (id) ON DELETE SET NULL');
        $this->addSql('ALTER TABLE jdj_post ADD CONSTRAINT FK_3312CC5B63D8C20E FOREIGN KEY (deletedBy_id) REFERENCES sylius_customer (id) ON DELETE SET NULL');
        $this->addSql('ALTER TABLE jdj_post ADD CONSTRAINT FK_3312CC5B65FF1AEC FOREIGN KEY (updatedBy_id) REFERENCES sylius_customer (id) ON DELETE SET NULL');
        $this->addSql('ALTER TABLE jdj_topic DROP FOREIGN KEY FK_F299315A3174800F');
        $this->addSql('ALTER TABLE jdj_topic DROP FOREIGN KEY FK_F299315A63D8C20E');
        $this->addSql('ALTER TABLE jdj_topic DROP FOREIGN KEY FK_F299315A65FF1AEC');
        $this->addSql('ALTER TABLE jdj_topic ADD CONSTRAINT FK_F299315A3174800F FOREIGN KEY (createdBy_id) REFERENCES sylius_customer (id) ON DELETE SET NULL');
        $this->addSql('ALTER TABLE jdj_topic ADD CONSTRAINT FK_F299315A63D8C20E FOREIGN KEY (deletedBy_id) REFERENCES sylius_customer (id) ON DELETE SET NULL');
        $this->addSql('ALTER TABLE jdj_topic ADD CONSTRAINT FK_F299315A65FF1AEC FOREIGN KEY (updatedBy_id) REFERENCES sylius_customer (id) ON DELETE SET NULL');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE jdj_post DROP FOREIGN KEY FK_3312CC5B3174800F');
        $this->addSql('ALTER TABLE jdj_post DROP FOREIGN KEY FK_3312CC5B65FF1AEC');
        $this->addSql('ALTER TABLE jdj_post DROP FOREIGN KEY FK_3312CC5B63D8C20E');
        $this->addSql('ALTER TABLE jdj_post ADD CONSTRAINT FK_3312CC5B3174800F FOREIGN KEY (createdBy_id) REFERENCES fos_user (id) ON DELETE SET NULL');
        $this->addSql('ALTER TABLE jdj_post ADD CONSTRAINT FK_3312CC5B65FF1AEC FOREIGN KEY (updatedBy_id) REFERENCES fos_user (id) ON DELETE SET NULL');
        $this->addSql('ALTER TABLE jdj_post ADD CONSTRAINT FK_3312CC5B63D8C20E FOREIGN KEY (deletedBy_id) REFERENCES fos_user (id) ON DELETE SET NULL');
        $this->addSql('ALTER TABLE jdj_topic DROP FOREIGN KEY FK_F299315A3174800F');
        $this->addSql('ALTER TABLE jdj_topic DROP FOREIGN KEY FK_F299315A65FF1AEC');
        $this->addSql('ALTER TABLE jdj_topic DROP FOREIGN KEY FK_F299315A63D8C20E');
        $this->addSql('ALTER TABLE jdj_topic ADD CONSTRAINT FK_F299315A3174800F FOREIGN KEY (createdBy_id) REFERENCES fos_user (id) ON DELETE SET NULL');
        $this->addSql('ALTER TABLE jdj_topic ADD CONSTRAINT FK_F299315A65FF1AEC FOREIGN KEY (updatedBy_id) REFERENCES fos_user (id) ON DELETE SET NULL');
        $this->addSql('ALTER TABLE jdj_topic ADD CONSTRAINT FK_F299315A63D8C20E FOREIGN KEY (deletedBy_id) REFERENCES fos_user (id) ON DELETE SET NULL');
        $this->addSql('ALTER TABLE sylius_customer DROP code');
    }
}
