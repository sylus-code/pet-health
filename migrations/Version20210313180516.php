<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210313180516 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE prevention ADD animal_id INT NOT NULL');
        $this->addSql('ALTER TABLE prevention ADD CONSTRAINT FK_492AE6E58E962C16 FOREIGN KEY (animal_id) REFERENCES animal (id)');
        $this->addSql('CREATE INDEX IDX_492AE6E58E962C16 ON prevention (animal_id)');
        $this->addSql('ALTER TABLE user CHANGE roles roles LONGTEXT NOT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE prevention DROP FOREIGN KEY FK_492AE6E58E962C16');
        $this->addSql('DROP INDEX IDX_492AE6E58E962C16 ON prevention');
        $this->addSql('ALTER TABLE prevention DROP animal_id');
        $this->addSql('ALTER TABLE user CHANGE roles roles LONGTEXT CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`');
    }
}
