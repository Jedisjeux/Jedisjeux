<?php declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190428115047 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE jdj_product_subscription (id INT AUTO_INCREMENT NOT NULL, subject_id INT DEFAULT NULL, subscriber_id INT DEFAULT NULL, options LONGTEXT NOT NULL COMMENT \'(DC2Type:array)\', INDEX IDX_70EBD9E723EDC87 (subject_id), INDEX IDX_70EBD9E77808B1AD (subscriber_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE jdj_product_subscription ADD CONSTRAINT FK_70EBD9E723EDC87 FOREIGN KEY (subject_id) REFERENCES sylius_product (id)');
        $this->addSql('ALTER TABLE jdj_product_subscription ADD CONSTRAINT FK_70EBD9E77808B1AD FOREIGN KEY (subscriber_id) REFERENCES sylius_customer (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE jdj_product_subscription');
    }
}
