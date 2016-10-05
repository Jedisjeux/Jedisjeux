<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20161005223359 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE jdj_post DROP FOREIGN KEY FK_3312CC5B3174800F');
        $this->addSql('ALTER TABLE jdj_post DROP FOREIGN KEY FK_3312CC5B63D8C20E');
        $this->addSql('ALTER TABLE jdj_post DROP FOREIGN KEY FK_3312CC5B65FF1AEC');
        $this->addSql('DROP INDEX IDX_3312CC5B3174800F ON jdj_post');
        $this->addSql('DROP INDEX IDX_3312CC5B65FF1AEC ON jdj_post');
        $this->addSql('DROP INDEX IDX_3312CC5B63D8C20E ON jdj_post');
        $this->addSql('ALTER TABLE jdj_post DROP createdBy_id, DROP updatedBy_id, DROP deletedBy_id');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE jdj_post ADD createdBy_id INT DEFAULT NULL, ADD updatedBy_id INT DEFAULT NULL, ADD deletedBy_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE jdj_post ADD CONSTRAINT FK_3312CC5B3174800F FOREIGN KEY (createdBy_id) REFERENCES sylius_user (id) ON DELETE SET NULL');
        $this->addSql('ALTER TABLE jdj_post ADD CONSTRAINT FK_3312CC5B63D8C20E FOREIGN KEY (deletedBy_id) REFERENCES sylius_user (id) ON DELETE SET NULL');
        $this->addSql('ALTER TABLE jdj_post ADD CONSTRAINT FK_3312CC5B65FF1AEC FOREIGN KEY (updatedBy_id) REFERENCES sylius_user (id) ON DELETE SET NULL');
        $this->addSql('CREATE INDEX IDX_3312CC5B3174800F ON jdj_post (createdBy_id)');
        $this->addSql('CREATE INDEX IDX_3312CC5B65FF1AEC ON jdj_post (updatedBy_id)');
        $this->addSql('CREATE INDEX IDX_3312CC5B63D8C20E ON jdj_post (deletedBy_id)');
    }
}
