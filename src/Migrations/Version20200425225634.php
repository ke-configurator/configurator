<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200425225634 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE calculation (id INT AUTO_INCREMENT NOT NULL, spread_sheet_id INT NOT NULL, calculation_group_id INT NOT NULL, created_by INT DEFAULT NULL, updated_by INT DEFAULT NULL, title VARCHAR(255) NOT NULL, description LONGTEXT DEFAULT NULL, is_active TINYINT(1) NOT NULL, created DATETIME NOT NULL, updated DATETIME NOT NULL, deleted_at DATETIME DEFAULT NULL, INDEX IDX_F6A769704A496839 (spread_sheet_id), INDEX IDX_F6A76970AE54F386 (calculation_group_id), INDEX IDX_F6A76970DE12AB56 (created_by), INDEX IDX_F6A7697016FE72E1 (updated_by), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE calculation_group (id INT AUTO_INCREMENT NOT NULL, title VARCHAR(255) NOT NULL, description LONGTEXT DEFAULT NULL, position INT DEFAULT NULL, is_active TINYINT(1) NOT NULL, created DATETIME NOT NULL, updated DATETIME NOT NULL, deleted_at DATETIME DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user_group (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(180) NOT NULL, roles LONGTEXT NOT NULL COMMENT \'(DC2Type:array)\', UNIQUE INDEX UNIQ_8F02BF9D5E237E06 (name), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user_profile (id INT AUTO_INCREMENT NOT NULL, firstname VARCHAR(50) NOT NULL, lastname VARCHAR(50) NOT NULL, phone VARCHAR(15) DEFAULT NULL, website VARCHAR(100) DEFAULT NULL, company VARCHAR(100) DEFAULT NULL, language VARCHAR(3) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE spread_sheet (id INT AUTO_INCREMENT NOT NULL, created_by INT DEFAULT NULL, updated_by INT DEFAULT NULL, name VARCHAR(255) NOT NULL, uid VARCHAR(255) NOT NULL, status VARCHAR(20) NOT NULL, is_active TINYINT(1) NOT NULL, created DATETIME NOT NULL, updated DATETIME NOT NULL, deleted_at DATETIME DEFAULT NULL, INDEX IDX_ED1542D8DE12AB56 (created_by), INDEX IDX_ED1542D816FE72E1 (updated_by), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, profile_id INT DEFAULT NULL, password VARCHAR(98) NOT NULL, email VARCHAR(60) NOT NULL, is_active TINYINT(1) NOT NULL, is_freeze TINYINT(1) NOT NULL, last_login DATETIME DEFAULT NULL, last_login_ip VARCHAR(32) DEFAULT NULL, confirmation_token VARCHAR(180) DEFAULT NULL, password_requested_at DATETIME DEFAULT NULL, created_at DATETIME DEFAULT NULL, roles LONGTEXT NOT NULL COMMENT \'(DC2Type:array)\', UNIQUE INDEX UNIQ_8D93D649E7927C74 (email), UNIQUE INDEX UNIQ_8D93D649C05FB297 (confirmation_token), UNIQUE INDEX UNIQ_8D93D649CCFA12B8 (profile_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user_group_tax (user_id INT NOT NULL, group_id INT NOT NULL, INDEX IDX_DF3DFEBA76ED395 (user_id), INDEX IDX_DF3DFEBFE54D947 (group_id), PRIMARY KEY(user_id, group_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE ext_translations (id INT AUTO_INCREMENT NOT NULL, locale VARCHAR(8) NOT NULL, object_class VARCHAR(255) NOT NULL, field VARCHAR(32) NOT NULL, foreign_key VARCHAR(64) NOT NULL, content LONGTEXT DEFAULT NULL, INDEX translations_lookup_idx (locale, object_class, foreign_key), UNIQUE INDEX lookup_unique_idx (locale, object_class, field, foreign_key), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB ROW_FORMAT = DYNAMIC');
        $this->addSql('CREATE TABLE ext_log_entries (id INT AUTO_INCREMENT NOT NULL, action VARCHAR(8) NOT NULL, logged_at DATETIME NOT NULL, object_id VARCHAR(64) DEFAULT NULL, object_class VARCHAR(255) NOT NULL, version INT NOT NULL, data LONGTEXT DEFAULT NULL COMMENT \'(DC2Type:array)\', username VARCHAR(255) DEFAULT NULL, INDEX log_class_lookup_idx (object_class), INDEX log_date_lookup_idx (logged_at), INDEX log_user_lookup_idx (username), INDEX log_version_lookup_idx (object_id, object_class, version), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB ROW_FORMAT = DYNAMIC');
        $this->addSql('ALTER TABLE calculation ADD CONSTRAINT FK_F6A769704A496839 FOREIGN KEY (spread_sheet_id) REFERENCES spread_sheet (id)');
        $this->addSql('ALTER TABLE calculation ADD CONSTRAINT FK_F6A76970AE54F386 FOREIGN KEY (calculation_group_id) REFERENCES calculation_group (id)');
        $this->addSql('ALTER TABLE calculation ADD CONSTRAINT FK_F6A76970DE12AB56 FOREIGN KEY (created_by) REFERENCES user (id)');
        $this->addSql('ALTER TABLE calculation ADD CONSTRAINT FK_F6A7697016FE72E1 FOREIGN KEY (updated_by) REFERENCES user (id)');
        $this->addSql('ALTER TABLE spread_sheet ADD CONSTRAINT FK_ED1542D8DE12AB56 FOREIGN KEY (created_by) REFERENCES user (id)');
        $this->addSql('ALTER TABLE spread_sheet ADD CONSTRAINT FK_ED1542D816FE72E1 FOREIGN KEY (updated_by) REFERENCES user (id)');
        $this->addSql('ALTER TABLE user ADD CONSTRAINT FK_8D93D649CCFA12B8 FOREIGN KEY (profile_id) REFERENCES user_profile (id)');
        $this->addSql('ALTER TABLE user_group_tax ADD CONSTRAINT FK_DF3DFEBA76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE user_group_tax ADD CONSTRAINT FK_DF3DFEBFE54D947 FOREIGN KEY (group_id) REFERENCES user_group (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE calculation DROP FOREIGN KEY FK_F6A76970AE54F386');
        $this->addSql('ALTER TABLE user_group_tax DROP FOREIGN KEY FK_DF3DFEBFE54D947');
        $this->addSql('ALTER TABLE user DROP FOREIGN KEY FK_8D93D649CCFA12B8');
        $this->addSql('ALTER TABLE calculation DROP FOREIGN KEY FK_F6A769704A496839');
        $this->addSql('ALTER TABLE calculation DROP FOREIGN KEY FK_F6A76970DE12AB56');
        $this->addSql('ALTER TABLE calculation DROP FOREIGN KEY FK_F6A7697016FE72E1');
        $this->addSql('ALTER TABLE spread_sheet DROP FOREIGN KEY FK_ED1542D8DE12AB56');
        $this->addSql('ALTER TABLE spread_sheet DROP FOREIGN KEY FK_ED1542D816FE72E1');
        $this->addSql('ALTER TABLE user_group_tax DROP FOREIGN KEY FK_DF3DFEBA76ED395');
        $this->addSql('DROP TABLE calculation');
        $this->addSql('DROP TABLE calculation_group');
        $this->addSql('DROP TABLE user_group');
        $this->addSql('DROP TABLE user_profile');
        $this->addSql('DROP TABLE spread_sheet');
        $this->addSql('DROP TABLE user');
        $this->addSql('DROP TABLE user_group_tax');
        $this->addSql('DROP TABLE ext_translations');
        $this->addSql('DROP TABLE ext_log_entries');
    }
}
