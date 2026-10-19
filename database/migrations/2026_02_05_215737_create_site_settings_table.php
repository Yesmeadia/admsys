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
        Schema::create('site_settings', function (Blueprint $row) {
            $row->id();
            $row->string('key')->unique();
            $row->text('value')->nullable();
            $row->timestamps();
        });

        // Insert default values
        DB::table('site_settings')->insert([
            ['key' => 'form1_enabled', 'value' => '1', 'created_at' => now(), 'updated_at' => now()],
            ['key' => 'form2_enabled', 'value' => '1', 'created_at' => now(), 'updated_at' => now()],
            ['key' => 'form3_enabled', 'value' => '1', 'created_at' => now(), 'updated_at' => now()],
            ['key' => 'site_name', 'value' => 'H.U.H. MUNDIA PUBLIC SCHOOL', 'created_at' => now(), 'updated_at' => now()],
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('site_settings');
    }
};
