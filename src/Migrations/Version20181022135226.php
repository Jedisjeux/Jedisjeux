<?php declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20181022135226 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE jdj_product_box ADD product_id INT DEFAULT NULL');
        $this->addSql('UPDATE jdj_product_box box INNER JOIN sylius_product_variant v ON box.id = v.box_id SET box.product_id = v.product_id');
        $this->addSql('ALTER TABLE jdj_product_box ADD CONSTRAINT FK_9ED1EFEC4584665A FOREIGN KEY (product_id) REFERENCES sylius_product (id)');
        $this->addSql('CREATE INDEX IDX_9ED1EFEC4584665A ON jdj_product_box (product_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE jdj_product_box DROP FOREIGN KEY FK_9ED1EFEC4584665A');
        $this->addSql('DROP INDEX IDX_9ED1EFEC4584665A ON jdj_product_box');
        $this->addSql('ALTER TABLE jdj_product_box DROP product_id');
    }
}
