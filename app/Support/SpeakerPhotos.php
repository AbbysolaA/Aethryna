<?php

namespace App\Support;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

/**
 * Publishes a speaker headshot: whatever arrives is decoded with GD and
 * written back out as a web-sized square JPEG, the same discipline as the
 * speakers:photo command. Re-encoding strips EXIF (a phone photo carries
 * location data) and means nothing but pixels survives the trip from a
 * private upload to a public page.
 */
class SpeakerPhotos
{
    public const DISK = 'speaker_images';

    /** The square's edge. Matches the speakers:photo command's default. */
    private const SIZE = 800;

    /**
     * Decode, square-crop from the top (headshots lose feet, not faces),
     * shrink and save. Returns the photo_path to store, or null when the
     * bytes cannot be read as an image.
     */
    public static function publish(string $bytes, string $nameHint): ?string
    {
        if (! extension_loaded('gd')) {
            return null;
        }

        $image = @imagecreatefromstring($bytes);

        if (! $image) {
            return null;
        }

        $width  = imagesx($image);
        $height = imagesy($image);

        // The largest square that fits, kept from the top.
        $crop = min($width, $height);
        $srcX = intdiv($width - $crop, 2);
        $srcY = 0;

        // Never upscale: a small source enlarged is a blurry file bigger and
        // worse than the original.
        $out = min(self::SIZE, $crop);

        $canvas = imagecreatetruecolor($out, $out);

        // Transparency saved as JPEG comes out black; fill with white first.
        imagefill($canvas, 0, 0, imagecolorallocate($canvas, 255, 255, 255));
        imagecopyresampled($canvas, $image, 0, 0, $srcX, $srcY, $out, $out, $crop, $crop);
        imagedestroy($image);

        $name = (Str::slug(pathinfo($nameHint, PATHINFO_FILENAME)) ?: 'speaker')
            .'-'.Str::lower(Str::random(6)).'.jpg';

        ob_start();
        imagejpeg($canvas, null, 82);
        $jpeg = ob_get_clean();
        imagedestroy($canvas);

        Storage::disk(self::DISK)->put($name, $jpeg);

        return 'images/speakers/'.$name;
    }

    /** Delete a published photo, and only a published one: files outside the
     *  speakers directory (hand-placed team photos, say) are left alone. */
    public static function delete(?string $photoPath): void
    {
        if ($photoPath && str_starts_with($photoPath, 'images/speakers/')) {
            Storage::disk(self::DISK)->delete(basename($photoPath));
        }
    }
}
