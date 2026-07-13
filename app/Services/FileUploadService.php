<?php

namespace App\Services;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class FileUploadService
{
    private function convertJpegToWebp($file)
    {
        $mime = $file->getMimeType();

        // Only convert JPG/JPEG
        if (!in_array($mime, ['image/jpeg', 'image/jpg'])) {
            return $file;
        }

        $sourcePath = $file->getPathname();

        $image = imagecreatefromjpeg($sourcePath);

        $webpPath = sys_get_temp_dir() . '/' . Str::uuid() . '.webp';

        imagewebp($image, $webpPath, 80);
        imagedestroy($image);

        return new \Illuminate\Http\File($webpPath);
    }

    public function uploadFile($files, $dir = 'uploads')
    {
        $driver = env('FILE_UPLOAD_DRIVER', 'storage');

        $dir = trim($dir, '/');
        $files = is_array($files) ? $files : [$files];
        $urls = [];

        foreach ($files as $file) {

            if (!$file || !$file->isValid()) {
                continue;
            }

            $mime = $file->getMimeType();

            // ==================================================
            // IMAGE HANDLING
            // ==================================================
            if (str_starts_with($mime, 'image/')) {

                // PNG → KEEP AS IS
                if ($mime === 'image/png') {
                    $finalFile = $file;
                    $extension = 'png';
                }

                // JPG/JPEG → CONVERT TO WEBP
                else {
                    $finalFile = $this->convertJpegToWebp($file);
                    $extension = 'webp';
                }
            } else {
                // NON-IMAGE FILES
                $finalFile = $file;
                $extension = $file->getClientOriginalExtension();
            }

            $hash = hash('sha256', Str::uuid() . microtime(true));
            $filename = $hash . '.' . $extension;

            // ==============================
            // STORAGE MODE
            // ==============================
            if ($driver === 'storage') {

                if (!Storage::disk('public')->exists($dir)) {
                    Storage::disk('public')->makeDirectory($dir);
                }

                $path = Storage::disk('public')->putFileAs($dir, $finalFile, $filename);

                $urls[] = Storage::disk('public')->url($path);
            }

            // ==============================
            // PUBLIC MODE
            // ==============================
            else {

                $publicPath = public_path($dir);

                if (!file_exists($publicPath)) {
                    mkdir($publicPath, 0777, true);
                }

                $finalFile->move($publicPath, $filename);

                $urls[] = asset($dir . '/' . $filename);
            }
        }

        return $urls;
    }

    public function deleteFile(string $fileUrl): bool
    {
        if (empty($fileUrl)) {
            return false;
        }

        $driver = env('FILE_UPLOAD_DRIVER', 'storage');

        $path = parse_url($fileUrl, PHP_URL_PATH);

        if ($driver === 'storage') {

            $storagePath = str_replace('/storage/', '', $path);

            if (Storage::disk('public')->exists($storagePath)) {
                return Storage::disk('public')->delete($storagePath);
            }
        } else {

            $fullPath = public_path(ltrim($path, '/'));

            if (file_exists($fullPath)) {
                return unlink($fullPath);
            }
        }

        return false;
    }
}
