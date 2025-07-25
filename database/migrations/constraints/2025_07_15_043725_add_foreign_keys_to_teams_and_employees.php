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
        Schema::table('teams', function (Blueprint $table) {
            $table->foreign('ins_id')->references('id')->on('employees');
            $table->foreign('upd_id')->references('id')->on('employees');
        });

        Schema::table('employees', function (Blueprint $table) {
            $table->foreign('team_id')->references('id')->on('teams');
            $table->foreign('ins_id')->references('id')->on('employees');
            $table->foreign('upd_id')->references('id')->on('employees');
        });
    }

    public function down(): void
    {
        Schema::table('employees', function (Blueprint $table) {
            $table->dropForeign(['team_id']);
            $table->dropForeign(['ins_id']);
            $table->dropForeign(['upd_id']);
        });

        Schema::table('teams', function (Blueprint $table) {
            $table->dropForeign(['ins_id']);
            $table->dropForeign(['upd_id']);
        });
    }

};
