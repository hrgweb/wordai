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
            $table->integer('article_type_id')->unsigned();
            $table->integer('domain_id');
            $table->string('doc_title')->index()->nullable();
            $table->string('keyword')->nullable();
            $table->string('lsi_terms')->nullable();
            $table->string('domain_protected')->nullable();
            $table->text('article');
            $table->text('spintax')->nullable();
            $table->text('spintax_copy')->nullable();
            $table->text('spin')->nullable();
            $table->string('protected')->nullable();
            $table->string('synonym')->nullable();
            $table->boolean('isUserEdit')->default(0);
            $table->boolean('isEditorEdit')->default(0);
            $table->integer('editor_id')->unsigned();
            $table->boolean('isCsCheckHitMax')->default(0);
            $table->boolean('isRespinHitMax')->default(0);
            $table->boolean('isProcess')->default(0);
            $table->integer('hr_spent_editor_edit_article')->unsigned()->nullable();
            $table->integer('min_spent_editor_edit_article')->unsigned()->nullable();
            $table->integer('sec_spent_editor_edit_article')->unsigned()->nullable();
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
