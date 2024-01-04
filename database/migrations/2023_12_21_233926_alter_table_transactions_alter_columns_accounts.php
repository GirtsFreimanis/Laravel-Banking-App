<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterTableTransactionsAlterColumnsAccounts extends Migration
{
    public function up()
    {
        Schema::table('transactions', function (Blueprint $table) {
            $table->bigInteger('account_from')->change();
            $table->bigInteger('account_to')->change();
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
