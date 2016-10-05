<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20161005233506 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE jdj_topic DROP FOREIGN KEY FK_F299315A3174800F');
        $this->addSql('ALTER TABLE jdj_topic DROP FOREIGN KEY FK_F299315A63D8C20E');
        $this->addSql('ALTER TABLE jdj_topic DROP FOREIGN KEY FK_F299315A65FF1AEC');
        $this->addSql('DROP INDEX IDX_F299315A3174800F ON jdj_topic');
        $this->addSql('DROP INDEX IDX_F299315A65FF1AEC ON jdj_topic');
        $this->addSql('DROP INDEX IDX_F299315A63D8C20E ON jdj_topic');
        $this->addSql('ALTER TABLE jdj_topic ADD author_id INT DEFAULT NULL, DROP createdBy_id, DROP updatedBy_id, DROP deletedBy_id');
        $this->addSql('ALTER TABLE jdj_topic ADD CONSTRAINT FK_F299315AF675F31B FOREIGN KEY (author_id) REFERENCES sylius_customer (id)');
        $this->addSql('CREATE INDEX IDX_F299315AF675F31B ON jdj_topic (author_id)');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE jdj_topic DROP FOREIGN KEY FK_F299315AF675F31B');
        $this->addSql('DROP INDEX IDX_F299315AF675F31B ON jdj_topic');
        $this->addSql('ALTER TABLE jdj_topic ADD updatedBy_id INT DEFAULT NULL, ADD deletedBy_id INT DEFAULT NULL, CHANGE author_id createdBy_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE jdj_topic ADD CONSTRAINT FK_F299315A3174800F FOREIGN KEY (createdBy_id) REFERENCES sylius_user (id) ON DELETE SET NULL');
        $this->addSql('ALTER TABLE jdj_topic ADD CONSTRAINT FK_F299315A63D8C20E FOREIGN KEY (deletedBy_id) REFERENCES sylius_user (id) ON DELETE SET NULL');
        $this->addSql('ALTER TABLE jdj_topic ADD CONSTRAINT FK_F299315A65FF1AEC FOREIGN KEY (updatedBy_id) REFERENCES sylius_user (id) ON DELETE SET NULL');
        $this->addSql('CREATE INDEX IDX_F299315A3174800F ON jdj_topic (createdBy_id)');
        $this->addSql('CREATE INDEX IDX_F299315A65FF1AEC ON jdj_topic (updatedBy_id)');
        $this->addSql('CREATE INDEX IDX_F299315A63D8C20E ON jdj_topic (deletedBy_id)');
    }
}
