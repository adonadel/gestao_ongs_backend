<?php

use App\Models\Address;
use App\Models\Media;
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
        Schema::create('people', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Address::class)->nullable();
            $table->foreignIdFor(Media::class, 'profile_picture_id')->nullable();
            $table->string('name', 100);
            $table->string('email', 100)->unique();
            $table->string('cpf_cnpj', 20);
            $table->string('phone', 20)->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('people');
    }
};
