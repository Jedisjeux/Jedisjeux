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
class Version20170828204807 extends AbstractMigration
{
    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf('mysql' !== $this->connection->getDatabasePlatform()->getName(), 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE jdj_topic DROP FOREIGN KEY FK_F299315A2D053F64');
        $this->addSql('DROP INDEX IDX_F299315A2D053F64 ON jdj_topic');
        $this->addSql('ALTER TABLE jdj_topic DROP last_post_id');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf('mysql' !== $this->connection->getDatabasePlatform()->getName(), 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE jdj_topic ADD last_post_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE jdj_topic ADD CONSTRAINT FK_F299315A2D053F64 FOREIGN KEY (last_post_id) REFERENCES jdj_post (id)');
        $this->addSql('CREATE INDEX IDX_F299315A2D053F64 ON jdj_topic (last_post_id)');
    }
}
