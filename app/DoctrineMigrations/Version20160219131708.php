<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20160219131708 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE jdj_post');
        $this->addSql('DROP TABLE Topic');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE jdj_post
(
    id INT(11) PRIMARY KEY NOT NULL,
    topic_id INT(11),
    body LONGTEXT NOT NULL,
    createdBy_id INT(11),
    updatedBy_id INT(11),
    deletedBy_id INT(11),
    createdAt DATETIME,
    updatedAt DATETIME,
    CONSTRAINT FK_3312CC5B1F55203D FOREIGN KEY (topic_id) REFERENCES Topic (id),
    CONSTRAINT FK_3312CC5B3174800F FOREIGN KEY (createdBy_id) REFERENCES fos_user (id),
    CONSTRAINT FK_3312CC5B63D8C20E FOREIGN KEY (deletedBy_id) REFERENCES fos_user (id),
    CONSTRAINT FK_3312CC5B65FF1AEC FOREIGN KEY (updatedBy_id) REFERENCES fos_user (id)
);
CREATE INDEX IDX_3312CC5B1F55203D ON jdj_post (topic_id);
CREATE INDEX IDX_3312CC5B3174800F ON jdj_post (createdBy_id);
CREATE INDEX IDX_3312CC5B63D8C20E ON jdj_post (deletedBy_id);
CREATE INDEX IDX_3312CC5B65FF1AEC ON jdj_post (updatedBy_id);');


        $this->addSql('CREATE TABLE Topic (id INT(11) PRIMARY KEY NOT NULL, title VARCHAR(50) NOT NULL, createdBy_id INT(11), updatedBy_id INT(11), deletedBy_id INT(11),
    createdAt DATETIME,
    updatedAt DATETIME,
    CONSTRAINT FK_5C81F11F3174800F FOREIGN KEY (createdBy_id) REFERENCES fos_user (id),
    CONSTRAINT FK_5C81F11F63D8C20E FOREIGN KEY (deletedBy_id) REFERENCES fos_user (id),
    CONSTRAINT FK_5C81F11F65FF1AEC FOREIGN KEY (updatedBy_id) REFERENCES fos_user (id)
)');
        $this->addSql('CREATE INDEX IDX_5C81F11F3174800F ON Topic (createdBy_id)');
        $this->addSql('CREATE INDEX IDX_5C81F11F63D8C20E ON Topic (deletedBy_id)');
        $this->addSql('CREATE INDEX IDX_5C81F11F65FF1AEC ON Topic (updatedBy_id)');
    }
}
