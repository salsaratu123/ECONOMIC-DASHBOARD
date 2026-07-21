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
        // Buat tabel ports jika belum ada
        if (!Schema::hasTable('ports')) {
            Schema::create('ports', function (Blueprint $table) {
                $table->id();
                $table->string('name');
                $table->string('code')->unique();
                $table->string('country');
                $table->decimal('latitude', 10, 7);
                $table->decimal('longitude', 10, 7);
                $table->enum('status', ['active', 'congested', 'closed'])->default('active');
                $table->timestamps();
            });
        }

        // Buat tabel routes jika belum ada
        if (!Schema::hasTable('routes')) {
            Schema::create('routes', function (Blueprint $table) {
                $table->id();
                $table->string('route_name');
                $table->foreignId('origin_port_id')->constrained('ports')->onDelete('cascade');
                $table->foreignId('destination_port_id')->constrained('ports')->onDelete('cascade');
                $table->integer('estimated_transit_days');
                $table->enum('risk_level', ['low', 'medium', 'high', 'critical'])->default('low');
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('routes');
        Schema::dropIfExists('ports');
    }
};