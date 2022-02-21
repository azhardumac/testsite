<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAuctionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('auctions', function (Blueprint $table) {
            $table->id();

            $table->decimal('quantity', 18, 8);
            $table->decimal('expected_profit', 18, 8);
            $table->integer('auction_owner');
            $table->integer('auction_buyer')->nullable();
            $table->boolean('auction_status')->comment('1=> Sold, 2=>Unsold');
            $table->tinyInteger('status')->comment('1=> Running Auction, 2=>Withdraw Auction');
            $table->dateTime('auction_completed')->nullable()->comment('Completed date when coin successfully purchased');

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
        Schema::dropIfExists('auctions');
    }
}
