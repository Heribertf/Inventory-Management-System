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
        Schema::create('inventory', function (Blueprint $table) {
            $table->id('inventory_id');
            $table->date('collection_date')->nullable();
            $table->string('collected_from')->nullable();
            $table->string('company')->nullable();
            $table->integer('category')->nullable();
            $table->string('model')->nullable();
            $table->string('serial_number')->nullable();
            $table->integer('status')->nullable();
            $table->string('dp_model')->nullable();
            $table->string('dp_serial')->nullable();
            $table->string('cb')->nullable();
            $table->integer('color')->nullable();
            $table->integer('mono_counter')->nullable();
            $table->integer('total')->nullable();
            $table->string('fk')->nullable();
            $table->string('dk')->nullable();
            $table->string('dv')->nullable();
            $table->string('belt')->nullable();
            $table->string('feed')->nullable();
            $table->string('dispatched_to')->nullable();
            $table->date('dispatch_date')->nullable();
            $table->string('warehouse')->nullable();
            $table->string('dp_pf_out')->nullable();
            $table->integer('life_counter')->nullable();
            $table->text('remarks')->nullable();
            $table->text('files')->nullable();
            $table->timestamps();
            $table->tinyInteger('delete_flag')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('inventory');
    }
};
