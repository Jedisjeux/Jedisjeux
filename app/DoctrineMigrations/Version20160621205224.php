<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20160621205224 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        $this->addSql('UPDATE Taxon set tree_root = parent_id WHERE parent_id is not null');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // nothing to do
    }
}
