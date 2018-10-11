<?php

namespace DoctrineMigrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20170615120742 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE jdj_topic ADD last_post_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE jdj_topic ADD CONSTRAINT FK_F299315A2D053F64 FOREIGN KEY (last_post_id) REFERENCES jdj_post (id)');
        $this->addSql('CREATE INDEX IDX_F299315A2D053F64 ON jdj_topic (last_post_id)');

        $this->addSql(<<<EOM
update jdj_topic topic
set last_post_id = (
  SELECT max(post.id)
  FROM jdj_post post
  where post.topic_id = topic.id
);

update jdj_topic topic
  inner join jdj_post post
    on post.id = topic.main_post_id
set last_post_id = post.id
where last_post_id is null;
EOM
);
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE jdj_topic DROP FOREIGN KEY FK_F299315A2D053F64');
        $this->addSql('DROP INDEX IDX_F299315A2D053F64 ON jdj_topic');
        $this->addSql('ALTER TABLE jdj_topic DROP last_post_id');
    }
}
