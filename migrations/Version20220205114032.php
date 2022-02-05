<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220205114032 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE user (id VARCHAR(38) NOT NULL, email VARCHAR(180) NOT NULL, password VARCHAR(255) NOT NULL, name VARCHAR(255) NOT NULL, gender VARCHAR(1) NOT NULL, age INT NOT NULL, UNIQUE INDEX UNIQ_8D93D649E7927C74 (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user_swipe (id VARCHAR(38) NOT NULL, logged_in_user_id VARCHAR(38) NOT NULL, swiped_user_id VARCHAR(38) NOT NULL, attracted INT NOT NULL, swiped_at DATETIME NOT NULL, INDEX IDX_399B12F1740FC49A (logged_in_user_id), INDEX IDX_399B12F14BAE63F2 (swiped_user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE user_swipe ADD CONSTRAINT FK_399B12F1740FC49A FOREIGN KEY (logged_in_user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE user_swipe ADD CONSTRAINT FK_399B12F14BAE63F2 FOREIGN KEY (swiped_user_id) REFERENCES user (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE user_swipe DROP FOREIGN KEY FK_399B12F1740FC49A');
        $this->addSql('ALTER TABLE user_swipe DROP FOREIGN KEY FK_399B12F14BAE63F2');
        $this->addSql('DROP TABLE user');
        $this->addSql('DROP TABLE user_swipe');
    }
}
