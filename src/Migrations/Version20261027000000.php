<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20261027000000 extends AbstractMigration
{
    private const SERIALIZED_COLUMNS = [
        'sylius_user' => 'roles',
        'sylius_product_attribute' => 'configuration',
    ];

    public function getDescription(): string
    {
        return 'Replace the array type by json and apply Sylius 1.14 schema changes';
    }

    public function up(Schema $schema): void
    {
        // Data must be converted before the column type changes, as MariaDB checks JSON validity
        foreach (self::SERIALIZED_COLUMNS as $table => $column) {
            $this->convertColumn($table, $column, static fn (string $value): string => json_encode(unserialize($value), \JSON_THROW_ON_ERROR));
        }

        $this->addSql('ALTER TABLE sylius_user CHANGE roles roles JSON NOT NULL COMMENT \'(DC2Type:json)\'');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_569A33C06B7BA4B6 ON sylius_user (password_reset_token)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_569A33C0C4995C67 ON sylius_user (email_verification_token)');
        $this->addSql('ALTER TABLE sylius_product_attribute CHANGE configuration configuration JSON NOT NULL COMMENT \'(DC2Type:json)\'');
        $this->addSql('ALTER TABLE sylius_user_oauth CHANGE access_token access_token TEXT DEFAULT NULL, CHANGE refresh_token refresh_token TEXT DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE sylius_user_oauth CHANGE access_token access_token VARCHAR(255) DEFAULT NULL, CHANGE refresh_token refresh_token VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE sylius_product_attribute CHANGE configuration configuration LONGTEXT NOT NULL COMMENT \'(DC2Type:array)\'');
        $this->addSql('DROP INDEX UNIQ_569A33C0C4995C67 ON sylius_user');
        $this->addSql('DROP INDEX UNIQ_569A33C06B7BA4B6 ON sylius_user');
        $this->addSql('ALTER TABLE sylius_user CHANGE roles roles LONGTEXT NOT NULL COMMENT \'(DC2Type:array)\'');
    }

    public function postDown(Schema $schema): void
    {
        foreach (self::SERIALIZED_COLUMNS as $table => $column) {
            $this->convertColumn($table, $column, static fn (string $value): string => serialize(json_decode($value, true, 512, \JSON_THROW_ON_ERROR)));
        }
    }

    private function convertColumn(string $table, string $column, callable $convert): void
    {
        foreach ($this->connection->fetchAllAssociative(sprintf('SELECT id, %s AS value FROM %s', $column, $table)) as $row) {
            $this->connection->executeStatement(
                sprintf('UPDATE %s SET %s = :value WHERE id = :id', $table, $column),
                ['value' => $convert($row['value']), 'id' => $row['id']],
            );
        }
    }
}
