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
        Schema::create('install_unistall_reports', function (Blueprint $table) {
            $table->id('report_id');
            $table->string('customer')->nullable();
            $table->string('model')->nullable();
            $table->string('serial_number')->nullable();
            $table->string('asset_code')->nullable();
            $table->string('location')->nullable();
            $table->date('date')->nullable()->comment('installation/uninstallation date');
            $table->string('technician_name')->nullable();
            $table->text('remarks')->nullable();
            $table->string('report_type');
            $table->foreignId('company')->constrained('companies', 'company_id')->nullable();
            $table->timestamps();
            $table->tinyInteger('delete_flag')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('install_unistall_reports');
    }
};
