<?php

namespace App\Console\Commands;

use App\Actions\OptimizePostMedia;
use App\Models\Post;
use Illuminate\Console\Command;

class OptimizePostMediaCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'posts:optimize-media 
                            {--post= : Optimize specific post ID}
                            {--cleanup : Clean up orphaned files}
                            {--thumbnails : Generate video thumbnails}
                            {--all : Optimize all posts}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Optimize post media files (images, videos, audio)';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('🎨 Post Media Optimization Tool');
        $this->newLine();

        // Cleanup orphaned files
        if ($this->option('cleanup')) {
            $this->info('🧹 Cleaning up orphaned files...');
            $result = OptimizePostMedia::cleanupOrphanedFiles();
            
            $this->info("✅ Deleted {$result['files_deleted']} orphaned files");
            $this->info("💾 Freed " . $this->formatBytes($result['space_freed']) . " of storage");
            
            if (!empty($result['errors'])) {
                $this->warn('⚠️  Some errors occurred:');
                foreach ($result['errors'] as $error) {
                    $this->error("   {$error}");
                }
            }
            $this->newLine();
        }

        // Optimize specific post
        if ($postId = $this->option('post')) {
            $post = Post::find($postId);
            if (!$post) {
                $this->error("❌ Post with ID {$postId} not found");
                return 1;
            }

            $this->optimizePost($post);
            return 0;
        }

        // Optimize all posts
        if ($this->option('all')) {
            $posts = Post::with(['images', 'media'])->get();
            $this->info("🔄 Optimizing {$posts->count()} posts...");
            $this->newLine();

            $totalResults = [
                'images_optimized' => 0,
                'media_processed' => 0,
                'total_size_saved' => 0,
                'errors' => []
            ];

            $progressBar = $this->output->createProgressBar($posts->count());
            $progressBar->start();

            foreach ($posts as $post) {
                $result = OptimizePostMedia::run($post);
                
                $totalResults['images_optimized'] += $result['images_optimized'];
                $totalResults['media_processed'] += $result['media_processed'];
                $totalResults['total_size_saved'] += $result['total_size_saved'];
                $totalResults['errors'] = array_merge($totalResults['errors'], $result['errors']);

                // Generate video thumbnails if requested
                if ($this->option('thumbnails')) {
                    foreach ($post->media()->where('media_type', 'video')->get() as $media) {
                        if (!$media->thumbnail_path) {
                            OptimizePostMedia::generateVideoThumbnail($media);
                        }
                    }
                }

                $progressBar->advance();
            }

            $progressBar->finish();
            $this->newLine(2);

            $this->displayResults($totalResults);
            return 0;
        }

        // Interactive mode
        $this->interactiveMode();
        return 0;
    }

    /**
     * Optimize a single post
     */
    private function optimizePost(Post $post): void
    {
        $this->info("🔄 Optimizing post: {$post->title}");
        
        $result = OptimizePostMedia::run($post);
        
        // Generate video thumbnails if requested
        if ($this->option('thumbnails')) {
            foreach ($post->media()->where('media_type', 'video')->get() as $media) {
                if (!$media->thumbnail_path) {
                    $thumbResult = OptimizePostMedia::generateVideoThumbnail($media);
                    if ($thumbResult['success']) {
                        $this->info("   📸 Generated thumbnail for video: {$media->title}");
                    }
                }
            }
        }

        $this->displayResults($result);
    }

    /**
     * Interactive mode
     */
    private function interactiveMode(): void
    {
        $choice = $this->choice(
            'What would you like to do?',
            [
                'optimize_all' => 'Optimize all posts',
                'optimize_post' => 'Optimize specific post',
                'cleanup' => 'Clean up orphaned files',
                'thumbnails' => 'Generate video thumbnails',
                'exit' => 'Exit'
            ],
            'optimize_all'
        );

        switch ($choice) {
            case 'optimize_all':
                $this->call('posts:optimize-media', ['--all' => true]);
                break;

            case 'optimize_post':
                $posts = Post::select('id', 'title')->get();
                $postChoices = $posts->pluck('title', 'id')->toArray();
                
                if (empty($postChoices)) {
                    $this->warn('No posts found');
                    return;
                }

                $postId = $this->choice('Select a post:', $postChoices);
                $this->call('posts:optimize-media', ['--post' => $postId]);
                break;

            case 'cleanup':
                $this->call('posts:optimize-media', ['--cleanup' => true]);
                break;

            case 'thumbnails':
                $this->call('posts:optimize-media', ['--all' => true, '--thumbnails' => true]);
                break;

            case 'exit':
                $this->info('👋 Goodbye!');
                break;
        }
    }

    /**
     * Display optimization results
     */
    private function displayResults(array $results): void
    {
        $this->info('📊 Optimization Results:');
        $this->table(
            ['Metric', 'Value'],
            [
                ['Images Optimized', $results['images_optimized']],
                ['Media Files Processed', $results['media_processed']],
                ['Total Size Saved', $this->formatBytes($results['total_size_saved'])],
                ['Errors', count($results['errors'])],
            ]
        );

        if (!empty($results['errors'])) {
            $this->newLine();
            $this->warn('⚠️  Errors encountered:');
            foreach ($results['errors'] as $error) {
                $this->error("   {$error}");
            }
        }

        $this->newLine();
        $this->info('✅ Optimization completed!');
    }

    /**
     * Format bytes to human readable format
     */
    private function formatBytes(int $bytes): string
    {
        if ($bytes === 0) return '0 B';

        $units = ['B', 'KB', 'MB', 'GB'];
        $factor = floor(log($bytes, 1024));
        
        return sprintf('%.2f %s', $bytes / pow(1024, $factor), $units[$factor]);
    }
}
