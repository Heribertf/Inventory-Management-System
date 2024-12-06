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
        Schema::create('collection_delivery_inventory', function (Blueprint $table) {
            $table->id('inventory_id');
            $table->date('request_collection_date')->nullable();
            $table->date('d_c_date')->nullable()->comment('delivery/collection date');
            $table->string('client_name', 255)->nullable();
            $table->foreignId('company')->constrained('companies', 'company_id')->nullable();
            $table->string('asset_code', 100)->nullable();
            $table->string('model', 100)->nullable();
            $table->string('serial_number', 100)->nullable();
            $table->string('warehouse', 200)->nullable();
            $table->string('location', 255)->nullable();
            $table->string('branches', 300)->nullable();
            $table->foreignId('status')->constrained('status', 'status_id')->nullable();
            $table->string('total_color', 50)->nullable();
            $table->string('total_b_w', 50)->nullable();
            $table->string('accessories', 200)->nullable();
            $table->string('ibt_number', 150)->nullable();
            $table->string('contact', 255)->nullable();
            $table->string('vehicle', 200)->nullable();
            $table->string('messenger', 255)->nullable();
            $table->string('ac_manager', 155)->nullable();
            $table->text('remarks')->nullable();
            $table->text('comments')->nullable();
            $table->string('dn_status', 155)->nullable()->comment('delivery note status');
            $table->string('files', 500)->nullable();
            $table->tinyInteger('delete_flag')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('collection_delivery_inventory');
    }
};
