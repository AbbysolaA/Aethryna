<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Room for a paid job in the table that already holds roles.
 *
 * The organisation is hiring its first employee and there was nowhere to put
 * the post: volunteer_roles knows about titles, summaries and DBS gates, but
 * nothing about salary, hours, or where a paid applicant should send a CV.
 *
 * Extending rather than adding a second table, because a vacancy is a vacancy:
 * both kinds need a title, a description, an open/closed flag, an admin screen
 * and a public listing, and all of that already exists here. What differs is a
 * handful of employment facts, which are null for a volunteer role.
 *
 * The table name is now a slight lie. Renaming it would touch the foreign key
 * on volunteer_engagements, every route model binding and every admin URL, for
 * a cosmetic gain, so it stays and the model documents it.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('volunteer_roles', function (Blueprint $table) {
            /*
             * Enum values, not a boolean is_paid. "Contractor" is a real third
             * case for an organisation that buys in a facilitator for a term,
             * and the difference matters to whoever reads the listing.
             *
             * SQLite has no native enum and Laravel emits a check constraint;
             * on MySQL this is a real enum. Both accept the same values.
             */
            $table->string('engagement_type')->default('volunteer')->after('slug');

            // Free text on purpose. "£32,000" and "£180 per day" and "Salary
            // under review" are all things an early-stage organisation
            // legitimately needs to publish, and a decimal column can hold none
            // of them.
            $table->string('compensation')->nullable()->after('engagement_type');

            $table->string('employment_basis')->nullable()->after('compensation'); // Full-time, Part-time...
            $table->string('location')->nullable()->after('employment_basis');
            $table->string('reports_to')->nullable()->after('location');

            /*
             * Where an application actually goes.
             *
             * The volunteer form asks for availability and no CV, which is the
             * wrong shape for a paid post, and the job description says to
             * email a CV and portfolio. So a paid role routes to an inbox
             * rather than to /volunteer/apply.
             */
            $table->string('apply_email')->nullable()->after('reports_to');
            $table->text('apply_instructions')->nullable()->after('apply_email');

            // Nullable because "until we find the right person" is how a small
            // organisation actually recruits.
            $table->date('closes_at')->nullable()->after('apply_instructions');

            /*
             * The body of the job description, as [{heading, items[]}].
             *
             * A job description is a series of headed lists: what you will
             * own, what you need, nice to have. Storing it as one blob of prose
             * would mean either publishing unformatted text or parsing markup
             * at render. Same shape as the event itinerary.
             */
            $table->json('sections')->nullable()->after('closes_at');
        });
    }

    public function down(): void
    {
        Schema::table('volunteer_roles', function (Blueprint $table) {
            $table->dropColumn([
                'engagement_type', 'compensation', 'employment_basis', 'location',
                'reports_to', 'apply_email', 'apply_instructions', 'closes_at', 'sections',
            ]);
        });
    }
};
