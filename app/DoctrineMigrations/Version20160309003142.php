<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20160309003142 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE jdj_caracteristique_jeu DROP FOREIGN KEY FK_EB44969F1704EEB7');
        $this->addSql('ALTER TABLE jdj_note_caracteristique DROP FOREIGN KEY FK_4A1759A41704EEB7');
        $this->addSql('ALTER TABLE jdj_jeu DROP FOREIGN KEY FK_1C86A526A96E5E09');
        $this->addSql('ALTER TABLE jdj_user_review DROP FOREIGN KEY FK_6A466932A3374967');
        $this->addSql('ALTER TABLE jeu_mechanism DROP FOREIGN KEY FK_2821C84737CD6DD0');
        $this->addSql('ALTER TABLE jdj_jeu_note DROP FOREIGN KEY FK_A56383F326ED0855');
        $this->addSql('ALTER TABLE jdj_caracteristique_jeu DROP FOREIGN KEY FK_EB44969F7CAECA0');
        $this->addSql('ALTER TABLE jdj_statut_jeu DROP FOREIGN KEY FK_65195847F6203804');
        $this->addSql('ALTER TABLE jeu_theme DROP FOREIGN KEY FK_A590E4F059027487');
        $this->addSql('DROP TABLE jdj_auteur_jeu');
        $this->addSql('DROP TABLE jdj_caracteristique');
        $this->addSql('DROP TABLE jdj_caracteristique_jeu');
        $this->addSql('DROP TABLE jdj_cible');
        $this->addSql('DROP TABLE jdj_editeur_jeu');
        $this->addSql('DROP TABLE jdj_game_taxon');
        $this->addSql('DROP TABLE jdj_illustrateur_jeu');
        $this->addSql('DROP TABLE jdj_jeu_note');
        $this->addSql('DROP TABLE jdj_materiel');
        $this->addSql('DROP TABLE jdj_mechanism');
        $this->addSql('DROP TABLE jdj_note');
        $this->addSql('DROP TABLE jdj_note_caracteristique');
        $this->addSql('DROP TABLE jdj_pays');
        $this->addSql('DROP TABLE jdj_statut');
        $this->addSql('DROP TABLE jdj_statut_jeu');
        $this->addSql('DROP TABLE jdj_theme');
        $this->addSql('DROP TABLE jdj_user_review');
        $this->addSql('DROP TABLE jeu_mechanism');
        $this->addSql('DROP TABLE jeu_theme');
        $this->addSql('DELETE FROM jdj_addon');
        $this->addSql('ALTER TABLE jdj_addon DROP FOREIGN KEY FK_BE5C7E3E8C9E392E');
        $this->addSql('ALTER TABLE jdj_addon ADD CONSTRAINT FK_BE5C7E3E8C9E392E FOREIGN KEY (jeu_id) REFERENCES sylius_product (id)');
        $this->addSql('DELETE FROM jdj_game_review');
        $this->addSql('DELETE FROM jdj_game_rate');
        $this->addSql('DELETE FROM jdj_partie');
        $this->addSql('DELETE FROM jdj_jeu_image');
        $this->addSql('DELETE FROM jdj_jeu');
        $this->addSql('DROP TABLE jdj_jeu_image');
        $this->addSql('ALTER TABLE jdj_partie DROP FOREIGN KEY FK_42FB7D48C9E392E');
        $this->addSql('ALTER TABLE jdj_partie ADD CONSTRAINT FK_42FB7D48C9E392E FOREIGN KEY (jeu_id) REFERENCES sylius_product (id)');
        $this->addSql('ALTER TABLE jdj_game_rate DROP FOREIGN KEY FK_3818F94FE48FD905');
        $this->addSql('ALTER TABLE jdj_game_rate ADD CONSTRAINT FK_3818F94FE48FD905 FOREIGN KEY (game_id) REFERENCES sylius_product (id)');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE jdj_auteur_jeu (jeu_id INT NOT NULL, personne_id INT NOT NULL, INDEX IDX_2B8B40448C9E392E (jeu_id), INDEX IDX_2B8B4044A21BD112 (personne_id), PRIMARY KEY(jeu_id, personne_id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE jdj_caracteristique (id INT AUTO_INCREMENT NOT NULL, libelle VARCHAR(50) NOT NULL COLLATE utf8_unicode_ci, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE jdj_caracteristique_jeu (caracteristique_id INT NOT NULL, jeu_id INT NOT NULL, caracteristiqueNote_id INT DEFAULT NULL, INDEX IDX_EB44969F1704EEB7 (caracteristique_id), INDEX IDX_EB44969F8C9E392E (jeu_id), INDEX IDX_EB44969F7CAECA0 (caracteristiqueNote_id), PRIMARY KEY(caracteristique_id, jeu_id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE jdj_cible (id INT AUTO_INCREMENT NOT NULL, libelle VARCHAR(100) NOT NULL COLLATE utf8_unicode_ci, description LONGTEXT NOT NULL COLLATE utf8_unicode_ci, slug VARCHAR(128) NOT NULL COLLATE utf8_unicode_ci, INDEX search_idx (slug), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE jdj_editeur_jeu (jeu_id INT NOT NULL, personne_id INT NOT NULL, INDEX IDX_D33D730C8C9E392E (jeu_id), INDEX IDX_D33D730CA21BD112 (personne_id), PRIMARY KEY(jeu_id, personne_id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE jdj_game_taxon (jeu_id INT NOT NULL, taxoninterface_id INT NOT NULL, INDEX IDX_E2E603648C9E392E (jeu_id), INDEX IDX_E2E603647E2DD0E0 (taxoninterface_id), PRIMARY KEY(jeu_id, taxoninterface_id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE jdj_illustrateur_jeu (jeu_id INT NOT NULL, personne_id INT NOT NULL, INDEX IDX_DD00AAF8C9E392E (jeu_id), INDEX IDX_DD00AAFA21BD112 (personne_id), PRIMARY KEY(jeu_id, personne_id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE jdj_jeu_note (id INT AUTO_INCREMENT NOT NULL, note_id INT NOT NULL, jeu_id INT NOT NULL, author_id INT NOT NULL, createdAt DATETIME NOT NULL, updatedAt DATETIME NOT NULL, INDEX IDX_A56383F3F675F31B (author_id), INDEX IDX_A56383F38C9E392E (jeu_id), INDEX IDX_A56383F326ED0855 (note_id), INDEX search_idx (jeu_id, author_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE jdj_materiel (id INT AUTO_INCREMENT NOT NULL, libelle VARCHAR(50) NOT NULL COLLATE utf8_unicode_ci, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE jdj_mechanism (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(50) DEFAULT NULL COLLATE utf8_unicode_ci, description LONGTEXT DEFAULT NULL COLLATE utf8_unicode_ci, slug VARCHAR(128) NOT NULL COLLATE utf8_unicode_ci, aliases LONGTEXT NOT NULL COLLATE utf8_unicode_ci COMMENT \'(DC2Type:array)\', PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE jdj_note (id INT AUTO_INCREMENT NOT NULL, libelle VARCHAR(50) NOT NULL COLLATE utf8_unicode_ci, valeur INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE jdj_note_caracteristique (id INT AUTO_INCREMENT NOT NULL, caracteristique_id INT DEFAULT NULL, valeur INT UNSIGNED NOT NULL, libelle VARCHAR(50) NOT NULL COLLATE utf8_unicode_ci, INDEX IDX_4A1759A41704EEB7 (caracteristique_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE jdj_pays (id INT AUTO_INCREMENT NOT NULL, libelle VARCHAR(50) NOT NULL COLLATE utf8_unicode_ci, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE jdj_statut (id INT AUTO_INCREMENT NOT NULL, code VARCHAR(50) NOT NULL COLLATE utf8_unicode_ci, libelle VARCHAR(50) NOT NULL COLLATE utf8_unicode_ci, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE jdj_statut_jeu (statut_id INT NOT NULL, ordre INT UNSIGNED NOT NULL, PRIMARY KEY(statut_id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE jdj_theme (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(100) NOT NULL COLLATE utf8_unicode_ci, description VARCHAR(50) DEFAULT NULL COLLATE utf8_unicode_ci, slug VARCHAR(128) NOT NULL COLLATE utf8_unicode_ci, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE jdj_user_review (id INT AUTO_INCREMENT NOT NULL, libelle VARCHAR(100) NOT NULL COLLATE utf8_unicode_ci, body LONGTEXT NOT NULL COLLATE utf8_unicode_ci, createdAt DATETIME NOT NULL, updatedAt DATETIME NOT NULL, jeuNote_id INT NOT NULL, UNIQUE INDEX UNIQ_6A466932A3374967 (jeuNote_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE jeu_mechanism (jeu_id INT NOT NULL, mechanism_id INT NOT NULL, INDEX IDX_2821C8478C9E392E (jeu_id), INDEX IDX_2821C84737CD6DD0 (mechanism_id), PRIMARY KEY(jeu_id, mechanism_id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE jeu_theme (jeu_id INT NOT NULL, theme_id INT NOT NULL, INDEX IDX_A590E4F08C9E392E (jeu_id), INDEX IDX_A590E4F059027487 (theme_id), PRIMARY KEY(jeu_id, theme_id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE jdj_auteur_jeu ADD CONSTRAINT FK_2B8B40448C9E392E FOREIGN KEY (jeu_id) REFERENCES jdj_jeu (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE jdj_auteur_jeu ADD CONSTRAINT FK_2B8B4044A21BD112 FOREIGN KEY (personne_id) REFERENCES jdj_personne (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE jdj_caracteristique_jeu ADD CONSTRAINT FK_EB44969F1704EEB7 FOREIGN KEY (caracteristique_id) REFERENCES jdj_caracteristique (id)');
        $this->addSql('ALTER TABLE jdj_caracteristique_jeu ADD CONSTRAINT FK_EB44969F7CAECA0 FOREIGN KEY (caracteristiqueNote_id) REFERENCES jdj_note_caracteristique (id)');
        $this->addSql('ALTER TABLE jdj_caracteristique_jeu ADD CONSTRAINT FK_EB44969F8C9E392E FOREIGN KEY (jeu_id) REFERENCES jdj_jeu (id)');
        $this->addSql('ALTER TABLE jdj_editeur_jeu ADD CONSTRAINT FK_D33D730C8C9E392E FOREIGN KEY (jeu_id) REFERENCES jdj_jeu (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE jdj_editeur_jeu ADD CONSTRAINT FK_D33D730CA21BD112 FOREIGN KEY (personne_id) REFERENCES jdj_personne (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE jdj_game_taxon ADD CONSTRAINT FK_E2E603647E2DD0E0 FOREIGN KEY (taxoninterface_id) REFERENCES Taxon (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE jdj_game_taxon ADD CONSTRAINT FK_E2E603648C9E392E FOREIGN KEY (jeu_id) REFERENCES jdj_jeu (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE jdj_illustrateur_jeu ADD CONSTRAINT FK_DD00AAF8C9E392E FOREIGN KEY (jeu_id) REFERENCES jdj_jeu (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE jdj_illustrateur_jeu ADD CONSTRAINT FK_DD00AAFA21BD112 FOREIGN KEY (personne_id) REFERENCES jdj_personne (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE jdj_jeu_note ADD CONSTRAINT FK_A56383F326ED0855 FOREIGN KEY (note_id) REFERENCES jdj_note (id)');
        $this->addSql('ALTER TABLE jdj_jeu_note ADD CONSTRAINT FK_A56383F38C9E392E FOREIGN KEY (jeu_id) REFERENCES jdj_jeu (id)');
        $this->addSql('ALTER TABLE jdj_jeu_note ADD CONSTRAINT FK_A56383F3F675F31B FOREIGN KEY (author_id) REFERENCES fos_user (id)');
        $this->addSql('ALTER TABLE jdj_note_caracteristique ADD CONSTRAINT FK_4A1759A41704EEB7 FOREIGN KEY (caracteristique_id) REFERENCES jdj_caracteristique (id)');
        $this->addSql('ALTER TABLE jdj_statut_jeu ADD CONSTRAINT FK_65195847F6203804 FOREIGN KEY (statut_id) REFERENCES jdj_statut (id)');
        $this->addSql('ALTER TABLE jdj_user_review ADD CONSTRAINT FK_6A466932A3374967 FOREIGN KEY (jeuNote_id) REFERENCES jdj_jeu_note (id)');
        $this->addSql('ALTER TABLE jeu_mechanism ADD CONSTRAINT FK_2821C84737CD6DD0 FOREIGN KEY (mechanism_id) REFERENCES jdj_mechanism (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE jeu_mechanism ADD CONSTRAINT FK_2821C8478C9E392E FOREIGN KEY (jeu_id) REFERENCES jdj_jeu (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE jeu_theme ADD CONSTRAINT FK_A590E4F059027487 FOREIGN KEY (theme_id) REFERENCES jdj_theme (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE jeu_theme ADD CONSTRAINT FK_A590E4F08C9E392E FOREIGN KEY (jeu_id) REFERENCES jdj_jeu (id) ON DELETE CASCADE');
        $this->addSql('DELETE FROM jdj_addon');
        $this->addSql('ALTER TABLE jdj_addon DROP FOREIGN KEY FK_BE5C7E3E8C9E392E');
        $this->addSql('ALTER TABLE jdj_addon ADD CONSTRAINT FK_BE5C7E3E8C9E392E FOREIGN KEY (jeu_id) REFERENCES jdj_jeu (id)');
        $this->addSql('ALTER TABLE jdj_game_rate DROP FOREIGN KEY FK_3818F94FE48FD905');
        $this->addSql('ALTER TABLE jdj_game_rate ADD CONSTRAINT FK_3818F94FE48FD905 FOREIGN KEY (game_id) REFERENCES jdj_jeu (id)');
        $this->addSql('DELETE FROM jdj_partie');
        $this->addSql('ALTER TABLE jdj_partie DROP FOREIGN KEY FK_42FB7D48C9E392E');
        $this->addSql('ALTER TABLE jdj_partie ADD CONSTRAINT FK_42FB7D48C9E392E FOREIGN KEY (jeu_id) REFERENCES jdj_jeu (id)');
    }
}
