<?php declare(strict_types = 1);

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20180109120714 extends AbstractMigration
{
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE sylius_product CHANGE age_min min_age INT UNSIGNED DEFAULT NULL, CHANGE joueur_min min_player_count INT UNSIGNED DEFAULT NULL, CHANGE joueur_max max_player_count INT UNSIGNED DEFAULT NULL, CHANGE duration_min min_duration INT UNSIGNED DEFAULT NULL, CHANGE duration_max max_duration INT UNSIGNED DEFAULT NULL, CHANGE materiel box_content LONGTEXT DEFAULT NULL, DROP but');
    }

    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE sylius_product CHANGE min_age age_min INT UNSIGNED DEFAULT NULL, CHANGE min_player_count joueur_min INT UNSIGNED DEFAULT NULL, CHANGE max_player_count joueur_max INT UNSIGNED DEFAULT NULL, CHANGE min_duration duration_min INT UNSIGNED DEFAULT NULL, CHANGE max_duration duration_max INT UNSIGNED DEFAULT NULL, CHANGE box_content materiel LONGTEXT DEFAULT NULL COLLATE utf8_unicode_ci, ADD but LONGTEXT DEFAULT NULL COLLATE utf8_unicode_ci');
    }
}
