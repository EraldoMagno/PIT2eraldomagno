<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInvoicesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('invoices', function (Blueprint $table) {
            $table->string('uuid', 36)->primary();
            $table->string('client_id', 36)->index('invoices_client_id_foreign');
            $table->string('invoice_no')->unique('invoices_number_unique');
            $table->date('invoice_date');
            $table->date('due_date');
            $table->integer('status');
            $table->double('discount', 8, 2);
            $table->enum('discount_mode', ['0', '1'])->default('1');
            $table->text('terms');
            $table->text('notes');
            $table->string('currency');
            $table->boolean('recurring');
            $table->boolean('recurring_cycle');
            $table->timestamps();
        });
        // then I add the increment_num "manually"
        DB::statement('ALTER Table invoices add increment_num INTEGER NOT NULL UNIQUE AUTO_INCREMENT');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('invoices');
    }
}
