<?php

use App\Enums\FinancePaymentStatusEnum;
use App\Enums\FinanceTypeEnum;
use App\Models\Animal;
use App\Models\User;
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
        Schema::create('finances', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(User::class)->nullable();
            $table->foreignIdFor(Animal::class)->nullable();
            $table->string('session_id')->nullable();
            $table->text('description')->nullable();
            $table->date('date');
            $table->double('value');
            $table->enum('type', FinanceTypeEnum::toArrayWithString());
            $table->enum('status', FinancePaymentStatusEnum::toArrayWithString())->default(FinancePaymentStatusEnum::UNPAID);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('finances');
    }
};
