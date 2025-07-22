<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Album;
use Illuminate\Support\Facades\Storage;

class CleanupOrphanedAlbumFiles extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'album:cleanup-orphaned-files {--dry-run : Show what would be cleaned without making changes}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Clean up orphaned file references in album thumbnails (files that no longer exist in storage)';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $isDryRun = $this->option('dry-run');

        $this->info('🧹 Starting Album Files Cleanup...');
        $this->info($isDryRun ? '📋 DRY RUN MODE - No changes will be made' : '🔧 LIVE MODE - Changes will be applied');
        $this->newLine();

        // Get all albums with images media type
        $albums = Album::where('media_type', 'images')->get();

        $this->info("Found {$albums->count()} albums with images media type");
        $this->newLine();

        $totalCleaned = 0;
        $totalAlbums = 0;
        $totalFilesRemoved = 0;

        foreach ($albums as $album) {
            $this->line("Checking Album ID {$album->id}: {$album->title}");

            if (!$album->thumbnail || !is_array($album->thumbnail)) {
                $this->line("  ⏭️  No thumbnail array, skipping");
                continue;
            }

            $originalCount = count($album->thumbnail);
            $existingFiles = [];
            $missingFiles = [];

            foreach ($album->thumbnail as $file) {
                if (Storage::disk('public')->exists($file)) {
                    $existingFiles[] = $file;
                } else {
                    $missingFiles[] = $file;
                }
            }

            $this->line("  📊 Original files: $originalCount");
            $this->line("  ✅ Existing files: " . count($existingFiles));
            $this->line("  ❌ Missing files: " . count($missingFiles));

            if (count($missingFiles) > 0) {
                $this->line("  🗑️  Missing files:");
                foreach ($missingFiles as $missing) {
                    $this->line("     • $missing");
                }

                if (!$isDryRun) {
                    // Update album with only existing files
                    $album->thumbnail = $existingFiles;
                    $album->save();
                    $this->line("  ✅ CLEANED: Updated thumbnail array");
                } else {
                    $this->line("  📋 WOULD CLEAN: Update thumbnail array");
                }

                $totalCleaned++;
                $totalFilesRemoved += count($missingFiles);
            } else {
                $this->line("  ✅ OK: All files exist");
            }

            $totalAlbums++;
            $this->newLine();
        }

        $this->info('📈 CLEANUP SUMMARY');
        $this->table(
            ['Metric', 'Count'],
            [
                ['Total albums checked', $totalAlbums],
                ['Albums cleaned', $totalCleaned],
                ['Albums already clean', $totalAlbums - $totalCleaned],
                ['Orphaned file references removed', $totalFilesRemoved],
            ]
        );

        if ($isDryRun && $totalCleaned > 0) {
            $this->warn('🔄 Run without --dry-run to apply changes');
        } elseif (!$isDryRun && $totalCleaned > 0) {
            $this->info('✅ Cleanup completed successfully!');
        } else {
            $this->info('✨ No cleanup needed - all albums are clean!');
        }

        return Command::SUCCESS;
    }
}
