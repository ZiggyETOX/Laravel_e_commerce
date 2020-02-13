<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->string('ProductName');
            $table->longText('ProductShortDescription')->nullable();
            $table->string('CSProductCategory');
            $table->string('CSProductSubCategory');
            $table->string('TopLevel');
            $table->string('SKU');
            $table->date('NewDate');
            $table->string('Promotion')->nullable();
            $table->double('SAPrice', 8, 2);
            $table->double('BotswanaPrice', 8, 2);
            $table->double('NamibiaPrice', 8, 2);
            // $table->string('ActionIndicator');


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
        Schema::dropIfExists('products');
    }
}
