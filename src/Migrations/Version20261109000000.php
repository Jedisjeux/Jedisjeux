<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20261109000000 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Drop the redirection and not found tables';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('DROP TABLE jdj_redirect');
        $this->addSql('DROP TABLE jdj_not_found');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('CREATE TABLE jdj_redirect (id INT AUTO_INCREMENT NOT NULL, source VARCHAR(255) NOT NULL, destination VARCHAR(255) NOT NULL, permanent TINYINT(1) NOT NULL, count INT NOT NULL, last_accessed DATETIME DEFAULT NULL, UNIQUE INDEX UNIQ_94BDFCE05F8A7F73 (source), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE jdj_not_found (id INT AUTO_INCREMENT NOT NULL, path VARCHAR(255) NOT NULL, full_url VARCHAR(255) NOT NULL, referer VARCHAR(255) DEFAULT NULL, timestamp DATETIME NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
    }
}
