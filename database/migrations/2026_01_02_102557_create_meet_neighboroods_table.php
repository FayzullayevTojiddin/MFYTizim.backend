<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('meet_neighboroods', function (Blueprint $table) {
            $table->id();
            
            $table->foreignId('meet_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->foreignId('neighborood_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('meet_neighboroods');
    }
};
