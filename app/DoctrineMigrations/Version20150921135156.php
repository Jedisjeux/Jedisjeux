<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20150921135156 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE ext_translations (id INT AUTO_INCREMENT NOT NULL, locale VARCHAR(8) NOT NULL, object_class VARCHAR(255) NOT NULL, field VARCHAR(32) NOT NULL, foreign_key VARCHAR(64) NOT NULL, content LONGTEXT DEFAULT NULL, INDEX translations_lookup_idx (locale, object_class, foreign_key), UNIQUE INDEX lookup_unique_idx (locale, object_class, field, foreign_key), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE ext_log_entries (id INT AUTO_INCREMENT NOT NULL, action VARCHAR(8) NOT NULL, logged_at DATETIME NOT NULL, object_id VARCHAR(64) DEFAULT NULL, object_class VARCHAR(255) NOT NULL, version INT NOT NULL, data LONGTEXT DEFAULT NULL COMMENT \'(DC2Type:array)\', username VARCHAR(255) DEFAULT NULL, INDEX log_class_lookup_idx (object_class), INDEX log_date_lookup_idx (logged_at), INDEX log_user_lookup_idx (username), INDEX log_version_lookup_idx (object_id, object_class, version), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE jdj_langue (id INT AUTO_INCREMENT NOT NULL, libelle VARCHAR(50) NOT NULL, icon VARCHAR(50) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE jdj_langue_addon (langue_id INT NOT NULL, addon_id INT NOT NULL, INDEX IDX_5747BA12AADBACD (langue_id), INDEX IDX_5747BA1CC642678 (addon_id), PRIMARY KEY(langue_id, addon_id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE jdj_pays (id INT AUTO_INCREMENT NOT NULL, libelle VARCHAR(50) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE jdj_statut (id INT AUTO_INCREMENT NOT NULL, code VARCHAR(50) NOT NULL, libelle VARCHAR(50) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE cpta_address (id INT AUTO_INCREMENT NOT NULL, street VARCHAR(255) NOT NULL, additionalAddressInfo VARCHAR(255) DEFAULT NULL, postalCode VARCHAR(255) NOT NULL, city VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE cpta_bill (id INT AUTO_INCREMENT NOT NULL, customer_id INT NOT NULL, paidAt DATETIME DEFAULT NULL, createdAt DATETIME NOT NULL, customerAddressVersion INT NOT NULL, paymentMethod_id INT NOT NULL, bookEntry_id INT DEFAULT NULL, INDEX IDX_151E8E7D9395C3F3 (customer_id), INDEX IDX_151E8E7DF57FBCCC (paymentMethod_id), UNIQUE INDEX UNIQ_151E8E7DA428E2FF (bookEntry_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE cpta_bill_product (bill_id INT NOT NULL, product_id INT NOT NULL, productVersion INT NOT NULL, quantity INT NOT NULL, INDEX IDX_D9A28B271A8C12F5 (bill_id), INDEX IDX_D9A28B274584665A (product_id), PRIMARY KEY(bill_id, product_id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE cpta_book_entry (id INT AUTO_INCREMENT NOT NULL, price NUMERIC(6, 2) NOT NULL, `label` VARCHAR(255) NOT NULL, createdAt DATETIME NOT NULL, creditedAt DATETIME DEFAULT NULL, debitedAt DATETIME DEFAULT NULL, paymentMethod_id INT NOT NULL, INDEX IDX_8A6A60D0F57FBCCC (paymentMethod_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE cpta_customer (id INT AUTO_INCREMENT NOT NULL, address_id INT NOT NULL, society VARCHAR(255) NOT NULL, email VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_A129E01EF5B7AF75 (address_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE cpta_payment_method (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE cpta_product (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(50) NOT NULL, price NUMERIC(6, 2) NOT NULL, subscriptionDuration INT NOT NULL, deletedAt DATETIME DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE cpta_subscription (id INT AUTO_INCREMENT NOT NULL, bill_id INT NOT NULL, product_id INT NOT NULL, customer_id INT NOT NULL, status VARCHAR(255) NOT NULL, createdAt DATETIME NOT NULL, startAt DATETIME DEFAULT NULL, endAt DATETIME DEFAULT NULL, INDEX IDX_5DEDDF461A8C12F5 (bill_id), INDEX IDX_5DEDDF464584665A (product_id), INDEX IDX_5DEDDF469395C3F3 (customer_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE jdj_addon (id INT AUTO_INCREMENT NOT NULL, jeu_id INT DEFAULT NULL, url VARCHAR(100) DEFAULT NULL, libelle VARCHAR(50) DEFAULT NULL, typeAddon_id INT DEFAULT NULL, INDEX IDX_BE5C7E3E8C9E392E (jeu_id), INDEX IDX_BE5C7E3E697BCAA6 (typeAddon_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE jdj_caracteristique (id INT AUTO_INCREMENT NOT NULL, libelle VARCHAR(50) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE jdj_note_caracteristique (id INT AUTO_INCREMENT NOT NULL, caracteristique_id INT DEFAULT NULL, valeur INT UNSIGNED NOT NULL, libelle VARCHAR(50) NOT NULL, INDEX IDX_4A1759A41704EEB7 (caracteristique_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE jdj_jeu (id INT AUTO_INCREMENT NOT NULL, cible_id INT DEFAULT NULL, libelle VARCHAR(50) NOT NULL, ageMin INT UNSIGNED DEFAULT NULL, joueurMin INT UNSIGNED DEFAULT NULL, joueurMax INT UNSIGNED DEFAULT NULL, intro LONGTEXT DEFAULT NULL, materiel LONGTEXT DEFAULT NULL, but LONGTEXT DEFAULT NULL, description LONGTEXT DEFAULT NULL, slug VARCHAR(128) NOT NULL, status enum(\'WRITING\', \'NEED_A_TRANSLATION\', \'NEED_A_REVIEW\', \'READY_TO_PUBLISH\', \'PUBLISHED\'), imageCouverture_id INT DEFAULT NULL, materialImage_id INT DEFAULT NULL, INDEX IDX_1C86A526DCE0CBC5 (imageCouverture_id), INDEX IDX_1C86A5261017DE9 (materialImage_id), INDEX IDX_1C86A526A96E5E09 (cible_id), INDEX search_idx (slug), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE jeu_mechanism (jeu_id INT NOT NULL, mechanism_id INT NOT NULL, INDEX IDX_2821C8478C9E392E (jeu_id), INDEX IDX_2821C84737CD6DD0 (mechanism_id), PRIMARY KEY(jeu_id, mechanism_id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE jeu_theme (jeu_id INT NOT NULL, theme_id INT NOT NULL, INDEX IDX_A590E4F08C9E392E (jeu_id), INDEX IDX_A590E4F059027487 (theme_id), PRIMARY KEY(jeu_id, theme_id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE jdj_auteur_jeu (jeu_id INT NOT NULL, personne_id INT NOT NULL, INDEX IDX_2B8B40448C9E392E (jeu_id), INDEX IDX_2B8B4044A21BD112 (personne_id), PRIMARY KEY(jeu_id, personne_id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE jdj_illustrateur_jeu (jeu_id INT NOT NULL, personne_id INT NOT NULL, INDEX IDX_DD00AAF8C9E392E (jeu_id), INDEX IDX_DD00AAFA21BD112 (personne_id), PRIMARY KEY(jeu_id, personne_id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE jdj_editeur_jeu (jeu_id INT NOT NULL, personne_id INT NOT NULL, INDEX IDX_D33D730C8C9E392E (jeu_id), INDEX IDX_D33D730CA21BD112 (personne_id), PRIMARY KEY(jeu_id, personne_id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE jdj_caracteristique_jeu (caracteristique_id INT NOT NULL, jeu_id INT NOT NULL, caracteristiqueNote_id INT DEFAULT NULL, INDEX IDX_EB44969F1704EEB7 (caracteristique_id), INDEX IDX_EB44969F8C9E392E (jeu_id), INDEX IDX_EB44969F7CAECA0 (caracteristiqueNote_id), PRIMARY KEY(caracteristique_id, jeu_id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE jdj_jeu_image (id INT AUTO_INCREMENT NOT NULL, jeu_id INT DEFAULT NULL, image_id INT DEFAULT NULL, description LONGTEXT DEFAULT NULL, INDEX IDX_FB39ADFD8C9E392E (jeu_id), INDEX IDX_FB39ADFD3DA5256D (image_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE jdj_materiel (id INT AUTO_INCREMENT NOT NULL, libelle VARCHAR(50) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE jdj_mechanism (id INT AUTO_INCREMENT NOT NULL, libelle VARCHAR(50) DEFAULT NULL, description LONGTEXT DEFAULT NULL, slug VARCHAR(128) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE jdj_statut_jeu (statut_id INT NOT NULL, ordre INT UNSIGNED NOT NULL, PRIMARY KEY(statut_id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE jdj_theme (id INT AUTO_INCREMENT NOT NULL, libelle VARCHAR(100) NOT NULL, description VARCHAR(50) DEFAULT NULL, slug VARCHAR(128) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE jdj_type_addon (id INT AUTO_INCREMENT NOT NULL, libelle VARCHAR(50) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE jdj_personne (id INT AUTO_INCREMENT NOT NULL, image_id INT DEFAULT NULL, pays_id INT DEFAULT NULL, nom VARCHAR(50) NOT NULL, prenom VARCHAR(50) NOT NULL, siteWeb VARCHAR(200) DEFAULT NULL, description LONGTEXT DEFAULT NULL, slug VARCHAR(128) NOT NULL, INDEX IDX_587FAB243DA5256D (image_id), INDEX IDX_587FAB24A6E44244 (pays_id), INDEX search_idx (slug), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE jdj_personne_image (personne_id INT NOT NULL, image_id INT NOT NULL, INDEX IDX_853CCEA6A21BD112 (personne_id), INDEX IDX_853CCEA63DA5256D (image_id), PRIMARY KEY(personne_id, image_id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE jdj_avatar (id INT AUTO_INCREMENT NOT NULL, path VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE fos_user (id INT AUTO_INCREMENT NOT NULL, avatar_id INT DEFAULT NULL, username VARCHAR(255) NOT NULL, username_canonical VARCHAR(255) NOT NULL, email VARCHAR(255) NOT NULL, email_canonical VARCHAR(255) NOT NULL, enabled TINYINT(1) NOT NULL, salt VARCHAR(255) NOT NULL, password VARCHAR(255) NOT NULL, last_login DATETIME DEFAULT NULL, locked TINYINT(1) NOT NULL, expired TINYINT(1) NOT NULL, expires_at DATETIME DEFAULT NULL, confirmation_token VARCHAR(255) DEFAULT NULL, password_requested_at DATETIME DEFAULT NULL, roles LONGTEXT NOT NULL COMMENT \'(DC2Type:array)\', credentials_expired TINYINT(1) NOT NULL, credentials_expire_at DATETIME DEFAULT NULL, slug VARCHAR(128) NOT NULL, dateNaissance DATETIME DEFAULT NULL, created DATETIME NOT NULL, updated DATETIME NOT NULL, deleted_at DATETIME DEFAULT NULL, presentation LONGTEXT DEFAULT NULL, UNIQUE INDEX UNIQ_957A647992FC23A8 (username_canonical), UNIQUE INDEX UNIQ_957A6479A0D96FBF (email_canonical), INDEX IDX_957A647986383B10 (avatar_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE Comment (id INT AUTO_INCREMENT NOT NULL, thread_id VARCHAR(255) DEFAULT NULL, author_id INT DEFAULT NULL, body LONGTEXT NOT NULL, ancestors VARCHAR(1024) NOT NULL, depth INT NOT NULL, created_at DATETIME NOT NULL, state INT NOT NULL, INDEX IDX_5BC96BF0E2904019 (thread_id), INDEX IDX_5BC96BF0F675F31B (author_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE Thread (id VARCHAR(255) NOT NULL, permalink VARCHAR(255) NOT NULL, is_commentable TINYINT(1) NOT NULL, num_comments INT NOT NULL, last_comment_at DATETIME DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE Vote (id INT AUTO_INCREMENT NOT NULL, comment_id INT DEFAULT NULL, created_at DATETIME NOT NULL, value INT NOT NULL, INDEX IDX_FA222A5AF8697D13 (comment_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE jdj_joueur (id INT AUTO_INCREMENT NOT NULL, partie_id INT NOT NULL, user_id INT DEFAULT NULL, nom VARCHAR(255) DEFAULT NULL, score INT DEFAULT NULL, INDEX IDX_FCC5012CE075F7A4 (partie_id), INDEX IDX_FCC5012CA76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE jdj_partie (id INT AUTO_INCREMENT NOT NULL, author_id INT NOT NULL, jeu_id INT NOT NULL, createdAt DATETIME NOT NULL, updatedAt DATETIME NOT NULL, playedAt DATE NOT NULL, INDEX IDX_42FB7D4F675F31B (author_id), INDEX IDX_42FB7D48C9E392E (jeu_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE jdj_user_partie (partie_id INT NOT NULL, user_id INT NOT NULL, INDEX IDX_169EF7C9E075F7A4 (partie_id), INDEX IDX_169EF7C9A76ED395 (user_id), PRIMARY KEY(partie_id, user_id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE jdj_partie_image (id INT AUTO_INCREMENT NOT NULL, partie_id INT DEFAULT NULL, image_id INT DEFAULT NULL, description LONGTEXT DEFAULT NULL, INDEX IDX_9B15754AE075F7A4 (partie_id), INDEX IDX_9B15754A3DA5256D (image_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE jdj_jeu_note (id INT AUTO_INCREMENT NOT NULL, author_id INT NOT NULL, jeu_id INT NOT NULL, note_id INT NOT NULL, createdAt DATETIME NOT NULL, updatedAt DATETIME NOT NULL, INDEX IDX_A56383F3F675F31B (author_id), INDEX IDX_A56383F38C9E392E (jeu_id), INDEX IDX_A56383F326ED0855 (note_id), INDEX search_idx (jeu_id, author_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE jdj_note (id INT AUTO_INCREMENT NOT NULL, libelle VARCHAR(50) NOT NULL, valeur INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE jdj_user_review (id INT AUTO_INCREMENT NOT NULL, libelle VARCHAR(100) NOT NULL, body LONGTEXT NOT NULL, createdAt DATETIME NOT NULL, updatedAt DATETIME NOT NULL, jeuNote_id INT NOT NULL, UNIQUE INDEX UNIQ_6A466932A3374967 (jeuNote_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE jdj_cible (id INT AUTO_INCREMENT NOT NULL, libelle VARCHAR(100) NOT NULL, description LONGTEXT NOT NULL, slug VARCHAR(128) NOT NULL, INDEX search_idx (slug), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE jdj_image (id INT AUTO_INCREMENT NOT NULL, path VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE jdj_like (id INT AUTO_INCREMENT NOT NULL, comment_id INT DEFAULT NULL, is_like TINYINT(1) NOT NULL, createdAt DATETIME NOT NULL, createdBy_id INT NOT NULL, userReview_id INT DEFAULT NULL, INDEX IDX_C5FBE0653174800F (createdBy_id), INDEX IDX_C5FBE0657292B558 (userReview_id), INDEX IDX_C5FBE065F8697D13 (comment_id), UNIQUE INDEX unique_like (createdBy_id, userReview_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE jdj_collection (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, name VARCHAR(50) NOT NULL, description LONGTEXT DEFAULT NULL, slug VARCHAR(128) NOT NULL, INDEX IDX_77FF3C72A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE jdj_list_element (id INT AUTO_INCREMENT NOT NULL, collection_id INT NOT NULL, jeu_id INT DEFAULT NULL, INDEX IDX_7FC1AED3514956FD (collection_id), INDEX IDX_7FC1AED38C9E392E (jeu_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE jdj_user_game_attribute (id INT AUTO_INCREMENT NOT NULL, user_id INT DEFAULT NULL, jeu_id INT DEFAULT NULL, is_favorite TINYINT(1) DEFAULT NULL, is_owned TINYINT(1) DEFAULT NULL, is_wanted TINYINT(1) DEFAULT NULL, has_played TINYINT(1) DEFAULT NULL, INDEX IDX_143FA0D8A76ED395 (user_id), INDEX IDX_143FA0D88C9E392E (jeu_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE jdj_activity (id INT AUTO_INCREMENT NOT NULL, jeu_id INT DEFAULT NULL, published DATETIME NOT NULL, actionUser_id INT DEFAULT NULL, INDEX IDX_FBC56B9153D03CE1 (actionUser_id), UNIQUE INDEX UNIQ_FBC56B918C9E392E (jeu_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE jdj_user_activity (activity_id INT NOT NULL, user_id INT NOT NULL, INDEX IDX_727554AC81C06096 (activity_id), INDEX IDX_727554ACA76ED395 (user_id), PRIMARY KEY(activity_id, user_id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE jdj_notification (id INT AUTO_INCREMENT NOT NULL, user_id INT DEFAULT NULL, activity_id INT DEFAULT NULL, createdAt DATETIME NOT NULL, comment LONGTEXT DEFAULT NULL, status enum(\'accept\', \'decline\'), isRead TINYINT(1) NOT NULL, changeStatus LONGTEXT DEFAULT NULL, INDEX IDX_5D9A5B2DA76ED395 (user_id), INDEX IDX_5D9A5B2D81C06096 (activity_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE jdj_langue_addon ADD CONSTRAINT FK_5747BA12AADBACD FOREIGN KEY (langue_id) REFERENCES jdj_langue (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE jdj_langue_addon ADD CONSTRAINT FK_5747BA1CC642678 FOREIGN KEY (addon_id) REFERENCES jdj_addon (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE cpta_bill ADD CONSTRAINT FK_151E8E7D9395C3F3 FOREIGN KEY (customer_id) REFERENCES cpta_customer (id)');
        $this->addSql('ALTER TABLE cpta_bill ADD CONSTRAINT FK_151E8E7DF57FBCCC FOREIGN KEY (paymentMethod_id) REFERENCES cpta_payment_method (id)');
        $this->addSql('ALTER TABLE cpta_bill ADD CONSTRAINT FK_151E8E7DA428E2FF FOREIGN KEY (bookEntry_id) REFERENCES cpta_book_entry (id)');
        $this->addSql('ALTER TABLE cpta_bill_product ADD CONSTRAINT FK_D9A28B271A8C12F5 FOREIGN KEY (bill_id) REFERENCES cpta_bill (id)');
        $this->addSql('ALTER TABLE cpta_bill_product ADD CONSTRAINT FK_D9A28B274584665A FOREIGN KEY (product_id) REFERENCES cpta_product (id)');
        $this->addSql('ALTER TABLE cpta_book_entry ADD CONSTRAINT FK_8A6A60D0F57FBCCC FOREIGN KEY (paymentMethod_id) REFERENCES cpta_payment_method (id)');
        $this->addSql('ALTER TABLE cpta_customer ADD CONSTRAINT FK_A129E01EF5B7AF75 FOREIGN KEY (address_id) REFERENCES cpta_address (id)');
        $this->addSql('ALTER TABLE cpta_subscription ADD CONSTRAINT FK_5DEDDF461A8C12F5 FOREIGN KEY (bill_id) REFERENCES cpta_bill (id)');
        $this->addSql('ALTER TABLE cpta_subscription ADD CONSTRAINT FK_5DEDDF464584665A FOREIGN KEY (product_id) REFERENCES cpta_product (id)');
        $this->addSql('ALTER TABLE cpta_subscription ADD CONSTRAINT FK_5DEDDF469395C3F3 FOREIGN KEY (customer_id) REFERENCES cpta_customer (id)');
        $this->addSql('ALTER TABLE jdj_addon ADD CONSTRAINT FK_BE5C7E3E8C9E392E FOREIGN KEY (jeu_id) REFERENCES jdj_jeu (id)');
        $this->addSql('ALTER TABLE jdj_addon ADD CONSTRAINT FK_BE5C7E3E697BCAA6 FOREIGN KEY (typeAddon_id) REFERENCES jdj_type_addon (id)');
        $this->addSql('ALTER TABLE jdj_note_caracteristique ADD CONSTRAINT FK_4A1759A41704EEB7 FOREIGN KEY (caracteristique_id) REFERENCES jdj_caracteristique (id)');
        $this->addSql('ALTER TABLE jdj_jeu ADD CONSTRAINT FK_1C86A526DCE0CBC5 FOREIGN KEY (imageCouverture_id) REFERENCES jdj_image (id)');
        $this->addSql('ALTER TABLE jdj_jeu ADD CONSTRAINT FK_1C86A5261017DE9 FOREIGN KEY (materialImage_id) REFERENCES jdj_image (id)');
        $this->addSql('ALTER TABLE jdj_jeu ADD CONSTRAINT FK_1C86A526A96E5E09 FOREIGN KEY (cible_id) REFERENCES jdj_cible (id)');
        $this->addSql('ALTER TABLE jeu_mechanism ADD CONSTRAINT FK_2821C8478C9E392E FOREIGN KEY (jeu_id) REFERENCES jdj_jeu (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE jeu_mechanism ADD CONSTRAINT FK_2821C84737CD6DD0 FOREIGN KEY (mechanism_id) REFERENCES jdj_mechanism (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE jeu_theme ADD CONSTRAINT FK_A590E4F08C9E392E FOREIGN KEY (jeu_id) REFERENCES jdj_jeu (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE jeu_theme ADD CONSTRAINT FK_A590E4F059027487 FOREIGN KEY (theme_id) REFERENCES jdj_theme (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE jdj_auteur_jeu ADD CONSTRAINT FK_2B8B40448C9E392E FOREIGN KEY (jeu_id) REFERENCES jdj_jeu (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE jdj_auteur_jeu ADD CONSTRAINT FK_2B8B4044A21BD112 FOREIGN KEY (personne_id) REFERENCES jdj_personne (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE jdj_illustrateur_jeu ADD CONSTRAINT FK_DD00AAF8C9E392E FOREIGN KEY (jeu_id) REFERENCES jdj_jeu (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE jdj_illustrateur_jeu ADD CONSTRAINT FK_DD00AAFA21BD112 FOREIGN KEY (personne_id) REFERENCES jdj_personne (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE jdj_editeur_jeu ADD CONSTRAINT FK_D33D730C8C9E392E FOREIGN KEY (jeu_id) REFERENCES jdj_jeu (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE jdj_editeur_jeu ADD CONSTRAINT FK_D33D730CA21BD112 FOREIGN KEY (personne_id) REFERENCES jdj_personne (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE jdj_caracteristique_jeu ADD CONSTRAINT FK_EB44969F1704EEB7 FOREIGN KEY (caracteristique_id) REFERENCES jdj_caracteristique (id)');
        $this->addSql('ALTER TABLE jdj_caracteristique_jeu ADD CONSTRAINT FK_EB44969F8C9E392E FOREIGN KEY (jeu_id) REFERENCES jdj_jeu (id)');
        $this->addSql('ALTER TABLE jdj_caracteristique_jeu ADD CONSTRAINT FK_EB44969F7CAECA0 FOREIGN KEY (caracteristiqueNote_id) REFERENCES jdj_note_caracteristique (id)');
        $this->addSql('ALTER TABLE jdj_jeu_image ADD CONSTRAINT FK_FB39ADFD8C9E392E FOREIGN KEY (jeu_id) REFERENCES jdj_jeu (id)');
        $this->addSql('ALTER TABLE jdj_jeu_image ADD CONSTRAINT FK_FB39ADFD3DA5256D FOREIGN KEY (image_id) REFERENCES jdj_image (id)');
        $this->addSql('ALTER TABLE jdj_statut_jeu ADD CONSTRAINT FK_65195847F6203804 FOREIGN KEY (statut_id) REFERENCES jdj_statut (id)');
        $this->addSql('ALTER TABLE jdj_personne ADD CONSTRAINT FK_587FAB243DA5256D FOREIGN KEY (image_id) REFERENCES jdj_image (id)');
        $this->addSql('ALTER TABLE jdj_personne ADD CONSTRAINT FK_587FAB24A6E44244 FOREIGN KEY (pays_id) REFERENCES jdj_pays (id)');
        $this->addSql('ALTER TABLE jdj_personne_image ADD CONSTRAINT FK_853CCEA6A21BD112 FOREIGN KEY (personne_id) REFERENCES jdj_personne (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE jdj_personne_image ADD CONSTRAINT FK_853CCEA63DA5256D FOREIGN KEY (image_id) REFERENCES jdj_image (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE fos_user ADD CONSTRAINT FK_957A647986383B10 FOREIGN KEY (avatar_id) REFERENCES jdj_avatar (id)');
        $this->addSql('ALTER TABLE Comment ADD CONSTRAINT FK_5BC96BF0E2904019 FOREIGN KEY (thread_id) REFERENCES Thread (id)');
        $this->addSql('ALTER TABLE Comment ADD CONSTRAINT FK_5BC96BF0F675F31B FOREIGN KEY (author_id) REFERENCES fos_user (id)');
        $this->addSql('ALTER TABLE Vote ADD CONSTRAINT FK_FA222A5AF8697D13 FOREIGN KEY (comment_id) REFERENCES Comment (id)');
        $this->addSql('ALTER TABLE jdj_joueur ADD CONSTRAINT FK_FCC5012CE075F7A4 FOREIGN KEY (partie_id) REFERENCES jdj_partie (id)');
        $this->addSql('ALTER TABLE jdj_joueur ADD CONSTRAINT FK_FCC5012CA76ED395 FOREIGN KEY (user_id) REFERENCES fos_user (id)');
        $this->addSql('ALTER TABLE jdj_partie ADD CONSTRAINT FK_42FB7D4F675F31B FOREIGN KEY (author_id) REFERENCES fos_user (id)');
        $this->addSql('ALTER TABLE jdj_partie ADD CONSTRAINT FK_42FB7D48C9E392E FOREIGN KEY (jeu_id) REFERENCES jdj_jeu (id)');
        $this->addSql('ALTER TABLE jdj_user_partie ADD CONSTRAINT FK_169EF7C9E075F7A4 FOREIGN KEY (partie_id) REFERENCES jdj_partie (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE jdj_user_partie ADD CONSTRAINT FK_169EF7C9A76ED395 FOREIGN KEY (user_id) REFERENCES fos_user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE jdj_partie_image ADD CONSTRAINT FK_9B15754AE075F7A4 FOREIGN KEY (partie_id) REFERENCES jdj_partie (id)');
        $this->addSql('ALTER TABLE jdj_partie_image ADD CONSTRAINT FK_9B15754A3DA5256D FOREIGN KEY (image_id) REFERENCES jdj_image (id)');
        $this->addSql('ALTER TABLE jdj_jeu_note ADD CONSTRAINT FK_A56383F3F675F31B FOREIGN KEY (author_id) REFERENCES fos_user (id)');
        $this->addSql('ALTER TABLE jdj_jeu_note ADD CONSTRAINT FK_A56383F38C9E392E FOREIGN KEY (jeu_id) REFERENCES jdj_jeu (id)');
        $this->addSql('ALTER TABLE jdj_jeu_note ADD CONSTRAINT FK_A56383F326ED0855 FOREIGN KEY (note_id) REFERENCES jdj_note (id)');
        $this->addSql('ALTER TABLE jdj_user_review ADD CONSTRAINT FK_6A466932A3374967 FOREIGN KEY (jeuNote_id) REFERENCES jdj_jeu_note (id)');
        $this->addSql('ALTER TABLE jdj_like ADD CONSTRAINT FK_C5FBE0653174800F FOREIGN KEY (createdBy_id) REFERENCES fos_user (id)');
        $this->addSql('ALTER TABLE jdj_like ADD CONSTRAINT FK_C5FBE0657292B558 FOREIGN KEY (userReview_id) REFERENCES jdj_user_review (id)');
        $this->addSql('ALTER TABLE jdj_like ADD CONSTRAINT FK_C5FBE065F8697D13 FOREIGN KEY (comment_id) REFERENCES Comment (id)');
        $this->addSql('ALTER TABLE jdj_collection ADD CONSTRAINT FK_77FF3C72A76ED395 FOREIGN KEY (user_id) REFERENCES fos_user (id)');
        $this->addSql('ALTER TABLE jdj_list_element ADD CONSTRAINT FK_7FC1AED3514956FD FOREIGN KEY (collection_id) REFERENCES jdj_collection (id)');
        $this->addSql('ALTER TABLE jdj_list_element ADD CONSTRAINT FK_7FC1AED38C9E392E FOREIGN KEY (jeu_id) REFERENCES jdj_jeu (id)');
        $this->addSql('ALTER TABLE jdj_user_game_attribute ADD CONSTRAINT FK_143FA0D8A76ED395 FOREIGN KEY (user_id) REFERENCES fos_user (id)');
        $this->addSql('ALTER TABLE jdj_user_game_attribute ADD CONSTRAINT FK_143FA0D88C9E392E FOREIGN KEY (jeu_id) REFERENCES jdj_jeu (id)');
        $this->addSql('ALTER TABLE jdj_activity ADD CONSTRAINT FK_FBC56B9153D03CE1 FOREIGN KEY (actionUser_id) REFERENCES fos_user (id)');
        $this->addSql('ALTER TABLE jdj_activity ADD CONSTRAINT FK_FBC56B918C9E392E FOREIGN KEY (jeu_id) REFERENCES jdj_jeu (id)');
        $this->addSql('ALTER TABLE jdj_user_activity ADD CONSTRAINT FK_727554AC81C06096 FOREIGN KEY (activity_id) REFERENCES jdj_activity (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE jdj_user_activity ADD CONSTRAINT FK_727554ACA76ED395 FOREIGN KEY (user_id) REFERENCES fos_user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE jdj_notification ADD CONSTRAINT FK_5D9A5B2DA76ED395 FOREIGN KEY (user_id) REFERENCES fos_user (id)');
        $this->addSql('ALTER TABLE jdj_notification ADD CONSTRAINT FK_5D9A5B2D81C06096 FOREIGN KEY (activity_id) REFERENCES jdj_activity (id)');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE jdj_langue_addon DROP FOREIGN KEY FK_5747BA12AADBACD');
        $this->addSql('ALTER TABLE jdj_personne DROP FOREIGN KEY FK_587FAB24A6E44244');
        $this->addSql('ALTER TABLE jdj_statut_jeu DROP FOREIGN KEY FK_65195847F6203804');
        $this->addSql('ALTER TABLE cpta_customer DROP FOREIGN KEY FK_A129E01EF5B7AF75');
        $this->addSql('ALTER TABLE cpta_bill_product DROP FOREIGN KEY FK_D9A28B271A8C12F5');
        $this->addSql('ALTER TABLE cpta_subscription DROP FOREIGN KEY FK_5DEDDF461A8C12F5');
        $this->addSql('ALTER TABLE cpta_bill DROP FOREIGN KEY FK_151E8E7DA428E2FF');
        $this->addSql('ALTER TABLE cpta_bill DROP FOREIGN KEY FK_151E8E7D9395C3F3');
        $this->addSql('ALTER TABLE cpta_subscription DROP FOREIGN KEY FK_5DEDDF469395C3F3');
        $this->addSql('ALTER TABLE cpta_bill DROP FOREIGN KEY FK_151E8E7DF57FBCCC');
        $this->addSql('ALTER TABLE cpta_book_entry DROP FOREIGN KEY FK_8A6A60D0F57FBCCC');
        $this->addSql('ALTER TABLE cpta_bill_product DROP FOREIGN KEY FK_D9A28B274584665A');
        $this->addSql('ALTER TABLE cpta_subscription DROP FOREIGN KEY FK_5DEDDF464584665A');
        $this->addSql('ALTER TABLE jdj_langue_addon DROP FOREIGN KEY FK_5747BA1CC642678');
        $this->addSql('ALTER TABLE jdj_note_caracteristique DROP FOREIGN KEY FK_4A1759A41704EEB7');
        $this->addSql('ALTER TABLE jdj_caracteristique_jeu DROP FOREIGN KEY FK_EB44969F1704EEB7');
        $this->addSql('ALTER TABLE jdj_caracteristique_jeu DROP FOREIGN KEY FK_EB44969F7CAECA0');
        $this->addSql('ALTER TABLE jdj_addon DROP FOREIGN KEY FK_BE5C7E3E8C9E392E');
        $this->addSql('ALTER TABLE jeu_mechanism DROP FOREIGN KEY FK_2821C8478C9E392E');
        $this->addSql('ALTER TABLE jeu_theme DROP FOREIGN KEY FK_A590E4F08C9E392E');
        $this->addSql('ALTER TABLE jdj_auteur_jeu DROP FOREIGN KEY FK_2B8B40448C9E392E');
        $this->addSql('ALTER TABLE jdj_illustrateur_jeu DROP FOREIGN KEY FK_DD00AAF8C9E392E');
        $this->addSql('ALTER TABLE jdj_editeur_jeu DROP FOREIGN KEY FK_D33D730C8C9E392E');
        $this->addSql('ALTER TABLE jdj_caracteristique_jeu DROP FOREIGN KEY FK_EB44969F8C9E392E');
        $this->addSql('ALTER TABLE jdj_jeu_image DROP FOREIGN KEY FK_FB39ADFD8C9E392E');
        $this->addSql('ALTER TABLE jdj_partie DROP FOREIGN KEY FK_42FB7D48C9E392E');
        $this->addSql('ALTER TABLE jdj_jeu_note DROP FOREIGN KEY FK_A56383F38C9E392E');
        $this->addSql('ALTER TABLE jdj_list_element DROP FOREIGN KEY FK_7FC1AED38C9E392E');
        $this->addSql('ALTER TABLE jdj_user_game_attribute DROP FOREIGN KEY FK_143FA0D88C9E392E');
        $this->addSql('ALTER TABLE jdj_activity DROP FOREIGN KEY FK_FBC56B918C9E392E');
        $this->addSql('ALTER TABLE jeu_mechanism DROP FOREIGN KEY FK_2821C84737CD6DD0');
        $this->addSql('ALTER TABLE jeu_theme DROP FOREIGN KEY FK_A590E4F059027487');
        $this->addSql('ALTER TABLE jdj_addon DROP FOREIGN KEY FK_BE5C7E3E697BCAA6');
        $this->addSql('ALTER TABLE jdj_auteur_jeu DROP FOREIGN KEY FK_2B8B4044A21BD112');
        $this->addSql('ALTER TABLE jdj_illustrateur_jeu DROP FOREIGN KEY FK_DD00AAFA21BD112');
        $this->addSql('ALTER TABLE jdj_editeur_jeu DROP FOREIGN KEY FK_D33D730CA21BD112');
        $this->addSql('ALTER TABLE jdj_personne_image DROP FOREIGN KEY FK_853CCEA6A21BD112');
        $this->addSql('ALTER TABLE fos_user DROP FOREIGN KEY FK_957A647986383B10');
        $this->addSql('ALTER TABLE Comment DROP FOREIGN KEY FK_5BC96BF0F675F31B');
        $this->addSql('ALTER TABLE jdj_joueur DROP FOREIGN KEY FK_FCC5012CA76ED395');
        $this->addSql('ALTER TABLE jdj_partie DROP FOREIGN KEY FK_42FB7D4F675F31B');
        $this->addSql('ALTER TABLE jdj_user_partie DROP FOREIGN KEY FK_169EF7C9A76ED395');
        $this->addSql('ALTER TABLE jdj_jeu_note DROP FOREIGN KEY FK_A56383F3F675F31B');
        $this->addSql('ALTER TABLE jdj_like DROP FOREIGN KEY FK_C5FBE0653174800F');
        $this->addSql('ALTER TABLE jdj_collection DROP FOREIGN KEY FK_77FF3C72A76ED395');
        $this->addSql('ALTER TABLE jdj_user_game_attribute DROP FOREIGN KEY FK_143FA0D8A76ED395');
        $this->addSql('ALTER TABLE jdj_activity DROP FOREIGN KEY FK_FBC56B9153D03CE1');
        $this->addSql('ALTER TABLE jdj_user_activity DROP FOREIGN KEY FK_727554ACA76ED395');
        $this->addSql('ALTER TABLE jdj_notification DROP FOREIGN KEY FK_5D9A5B2DA76ED395');
        $this->addSql('ALTER TABLE Vote DROP FOREIGN KEY FK_FA222A5AF8697D13');
        $this->addSql('ALTER TABLE jdj_like DROP FOREIGN KEY FK_C5FBE065F8697D13');
        $this->addSql('ALTER TABLE Comment DROP FOREIGN KEY FK_5BC96BF0E2904019');
        $this->addSql('ALTER TABLE jdj_joueur DROP FOREIGN KEY FK_FCC5012CE075F7A4');
        $this->addSql('ALTER TABLE jdj_user_partie DROP FOREIGN KEY FK_169EF7C9E075F7A4');
        $this->addSql('ALTER TABLE jdj_partie_image DROP FOREIGN KEY FK_9B15754AE075F7A4');
        $this->addSql('ALTER TABLE jdj_user_review DROP FOREIGN KEY FK_6A466932A3374967');
        $this->addSql('ALTER TABLE jdj_jeu_note DROP FOREIGN KEY FK_A56383F326ED0855');
        $this->addSql('ALTER TABLE jdj_like DROP FOREIGN KEY FK_C5FBE0657292B558');
        $this->addSql('ALTER TABLE jdj_jeu DROP FOREIGN KEY FK_1C86A526A96E5E09');
        $this->addSql('ALTER TABLE jdj_jeu DROP FOREIGN KEY FK_1C86A526DCE0CBC5');
        $this->addSql('ALTER TABLE jdj_jeu DROP FOREIGN KEY FK_1C86A5261017DE9');
        $this->addSql('ALTER TABLE jdj_jeu_image DROP FOREIGN KEY FK_FB39ADFD3DA5256D');
        $this->addSql('ALTER TABLE jdj_personne DROP FOREIGN KEY FK_587FAB243DA5256D');
        $this->addSql('ALTER TABLE jdj_personne_image DROP FOREIGN KEY FK_853CCEA63DA5256D');
        $this->addSql('ALTER TABLE jdj_partie_image DROP FOREIGN KEY FK_9B15754A3DA5256D');
        $this->addSql('ALTER TABLE jdj_list_element DROP FOREIGN KEY FK_7FC1AED3514956FD');
        $this->addSql('ALTER TABLE jdj_user_activity DROP FOREIGN KEY FK_727554AC81C06096');
        $this->addSql('ALTER TABLE jdj_notification DROP FOREIGN KEY FK_5D9A5B2D81C06096');
        $this->addSql('DROP TABLE ext_translations');
        $this->addSql('DROP TABLE ext_log_entries');
        $this->addSql('DROP TABLE jdj_langue');
        $this->addSql('DROP TABLE jdj_langue_addon');
        $this->addSql('DROP TABLE jdj_pays');
        $this->addSql('DROP TABLE jdj_statut');
        $this->addSql('DROP TABLE cpta_address');
        $this->addSql('DROP TABLE cpta_bill');
        $this->addSql('DROP TABLE cpta_bill_product');
        $this->addSql('DROP TABLE cpta_book_entry');
        $this->addSql('DROP TABLE cpta_customer');
        $this->addSql('DROP TABLE cpta_payment_method');
        $this->addSql('DROP TABLE cpta_product');
        $this->addSql('DROP TABLE cpta_subscription');
        $this->addSql('DROP TABLE jdj_addon');
        $this->addSql('DROP TABLE jdj_caracteristique');
        $this->addSql('DROP TABLE jdj_note_caracteristique');
        $this->addSql('DROP TABLE jdj_jeu');
        $this->addSql('DROP TABLE jeu_mechanism');
        $this->addSql('DROP TABLE jeu_theme');
        $this->addSql('DROP TABLE jdj_auteur_jeu');
        $this->addSql('DROP TABLE jdj_illustrateur_jeu');
        $this->addSql('DROP TABLE jdj_editeur_jeu');
        $this->addSql('DROP TABLE jdj_caracteristique_jeu');
        $this->addSql('DROP TABLE jdj_jeu_image');
        $this->addSql('DROP TABLE jdj_materiel');
        $this->addSql('DROP TABLE jdj_mechanism');
        $this->addSql('DROP TABLE jdj_statut_jeu');
        $this->addSql('DROP TABLE jdj_theme');
        $this->addSql('DROP TABLE jdj_type_addon');
        $this->addSql('DROP TABLE jdj_personne');
        $this->addSql('DROP TABLE jdj_personne_image');
        $this->addSql('DROP TABLE jdj_avatar');
        $this->addSql('DROP TABLE fos_user');
        $this->addSql('DROP TABLE Comment');
        $this->addSql('DROP TABLE Thread');
        $this->addSql('DROP TABLE Vote');
        $this->addSql('DROP TABLE jdj_joueur');
        $this->addSql('DROP TABLE jdj_partie');
        $this->addSql('DROP TABLE jdj_user_partie');
        $this->addSql('DROP TABLE jdj_partie_image');
        $this->addSql('DROP TABLE jdj_jeu_note');
        $this->addSql('DROP TABLE jdj_note');
        $this->addSql('DROP TABLE jdj_user_review');
        $this->addSql('DROP TABLE jdj_cible');
        $this->addSql('DROP TABLE jdj_image');
        $this->addSql('DROP TABLE jdj_like');
        $this->addSql('DROP TABLE jdj_collection');
        $this->addSql('DROP TABLE jdj_list_element');
        $this->addSql('DROP TABLE jdj_user_game_attribute');
        $this->addSql('DROP TABLE jdj_activity');
        $this->addSql('DROP TABLE jdj_user_activity');
        $this->addSql('DROP TABLE jdj_notification');
    }
}
