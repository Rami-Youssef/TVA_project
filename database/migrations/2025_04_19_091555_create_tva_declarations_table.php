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
        Schema::create('tva_declarations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('entreprise_id')->constrained()->onDelete('cascade');
            $table->enum('type', ['mensuelle', 'trimestrielle', 'annuelle']);
            $table->string('periode');
            $table->decimal('montant', 10, 2);
            $table->date('date_declaration');
            $table->timestamps();

            // Add indexes for frequently queried columns
            $table->index(['type', 'periode']);
            $table->index('date_declaration');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tva_declarations');
    }
};
