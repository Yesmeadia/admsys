<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('admission_queries', function (Blueprint $table) {
            $table->string('father_photo_path')->nullable()->after('student_photo_path');
            $table->string('mother_photo_path')->nullable()->after('father_photo_path');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('admission_queries', function (Blueprint $table) {
            $table->dropColumn(['father_photo_path', 'mother_photo_path']);
        });
    }
};
