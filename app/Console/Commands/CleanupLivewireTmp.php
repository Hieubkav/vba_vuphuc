<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Carbon\Carbon;

class CleanupLivewireTmp extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'livewire:cleanup-tmp {--hours=24 : Files older than this many hours will be deleted}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Clean up old temporary files in livewire-tmp directory';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $hours = (int) $this->option('hours');
        $tmpPath = storage_path('app/livewire-tmp');

        if (!is_dir($tmpPath)) {
            $this->info('Livewire tmp directory does not exist.');
            return 0;
        }

        $cutoffTime = Carbon::now()->subHours($hours);
        $deletedCount = 0;
        $totalSize = 0;

        $files = File::allFiles($tmpPath);

        foreach ($files as $file) {
            $fileTime = Carbon::createFromTimestamp($file->getMTime());

            if ($fileTime->lt($cutoffTime)) {
                $fileSize = $file->getSize();
                $totalSize += $fileSize;

                if (File::delete($file->getPathname())) {
                    $deletedCount++;
                }
            }
        }

        $this->info("Cleaned up {$deletedCount} files, freed " . $this->formatBytes($totalSize));

        return 0;
    }

    /**
     * Format bytes to human readable format
     */
    private function formatBytes($bytes, $precision = 2)
    {
        $units = array('B', 'KB', 'MB', 'GB', 'TB');

        for ($i = 0; $bytes > 1024 && $i < count($units) - 1; $i++) {
            $bytes /= 1024;
        }

        return round($bytes, $precision) . ' ' . $units[$i];
    }
}
