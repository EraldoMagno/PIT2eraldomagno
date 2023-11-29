<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEstimateItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('estimate_items', function (Blueprint $table) {
            $table->string('uuid', 36)->primary();
            $table->string('estimate_id', 36)->index('estimate_items_estimate_id_foreign');
            $table->string('item_name');
            $table->text('item_description');
            $table->double('quantity', 8, 2);
            $table->double('price', 15, 2);
            $table->string('tax_id', 36)->nullable()->index('estimate_items_tax_id_foreign');
            $table->integer('item_order');
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
        Schema::dropIfExists('estimate_items');
    }
}
