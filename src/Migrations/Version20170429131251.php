<?php

/*
 * This file is part of the Jedisjeux project.
 *
 * (c) Jedisjeux
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20170429131251 extends AbstractMigration
{
    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf('mysql' !== $this->connection->getDatabasePlatform()->getName(), 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE jdj_product_box (id INT AUTO_INCREMENT NOT NULL, image_id INT DEFAULT NULL, height NUMERIC(10, 0) NOT NULL, UNIQUE INDEX UNIQ_9ED1EFEC3DA5256D (image_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE jdj_product_box_image (id INT AUTO_INCREMENT NOT NULL, path VARCHAR(255) DEFAULT NULL, created_at DATETIME DEFAULT NULL, updated_at DATETIME DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE jdj_product_box ADD CONSTRAINT FK_9ED1EFEC3DA5256D FOREIGN KEY (image_id) REFERENCES jdj_product_box_image (id)');
        $this->addSql('ALTER TABLE sylius_product_variant ADD box_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE sylius_product_variant ADD CONSTRAINT FK_A29B523D8177B3F FOREIGN KEY (box_id) REFERENCES jdj_product_box (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_A29B523D8177B3F ON sylius_product_variant (box_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf('mysql' !== $this->connection->getDatabasePlatform()->getName(), 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE sylius_product_variant DROP FOREIGN KEY FK_A29B523D8177B3F');
        $this->addSql('ALTER TABLE jdj_product_box DROP FOREIGN KEY FK_9ED1EFEC3DA5256D');
        $this->addSql('DROP TABLE jdj_product_box');
        $this->addSql('DROP TABLE jdj_product_box_image');
        $this->addSql('DROP INDEX UNIQ_A29B523D8177B3F ON sylius_product_variant');
        $this->addSql('ALTER TABLE sylius_product_variant DROP box_id');
    }
}
