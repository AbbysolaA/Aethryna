<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * The onboarding pack, uploaded and managed from the admin.
 *
 * Replaces the hardcoded list in config/volunteering.php, which required a
 * deploy to change and left every entry with a null URL because the files were
 * never anywhere the site could serve them.
 *
 * Files land on the 'local' disk, which roots at storage/app/private and is
 * not web-reachable. They are served through a gated download route rather
 * than a direct link, because the handover brief and access checklist describe
 * internal state and should not sit on a guessable public URL.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('volunteer_documents', function (Blueprint $table) {
            $table->id();

            $table->string('label');
            $table->string('note')->nullable();

            // Relative to the 'local' disk root.
            $table->string('path');
            $table->string('original_name');
            $table->string('mime')->nullable();
            $table->unsignedBigInteger('size')->default(0);

            // Order in the welcome email. Lead with what has to come back signed.
            $table->unsignedSmallInteger('sort_order')->default(0);

            // Off keeps a document on file without listing it in the email.
            $table->boolean('is_active')->default(true);

            $table->timestamps();

            $table->index(['is_active', 'sort_order']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('volunteer_documents');
    }
};
