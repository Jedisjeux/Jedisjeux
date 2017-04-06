<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20170407001627 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE sylius_taxon DROP FOREIGN KEY FK_9AA60CAF727ACA70');
        $this->addSql('ALTER TABLE sylius_taxon DROP FOREIGN KEY FK_9AA60CAFA977936C');
        $this->addSql('DROP INDEX uniq_9aa60caf77153098 ON sylius_taxon');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_CFD811CA77153098 ON sylius_taxon (code)');
        $this->addSql('DROP INDEX idx_9aa60cafa977936c ON sylius_taxon');
        $this->addSql('CREATE INDEX IDX_CFD811CAA977936C ON sylius_taxon (tree_root)');
        $this->addSql('DROP INDEX idx_9aa60caf727aca70 ON sylius_taxon');
        $this->addSql('CREATE INDEX IDX_CFD811CA727ACA70 ON sylius_taxon (parent_id)');
        $this->addSql('ALTER TABLE sylius_taxon ADD CONSTRAINT FK_9AA60CAF727ACA70 FOREIGN KEY (parent_id) REFERENCES sylius_taxon (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE sylius_taxon ADD CONSTRAINT FK_9AA60CAFA977936C FOREIGN KEY (tree_root) REFERENCES sylius_taxon (id) ON DELETE CASCADE');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE sylius_taxon DROP FOREIGN KEY FK_CFD811CAA977936C');
        $this->addSql('ALTER TABLE sylius_taxon DROP FOREIGN KEY FK_CFD811CA727ACA70');
        $this->addSql('DROP INDEX uniq_cfd811ca77153098 ON sylius_taxon');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_9AA60CAF77153098 ON sylius_taxon (code)');
        $this->addSql('DROP INDEX idx_cfd811caa977936c ON sylius_taxon');
        $this->addSql('CREATE INDEX IDX_9AA60CAFA977936C ON sylius_taxon (tree_root)');
        $this->addSql('DROP INDEX idx_cfd811ca727aca70 ON sylius_taxon');
        $this->addSql('CREATE INDEX IDX_9AA60CAF727ACA70 ON sylius_taxon (parent_id)');
        $this->addSql('ALTER TABLE sylius_taxon ADD CONSTRAINT FK_CFD811CAA977936C FOREIGN KEY (tree_root) REFERENCES sylius_taxon (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE sylius_taxon ADD CONSTRAINT FK_CFD811CA727ACA70 FOREIGN KEY (parent_id) REFERENCES sylius_taxon (id) ON DELETE CASCADE');
    }
}
