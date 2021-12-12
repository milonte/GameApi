<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20211212175034 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE physical_container (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE physical_content (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE physical_support (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE platform_base_content (id INT AUTO_INCREMENT NOT NULL, physical_support_id INT NOT NULL, physical_container_id INT DEFAULT NULL, INDEX IDX_823E188090E46CFD (physical_support_id), INDEX IDX_823E1880E84C55E0 (physical_container_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE platform_base_content_physical_content (platform_base_content_id INT NOT NULL, physical_content_id INT NOT NULL, INDEX IDX_2FCD15E2C40E5BC0 (platform_base_content_id), INDEX IDX_2FCD15E217517B15 (physical_content_id), PRIMARY KEY(platform_base_content_id, physical_content_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE platform_base_content ADD CONSTRAINT FK_823E188090E46CFD FOREIGN KEY (physical_support_id) REFERENCES physical_support (id)');
        $this->addSql('ALTER TABLE platform_base_content ADD CONSTRAINT FK_823E1880E84C55E0 FOREIGN KEY (physical_container_id) REFERENCES physical_container (id)');
        $this->addSql('ALTER TABLE platform_base_content_physical_content ADD CONSTRAINT FK_2FCD15E2C40E5BC0 FOREIGN KEY (platform_base_content_id) REFERENCES platform_base_content (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE platform_base_content_physical_content ADD CONSTRAINT FK_2FCD15E217517B15 FOREIGN KEY (physical_content_id) REFERENCES physical_content (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE game CHANGE isbn isbn VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE game RENAME INDEX idx_232b318cff0eb3ec TO IDX_232B318CA237DE13');
        $this->addSql('ALTER TABLE game_data RENAME INDEX idx_dcf9fbe2922726e9 TO IDX_46E69F4F922726E9');
        $this->addSql('ALTER TABLE game_data_platform RENAME INDEX idx_35499784ff0eb3ec TO IDX_5968857CA237DE13');
        $this->addSql('ALTER TABLE game_data_platform RENAME INDEX idx_35499784ffe6496f TO IDX_5968857CFFE6496F');
        $this->addSql('ALTER TABLE game_data_developer RENAME INDEX idx_8394ccdcff0eb3ec TO IDX_309E97E0A237DE13');
        $this->addSql('ALTER TABLE game_data_developer RENAME INDEX idx_8394ccdc64dd9267 TO IDX_309E97E064DD9267');
        $this->addSql('ALTER TABLE game_data_publisher RENAME INDEX idx_7a879200ff0eb3ec TO IDX_C98DC93CA237DE13');
        $this->addSql('ALTER TABLE game_data_publisher RENAME INDEX idx_7a87920040c86fce TO IDX_C98DC93C40C86FCE');
        $this->addSql('ALTER TABLE game_data_tag RENAME INDEX idx_c11e7ff0ff0eb3ec TO IDX_EC41A910A237DE13');
        $this->addSql('ALTER TABLE game_data_tag RENAME INDEX idx_c11e7ff0bad26311 TO IDX_EC41A910BAD26311');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE platform_base_content DROP FOREIGN KEY FK_823E1880E84C55E0');
        $this->addSql('ALTER TABLE platform_base_content_physical_content DROP FOREIGN KEY FK_2FCD15E217517B15');
        $this->addSql('ALTER TABLE platform_base_content DROP FOREIGN KEY FK_823E188090E46CFD');
        $this->addSql('ALTER TABLE platform_base_content_physical_content DROP FOREIGN KEY FK_2FCD15E2C40E5BC0');
        $this->addSql('DROP TABLE physical_container');
        $this->addSql('DROP TABLE physical_content');
        $this->addSql('DROP TABLE physical_support');
        $this->addSql('DROP TABLE platform_base_content');
        $this->addSql('DROP TABLE platform_base_content_physical_content');
        $this->addSql('ALTER TABLE game CHANGE isbn isbn VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE game RENAME INDEX idx_232b318ca237de13 TO IDX_232B318CFF0EB3EC');
        $this->addSql('ALTER TABLE game_data RENAME INDEX idx_46e69f4f922726e9 TO IDX_DCF9FBE2922726E9');
        $this->addSql('ALTER TABLE game_data_developer RENAME INDEX idx_309e97e064dd9267 TO IDX_8394CCDC64DD9267');
        $this->addSql('ALTER TABLE game_data_developer RENAME INDEX idx_309e97e0a237de13 TO IDX_8394CCDCFF0EB3EC');
        $this->addSql('ALTER TABLE game_data_platform RENAME INDEX idx_5968857ca237de13 TO IDX_35499784FF0EB3EC');
        $this->addSql('ALTER TABLE game_data_platform RENAME INDEX idx_5968857cffe6496f TO IDX_35499784FFE6496F');
        $this->addSql('ALTER TABLE game_data_publisher RENAME INDEX idx_c98dc93c40c86fce TO IDX_7A87920040C86FCE');
        $this->addSql('ALTER TABLE game_data_publisher RENAME INDEX idx_c98dc93ca237de13 TO IDX_7A879200FF0EB3EC');
        $this->addSql('ALTER TABLE game_data_tag RENAME INDEX idx_ec41a910bad26311 TO IDX_C11E7FF0BAD26311');
        $this->addSql('ALTER TABLE game_data_tag RENAME INDEX idx_ec41a910a237de13 TO IDX_C11E7FF0FF0EB3EC');
    }
}
