<?php declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20181207155828 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE jdj_product_image ADD product_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE jdj_product_image ADD CONSTRAINT FK_5AEDC6F54584665A FOREIGN KEY (product_id) REFERENCES sylius_product (id)');
        $this->addSql('CREATE INDEX IDX_5AEDC6F54584665A ON jdj_product_image (product_id)');
        $this->addSql('ALTER TABLE jdj_product_image RENAME INDEX idx_c6b77d5d3b69a9af TO IDX_5AEDC6F53B69A9AF');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE jdj_product_image DROP FOREIGN KEY FK_5AEDC6F54584665A');
        $this->addSql('DROP INDEX IDX_5AEDC6F54584665A ON jdj_product_image');
        $this->addSql('ALTER TABLE jdj_product_image DROP product_id');
        $this->addSql('ALTER TABLE jdj_product_image RENAME INDEX idx_5aedc6f53b69a9af TO IDX_C6B77D5D3B69A9AF');
    }
}
