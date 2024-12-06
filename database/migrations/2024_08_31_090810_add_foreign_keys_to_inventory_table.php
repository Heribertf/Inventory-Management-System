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
        Schema::table('inventory', function (Blueprint $table) {
            $table->unsignedBigInteger('category')->change();
            $table->unsignedBigInteger('status')->change();

            $table->foreign('category')
                  ->references('category_id')
                  ->on('categories');

            $table->foreign('status')
                  ->references('status_id')
                  ->on('status');   
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('inventory', function (Blueprint $table) {
            $table->dropForeign(['category']);
            $table->dropForeign(['status']);

            $table->integer('category')->change();
            $table->integer('status')->change();
        });
    }
};
