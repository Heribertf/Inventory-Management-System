<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id('user_id');
            $table->string('firstname', 100);
            $table->string('lastname', 100);
            $table->string('email', 100)->unique();
            $table->string('username', 50)->nullable();
            $table->string('phone', 50)->nullable();
            $table->string('password', 100);
            $table->unsignedBigInteger('role')->nullable();
            $table->tinyInteger('type')->default(2)->comment('type 1 = Admin, type 2 = Normal user');
            $table->timestamp('date_added')->useCurrent();
            $table->timestamp('date_updated')->nullable()->useCurrentOnUpdate();
            $table->tinyInteger('delete_flag')->default(0);
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
