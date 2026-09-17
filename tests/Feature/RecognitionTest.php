<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * Third-party recognition, shown where trust is being weighed.
 *
 * Config-driven so the next shortlisting is one entry in
 * config/organisation.php; these tests pin the plumbing from that entry to
 * the two pages and the structured data.
 */
class RecognitionTest extends TestCase
{
    use RefreshDatabase;

    public function test_the_home_and_impact_pages_carry_the_recognition_strip(): void
    {
        foreach (['/', '/impact'] as $page) {
            $this->get($page)
                ->assertOk()
                ->assertSee('Shortlisted for the Tech for Good Award, Skills and Employment')
                ->assertSee('prolificnorth.co.uk', false);
        }
    }

    public function test_the_organisation_structured_data_names_the_award(): void
    {
        $this->get('/')
            ->assertOk()
            ->assertSee('"award"', false)
            ->assertSee('Tech for Good Award', false);
    }

    /**
     * The directory listing lives on the pages whose readers use
     * directories, and stays out of the award strip on purpose.
     */
    public function test_the_infobank_listing_sits_on_refer_and_partners_but_not_the_strip(): void
    {
        foreach (['/refer', '/partners'] as $page) {
            $this->get($page)
                ->assertOk()
                ->assertSee('Wirral InfoBank')
                ->assertSee('wirralinfobank.co.uk/Services/16439', false);
        }

        $this->get('/')->assertOk()->assertDontSee('Wirral InfoBank');
    }

    public function test_an_empty_recognition_list_renders_no_strip(): void
    {
        config(['organisation.recognition' => []]);

        $this->get('/')
            ->assertOk()
            ->assertDontSee('rec-strip');
    }
}
