<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement("ALTER TABLE commandes MODIFY COLUMN statut ENUM('en_attente', 'en_production', 'terminee', 'livree') DEFAULT 'en_attente'");
        
        DB::table('commandes')
            ->where('statut', 'terminée')
            ->update(['statut' => 'terminee']);
    }

    public function down(): void
    {
        DB::table('commandes')
            ->where('statut', 'terminee')
            ->update(['statut' => 'terminée']);
            
        DB::statement("ALTER TABLE commandes MODIFY COLUMN statut ENUM('en_attente', 'en_production', 'terminée', 'livrée') DEFAULT 'en_attente'");
    }
};