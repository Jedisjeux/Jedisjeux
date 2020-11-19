<?php

/*
 * This file is part of the Jedisjeux project.
 *
 * (c) Jedisjeux
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20181029134345 extends AbstractMigration
{
    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf('mysql' !== $this->connection->getDatabasePlatform()->getName(), 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE jdj_product_video_image (id INT AUTO_INCREMENT NOT NULL, path VARCHAR(255) DEFAULT NULL, created_at DATETIME DEFAULT NULL, updated_at DATETIME DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE jdj_product_video ADD image_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE jdj_product_video ADD CONSTRAINT FK_E31718863DA5256D FOREIGN KEY (image_id) REFERENCES jdj_product_video_image (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_E31718863DA5256D ON jdj_product_video (image_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf('mysql' !== $this->connection->getDatabasePlatform()->getName(), 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE jdj_product_video DROP FOREIGN KEY FK_E31718863DA5256D');
        $this->addSql('DROP TABLE jdj_product_video_image');
        $this->addSql('DROP INDEX UNIQ_E31718863DA5256D ON jdj_product_video');
        $this->addSql('ALTER TABLE jdj_product_video DROP image_id');
    }
}
