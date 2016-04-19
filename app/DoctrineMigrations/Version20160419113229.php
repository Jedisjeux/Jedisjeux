<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20160419113229 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE jdj_game_rate DROP FOREIGN KEY FK_3818F94F3174800F');
        $this->addSql('ALTER TABLE jdj_game_rate DROP FOREIGN KEY FK_3818F94F65FF1AEC');
        $this->addSql('DROP TABLE fos_user');
        $this->addSql('DROP TABLE jdj_avatar');
        $this->addSql('DROP TABLE jdj_game_rate');
        $this->addSql('DROP TABLE jdj_image');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE fos_user (id INT AUTO_INCREMENT NOT NULL, username VARCHAR(255) NOT NULL COLLATE utf8_unicode_ci, username_canonical VARCHAR(255) NOT NULL COLLATE utf8_unicode_ci, email VARCHAR(255) NOT NULL COLLATE utf8_unicode_ci, email_canonical VARCHAR(255) NOT NULL COLLATE utf8_unicode_ci, enabled TINYINT(1) NOT NULL, salt VARCHAR(255) NOT NULL COLLATE utf8_unicode_ci, password VARCHAR(255) NOT NULL COLLATE utf8_unicode_ci, last_login DATETIME DEFAULT NULL, locked TINYINT(1) NOT NULL, expired TINYINT(1) NOT NULL, expires_at DATETIME DEFAULT NULL, confirmation_token VARCHAR(255) DEFAULT NULL COLLATE utf8_unicode_ci, password_requested_at DATETIME DEFAULT NULL, roles LONGTEXT NOT NULL COLLATE utf8_unicode_ci COMMENT \'(DC2Type:array)\', credentials_expired TINYINT(1) NOT NULL, credentials_expire_at DATETIME DEFAULT NULL, UNIQUE INDEX UNIQ_957A647992FC23A8 (username_canonical), UNIQUE INDEX UNIQ_957A6479A0D96FBF (email_canonical), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE jdj_avatar (id INT AUTO_INCREMENT NOT NULL, path VARCHAR(255) DEFAULT NULL COLLATE utf8_unicode_ci, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE jdj_game_rate (id INT AUTO_INCREMENT NOT NULL, game_id INT DEFAULT NULL, value DOUBLE PRECISION NOT NULL, createdBy_id INT NOT NULL, updatedBy_id INT DEFAULT NULL, createdAt DATETIME NOT NULL, updatedAt DATETIME NOT NULL, INDEX IDX_3818F94FE48FD905 (game_id), INDEX IDX_3818F94F3174800F (createdBy_id), INDEX IDX_3818F94F65FF1AEC (updatedBy_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE jdj_image (id INT AUTO_INCREMENT NOT NULL, path VARCHAR(255) DEFAULT NULL COLLATE utf8_unicode_ci, description VARCHAR(255) DEFAULT NULL COLLATE utf8_unicode_ci, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE jdj_game_rate ADD CONSTRAINT FK_3818F94F3174800F FOREIGN KEY (createdBy_id) REFERENCES fos_user (id)');
        $this->addSql('ALTER TABLE jdj_game_rate ADD CONSTRAINT FK_3818F94F65FF1AEC FOREIGN KEY (updatedBy_id) REFERENCES fos_user (id)');
        $this->addSql('ALTER TABLE jdj_game_rate ADD CONSTRAINT FK_3818F94FE48FD905 FOREIGN KEY (game_id) REFERENCES sylius_product (id)');
    }
}
