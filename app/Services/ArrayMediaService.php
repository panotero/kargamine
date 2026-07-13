<?php

namespace App\Services;

use Illuminate\Support\Facades\Storage;

class ArrayMediaService
{
    /**
     * Remove a single item from a JSON array column + delete physical file
     *
     * @param  object  $model        Eloquent model (Room, Hotel, etc.)
     * @param  string  $column        Column name (e.g. photos)
     * @param  string  $url           Full file URL
     * @return bool
     */
    public function removeItem(object $model, string $column, string $url): bool
    {
        if (!$model || empty($model->$column)) {
            return false;
        }

        $items = $model->$column;

        if (!is_array($items)) {
            return false;
        }

        // =========================
        // 1. Remove item from array
        // =========================
        $updated = array_values(array_filter(
            $items,
            fn($item) => $item !== $url
        ));

        // =========================
        // 2. Delete physical file
        // =========================
        $this->deleteFile($url);

        // =========================
        // 3. Save back to model
        // =========================
        $model->$column = $updated;
        $model->save();

        return true;
    }

    /**
     * Delete physical file (storage or public mode)
     */
    private function deleteFile(string $url): void
    {
        $driver = env('FILE_UPLOAD_DRIVER', 'storage');

        $path = parse_url($url, PHP_URL_PATH);

        if ($driver === 'storage') {

            $storagePath = str_replace('/storage/', '', $path);

            if (Storage::disk('public')->exists($storagePath)) {
                Storage::disk('public')->delete($storagePath);
            }
        } else {

            $fullPath = public_path(ltrim($path, '/'));

            if (file_exists($fullPath)) {
                unlink($fullPath);
            }
        }
    }
    public function addItems(object $model, string $column, array $newItems, bool $prepend = false): bool
    {
        $currentItems = $model->$column ?? [];

        if (!is_array($currentItems)) {
            $currentItems = [];
        }

        $updated = $prepend
            ? array_values(array_unique(array_merge($newItems, $currentItems)))
            : array_values(array_unique(array_merge($currentItems, $newItems)));

        $model->$column = $updated;
        $model->save();

        return true;
    }
}
// sample usage
// $this->arrayMediaService->removeItem($room, 'photos', $photoUrl);
