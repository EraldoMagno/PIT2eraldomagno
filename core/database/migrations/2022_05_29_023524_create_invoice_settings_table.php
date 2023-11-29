<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInvoiceSettingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('invoice_settings', function (Blueprint $table) {
            $table->string('uuid', 36)->primary();
            $table->integer('start_number');
            $table->text('terms');
            $table->integer('due_days');
            $table->string('logo');
            $table->enum('show_status', ['0', '1'])->default('1');
            $table->timestamps();
            $table->enum('show_pay_button', ['0', '1'])->default('1');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('invoice_settings');
    }
}
