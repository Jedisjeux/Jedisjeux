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
final class Version20190326100758 extends AbstractMigration
{
    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf('mysql' !== $this->connection->getDatabasePlatform()->getName(), 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE jdj_product_box ADD author_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE jdj_product_box ADD CONSTRAINT FK_9ED1EFECF675F31B FOREIGN KEY (author_id) REFERENCES sylius_customer (id)');
        $this->addSql('CREATE INDEX IDX_9ED1EFECF675F31B ON jdj_product_box (author_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf('mysql' !== $this->connection->getDatabasePlatform()->getName(), 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE jdj_product_box DROP FOREIGN KEY FK_9ED1EFECF675F31B');
        $this->addSql('DROP INDEX IDX_9ED1EFECF675F31B ON jdj_product_box');
        $this->addSql('ALTER TABLE jdj_product_box DROP author_id');
    }
}
