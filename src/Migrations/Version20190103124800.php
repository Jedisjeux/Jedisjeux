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
final class Version20190103124800 extends AbstractMigration
{
    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf('mysql' !== $this->connection->getDatabasePlatform()->getName(), 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE jdj_game_award (id INT AUTO_INCREMENT NOT NULL, image_id INT DEFAULT NULL, name VARCHAR(255) NOT NULL, slug VARCHAR(255) DEFAULT NULL, description LONGTEXT DEFAULT NULL, UNIQUE INDEX UNIQ_33DA0E28989D9B62 (slug), UNIQUE INDEX UNIQ_33DA0E283DA5256D (image_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE jdj_game_award_image (id INT AUTO_INCREMENT NOT NULL, path VARCHAR(255) DEFAULT NULL, created_at DATETIME DEFAULT NULL, updated_at DATETIME DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE jdj_year_award (id INT AUTO_INCREMENT NOT NULL, award_id INT DEFAULT NULL, product_id INT DEFAULT NULL, year VARCHAR(255) NOT NULL, INDEX IDX_A28759703D5282CF (award_id), INDEX IDX_A28759704584665A (product_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE jdj_game_award ADD CONSTRAINT FK_33DA0E283DA5256D FOREIGN KEY (image_id) REFERENCES jdj_game_award_image (id)');
        $this->addSql('ALTER TABLE jdj_year_award ADD CONSTRAINT FK_A28759703D5282CF FOREIGN KEY (award_id) REFERENCES jdj_game_award (id)');
        $this->addSql('ALTER TABLE jdj_year_award ADD CONSTRAINT FK_A28759704584665A FOREIGN KEY (product_id) REFERENCES sylius_product (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf('mysql' !== $this->connection->getDatabasePlatform()->getName(), 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE jdj_year_award DROP FOREIGN KEY FK_A28759703D5282CF');
        $this->addSql('ALTER TABLE jdj_game_award DROP FOREIGN KEY FK_33DA0E283DA5256D');
        $this->addSql('DROP TABLE jdj_game_award');
        $this->addSql('DROP TABLE jdj_game_award_image');
        $this->addSql('DROP TABLE jdj_year_award');
    }
}
