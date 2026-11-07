<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Validation\ValidationException;

/**
 * A customer's logo and brand assets for the Budget Planner pictures.
 * Kept privately per account (storage/app/planner-brand/{userid}), re-saved as PNG so only
 * a clean picture is ever kept or sent to the image model.
 */
class PlannerBrandFiles
{
    const MAX_ASSETS = 3;
    const MAX_KB = 5120;
    const MAX_SIDE = 1024;

    public function list($userId)
    {
        $files = $this->files($userId);
        return [
            'logo' => isset($files['logo']) ? ['id' => 'logo', 'url' => route('planner.brand.show', 'logo')] : null,
            'assets' => array_values(array_map(function ($id) {
                return ['id' => $id, 'url' => route('planner.brand.show', $id)];
            }, array_values(array_filter(array_keys($files), function ($id) { return $id !== 'logo'; })))),
        ];
    }

    /** @return array id => full path, logo first, then assets oldest first */
    public function files($userId)
    {
        $paths = glob($this->dir($userId) . '/*.png') ?: [];
        usort($paths, function ($a, $b) { return filemtime($a) <=> filemtime($b) ?: strcmp($a, $b); });
        $files = [];
        foreach ($paths as $path) {
            $files[basename($path, '.png')] = $path;
        }
        if (isset($files['logo'])) {
            $files = ['logo' => $files['logo']] + $files;
        }
        return $files;
    }

    public function path($userId, $id)
    {
        return $this->files($userId)[$id] ?? null;
    }

    public function store($userId, $kind, UploadedFile $file)
    {
        $files = $this->files($userId);
        if ($kind === 'asset' && count($files) - (isset($files['logo']) ? 1 : 0) >= self::MAX_ASSETS) {
            throw ValidationException::withMessages(['file' => 'You can add up to ' . self::MAX_ASSETS . ' brand assets. Remove one first.']);
        }
        $image = @imagecreatefromstring((string) file_get_contents($file->getRealPath()));
        if (!$image) {
            throw ValidationException::withMessages(['file' => 'Please choose a JPG, PNG or WEBP picture.']);
        }

        imagepalettetotruecolor($image);
        imagealphablending($image, false);
        imagesavealpha($image, true);
        if (max(imagesx($image), imagesy($image)) > self::MAX_SIDE) {
            $image = imagesx($image) >= imagesy($image) ? imagescale($image, self::MAX_SIDE) : imagescale($image, (int) round(imagesx($image) * self::MAX_SIDE / imagesy($image)), self::MAX_SIDE);
            imagealphablending($image, false);
            imagesavealpha($image, true);
        }

        $dir = $this->dir($userId);
        if (!is_dir($dir)) {
            mkdir($dir, 0755, true);
        }
        $id = $kind === 'logo' ? 'logo' : 'asset-' . bin2hex(random_bytes(6));
        imagepng($image, $dir . '/' . $id . '.png', 6);
        imagedestroy($image);
        return $id;
    }

    public function remove($userId, $id)
    {
        if ($path = $this->path($userId, $id)) {
            unlink($path);
        }
    }

    /** Changes whenever the logo or assets change, so pictures made with them are kept apart. */
    public function fingerprint($userId)
    {
        $files = $this->files($userId);
        return $files ? sha1(implode('|', array_map(function ($id, $path) { return $id . ':' . sha1_file($path); }, array_keys($files), $files))) : '';
    }

    public function dataUrl($path)
    {
        return 'data:image/png;base64,' . base64_encode(file_get_contents($path));
    }

    private function dir($userId)
    {
        return storage_path('app/planner-brand/' . (int) $userId);
    }
}
