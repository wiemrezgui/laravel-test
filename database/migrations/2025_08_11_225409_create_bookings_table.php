<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('bookings', function (Blueprint $table) {
            $table->id();
            
            // Clés étrangères
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('property_id')->constrained()->onDelete('cascade');
            
            // Champs de réservation
            $table->date('start_date');
            $table->date('end_date');
            $table->decimal('total_price', 10, 2);
            $table->enum('status', ['PENDING', 'CONFIRMED', 'CANCELLED'])->default('CONFIRMED');
            $table->text('special_requests')->nullable();
            
            $table->timestamps();
            
            // Index pour les recherches par dates
            $table->index(['start_date', 'end_date']);
            // Contrainte
        });
         // Ajout d'une contrainte check
        DB::statement('ALTER TABLE bookings ADD CONSTRAINT chk_dates CHECK (end_date > start_date)');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bookings');
    }
};
