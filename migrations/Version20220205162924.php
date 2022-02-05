<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220205162924 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE user ADD profile_photo VARCHAR(255) DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE user DROP profile_photo, CHANGE id id VARCHAR(38) NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE email email VARCHAR(180) NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE password password VARCHAR(255) NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE name name VARCHAR(255) NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE gender gender VARCHAR(1) NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE auth_token auth_token VARCHAR(255) DEFAULT NULL COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE user_swipe CHANGE id id VARCHAR(38) NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE logged_in_user_id logged_in_user_id VARCHAR(38) NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE swiped_user_id swiped_user_id VARCHAR(38) NOT NULL COLLATE `utf8mb4_unicode_ci`');
    }
}
