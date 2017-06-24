<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateWordsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('words', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id')->unsigned();
            $table->string('doc_title')->index()->nullable();
            $table->string('keyword')->nullable();
            $table->string('lsi_terms')->nullable();
            $table->string('domain_protected')->nullable();
            $table->text('article');
            $table->string('dom_name')->nullable();
            $table->string('protected')->nullable();
            $table->string('synonyms')->nullable();
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
        Schema::dropIfExists('words');
    }
}
