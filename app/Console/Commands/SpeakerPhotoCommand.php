<?php

namespace App\Console\Commands;

use App\Models\PanelSpeaker;
use Illuminate\Console\Command;

/**
 * Prepare a speaker headshot for the web, and audit the ones already there.
 *
 * Two problems this solves, both of which have already bitten us.
 *
 * The first is silence. PanelSpeaker::photoUrl() checks the file exists and
 * falls back to a generated initials avatar when it does not, so a photo
 * uploaded under the wrong name produces no error anywhere — the page just
 * quietly shows the wrong thing, and you only find out by looking. Running
 * this with no arguments reports every speaker whose file is missing.
 *
 * The second is weight. Photos arrive straight from a phone or a designer:
 * the four Panel 3 headshots came in at 21MB between them, one of them 13MB
 * on its own. That is the entire performance budget for the page spent on
 * four faces. Anything over about 150KB is flagged.
 *
 * Uses GD rather than ImageMagick, because the server has GD and does not have
 * ImageMagick.
 */
class SpeakerPhotoCommand extends Command
{
    protected $signature = 'speakers:photo
                            {source? : File to convert, relative to public/images/speakers}
                            {target? : Name to save it as, e.g. bola-soko.jpg}
                            {--size=800 : Width and height of the square output}
                            {--quality=82 : JPEG quality, 0-100}
                            {--gravity=north : Which edge to keep when cropping — north, center or south}
                            {--force : Overwrite the target if it already exists}';

    protected $description = 'Convert a speaker headshot to a web-sized square, or audit the existing ones';

    /** Above this, a headshot is costing more than it is worth. */
    private const SIZE_WARNING = 150 * 1024;

    private const DIR = 'images/speakers';

    public function handle(): int
    {
        if (! extension_loaded('gd')) {
            $this->error('The PHP GD extension is not loaded, so images cannot be resized here.');

            return self::FAILURE;
        }

        return $this->argument('source')
            ? $this->convert()
            : $this->audit();
    }

    /**
     * What every panel page is actually going to render.
     *
     * Reads the database rather than the directory, because a file nobody
     * points at is harmless and a pointer with no file is the bug.
     */
    private function audit(): int
    {
        // Speakers belong to panels through the session pivot, so the panel
        // column is assembled from the relation rather than a column.
        $speakers = PanelSpeaker::with('sessions:id,title')->orderBy('name')->get();

        if ($speakers->isEmpty()) {
            $this->warn('No speakers in the database.');

            return self::SUCCESS;
        }

        $rows    = [];
        $missing = 0;
        $heavy   = 0;

        foreach ($speakers as $speaker) {
            $path   = $speaker->photo_path ? public_path($speaker->photo_path) : null;
            $exists = $path && file_exists($path);
            $bytes  = $exists ? filesize($path) : 0;

            if (! $exists) {
                $missing++;
                $status = '<fg=red>MISSING — showing initials</>';
            } elseif ($bytes > self::SIZE_WARNING) {
                $heavy++;
                $status = '<fg=yellow>too heavy</>';
            } else {
                $status = '<fg=green>ok</>';
            }

            $rows[] = [
                $speaker->sessions->pluck('title')->implode(', ') ?: '—',
                $speaker->name,
                $speaker->photo_path ?: '(none set)',
                $exists ? $this->humanBytes($bytes) : '—',
                $status,
            ];
        }

        $this->table(['Session', 'Speaker', 'Expected file', 'Size', 'Status'], $rows);

        if ($missing) {
            $this->newLine();
            $this->warn($missing.' '.($missing === 1 ? 'photo is' : 'photos are').' missing. Those speakers render as initials, with no error anywhere.');
            $this->line('Fix one with: <info>php artisan speakers:photo "Some Upload.png" expected-name.jpg</info>');
        }

        if ($heavy) {
            $this->newLine();
            $this->warn($heavy.' '.($heavy === 1 ? 'photo is' : 'photos are').' over '.$this->humanBytes(self::SIZE_WARNING).'. Re-run them through this command to shrink them.');
        }

        if (! $missing && ! $heavy) {
            $this->newLine();
            $this->info('Every speaker photo is present and a sensible size.');
        }

        return self::SUCCESS;
    }

