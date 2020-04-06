<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePricesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('prices', function (Blueprint $table) {
            $table->id();
            $table->morphs('priceable');
            $table->unsignedBigInteger('vatrate_id');
            $table->unsignedBigInteger('currency_id');
            $table->bigInteger('display_price')->default(0);
            $table->bigInteger('price_excluding_vat')->default(0);
            $table->bigInteger('price_including_vat')->default(0);
            $table->bigInteger('vat_amount')->default(0);
            $table->timestamp('valid_from')->nullable()->default(null);
            $table->timestamp('valid_till')->nullable()->default(null);
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('vatrate_id')->references('id')->on('vat_rates');
            $table->foreign('currency_id')->references('id')->on('currencies');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('vats');
    }
}
