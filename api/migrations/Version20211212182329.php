<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20211212182329 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE platform ADD platform_base_content_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE platform ADD CONSTRAINT FK_3952D0CBC40E5BC0 FOREIGN KEY (platform_base_content_id) REFERENCES platform_base_content (id)');
        $this->addSql('CREATE INDEX IDX_3952D0CBC40E5BC0 ON platform (platform_base_content_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE platform DROP FOREIGN KEY FK_3952D0CBC40E5BC0');
        $this->addSql('DROP INDEX IDX_3952D0CBC40E5BC0 ON platform');
        $this->addSql('ALTER TABLE platform DROP platform_base_content_id');
    }
}
