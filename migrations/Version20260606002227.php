<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20260606002227 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Allow project repository URLs to be nullable';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('CREATE TEMPORARY TABLE __temp__project AS SELECT id, name, repo_url FROM project');
        $this->addSql('DROP TABLE project');
        $this->addSql('CREATE TABLE project (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, name VARCHAR(255) NOT NULL, repo_url VARCHAR(255) DEFAULT NULL)');
        $this->addSql('INSERT INTO project (id, name, repo_url) SELECT id, name, repo_url FROM __temp__project');
        $this->addSql('DROP TABLE __temp__project');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('CREATE TEMPORARY TABLE __temp__project AS SELECT id, name, repo_url FROM project');
        $this->addSql('DROP TABLE project');
        $this->addSql('CREATE TABLE project (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, name VARCHAR(255) NOT NULL, repo_url VARCHAR(255) NOT NULL)');
        $this->addSql('INSERT INTO project (id, name, repo_url) SELECT id, name, repo_url FROM __temp__project');
        $this->addSql('DROP TABLE __temp__project');
    }
}
