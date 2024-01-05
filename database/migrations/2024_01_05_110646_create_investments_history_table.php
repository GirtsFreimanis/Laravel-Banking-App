<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInvestmentsHistoryTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('investments_history', function (Blueprint $table) {
            $table->id();
            $table->foreignId('account_id')->constrained('accounts');
            $table->foreignId('symbol_id')->constrained('crypto_currencies');
            $table->decimal('amount');
            $table->decimal('bought_at');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {

    }
}
