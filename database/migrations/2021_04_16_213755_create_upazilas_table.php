<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUpazilasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
//        Schema::create('upazilas', function (Blueprint $table) {
//            $table->id();
//            $table->unsignedBigInteger('district_id');
//            $table->string('name');
//            $table->string('bn_name')->nullable();
//            $table->string('url')->nullable();
//            $table->boolean('status')->default(1);
//            $table->timestamps();
//
//            $table->foreign('district_id')
//                ->references('id')
//                ->on('districts')
//                ->onDelete('cascade')
//                ->onUpdate('cascade');
//        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('upazilas');
    }
}
