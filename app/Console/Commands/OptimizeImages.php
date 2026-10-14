<?php

namespace App\Console\Commands;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

class OptimizeImages extends Command
{
    protected $signature = 'images:optimize {--width=800}';

    protected $description = 'Convert PNG/JPG product and category images to resized WebP';

    public function handle(): int
    {
        ini_set('memory_limit', '-1');

        foreach ([Product::class => 'products', Category::class => 'categories'] as $model => $dir) {
            foreach ($model::whereNotNull('image')->get() as $record) {
                if (preg_match('/\.webp$/i', $record->image) || ! Storage::disk('public')->exists($record->image)) {
                    continue;
                }

                $full = Storage::disk('public')->path($record->image);
                $info = @getimagesize($full);
                $source = match ($info['mime'] ?? null) {
                    'image/png' => imagecreatefrompng($full),
                    'image/jpeg' => imagecreatefromjpeg($full),
                    default => null,
                };
                if (! $source) {
                    continue;
                }

                $w = imagesx($source);
                $h = imagesy($source);
                $nw = min($w, (int) $this->option('width'));
                $nh = (int) floor($h * ($nw / $w));

                $target = imagecreatetruecolor($nw, $nh);
                imagealphablending($target, false);
                imagesavealpha($target, true);
                imagefilledrectangle($target, 0, 0, $nw, $nh, imagecolorallocatealpha($target, 255, 255, 255, 127));
                imagecopyresampled($target, $source, 0, 0, 0, 0, $nw, $nh, $w, $h);

                $newPath = $dir . '/' . uniqid('book_') . '.webp';
                imagewebp($target, Storage::disk('public')->path($newPath), 80);
                imagedestroy($source);
                imagedestroy($target);

                $old = $record->image;
                $record->update(['image' => $newPath]);
                Storage::disk('public')->delete($old);

                $this->info("$old -> $newPath");
            }
        }

        return self::SUCCESS;
    }
}
