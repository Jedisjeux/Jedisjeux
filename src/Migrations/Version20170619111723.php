<?php

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20170619111723 extends AbstractMigration
{
    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf('mysql' !== $this->connection->getDatabasePlatform()->getName(), 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE jdj_article_review DROP FOREIGN KEY FK_4764CDD675AE1D8A');
        $this->addSql('ALTER TABLE jdj_article_review DROP FOREIGN KEY FK_4764CDD6F675F31B');
        $this->addSql('DROP INDEX IDX_4764CDD675AE1D8A ON jdj_article_review');
        $this->addSql('ALTER TABLE jdj_article_review CHANGE review_subject_id article_id INT NOT NULL, CHANGE author_id author_id INT NOT NULL');
        $this->addSql('ALTER TABLE jdj_article_review ADD CONSTRAINT FK_4764CDD67294869C FOREIGN KEY (article_id) REFERENCES jdj_article (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE jdj_article_review ADD CONSTRAINT FK_4764CDD6F675F31B FOREIGN KEY (author_id) REFERENCES sylius_customer (id) ON DELETE CASCADE');
        $this->addSql('CREATE INDEX IDX_4764CDD67294869C ON jdj_article_review (article_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf('mysql' !== $this->connection->getDatabasePlatform()->getName(), 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE jdj_article_review DROP FOREIGN KEY FK_4764CDD67294869C');
        $this->addSql('ALTER TABLE jdj_article_review DROP FOREIGN KEY FK_4764CDD6F675F31B');
        $this->addSql('DROP INDEX IDX_4764CDD67294869C ON jdj_article_review');
        $this->addSql('ALTER TABLE jdj_article_review CHANGE article_id review_subject_id INT DEFAULT NULL, CHANGE author_id author_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE jdj_article_review ADD CONSTRAINT FK_4764CDD675AE1D8A FOREIGN KEY (review_subject_id) REFERENCES jdj_article (id)');
        $this->addSql('ALTER TABLE jdj_article_review ADD CONSTRAINT FK_4764CDD6F675F31B FOREIGN KEY (author_id) REFERENCES sylius_customer (id)');
        $this->addSql('CREATE INDEX IDX_4764CDD675AE1D8A ON jdj_article_review (review_subject_id)');
    }
}
