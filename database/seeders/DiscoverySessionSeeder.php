<?php

namespace Database\Seeders;

use App\Models\PanelSession;
use Illuminate\Database\Seeder;

/**
 * The Community Discovery Session, Birkenhead, 29 August 2026.
 *
 * The first time Skills Co-op meets its community in a room rather than on a
 * call, and the first event with a door, a postcode and a fire limit. It is
 * stored as a panel_session because that is where events live and where
 * registrations already point; what makes it different is the venue, the
 * capacity and the itinerary, which the online panels have no use for.
 *
 * updateOrCreate on the slug, so running this twice is safe and so the copy can
 * be corrected by re-running rather than by editing rows by hand.
 */
class DiscoverySessionSeeder extends Seeder
{
    public function run(): void
    {
        PanelSession::updateOrCreate(
            ['slug' => 'discovery-session'],
            [
                'title'    => 'Skills Co-op Community Discovery Session',
                'tagline'  => 'Rise. Learn. Become.',
                'duration' => '3 hours',
                'format'   => 'In person',

                // Saturday afternoon on purpose. A weekday event asks people in
                // work, in caring, or on a placement to choose between coming
                // and the thing that pays.
                'event_date' => '2026-08-29 12:30:00',

                'description' => 'A free, informal taster afternoon introducing Skills Co-op to the local community, ahead of the full pilot launching January 2027. No experience or qualifications needed, and no obligation to sign up to anything on the day.',

                'venue_name'    => 'Wirral Multicultural Organisation',
                'venue_address' => '111 Conway Street, Birkenhead, CH41 4AF',

                /*
                 * Both channels run in parallel. The site's own form is the
                 * primary route, because it needs no account and no third-party
                 * redirect, but plenty of people would rather use the thing they
                 * already have a login for, and refusing them that costs a
                 * registration.
                 *
                 * The aff code is ours rather than the oddtdtcreator one
                 * Eventbrite attaches when you copy the link out of the
                 * dashboard. That default means nothing; this one makes
                 * Eventbrite's traffic sources report say how many people came
                 * from this website, which is a question worth being able to
                 * answer.
                 */
                'eventbrite_url' => 'https://www.eventbrite.co.uk/e/1996441615615?aff=skillscooporg',

                // Confirmed with the venue directly rather than assumed from a
                // listing. Someone deciding whether they can physically get in
                // needs this to be true, not likely.
                'accessibility_note' => 'Step-free access throughout the venue, including the toilets. We confirmed this with the venue directly.',

                // The room's limit. Registration stays open past it and becomes
                // a waiting list rather than a closed door.
                'capacity' => 35,

                'itinerary' => [
                    ['time' => '12.30pm', 'what' => 'Doors open', 'detail' => 'Registration and refreshments. Come when you can, there is no wrong time to arrive.'],
                    ['time' => '12.50pm', 'what' => 'Welcome', 'detail' => 'What Skills Co-op is, who it is for, and what the 25-week programme actually involves.'],
                    ['time' => '1.10pm', 'what' => 'Small-group conversations', 'detail' => 'Round a table, not a lecture. Your questions, and what would need to be true for this to work for you.'],
                    ['time' => '2.00pm', 'what' => 'Hands-on taster', 'detail' => 'A short piece of the actual learning, so you can see what a session feels like before deciding anything.'],
                    ['time' => '2.40pm', 'what' => 'Short talks and Q&A', 'detail' => 'Real routes into digital work, including tech sales and tech support.'],
                    ['time' => '3.00pm', 'what' => 'Refreshments and one-to-ones', 'detail' => 'Time to talk to someone on your own if you would rather not ask in front of a group.'],
                    ['time' => '3.20pm', 'what' => 'Close', 'detail' => 'Away by half past, with nothing to sign.'],
                ],

                // Its own page rather than /sessions/{slug}: this URL goes on
                // printed flyers and gets read aloud, so it needs to survive
                // being typed from memory.
                'landing_path' => '/discovery-session',

                'status'     => 'upcoming',
                'sort_order' => 0,
            ]
        );
    }
}
