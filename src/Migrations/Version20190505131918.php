<?php declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190505131918 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE sylius_product_translation ADD box_content LONGTEXT DEFAULT NULL');
        $this->addSql('UPDATE sylius_product product INNER JOIN sylius_product_translation translation on translation.translatable_id = product.id SET translation.box_content = product.box_content WHERE translation.locale = \'fr_FR\'');
        $this->addSql('ALTER TABLE sylius_product DROP box_content');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE sylius_product ADD box_content LONGTEXT DEFAULT NULL COLLATE utf8_unicode_ci');
        $this->addSql('UPDATE sylius_product product INNER JOIN sylius_product_translation translation on translation.translatable_id = product.id SET product.box_content = translation.box_content WHERE translation.locale = \'fr_FR\'');
        $this->addSql('ALTER TABLE sylius_product_translation DROP box_content');
    }
}
