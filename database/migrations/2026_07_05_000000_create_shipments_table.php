<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('shipments', function (Blueprint $table) {
            $table->id();
            $table->string('container_number')->unique();
            $table->string('origin_country');
            $table->string('destination_country');
            $table->string('origin_port');
            $table->string('destination_port');
            $table->string('ship_name');
            $table->date('eta');
            $table->string('status')->default('On Voyage');
            $table->enum('risk_level', ['LOW', 'MEDIUM', 'HIGH'])->default('LOW');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('shipments');
    }
};
