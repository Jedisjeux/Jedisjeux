<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20160218130519 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE jdj_post (id INT AUTO_INCREMENT NOT NULL, topic_id INT DEFAULT NULL, body LONGTEXT NOT NULL, createdBy_id INT DEFAULT NULL, updatedBy_id INT DEFAULT NULL, deletedBy_id INT DEFAULT NULL, INDEX IDX_3312CC5B1F55203D (topic_id), INDEX IDX_3312CC5B3174800F (createdBy_id), INDEX IDX_3312CC5B65FF1AEC (updatedBy_id), INDEX IDX_3312CC5B63D8C20E (deletedBy_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE jdj_post ADD CONSTRAINT FK_3312CC5B1F55203D FOREIGN KEY (topic_id) REFERENCES Topic (id)');
        $this->addSql('ALTER TABLE jdj_post ADD CONSTRAINT FK_3312CC5B3174800F FOREIGN KEY (createdBy_id) REFERENCES fos_user (id) ON DELETE SET NULL');
        $this->addSql('ALTER TABLE jdj_post ADD CONSTRAINT FK_3312CC5B65FF1AEC FOREIGN KEY (updatedBy_id) REFERENCES fos_user (id) ON DELETE SET NULL');
        $this->addSql('ALTER TABLE jdj_post ADD CONSTRAINT FK_3312CC5B63D8C20E FOREIGN KEY (deletedBy_id) REFERENCES fos_user (id) ON DELETE SET NULL');
        $this->addSql('DROP TABLE Post');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE Post (id INT AUTO_INCREMENT NOT NULL, body LONGTEXT NOT NULL COLLATE utf8_unicode_ci, createdBy_id INT DEFAULT NULL, updatedBy_id INT DEFAULT NULL, deletedBy_id INT DEFAULT NULL, INDEX IDX_FAB8C3B33174800F (createdBy_id), INDEX IDX_FAB8C3B365FF1AEC (updatedBy_id), INDEX IDX_FAB8C3B363D8C20E (deletedBy_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE Post ADD CONSTRAINT FK_FAB8C3B33174800F FOREIGN KEY (createdBy_id) REFERENCES fos_user (id) ON DELETE SET NULL');
        $this->addSql('ALTER TABLE Post ADD CONSTRAINT FK_FAB8C3B363D8C20E FOREIGN KEY (deletedBy_id) REFERENCES fos_user (id) ON DELETE SET NULL');
        $this->addSql('ALTER TABLE Post ADD CONSTRAINT FK_FAB8C3B365FF1AEC FOREIGN KEY (updatedBy_id) REFERENCES fos_user (id) ON DELETE SET NULL');
        $this->addSql('DROP TABLE jdj_post');
    }
}
