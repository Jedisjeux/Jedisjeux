<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20160719010209 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE jdj_article_review (id INT AUTO_INCREMENT NOT NULL, author_id INT DEFAULT NULL, title VARCHAR(255) NOT NULL, rating INT NOT NULL, comment LONGTEXT DEFAULT NULL, status VARCHAR(255) NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME DEFAULT NULL, reviewSubject_id INT DEFAULT NULL, INDEX IDX_4764CDD6970EE54B (reviewSubject_id), INDEX IDX_4764CDD6F675F31B (author_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE jdj_article_review ADD CONSTRAINT FK_4764CDD6970EE54B FOREIGN KEY (reviewSubject_id) REFERENCES jdj_article (id)');
        $this->addSql('ALTER TABLE jdj_article_review ADD CONSTRAINT FK_4764CDD6F675F31B FOREIGN KEY (author_id) REFERENCES sylius_customer (id)');
        $this->addSql('DROP TABLE app_article_review');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE app_article_review (id INT AUTO_INCREMENT NOT NULL, author_id INT DEFAULT NULL, title VARCHAR(255) NOT NULL COLLATE utf8_unicode_ci, rating INT NOT NULL, comment LONGTEXT DEFAULT NULL COLLATE utf8_unicode_ci, status VARCHAR(255) NOT NULL COLLATE utf8_unicode_ci, created_at DATETIME NOT NULL, updated_at DATETIME DEFAULT NULL, reviewSubject_id INT DEFAULT NULL, INDEX IDX_CC7E84B8970EE54B (reviewSubject_id), INDEX IDX_CC7E84B8F675F31B (author_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE app_article_review ADD CONSTRAINT FK_CC7E84B8970EE54B FOREIGN KEY (reviewSubject_id) REFERENCES jdj_article (id)');
        $this->addSql('ALTER TABLE app_article_review ADD CONSTRAINT FK_CC7E84B8F675F31B FOREIGN KEY (author_id) REFERENCES sylius_customer (id)');
        $this->addSql('DROP TABLE jdj_article_review');
    }
}
