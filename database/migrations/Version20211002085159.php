<?php

namespace Database\Migrations;

use Doctrine\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema as Schema;

class Version20211002085159 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema): void
    {
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE scopes (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, created_at DATETIME DEFAULT NULL, updated_at DATETIME DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE spotify_albums (id VARCHAR(255) NOT NULL, available_markets JSON DEFAULT NULL, copyrights JSON DEFAULT NULL, external_ids JSON DEFAULT NULL, external_urls JSON DEFAULT NULL, genres JSON DEFAULT NULL, href VARCHAR(255) DEFAULT NULL, images JSON DEFAULT NULL, label VARCHAR(255) DEFAULT NULL, name VARCHAR(255) NOT NULL, popularity SMALLINT DEFAULT NULL, release_date VARCHAR(255) DEFAULT NULL, release_date_precision VARCHAR(255) DEFAULT NULL, restrictions JSON DEFAULT NULL, total_tracks INT DEFAULT NULL, type VARCHAR(255) DEFAULT NULL, uri VARCHAR(255) DEFAULT NULL, created_at DATETIME DEFAULT NULL, updated_at DATETIME DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE spotify_artists (id VARCHAR(255) NOT NULL, followers JSON DEFAULT NULL, genres JSON DEFAULT NULL, href VARCHAR(255) DEFAULT NULL, images JSON DEFAULT NULL, name VARCHAR(255) DEFAULT NULL, popularity SMALLINT DEFAULT NULL, type VARCHAR(255) NOT NULL, uri VARCHAR(255) DEFAULT NULL, created_at DATETIME DEFAULT NULL, updated_at DATETIME DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE spotify_album_artist (artist_id VARCHAR(255) NOT NULL, album_id VARCHAR(255) NOT NULL, INDEX IDX_57B2789BB7970CF8 (artist_id), INDEX IDX_57B2789B1137ABCF (album_id), PRIMARY KEY(artist_id, album_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE spotify_playbacks (id INT AUTO_INCREMENT NOT NULL, track_id VARCHAR(255) DEFAULT NULL, user_id INT DEFAULT NULL, created_at DATETIME DEFAULT NULL, updated_at DATETIME DEFAULT NULL, played_at DATETIME DEFAULT NULL, INDEX IDX_545CFB3F5ED23C43 (track_id), INDEX IDX_545CFB3FA76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE spotify_playlist_track (id INT AUTO_INCREMENT NOT NULL, playlist_id VARCHAR(255) DEFAULT NULL, track_id VARCHAR(255) DEFAULT NULL, added_at DATETIME NOT NULL, added_by_user_id VARCHAR(255) DEFAULT NULL, is_local TINYINT(1) DEFAULT NULL, INDEX IDX_27D683396BBD148 (playlist_id), INDEX IDX_27D683395ED23C43 (track_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE spotify_playlists (id VARCHAR(255) NOT NULL, local_user_id INT DEFAULT NULL, collaborative TINYINT(1) DEFAULT NULL, description VARCHAR(255) DEFAULT NULL, external_url JSON DEFAULT NULL, followers JSON DEFAULT NULL, images JSON DEFAULT NULL, name VARCHAR(255) NOT NULL, owner_user_id VARCHAR(255) DEFAULT NULL, snapshot_id VARCHAR(255) DEFAULT NULL, type VARCHAR(255) NOT NULL, uri VARCHAR(255) DEFAULT NULL, href VARCHAR(255) DEFAULT NULL, created_at DATETIME DEFAULT NULL, updated_at DATETIME DEFAULT NULL, INDEX IDX_FFA4C05F3ABBD618 (local_user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE spotify_tracks (id VARCHAR(255) NOT NULL, album_id VARCHAR(255) DEFAULT NULL, available_markets JSON DEFAULT NULL, disc_number SMALLINT DEFAULT NULL, duration_ms BIGINT DEFAULT NULL, explicit TINYINT(1) NOT NULL, external_ids JSON DEFAULT NULL, external_urls JSON DEFAULT NULL, href VARCHAR(255) DEFAULT NULL, is_local TINYINT(1) DEFAULT NULL, is_playable TINYINT(1) DEFAULT NULL, name VARCHAR(255) NOT NULL, popularity SMALLINT NOT NULL, preview_url VARCHAR(255) DEFAULT NULL, track_number INT DEFAULT NULL, type VARCHAR(255) DEFAULT NULL, uri VARCHAR(255) DEFAULT NULL, created_at DATETIME DEFAULT NULL, updated_at DATETIME DEFAULT NULL, INDEX IDX_18DA0E291137ABCF (album_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE spotify_track_artist (track_id VARCHAR(255) NOT NULL, artist_id VARCHAR(255) NOT NULL, INDEX IDX_CD0B84C55ED23C43 (track_id), INDEX IDX_CD0B84C5B7970CF8 (artist_id), PRIMARY KEY(track_id, artist_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE users (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, email VARCHAR(255) NOT NULL, password VARCHAR(255) DEFAULT NULL, spotify_access_token VARCHAR(255) DEFAULT NULL, spotify_refresh_token VARCHAR(255) DEFAULT NULL, spotify_access_token_expiry DATETIME DEFAULT NULL, spotify_id VARCHAR(255) DEFAULT NULL, automatically_create_weekly_playlist TINYINT(1) DEFAULT NULL, created_at DATETIME DEFAULT NULL, updated_at DATETIME DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user_scope (user_id INT NOT NULL, scope_id INT NOT NULL, INDEX IDX_E26DAE8BA76ED395 (user_id), INDEX IDX_E26DAE8B682B5931 (scope_id), PRIMARY KEY(user_id, scope_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE spotify_album_artist ADD CONSTRAINT FK_57B2789BB7970CF8 FOREIGN KEY (artist_id) REFERENCES spotify_artists (id)');
        $this->addSql('ALTER TABLE spotify_album_artist ADD CONSTRAINT FK_57B2789B1137ABCF FOREIGN KEY (album_id) REFERENCES spotify_albums (id)');
        $this->addSql('ALTER TABLE spotify_playbacks ADD CONSTRAINT FK_545CFB3F5ED23C43 FOREIGN KEY (track_id) REFERENCES spotify_tracks (id)');
        $this->addSql('ALTER TABLE spotify_playbacks ADD CONSTRAINT FK_545CFB3FA76ED395 FOREIGN KEY (user_id) REFERENCES users (id)');
        $this->addSql('ALTER TABLE spotify_playlist_track ADD CONSTRAINT FK_27D683396BBD148 FOREIGN KEY (playlist_id) REFERENCES spotify_playlists (id)');
        $this->addSql('ALTER TABLE spotify_playlist_track ADD CONSTRAINT FK_27D683395ED23C43 FOREIGN KEY (track_id) REFERENCES spotify_tracks (id)');
        $this->addSql('ALTER TABLE spotify_playlists ADD CONSTRAINT FK_FFA4C05F3ABBD618 FOREIGN KEY (local_user_id) REFERENCES users (id)');
        $this->addSql('ALTER TABLE spotify_tracks ADD CONSTRAINT FK_18DA0E291137ABCF FOREIGN KEY (album_id) REFERENCES spotify_albums (id)');
        $this->addSql('ALTER TABLE spotify_track_artist ADD CONSTRAINT FK_CD0B84C55ED23C43 FOREIGN KEY (track_id) REFERENCES spotify_tracks (id)');
        $this->addSql('ALTER TABLE spotify_track_artist ADD CONSTRAINT FK_CD0B84C5B7970CF8 FOREIGN KEY (artist_id) REFERENCES spotify_artists (id)');
        $this->addSql('ALTER TABLE user_scope ADD CONSTRAINT FK_E26DAE8BA76ED395 FOREIGN KEY (user_id) REFERENCES users (id)');
        $this->addSql('ALTER TABLE user_scope ADD CONSTRAINT FK_E26DAE8B682B5931 FOREIGN KEY (scope_id) REFERENCES scopes (id)');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema): void
    {
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE user_scope DROP FOREIGN KEY FK_E26DAE8B682B5931');
        $this->addSql('ALTER TABLE spotify_album_artist DROP FOREIGN KEY FK_57B2789B1137ABCF');
        $this->addSql('ALTER TABLE spotify_tracks DROP FOREIGN KEY FK_18DA0E291137ABCF');
        $this->addSql('ALTER TABLE spotify_album_artist DROP FOREIGN KEY FK_57B2789BB7970CF8');
        $this->addSql('ALTER TABLE spotify_track_artist DROP FOREIGN KEY FK_CD0B84C5B7970CF8');
        $this->addSql('ALTER TABLE spotify_playlist_track DROP FOREIGN KEY FK_27D683396BBD148');
        $this->addSql('ALTER TABLE spotify_playbacks DROP FOREIGN KEY FK_545CFB3F5ED23C43');
        $this->addSql('ALTER TABLE spotify_playlist_track DROP FOREIGN KEY FK_27D683395ED23C43');
        $this->addSql('ALTER TABLE spotify_track_artist DROP FOREIGN KEY FK_CD0B84C55ED23C43');
        $this->addSql('ALTER TABLE spotify_playbacks DROP FOREIGN KEY FK_545CFB3FA76ED395');
        $this->addSql('ALTER TABLE spotify_playlists DROP FOREIGN KEY FK_FFA4C05F3ABBD618');
        $this->addSql('ALTER TABLE user_scope DROP FOREIGN KEY FK_E26DAE8BA76ED395');
        $this->addSql('DROP TABLE scopes');
        $this->addSql('DROP TABLE spotify_albums');
        $this->addSql('DROP TABLE spotify_artists');
        $this->addSql('DROP TABLE spotify_album_artist');
        $this->addSql('DROP TABLE spotify_playbacks');
        $this->addSql('DROP TABLE spotify_playlist_track');
        $this->addSql('DROP TABLE spotify_playlists');
        $this->addSql('DROP TABLE spotify_tracks');
        $this->addSql('DROP TABLE spotify_track_artist');
        $this->addSql('DROP TABLE users');
        $this->addSql('DROP TABLE user_scope');
    }
}
