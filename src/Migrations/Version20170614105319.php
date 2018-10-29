<?php

namespace DoctrineMigrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20170614105319 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf('mysql' !== $this->connection->getDatabasePlatform()->getName(), 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE jdj_topic ADD last_post_created_at DATETIME DEFAULT NULL');
        $this->addSql(<<<EOM
update jdj_topic topic
set last_post_created_at = (
  SELECT max(post.created_at)
  FROM jdj_post post
  where post.topic_id = topic.id
);

update jdj_topic topic
  inner join jdj_post post
    on post.id = topic.main_post_id
set last_post_created_at = post.created_at
where last_post_created_at is null;
EOM
);
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf('mysql' !== $this->connection->getDatabasePlatform()->getName(), 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE jdj_topic DROP last_post_created_at');
    }
}
