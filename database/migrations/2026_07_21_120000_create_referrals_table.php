<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('referrals', function (Blueprint $table) {
            $table->id();
            $table->string('referrer_name');
            $table->string('referrer_email');
            $table->string('referrer_organisation')->nullable();
            $table->string('referrer_role')->nullable();
            $table->string('referred_first_name');
            // Phone or email, only stored when consent_confirmed = true.
            $table->string('referred_contact')->nullable();
            // neet | justice | returner | unsure
            $table->string('cohort')->nullable();
            $table->text('context')->nullable();
            $table->boolean('consent_confirmed')->default(false);
            // website | event | partner
            $table->string('source')->default('website');
            // new | contacted | enrolled | closed
            $table->string('status')->default('new');
            $table->timestamps();

            $table->index('status');
            $table->index('cohort');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('referrals');
    }
};
