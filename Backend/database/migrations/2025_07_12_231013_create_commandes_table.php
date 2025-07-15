<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('commandes', function (Blueprint $table) {
            $table->id();
            $table->date('date_commande');
            $table->enum('statut', ['en_attente', 'en_production', 'terminée', 'livrée'])->default('en_attente');
            $table->decimal('total', 10, 2);
            $table->text('commentaire')->nullable();
            $table->enum('moyen_paiement', ['espèce', 'virement', 'chèque']);
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('commandes');
    }
};