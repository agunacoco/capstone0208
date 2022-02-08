<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateConcertsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('concerts')) {
            Schema::create('concerts', function (Blueprint $table) {
                $table->id();
                $table->foreignId('category_id')->constrained();
                $table->date('startDate');
                $table->date('endDate');
                $table->string('title');
                $table->string('desc');
                $table->longText('content')->nullable();
                $table->string('artist');
                $table->bigInteger('price');
                $table->unsignedBigInteger('remainTicket')->nullable();
                $table->date('openDate');
                $table->date('closeDate');
                $table->string('playTime');
                $table->date('reEndDate');
                $table->timestamps();
            });
        }
        DB::statement('ALTER TABLE concerts ADD FULLTEXT search(title, content)');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('concerts');
    }
}
