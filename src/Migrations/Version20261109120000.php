<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20261109120000 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Link a game to the article blocks';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('ALTER TABLE jdj_block ADD product_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE jdj_block ADD CONSTRAINT FK_ECC278634584665A FOREIGN KEY (product_id) REFERENCES sylius_product (id) ON DELETE SET NULL');
        $this->addSql('CREATE INDEX IDX_ECC278634584665A ON jdj_block (product_id)');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE jdj_block DROP FOREIGN KEY FK_ECC278634584665A');
        $this->addSql('DROP INDEX IDX_ECC278634584665A ON jdj_block');
        $this->addSql('ALTER TABLE jdj_block DROP product_id');
    }
}
