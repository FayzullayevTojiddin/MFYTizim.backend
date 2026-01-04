<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tasks', function (Blueprint $table) {
            $table->id();

            $table->foreignId('task_category_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->foreignId('neighborood_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->text('description')->nullable();

            $table->string('file')->nullable();

            $table->string('status')->nullable('new');

            $table->decimal('latitude', 10, 7)->nullable();
            $table->decimal('longitude', 10, 7)->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tasks');
    }
};