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
        Schema::create('employees', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('team_id');
            $table->string('email', 128);
            $table->unique(['team_id', 'email']);
            $table->string('first_name', 128);
            $table->string('last_name', 128);
            $table->string('password', 64);
            $table->char('gender', 1)->default(1);
            $table->date('birthday');
            $table->string('address', 256);
            $table->string('avatar', 128);
            $table->unsignedInteger('salary');
            $table->char('position', 1);
            $table->char('status', 1);
            $table->char('type_of_work', 1);
            $table->unsignedBigInteger('ins_id');
            $table->unsignedBigInteger('upd_id')->nullable();
            $table->timestamp('ins_datetime')->useCurrent();
            $table->timestamp('upd_datetime')->nullable();
            $table->char('del_flag', 1)->default(0);
        });
    }


    public function down(): void
    {
        Schema::dropIfExists('employee');
    }
};
