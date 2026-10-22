<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20261022000000 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Replace the json_array type removed from DBAL 3 by json and apply Sylius 1.10 schema changes';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('ALTER TABLE sylius_product_attribute_value CHANGE json_value json_value LONGTEXT DEFAULT NULL COMMENT \'(DC2Type:json)\'');
        $this->addSql('ALTER TABLE sylius_user CHANGE password password VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE sylius_product_attribute ADD translatable TINYINT(1) DEFAULT 1 NOT NULL');
        $this->addSql('ALTER TABLE sylius_product_attribute_value CHANGE locale_code locale_code VARCHAR(255) DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE sylius_product_attribute_value CHANGE locale_code locale_code VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE sylius_product_attribute DROP translatable');
        $this->addSql('ALTER TABLE sylius_user CHANGE password password VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE sylius_product_attribute_value CHANGE json_value json_value LONGTEXT DEFAULT NULL COMMENT \'(DC2Type:json_array)\'');
    }
}
