<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * One person's stint in one volunteer role, from offer through to completion.
 *
 * user_id is deliberately nullable. Volunteers reach us through partner orgs,
 * panels and word of mouth as well as the website, so an offer has to be
 * sendable to someone who has never had an account. The engagement carries
 * offer_name/offer_email until the person signs in or registers, at which
 * point it binds to their user_id and those fields become history.
 *
 * "Volunteering" is not a stored status. It is derived from offer_accepted
 * plus starts_on having passed, so there is no scheduled job to flip and no
 * chance of the timeline disagreeing with the dates.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('volunteer_engagements', function (Blueprint $table) {
            $table->id();
            $table->foreignId('volunteer_role_id')->constrained()->cascadeOnDelete();

            // Null until the offer is claimed by a signed-in account.
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();

            $table->string('offer_name');
            $table->string('offer_email')->index();

            $table->enum('status', [
                'applied',
                'offer_extended',
                'offer_accepted',
                'offer_declined',
                'withdrawn',
                'complete',
            ])->default('offer_extended');

            // Single-use claim link sent in the offer email. Cleared once the
            // offer is answered so a forwarded email cannot be replayed.
            $table->string('offer_token', 64)->nullable()->unique();
            $table->timestamp('offer_expires_at')->nullable();

            $table->timestamp('applied_at')->nullable();
            $table->timestamp('offer_extended_at')->nullable();
            $table->timestamp('offer_responded_at')->nullable();

            $table->date('starts_on')->nullable();
            $table->date('ends_on')->nullable();
            $table->timestamp('completed_at')->nullable();

            // Onboarding gates, recorded by an admin as each comes back.
            $table->timestamp('agreement_signed_at')->nullable();
            $table->timestamp('nda_signed_at')->nullable();
            $table->timestamp('dbs_cleared_at')->nullable();

            $table->text('notes')->nullable();
            $table->timestamps();

            // The volunteer's own list, and the admin roster, both read by these.
            $table->index(['user_id', 'status']);
            $table->index(['volunteer_role_id', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('volunteer_engagements');
    }
};
