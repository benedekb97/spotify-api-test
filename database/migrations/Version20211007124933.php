<?php

namespace Database\Migrations;

use Doctrine\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema as Schema;

class Version20211007124933 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema): void
    {
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE spotify_profiles (id VARCHAR(255) NOT NULL, user_id INT DEFAULT NULL, country VARCHAR(255) DEFAULT NULL, display_name VARCHAR(255) DEFAULT NULL, explicit_content JSON DEFAULT NULL, external_url JSON DEFAULT NULL, followers JSON DEFAULT NULL, href VARCHAR(255) DEFAULT NULL, images JSON DEFAULT NULL, product VARCHAR(255) DEFAULT NULL, type VARCHAR(255) DEFAULT NULL, uri VARCHAR(255) DEFAULT NULL, UNIQUE INDEX UNIQ_F872CB91A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE spotify_profiles ADD CONSTRAINT FK_F872CB91A76ED395 FOREIGN KEY (user_id) REFERENCES users (id)');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema): void
    {
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE spotify_profiles');
    }
}
