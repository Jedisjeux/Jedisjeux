<?php declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20181231123014 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE product_year_award');
        $this->addSql('DROP INDEX UNIQ_A2875970989D9B62 ON jdj_year_award');
        $this->addSql('ALTER TABLE jdj_year_award ADD product_id INT DEFAULT NULL, DROP slug');
        $this->addSql('ALTER TABLE jdj_year_award ADD CONSTRAINT FK_A28759704584665A FOREIGN KEY (product_id) REFERENCES sylius_product (id)');
        $this->addSql('CREATE INDEX IDX_A28759704584665A ON jdj_year_award (product_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE product_year_award (product_id INT NOT NULL, year_award_id INT NOT NULL, INDEX IDX_BA525F1B4584665A (product_id), INDEX IDX_BA525F1B1499E4AA (year_award_id), PRIMARY KEY(product_id, year_award_id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE product_year_award ADD CONSTRAINT FK_BA525F1B1499E4AA FOREIGN KEY (year_award_id) REFERENCES jdj_year_award (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE product_year_award ADD CONSTRAINT FK_BA525F1B4584665A FOREIGN KEY (product_id) REFERENCES sylius_product (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE jdj_year_award DROP FOREIGN KEY FK_A28759704584665A');
        $this->addSql('DROP INDEX IDX_A28759704584665A ON jdj_year_award');
        $this->addSql('ALTER TABLE jdj_year_award ADD slug VARCHAR(255) DEFAULT NULL COLLATE utf8_unicode_ci, DROP product_id');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_A2875970989D9B62 ON jdj_year_award (slug)');
    }
}
