<?php

namespace Tests\Feature;

use App\Models\PanelSpeaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Artisan;
use Tests\TestCase;

/**
 * Preparing speaker headshots, and noticing when one is missing.
 *
 * The bug being guarded against is a quiet one. photoUrl() falls back to a
 * generated initials avatar when the file is absent, so a photo uploaded under
 * the wrong name breaks nothing and reports nothing — the page just shows the
 * wrong picture until somebody looks. That is exactly what happened with the
 * Panel 3 uploads.
 */
class SpeakerPhotoCommandTest extends TestCase
{
    use RefreshDatabase;

    private string $dir;

    protected function setUp(): void
    {
        parent::setUp();

        $this->dir = public_path('images/speakers');

        if (! is_dir($this->dir)) {
            mkdir($this->dir, 0755, true);
        }
    }

    protected function tearDown(): void
    {
        foreach (glob($this->dir.'/__test-*') as $file) {
            @unlink($file);
        }

        parent::tearDown();
    }

    /**
     * A tall, transparent PNG — the shape and format a phone or a designer
     * actually hands over.
     */
    private function makeSource(string $name, int $w = 1200, int $h = 1600, bool $noisy = false): string
    {
        $path  = $this->dir.'/'.$name;
        $image = imagecreatetruecolor($w, $h);

        imagesavealpha($image, true);
        imagefill($image, 0, 0, imagecolorallocatealpha($image, 0, 0, 0, 127));

        // Flat colour compresses to almost nothing, so a fixture that has to
        // start out over the size budget needs detail in it.
        if ($noisy) {
            mt_srand(1);
            for ($i = 0; $i < $w * $h / 8; $i++) {
                imagesetpixel(
                    $image,
                    mt_rand(0, $w - 1),
                    mt_rand(0, $h - 1),
                    imagecolorallocate($image, mt_rand(0, 255), mt_rand(0, 255), mt_rand(0, 255))
                );
            }
        }

        // A marker band across the top, so the crop's gravity is observable.
        imagefilledrectangle($image, 0, 0, $w, (int) ($h * 0.1), imagecolorallocate($image, 255, 0, 0));
        imagepng($image, $path);
        imagedestroy($image);

        return $path;
    }

    public function test_it_produces_a_square_jpeg_within_budget(): void
    {
        $this->makeSource('__test-source.png');

        $this->artisan('speakers:photo', [
            'source' => '__test-source.png',
            'target' => '__test-out.jpg',
        ])->assertSuccessful();

        $out = $this->dir.'/__test-out.jpg';
        $this->assertFileExists($out);

        [$width, $height, $type] = getimagesize($out);

        $this->assertSame(800, $width);
        $this->assertSame(800, $height, 'Panel pages lay photos out in a grid, so the output has to be square.');
        $this->assertSame(IMAGETYPE_JPEG, $type);
        $this->assertLessThan(150 * 1024, filesize($out), 'A headshot over 150KB costs more than it is worth.');
    }

    /**
     * Transparency saved as JPEG comes out black unless it is flattened first,
     * which would turn a cut-out headshot into a silhouette.
     */
    public function test_it_flattens_transparency_to_white(): void
    {
        $this->makeSource('__test-alpha.png');

        $this->artisan('speakers:photo', [
            'source' => '__test-alpha.png',
            'target' => '__test-alpha-out.jpg',
        ])->assertSuccessful();

        $image = imagecreatefromjpeg($this->dir.'/__test-alpha-out.jpg');
        $rgb   = imagecolorsforindex($image, imagecolorat($image, 400, 700));

        $this->assertGreaterThan(240, $rgb['red']);
        $this->assertGreaterThan(240, $rgb['green']);
        $this->assertGreaterThan(240, $rgb['blue']);
    }

    /**
     * On a headshot the face sits near the top, so centre-cropping a tall
     * portrait is how you behead someone.
     */
    public function test_north_gravity_keeps_the_top_of_the_frame(): void
    {
        $this->makeSource('__test-gravity.png');

        $this->artisan('speakers:photo', [
            'source' => '__test-gravity.png',
            'target' => '__test-gravity-out.jpg',
        ])->assertSuccessful();

        $image = imagecreatefromjpeg($this->dir.'/__test-gravity-out.jpg');
        $rgb   = imagecolorsforindex($image, imagecolorat($image, 400, 20));

        $this->assertGreaterThan(200, $rgb['red']);
        $this->assertLessThan(60, $rgb['green'], 'The marker band at the top of the source should survive the crop.');
    }

    public function test_it_refuses_to_overwrite_without_force(): void
    {
        $this->makeSource('__test-src2.png');
        file_put_contents($this->dir.'/__test-existing.jpg', 'not really a jpeg');

        $this->artisan('speakers:photo', [
            'source' => '__test-src2.png',
            'target' => '__test-existing.jpg',
        ])->assertFailed();

        $this->assertSame('not really a jpeg', file_get_contents($this->dir.'/__test-existing.jpg'));

        $this->artisan('speakers:photo', [
            'source'  => '__test-src2.png',
            'target'  => '__test-existing.jpg',
            '--force' => true,
        ])->assertSuccessful();

        $this->assertSame(IMAGETYPE_JPEG, getimagesize($this->dir.'/__test-existing.jpg')[2]);
    }

