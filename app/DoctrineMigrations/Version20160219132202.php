<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20160219132202 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE jdj_post (id INT AUTO_INCREMENT NOT NULL, topic_id INT DEFAULT NULL, body LONGTEXT NOT NULL, createdAt DATETIME DEFAULT NULL, updatedAt DATETIME DEFAULT NULL, createdBy_id INT DEFAULT NULL, updatedBy_id INT DEFAULT NULL, deletedBy_id INT DEFAULT NULL, INDEX IDX_3312CC5B1F55203D (topic_id), INDEX IDX_3312CC5B3174800F (createdBy_id), INDEX IDX_3312CC5B65FF1AEC (updatedBy_id), INDEX IDX_3312CC5B63D8C20E (deletedBy_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE jdj_topic (id INT AUTO_INCREMENT NOT NULL, title VARCHAR(255) NOT NULL, createdAt DATETIME DEFAULT NULL, updatedAt DATETIME DEFAULT NULL, createdBy_id INT DEFAULT NULL, updatedBy_id INT DEFAULT NULL, deletedBy_id INT DEFAULT NULL, INDEX IDX_F299315A3174800F (createdBy_id), INDEX IDX_F299315A65FF1AEC (updatedBy_id), INDEX IDX_F299315A63D8C20E (deletedBy_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE jdj_post ADD CONSTRAINT FK_3312CC5B1F55203D FOREIGN KEY (topic_id) REFERENCES jdj_topic (id)');
        $this->addSql('ALTER TABLE jdj_post ADD CONSTRAINT FK_3312CC5B3174800F FOREIGN KEY (createdBy_id) REFERENCES fos_user (id) ON DELETE SET NULL');
        $this->addSql('ALTER TABLE jdj_post ADD CONSTRAINT FK_3312CC5B65FF1AEC FOREIGN KEY (updatedBy_id) REFERENCES fos_user (id) ON DELETE SET NULL');
        $this->addSql('ALTER TABLE jdj_post ADD CONSTRAINT FK_3312CC5B63D8C20E FOREIGN KEY (deletedBy_id) REFERENCES fos_user (id) ON DELETE SET NULL');
        $this->addSql('ALTER TABLE jdj_topic ADD CONSTRAINT FK_F299315A3174800F FOREIGN KEY (createdBy_id) REFERENCES fos_user (id) ON DELETE SET NULL');
        $this->addSql('ALTER TABLE jdj_topic ADD CONSTRAINT FK_F299315A65FF1AEC FOREIGN KEY (updatedBy_id) REFERENCES fos_user (id) ON DELETE SET NULL');
        $this->addSql('ALTER TABLE jdj_topic ADD CONSTRAINT FK_F299315A63D8C20E FOREIGN KEY (deletedBy_id) REFERENCES fos_user (id) ON DELETE SET NULL');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE jdj_post DROP FOREIGN KEY FK_3312CC5B1F55203D');
        $this->addSql('DROP TABLE jdj_post');
        $this->addSql('DROP TABLE jdj_topic');
    }
}
