<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210318131310 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE animal (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, name VARCHAR(50) NOT NULL, sex VARCHAR(10) NOT NULL, date_of_birth DATE NOT NULL, species VARCHAR(100) NOT NULL, breed VARCHAR(255) DEFAULT NULL, INDEX IDX_6AAB231FA76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE prevention (id INT AUTO_INCREMENT NOT NULL, animal_id INT NOT NULL, description VARCHAR(255) NOT NULL, date DATE NOT NULL, type SMALLINT NOT NULL, INDEX IDX_492AE6E58E962C16 (animal_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE symptom (id INT AUTO_INCREMENT NOT NULL, animal_id INT NOT NULL, description VARCHAR(255) NOT NULL, date DATE NOT NULL, INDEX IDX_E4C2F0A08E962C16 (animal_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE symptom_visit (symptom_id INT NOT NULL, visit_id INT NOT NULL, INDEX IDX_EAC11AE7DEEFDA95 (symptom_id), INDEX IDX_EAC11AE775FA0FF2 (visit_id), PRIMARY KEY(symptom_id, visit_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, username VARCHAR(180) NOT NULL, roles LONGTEXT NOT NULL COMMENT \'(DC2Type:json)\', password VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_8D93D649F85E0677 (username), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE visit (id INT AUTO_INCREMENT NOT NULL, animal_id INT NOT NULL, date DATE NOT NULL, description VARCHAR(255) NOT NULL, INDEX IDX_437EE9398E962C16 (animal_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE animal ADD CONSTRAINT FK_6AAB231FA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE prevention ADD CONSTRAINT FK_492AE6E58E962C16 FOREIGN KEY (animal_id) REFERENCES animal (id)');
        $this->addSql('ALTER TABLE symptom ADD CONSTRAINT FK_E4C2F0A08E962C16 FOREIGN KEY (animal_id) REFERENCES animal (id)');
        $this->addSql('ALTER TABLE symptom_visit ADD CONSTRAINT FK_EAC11AE7DEEFDA95 FOREIGN KEY (symptom_id) REFERENCES symptom (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE symptom_visit ADD CONSTRAINT FK_EAC11AE775FA0FF2 FOREIGN KEY (visit_id) REFERENCES visit (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE visit ADD CONSTRAINT FK_437EE9398E962C16 FOREIGN KEY (animal_id) REFERENCES animal (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE prevention DROP FOREIGN KEY FK_492AE6E58E962C16');
        $this->addSql('ALTER TABLE symptom DROP FOREIGN KEY FK_E4C2F0A08E962C16');
        $this->addSql('ALTER TABLE visit DROP FOREIGN KEY FK_437EE9398E962C16');
        $this->addSql('ALTER TABLE symptom_visit DROP FOREIGN KEY FK_EAC11AE7DEEFDA95');
        $this->addSql('ALTER TABLE animal DROP FOREIGN KEY FK_6AAB231FA76ED395');
        $this->addSql('ALTER TABLE symptom_visit DROP FOREIGN KEY FK_EAC11AE775FA0FF2');
        $this->addSql('DROP TABLE animal');
        $this->addSql('DROP TABLE prevention');
        $this->addSql('DROP TABLE symptom');
        $this->addSql('DROP TABLE symptom_visit');
        $this->addSql('DROP TABLE user');
        $this->addSql('DROP TABLE visit');
    }
}
