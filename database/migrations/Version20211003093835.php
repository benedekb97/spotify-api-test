<?php

namespace Database\Migrations;

use Doctrine\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema as Schema;

class Version20211003093835 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema): void
    {
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE spotify_track_associations ADD playlist_id VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE spotify_track_associations ADD CONSTRAINT FK_282003396BBD148 FOREIGN KEY (playlist_id) REFERENCES spotify_playlists (id)');
        $this->addSql('CREATE INDEX IDX_282003396BBD148 ON spotify_track_associations (playlist_id)');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema): void
    {
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE spotify_track_associations DROP FOREIGN KEY FK_282003396BBD148');
        $this->addSql('DROP INDEX IDX_282003396BBD148 ON spotify_track_associations');
        $this->addSql('ALTER TABLE spotify_track_associations DROP playlist_id');
    }
}
