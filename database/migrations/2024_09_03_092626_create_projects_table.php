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
        Schema::create('projects', function (Blueprint $table) {
            $table->id('project_id');
            $table->foreignId('inventory_id')->constrained('inventory', 'inventory_id');
            $table->date('request_date');
            $table->string('client');
            $table->foreignId('category')->constrained('categories', 'category_id');
            $table->string('model')->nullable();
            $table->string('serial_number')->unique();
            $table->integer('total_counter')->nullable();
            $table->string('ac_manager')->nullable();
            $table->string('priority')->nullable();
            $table->string('tech_name')->nullable();
            $table->date('deadline')->nullable();
            $table->integer('days_left')->nullable();
            $table->string('state')->default('running');
            $table->string('status_page')->nullable();
            $table->foreignId('status')->constrained('status', 'status_id');
            $table->boolean('delete_flag')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('projects');
    }
};
