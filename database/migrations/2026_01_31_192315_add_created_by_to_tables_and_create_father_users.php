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
        // Add created_by to father_schedules
        Schema::table('father_schedules', function (Blueprint $table) {
            $table->foreignId('created_by')->nullable()->after('slot_duration')->constrained('users')->nullOnDelete();
        });

        // Add created_by to atraf
        Schema::table('atraf', function (Blueprint $table) {
            $table->foreignId('created_by')->nullable()->after('notes')->constrained('users')->nullOnDelete();
        });

        // Create father_users pivot table
        Schema::create('father_users', function (Blueprint $table) {
            $table->id();
            $table->foreignId('father_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('father_schedules', function (Blueprint $table) {
            $table->dropForeign(['created_by']);
            $table->dropColumn('created_by');
        });

        Schema::table('atraf', function (Blueprint $table) {
            $table->dropForeign(['created_by']);
            $table->dropColumn('created_by');
        });

        Schema::dropIfExists('father_users');
    }
};
