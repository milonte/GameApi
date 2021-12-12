<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20211212135220 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE cover_object (id INT AUTO_INCREMENT NOT NULL, file_path VARCHAR(255) DEFAULT NULL, slug VARCHAR(255) NOT NULL, updated_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE developer (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE game (id INT AUTO_INCREMENT NOT NULL, cover_id INT DEFAULT NULL, game_infos_id INT DEFAULT NULL, platform_id INT DEFAULT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', updated_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', release_date DATE DEFAULT NULL, INDEX IDX_232B318C922726E9 (cover_id), INDEX IDX_232B318CFF0EB3EC (game_infos_id), INDEX IDX_232B318CFFE6496F (platform_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE game_developer (game_id INT NOT NULL, developer_id INT NOT NULL, INDEX IDX_B75D4A98E48FD905 (game_id), INDEX IDX_B75D4A9864DD9267 (developer_id), PRIMARY KEY(game_id, developer_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE game_publisher (game_id INT NOT NULL, publisher_id INT NOT NULL, INDEX IDX_4E4E1444E48FD905 (game_id), INDEX IDX_4E4E144440C86FCE (publisher_id), PRIMARY KEY(game_id, publisher_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE game_tag (game_id INT NOT NULL, tag_id INT NOT NULL, INDEX IDX_18D3A446E48FD905 (game_id), INDEX IDX_18D3A446BAD26311 (tag_id), PRIMARY KEY(game_id, tag_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE game_infos (id INT AUTO_INCREMENT NOT NULL, cover_id INT DEFAULT NULL, title VARCHAR(255) NOT NULL, description LONGTEXT DEFAULT NULL, INDEX IDX_DCF9FBE2922726E9 (cover_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE game_infos_platform (game_infos_id INT NOT NULL, platform_id INT NOT NULL, INDEX IDX_35499784FF0EB3EC (game_infos_id), INDEX IDX_35499784FFE6496F (platform_id), PRIMARY KEY(game_infos_id, platform_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE game_infos_developer (game_infos_id INT NOT NULL, developer_id INT NOT NULL, INDEX IDX_8394CCDCFF0EB3EC (game_infos_id), INDEX IDX_8394CCDC64DD9267 (developer_id), PRIMARY KEY(game_infos_id, developer_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE game_infos_publisher (game_infos_id INT NOT NULL, publisher_id INT NOT NULL, INDEX IDX_7A879200FF0EB3EC (game_infos_id), INDEX IDX_7A87920040C86FCE (publisher_id), PRIMARY KEY(game_infos_id, publisher_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE game_infos_tag (game_infos_id INT NOT NULL, tag_id INT NOT NULL, INDEX IDX_C11E7FF0FF0EB3EC (game_infos_id), INDEX IDX_C11E7FF0BAD26311 (tag_id), PRIMARY KEY(game_infos_id, tag_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE games_collection (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, game_id INT DEFAULT NULL, INDEX IDX_5C684131A76ED395 (user_id), INDEX IDX_5C684131E48FD905 (game_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE platform (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE publisher (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE tag (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(180) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_8D93D649E7927C74 (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE game ADD CONSTRAINT FK_232B318C922726E9 FOREIGN KEY (cover_id) REFERENCES cover_object (id)');
        $this->addSql('ALTER TABLE game ADD CONSTRAINT FK_232B318CFF0EB3EC FOREIGN KEY (game_infos_id) REFERENCES game_infos (id)');
        $this->addSql('ALTER TABLE game ADD CONSTRAINT FK_232B318CFFE6496F FOREIGN KEY (platform_id) REFERENCES platform (id)');
        $this->addSql('ALTER TABLE game_developer ADD CONSTRAINT FK_B75D4A98E48FD905 FOREIGN KEY (game_id) REFERENCES game (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE game_developer ADD CONSTRAINT FK_B75D4A9864DD9267 FOREIGN KEY (developer_id) REFERENCES developer (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE game_publisher ADD CONSTRAINT FK_4E4E1444E48FD905 FOREIGN KEY (game_id) REFERENCES game (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE game_publisher ADD CONSTRAINT FK_4E4E144440C86FCE FOREIGN KEY (publisher_id) REFERENCES publisher (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE game_tag ADD CONSTRAINT FK_18D3A446E48FD905 FOREIGN KEY (game_id) REFERENCES game (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE game_tag ADD CONSTRAINT FK_18D3A446BAD26311 FOREIGN KEY (tag_id) REFERENCES tag (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE game_infos ADD CONSTRAINT FK_DCF9FBE2922726E9 FOREIGN KEY (cover_id) REFERENCES cover_object (id)');
        $this->addSql('ALTER TABLE game_infos_platform ADD CONSTRAINT FK_35499784FF0EB3EC FOREIGN KEY (game_infos_id) REFERENCES game_infos (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE game_infos_platform ADD CONSTRAINT FK_35499784FFE6496F FOREIGN KEY (platform_id) REFERENCES platform (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE game_infos_developer ADD CONSTRAINT FK_8394CCDCFF0EB3EC FOREIGN KEY (game_infos_id) REFERENCES game_infos (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE game_infos_developer ADD CONSTRAINT FK_8394CCDC64DD9267 FOREIGN KEY (developer_id) REFERENCES developer (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE game_infos_publisher ADD CONSTRAINT FK_7A879200FF0EB3EC FOREIGN KEY (game_infos_id) REFERENCES game_infos (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE game_infos_publisher ADD CONSTRAINT FK_7A87920040C86FCE FOREIGN KEY (publisher_id) REFERENCES publisher (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE game_infos_tag ADD CONSTRAINT FK_C11E7FF0FF0EB3EC FOREIGN KEY (game_infos_id) REFERENCES game_infos (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE game_infos_tag ADD CONSTRAINT FK_C11E7FF0BAD26311 FOREIGN KEY (tag_id) REFERENCES tag (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE games_collection ADD CONSTRAINT FK_5C684131A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE games_collection ADD CONSTRAINT FK_5C684131E48FD905 FOREIGN KEY (game_id) REFERENCES game (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE game DROP FOREIGN KEY FK_232B318C922726E9');
        $this->addSql('ALTER TABLE game_infos DROP FOREIGN KEY FK_DCF9FBE2922726E9');
        $this->addSql('ALTER TABLE game_developer DROP FOREIGN KEY FK_B75D4A9864DD9267');
        $this->addSql('ALTER TABLE game_infos_developer DROP FOREIGN KEY FK_8394CCDC64DD9267');
        $this->addSql('ALTER TABLE game_developer DROP FOREIGN KEY FK_B75D4A98E48FD905');
        $this->addSql('ALTER TABLE game_publisher DROP FOREIGN KEY FK_4E4E1444E48FD905');
        $this->addSql('ALTER TABLE game_tag DROP FOREIGN KEY FK_18D3A446E48FD905');
        $this->addSql('ALTER TABLE games_collection DROP FOREIGN KEY FK_5C684131E48FD905');
        $this->addSql('ALTER TABLE game DROP FOREIGN KEY FK_232B318CFF0EB3EC');
        $this->addSql('ALTER TABLE game_infos_platform DROP FOREIGN KEY FK_35499784FF0EB3EC');
        $this->addSql('ALTER TABLE game_infos_developer DROP FOREIGN KEY FK_8394CCDCFF0EB3EC');
        $this->addSql('ALTER TABLE game_infos_publisher DROP FOREIGN KEY FK_7A879200FF0EB3EC');
        $this->addSql('ALTER TABLE game_infos_tag DROP FOREIGN KEY FK_C11E7FF0FF0EB3EC');
        $this->addSql('ALTER TABLE game DROP FOREIGN KEY FK_232B318CFFE6496F');
        $this->addSql('ALTER TABLE game_infos_platform DROP FOREIGN KEY FK_35499784FFE6496F');
        $this->addSql('ALTER TABLE game_publisher DROP FOREIGN KEY FK_4E4E144440C86FCE');
        $this->addSql('ALTER TABLE game_infos_publisher DROP FOREIGN KEY FK_7A87920040C86FCE');
        $this->addSql('ALTER TABLE game_tag DROP FOREIGN KEY FK_18D3A446BAD26311');
        $this->addSql('ALTER TABLE game_infos_tag DROP FOREIGN KEY FK_C11E7FF0BAD26311');
        $this->addSql('ALTER TABLE games_collection DROP FOREIGN KEY FK_5C684131A76ED395');
        $this->addSql('DROP TABLE cover_object');
        $this->addSql('DROP TABLE developer');
        $this->addSql('DROP TABLE game');
        $this->addSql('DROP TABLE game_developer');
        $this->addSql('DROP TABLE game_publisher');
        $this->addSql('DROP TABLE game_tag');
        $this->addSql('DROP TABLE game_infos');
        $this->addSql('DROP TABLE game_infos_platform');
        $this->addSql('DROP TABLE game_infos_developer');
        $this->addSql('DROP TABLE game_infos_publisher');
        $this->addSql('DROP TABLE game_infos_tag');
        $this->addSql('DROP TABLE games_collection');
        $this->addSql('DROP TABLE platform');
        $this->addSql('DROP TABLE publisher');
        $this->addSql('DROP TABLE tag');
        $this->addSql('DROP TABLE user');
    }
}
