<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('data', function (Blueprint $table) {
            $table->id();
            $table->integer('end_year');
            $table->string('citylng');
            $table->string('citylat');
            $table->integer('intensity');
            $table->string('sector');
            $table->string('topic');
            $table->binary('insight');
            $table->string('swot');
            $table->text('url');
            $table->string('region');
            $table->string('start_year');
            $table->string('impact');
            $table->string('added');
            $table->string('published');
            $table->string('city');
            $table->string('country');
            $table->integer('relevance');
            $table->string('pestle');
            $table->binary('source');
            $table->binary('title');
            $table->integer('Likelihood');
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
        Schema::dropIfExists('data');
    }
};
