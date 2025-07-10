<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('form_submissions', function (Blueprint $table) {
            $table->id();
            $table->string('city');
            $table->string('country');
            $table->string('department')->nullable();
            $table->string('email');
            $table->boolean('consent_newsletter')->default(false);
            $table->boolean('consent_data_processing');
            $table->string('profile');
            $table->json('age_groups');
            $table->json('specific_requests');
            $table->json('general_requests');
            $table->text('other_request')->nullable();
            $table->timestamps();

            $table->index(['city', 'created_at']);
            $table->index('email');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('form_submissions');
    }
};
