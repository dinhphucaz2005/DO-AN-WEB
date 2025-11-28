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
        Schema::table('memes', function (Blueprint $table) {
            // Drop old image_path column
            $table->dropColumn('image_path');
            
            // Add new columns for storing image as blob
            $table->binary('image_data')->nullable()->after('type');
            $table->string('mime_type', 50)->nullable()->after('image_data');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('memes', function (Blueprint $table) {
            // Restore image_path column
            $table->string('image_path')->nullable()->after('type');
            
            // Drop blob columns
            $table->dropColumn(['image_data', 'mime_type']);
        });
    }
};
