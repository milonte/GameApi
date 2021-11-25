<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20211123231450 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE games_collection DROP FOREIGN KEY FK_5C68413197FFC673');
        $this->addSql('DROP INDEX IDX_5C68413197FFC673 ON games_collection');
        $this->addSql('ALTER TABLE games_collection CHANGE games_id game_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE games_collection ADD CONSTRAINT FK_5C684131E48FD905 FOREIGN KEY (game_id) REFERENCES game (id)');
        $this->addSql('CREATE INDEX IDX_5C684131E48FD905 ON games_collection (game_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE games_collection DROP FOREIGN KEY FK_5C684131E48FD905');
        $this->addSql('DROP INDEX IDX_5C684131E48FD905 ON games_collection');
        $this->addSql('ALTER TABLE games_collection CHANGE game_id games_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE games_collection ADD CONSTRAINT FK_5C68413197FFC673 FOREIGN KEY (games_id) REFERENCES game (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('CREATE INDEX IDX_5C68413197FFC673 ON games_collection (games_id)');
    }
}
