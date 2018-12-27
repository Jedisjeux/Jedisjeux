<?php declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20181227141838 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE jdj_game_award ADD image_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE jdj_game_award ADD CONSTRAINT FK_33DA0E283DA5256D FOREIGN KEY (image_id) REFERENCES jdj_game_award_image (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_33DA0E283DA5256D ON jdj_game_award (image_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE jdj_game_award DROP FOREIGN KEY FK_33DA0E283DA5256D');
        $this->addSql('DROP INDEX UNIQ_33DA0E283DA5256D ON jdj_game_award');
        $this->addSql('ALTER TABLE jdj_game_award DROP image_id');
    }
}
