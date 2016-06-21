<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20160621204743 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        $this->addSql('UPDATE Taxon set tree_root = id WHERE parent_id is null');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // nothing to do
    }
}
