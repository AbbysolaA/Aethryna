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
                            {--force : Overwrite the target if it already exists}
                            {--heavy : Re-encode every speaker photo that is over the size budget, in place}';

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

        if ($this->option('heavy')) {
            return $this->fixHeavy();
        }

        return $this->argument('source')
            ? $this->convert()
            : $this->audit();
    }

    /**
     * Re-encode everything already over budget, in place.
     *
     * Nine of the thirteen photos on the site arrived straight from a camera
     * or a designer and are between 400KB and 1.2MB each. Individually none is
     * fatal; together they are several megabytes on pages that are mostly
     * text, and fixing them one command at a time is the kind of chore that
     * does not get done.
     */
    private function fixHeavy(): int
    {
        $over = PanelSpeaker::all()->filter(function (PanelSpeaker $speaker) {
            $path = $speaker->photo_path ? public_path($speaker->photo_path) : null;

            return $path && is_file($path) && filesize($path) > self::SIZE_WARNING;
        });

        if ($over->isEmpty()) {
            $this->info('Every speaker photo is already within budget.');

            return self::SUCCESS;
        }

        $this->line('Re-encoding '.$over->count().' '.($over->count() === 1 ? 'photo' : 'photos').' in place.');
        $this->newLine();

        $saved  = 0;
        $failed = 0;

        foreach ($over as $speaker) {
            $path = public_path($speaker->photo_path);

            // The output is always JPEG, so writing it back over a .png would
            // leave the server serving image/png bytes that are not a PNG.
            // Renaming instead would mean editing photo_path, which is a
            // seeder's decision and not this command's.
            if (! in_array(strtolower(pathinfo($path, PATHINFO_EXTENSION)), ['jpg', 'jpeg'], true)) {
                $this->warn(sprintf(
                    'Skipping %s (%s): output is JPEG and the path is not. Convert it explicitly, then update photo_path.',
                    $speaker->name,
                    basename($path)
                ));
                $failed++;

                continue;
            }

            $before = filesize($path);

            // Source and target are the same file. GD decodes fully into
            // memory before anything is written, so this is safe.
            if ($this->process($path, $path)) {
                $saved += $before - filesize($path);
            } else {
                $failed++;
            }
        }

        $this->newLine();
        $this->info($this->humanBytes(max(0, $saved)).' saved across '.($over->count() - $failed).' '.($over->count() - $failed === 1 ? 'photo' : 'photos').'.');

        if ($failed) {
            $this->warn($failed.' could not be processed. See the messages above.');
        }

        return $failed ? self::FAILURE : self::SUCCESS;
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
        $source = $this->resolve($this->argument('source'));
        $target = $this->argument('target') ?: pathinfo($source, PATHINFO_FILENAME).'.jpg';
        $target = $this->resolve($target);

        if (! is_file($source)) {
            $this->error('No such file: '.$source);

            return self::FAILURE;
        }

        if ($source !== $target && file_exists($target) && ! $this->option('force')) {
            $this->error(basename($target).' already exists. Pass --force to overwrite it.');

            return self::FAILURE;
        }

        return $this->process($source, $target) ? self::SUCCESS : self::FAILURE;
    }

    private function process(string $source, string $target): bool
    {
        $size    = max(64, (int) $this->option('size'));
        $quality = min(100, max(1, (int) $this->option('quality')));

        // Dimensions before decoding, because decoding is the expensive part
        // and the whole point is to know its cost in advance.
        $info = @getimagesize($source);

        if (! $info) {
            $this->error('Could not read '.basename($source).'. Supported: JPEG, PNG, GIF, WebP.');

            return false;
        }

        [$width, $height] = $info;

        if (! $this->reserveMemoryFor($width, $height, $size)) {
            return false;
        }

        $image = $this->read($source);

        if (! $image) {
            $this->error('Could not decode '.basename($source).'. Supported: JPEG, PNG, GIF, WebP.');

            return false;
        }

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

        // Read before writing, because --heavy re-encodes in place and the
        // source would otherwise be measured after it had been replaced.
        $before = filesize($source);

        imagedestroy($image);

        // imagejpeg writes no EXIF, so location and camera data from a phone
        // are dropped here rather than published on a speaker's photo.
        if (! imagejpeg($canvas, $target, $quality)) {
            $this->error('Could not write '.$target);
            imagedestroy($canvas);

            return false;
        }

        imagedestroy($canvas);
        clearstatcache(true, $target);

        $after = filesize($target);

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

        return true;
    }

    /**
     * Make room for the decode before attempting it.
     *
     * GD holds a decoded image as raw pixels, four bytes each, however well it
     * was compressed on disk. A 13MB PNG of a 4000x6000 headshot needs close
     * to 100MB to open, which is what exhausted the server's 128MB limit and
     * killed the command outright — a fatal error, not a catchable one, so it
     * has to be headed off before the decode rather than handled after it.
     *
     * Raising the limit is safe here in a way it would not be in a web
     * request: this is a one-shot CLI command run by hand, not something
     * serving concurrent traffic.
     */
    private function reserveMemoryFor(int $width, int $height, int $size): bool
    {
        // Source pixels plus the output canvas, with headroom for the
        // resample, then the framework's own footprint on top.
        $needed = (int) (($width * $height * 4 + $size * $size * 4) * 1.6)
            + memory_get_usage(true)
            + (16 * 1024 * 1024);

        $limit = $this->memoryLimitBytes();

        if ($limit < 0 || $limit >= $needed) {
            return true;   // unlimited, or already enough
        }

        @ini_set('memory_limit', (int) ceil($needed / 1048576).'M');

        if ($this->memoryLimitBytes() >= $needed || $this->memoryLimitBytes() < 0) {
            return true;
        }

        $this->error(sprintf(
            '%dx%d needs about %s to decode, and the memory limit is %s and cannot be raised from here.',
            $width,
            $height,
            $this->humanBytes($needed),
            $this->humanBytes($limit)
        ));
        $this->line('Try: <info>php -d memory_limit='.(int) ceil($needed / 1048576).'M artisan speakers:photo ...</info>');

        return false;
    }

    private function memoryLimitBytes(): int
    {
        $limit = trim((string) ini_get('memory_limit'));

        if ($limit === '' || $limit === '-1') {
            return -1;
        }

        $value = (int) $limit;

        return match (strtolower(substr($limit, -1))) {
            'g'     => $value * 1024 * 1024 * 1024,
            'm'     => $value * 1024 * 1024,
            'k'     => $value * 1024,
            default => $value,
        };
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
