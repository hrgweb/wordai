<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDomainDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('domain_details', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id')->unsigned();
            $table->integer('domain_id')->unsigned();
            $table->integer('group_id')->unsigned();
            $table->text('lsi_terms')->nullable();
            $table->text('protected')->nullable();
            $table->string('synonym')->nullable();
            $table->index(['user_id', 'domain_id']);
            $table->unique(['domain_id']);
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
        Schema::dropIfExists('domain_details');
    }
}
