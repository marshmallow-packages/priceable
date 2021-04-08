<?php

use Illuminate\Support\Facades\Schema;
use Marshmallow\Priceable\Models\Price;
use Illuminate\Database\Schema\Blueprint;
use Marshmallow\Priceable\Models\PriceType;
use Illuminate\Database\Migrations\Migration;

class AddCurrencySymbol extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('currencies', function (Blueprint $table) {
            $table->text('symbol', 10)->nullable()->default(NULL)->after('iso_4217');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('currencies', function (Blueprint $table) {
            $table->dropColumn('symbol');
        });
    }
}
