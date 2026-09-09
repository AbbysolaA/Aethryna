<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

/**
 * Pictures for blog posts.
 *
 * A shared picture library rather than per-post attachments: the same photo
 * of the room turns up in the recap, the impact page draft and next month's
 * newsletter post, and a writer should upload it once. Files live on the
 * blog_images disk (public/images/blog), which the web server serves
 * directly with no storage symlink for a deploy to forget.
 *
 * Uploads are re-encoded, never trusted: whatever arrives is decoded with GD
 * and written back out as a fresh file, so a "picture" carrying anything
 * else does not survive, and phone photos measured in megabytes come out
 * page-sized.
 */
class PostImageController extends Controller
{
    private const DISK = 'blog_images';

    /** Wide enough for the 760px article column on a retina screen. */
    private const MAX_WIDTH = 1600;

    /**
     * The library, newest first, for the form's picture panel.
     *
     * @return array<int, array{name: string, url: string}>
     */
    public static function listing(): array
    {
        $disk = Storage::disk(self::DISK);

        return collect($disk->files())
            ->filter(fn ($f) => preg_match('/\.(jpe?g|png|webp|gif)$/i', $f))
            ->sortByDesc(fn ($f) => $disk->lastModified($f))
            ->map(fn ($f) => ['name' => basename($f), 'url' => '/images/blog/'.basename($f)])
            ->values()
            ->all();
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'picture' => ['required', 'file', 'image', 'mimes:jpg,jpeg,png,webp,gif', 'max:8192'],
        ], [
            'picture.max'   => 'Keep pictures under 8MB. Most phone photos shrink well below that once uploaded here.',
            'picture.mimes' => 'Use a JPEG, PNG, WebP or GIF.',
        ]);

        $upload = $request->file('picture');

        $name = $this->reencode($upload->getRealPath(), $upload->getClientOriginalName());

        if (! $name) {
            return back()->with('error', 'That file could not be read as a picture. Try exporting it as a JPEG and uploading again.');
        }

        return back()->with('status', 'Picture uploaded. Copy its line below into the post where the picture should appear.');
    }

    public function destroy(string $image): RedirectResponse
    {
        // The route constrains the parameter to a plain filename, so this
        // cannot reach outside the directory; the existence check keeps the
        // flash message honest.
        abort_unless(Storage::disk(self::DISK)->exists($image), 404);

        Storage::disk(self::DISK)->delete($image);

        return back()->with('status', 'Picture deleted. Posts still referencing it will show a gap, so check anywhere it was used.');
    }

    /**
     * Decode, shrink to the article column's needs, and write a fresh file.
     * Returns the stored filename, or null when GD cannot read the upload.
     *
     * Animated GIFs are the exception: GD would flatten them to one frame,
     * so they are stored as they came, size cap and image validation still
     * applied.
     */
    private function reencode(string $tmpPath, string $originalName): ?string
    {
        $info = @getimagesize($tmpPath);

        if (! $info) {
            return null;
        }

        [$width, $height, $type] = $info;

        $base = Str::slug(pathinfo($originalName, PATHINFO_FILENAME)) ?: 'picture';
        $base = Str::limit($base, 40, '').'-'.Str::lower(Str::random(6));

        if ($type === IMAGETYPE_GIF) {
            Storage::disk(self::DISK)->put($base.'.gif', file_get_contents($tmpPath));

            return $base.'.gif';
        }

        // Decoding needs the raw pixels in memory. An 8MB phone JPEG can be
        // 8000px across, which is far more than the default limit; same
        // arithmetic as the speakers:photo command.
        $needed = (int) ($width * $height * 4 * 1.8) + memory_get_usage(true) + 32 * 1024 * 1024;
        if ($needed > $this->memoryLimitBytes()) {
            @ini_set('memory_limit', (string) ceil($needed / 1048576).'M');
        }

        $image = match ($type) {
            IMAGETYPE_JPEG => @imagecreatefromjpeg($tmpPath),
            IMAGETYPE_PNG  => @imagecreatefrompng($tmpPath),
            IMAGETYPE_WEBP => @imagecreatefromwebp($tmpPath),
            default        => null,
        };

        if (! $image) {
            return null;
        }

        if ($width > self::MAX_WIDTH) {
            $scaled = imagescale($image, self::MAX_WIDTH);
            imagedestroy($image);
            $image = $scaled;
        }

        // PNG keeps transparency (logos, screenshots); everything else
        // becomes JPEG, which is what photographs should be on a page.
        if ($type === IMAGETYPE_PNG) {
            imagesavealpha($image, true);
            ob_start();
            imagepng($image, null, 9);
            $name = $base.'.png';
        } else {
            ob_start();
            imagejpeg($image, null, 82);
            $name = $base.'.jpg';
        }

        $bytes = ob_get_clean();
        imagedestroy($image);

        Storage::disk(self::DISK)->put($name, $bytes);

        return $name;
    }

    private function memoryLimitBytes(): int
    {
        $limit = ini_get('memory_limit');

        if ($limit === false || $limit === '-1') {
            return PHP_INT_MAX;
        }

        return (int) $limit * match (strtoupper(substr($limit, -1))) {
            'G'     => 1073741824,
            'M'     => 1048576,
            'K'     => 1024,
            default => 1,
        };
    }
}
