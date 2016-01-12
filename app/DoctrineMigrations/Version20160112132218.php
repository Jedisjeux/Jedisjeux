<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20160112132218 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE phpcr_namespaces (prefix VARCHAR(255) NOT NULL, uri VARCHAR(255) NOT NULL, PRIMARY KEY(prefix)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE phpcr_workspaces (name VARCHAR(255) NOT NULL, PRIMARY KEY(name)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE phpcr_nodes (id INT AUTO_INCREMENT NOT NULL, path VARCHAR(255) NOT NULL, parent VARCHAR(255) NOT NULL, local_name VARCHAR(255) NOT NULL, namespace VARCHAR(255) NOT NULL, workspace_name VARCHAR(255) NOT NULL, identifier VARCHAR(255) NOT NULL, type VARCHAR(255) NOT NULL, props LONGTEXT NOT NULL, numerical_props LONGTEXT DEFAULT NULL, depth INT NOT NULL, sort_order INT DEFAULT NULL, UNIQUE INDEX UNIQ_A4624AD7B548B0F1AC10DC4 (path, workspace_name), UNIQUE INDEX UNIQ_A4624AD7772E836A1AC10DC4 (identifier, workspace_name), INDEX IDX_A4624AD73D8E604F (parent), INDEX IDX_A4624AD78CDE5729 (type), INDEX IDX_A4624AD7623C14D533E16B56 (local_name, namespace), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE phpcr_internal_index_types (type VARCHAR(255) NOT NULL, node_id INT NOT NULL, PRIMARY KEY(type, node_id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE phpcr_binarydata (id INT AUTO_INCREMENT NOT NULL, node_id INT NOT NULL, property_name VARCHAR(255) NOT NULL, workspace_name VARCHAR(255) NOT NULL, idx INT DEFAULT 0 NOT NULL, data LONGBLOB NOT NULL, UNIQUE INDEX UNIQ_37E65615460D9FD7413BC13C1AC10DC4E7087E10 (node_id, property_name, workspace_name, idx), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE phpcr_nodes_references (source_id INT NOT NULL, source_property_name VARCHAR(220) NOT NULL, target_id INT NOT NULL, INDEX IDX_F3BF7E1158E0B66 (target_id), PRIMARY KEY(source_id, source_property_name, target_id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE phpcr_nodes_weakreferences (source_id INT NOT NULL, source_property_name VARCHAR(220) NOT NULL, target_id INT NOT NULL, INDEX IDX_F0E4F6FA158E0B66 (target_id), PRIMARY KEY(source_id, source_property_name, target_id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE phpcr_type_nodes (node_type_id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, supertypes VARCHAR(255) NOT NULL, is_abstract TINYINT(1) NOT NULL, is_mixin TINYINT(1) NOT NULL, queryable TINYINT(1) NOT NULL, orderable_child_nodes TINYINT(1) NOT NULL, primary_item VARCHAR(255) DEFAULT NULL, UNIQUE INDEX UNIQ_34B0A8095E237E06 (name), PRIMARY KEY(node_type_id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE phpcr_type_props (node_type_id INT NOT NULL, name VARCHAR(255) NOT NULL, protected TINYINT(1) NOT NULL, auto_created TINYINT(1) NOT NULL, mandatory TINYINT(1) NOT NULL, on_parent_version INT NOT NULL, multiple TINYINT(1) NOT NULL, fulltext_searchable TINYINT(1) NOT NULL, query_orderable TINYINT(1) NOT NULL, required_type INT NOT NULL, query_operators INT NOT NULL, default_value VARCHAR(255) DEFAULT NULL, PRIMARY KEY(node_type_id, name)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE phpcr_type_childs (node_type_id INT NOT NULL, name VARCHAR(255) NOT NULL, protected TINYINT(1) NOT NULL, auto_created TINYINT(1) NOT NULL, mandatory TINYINT(1) NOT NULL, on_parent_version INT NOT NULL, primary_types VARCHAR(255) NOT NULL, default_type VARCHAR(255) DEFAULT NULL) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE phpcr_namespaces');
        $this->addSql('DROP TABLE phpcr_workspaces');
        $this->addSql('DROP TABLE phpcr_nodes');
        $this->addSql('DROP TABLE phpcr_internal_index_types');
        $this->addSql('DROP TABLE phpcr_binarydata');
        $this->addSql('DROP TABLE phpcr_nodes_references');
        $this->addSql('DROP TABLE phpcr_nodes_weakreferences');
        $this->addSql('DROP TABLE phpcr_type_nodes');
        $this->addSql('DROP TABLE phpcr_type_props');
        $this->addSql('DROP TABLE phpcr_type_childs');
    }
}
