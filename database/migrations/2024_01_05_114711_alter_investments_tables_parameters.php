<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterInvestmentsTablesParameters extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('investments_history', function (Blueprint $table) {
            $table->decimal('amount', 36, 12)->change();
            $table->decimal('bought_at', 36, 12)->change();
        });

        Schema::table('investments', function (Blueprint $table) {
            $table->decimal('amount', 36, 12)->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
