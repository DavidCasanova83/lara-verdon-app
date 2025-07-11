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
        Schema::table('form_submissions', function (Blueprint $table) {
            // Index composite pour les filtres les plus courants
            $table->index(['city', 'created_at'], 'idx_city_created_at');
            $table->index(['city', 'country'], 'idx_city_country');
            $table->index(['city', 'department'], 'idx_city_department');
            $table->index(['city', 'profile'], 'idx_city_profile');
            
            // Index pour les recherches par email
            $table->index(['city', 'email'], 'idx_city_email');
            
            // Index pour les filtres de date combinés
            $table->index(['city', 'created_at', 'country'], 'idx_city_date_country');
            $table->index(['city', 'created_at', 'department'], 'idx_city_date_department');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('form_submissions', function (Blueprint $table) {
            $table->dropIndex('idx_city_created_at');
            $table->dropIndex('idx_city_country');
            $table->dropIndex('idx_city_department');
            $table->dropIndex('idx_city_profile');
            $table->dropIndex('idx_city_email');
            $table->dropIndex('idx_city_date_country');
            $table->dropIndex('idx_city_date_department');
        });
    }
};
