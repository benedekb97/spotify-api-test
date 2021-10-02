<?php

declare(strict_types=1);

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class DeleteDuplicateAlbumArtistsCommand extends Command
{
    protected $name = 'spotify:delete-duplicate:album-artist';

    public function execute(InputInterface $input, OutputInterface $output): int
    {
        $duplicates = DB::table('user_scope')
            ->addSelect('user_id')
            ->addSelect('scope_id')
            ->addSelect(DB::raw('count(*) c'))
            ->groupBy('user_id', 'scope_id')
            ->orderBy('c', 'DESC')
            ->get();

        foreach ($duplicates as $duplicate) {
            if ($duplicate->c > 1) {
                DB::table('user_scope')
                    ->where('user_id', $duplicate->user_id)
                    ->where('scope_id', $duplicate->scope_id)
                    ->limit($duplicate->c - 1)
                    ->delete();
            }
        }

        return self::SUCCESS;
    }
}
