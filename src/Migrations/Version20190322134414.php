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
final class Version20190322134414 extends AbstractMigration
{
    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf('mysql' !== $this->connection->getDatabasePlatform()->getName(), 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE jdj_product_box ADD product_variant_id INT DEFAULT NULL, ADD enabled TINYINT(1) NOT NULL');
        $this->addSql('ALTER TABLE jdj_product_box ADD CONSTRAINT FK_9ED1EFECA80EF684 FOREIGN KEY (product_variant_id) REFERENCES sylius_product_variant (id)');
        $this->addSql('CREATE INDEX IDX_9ED1EFECA80EF684 ON jdj_product_box (product_variant_id)');
        $this->addSql('ALTER TABLE sylius_product_variant DROP FOREIGN KEY FK_A29B523D8177B3F');
        $this->addSql('DROP INDEX UNIQ_A29B523D8177B3F ON sylius_product_variant');

        $this->addSql(<<<EOM
UPDATE jdj_product_box box
INNER JOIN sylius_product_variant variant on box.id = variant.box_id
SET box.product_variant_id = variant.id
EOM
);

        $this->addSql('ALTER TABLE sylius_product_variant DROP box_id');
        $this->addSql('UPDATE jdj_product_box set enabled=1');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf('mysql' !== $this->connection->getDatabasePlatform()->getName(), 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE sylius_product_variant ADD box_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE jdj_product_box DROP FOREIGN KEY FK_9ED1EFECA80EF684');
        $this->addSql('DROP INDEX IDX_9ED1EFECA80EF684 ON jdj_product_box');

        $this->addSql(<<<EOM
UPDATE sylius_product_variant variant
  INNER JOIN jdj_product_box box on box.product_variant_id = variant.id
SET variant.box_id = box.id
where 0
EOM
);

        $this->addSql('ALTER TABLE jdj_product_box DROP product_variant_id, DROP enabled');
        $this->addSql('ALTER TABLE sylius_product_variant ADD CONSTRAINT FK_A29B523D8177B3F FOREIGN KEY (box_id) REFERENCES jdj_product_box (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_A29B523D8177B3F ON sylius_product_variant (box_id)');
    }
}
