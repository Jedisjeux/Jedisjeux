<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20170303013233 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE jdj_block (id INT AUTO_INCREMENT NOT NULL, image_id INT DEFAULT NULL, article_id INT DEFAULT NULL, title VARCHAR(255) DEFAULT NULL, body VARCHAR(255) NOT NULL, position INT NOT NULL, UNIQUE INDEX UNIQ_ECC278633DA5256D (image_id), INDEX IDX_ECC278637294869C (article_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE jdj_block_image (id INT AUTO_INCREMENT NOT NULL, path VARCHAR(255) DEFAULT NULL, createdAt DATETIME DEFAULT NULL, updatedAt DATETIME DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE jdj_block ADD CONSTRAINT FK_ECC278633DA5256D FOREIGN KEY (image_id) REFERENCES jdj_block_image (id)');
        $this->addSql('ALTER TABLE jdj_block ADD CONSTRAINT FK_ECC278637294869C FOREIGN KEY (article_id) REFERENCES jdj_article (id)');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE jdj_block DROP FOREIGN KEY FK_ECC278633DA5256D');
        $this->addSql('DROP TABLE jdj_block');
        $this->addSql('DROP TABLE jdj_block_image');
    }
}
