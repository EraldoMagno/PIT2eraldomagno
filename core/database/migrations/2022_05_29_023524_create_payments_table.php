<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePaymentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->string('uuid', 36)->primary();
            $table->string('invoice_id', 36)->index('payments_invoice_id_foreign');
            $table->date('payment_date');
            $table->double('amount', 15, 2);
            $table->text('notes');
            $table->string('method', 36)->index('payments_method_foreign');
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
        Schema::dropIfExists('payments');
    }
}
