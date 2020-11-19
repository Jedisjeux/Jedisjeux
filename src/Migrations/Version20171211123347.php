<?php

/*
 * This file is part of the Jedisjeux project.
 *
 * (c) Jedisjeux
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20171211123347 extends AbstractMigration
{
    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf('mysql' !== $this->connection->getDatabasePlatform()->getName(), 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE jdj_festival_list ADD image_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE jdj_festival_list ADD CONSTRAINT FK_A0BAA8793DA5256D FOREIGN KEY (image_id) REFERENCES jdj_festival_list_image (id)');
        $this->addSql('CREATE INDEX IDX_A0BAA8793DA5256D ON jdj_festival_list (image_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf('mysql' !== $this->connection->getDatabasePlatform()->getName(), 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE jdj_festival_list DROP FOREIGN KEY FK_A0BAA8793DA5256D');
        $this->addSql('DROP INDEX IDX_A0BAA8793DA5256D ON jdj_festival_list');
        $this->addSql('ALTER TABLE jdj_festival_list DROP image_id');
    }
}
