<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProtectedTermsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('protected_terms', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('domain_id')->index()->unsigned();
            $table->integer('user_id')->index()->unsigned();
            $table->string('terms')->index();
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
        Schema::dropIfExists('protected_terms');
    }
}
