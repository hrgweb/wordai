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
            $table->integer('group_id')->unsigned()->index();
            $table->string('doc_title')->index()->nullable();
            $table->string('keyword')->nullable();
            $table->string('lsi_terms')->nullable();
            $table->text('domain_protected')->nullable();
            $table->text('article');
            $table->text('spintax')->nullable();
            $table->text('spintax_copy')->nullable();
            $table->text('spin')->nullable();
            $table->text('protected')->nullable();
            $table->string('company')->default('%company%');
            $table->string('city')->default('%city%');
            $table->string('state')->default('%state%');
            $table->string('synonym')->nullable();
            $table->text('citation')->nullable();
            $table->boolean('isUserEdit')->default(0);
            $table->boolean('isEditorEdit')->default(0);
            $table->integer('editor_id')->unsigned();
            $table->boolean('isCsCheckHitMax')->default(0);
            $table->integer('csCounter')->default(5);
            $table->boolean('isRespinHitMax')->default(0);
            $table->integer('respinCounter')->default(5);
            $table->boolean('isProcess')->default(0);
            $table->timestamp('isPublish')->nullable();
            $table->boolean('isArticleApprove')->default(0);
            $table->char('reasonArticleNotAprrove', 20)->nullable();
            $table->text('reasonArticleNotAprroveBody')->nullable();
            $table->integer('hr_spent_editor_edit_article')->unsigned()->default(0);
            $table->integer('min_spent_editor_edit_article')->unsigned()->default(0);
            $table->integer('sec_spent_editor_edit_article')->unsigned()->default(0);
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
