<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20160418162616 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE jdj_user_activity');
        $this->addSql('DROP TABLE jdj_user_partie');
        $this->addSql('ALTER TABLE fos_user DROP FOREIGN KEY FK_957A647986383B10');
        $this->addSql('DROP INDEX IDX_957A647986383B10 ON fos_user');
        $this->addSql('ALTER TABLE fos_user DROP avatar_id, DROP slug, DROP dateNaissance, DROP created, DROP updated, DROP deleted_at, DROP presentation');
        $this->addSql('ALTER TABLE jdj_partie_image DROP FOREIGN KEY FK_9B15754A3DA5256D');
        $this->addSql('ALTER TABLE jdj_partie_image DROP FOREIGN KEY FK_9B15754AE075F7A4');
        $this->addSql('DROP INDEX IDX_9B15754AE075F7A4 ON jdj_partie_image');
        $this->addSql('DROP INDEX IDX_9B15754A3DA5256D ON jdj_partie_image');
        $this->addSql('ALTER TABLE jdj_partie_image DROP image_id, DROP partie_id, DROP description');
        $this->addSql('ALTER TABLE jdj_joueur DROP FOREIGN KEY FK_FCC5012CA76ED395');
        $this->addSql('ALTER TABLE jdj_joueur DROP FOREIGN KEY FK_FCC5012CE075F7A4');
        $this->addSql('DROP INDEX IDX_FCC5012CE075F7A4 ON jdj_joueur');
        $this->addSql('DROP INDEX IDX_FCC5012CA76ED395 ON jdj_joueur');
        $this->addSql('ALTER TABLE jdj_joueur DROP user_id, DROP partie_id, DROP nom, DROP score');
        $this->addSql('ALTER TABLE jdj_partie DROP FOREIGN KEY FK_42FB7D48C9E392E');
        $this->addSql('ALTER TABLE jdj_partie DROP FOREIGN KEY FK_42FB7D4F675F31B');
        $this->addSql('DROP INDEX IDX_42FB7D4F675F31B ON jdj_partie');
        $this->addSql('DROP INDEX IDX_42FB7D48C9E392E ON jdj_partie');
        $this->addSql('ALTER TABLE jdj_partie DROP jeu_id, DROP author_id, DROP createdAt, DROP updatedAt, DROP playedAt');
        $this->addSql('ALTER TABLE jdj_list_element DROP FOREIGN KEY FK_7FC1AED3514956FD');
        $this->addSql('ALTER TABLE jdj_list_element DROP FOREIGN KEY FK_7FC1AED38C9E392E');
        $this->addSql('DROP INDEX IDX_7FC1AED3514956FD ON jdj_list_element');
        $this->addSql('DROP INDEX IDX_7FC1AED38C9E392E ON jdj_list_element');
        $this->addSql('ALTER TABLE jdj_list_element DROP collection_id, DROP jeu_id');
        $this->addSql('ALTER TABLE jdj_user_game_attribute DROP FOREIGN KEY FK_143FA0D88C9E392E');
        $this->addSql('ALTER TABLE jdj_user_game_attribute DROP FOREIGN KEY FK_143FA0D8A76ED395');
        $this->addSql('DROP INDEX IDX_143FA0D8A76ED395 ON jdj_user_game_attribute');
        $this->addSql('DROP INDEX IDX_143FA0D88C9E392E ON jdj_user_game_attribute');
        $this->addSql('ALTER TABLE jdj_user_game_attribute DROP jeu_id, DROP user_id, DROP is_favorite, DROP is_owned, DROP is_wanted, DROP has_played');
        $this->addSql('ALTER TABLE jdj_collection DROP FOREIGN KEY FK_77FF3C72A76ED395');
        $this->addSql('DROP INDEX IDX_77FF3C72A76ED395 ON jdj_collection');
        $this->addSql('ALTER TABLE jdj_collection DROP user_id, DROP name, DROP description, DROP slug');
        $this->addSql('ALTER TABLE jdj_activity DROP FOREIGN KEY FK_FBC56B9153D03CE1');
        $this->addSql('ALTER TABLE jdj_activity DROP FOREIGN KEY FK_FBC56B918C9E392E');
        $this->addSql('DROP INDEX UNIQ_FBC56B918C9E392E ON jdj_activity');
        $this->addSql('DROP INDEX IDX_FBC56B9153D03CE1 ON jdj_activity');
        $this->addSql('ALTER TABLE jdj_activity DROP jeu_id, DROP published, DROP actionUser_id');
        $this->addSql('ALTER TABLE jdj_notification DROP FOREIGN KEY FK_5D9A5B2D81C06096');
        $this->addSql('ALTER TABLE jdj_notification DROP FOREIGN KEY FK_5D9A5B2DA76ED395');
        $this->addSql('DROP INDEX IDX_5D9A5B2DA76ED395 ON jdj_notification');
        $this->addSql('DROP INDEX IDX_5D9A5B2D81C06096 ON jdj_notification');
        $this->addSql('ALTER TABLE jdj_notification DROP activity_id, DROP user_id, DROP createdAt, DROP comment, DROP status, DROP isRead, DROP changeStatus');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE jdj_user_activity (activity_id INT NOT NULL, user_id INT NOT NULL, INDEX IDX_727554AC81C06096 (activity_id), INDEX IDX_727554ACA76ED395 (user_id), PRIMARY KEY(activity_id, user_id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE jdj_user_partie (partie_id INT NOT NULL, user_id INT NOT NULL, INDEX IDX_169EF7C9E075F7A4 (partie_id), INDEX IDX_169EF7C9A76ED395 (user_id), PRIMARY KEY(partie_id, user_id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE jdj_user_activity ADD CONSTRAINT FK_727554AC81C06096 FOREIGN KEY (activity_id) REFERENCES jdj_activity (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE jdj_user_activity ADD CONSTRAINT FK_727554ACA76ED395 FOREIGN KEY (user_id) REFERENCES fos_user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE jdj_user_partie ADD CONSTRAINT FK_169EF7C9A76ED395 FOREIGN KEY (user_id) REFERENCES fos_user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE jdj_user_partie ADD CONSTRAINT FK_169EF7C9E075F7A4 FOREIGN KEY (partie_id) REFERENCES jdj_partie (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE fos_user ADD avatar_id INT DEFAULT NULL, ADD slug VARCHAR(128) NOT NULL COLLATE utf8_unicode_ci, ADD dateNaissance DATETIME DEFAULT NULL, ADD created DATETIME NOT NULL, ADD updated DATETIME NOT NULL, ADD deleted_at DATETIME DEFAULT NULL, ADD presentation LONGTEXT DEFAULT NULL COLLATE utf8_unicode_ci');
        $this->addSql('ALTER TABLE fos_user ADD CONSTRAINT FK_957A647986383B10 FOREIGN KEY (avatar_id) REFERENCES jdj_avatar (id)');
        $this->addSql('CREATE INDEX IDX_957A647986383B10 ON fos_user (avatar_id)');
        $this->addSql('ALTER TABLE jdj_activity ADD jeu_id INT DEFAULT NULL, ADD published DATETIME NOT NULL, ADD actionUser_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE jdj_activity ADD CONSTRAINT FK_FBC56B9153D03CE1 FOREIGN KEY (actionUser_id) REFERENCES fos_user (id)');
        $this->addSql('ALTER TABLE jdj_activity ADD CONSTRAINT FK_FBC56B918C9E392E FOREIGN KEY (jeu_id) REFERENCES jdj_jeu (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_FBC56B918C9E392E ON jdj_activity (jeu_id)');
        $this->addSql('CREATE INDEX IDX_FBC56B9153D03CE1 ON jdj_activity (actionUser_id)');
        $this->addSql('ALTER TABLE jdj_collection ADD user_id INT NOT NULL, ADD name VARCHAR(50) NOT NULL COLLATE utf8_unicode_ci, ADD description LONGTEXT DEFAULT NULL COLLATE utf8_unicode_ci, ADD slug VARCHAR(128) NOT NULL COLLATE utf8_unicode_ci');
        $this->addSql('ALTER TABLE jdj_collection ADD CONSTRAINT FK_77FF3C72A76ED395 FOREIGN KEY (user_id) REFERENCES fos_user (id)');
        $this->addSql('CREATE INDEX IDX_77FF3C72A76ED395 ON jdj_collection (user_id)');
        $this->addSql('ALTER TABLE jdj_joueur ADD user_id INT DEFAULT NULL, ADD partie_id INT NOT NULL, ADD nom VARCHAR(255) DEFAULT NULL COLLATE utf8_unicode_ci, ADD score INT DEFAULT NULL');
        $this->addSql('ALTER TABLE jdj_joueur ADD CONSTRAINT FK_FCC5012CA76ED395 FOREIGN KEY (user_id) REFERENCES fos_user (id)');
        $this->addSql('ALTER TABLE jdj_joueur ADD CONSTRAINT FK_FCC5012CE075F7A4 FOREIGN KEY (partie_id) REFERENCES jdj_partie (id)');
        $this->addSql('CREATE INDEX IDX_FCC5012CE075F7A4 ON jdj_joueur (partie_id)');
        $this->addSql('CREATE INDEX IDX_FCC5012CA76ED395 ON jdj_joueur (user_id)');
        $this->addSql('ALTER TABLE jdj_list_element ADD collection_id INT NOT NULL, ADD jeu_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE jdj_list_element ADD CONSTRAINT FK_7FC1AED3514956FD FOREIGN KEY (collection_id) REFERENCES jdj_collection (id)');
        $this->addSql('ALTER TABLE jdj_list_element ADD CONSTRAINT FK_7FC1AED38C9E392E FOREIGN KEY (jeu_id) REFERENCES jdj_jeu (id)');
        $this->addSql('CREATE INDEX IDX_7FC1AED3514956FD ON jdj_list_element (collection_id)');
        $this->addSql('CREATE INDEX IDX_7FC1AED38C9E392E ON jdj_list_element (jeu_id)');
        $this->addSql('ALTER TABLE jdj_notification ADD activity_id INT DEFAULT NULL, ADD user_id INT DEFAULT NULL, ADD createdAt DATETIME NOT NULL, ADD comment LONGTEXT DEFAULT NULL COLLATE utf8_unicode_ci, ADD status VARCHAR(255) NOT NULL COLLATE utf8_unicode_ci, ADD isRead TINYINT(1) NOT NULL, ADD changeStatus LONGTEXT DEFAULT NULL COLLATE utf8_unicode_ci');
        $this->addSql('ALTER TABLE jdj_notification ADD CONSTRAINT FK_5D9A5B2D81C06096 FOREIGN KEY (activity_id) REFERENCES jdj_activity (id)');
        $this->addSql('ALTER TABLE jdj_notification ADD CONSTRAINT FK_5D9A5B2DA76ED395 FOREIGN KEY (user_id) REFERENCES fos_user (id)');
        $this->addSql('CREATE INDEX IDX_5D9A5B2DA76ED395 ON jdj_notification (user_id)');
        $this->addSql('CREATE INDEX IDX_5D9A5B2D81C06096 ON jdj_notification (activity_id)');
        $this->addSql('ALTER TABLE jdj_partie ADD jeu_id INT NOT NULL, ADD author_id INT NOT NULL, ADD createdAt DATETIME NOT NULL, ADD updatedAt DATETIME NOT NULL, ADD playedAt DATE NOT NULL');
        $this->addSql('ALTER TABLE jdj_partie ADD CONSTRAINT FK_42FB7D48C9E392E FOREIGN KEY (jeu_id) REFERENCES sylius_product (id)');
        $this->addSql('ALTER TABLE jdj_partie ADD CONSTRAINT FK_42FB7D4F675F31B FOREIGN KEY (author_id) REFERENCES fos_user (id)');
        $this->addSql('CREATE INDEX IDX_42FB7D4F675F31B ON jdj_partie (author_id)');
        $this->addSql('CREATE INDEX IDX_42FB7D48C9E392E ON jdj_partie (jeu_id)');
        $this->addSql('ALTER TABLE jdj_partie_image ADD image_id INT DEFAULT NULL, ADD partie_id INT DEFAULT NULL, ADD description LONGTEXT DEFAULT NULL COLLATE utf8_unicode_ci');
        $this->addSql('ALTER TABLE jdj_partie_image ADD CONSTRAINT FK_9B15754A3DA5256D FOREIGN KEY (image_id) REFERENCES jdj_image (id)');
        $this->addSql('ALTER TABLE jdj_partie_image ADD CONSTRAINT FK_9B15754AE075F7A4 FOREIGN KEY (partie_id) REFERENCES jdj_partie (id)');
        $this->addSql('CREATE INDEX IDX_9B15754AE075F7A4 ON jdj_partie_image (partie_id)');
        $this->addSql('CREATE INDEX IDX_9B15754A3DA5256D ON jdj_partie_image (image_id)');
        $this->addSql('ALTER TABLE jdj_user_game_attribute ADD jeu_id INT DEFAULT NULL, ADD user_id INT DEFAULT NULL, ADD is_favorite TINYINT(1) DEFAULT NULL, ADD is_owned TINYINT(1) DEFAULT NULL, ADD is_wanted TINYINT(1) DEFAULT NULL, ADD has_played TINYINT(1) DEFAULT NULL');
        $this->addSql('ALTER TABLE jdj_user_game_attribute ADD CONSTRAINT FK_143FA0D88C9E392E FOREIGN KEY (jeu_id) REFERENCES jdj_jeu (id)');
        $this->addSql('ALTER TABLE jdj_user_game_attribute ADD CONSTRAINT FK_143FA0D8A76ED395 FOREIGN KEY (user_id) REFERENCES fos_user (id)');
        $this->addSql('CREATE INDEX IDX_143FA0D8A76ED395 ON jdj_user_game_attribute (user_id)');
        $this->addSql('CREATE INDEX IDX_143FA0D88C9E392E ON jdj_user_game_attribute (jeu_id)');
    }
}
