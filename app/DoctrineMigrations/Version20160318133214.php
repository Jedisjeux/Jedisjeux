<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20160318133214 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE jdj_game_play (id INT AUTO_INCREMENT NOT NULL, topic_id INT DEFAULT NULL, product_id INT NOT NULL, author_id INT NOT NULL, createdAt DATETIME DEFAULT NULL, updatedAt DATETIME DEFAULT NULL, INDEX IDX_B97D18CC1F55203D (topic_id), INDEX IDX_B97D18CC4584665A (product_id), INDEX IDX_B97D18CCF675F31B (author_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE jdj_game_play ADD CONSTRAINT FK_B97D18CC1F55203D FOREIGN KEY (topic_id) REFERENCES jdj_topic (id)');
        $this->addSql('ALTER TABLE jdj_game_play ADD CONSTRAINT FK_B97D18CC4584665A FOREIGN KEY (product_id) REFERENCES sylius_product (id)');
        $this->addSql('ALTER TABLE jdj_game_play ADD CONSTRAINT FK_B97D18CCF675F31B FOREIGN KEY (author_id) REFERENCES sylius_customer (id)');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE jdj_game_play');
    }
}
