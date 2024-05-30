<?php

use App\Enums\AnimalAgeTypeEnum;
use App\Enums\AnimalCastrateEnum;
use App\Enums\AnimalGenderEnum;
use App\Enums\AnimalSizeEnum;
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
        Schema::create('animals', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->enum('gender', AnimalGenderEnum::toArrayWithString());
            $table->enum('size', AnimalSizeEnum::toArrayWithString());
            $table->enum('age_type', AnimalAgeTypeEnum::toArrayWithString());
            $table->enum('castrate_type', AnimalCastrateEnum::toArrayWithString());
            $table->text('description')->nullable();
            $table->text('location')->nullable();
            $table->text('tags');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('animals');
    }
};
