<?php

use App\Models\Address;
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
        Schema::create('ngrs', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Address::class)->nullable();
            $table->string('name');
            $table->string('cnpj')->unique();
            $table->string('phone')->nullable();
            $table->text('description');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ngrs');
    }
};
