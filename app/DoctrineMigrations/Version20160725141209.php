<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20160725141209 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE jdj_topic_follower (topic_id INT NOT NULL, customerinterface_id INT NOT NULL, INDEX IDX_1B075FD01F55203D (topic_id), INDEX IDX_1B075FD0F6FD7074 (customerinterface_id), PRIMARY KEY(topic_id, customerinterface_id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE jdj_topic_follower ADD CONSTRAINT FK_1B075FD01F55203D FOREIGN KEY (topic_id) REFERENCES jdj_topic (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE jdj_topic_follower ADD CONSTRAINT FK_1B075FD0F6FD7074 FOREIGN KEY (customerinterface_id) REFERENCES sylius_customer (id) ON DELETE CASCADE');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE jdj_topic_follower');
    }
}
