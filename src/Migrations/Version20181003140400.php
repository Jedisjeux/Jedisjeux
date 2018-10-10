<?php declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20181003140400 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE sylius_product_review CHANGE title title VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE jdj_article_review CHANGE title title VARCHAR(255) DEFAULT NULL');
        $this->addSql("UPDATE sylius_product_review SET title = null where title=''");
        $this->addSql("UPDATE jdj_article_review SET title = null");
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql("UPDATE sylius_product_review SET title = '' where title is null");
        $this->addSql("UPDATE jdj_article_review SET title = ''");
        $this->addSql('ALTER TABLE jdj_article_review CHANGE title title VARCHAR(255) NOT NULL COLLATE utf8_unicode_ci');
        $this->addSql('ALTER TABLE sylius_product_review CHANGE title title VARCHAR(255) NOT NULL COLLATE utf8_unicode_ci');

    }
}
