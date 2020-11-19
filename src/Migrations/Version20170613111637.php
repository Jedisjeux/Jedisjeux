<?php

/*
 * This file is part of the Jedisjeux project.
 *
 * (c) Jedisjeux
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20170613111637 extends AbstractMigration
{
    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf('mysql' !== $this->connection->getDatabasePlatform()->getName(), 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE jdj_notification_author (notification_id INT NOT NULL, author_id INT NOT NULL, INDEX IDX_2E6E77EDEF1A9D84 (notification_id), INDEX IDX_2E6E77EDF675F31B (author_id), PRIMARY KEY(notification_id, author_id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE jdj_notification_author ADD CONSTRAINT FK_2E6E77EDEF1A9D84 FOREIGN KEY (notification_id) REFERENCES jdj_notification (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE jdj_notification_author ADD CONSTRAINT FK_2E6E77EDF675F31B FOREIGN KEY (author_id) REFERENCES sylius_customer (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf('mysql' !== $this->connection->getDatabasePlatform()->getName(), 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE app_notification_author (notification_id INT NOT NULL, author_id INT NOT NULL, INDEX IDX_27B6CE50EF1A9D84 (notification_id), INDEX IDX_27B6CE50F675F31B (author_id), PRIMARY KEY(notification_id, author_id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE app_notification_author ADD CONSTRAINT FK_27B6CE50EF1A9D84 FOREIGN KEY (notification_id) REFERENCES jdj_notification (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE app_notification_author ADD CONSTRAINT FK_27B6CE50F675F31B FOREIGN KEY (author_id) REFERENCES sylius_customer (id)');
    }
}
