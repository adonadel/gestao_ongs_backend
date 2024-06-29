<?php

use App\Enums\UserStatusEnum;
use App\Enums\UserTypeEnum;
use App\Models\People;
use App\Models\Role;
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
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Role::class);
            $table->foreignIdFor(People::class);
            $table->enum('status', UserStatusEnum::toArrayWithString())->default(UserStatusEnum::ENABLED);
            $table->enum('type', UserTypeEnum::toArrayWithString());
            $table->string('password');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
