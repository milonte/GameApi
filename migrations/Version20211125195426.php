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
        $this->addSql('CREATE TABLE developers (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE game_developers (game_id INT NOT NULL, developers_id INT NOT NULL, INDEX IDX_E56D8937E48FD905 (game_id), INDEX IDX_E56D893734C3944C (developers_id), PRIMARY KEY(game_id, developers_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE game_publishers (game_id INT NOT NULL, publishers_id INT NOT NULL, INDEX IDX_6AF10496E48FD905 (game_id), INDEX IDX_6AF104969BED9AFD (publishers_id), PRIMARY KEY(game_id, publishers_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE publishers (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE game_developers ADD CONSTRAINT FK_E56D8937E48FD905 FOREIGN KEY (game_id) REFERENCES game (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE game_developers ADD CONSTRAINT FK_E56D893734C3944C FOREIGN KEY (developers_id) REFERENCES developers (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE game_publishers ADD CONSTRAINT FK_6AF10496E48FD905 FOREIGN KEY (game_id) REFERENCES game (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE game_publishers ADD CONSTRAINT FK_6AF104969BED9AFD FOREIGN KEY (publishers_id) REFERENCES publishers (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE game ADD release_date DATE DEFAULT NULL, ADD description LONGTEXT DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE game_developers DROP FOREIGN KEY FK_E56D893734C3944C');
        $this->addSql('ALTER TABLE game_publishers DROP FOREIGN KEY FK_6AF104969BED9AFD');
        $this->addSql('DROP TABLE developers');
        $this->addSql('DROP TABLE game_developers');
        $this->addSql('DROP TABLE game_publishers');
        $this->addSql('DROP TABLE publishers');
        $this->addSql('ALTER TABLE game DROP release_date, DROP description');
    }
}
