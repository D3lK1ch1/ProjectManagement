<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260419160408 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE activity_dependency (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, successor_id INTEGER NOT NULL, predecessor_id INTEGER NOT NULL, CONSTRAINT FK_7ABA6B0D7323E667 FOREIGN KEY (successor_id) REFERENCES activity (id) NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_7ABA6B0D68C90015 FOREIGN KEY (predecessor_id) REFERENCES activity (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('CREATE INDEX IDX_7ABA6B0D7323E667 ON activity_dependency (successor_id)');
        $this->addSql('CREATE INDEX IDX_7ABA6B0D68C90015 ON activity_dependency (predecessor_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE activity_dependency');
    }
}
