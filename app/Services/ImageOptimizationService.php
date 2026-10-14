<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;

class ImageOptimizationService
{
    /**
     * Store and optimize an uploaded image as WebP using native GD.
     *
     * @param UploadedFile $file The uploaded file instance
     * @param string $directory Target directory on the public disk
     * @param string $prefix Filename prefix (e.g. 'book_', 'bundle_', 'cat_')
     * @param int $maxWidth Maximum width in pixels to resize down to (preserves aspect ratio)
     * @param int $quality WebP compression quality (0-100)
     * @return string Stored file path relative to public disk (e.g. 'products/book_xxx.webp')
     */
    public function storeAsWebp(
        UploadedFile $file,
        string $directory,
        string $prefix = 'img_',
        int $maxWidth = 800,
        int $quality = 80
    ): string {
        $cleanDir = trim($directory, '/\\');
        $filename = uniqid($prefix) . '.webp';

        // Ensure target directory exists on public disk
        $dirPath = storage_path('app/public/' . $cleanDir);
        if (!file_exists($dirPath)) {
            mkdir($dirPath, 0755, true);
        }

        $targetPath = $dirPath . '/' . $filename;

        if (extension_loaded('gd')) {
            // SECURITY FIX: Limit memory to safe 256M instead of unlimited (-1) to prevent RAM exhaustion
            ini_set('memory_limit', '256M');

            $realPath = $file->getRealPath();
            $info = @getimagesize($realPath);
            $mime = $info['mime'] ?? '';

            $source = null;
            switch ($mime) {
                case 'image/jpeg':
                    $source = @\imagecreatefromjpeg($realPath);
                    break;
                case 'image/png':
                    $source = @\imagecreatefrompng($realPath);
                    break;
                case 'image/webp':
                    $source = @\imagecreatefromwebp($realPath);
                    break;
                default:
                    $content = @file_get_contents($realPath);
                    if ($content !== false) {
                        $source = @\imagecreatefromstring($content);
                    }
                    break;
            }

            if ($source) {
                $width = \imagesx($source);
                $height = \imagesy($source);

                $newWidth = $width > $maxWidth ? $maxWidth : $width;
                $newHeight = (int) floor($height * ($newWidth / $width));

                $target = \imagecreatetruecolor($newWidth, $newHeight);

                // Handle transparency for PNGs and transparent WebPs
                if ($mime === 'image/png' || $mime === 'image/webp') {
                    \imagealphablending($target, false);
                    \imagesavealpha($target, true);
                    $transparent = \imagecolorallocatealpha($target, 255, 255, 255, 127);
                    \imagefilledrectangle($target, 0, 0, $newWidth, $newHeight, $transparent);
                }

                \imagecopyresampled($target, $source, 0, 0, 0, 0, $newWidth, $newHeight, $width, $height);
                \imagewebp($target, $targetPath, $quality);

                \imagedestroy($target);
                \imagedestroy($source);

                return $cleanDir . '/' . $filename;
            }
        }

        // Fallback if GD is not available or failed to decode
        return $file->store($cleanDir, 'public');
    }
}
