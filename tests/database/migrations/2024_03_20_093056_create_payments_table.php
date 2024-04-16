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
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->string('envelope_code', 5);
            $table->string('file_code', 15)->unique();
            $table->string('payment_code', 10);
            $table->string('relative_files', 30);
            $table->boolean('has_relatives');
            $table->unsignedBigInteger('payment_type_id');
            $table->unsignedBigInteger('beneficiary_id');
            $table->string('description', 255);
            $table->float('total_amount');
            $table->float('deductions');
            $table->float('payment_amount');
            $table->integer('year');
            $table->string('envelope_folder', 10);
            $table->string('filename', 20);
            $table->timestamps();

            //foreign keys
            $table->foreign('payment_type_id')->references('id')->on('payment_types')->onDelete('restrict');
            $table->foreign('beneficiary_id')->references('id')->on('beneficiaries')->onDelete('restrict');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
