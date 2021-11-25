<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20211125205109 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE game_tag (game_id INT NOT NULL, tag_id INT NOT NULL, INDEX IDX_18D3A446E48FD905 (game_id), INDEX IDX_18D3A446BAD26311 (tag_id), PRIMARY KEY(game_id, tag_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE tag (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE game_tag ADD CONSTRAINT FK_18D3A446E48FD905 FOREIGN KEY (game_id) REFERENCES game (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE game_tag ADD CONSTRAINT FK_18D3A446BAD26311 FOREIGN KEY (tag_id) REFERENCES tag (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE game_developer RENAME INDEX idx_e56d8937e48fd905 TO IDX_B75D4A98E48FD905');
        $this->addSql('ALTER TABLE game_developer RENAME INDEX idx_e56d893734c3944c TO IDX_B75D4A9864DD9267');
        $this->addSql('ALTER TABLE game_publisher RENAME INDEX idx_6af10496e48fd905 TO IDX_4E4E1444E48FD905');
        $this->addSql('ALTER TABLE game_publisher RENAME INDEX idx_6af104969bed9afd TO IDX_4E4E144440C86FCE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE game_tag DROP FOREIGN KEY FK_18D3A446BAD26311');
        $this->addSql('DROP TABLE game_tag');
        $this->addSql('DROP TABLE tag');
        $this->addSql('ALTER TABLE game_developer RENAME INDEX idx_b75d4a9864dd9267 TO IDX_E56D893734C3944C');
        $this->addSql('ALTER TABLE game_developer RENAME INDEX idx_b75d4a98e48fd905 TO IDX_E56D8937E48FD905');
        $this->addSql('ALTER TABLE game_publisher RENAME INDEX idx_4e4e144440c86fce TO IDX_6AF104969BED9AFD');
        $this->addSql('ALTER TABLE game_publisher RENAME INDEX idx_4e4e1444e48fd905 TO IDX_6AF10496E48FD905');
    }
}
