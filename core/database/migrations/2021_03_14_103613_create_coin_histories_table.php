<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCoinHistoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('coin_histories', function (Blueprint $table) {
            $table->id();

            $table->integer('transaction_id');
            $table->integer('user_id');
            $table->string('type', 10)->comment('Type of transaction like add or less');
            $table->string('stage', 30)->comment('Stage of phase/ICO planning');
            $table->decimal('transaction_rate', 18, 8)->comment('Purchase time rate or price');
            $table->decimal('coin_quantity', 18, 8);
            $table->decimal('amount', 18, 8)->comment('Total price');
            $table->decimal('coin_post_balance', 18, 8);
            $table->boolean('status')->comment('1=> Success, 0=>Not Success');

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
        Schema::dropIfExists('coin_histories');
    }
}
