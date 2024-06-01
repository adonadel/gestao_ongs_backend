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
        Schema::create('medias', function (Blueprint $table) {
            $table->id();
            $table->string('display_name')->nullable();
            $table->string('filename');
            $table->string('filename_id');
            $table->integer('size');
            $table->integer('width');
            $table->integer('height');
            $table->string('extension', 25);
            $table->text('description')->nullable();
            $table->integer('order')->nullable()->unique();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('medias');
    }
};
