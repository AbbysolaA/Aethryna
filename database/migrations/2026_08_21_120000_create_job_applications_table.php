<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Applications for paid roles, taken on the site.
 *
 * The vacancy page used to say "email your CV to hr@". That works until the
 * moment it matters: applications arrive as unstructured inbox threads, there
 * is no record of who applied for what or when, and the person triaging them
 * is the Founder, whose lack of time is the reason the first vacancy exists.
 *
 * Not a volunteer_engagement. That table carries an offer token, VA, NDA and
 * DBS gates, and logged hours, none of which describes hiring an employee, and
 * bolting a second lifecycle onto it would leave every query filtering one
 * kind out of the other. What a job application shares with a volunteer one is
 * only the CV columns, which follow the same private disk pattern.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('job_applications', function (Blueprint $table) {
            $table->id();

            // nullOnDelete rather than cascade: a cascade would bypass the
            // model's deleting event and orphan the CV files on disk. Role
            // deletion is refused in admin while applications exist anyway,
            // mirroring the engagement guard.
            $table->foreignId('volunteer_role_id')
                ->nullable()
                ->constrained('volunteer_roles')
                ->nullOnDelete();

            $table->string('name');
            $table->string('email');
            $table->string('phone')->nullable();

            // The covering note. The job description asks applicants to say
            // why the role fits them; this is where that answer lives.
            $table->text('cover_note');

            $table->string('portfolio_url')->nullable();

            // Same shape as the volunteer CV columns: random name on a private
            // disk, original name kept for the download.
            $table->string('cv_path')->nullable();
            $table->string('cv_original_name')->nullable();
            $table->string('cv_mime')->nullable();
            $table->unsignedInteger('cv_size')->nullable();

            // new -> shortlisted -> hired, or declined. A string rather than an
            // enum so adding a stage is an admin decision, not a migration.
            $table->string('status')->default('new');

            $table->timestamp('consented_at')->nullable();
            $table->timestamps();

            // The screens read newest-first per role.
            $table->index(['volunteer_role_id', 'created_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('job_applications');
    }
};
