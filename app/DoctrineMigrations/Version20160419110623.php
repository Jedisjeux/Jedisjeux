<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20160419110623 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE Vote DROP FOREIGN KEY FK_FA222A5AF8697D13');
        $this->addSql('ALTER TABLE jdj_like DROP FOREIGN KEY FK_C5FBE065F8697D13');
        $this->addSql('ALTER TABLE Comment DROP FOREIGN KEY FK_5BC96BF0E2904019');
        $this->addSql('ALTER TABLE jdj_like DROP FOREIGN KEY FK_C5FBE065395BAE7E');
        $this->addSql('DROP TABLE Comment');
        $this->addSql('DROP TABLE Thread');
        $this->addSql('DROP TABLE Vote');
        $this->addSql('DROP TABLE jdj_game_review');
        $this->addSql('DROP TABLE jdj_like');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE Comment (id INT AUTO_INCREMENT NOT NULL, thread_id VARCHAR(255) DEFAULT NULL COLLATE utf8_unicode_ci, author_id INT DEFAULT NULL, body LONGTEXT NOT NULL COLLATE utf8_unicode_ci, ancestors VARCHAR(1024) NOT NULL COLLATE utf8_unicode_ci, depth INT NOT NULL, created_at DATETIME NOT NULL, state INT NOT NULL, INDEX IDX_5BC96BF0E2904019 (thread_id), INDEX IDX_5BC96BF0F675F31B (author_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE Thread (id VARCHAR(255) NOT NULL COLLATE utf8_unicode_ci, permalink VARCHAR(255) NOT NULL COLLATE utf8_unicode_ci, is_commentable TINYINT(1) NOT NULL, num_comments INT NOT NULL, last_comment_at DATETIME DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE Vote (id INT AUTO_INCREMENT NOT NULL, comment_id INT DEFAULT NULL, created_at DATETIME NOT NULL, value INT NOT NULL, INDEX IDX_FA222A5AF8697D13 (comment_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE jdj_game_review (id INT AUTO_INCREMENT NOT NULL, rate_id INT DEFAULT NULL, title VARCHAR(255) NOT NULL COLLATE utf8_unicode_ci, body LONGTEXT NOT NULL COLLATE utf8_unicode_ci, createdAt DATETIME NOT NULL, updatedAt DATETIME NOT NULL, UNIQUE INDEX UNIQ_7221DFC7BC999F9F (rate_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE jdj_like (id INT AUTO_INCREMENT NOT NULL, comment_id INT DEFAULT NULL, is_like TINYINT(1) NOT NULL, createdAt DATETIME NOT NULL, createdBy_id INT NOT NULL, gameReview_id INT DEFAULT NULL, UNIQUE INDEX unique_like (createdBy_id, gameReview_id), INDEX IDX_C5FBE0653174800F (createdBy_id), INDEX IDX_C5FBE065F8697D13 (comment_id), INDEX IDX_C5FBE065395BAE7E (gameReview_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE Comment ADD CONSTRAINT FK_5BC96BF0E2904019 FOREIGN KEY (thread_id) REFERENCES Thread (id)');
        $this->addSql('ALTER TABLE Comment ADD CONSTRAINT FK_5BC96BF0F675F31B FOREIGN KEY (author_id) REFERENCES fos_user (id)');
        $this->addSql('ALTER TABLE Vote ADD CONSTRAINT FK_FA222A5AF8697D13 FOREIGN KEY (comment_id) REFERENCES Comment (id)');
        $this->addSql('ALTER TABLE jdj_game_review ADD CONSTRAINT FK_7221DFC7BC999F9F FOREIGN KEY (rate_id) REFERENCES jdj_game_rate (id)');
        $this->addSql('ALTER TABLE jdj_like ADD CONSTRAINT FK_C5FBE0653174800F FOREIGN KEY (createdBy_id) REFERENCES fos_user (id)');
        $this->addSql('ALTER TABLE jdj_like ADD CONSTRAINT FK_C5FBE065395BAE7E FOREIGN KEY (gameReview_id) REFERENCES jdj_game_review (id)');
        $this->addSql('ALTER TABLE jdj_like ADD CONSTRAINT FK_C5FBE065F8697D13 FOREIGN KEY (comment_id) REFERENCES Comment (id)');
    }
}
