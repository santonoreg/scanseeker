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
        Schema::create('payment_stats', function (Blueprint $table) {
            $table->id()->startingValue(1);
            $table->string('envelope', 10);
            $table->integer('pdfs');
            $table->integer('total_pages');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payment_stats');
    }
};
