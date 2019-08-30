<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

class CreateBooksTable extends Migration
{
    public function up()
    {
        Schema::create('books', function(Blueprint $table) {
            $table->unsignedInteger('author_id');
            $table->string('title')->index();
            $table->string('description');
            $table->timestamps();
            $table->softDeletes();


            $table->foreign('author_id')->references('rowid')->on('authors');
        });
    }

    public function down()
    {
        Schema::drop('books');
    }
}
