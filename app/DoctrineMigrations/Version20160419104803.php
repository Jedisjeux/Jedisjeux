<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20160419104803 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE jdj_langue_addon DROP FOREIGN KEY FK_5747BA12AADBACD');
        $this->addSql('DROP TABLE jdj_joueur');
        $this->addSql('DROP TABLE jdj_langue');
        $this->addSql('DROP TABLE jdj_langue_addon');
        $this->addSql('DROP TABLE jdj_partie');
        $this->addSql('DROP TABLE jdj_partie_image');
        $this->addSql('ALTER TABLE jdj_addon DROP FOREIGN KEY FK_BE5C7E3E697BCAA6');
        $this->addSql('ALTER TABLE jdj_addon DROP FOREIGN KEY FK_BE5C7E3E8C9E392E');
        $this->addSql('DROP INDEX IDX_BE5C7E3E8C9E392E ON jdj_addon');
        $this->addSql('DROP INDEX IDX_BE5C7E3E697BCAA6 ON jdj_addon');
        $this->addSql('ALTER TABLE jdj_addon DROP jeu_id, DROP url, DROP libelle, DROP typeAddon_id');
        $this->addSql('ALTER TABLE jdj_type_addon DROP libelle');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE jdj_joueur (id INT AUTO_INCREMENT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE jdj_langue (id INT AUTO_INCREMENT NOT NULL, libelle VARCHAR(50) NOT NULL COLLATE utf8_unicode_ci, icon VARCHAR(50) NOT NULL COLLATE utf8_unicode_ci, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE jdj_langue_addon (langue_id INT NOT NULL, addon_id INT NOT NULL, INDEX IDX_5747BA12AADBACD (langue_id), INDEX IDX_5747BA1CC642678 (addon_id), PRIMARY KEY(langue_id, addon_id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE jdj_partie (id INT AUTO_INCREMENT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE jdj_partie_image (id INT AUTO_INCREMENT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE jdj_langue_addon ADD CONSTRAINT FK_5747BA12AADBACD FOREIGN KEY (langue_id) REFERENCES jdj_langue (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE jdj_langue_addon ADD CONSTRAINT FK_5747BA1CC642678 FOREIGN KEY (addon_id) REFERENCES jdj_addon (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE jdj_addon ADD jeu_id INT DEFAULT NULL, ADD url VARCHAR(100) DEFAULT NULL COLLATE utf8_unicode_ci, ADD libelle VARCHAR(50) DEFAULT NULL COLLATE utf8_unicode_ci, ADD typeAddon_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE jdj_addon ADD CONSTRAINT FK_BE5C7E3E697BCAA6 FOREIGN KEY (typeAddon_id) REFERENCES jdj_type_addon (id)');
        $this->addSql('ALTER TABLE jdj_addon ADD CONSTRAINT FK_BE5C7E3E8C9E392E FOREIGN KEY (jeu_id) REFERENCES sylius_product (id)');
        $this->addSql('CREATE INDEX IDX_BE5C7E3E8C9E392E ON jdj_addon (jeu_id)');
        $this->addSql('CREATE INDEX IDX_BE5C7E3E697BCAA6 ON jdj_addon (typeAddon_id)');
        $this->addSql('ALTER TABLE jdj_type_addon ADD libelle VARCHAR(50) NOT NULL COLLATE utf8_unicode_ci');
    }
}
