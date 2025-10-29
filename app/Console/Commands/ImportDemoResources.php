<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use App\Models\Resource;

class ImportDemoResources extends Command
{
    protected $signature = 'import:demo-resources {--force : overwrite existing entries for same filename}';
    protected $description = 'Import demo PDF/video/image files from resources/demo_resources into storage and create Resource records';

    public function handle(): int
    {
        $base = resource_path('demo_resources');

        if (!File::isDirectory($base)) {
            $this->error("Demo directory not found: {$base}");
            $this->line('Create demo files under resources/demo_resources/culinary and resources/demo_resources/educational');
            return 1;
        }

        $subdirs = ['culinary', 'educational'];
        foreach ($subdirs as $sub) {
            $dir = $base . DIRECTORY_SEPARATOR . $sub;
            if (!File::isDirectory($dir)) {
                $this->warn("Skipping missing folder: {$dir}");
                continue;
            }

            $files = File::files($dir);
            foreach ($files as $file) {
                $originalName = $file->getFilename();
                $ext = strtolower($file->getExtension());
                $slugBase = Str::slug(pathinfo($originalName, PATHINFO_FILENAME));
                $targetName = time() . '_' . $originalName;
                $targetPath = storage_path('app/public/resources/' . $targetName);

                // copy file into storage/public/resources
                if (!File::exists(dirname($targetPath))) {
                    File::makeDirectory(dirname($targetPath), 0755, true);
                }
                File::copy($file->getRealPath(), $targetPath);

                // determine type
                $type = 'tutorial';
                if (in_array($ext, ['pdf'])) {
                    $type = 'card';
                } elseif (in_array($ext, ['mp4', 'webm', 'ogg'])) {
                    $type = 'video';
                } elseif (in_array($ext, ['jpg', 'jpeg', 'png', 'gif', 'webp'])) {
                    // images: treat as tutorial or thumbnail; we'll create a tutorial resource with thumbnail
                    $type = 'tutorial';
                }

                $slug = $slugBase;
                // ensure unique slug
                $i = 1;
                while (Resource::where('slug', $slug)->exists()) {
                    $slug = $slugBase . '-' . $i;
                    $i++;
                }

                // create DB record (skip if exists unless --force)
                $existing = Resource::whereRaw('LOWER(file_path) = ?', ['resources/' . $targetName])->first();
                if ($existing && !$this->option('force')) {
                    $this->info("Exists, skipping: {$originalName}");
                    continue;
                }

                $data = [
                    'title' => Str::title(str_replace(['_', '-'], ' ', pathinfo($originalName, PATHINFO_FILENAME))),
                    'slug' => $slug,
                    'category' => $sub,
                    'type' => $type,
                    'description' => null,
                    'file_path' => 'resources/' . $targetName, // accessible via asset('storage/...') after storage:link
                    'external_url' => null,
                    'thumbnail_url' => null,
                    'duration' => $type === 'video' ? null : null,
                    'tags' => [],
                    'published' => true,
                ];

                if ($existing && $this->option('force')) {
                    $existing->update($data);
                    $this->info("Updated resource for: {$originalName}");
                } else {
                    Resource::create($data);
                    $this->info("Imported: {$originalName}");
                }
            }
        }

        $this->info('Done.');
        return 0;
    }
}