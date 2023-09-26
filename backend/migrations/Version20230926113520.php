<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230926113520 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE creature_stat_block (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, life INTEGER NOT NULL, strength INTEGER NOT NULL, defense INTEGER NOT NULL)');
        $this->addSql('CREATE TABLE monster (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, stats_id INTEGER NOT NULL, description CLOB NOT NULL, image VARCHAR(255) DEFAULT NULL, name VARCHAR(255) NOT NULL, next_step INTEGER NOT NULL, CONSTRAINT FK_245EC6F470AA3482 FOREIGN KEY (stats_id) REFERENCES creature_stat_block (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_245EC6F470AA3482 ON monster (stats_id)');
        $this->addSql('CREATE TABLE question (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, step_id INTEGER NOT NULL, description CLOB NOT NULL, next_step INTEGER NOT NULL, CONSTRAINT FK_B6F7494E73B21E9C FOREIGN KEY (step_id) REFERENCES step (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('CREATE INDEX IDX_B6F7494E73B21E9C ON question (step_id)');
        $this->addSql('CREATE TABLE step (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, game_id_id INTEGER NOT NULL, description CLOB NOT NULL, step_number INTEGER NOT NULL, CONSTRAINT FK_43B9FE3C4D77E7D8 FOREIGN KEY (game_id_id) REFERENCES game (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('CREATE INDEX IDX_43B9FE3C4D77E7D8 ON step (game_id_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE creature_stat_block');
        $this->addSql('DROP TABLE monster');
        $this->addSql('DROP TABLE question');
        $this->addSql('DROP TABLE step');
    }
}
