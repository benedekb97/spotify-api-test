<?php

namespace Database\Migrations;

use Doctrine\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema as Schema;

class Version20211003093243 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema): void
    {
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE spotify_track_associations (id INT AUTO_INCREMENT NOT NULL, original_track_id VARCHAR(255) DEFAULT NULL, recommended_track_id VARCHAR(255) DEFAULT NULL, user_id INT DEFAULT NULL, created_at DATETIME DEFAULT NULL, updated_at DATETIME DEFAULT NULL, INDEX IDX_282003397D8DDB52 (original_track_id), INDEX IDX_28200339D165BB47 (recommended_track_id), INDEX IDX_28200339A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE spotify_track_associations ADD CONSTRAINT FK_282003397D8DDB52 FOREIGN KEY (original_track_id) REFERENCES spotify_tracks (id)');
        $this->addSql('ALTER TABLE spotify_track_associations ADD CONSTRAINT FK_28200339D165BB47 FOREIGN KEY (recommended_track_id) REFERENCES spotify_tracks (id)');
        $this->addSql('ALTER TABLE spotify_track_associations ADD CONSTRAINT FK_28200339A76ED395 FOREIGN KEY (user_id) REFERENCES users (id)');
        $this->addSql('ALTER TABLE spotify_playlists ADD top_played TINYINT(1) DEFAULT NULL');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema): void
    {
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE spotify_track_associations');
        $this->addSql('ALTER TABLE spotify_playlists DROP top_played');
    }
}
