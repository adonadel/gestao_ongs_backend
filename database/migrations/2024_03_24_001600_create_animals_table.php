<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Enums\GenderEnum;
use App\Enums\SizeEnum;
use App\Enums\AgeTypeEnum;

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
            $table->enum('gender', GenderEnum::cases());
            $table->enum('size', SizeEnum::cases());
            $table->enum('age_type', AgeTypeEnum::cases());
            $table->text('description')->nullable();
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
