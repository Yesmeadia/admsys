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
            $table->string('apaar_id')->nullable()->after('aadhar_no');
            $table->string('pen_no')->nullable()->after('apaar_id');
            $table->string('adharshila_number')->nullable()->after('pen_no');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('admission_queries', function (Blueprint $table) {
            $table->dropColumn(['apaar_id', 'pen_no', 'adharshila_number']);
        });
    }
};
