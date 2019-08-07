<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSkillsCommentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('skills_comments', function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->bigInteger('article_id')->unsigned();
                $table->foreign('article_id')->references('id')->on('skills_articles');
                $table->bigInteger('author_id')->unsigned();
                $table->foreign('author_id')->references('id')->on('skills_autors');
                $table->text('body');
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
        Schema::dropIfExists('skills_comments');
    }
}
