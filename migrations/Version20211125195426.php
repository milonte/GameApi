<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20211125195426 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE developer (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE game_developer (game_id INT NOT NULL, developer_id INT NOT NULL, INDEX IDX_E56D8937E48FD905 (game_id), INDEX IDX_E56D893734C3944C (developer_id), PRIMARY KEY(game_id, developer_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE game_publisher (game_id INT NOT NULL, publisher_id INT NOT NULL, INDEX IDX_6AF10496E48FD905 (game_id), INDEX IDX_6AF104969BED9AFD (publisher_id), PRIMARY KEY(game_id, publisher_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE publisher (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE game_developer ADD CONSTRAINT FK_E56D8937E48FD905 FOREIGN KEY (game_id) REFERENCES game (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE game_developer ADD CONSTRAINT FK_E56D893734C3944C FOREIGN KEY (developer_id) REFERENCES developer (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE game_publisher ADD CONSTRAINT FK_6AF10496E48FD905 FOREIGN KEY (game_id) REFERENCES game (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE game_publisher ADD CONSTRAINT FK_6AF104969BED9AFD FOREIGN KEY (publisher_id) REFERENCES publisher (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE game ADD release_date DATE DEFAULT NULL, ADD description LONGTEXT DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE game_developer DROP FOREIGN KEY FK_E56D893734C3944C');
        $this->addSql('ALTER TABLE game_publisher DROP FOREIGN KEY FK_6AF104969BED9AFD');
        $this->addSql('DROP TABLE developer');
        $this->addSql('DROP TABLE game_developer');
        $this->addSql('DROP TABLE game_publisher');
        $this->addSql('DROP TABLE publisher');
        $this->addSql('ALTER TABLE game DROP release_date, DROP description');
    }
}