    /**
     * Enlarging a small source produces a bigger, blurrier file than the
     * original — worse on both counts.
     */
    public function test_it_does_not_upscale(): void
    {
        $this->makeSource('__test-small.png', 300, 300);

        $this->artisan('speakers:photo', [
            'source' => '__test-small.png',
            'target' => '__test-small-out.jpg',
        ])->assertSuccessful();

        $this->assertSame(300, getimagesize($this->dir.'/__test-small-out.jpg')[0]);
    }

    /**
     * A big photo raises the memory limit rather than dying on it.
     *
     * GD holds a decoded image as raw pixels — four bytes each, however well
     * compressed the file was. A 13MB PNG of a 4000x6000 headshot needs close
     * to 100MB to open, which killed the command outright on the server's
     * 128MB limit. It is a fatal error, not a catchable one, so it has to be
     * headed off before the decode.
     */
    public function test_it_raises_the_memory_limit_for_a_large_source(): void
    {
        $original = ini_get('memory_limit');

        // Comfortably above what PHPUnit is already using, and far below what
        // a 3000x3000 decode needs (~36MB of pixels alone).
        $floor = memory_get_usage(true) + (24 * 1024 * 1024);
        ini_set('memory_limit', (int) ceil($floor / 1048576).'M');
        $before = $this->limitInBytes();

        try {
            $this->makeSource('__test-huge.png', 3000, 3000);

            $this->artisan('speakers:photo', [
                'source' => '__test-huge.png',
                'target' => '__test-huge-out.jpg',
            ])->assertSuccessful();

            $this->assertGreaterThan(
                $before,
                $this->limitInBytes(),
                'The limit should have been raised to fit the decode.'
            );
            $this->assertSame(800, getimagesize($this->dir.'/__test-huge-out.jpg')[0]);
        } finally {
            ini_set('memory_limit', $original);
        }
    }

    private function limitInBytes(): int
    {
        $limit = trim((string) ini_get('memory_limit'));

        if ($limit === '-1') {
            return PHP_INT_MAX;
        }

        return match (strtolower(substr($limit, -1))) {
            'g'     => (int) $limit * 1073741824,
            'm'     => (int) $limit * 1048576,
            'k'     => (int) $limit * 1024,
            default => (int) $limit,
        };
    }

    /**
     * --heavy re-encodes in place, so the file it measures as "before" is the
     * same one it is about to overwrite.
     */
    public function test_heavy_reencodes_oversized_photos_in_place(): void
    {
        // A .jpg path, because that is what every speaker photo actually uses
        // and what --heavy will act on. The bytes start as PNG; the extension
        // is what the command keys off.
        $this->makeSource('__test-heavy.jpg', 1600, 1600, noisy: true);
        $path = $this->dir.'/__test-heavy.jpg';

        PanelSpeaker::create([
            'name'       => 'Heavy Photo',
            'title'      => 'Speaker',
            'company'    => 'Test Co',
            'bio'        => 'A speaker whose photo is far too large.',
            'photo_path' => 'images/speakers/__test-heavy.jpg',
        ]);

        $before = filesize($path);
        $this->assertGreaterThan(150 * 1024, $before, 'The fixture needs to start over budget.');

        Artisan::call('speakers:photo', ['--heavy' => true]);
        clearstatcache(true, $path);

        // Not an absolute budget: the fixture is pure noise, which is the worst
        // case JPEG can be handed. What --heavy promises is a large reduction
        // in place, and the absolute budget is covered above with a realistic
        // source.
        $after = filesize($path);

        $this->assertLessThan($before / 2, $after, 'Re-encoding should at least halve an oversized photo.');
        $this->assertSame(800, getimagesize($path)[0], 'It should have been resized down, not merely recompressed.');
        $this->assertSame(IMAGETYPE_JPEG, getimagesize($path)[2], 'Re-encoded in place, so the path is unchanged even though the format is not.');
        $this->assertStringContainsString('saved across 1 photo', Artisan::output());
    }

    public function test_the_audit_names_speakers_whose_photo_is_absent(): void
    {
        PanelSpeaker::create([
            'name'       => 'Nobody Uploaded Me',
            'title'      => 'Speaker',
            'company'    => 'Test Co',
            'bio'        => 'A speaker whose photo never arrived.',
            'photo_path' => 'images/speakers/__test-never-uploaded.jpg',
        ]);

        // Artisan::call rather than $this->artisan(), because the latter mocks
        // the output style and never sees what table() wrote — which is most of
        // what this command says.
        $this->assertSame(0, Artisan::call('speakers:photo'));

        $output = Artisan::output();

        $this->assertStringContainsString('Nobody Uploaded Me', $output);
        $this->assertStringContainsString('images/speakers/__test-never-uploaded.jpg', $output);
        $this->assertStringContainsString('MISSING', $output);
        $this->assertStringContainsString('1 photo is missing', $output);
    }

    public function test_the_audit_is_quiet_when_every_photo_is_present(): void
    {
        $this->makeSource('__test-present.png');

        Artisan::call('speakers:photo', [
            'source' => '__test-present.png',
            'target' => '__test-present.jpg',
        ]);

        PanelSpeaker::create([
            'name'       => 'Fully Illustrated',
            'title'      => 'Speaker',
            'company'    => 'Test Co',
            'bio'        => 'A speaker whose photo arrived.',
            'photo_path' => 'images/speakers/__test-present.jpg',
        ]);

        Artisan::call('speakers:photo');

        $this->assertStringContainsString(
            'Every speaker photo is present and a sensible size.',
            Artisan::output()
        );
    }
}
