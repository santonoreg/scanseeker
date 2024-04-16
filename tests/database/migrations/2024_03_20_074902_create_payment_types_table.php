<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('payment_types', function (Blueprint $table) {
            $table->id()->startingValue(1);
            $table->string('type', 15);
        });

        // Insert sample payment types
        DB::table('payment_types')->insert([
            ['type' => 'ΕΝΤΠε'],
            ['type' => 'ΜΙΣΘ-ΜΟΝε'],
            ['type' => 'ΑΚΥΡ-ΕΝΤ'],
            ['type' => 'ΕΝΤΠ'],
            ['type' => 'ΜΙΣΘ-ΜΟΝ'],
            ['type' => 'ΕΝΤ-ΚΡΑΤ'],
            ['type' => 'ΕΝΤΠΠ'],
            ['type' => 'ΔΙΚ'],
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payment_types');
    }
};
