<?php declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190424133301 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE jdj_notification ADD product_file_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE jdj_notification ADD CONSTRAINT FK_5D9A5B2D421262DC FOREIGN KEY (product_file_id) REFERENCES jdj_product_file (id)');
        $this->addSql('CREATE INDEX IDX_5D9A5B2D421262DC ON jdj_notification (product_file_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE jdj_notification DROP FOREIGN KEY FK_5D9A5B2D421262DC');
        $this->addSql('DROP INDEX IDX_5D9A5B2D421262DC ON jdj_notification');
        $this->addSql('ALTER TABLE jdj_notification DROP product_file_id');
    }
}
