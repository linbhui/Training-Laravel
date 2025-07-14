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
        Schema::create('team', function (Blueprint $table) {
            $table->id();
            $table->string('name', 128);
            $table->foreignId('ins_id')->references('id')->on('employee');
            $table->foreignId('upd_id')->nullable()->references('id')->on('employee');
            $table->timestamp('ins_datetime')->useCurrent();
            $table->timestamp('upd_datetime')->nullable();
            $table->char('del_flag', 1)->default(0);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('team');
    }
};