    /**
     * Square-crop, resize and re-encode one upload.
     *
     * Cropping is "cover", not "fit": the output is always square because the
     * panel pages lay the photos out in a grid, and a stray portrait would
     * break the row. Which part survives the crop is the --gravity option,
     * defaulting to north — on a headshot the face is near the top, and
     * centre-cropping a tall photo is how you behead someone.
     */
    private function convert(): int
    {
        $size    = max(64, (int) $this->option('size'));
        $quality = min(100, max(1, (int) $this->option('quality')));

        $source = $this->resolve($this->argument('source'));
        $target = $this->argument('target') ?: pathinfo($source, PATHINFO_FILENAME).'.jpg';
        $target = $this->resolve($target);

        if (! is_file($source)) {
            $this->error('No such file: '.$source);

            return self::FAILURE;
        }

        if (file_exists($target) && ! $this->option('force')) {
            $this->error(basename($target).' already exists. Pass --force to overwrite it.');

            return self::FAILURE;
        }

        $image = $this->read($source);

        if (! $image) {
            $this->error('Could not read '.basename($source).'. Supported: JPEG, PNG, GIF, WebP.');

            return self::FAILURE;
        }

        $width  = imagesx($image);
        $height = imagesy($image);

        // The largest square that fits inside the source, positioned by gravity.
        $crop = min($width, $height);
        $srcX = intdiv($width - $crop, 2);
        $srcY = match ($this->option('gravity')) {
            'center', 'centre' => intdiv($height - $crop, 2),
            'south'            => $height - $crop,
            default            => 0,   // north
        };

        // Never upscale. A 300px source enlarged to 800 is a blurry 800px file,
        // bigger and worse than the original.
        $out = min($size, $crop);

        $canvas = imagecreatetruecolor($out, $out);

        // PNGs and WebPs can be transparent, and transparency saved as JPEG
        // comes out black. Fill with white first.
        imagefill($canvas, 0, 0, imagecolorallocate($canvas, 255, 255, 255));
        imagecopyresampled($canvas, $image, 0, 0, $srcX, $srcY, $out, $out, $crop, $crop);

        // imagejpeg writes no EXIF, so location and camera data from a phone
        // are dropped here rather than published on a speaker's photo.
        if (! imagejpeg($canvas, $target, $quality)) {
            $this->error('Could not write '.$target);

            return self::FAILURE;
        }

        imagedestroy($canvas);
        imagedestroy($image);

        $before = filesize($source);
        $after  = filesize($target);

        $this->info(sprintf(
            '%s → %s  (%dx%d %s → %dx%d %s, %d%% smaller)',
            basename($source),
            basename($target),
            $width,
            $height,
            $this->humanBytes($before),
            $out,
            $out,
            $this->humanBytes($after),
            $before > 0 ? round((1 - $after / $before) * 100) : 0
        ));

        if ($out < $size) {
            $this->warn(sprintf('Source was only %dpx on its short side, so the output is %dpx rather than %dpx.', $crop, $out, $size));
        }

        return self::SUCCESS;
    }

    /** Bare names are relative to the speakers directory; paths are left alone. */
    private function resolve(string $name): string
    {
        return str_contains($name, '/')
            ? (str_starts_with($name, '/') ? $name : public_path($name))
            : public_path(self::DIR.'/'.$name);
    }

    private function read(string $path): ?\GdImage
    {
        $type = @exif_imagetype($path) ?: (getimagesize($path)[2] ?? null);

        $image = match ($type) {
            IMAGETYPE_JPEG => @imagecreatefromjpeg($path),
            IMAGETYPE_PNG  => @imagecreatefrompng($path),
            IMAGETYPE_GIF  => @imagecreatefromgif($path),
            IMAGETYPE_WEBP => @imagecreatefromwebp($path),
            default        => false,
        };

        return $image ?: null;
    }

    private function humanBytes(int $bytes): string
    {
        return $bytes >= 1048576
            ? round($bytes / 1048576, 1).'MB'
            : round($bytes / 1024).'KB';
    }
}
