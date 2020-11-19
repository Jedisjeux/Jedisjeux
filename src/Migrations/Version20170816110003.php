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
class Version20170816110003 extends AbstractMigration
{
    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf('mysql' !== $this->connection->getDatabasePlatform()->getName(), 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE jdj_post DROP FOREIGN KEY FK_3312CC5B1F55203D');
        $this->addSql('ALTER TABLE jdj_post ADD CONSTRAINT FK_3312CC5B1F55203D FOREIGN KEY (topic_id) REFERENCES jdj_topic (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf('mysql' !== $this->connection->getDatabasePlatform()->getName(), 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE jdj_post DROP FOREIGN KEY FK_3312CC5B1F55203D');
        $this->addSql('ALTER TABLE jdj_post ADD CONSTRAINT FK_3312CC5B1F55203D FOREIGN KEY (topic_id) REFERENCES jdj_topic (id)');
    }
}
