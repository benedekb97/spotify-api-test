<?php

declare(strict_types=1);

namespace App\Factories;

use DateTimeInterface;
use Intervention\Image\Image;
use Intervention\Image\ImageManager;
use Intervention\Image\Gd\Font;

class TopTracksPlaylistCoverFactory
{
    private const TITLE_TEXT = 'Top 25 Tracks';

    private ImageManager $imageManager;

    public function __construct(
        ImageManager $imageManager
    ) {
        $this->imageManager = $imageManager;
    }

    public function create(DateTimeInterface $startDate, DateTimeInterface $endDate): Image
    {
        $image = $this->imageManager
            ->make(storage_path('images/playlist_cover_template.png'))
            ->resize(256, 256);

        $image->text(
            self::TITLE_TEXT,
            128,
            110,
            static function (Font $font) {
                $font->file(storage_path('fonts/georama.ttf'));
                $font->size(56);
                $font->align('center');
                $font->color('#050505');
                $font->valign('middle');
            }
        );

        $image->text(
            sprintf(
                '%s - %s',
                $startDate->format('Y-m-d'),
                $endDate->format('Y-m-d')
            ),
            128,
            180,
            static function (Font $font) {
                $font->file(storage_path('fonts/georama.ttf'));
                $font->size(28);
                $font->align('center');
                $font->color('#040404');
                $font->valign('bottom');
            }
        );

        return $image;
    }
}
