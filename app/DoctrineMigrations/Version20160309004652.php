<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20160309004652 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE jdj_jeu DROP FOREIGN KEY FK_1C86A5261017DE9');
        $this->addSql('ALTER TABLE jdj_jeu DROP FOREIGN KEY FK_1C86A526DCE0CBC5');
        $this->addSql('DROP INDEX IDX_1C86A526DCE0CBC5 ON jdj_jeu');
        $this->addSql('DROP INDEX IDX_1C86A5261017DE9 ON jdj_jeu');
        $this->addSql('DROP INDEX IDX_1C86A526A96E5E09 ON jdj_jeu');
        $this->addSql('DROP INDEX search_idx ON jdj_jeu');
        $this->addSql('ALTER TABLE jdj_jeu DROP cible_id, DROP name, DROP ageMin, DROP joueurMin, DROP joueurMax, DROP intro, DROP materiel, DROP but, DROP description, DROP slug, DROP status, DROP imageCouverture_id, DROP materialImage_id, DROP durationMin, DROP durationMax, DROP durationByPlayer');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE jdj_jeu ADD cible_id INT DEFAULT NULL, ADD name VARCHAR(50) NOT NULL COLLATE utf8_unicode_ci, ADD ageMin INT UNSIGNED DEFAULT NULL, ADD joueurMin INT UNSIGNED DEFAULT NULL, ADD joueurMax INT UNSIGNED DEFAULT NULL, ADD intro LONGTEXT DEFAULT NULL COLLATE utf8_unicode_ci, ADD materiel LONGTEXT DEFAULT NULL COLLATE utf8_unicode_ci, ADD but LONGTEXT DEFAULT NULL COLLATE utf8_unicode_ci, ADD description LONGTEXT DEFAULT NULL COLLATE utf8_unicode_ci, ADD slug VARCHAR(128) NOT NULL COLLATE utf8_unicode_ci, ADD status VARCHAR(255) NOT NULL COLLATE utf8_unicode_ci, ADD imageCouverture_id INT DEFAULT NULL, ADD materialImage_id INT DEFAULT NULL, ADD durationMin INT UNSIGNED DEFAULT NULL, ADD durationMax INT UNSIGNED DEFAULT NULL, ADD durationByPlayer TINYINT(1) NOT NULL');
        $this->addSql('ALTER TABLE jdj_jeu ADD CONSTRAINT FK_1C86A5261017DE9 FOREIGN KEY (materialImage_id) REFERENCES jdj_image (id)');
        $this->addSql('ALTER TABLE jdj_jeu ADD CONSTRAINT FK_1C86A526DCE0CBC5 FOREIGN KEY (imageCouverture_id) REFERENCES jdj_image (id)');
        $this->addSql('CREATE INDEX IDX_1C86A526DCE0CBC5 ON jdj_jeu (imageCouverture_id)');
        $this->addSql('CREATE INDEX IDX_1C86A5261017DE9 ON jdj_jeu (materialImage_id)');
        $this->addSql('CREATE INDEX IDX_1C86A526A96E5E09 ON jdj_jeu (cible_id)');
        $this->addSql('CREATE INDEX search_idx ON jdj_jeu (slug)');
    }
}
