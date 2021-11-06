<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20211020171315 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE song (id INT AUTO_INCREMENT NOT NULL, save_id INT NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', spotify_id VARCHAR(255) NOT NULL, ranking INT NOT NULL, INDEX IDX_33EDEEA1602EC74B (save_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE song ADD CONSTRAINT FK_33EDEEA1602EC74B FOREIGN KEY (save_id) REFERENCES save (id)');
        $this->addSql('ALTER TABLE save ADD date_created DATE NOT NULL, DROP created_at, DROP spotify_id, DROP ranking');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE song');
        $this->addSql('ALTER TABLE save ADD created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', ADD spotify_id VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, ADD ranking INT NOT NULL, DROP date_created');
    }
}
