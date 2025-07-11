<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('raw_materials', function (Blueprint $table) {
            $table->id();
            $table->string('nom');
            $table->decimal('prix_unitaire', 10, 2);
            $table->integer('stock_min');
            $table->integer('stock_actuel');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('raw_materials');
    }
};