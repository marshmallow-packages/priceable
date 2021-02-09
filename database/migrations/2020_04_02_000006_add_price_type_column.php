<?php

use Illuminate\Support\Facades\Schema;
use Marshmallow\Priceable\Models\Price;
use Illuminate\Database\Schema\Blueprint;
use Marshmallow\Priceable\Models\PriceType;
use Illuminate\Database\Migrations\Migration;

class AddPriceTypeColumn extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('price_types', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->timestamps();
            $table->softDeletes();
        });

        $price_type = PriceType::create([
            'name' => 'Default',
        ]);

        Schema::table('prices', function (Blueprint $table) {
            $table->unsignedBigInteger('price_type_id')->after('priceable_id')->default(null)->nullable();
            $table->foreign('price_type_id')->references('id')->on('price_types');
        });

        $prices = Price::get();
        foreach ($prices as $price) {
            $price->update([
                'price_type_id' => $price_type->id,
            ]);
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('price_types');
        Schema::table('prices', function (Blueprint $table) {
            $table->dropColumn('price_type_id');
        });
    }
}
